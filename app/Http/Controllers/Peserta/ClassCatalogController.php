<?php

namespace App\Http\Controllers\Peserta;

use App\Http\Controllers\Controller;
use App\Http\Requests\Peserta\EnrollClassRequest;
use App\Models\ActivityLog;
use App\Models\Branch;
use App\Models\ClassEnrollment;
use App\Models\TrainingClass;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ClassCatalogController extends Controller
{
    /**
     * Tampilkan katalog kelas pelatihan dengan filter cabang, tipe, dan pencarian.
     */
    public function index(Request $request): View
    {
        $user = $request->user();

        $query = TrainingClass::whereIn('status', ['open', 'ongoing'])
            ->with(['branch', 'trainer']);

        if ($request->filled('branch_id')) {
            $query->where('branch_id', $request->input('branch_id'));
        }

        if ($request->filled('type')) {
            $query->where('type', $request->input('type'));
        }

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $classes = $query->latest()->paginate(9)->withQueryString();

        // ID kelas-kelas yang sudah diikuti peserta
        $myEnrolledClassIds = $user->enrollments()->pluck('class_id')->toArray();

        $branches = Branch::where('is_active', true)->orderBy('name')->get();

        return view('peserta.catalog.index', compact('classes', 'branches', 'myEnrolledClassIds'));
    }

    /**
     * Tampilkan detail kelas, silabus sesi, dan ketersediaan kuota.
     */
    public function show(Request $request, TrainingClass $class): View
    {
        $user = $request->user();
        $class->load(['branch', 'trainer', 'sessions', 'quizzes']);

        $enrollment = ClassEnrollment::where('class_id', $class->id)
            ->where('user_id', $user->id)
            ->first();

        return view('peserta.catalog.show', compact('class', 'enrollment'));
    }

    /**
     * Daftarkan peserta ke kelas dengan proteksi transaksi & lockForUpdate (Maks 40 Offline).
     */
    public function enroll(EnrollClassRequest $request, TrainingClass $class): RedirectResponse
    {
        $user = $request->user();
        $mode = $request->input('attendance_mode');

        // Validasi kesesuaian modalitas dengan tipe kelas
        if ($class->isOffline() && $mode !== 'offline') {
            return back()->withErrors(['attendance_mode' => 'Kelas offline hanya menerima kehadiran fisik (offline).']);
        }
        if ($class->isOnline() && $mode !== 'online') {
            return back()->withErrors(['attendance_mode' => 'Kelas online hanya menerima kehadiran daring (online).']);
        }

        // Cek pendaftaran ganda
        $alreadyEnrolled = ClassEnrollment::where('class_id', $class->id)
            ->where('user_id', $user->id)
            ->exists();

        if ($alreadyEnrolled) {
            return redirect()
                ->route('peserta.study.show', $class)
                ->with('info', 'Anda sudah terdaftar pada kelas pelatihan ini.');
        }

        try {
            $enrollment = DB::transaction(function () use ($class, $user, $mode) {
                // Kunci baris kelas untuk menghindari race condition / overbooking
                $lockedClass = TrainingClass::where('id', $class->id)->lockForUpdate()->firstOrFail();

                abort_unless(
                    in_array($lockedClass->status, ['open', 'ongoing']),
                    422,
                    'Kelas ini tidak sedang menerima pendaftaran baru.'
                );

                $doubleCheck = ClassEnrollment::where('class_id', $lockedClass->id)
                    ->where('user_id', $user->id)
                    ->exists();

                abort_if(
                    $doubleCheck,
                    422,
                    'Anda sudah terdaftar pada kelas pelatihan ini.'
                );

                if ($mode === 'offline') {
                    abort_if(
                        $lockedClass->isFullOffline(),
                        422,
                        'Mohon maaf, kuota kursi fisik offline (maksimal 40 orang) untuk kelas ini telah penuh.'
                    );
                    $lockedClass->increment('enrolled_offline');
                } else {
                    abort_if(
                        $lockedClass->isFullOnline(),
                        422,
                        'Mohon maaf, kuota peserta daring kelas ini telah penuh.'
                    );
                    $lockedClass->increment('enrolled_online');
                }

                return ClassEnrollment::create([
                    'class_id' => $lockedClass->id,
                    'user_id' => $user->id,
                    'attendance_mode' => $mode,
                    'accumulated_minutes' => 0,
                    'accumulated_jp' => 0.0,
                    'status' => 'enrolled',
                    'enrolled_at' => now(),
                ]);
            });

            ActivityLog::record(
                action: 'ENROLL',
                description: "Peserta {$user->name} berhasil mendaftar kelas '{$class->title}' dengan moda {$mode}",
                target: $enrollment,
                old: null,
                new: $enrollment->toArray(),
                branchId: $class->branch_id
            );

            // Gamifikasi: +50 XP Pendaftaran Kelas & Cek Lencana Langkah Awal
            app(\App\Services\GamificationService::class)->awardPoints(
                $user,
                50,
                'enrollment',
                "Pendaftaran Kelas Pelatihan: {$class->title}",
                $enrollment->id
            );

            return redirect()
                ->route('peserta.study.show', $class)
                ->with('success', "Selamat! Anda berhasil terdaftar di kelas '{$class->title}'. Mari mulai pengumpulan 20 JP!");
        } catch (HttpException $e) {
            return back()
                ->withErrors(['attendance_mode' => $e->getMessage()])
                ->with('error', $e->getMessage());
        }
    }
}
