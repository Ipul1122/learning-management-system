<?php

namespace App\Http\Controllers\Api\V1\AdminCabang;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminCabang\StoreClassRequest;
use App\Http\Requests\AdminCabang\UpdateClassRequest;
use App\Http\Resources\AdminCabang\ClassResource;
use App\Models\ActivityLog;
use App\Models\TrainingClass;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ClassApiController extends Controller
{
    /**
     * Tampilkan daftar kelas cabang via REST API.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $branchId = $request->user()->branch_id;
        abort_unless($branchId, 403, 'User tidak terikat dengan cabang.');

        $query = TrainingClass::query()
            ->where('branch_id', $branchId)
            ->with(['trainer', 'sessions'])
            ->withCount('sessions');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhereHas('trainer', fn ($t) => $t->where('name', 'like', "%{$search}%"));
            });
        }

        if ($request->filled('type') && in_array($request->input('type'), ['offline', 'online', 'hybrid'])) {
            $query->where('type', $request->input('type'));
        }

        if ($request->filled('status') && in_array($request->input('status'), ['draft', 'open', 'ongoing', 'completed', 'cancelled'])) {
            $query->where('status', $request->input('status'));
        }

        $perPage = min((int) $request->input('per_page', 15), 100);
        $classes = $query->latest()->paginate($perPage);

        return ClassResource::collection($classes);
    }

    /**
     * Buat kelas baru via REST API.
     */
    public function store(StoreClassRequest $request): JsonResponse
    {
        $branchId = $request->user()->branch_id;
        abort_unless($branchId, 403, 'User tidak terikat dengan cabang.');

        $data = $request->validated();
        $data['branch_id'] = $branchId;
        $data['slug'] = TrainingClass::generateUniqueSlug($data['title']);

        $class = TrainingClass::create($data);

        ActivityLog::record(
            action: 'CREATE',
            description: "API: Membuat kelas baru: {$class->title} (Tipe: ".strtoupper($class->type).')',
            target: $class,
            old: null,
            new: $class->toArray(),
            branchId: $branchId
        );

        return (new ClassResource($class->load(['trainer', 'sessions'])))
            ->response()
            ->setStatusCode(201);
    }

    /**
     * Tampilkan detail kelas via REST API.
     */
    public function show(Request $request, TrainingClass $class): ClassResource
    {
        $this->authorizeClassAccess($class, $request->user()->branch_id);

        return new ClassResource($class->load(['trainer', 'sessions.creator']));
    }

    /**
     * Perbarui data kelas via REST API.
     */
    public function update(UpdateClassRequest $request, TrainingClass $class): ClassResource
    {
        $branchId = $request->user()->branch_id;
        $this->authorizeClassAccess($class, $branchId);

        $oldData = $class->toArray();
        $data = $request->validated();

        if ($data['title'] !== $class->title) {
            $data['slug'] = TrainingClass::generateUniqueSlug($data['title'], $class->id);
        }

        $class->update($data);
        $newData = $class->fresh()->toArray();

        ActivityLog::record(
            action: 'UPDATE',
            description: "API: Memperbarui data kelas: {$class->title}",
            target: $class,
            old: $oldData,
            new: $newData,
            branchId: $branchId
        );

        return new ClassResource($class->fresh()->load(['trainer', 'sessions']));
    }

    /**
     * Hapus kelas via REST API.
     */
    public function destroy(Request $request, TrainingClass $class): JsonResponse
    {
        $branchId = $request->user()->branch_id;
        $this->authorizeClassAccess($class, $branchId);

        $className = $class->title;
        $oldData = $class->toArray();
        $class->delete();

        ActivityLog::record(
            action: 'DELETE',
            description: "API: Menghapus kelas: {$className}",
            target: null,
            old: $oldData,
            new: null,
            branchId: $branchId
        );

        return response()->json([
            'message' => "Kelas {$className} berhasil dihapus.",
        ]);
    }

    protected function authorizeClassAccess(TrainingClass $class, ?int $branchId): void
    {
        abort_unless(
            $branchId && $class->branch_id === $branchId,
            403,
            'Anda tidak memiliki wewenang mengakses kelas dari cabang lain.'
        );
    }
}
