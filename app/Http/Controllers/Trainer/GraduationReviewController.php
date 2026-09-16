<?php

namespace App\Http\Controllers\Trainer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Trainer\RejectGraduationRequest;
use App\Models\ActivityLog;
use App\Models\GraduationSubmission;
use App\Models\TrainingClass;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GraduationReviewController extends Controller
{
    /**
     * Tampilkan antrean pengajuan kelulusan 20 JP peserta pada kelas yang diampu Trainer.
     */
    public function index(Request $request): View
    {
        $user = $request->user();

        $query = GraduationSubmission::whereHas('enrollment.trainingClass', function ($q) use ($user) {
            $q->where('trainer_id', $user->id);
        })->with(['enrollment.user', 'enrollment.trainingClass.branch']);

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('class_id')) {
            $query->whereHas('enrollment', fn ($q) => $q->where('class_id', $request->input('class_id')));
        }

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->whereHas('enrollment.user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $submissions = $query->latest()->paginate(10)->withQueryString();

        $pendingCount = GraduationSubmission::whereHas('enrollment.trainingClass', function ($q) use ($user) {
            $q->where('trainer_id', $user->id);
        })->where('status', 'pending')->count();

        $trainerClasses = TrainingClass::where('trainer_id', $user->id)->orderBy('title')->get();
        $classes = $trainerClasses;

        return view('trainer.graduations.index', compact('submissions', 'pendingCount', 'trainerClasses', 'classes'));
    }

    /**
     * Tampilkan lembar audit performa belajar peserta (20 JP, Presensi Zoom, Nilai Kuis).
     */
    public function show(Request $request, GraduationSubmission $submission): View
    {
        $user = $request->user();
        $this->authorizeSubmissionAccess($submission, $user->id);

        $submission->load([
            'enrollment.user',
            'enrollment.trainingClass.branch',
            'enrollment.trainingClass.sessions',
            'enrollment.trainingClass.quizzes',
            'enrollment.attendances.session',
            'trainer',
        ]);

        $enrollment = $submission->enrollment;
        $class = $enrollment->trainingClass;
        $student = $enrollment->user;
        $attendancesBySessionId = $enrollment->attendances->keyBy('session_id');

        // Rekapitulasi pengerjaan kuis peserta pada kelas ini
        $userQuizAttempts = $student->quizAttempts()
            ->whereIn('quiz_id', $class->quizzes->pluck('id'))
            ->get()
            ->groupBy('quiz_id');

        return view('trainer.graduations.show', compact(
            'submission',
            'enrollment',
            'class',
            'student',
            'attendancesBySessionId',
            'userQuizAttempts'
        ));
    }

    /**
     * Setujui kelulusan peserta (LULUS), generate nomor sertifikat resmi dan QR Code.
     */
    public function approve(Request $request, GraduationSubmission $submission): RedirectResponse
    {
        $user = $request->user();
        $this->authorizeSubmissionAccess($submission, $user->id);

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
            'trainer_feedback' => $request->input('trainer_feedback', 'Selamat atas kelulusan pelatihan Anda! Seluruh capaian 20 JP dan evaluasi materi telah terverifikasi dengan sangat baik.'),
        ]);

        $enrollment->update([
            'status' => 'graduated',
        ]);

        ActivityLog::record(
            action: 'APPROVE_GRADUATION',
            description: "Trainer {$user->name} menyetujui kelulusan 20 JP peserta {$student->name} pada kelas '{$class->title}' dan menerbitkan nomor sertifikat {$certificateNumber}",
            target: $submission,
            old: null,
            new: $submission->fresh()->toArray(),
            branchId: $class->branch_id
        );

        return redirect()
            ->route('trainer.graduations.show', $submission)
            ->with('success', "Kelulusan peserta berhasil disetujui! E-Sertifikat resmi {$certificateNumber} telah diterbitkan.");
    }

    /**
     * Tolak pengajuan kelulusan peserta (REMEDIAL) dengan form instruksi perbaikan wajib.
     */
    public function reject(RejectGraduationRequest $request, GraduationSubmission $submission): RedirectResponse
    {
        $user = $request->user();
        $this->authorizeSubmissionAccess($submission, $user->id);

        $enrollment = $submission->enrollment;
        $student = $enrollment->user;
        $class = $enrollment->trainingClass;

        $feedback = $request->input('trainer_feedback');

        $submission->update([
            'status' => 'rejected',
            'trainer_id' => $user->id,
            'reviewed_at' => now(),
            'trainer_feedback' => $feedback,
        ]);

        $enrollment->update([
            'status' => 'rejected',
        ]);

        ActivityLog::record(
            action: 'REJECT_GRADUATION',
            description: "Trainer {$user->name} menolak kelulusan peserta {$student->name} pada kelas '{$class->title}' dengan instruksi remedial: {$feedback}",
            target: $submission,
            old: null,
            new: $submission->fresh()->toArray(),
            branchId: $class->branch_id
        );

        return redirect()
            ->route('trainer.graduations.show', $submission)
            ->with('warning', 'Pengajuan kelulusan ditolak. Instruksi perbaikan telah dikirimkan ke lembar hasil peserta.');
    }

    /**
     * Validasi hak akses trainer terhadap pengajuan kelas.
     */
    protected function authorizeSubmissionAccess(GraduationSubmission $submission, int $userId): void
    {
        abort_unless(
            $submission->enrollment?->trainingClass?->trainer_id === $userId,
            403,
            'Anda tidak memiliki wewenang untuk meninjau kelulusan pada kelas ini.'
        );
    }
}
