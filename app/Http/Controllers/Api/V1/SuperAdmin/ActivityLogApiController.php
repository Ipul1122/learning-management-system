<?php

namespace App\Http\Controllers\Api\V1\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Http\Resources\SuperAdmin\ActivityLogResource;
use App\Models\ActivityLog;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ActivityLogApiController extends Controller
{
    /**
     * Tampilkan riwayat log aktivitas via REST API.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = ActivityLog::query()->with(['user.roles', 'branch']);

        if ($request->filled('action')) {
            $query->where('action', strtoupper($request->input('action')));
        }

        if ($request->filled('branch_id')) {
            $query->where('branch_id', $request->input('branch_id'));
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
        $logs = $query->latest()->paginate($perPage)->withQueryString();

        return ActivityLogResource::collection($logs);
    }

    /**
     * Tampilkan detail satu log aktivitas spesifik.
     */
    public function show(ActivityLog $log): ActivityLogResource
    {
        return new ActivityLogResource($log->load(['user.roles', 'branch']));
    }
}
