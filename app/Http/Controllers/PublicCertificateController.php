<?php

namespace App\Http\Controllers;

use App\Models\GraduationSubmission;
use Illuminate\View\View;

class PublicCertificateController extends Controller
{
    /**
     * Halaman verifikasi publik keaslian E-Sertifikat Digital melalui pemindaian QR Code.
     * Dapat diakses bebas tanpa login oleh pihak ketiga / publik.
     */
    public function verify(string $certificate_number): View
    {
        $submission = GraduationSubmission::where('certificate_number', $certificate_number)
            ->where('status', 'approved')
            ->with([
                'enrollment.user',
                'enrollment.trainingClass.branch',
                'trainer',
            ])
            ->firstOrFail();

        $enrollment = $submission->enrollment;
        $student = $enrollment->user;
        $class = $enrollment->trainingClass;
        $branch = $class->branch;
        $trainer = $submission->trainer;

        return view('certificates.verify', compact(
            'submission',
            'enrollment',
            'student',
            'class',
            'branch',
            'trainer'
        ));
    }
}
