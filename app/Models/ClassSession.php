<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'class_id',
    'session_order',
    'title',
    'jp_duration',
    'minute_duration',
    'session_date',
    'zoom_url',
    'zoom_meeting_id',
    'zoom_passcode',
    'created_by_user_id',
])]
class ClassSession extends Model
{
    use HasFactory;

    protected $table = 'class_sessions';

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'session_order' => 'integer',
            'jp_duration' => 'integer',
            'minute_duration' => 'integer',
            'session_date' => 'datetime',
        ];
    }

    /**
     * Kelas tempat sesi ini bernaung.
     */
    public function trainingClass(): BelongsTo
    {
        return $this->belongsTo(TrainingClass::class, 'class_id');
    }

    /**
     * User (Admin Cabang / Trainer) yang membuat sesi jadwal ini.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }
}
