<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    public function index(Request $request)
    {
        // Hanya admin portal yang bisa lihat semua user
        if (auth()->user()->role !== 'admin_portal') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $query = User::query();

        if ($request->search) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
        }

        if ($request->role) {
            $query->where('role', $request->role);
        }

        if ($request->is_active !== null) {
            $query->where('is_active', $request->is_active);
        }

        $users = $query->with('opd')->paginate($request->per_page ?? 15);

        return response()->json($users);
    }

    public function show($id)
    {
        if (auth()->user()->role !== 'admin_portal' && auth()->id() !== $id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $user = User::with('opd')->findOrFail($id);
        return response()->json($user);
    }

    public function update(Request $request, $id)
    {
        if (auth()->user()->role !== 'admin_portal' && auth()->id() !== $id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'string',
            'email' => 'email|unique:users,email,' . $id,
            'password' => 'nullable|min:6',
            'role' => auth()->user()->role === 'admin_portal' ? 'in:viewer,admin_opd,admin_portal' : '',
            'is_active' => auth()->user()->role === 'admin_portal' ? 'boolean' : '',
        ]);

        $data = $request->only(['name', 'email']);

        if ($request->password) {
            $data['password'] = Hash::make($request->password);
        }

        if (auth()->user()->role === 'admin_portal') {
            $data['role'] = $request->role ?? $user->role;
            $data['is_active'] = $request->is_active ?? $user->is_active;
        }

        $user->update($data);

        return response()->json([
            'message' => 'User berhasil diupdate',
            'user' => $user,
        ]);
    }

    public function destroy($id)
    {
        if (auth()->user()->role !== 'admin_portal') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        User::findOrFail($id)->delete();

        return response()->json(['message' => 'User berhasil dihapus']);
    }

    public function approveUser($id)
    {
        if (auth()->user()->role !== 'admin_portal') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $user = User::findOrFail($id);
        $user->update(['is_active' => true]);

        return response()->json([
            'message' => 'User berhasil diaktifkan',
            'user' => $user,
        ]);
    }
}
