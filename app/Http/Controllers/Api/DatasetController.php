<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Dataset;
use App\Models\DatasetFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use League\Csv\Reader;

class DatasetController extends Controller
{
    public function index(Request $request)
    {
        $query = Dataset::query();

        // Filter publik untuk viewer
        if (auth()->user()->role === 'viewer') {
            $query->where('is_public', true);
        } elseif (auth()->user()->role === 'admin_opd') {
            // Admin OPD hanya lihat dataset sendiri
            $query->where('opd_id', auth()->user()->opd_id);
        }

        // Search
        if ($request->search) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
        }

        // Filter kategori
        if ($request->category) {
            $query->where('category', $request->category);
        }

        $datasets = $query->with(['user', 'opd', 'files'])
                         ->paginate($request->per_page ?? 15);

        return response()->json($datasets);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'description' => 'required|string',
            'category' => 'required|string',
            'file' => 'required|file|mimes:csv,xlsx,json',
            'is_public' => 'boolean',
        ]);

        $file = $request->file('file');
        $fileName = time() . '_' . $file->getClientOriginalName();
        
        // Simpan file
        $path = $file->storeAs('datasets', $fileName, 'public');

        // Parse file untuk ambil headers
        $headers = $this->parseFileHeaders($file);
        $rowCount = $this->countRows($file);

        // Buat dataset
        $dataset = Dataset::create([
            'name' => $request->name,
            'description' => $request->description,
            'category' => $request->category,
            'user_id' => auth()->id(),
            'opd_id' => auth()->user()->opd_id,
            'is_public' => $request->is_public ?? false,
            'row_count' => $rowCount,
            'file_size' => $file->getSize(),
            'published_at' => now(),
        ]);

        // Simpan file info
        DatasetFile::create([
            'dataset_id' => $dataset->id,
            'file_name' => $fileName,
            'file_path' => $path,
            'file_type' => $file->getClientOriginalExtension(),
            'file_size' => $file->getSize(),
            'column_headers' => $headers,
        ]);

        return response()->json([
            'message' => 'Dataset berhasil diupload',
            'dataset' => $dataset->load(['files']),
        ], 201);
    }

    public function show($id)
    {
        $dataset = Dataset::with(['user', 'opd', 'files'])->findOrFail($id);

        // Check permission
        if (!auth()->user()->isAdmin() && $dataset->opd_id !== auth()->user()->opd_id && !$dataset->is_public) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json($dataset);
    }

    public function update(Request $request, $id)
    {
        $dataset = Dataset::findOrFail($id);

        // Check permission
        if (auth()->user()->role === 'admin_opd' && $dataset->opd_id !== auth()->user()->opd_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'name' => 'string',
            'description' => 'string',
            'category' => 'string',
            'is_public' => 'boolean',
        ]);

        $dataset->update($request->only(['name', 'description', 'category', 'is_public']));

        return response()->json([
            'message' => 'Dataset berhasil diupdate',
            'dataset' => $dataset,
        ]);
    }

    public function destroy($id)
    {
        $dataset = Dataset::findOrFail($id);

        // Check permission
        if (auth()->user()->role === 'admin_opd' && $dataset->opd_id !== auth()->user()->opd_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Hapus file
        foreach ($dataset->files as $file) {
            Storage::disk('public')->delete($file->file_path);
        }

        $dataset->delete();

        return response()->json(['message' => 'Dataset berhasil dihapus']);
    }

    public function download($id)
    {
        $dataset = Dataset::findOrFail($id);

        if (!$dataset->is_public && auth()->user()->role === 'viewer') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $file = $dataset->files->first();
        return Storage::disk('public')->download($file->file_path);
    }

    private function parseFileHeaders($file)
    {
        try {
            $csv = Reader::createFromPath($file->path());
            $csv->setHeaderOffset(0);
            $records = $csv->getRecords();
            
            foreach ($records as $record) {
                return array_keys($record);
            }
        } catch (\Exception $e) {
            return [];
        }
    }

    private function countRows($file)
    {
        try {
            $csv = Reader::createFromPath($file->path());
            $csv->setHeaderOffset(0);
            return count($csv->getRecords());
        } catch (\Exception $e) {
            return 0;
        }
    }
}
