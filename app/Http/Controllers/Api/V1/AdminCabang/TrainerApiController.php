<?php

namespace App\Http\Controllers\Api\V1\AdminCabang;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminCabang\StoreTrainerRequest;
use App\Http\Requests\AdminCabang\UpdateTrainerRequest;
use App\Http\Resources\AdminCabang\TrainerResource;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Hash;

class TrainerApiController extends Controller
{
    /**
     * Tampilkan daftar trainer cabang via REST API.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $branchId = $request->user()->branch_id;
        abort_unless($branchId, 403, 'User tidak terikat dengan cabang.');

        $query = User::trainers()
            ->where('branch_id', $branchId)
            ->withCount('assignedClasses');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone_number', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status') && in_array($request->input('status'), ['active', 'inactive'])) {
            $query->where('status', $request->input('status'));
        }

        $perPage = min((int) $request->input('per_page', 15), 100);
        $trainers = $query->latest()->paginate($perPage)->withQueryString();

        return TrainerResource::collection($trainers);
    }

    /**
     * Daftarkan trainer baru untuk cabang ini via API.
     */
    public function store(StoreTrainerRequest $request): JsonResponse
    {
        $branchId = $request->user()->branch_id;
        abort_unless($branchId, 403, 'User tidak terikat dengan cabang.');

        $data = $request->validated();
        $data['password'] = Hash::make($data['password']);
        $data['branch_id'] = $branchId;

        $trainer = User::create($data);
        $trainer->assignRole('trainer');

        ActivityLog::record(
            action: 'CREATE',
            description: "API: Mendaftarkan trainer baru: {$trainer->name} ({$trainer->email})",
            target: $trainer,
            old: null,
            new: $trainer->only(['id', 'name', 'email', 'branch_id', 'status']),
            branchId: $branchId
        );

        return (new TrainerResource($trainer->loadCount('assignedClasses')))
            ->response()
            ->setStatusCode(201);
    }

    /**
     * Tampilkan detail trainer.
     */
    public function show(Request $request, User $trainer): TrainerResource
    {
        $this->authorizeTrainerAccess($trainer, $request->user()->branch_id);

        return new TrainerResource($trainer->loadCount('assignedClasses'));
    }

    /**
     * Perbarui data trainer via API.
     */
    public function update(UpdateTrainerRequest $request, User $trainer): TrainerResource
    {
        $branchId = $request->user()->branch_id;
        $this->authorizeTrainerAccess($trainer, $branchId);

        $oldData = $trainer->only(['name', 'email', 'phone_number', 'status']);
        $data = $request->validated();

        if (! empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $trainer->update($data);
        $newData = $trainer->fresh()->only(['name', 'email', 'phone_number', 'status']);

        ActivityLog::record(
            action: 'UPDATE',
            description: "API: Memperbarui profil trainer: {$trainer->name}",
            target: $trainer,
            old: $oldData,
            new: $newData,
            branchId: $branchId
        );

        return new TrainerResource($trainer->fresh()->loadCount('assignedClasses'));
    }

    /**
     * Ubah status keaktifan trainer via API.
     */
    public function toggleStatus(Request $request, User $trainer): JsonResponse
    {
        $branchId = $request->user()->branch_id;
        $this->authorizeTrainerAccess($trainer, $branchId);

        $oldStatus = $trainer->status;
        $newStatus = $oldStatus === 'active' ? 'inactive' : 'active';

        $trainer->status = $newStatus;
        $trainer->save();

        $statusText = $newStatus === 'active' ? 'diaktifkan' : 'dinonaktifkan';

        ActivityLog::record(
            action: 'UPDATE',
            description: "API: Mengubah status trainer {$trainer->name} menjadi {$statusText}",
            target: $trainer,
            old: ['status' => $oldStatus],
            new: ['status' => $newStatus],
            branchId: $branchId
        );

        return response()->json([
            'message' => "Status trainer {$trainer->name} berhasil {$statusText}.",
            'data' => new TrainerResource($trainer->loadCount('assignedClasses')),
        ]);
    }

    /**
     * Hapus trainer via API.
     */
    public function destroy(Request $request, User $trainer): JsonResponse
    {
        $branchId = $request->user()->branch_id;
        $this->authorizeTrainerAccess($trainer, $branchId);

        if ($trainer->assignedClasses()->exists()) {
            return response()->json([
                'message' => "Tidak dapat menghapus trainer {$trainer->name} karena masih memiliki kelas yang diampu. Silakan nonaktifkan akun sebagai gantinya.",
            ], 422);
        }

        $trainerName = $trainer->name;
        $oldData = $trainer->only(['id', 'name', 'email', 'branch_id']);
        $trainer->delete();

        ActivityLog::record(
            action: 'DELETE',
            description: "API: Menghapus akun trainer: {$trainerName}",
            target: null,
            old: $oldData,
            new: null,
            branchId: $branchId
        );

        return response()->json([
            'message' => "Akun trainer {$trainerName} berhasil dihapus.",
        ]);
    }

    protected function authorizeTrainerAccess(User $trainer, ?int $branchId): void
    {
        abort_unless(
            $trainer->hasRole('trainer') && $branchId && $trainer->branch_id === $branchId,
            403,
            'Anda tidak memiliki wewenang mengakses trainer dari cabang lain.'
        );
    }
}
