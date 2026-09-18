<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Branch;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ActivityLogController extends Controller
{
    /**
     * Tampilkan audit trail log aktivitas global bagi Super Admin.
     */
    public function index(Request $request): View
    {
        $query = ActivityLog::query()->with(['user.roles', 'branch']);

        // Filter tipe aksi
        if ($request->filled('action')) {
            $query->where('action', strtoupper($request->input('action')));
        }

        // Filter cabang
        if ($request->filled('branch_id')) {
            $query->where('branch_id', $request->input('branch_id'));
        }

        // Filter user
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

        // Pencarian deskripsi atau IP
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                    ->orWhere('ip_address', 'like', "%{$search}%");
            });
        }

        $logs = $query->latest()->paginate(15)->withQueryString();

        $branches = Branch::orderBy('name')->get();
        $users = User::whereHas('roles', function ($q) {
            $q->whereIn('name', ['super-admin', 'admin-cabang', 'trainer']);
        })->orderBy('name')->get();

        // Statistik Aksi Hari Ini
        $todayStats = [
            'total' => ActivityLog::whereDate('created_at', Carbon::today())->count(),
            'creates' => ActivityLog::whereDate('created_at', Carbon::today())->where('action', 'CREATE')->count(),
            'updates' => ActivityLog::whereDate('created_at', Carbon::today())->where('action', 'UPDATE')->count(),
            'deletes' => ActivityLog::whereDate('created_at', Carbon::today())->where('action', 'DELETE')->count(),
        ];

        return view('super-admin.logs.index', compact('logs', 'branches', 'users', 'todayStats'));
    }

    /**
     * Mengembalikan data detail log dalam format JSON untuk modal inspeksi perubahan.
     */
    public function show(ActivityLog $log): JsonResponse
    {
        $log->load(['user', 'branch']);

        return response()->json([
            'log' => $log,
            'user_name' => $log->user?->name ?? 'System',
            'user_role' => $log->user?->roles->first()?->name ?? 'None',
            'branch_name' => $log->branch?->name ?? 'Global / Pusat',
            'target_entity_label' => $log->target_entity_label,
            'formatted_time' => $log->formatted_created_at,
            'formatted_ip' => $log->formatted_ip_address,
        ]);
    }
}
