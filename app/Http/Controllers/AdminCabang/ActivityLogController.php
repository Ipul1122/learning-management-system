<?php

namespace App\Http\Controllers\AdminCabang;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ActivityLogController extends Controller
{
    /**
     * Tampilkan riwayat audit trail log aktivitas internal cabang.
     */
    public function index(Request $request): View
    {
        $branch = $request->user()->branch;
        abort_unless($branch, 403, 'Akun Anda belum diasosiasikan dengan cabang manapun.');

        $query = ActivityLog::query()
            ->where('branch_id', $branch->id)
            ->with(['user.roles']);

        // Filter aksi
        if ($request->filled('action')) {
            $query->where('action', strtoupper($request->input('action')));
        }

        // Filter pengguna internal cabang (admin cabang & trainer cabang)
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->input('user_id'));
        }

        // Filter rentang tanggal
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', Carbon::parse($request->input('date_from')));
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', Carbon::parse($request->input('date_to')));
        }

        // Pencarian deskripsi
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                    ->orWhere('ip_address', 'like', "%{$search}%");
            });
        }

        $logs = $query->latest()->paginate(15)->withQueryString();

        // Daftar pengguna di cabang ini untuk filter dropdown
        $users = User::where('branch_id', $branch->id)
            ->whereHas('roles', function ($q) {
                $q->whereIn('name', ['admin-cabang', 'trainer']);
            })
            ->orderBy('name')
            ->get();

        // Statistik hari ini di cabang ini
        $todayStats = [
            'total' => ActivityLog::where('branch_id', $branch->id)->whereDate('created_at', Carbon::today())->count(),
            'creates' => ActivityLog::where('branch_id', $branch->id)->whereDate('created_at', Carbon::today())->where('action', 'CREATE')->count(),
            'updates' => ActivityLog::where('branch_id', $branch->id)->whereDate('created_at', Carbon::today())->where('action', 'UPDATE')->count(),
            'deletes' => ActivityLog::where('branch_id', $branch->id)->whereDate('created_at', Carbon::today())->where('action', 'DELETE')->count(),
        ];

        return view('admin-cabang.logs.index', compact('logs', 'branch', 'users', 'todayStats'));
    }

    /**
     * Tampilkan detail perubahan nilai lama vs baru untuk modal inspeksi (JSON).
     */
    public function show(Request $request, ActivityLog $log): JsonResponse
    {
        $branch = $request->user()->branch;
        abort_unless($branch && $log->branch_id === $branch->id, 403, 'Anda tidak memiliki hak akses melihat log aktivitas cabang lain.');

        $log->load(['user.roles', 'branch']);

        return response()->json([
            'log' => $log,
            'user_name' => $log->user?->name ?? 'System',
            'user_role' => $log->user?->roles->first()?->name ?? 'None',
            'branch_name' => $branch->name,
            'formatted_time' => $log->created_at?->format('d M Y H:i:s'),
        ]);
    }
}
