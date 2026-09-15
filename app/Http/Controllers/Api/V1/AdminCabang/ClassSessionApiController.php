<?php

namespace App\Http\Controllers\Api\V1\AdminCabang;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminCabang\StoreClassSessionRequest;
use App\Http\Requests\AdminCabang\UpdateClassSessionRequest;
use App\Http\Resources\AdminCabang\ClassSessionResource;
use App\Models\ActivityLog;
use App\Models\ClassSession;
use App\Models\TrainingClass;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ClassSessionApiController extends Controller
{
    /**
     * Tampilkan seluruh sesi pada kelas tertentu via API.
     */
    public function index(Request $request, TrainingClass $class): AnonymousResourceCollection
    {
        $this->authorizeClassAccess($class, $request->user()->branch_id);

        return ClassSessionResource::collection($class->sessions);
    }

    /**
     * Tambahkan sesi baru ke kelas via API.
     */
    public function store(StoreClassSessionRequest $request, TrainingClass $class): JsonResponse
    {
        $this->authorizeClassAccess($class, $request->user()->branch_id);

        $data = $request->validated();
        $data['class_id'] = $class->id;
        $data['minute_duration'] = $data['jp_duration'] * 45;
        $data['created_by_user_id'] = $request->user()->id;

        $session = ClassSession::create($data);

        ActivityLog::record(
            action: 'CREATE',
            description: "API: Menambahkan sesi pertemuan: {$session->title} ({$session->jp_duration} JP) pada kelas {$class->title}",
            target: $session,
            old: null,
            new: $session->toArray(),
            branchId: $class->branch_id
        );

        return (new ClassSessionResource($session))
            ->response()
            ->setStatusCode(201);
    }

    /**
     * Tampilkan detail sesi tertentu via API.
     */
    public function show(Request $request, TrainingClass $class, ClassSession $session): ClassSessionResource
    {
        $this->authorizeClassAccess($class, $request->user()->branch_id);
        abort_unless($session->class_id === $class->id, 404);

        return new ClassSessionResource($session);
    }

    /**
     * Perbarui data sesi pertemuan via API.
     */
    public function update(UpdateClassSessionRequest $request, TrainingClass $class, ClassSession $session): ClassSessionResource
    {
        $this->authorizeClassAccess($class, $request->user()->branch_id);
        abort_unless($session->class_id === $class->id, 404);

        $oldData = $session->toArray();
        $data = $request->validated();
        $data['minute_duration'] = $data['jp_duration'] * 45;

        $session->update($data);
        $newData = $session->fresh()->toArray();

        ActivityLog::record(
            action: 'UPDATE',
            description: "API: Memperbarui sesi pertemuan: {$session->title} pada kelas {$class->title}",
            target: $session,
            old: $oldData,
            new: $newData,
            branchId: $class->branch_id
        );

        return new ClassSessionResource($session->fresh());
    }

    /**
     * Hapus sesi pertemuan via API.
     */
    public function destroy(Request $request, TrainingClass $class, ClassSession $session): JsonResponse
    {
        $this->authorizeClassAccess($class, $request->user()->branch_id);
        abort_unless($session->class_id === $class->id, 404);

        $sessionTitle = $session->title;
        $oldData = $session->toArray();
        $session->delete();

        ActivityLog::record(
            action: 'DELETE',
            description: "API: Menghapus sesi pertemuan: {$sessionTitle} dari kelas {$class->title}",
            target: null,
            old: $oldData,
            new: null,
            branchId: $class->branch_id
        );

        return response()->json([
            'message' => "Sesi pertemuan {$sessionTitle} berhasil dihapus.",
        ]);
    }

    protected function authorizeClassAccess(TrainingClass $class, ?int $branchId): void
    {
        abort_unless(
            $branchId && $class->branch_id === $branchId,
            403,
            'Anda tidak memiliki wewenang mengelola sesi kelas dari cabang lain.'
        );
    }
}
