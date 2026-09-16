<?php

namespace App\Http\Controllers\Api\V1\Trainer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Trainer\RejectGraduationRequest;
use App\Http\Resources\Trainer\GraduationSubmissionResource;
use App\Models\ActivityLog;
use App\Models\GraduationSubmission;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GraduationReviewApiController extends Controller
{
    /**
     * Daftar antrean pengajuan kelulusan via API.
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        $query = GraduationSubmission::whereHas('enrollment.trainingClass', function ($q) use ($user) {
            $q->where('trainer_id', $user->id);
        })->with(['enrollment.user', 'enrollment.trainingClass.branch', 'trainer']);

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        $submissions = $query->latest()->paginate(15);

        return response()->json([
            'data' => GraduationSubmissionResource::collection($submissions),
            'meta' => [
                'current_page' => $submissions->currentPage(),
                'last_page' => $submissions->lastPage(),
                'total' => $submissions->total(),
            ],
        ]);
    }

    /**
     * Detail pengajuan kelulusan peserta via API.
     */
    public function show(Request $request, GraduationSubmission $submission): JsonResponse
    {
        $user = $request->user();
        abort_unless(
            $submission->enrollment?->trainingClass?->trainer_id === $user->id,
            403,
            'Akses ditolak.'
        );

        $submission->load(['enrollment.user', 'enrollment.trainingClass.branch', 'trainer']);

        return response()->json([
            'submission' => new GraduationSubmissionResource($submission),
            'attendances' => $submission->enrollment->attendances()->with('session')->get(),
            'quiz_attempts' => $submission->enrollment->user->quizAttempts()
                ->whereIn('quiz_id', $submission->enrollment->trainingClass->quizzes()->pluck('id'))
                ->get(),
        ]);
    }

    /**
     * Setujui kelulusan peserta via API.
     */
    public function approve(Request $request, GraduationSubmission $submission): JsonResponse
    {
        $user = $request->user();
        abort_unless(
            $submission->enrollment?->trainingClass?->trainer_id === $user->id,
            403,
            'Akses ditolak.'
        );

        $enrollment = $submission->enrollment;
        $student = $enrollment->user;
        $class = $enrollment->trainingClass;

        $certificateNumber = $submission->certificate_number ?: $submission->generateCertificateNumber();

        $submission->update([
            'status' => 'approved',
            'trainer_id' => $user->id,
            'reviewed_at' => now(),
            'certificate_number' => $certificateNumber,
            'qr_verification_code' => route('certificates.verify', $certificateNumber),
            'trainer_feedback' => $request->input('trainer_feedback', 'Disetujui.'),
        ]);

        $enrollment->update(['status' => 'graduated']);

        ActivityLog::record(
            action: 'APPROVE_GRADUATION',
            description: "API: Trainer {$user->name} menyetujui kelulusan {$student->name} pada kelas '{$class->title}'",
            target: $submission,
            old: null,
            new: $submission->fresh()->toArray(),
            branchId: $class->branch_id
        );

        return response()->json([
            'message' => 'Kelulusan berhasil disetujui.',
            'submission' => new GraduationSubmissionResource($submission->fresh()),
        ]);
    }

    /**
     * Tolak kelulusan peserta via API.
     */
    public function reject(RejectGraduationRequest $request, GraduationSubmission $submission): JsonResponse
    {
        $user = $request->user();
        abort_unless(
            $submission->enrollment?->trainingClass?->trainer_id === $user->id,
            403,
            'Akses ditolak.'
        );

        $enrollment = $submission->enrollment;
        $student = $enrollment->user;
        $class = $enrollment->trainingClass;

        $submission->update([
            'status' => 'rejected',
            'trainer_id' => $user->id,
            'reviewed_at' => now(),
            'trainer_feedback' => $request->input('trainer_feedback'),
        ]);

        $enrollment->update(['status' => 'rejected']);

        ActivityLog::record(
            action: 'REJECT_GRADUATION',
            description: "API: Trainer {$user->name} menolak kelulusan {$student->name} pada kelas '{$class->title}'",
            target: $submission,
            old: null,
            new: $submission->fresh()->toArray(),
            branchId: $class->branch_id
        );

        return response()->json([
            'message' => 'Pengajuan kelulusan ditolak.',
            'submission' => new GraduationSubmissionResource($submission->fresh()),
        ]);
    }
}
