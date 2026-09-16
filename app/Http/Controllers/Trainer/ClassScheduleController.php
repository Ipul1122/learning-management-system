<?php

namespace App\Http\Controllers\Trainer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Trainer\UpdateZoomSessionRequest;
use App\Models\ActivityLog;
use App\Models\ClassSession;
use App\Models\TrainingClass;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ClassScheduleController extends Controller
{
    /**
     * Tampilkan daftar kelas yang diampu oleh trainer dan jadwal sesi mendatang.
     */
    public function index(Request $request): View
    {
        $user = $request->user();
        $branch = $user->branch;
        abort_unless($branch, 403, 'Akun Anda belum diasosiasikan dengan cabang.');

        $trainingClasses = TrainingClass::where('branch_id', $branch->id)
            ->where('trainer_id', $user->id)
            ->withCount(['sessions', 'quizzes'])
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $classes = $trainingClasses;

        // Cari sesi-sesi mendatang pada kelas yang diampu trainer ini
        $upcomingSessions = ClassSession::whereHas('trainingClass', function ($q) use ($user, $branch) {
            $q->where('branch_id', $branch->id)
                ->where('trainer_id', $user->id);
        })
            ->where(function ($q) {
                $q->whereNull('session_date')
                    ->orWhere('session_date', '>=', now()->startOfDay());
            })
            ->with('trainingClass')
            ->orderByRaw('session_date IS NULL, session_date ASC')
            ->limit(10)
            ->get();

        return view('trainer.classes.index', compact('trainingClasses', 'classes', 'upcomingSessions', 'branch'));
    }

    /**
     * Tampilkan detail kelas pelatihan, silabus sesi, dan kuis terhubung.
     */
    public function show(Request $request, TrainingClass $class): View
    {
        $user = $request->user();
        $this->authorizeClassAccess($class, $user);

        $class->load(['sessions', 'quizzes' => fn ($q) => $q->withCount('questions')]);

        return view('trainer.classes.show', [
            'class' => $class,
            'branch' => $user->branch,
        ]);
    }

    /**
     * Akses cepat perbarui tautan Zoom sesi pertemuan (PRD 3.6).
     */
    public function updateZoom(UpdateZoomSessionRequest $request, TrainingClass $class, ClassSession $session): RedirectResponse
    {
        $user = $request->user();
        $this->authorizeClassAccess($class, $user);

        abort_unless(
            $session->class_id === $class->id,
            404,
            'Sesi pertemuan tidak ditemukan pada kelas pelatihan ini.'
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
            description: "Trainer memperbarui tautan Zoom sesi #{$session->session_order} ('{$session->title}') kelas '{$class->title}'",
            target: $session,
            old: $oldData,
            new: [
                'zoom_url' => $session->zoom_url,
                'zoom_meeting_id' => $session->zoom_meeting_id,
                'zoom_passcode' => $session->zoom_passcode,
            ],
            branchId: $class->branch_id
        );

        return back()->with('success', "Tautan Zoom untuk Sesi #{$session->session_order} ({$session->title}) berhasil disimpan!");
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
