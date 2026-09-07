<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Dataset;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        $stats = [
            'total_datasets' => 0,
            'total_records' => 0,
            'total_opds' => 0,
            'total_users' => 0,
        ];

        if ($user->role === 'admin_portal') {
            $stats['total_datasets'] = Dataset::count();
            $stats['total_records'] = Dataset::sum('row_count');
            $stats['total_opds'] = \App\Models\OPD::count();
            $stats['total_users'] = \App\Models\User::count();
        } elseif ($user->role === 'admin_opd') {
            $stats['total_datasets'] = Dataset::where('opd_id', $user->opd_id)->count();
            $stats['total_records'] = Dataset::where('opd_id', $user->opd_id)->sum('row_count');
        }

        $recentDatasets = Dataset::query();

        if ($user->role === 'admin_portal') {
            $recentDatasets = $recentDatasets->latest()->limit(5)->get();
        } elseif ($user->role === 'admin_opd') {
            $recentDatasets = $recentDatasets->where('opd_id', $user->opd_id)->latest()->limit(5)->get();
        } else {
            $recentDatasets = $recentDatasets->where('is_public', true)->latest()->limit(5)->get();
        }

        return response()->json([
            'stats' => $stats,
            'recent_datasets' => $recentDatasets,
        ]);
    }
}
