<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SessionAttendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'enrollment_id',
        'session_id',
        'joined_zoom_at',
        'minutes_earned',
        'is_verified',
    ];

    protected $casts = [
        'joined_zoom_at' => 'datetime',
        'minutes_earned' => 'integer',
        'is_verified' => 'boolean',
    ];

    /**
     * Pendaftaran kelas terkait.
     */
    public function enrollment(): BelongsTo
    {
        return $this->belongsTo(ClassEnrollment::class, 'enrollment_id');
    }

    /**
     * Sesi pertemuan yang dihadiri.
     */
    public function session(): BelongsTo
    {
        return $this->belongsTo(ClassSession::class, 'session_id');
    }
}
