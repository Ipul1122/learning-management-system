<?php

namespace App\Http\Controllers\Api\V1\Trainer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Trainer\UpdateZoomSessionRequest;
use App\Models\ActivityLog;
use App\Models\ClassSession;
use App\Models\TrainingClass;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ClassScheduleApiController extends Controller
{
    /**
     * Tampilkan daftar kelas yang diampu trainer via API.
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();
        $branch = $user->branch;
        abort_unless($branch, 403, 'Akun Anda belum diasosiasikan dengan cabang.');

        $classes = TrainingClass::where('branch_id', $branch->id)
            ->where('trainer_id', $user->id)
            ->with(['sessions', 'quizzes'])
            ->withCount(['sessions', 'quizzes'])
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return response()->json($classes);
    }

    /**
     * Detail kelas dan silabus jadwal via API.
     */
    public function show(Request $request, TrainingClass $class): JsonResponse
    {
        $user = $request->user();
        $this->authorizeClassAccess($class, $user);

        $class->load(['sessions', 'quizzes.questions']);

        return response()->json([
            'data' => $class,
        ]);
    }

    /**
     * Akses cepat perbarui tautan Zoom via API.
     */
    public function updateZoom(UpdateZoomSessionRequest $request, TrainingClass $class, ClassSession $session): JsonResponse
    {
        $user = $request->user();
        $this->authorizeClassAccess($class, $user);

        abort_unless(
            $session->class_id === $class->id,
            404,
            'Sesi pertemuan tidak ditemukan pada kelas ini.'
        );

        $oldData = [
            'zoom_url' => $session->zoom_url,
            'zoom_meeting_id' => $session->zoom_meeting_id,
            'zoom_passcode' => $session->zoom_passcode,
        ];

        $session->update([
            'zoom_url' => $request->input('zoom_url'),
            'zoom_meeting_id' => $request->input('zoom_meeting_id'),
            'zoom_passcode' => $request->input('zoom_passcode'),
        ]);

        ActivityLog::record(
            action: 'UPDATE',
            description: "API: Trainer memperbarui tautan Zoom sesi #{$session->session_order} ('{$session->title}') kelas '{$class->title}'",
            target: $session,
            old: $oldData,
            new: [
                'zoom_url' => $session->zoom_url,
                'zoom_meeting_id' => $session->zoom_meeting_id,
                'zoom_passcode' => $session->zoom_passcode,
            ],
            branchId: $class->branch_id
        );

        return response()->json([
            'message' => 'Tautan Zoom sesi berhasil diperbarui.',
            'session' => $session->fresh(),
        ]);
    }

    /**
     * Verifikasi bahwa trainer adalah pengampu kelas di cabang terkait.
     */
    protected function authorizeClassAccess(TrainingClass $class, User $user): void
    {
        abort_unless(
            $user->branch_id && $class->branch_id === $user->branch_id && $class->trainer_id === $user->id,
            403,
            'Anda tidak memiliki akses ke kelas pelatihan ini.'
        );
    }
}
