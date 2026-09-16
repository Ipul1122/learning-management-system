<?php

namespace App\Http\Controllers\Api\V1\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SuperAdmin\StoreBranchRequest;
use App\Http\Requests\SuperAdmin\UpdateBranchRequest;
use App\Http\Resources\SuperAdmin\BranchResource;
use App\Models\ActivityLog;
use App\Models\Branch;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class BranchApiController extends Controller
{
    /**
     * Tampilkan daftar cabang via REST API.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = Branch::query()->withCount('users');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%")
                    ->orWhere('city', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status') && in_array($request->input('status'), ['active', 'inactive'])) {
            $query->where('is_active', $request->input('status') === 'active');
        }

        $perPage = min((int) $request->input('per_page', 15), 100);
        $branches = $query->latest()->paginate($perPage)->withQueryString();

        return BranchResource::collection($branches);
    }

    /**
     * Simpan cabang baru via REST API.
     */
    public function store(StoreBranchRequest $request): JsonResponse
    {
        $branch = Branch::create($request->validated());

        ActivityLog::record(
            action: 'CREATE',
            description: "API: Membuat cabang baru: {$branch->name} ({$branch->code})",
            target: $branch,
            old: null,
            new: $branch->toArray(),
            branchId: $branch->id
        );

        return (new BranchResource($branch->loadCount('users')))
            ->response()
            ->setStatusCode(201);
    }

    /**
     * Tampilkan detail cabang spesifik.
     */
    public function show(Branch $branch): BranchResource
    {
        return new BranchResource($branch->loadCount('users'));
    }

    /**
     * Perbarui data cabang via REST API.
     */
    public function update(UpdateBranchRequest $request, Branch $branch): BranchResource
    {
        $oldData = $branch->toArray();
        $branch->update($request->validated());
        $newData = $branch->fresh()->toArray();

        ActivityLog::record(
            action: 'UPDATE',
            description: "API: Memperbarui data cabang: {$branch->name} ({$branch->code})",
            target: $branch,
            old: $oldData,
            new: $newData,
            branchId: $branch->id
        );

        return new BranchResource($branch->fresh()->loadCount('users'));
    }

    /**
     * Ubah status keaktifan cabang via API.
     */
    public function toggleStatus(Branch $branch): JsonResponse
    {
        $oldStatus = $branch->is_active;
        $branch->is_active = ! $oldStatus;
        $branch->save();

        $statusText = $branch->is_active ? 'diaktifkan' : 'dinonaktifkan';

        ActivityLog::record(
            action: 'UPDATE',
            description: "API: Mengubah status cabang {$branch->name} menjadi {$statusText}",
            target: $branch,
            old: ['is_active' => $oldStatus],
            new: ['is_active' => $branch->is_active],
            branchId: $branch->id
        );

        return response()->json([
            'message' => "Cabang {$branch->name} berhasil {$statusText}.",
            'data' => new BranchResource($branch),
        ]);
    }

    /**
     * Hapus cabang via REST API.
     */
    public function destroy(Branch $branch): JsonResponse
    {
        if ($branch->users()->exists()) {
            return response()->json([
                'message' => "Tidak dapat menghapus cabang {$branch->name} karena masih memiliki pengguna terkait. Silakan nonaktifkan cabang sebagai gantinya.",
            ], 422);
        }

        $oldData = $branch->toArray();
        $branchName = $branch->name;
        $branch->delete();

        ActivityLog::record(
            action: 'DELETE',
            description: "API: Menghapus cabang: {$branchName}",
            target: null,
            old: $oldData,
            new: null,
            branchId: null
        );

        return response()->json([
            'message' => "Cabang {$branchName} berhasil dihapus.",
        ]);
    }
}
