<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailOtp extends Model
{
    use HasFactory;

    protected $fillable = [
        'email',
        'otp_code',
        'type',
        'metadata',
        'expires_at',
    ];

    protected function casts(): array
    {
        return [
            'metadata' => 'array',
            'expires_at' => 'datetime',
        ];
    }

    /**
     * Cek apakah kode OTP sudah kedaluwarsa.
     */
    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }

    /**
     * Bangkitkan kode OTP 6 digit baru dan simpan ke database.
     * Hapus OTP lama dengan email dan tipe yang sama jika masih ada.
     */
    public static function generate(string $email, string $type, ?array $metadata = null, int $expiryMinutes = 10): self
    {
        // Bersihkan OTP aktif sebelumnya untuk email dan tipe ini
        static::where('email', $email)
            ->where('type', $type)
            ->delete();

        // Generate 6 digit angka aman
        $otpCode = str_pad((string) random_int(100000, 999999), 6, '0', STR_PAD_LEFT);

        return static::create([
            'email' => $email,
            'otp_code' => $otpCode,
            'type' => $type,
            'metadata' => $metadata,
            'expires_at' => now()->addMinutes($expiryMinutes),
        ]);
    }

    /**
     * Verifikasi kode OTP berdasarkan email, kode, dan tipenya.
     * Mengembalikan record EmailOtp jika valid, atau null jika salah / kedaluwarsa.
     */
    public static function verify(string $email, string $otpCode, string $type): ?self
    {
        $otp = static::where('email', $email)
            ->where('otp_code', trim($otpCode))
            ->where('type', $type)
            ->where('expires_at', '>', now())
            ->first();

        return $otp;
    }
}
