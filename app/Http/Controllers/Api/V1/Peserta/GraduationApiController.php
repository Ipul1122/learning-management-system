<?php

namespace App\Http\Controllers\Api\V1\Peserta;

use App\Http\Controllers\Controller;
use App\Http\Resources\Trainer\GraduationSubmissionResource;
use App\Models\GraduationSubmission;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;

class GraduationApiController extends Controller
{
    /**
     * Daftar sertifikat & pengajuan kelulusan peserta via API.
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        $submissions = GraduationSubmission::whereHas('enrollment', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })->with(['enrollment.trainingClass.branch', 'trainer'])->latest()->paginate(15);

        return response()->json([
            'data' => GraduationSubmissionResource::collection($submissions),
        ]);
    }

    /**
     * Detail status sertifikat via API.
     */
    public function show(Request $request, GraduationSubmission $submission): JsonResponse
    {
        $user = $request->user();
        abort_unless(
            $submission->enrollment?->user_id === $user->id,
            403,
            'Akses ditolak.'
        );

        return response()->json([
            'submission' => new GraduationSubmissionResource($submission->load(['enrollment.trainingClass.branch', 'trainer'])),
        ]);
    }

    /**
     * Unduh file PDF E-Sertifikat via API.
     */
    public function download(Request $request, GraduationSubmission $submission): Response
    {
        $user = $request->user();
        abort_unless(
            $submission->enrollment?->user_id === $user->id,
            403,
            'Akses ditolak.'
        );

        abort_unless(
            $submission->isApproved(),
            403,
            'Sertifikat belum disetujui oleh Trainer.'
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
}
