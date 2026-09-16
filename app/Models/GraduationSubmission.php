<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class GraduationSubmission extends Model
{
    use HasFactory;

    protected $fillable = [
        'enrollment_id',
        'trainer_id',
        'total_jp_earned',
        'avg_quiz_score',
        'status',
        'trainer_feedback',
        'reviewed_at',
        'certificate_number',
        'certificate_path',
        'qr_verification_code',
    ];

    protected $casts = [
        'total_jp_earned' => 'float',
        'avg_quiz_score' => 'float',
        'reviewed_at' => 'datetime',
    ];

    /**
     * Data pendaftaran kelas yang diverifikasi.
     */
    public function enrollment(): BelongsTo
    {
        return $this->belongsTo(ClassEnrollment::class, 'enrollment_id');
    }

    /**
     * Trainer yang mengevaluasi kelulusan.
     */
    public function trainer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'trainer_id');
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    /**
     * Menghasilkan nomor registrasi sertifikat unik resmi.
     * Format: CERT-{TAHUN}-{KODE_CABANG}-{ID_PAD}
     */
    public function generateCertificateNumber(): string
    {
        $year = now()->format('Y');
        $branchCode = $this->enrollment?->trainingClass?->branch?->code ?? 'LMS';
        $paddedId = str_pad((string) $this->id, 5, '0', STR_PAD_LEFT);

        return "CERT-{$year}-{$branchCode}-{$paddedId}";
    }

    /**
     * Tautan URL publik untuk verifikasi QR Code keabsahan sertifikat.
     */
    public function getVerificationUrl(): string
    {
        if (empty($this->certificate_number)) {
            return url('/');
        }

        return route('certificates.verify', $this->certificate_number);
    }

    /**
     * Menghasilkan data URI Base64 SVG dari QR Code untuk disematkan pada PDF sertifikat.
     */
    public function getQrCodeBase64(): string
    {
        $verificationUrl = $this->getVerificationUrl();
        $svg = QrCode::size(110)
            ->color(30, 27, 24)
            ->generate($verificationUrl);

        return 'data:image/svg+xml;base64,'.base64_encode($svg);
    }
}
