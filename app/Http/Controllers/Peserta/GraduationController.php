<?php

namespace App\Http\Controllers\Peserta;

use App\Http\Controllers\Controller;
use App\Models\ClassEnrollment;
use App\Models\GraduationSubmission;
use App\Models\TrainingClass;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use Illuminate\View\View;

class GraduationController extends Controller
{
    /**
     * Tampilkan daftar sertifikat dan status kelulusan peserta.
     */
    public function index(Request $request): View
    {
        $user = $request->user();

        $enrollments = ClassEnrollment::where('user_id', $user->id)
            ->with([
                'trainingClass.branch',
                'trainingClass.trainer',
                'graduationSubmission.trainer',
            ])
            ->latest()
            ->paginate(9)
            ->withQueryString();

        return view('peserta.certificates.index', compact('enrollments'));
    }

    /**
     * Tampilkan detail pengajuan kelulusan atau feedback remedial instruktur.
     */
    public function show(Request $request, GraduationSubmission $submission): View
    {
        $user = $request->user();
        abort_unless(
            $submission->enrollment?->user_id === $user->id,
            403,
            'Anda tidak memiliki akses ke lembar kelulusan ini.'
        );

        $submission->load([
            'enrollment.trainingClass.branch',
            'enrollment.attendances.session',
            'trainer',
        ]);

        return view('peserta.certificates.show', compact('submission'));
    }

    /**
     * Unduh E-Sertifikat Digital resmi dalam format PDF beresolusi tinggi dengan QR Code.
     */
    public function download(Request $request, GraduationSubmission $submission): Response
    {
        $user = $request->user();
        abort_unless(
            $submission->enrollment?->user_id === $user->id,
            403,
            'Anda tidak memiliki akses untuk mengunduh sertifikat ini.'
        );

        abort_unless(
            $submission->isApproved(),
            403,
            'Sertifikat belum dapat diunduh karena belum disetujui oleh Trainer.'
        );

        $submission->load([
            'enrollment.user',
            'enrollment.trainingClass.branch',
            'trainer',
        ]);

        $enrollment = $submission->enrollment;
        $student = $enrollment->user;
        $class = $enrollment->trainingClass;
        $branch = $class->branch;
        $trainer = $submission->trainer;
        $qrCodeBase64 = $submission->getQrCodeBase64();

        $pdf = Pdf::loadView('certificates.template', compact(
            'submission',
            'enrollment',
            'student',
            'class',
            'branch',
            'trainer',
            'qrCodeBase64'
        ))->setPaper('a4', 'landscape');

        $safeName = Str::slug($student->name);
        $safeClass = Str::slug($class->title);
        $fileName = "Sertifikat_{$safeClass}_{$safeName}.pdf";

        return $pdf->download($fileName);
    }

    /**
     * Tampilkan pratinjau E-Sertifikat langsung di browser.
     */
    public function preview(Request $request, GraduationSubmission $submission): Response
    {
        $user = $request->user();
        abort_unless(
            $submission->enrollment?->user_id === $user->id,
            403,
            'Anda tidak memiliki akses ke sertifikat ini.'
        );

        abort_unless(
            $submission->isApproved(),
            403,
            'Sertifikat belum dapat dipratinjau.'
        );

        $submission->load([
            'enrollment.user',
            'enrollment.trainingClass.branch',
            'trainer',
        ]);

        $enrollment = $submission->enrollment;
        $student = $enrollment->user;
        $class = $enrollment->trainingClass;
        $branch = $class->branch;
        $trainer = $submission->trainer;
        $qrCodeBase64 = $submission->getQrCodeBase64();

        $pdf = Pdf::loadView('certificates.template', compact(
            'submission',
            'enrollment',
            'student',
            'class',
            'branch',
            'trainer',
            'qrCodeBase64'
        ))->setPaper('a4', 'landscape');

        return $pdf->stream("Sertifikat_{$submission->certificate_number}.pdf");
    }

    /**
     * Tombol pengajuan manual kelulusan jika target 20 JP telah tercapai.
     */
    public function requestReview(Request $request, TrainingClass $class): RedirectResponse
    {
        $user = $request->user();

        $enrollment = ClassEnrollment::where('class_id', $class->id)
            ->where('user_id', $user->id)
            ->firstOrFail();

        if (! $enrollment->isCompletedJp()) {
            return back()->with('error', 'Anda belum memenuhi syarat minimal 20 JP (900 menit) belajar.');
        }

        $submission = $enrollment->checkAndSubmitGraduation();

        return back()->with('success', 'Pengajuan verifikasi kelulusan 20 JP berhasil dikirimkan ke Trainer pengampu.');
    }
}
