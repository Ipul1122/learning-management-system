<?php

namespace App\Http\Controllers\Api\V1\Peserta;

use App\Http\Controllers\Controller;
use App\Http\Requests\Peserta\EnrollClassRequest;
use App\Http\Resources\Peserta\EnrollmentResource;
use App\Models\ActivityLog;
use App\Models\ClassEnrollment;
use App\Models\TrainingClass;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClassCatalogApiController extends Controller
{
    /**
     * Katalog kelas pelatihan via API.
     */
    public function index(Request $request): JsonResponse
    {
        $query = TrainingClass::whereIn('status', ['open', 'ongoing'])
            ->with(['branch', 'trainer']);

        if ($request->filled('branch_id')) {
            $query->where('branch_id', $request->input('branch_id'));
        }

        if ($request->filled('type')) {
            $query->where('type', $request->input('type'));
        }

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $perPage = min((int) $request->input('per_page', 12), 50);
        $classes = $query->latest()->paginate($perPage);

        return response()->json($classes);
    }

    /**
     * Detail kelas via API.
     */
    public function show(Request $request, TrainingClass $class): JsonResponse
    {
        $class->load(['branch', 'trainer', 'sessions', 'quizzes']);

        $enrollment = ClassEnrollment::where('class_id', $class->id)
            ->where('user_id', $request->user()->id)
            ->first();

        return response()->json([
            'class' => $class,
            'my_enrollment' => $enrollment ? new EnrollmentResource($enrollment) : null,
            'is_enrolled' => ! is_null($enrollment),
        ]);
    }

    /**
     * Pendaftaran kelas dengan proteksi lockForUpdate via API.
     */
    public function enroll(EnrollClassRequest $request, TrainingClass $class): JsonResponse
    {
        $user = $request->user();
        $mode = $request->input('attendance_mode');

        if ($class->isOffline() && $mode !== 'offline') {
            return response()->json(['message' => 'Kelas offline hanya menerima kehadiran fisik.'], 422);
        }
        if ($class->isOnline() && $mode !== 'online') {
            return response()->json(['message' => 'Kelas online hanya menerima kehadiran daring.'], 422);
        }

        $enrollment = DB::transaction(function () use ($class, $user, $mode) {
            $lockedClass = TrainingClass::where('id', $class->id)->lockForUpdate()->firstOrFail();

            abort_unless(
                in_array($lockedClass->status, ['open', 'ongoing']),
                422,
                'Kelas ini tidak sedang menerima pendaftaran baru.'
            );

            $alreadyEnrolled = ClassEnrollment::where('class_id', $lockedClass->id)
                ->where('user_id', $user->id)
                ->exists();

            abort_if(
                $alreadyEnrolled,
                422,
                'Anda sudah terdaftar pada kelas pelatihan ini.'
            );

            if ($mode === 'offline') {
                abort_if(
                    $lockedClass->isFullOffline(),
                    422,
                    'Mohon maaf, kuota kursi fisik offline (maksimal 40 orang) untuk kelas ini telah penuh.'
                );
                $lockedClass->increment('enrolled_offline');
            } else {
                abort_if(
                    $lockedClass->isFullOnline(),
                    422,
                    'Mohon maaf, kuota peserta daring kelas ini telah penuh.'
                );
                $lockedClass->increment('enrolled_online');
            }

            return ClassEnrollment::create([
                'class_id' => $lockedClass->id,
                'user_id' => $user->id,
                'attendance_mode' => $mode,
                'accumulated_minutes' => 0,
                'accumulated_jp' => 0.0,
                'status' => 'enrolled',
                'enrolled_at' => now(),
            ]);
        });

        ActivityLog::record(
            action: 'ENROLL',
            description: "API: Peserta {$user->name} mendaftar kelas '{$class->title}'",
            target: $enrollment,
            old: null,
            new: $enrollment->toArray(),
            branchId: $class->branch_id
        );

        return (new EnrollmentResource($enrollment->load('trainingClass')))
            ->response()
            ->setStatusCode(201);
    }
}
