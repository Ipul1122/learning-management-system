<?php

namespace App\Http\Controllers\Api\V1\Peserta;

use App\Http\Controllers\Controller;
use App\Http\Resources\Peserta\EnrollmentResource;
use App\Models\ActivityLog;
use App\Models\ClassEnrollment;
use App\Models\ClassSession;
use App\Models\SessionAttendance;
use App\Models\TrainingClass;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StudyRoomApiController extends Controller
{
    /**
     * Daftar kelas yang diikuti peserta via API.
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        $enrollments = ClassEnrollment::where('user_id', $user->id)
            ->with(['trainingClass.branch', 'trainingClass.trainer', 'attendances'])
            ->latest()
            ->paginate(15);

        return response()->json([
            'enrollments' => EnrollmentResource::collection($enrollments),
            'total_accumulated_minutes' => (int) $user->enrollments()->sum('accumulated_minutes'),
            'total_accumulated_jp' => round($user->enrollments()->sum('accumulated_minutes') / 45, 1),
        ]);
    }

    /**
     * Ruang belajar kelas via API.
     */
    public function show(Request $request, TrainingClass $class): JsonResponse
    {
        $user = $request->user();

        $enrollment = ClassEnrollment::where('class_id', $class->id)
            ->where('user_id', $user->id)
            ->firstOrFail();

        $class->load([
            'branch',
            'trainer',
            'sessions',
            'quizzes.questions',
        ]);

        $attendances = SessionAttendance::where('enrollment_id', $enrollment->id)->get();

        return response()->json([
            'class' => $class,
            'enrollment' => new EnrollmentResource($enrollment),
            'attendances' => $attendances,
        ]);
    }

    /**
     * Masuk Zoom & presensi via API.
     */
    public function joinZoom(Request $request, TrainingClass $class, ClassSession $session): JsonResponse
    {
        $user = $request->user();

        $enrollment = ClassEnrollment::where('class_id', $class->id)
            ->where('user_id', $user->id)
            ->firstOrFail();

        abort_unless(
            $session->class_id === $class->id,
            404,
            'Sesi pertemuan tidak ditemukan pada kelas ini.'
        );

        if (empty($session->zoom_url)) {
            return response()->json(['message' => 'Tautan Zoom belum disiapkan oleh Trainer.'], 422);
        }

        $attendance = SessionAttendance::firstOrCreate(
            [
                'enrollment_id' => $enrollment->id,
                'session_id' => $session->id,
            ],
            [
                'joined_zoom_at' => now(),
                'minutes_earned' => $session->minute_duration,
                'is_verified' => false,
            ]
        );

        if ($attendance->wasRecentlyCreated) {
            $enrollment->addMinutes($session->minute_duration);

            ActivityLog::record(
                action: 'ATTEND_ZOOM',
                description: "API: Peserta {$user->name} bergabung ke live session Zoom sesi #{$session->session_order}",
                target: $attendance,
                old: null,
                new: $attendance->toArray(),
                branchId: $class->branch_id
            );
        }

        return response()->json([
            'message' => 'Presensi Zoom berhasil dicatat.',
            'zoom_url' => $session->zoom_url,
            'attendance' => $attendance,
            'enrollment' => new EnrollmentResource($enrollment->fresh()),
        ]);
    }
}
