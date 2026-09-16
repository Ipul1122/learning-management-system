<?php

namespace App\Http\Controllers\Peserta;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\ClassEnrollment;
use App\Models\ClassSession;
use App\Models\SessionAttendance;
use App\Models\TrainingClass;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StudyRoomController extends Controller
{
    /**
     * Tampilkan seluruh kelas aktif yang diikuti peserta & progres 20 JP global.
     */
    public function index(Request $request): View
    {
        $user = $request->user();

        $enrollments = ClassEnrollment::where('user_id', $user->id)
            ->with(['trainingClass.branch', 'trainingClass.trainer', 'attendances'])
            ->latest()
            ->paginate(9);

        $totalMinutesAllClasses = (int) $user->enrollments()->sum('accumulated_minutes');
        $totalJpAllClasses = round($totalMinutesAllClasses / 45, 1);

        return view('peserta.study.index', compact('enrollments', 'totalMinutesAllClasses', 'totalJpAllClasses'));
    }

    /**
     * Tampilkan ruang belajar kelas tertentu: silabus sesi, tombol Zoom 1-klik, dan kuis.
     */
    public function show(Request $request, TrainingClass $class): View|RedirectResponse
    {
        $user = $request->user();

        $enrollment = ClassEnrollment::where('class_id', $class->id)
            ->where('user_id', $user->id)
            ->first();

        if (! $enrollment) {
            return redirect()
                ->route('peserta.catalog.show', $class)
                ->with('info', 'Silakan mendaftar terlebih dahulu untuk mengakses materi dan sesi kelas ini.');
        }

        $class->load([
            'branch',
            'trainer',
            'sessions',
            'quizzes' => fn ($q) => $q->withCount('questions'),
        ]);

        // Map session attendance peserta untuk penanda kehadiran
        $attendancesBySessionId = SessionAttendance::where('enrollment_id', $enrollment->id)
            ->get()
            ->keyBy('session_id');

        // Riwayat pengerjaan kuis peserta pada kelas ini
        $userQuizAttempts = $user->quizAttempts()
            ->whereIn('quiz_id', $class->quizzes->pluck('id'))
            ->get()
            ->groupBy('quiz_id');

        return view('peserta.study.show', compact('class', 'enrollment', 'attendancesBySessionId', 'userQuizAttempts'));
    }

    /**
     * 1-Klik Masuk Zoom: Catat presensi kehadiran dan arahkan ke live meeting (PRD 4.6).
     */
    public function joinZoom(Request $request, TrainingClass $class, ClassSession $session): RedirectResponse
    {
        $user = $request->user();

        $enrollment = ClassEnrollment::where('class_id', $class->id)
            ->where('user_id', $user->id)
            ->firstOrFail();

        abort_unless(
            $session->class_id === $class->id,
            404,
            'Sesi pertemuan tidak ditemukan pada kelas ini.'
        );

        if (empty($session->zoom_url)) {
            return back()->with('error', 'Tautan ruang Zoom untuk sesi ini belum disiapkan oleh Trainer.');
        }

        // Catat presensi sesi
        $attendance = SessionAttendance::firstOrCreate(
            [
                'enrollment_id' => $enrollment->id,
                'session_id' => $session->id,
            ],
            [
                'joined_zoom_at' => now(),
                'minutes_earned' => $session->minute_duration,
                'is_verified' => false,
            ]
        );

        // Jika baru pertama kali bergabung, tambahkan menit JP ke pendaftaran peserta
        if ($attendance->wasRecentlyCreated) {
            $enrollment->addMinutes($session->minute_duration);

            ActivityLog::record(
                action: 'ATTEND_ZOOM',
                description: "Peserta {$user->name} bergabung ke live session Zoom sesi #{$session->session_order} ({$session->title}) dan memperoleh {$session->minute_duration} menit belajar",
                target: $attendance,
                old: null,
                new: $attendance->toArray(),
                branchId: $class->branch_id
            );
        }

        return redirect()->away($session->zoom_url);
    }
}
