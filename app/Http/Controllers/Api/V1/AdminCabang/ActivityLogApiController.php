<?php

namespace App\Http\Controllers\Api\V1\AdminCabang;

use App\Http\Controllers\Controller;
use App\Http\Resources\AdminCabang\ActivityLogResource;
use App\Models\ActivityLog;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ActivityLogApiController extends Controller
{
    /**
     * Tampilkan riwayat log aktivitas internal cabang via REST API.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $branch = $request->user()->branch;
        abort_unless($branch, 403, 'Akun Anda belum diasosiasikan dengan cabang.');

        $query = ActivityLog::query()
            ->where('branch_id', $branch->id)
            ->with(['user.roles', 'branch']);

        if ($request->filled('action')) {
            $query->where('action', strtoupper($request->input('action')));
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->input('user_id'));
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', Carbon::parse($request->input('date_from')));
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', Carbon::parse($request->input('date_to')));
        }

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                    ->orWhere('ip_address', 'like', "%{$search}%");
            });
        }

        $perPage = min((int) $request->input('per_page', 20), 100);
        $logs = $query->latest()->paginate($perPage);

        return ActivityLogResource::collection($logs);
    }

    /**
     * Tampilkan detail satu log aktivitas cabang tertentu.
     */
    public function show(Request $request, ActivityLog $log): ActivityLogResource
    {
        $branch = $request->user()->branch;
        abort_unless($branch && $log->branch_id === $branch->id, 403, 'Anda tidak memiliki hak akses melihat log aktivitas cabang lain.');

        return new ActivityLogResource($log->load(['user.roles', 'branch']));
    }
}
