<?php

namespace App\Services;

use App\Models\Badge;
use App\Models\ClassEnrollment;
use App\Models\ForumReply;
use App\Models\ForumThread;
use App\Models\GraduationSubmission;
use App\Models\PointTransaction;
use App\Models\QuizAttempt;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class GamificationService
{
    /**
     * Berikan poin XP ke pengguna, catat mutasi, dan evaluasi kenaikan level serta lencana.
     */
    public function awardPoints(
        User $user,
        int $points,
        string $sourceType,
        string $description,
        ?int $referenceId = null,
        bool $evaluateBadges = true
    ): PointTransaction {
        return DB::transaction(function () use ($user, $points, $sourceType, $description, $referenceId, $evaluateBadges) {
            $transaction = PointTransaction::create([
                'user_id' => $user->id,
                'points' => $points,
                'source_type' => $sourceType,
                'description' => $description,
                'reference_id' => $referenceId,
            ]);

            $newTotal = max(0, ($user->total_points ?? 0) + $points);
            $newLevel = max(1, (int) floor($newTotal / 200) + 1);

            $user->update([
                'total_points' => $newTotal,
                'level' => $newLevel,
            ]);

            if ($evaluateBadges) {
                $this->checkAndAwardBadges($user);
            }

            return $transaction;
        });
    }

    /**
     * Evaluasi seluruh kriteria lencana dan sematkan lencana baru jika syarat terpenuhi.
     */
    public function checkAndAwardBadges(User $user): array
    {
        $newlyEarned = [];
        $existingBadgeIds = $user->badges()->pluck('badges.id')->toArray();

        // 1. Lencana: Langkah Awal (Terdaftar di minimal 1 kelas pelatihan)
        $hasEnrollment = ClassEnrollment::where('user_id', $user->id)->exists();
        if ($hasEnrollment) {
            $badge = $this->awardBadgeIfEligible($user, 'langkah-awal', $existingBadgeIds);
            if ($badge) {
                $newlyEarned[] = $badge;
            }
        }

        // 2. Lencana: Pejuang Waktu (Akumulasi menit belajar >= 450 menit / 10 JP)
        $totalMinutes = (int) ClassEnrollment::where('user_id', $user->id)->sum('accumulated_minutes');
        if ($totalMinutes >= 450) {
            $badge = $this->awardBadgeIfEligible($user, 'pejuang-waktu', $existingBadgeIds);
            if ($badge) {
                $newlyEarned[] = $badge;
            }
        }

        // 3. Lencana: Juara 20 JP (Akumulasi menit belajar >= 900 menit / 20 JP)
        if ($totalMinutes >= 900) {
            $badge = $this->awardBadgeIfEligible($user, 'juara-20-jp', $existingBadgeIds);
            if ($badge) {
                $newlyEarned[] = $badge;
            }
        }

        // 4. Lencana: Akurasi Tinggi (Pernah meraih nilai kuis >= 90)
        $hasHighScore = QuizAttempt::where('user_id', $user->id)
            ->whereNotNull('submitted_at')
            ->where('total_score', '>=', 90)
            ->exists();
        if ($hasHighScore) {
            $badge = $this->awardBadgeIfEligible($user, 'kuis-sempurna', $existingBadgeIds);
            if ($badge) {
                $newlyEarned[] = $badge;
            }
        }

        // 5. Lencana: Lulusan Kompeten (Memiliki pengajuan kelulusan yang disetujui / sertifikat)
        $hasApprovedGraduation = GraduationSubmission::whereHas('enrollment', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })->where('status', 'approved')->exists();
        if ($hasApprovedGraduation) {
            $badge = $this->awardBadgeIfEligible($user, 'lulusan-kompeten', $existingBadgeIds);
            if ($badge) {
                $newlyEarned[] = $badge;
            }
        }

        // 6. Lencana: Aktivis Forum (Pernah membuat thread diskusi atau membalas thread)
        $hasForumActivity = ForumThread::where('author_id', $user->id)->exists()
            || ForumReply::where('author_id', $user->id)->exists();
        if ($hasForumActivity) {
            $badge = $this->awardBadgeIfEligible($user, 'kontributor-forum', $existingBadgeIds);
            if ($badge) {
                $newlyEarned[] = $badge;
            }
        }

        return $newlyEarned;
    }

    /**
     * Helper internal untuk memeriksa apakah lencana belum dimiliki dan memberikannya beserta bonus XP.
     */
    protected function awardBadgeIfEligible(User $user, string $slug, array &$existingBadgeIds): ?Badge
    {
        $badge = Badge::where('slug', $slug)->first();
        if (! $badge) {
            return null;
        }

        if (in_array($badge->id, $existingBadgeIds, true)) {
            return null;
        }

        $user->badges()->attach($badge->id, ['earned_at' => now()]);
        $existingBadgeIds[] = $badge->id;

        // Berikan bonus XP reward lencana tanpa rekursi evaluasi lencana
        if ($badge->xp_reward > 0) {
            $this->awardPoints(
                $user,
                $badge->xp_reward,
                'badge',
                "Membuka Lencana Prestasi: {$badge->name}",
                $badge->id,
                false
            );
        }

        return $badge;
    }

    /**
     * Sinkronisasi data historis seluruh pengguna peserta (kalkulasi ulang poin & lencana dari data yang ada).
     */
    public function syncAllExistingUsers(): void
    {
        $pesertas = User::role('peserta')->get();

        foreach ($pesertas as $peserta) {
            $this->syncUserGamification($peserta);
        }
    }

    /**
     * Sinkronisasi gamifikasi untuk satu pengguna peserta.
     */
    public function syncUserGamification(User $user): void
    {
        // Hitung poin dari pendaftaran kelas (50 XP per kelas)
        $enrollments = ClassEnrollment::where('user_id', $user->id)->get();
        foreach ($enrollments as $enr) {
            if (! PointTransaction::where('user_id', $user->id)->where('source_type', 'enrollment')->where('reference_id', $enr->id)->exists()) {
                $this->awardPoints($user, 50, 'enrollment', "Pendaftaran Kelas: {$enr->trainingClass?->title}", $enr->id, false);
            }
        }

        // Hitung poin dari kuis yang telah dikerjakan
        $attempts = QuizAttempt::where('user_id', $user->id)->whereNotNull('submitted_at')->get();
        foreach ($attempts as $att) {
            if (! PointTransaction::where('user_id', $user->id)->where('source_type', 'quiz')->where('reference_id', $att->id)->exists()) {
                $pts = (int) round(($att->total_score ?? 0) / 2);
                if ($pts > 0) {
                    $this->awardPoints($user, $pts, 'quiz', "Penyelesaian Kuis Skor {$att->total_score}", $att->id, false);
                }
            }
        }

        // Hitung poin dari kelulusan (300 XP)
        $graduations = GraduationSubmission::whereHas('enrollment', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })->where('status', 'approved')->get();

        foreach ($graduations as $grad) {
            if (! PointTransaction::where('user_id', $user->id)->where('source_type', 'graduation')->where('reference_id', $grad->id)->exists()) {
                $this->awardPoints($user, 300, 'graduation', 'Kelulusan Resmi Program Pelatihan 20 JP', $grad->id, false);
            }
        }

        // Evaluasi lencana
        $this->checkAndAwardBadges($user);
    }
}
