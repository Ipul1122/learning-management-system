<?php

namespace App\Http\Controllers\Api\V1\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SuperAdmin\StoreAdminCabangRequest;
use App\Http\Requests\SuperAdmin\UpdateAdminCabangRequest;
use App\Http\Resources\SuperAdmin\AdminCabangResource;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Hash;

class AdminCabangApiController extends Controller
{
    /**
     * Tampilkan daftar Admin Cabang via REST API.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = User::adminCabangs()->with('branch');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone_number', 'like', "%{$search}%");
            });
        }

        if ($request->filled('branch_id')) {
            $query->where('branch_id', $request->input('branch_id'));
        }

        if ($request->filled('status') && in_array($request->input('status'), ['active', 'inactive'])) {
            $query->where('status', $request->input('status'));
        }

        $perPage = min((int) $request->input('per_page', 15), 100);
        $admins = $query->latest()->paginate($perPage)->withQueryString();

        return AdminCabangResource::collection($admins);
    }

    /**
     * Daftarkan akun Admin Cabang baru via REST API.
     */
    public function store(StoreAdminCabangRequest $request): JsonResponse
    {
        $data = $request->validated();
        $data['password'] = Hash::make($data['password']);

        $admin = User::create($data);
        $admin->assignRole('admin-cabang');

        ActivityLog::record(
            action: 'CREATE',
            description: "API: Membuat akun Admin Cabang: {$admin->name} ({$admin->email})",
            target: $admin,
            old: null,
            new: $admin->only(['id', 'name', 'email', 'branch_id', 'status']),
            branchId: $admin->branch_id
        );

        return (new AdminCabangResource($admin->load('branch')))
            ->response()
            ->setStatusCode(201);
    }

    /**
     * Tampilkan detail akun Admin Cabang.
     */
    public function show(User $admin): AdminCabangResource
    {
        abort_unless($admin->hasRole('admin-cabang'), 404);

        return new AdminCabangResource($admin->load('branch'));
    }

    /**
     * Perbarui data akun Admin Cabang via REST API.
     */
    public function update(UpdateAdminCabangRequest $request, User $admin): AdminCabangResource
    {
        abort_unless($admin->hasRole('admin-cabang'), 404);

        $oldData = $admin->only(['name', 'email', 'branch_id', 'phone_number', 'status']);
        $data = $request->validated();

        if (! empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $admin->update($data);
        $newData = $admin->fresh()->only(['name', 'email', 'branch_id', 'phone_number', 'status']);

        ActivityLog::record(
            action: 'UPDATE',
            description: "API: Memperbarui akun Admin Cabang: {$admin->name} ({$admin->email})",
            target: $admin,
            old: $oldData,
            new: $newData,
            branchId: $admin->branch_id
        );

        return new AdminCabangResource($admin->fresh()->load('branch'));
    }

    /**
     * Ubah status keaktifan akun Admin Cabang via API.
     */
    public function toggleStatus(User $admin): JsonResponse
    {
        abort_unless($admin->hasRole('admin-cabang'), 404);

        $oldStatus = $admin->status;
        $newStatus = $oldStatus === 'active' ? 'inactive' : 'active';

        $admin->status = $newStatus;
        $admin->save();

        $statusText = $newStatus === 'active' ? 'diaktifkan' : 'dinonaktifkan';

        ActivityLog::record(
            action: 'UPDATE',
            description: "API: Mengubah status akun Admin Cabang {$admin->name} menjadi {$statusText}",
            target: $admin,
            old: ['status' => $oldStatus],
            new: ['status' => $newStatus],
            branchId: $admin->branch_id
        );

        return response()->json([
            'message' => "Akun {$admin->name} berhasil {$statusText}.",
            'data' => new AdminCabangResource($admin->load('branch')),
        ]);
    }

    /**
     * Hapus akun Admin Cabang via REST API.
     */
    public function destroy(User $admin): JsonResponse
    {
        abort_unless($admin->hasRole('admin-cabang'), 404);

        $adminName = $admin->name;
        $oldData = $admin->only(['id', 'name', 'email', 'branch_id']);
        $admin->delete();

        ActivityLog::record(
            action: 'DELETE',
            description: "API: Menghapus akun Admin Cabang: {$adminName}",
            target: null,
            old: $oldData,
            new: null,
            branchId: $oldData['branch_id']
        );

        return response()->json([
            'message' => "Akun Admin Cabang {$adminName} berhasil dihapus.",
        ]);
    }
}
