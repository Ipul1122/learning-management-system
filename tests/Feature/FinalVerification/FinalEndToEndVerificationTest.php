<?php

use App\Models\Branch;
use App\Models\ClassEnrollment;
use App\Models\GraduationSubmission;
use App\Models\TrainingClass;
use App\Models\User;
use Tests\TestCase;

beforeEach(function () {
    /** @var TestCase $this */
    $this->seed();
});

/*
|--------------------------------------------------------------------------
| 1. UJI KUOTA KETAT KELAS OFFLINE: MENOLAK PENDAFTAR KE-41 (KUOTA 40)
|--------------------------------------------------------------------------
*/
test('kuota kelas offline menolak pendaftar ke-41 secara ketat (kuota 40)', function () {
    /** @var TestCase $this */
    $branch = Branch::first();
    $trainer = User::where('email', 'trainer1@lms.test')->first();

    // Buat kelas tipe hybrid/offline dengan batas 40 kursi offline
    $class = TrainingClass::create([
        'branch_id' => $branch->id,
        'trainer_id' => $trainer->id,
        'title' => 'Bootcamp Fullstack Offline Batch 40',
        'slug' => 'bootcamp-fullstack-offline-batch-40',
        'type' => 'hybrid',
        'offline_capacity' => 40,
        'online_capacity' => 100,
        'enrolled_offline' => 0,
        'enrolled_online' => 0,
        'required_jp' => 20,
        'start_date' => now()->toDateString(),
        'end_date' => now()->addDays(30)->toDateString(),
        'status' => 'open',
    ]);

    // Daftarkan 40 peserta awal secara berurutan
    for ($i = 1; $i <= 40; $i++) {
        $student = User::firstOrCreate(
            ['email' => "offline.student{$i}@lms.test"],
            [
                'name' => "Peserta Offline {$i}",
                'password' => bcrypt('password'),
                'is_active' => true,
                'branch_id' => $branch->id,
            ]
        );
        $student->syncRoles(['peserta']);

        $response = $this->actingAs($student)->post(route('peserta.catalog.enroll', $class), [
            'attendance_mode' => 'offline',
        ]);

        $response->assertRedirect(route('peserta.study.show', $class));
        $this->assertDatabaseHas('class_enrollments', [
            'class_id' => $class->id,
            'user_id' => $student->id,
            'attendance_mode' => 'offline',
            'status' => 'enrolled',
        ]);
    }

    // Pastikan counter enrolled_offline tepat bernilai 40
    $freshClass = $class->fresh();
    expect($freshClass->enrolled_offline)->toBe(40);
    expect($freshClass->remainingOfflineSeats())->toBe(0);

    // Sekarang daftarkan Peserta ke-41
    $student41 = User::firstOrCreate(
        ['email' => 'offline.student41@lms.test'],
        [
            'name' => 'Peserta Offline 41 (Harus Ditolak)',
            'password' => bcrypt('password'),
            'is_active' => true,
            'branch_id' => $branch->id,
        ]
    );
    $student41->syncRoles(['peserta']);

    $response41 = $this->actingAs($student41)->post(route('peserta.catalog.enroll', $class), [
        'attendance_mode' => 'offline',
    ]);

    // Wajib ditolak dengan validation error pada field attendance_mode
    $response41->assertSessionHasErrors('attendance_mode');

    // Pastikan tidak ada enrollment untuk student 41
    $this->assertDatabaseMissing('class_enrollments', [
        'class_id' => $class->id,
        'user_id' => $student41->id,
    ]);

    // Pastikan counter tidak bocor (tetap 40)
    expect($class->fresh()->enrolled_offline)->toBe(40);

    // Total enrollment offline di database harus tepat 40
    $countInDb = ClassEnrollment::where('class_id', $class->id)
        ->where('attendance_mode', 'offline')
        ->count();
    expect($countInDb)->toBe(40);
});

/*
|--------------------------------------------------------------------------
| 2. UJI AKURASI PERHITUNGAN 20 JP (1 JP = 45 MENIT -> 900 MENIT)
|--------------------------------------------------------------------------
*/
test('perhitungan jam pelajaran 20 JP akurat (1 JP = 45 menit) dan memicu otomasi pengajuan kelulusan', function () {
    /** @var TestCase $this */
    $branch = Branch::first();
    $trainer = User::where('email', 'trainer1@lms.test')->first();
    $student = User::where('email', 'peserta1@lms.test')->first();

    $class = TrainingClass::create([
        'branch_id' => $branch->id,
        'trainer_id' => $trainer->id,
        'title' => 'Kelas Sertifikasi 20 JP Akurasi',
        'slug' => 'kelas-sertifikasi-20-jp-akurasi',
        'type' => 'online',
        'online_capacity' => 50,
        'required_jp' => 20,
        'start_date' => now()->toDateString(),
        'end_date' => now()->addDays(14)->toDateString(),
        'status' => 'ongoing',
    ]);

    $enrollment = ClassEnrollment::create([
        'class_id' => $class->id,
        'user_id' => $student->id,
        'attendance_mode' => 'online',
        'total_minutes_accumulated' => 0,
        'status' => 'enrolled',
        'enrolled_at' => now(),
    ]);

    // 1. Akumulasi 45 menit -> Tepat 1.0 JP
    $enrollment->addMinutes(45);
    $enrollment->refresh();
    expect($enrollment->accumulated_jp)->toBe(1.0);
    expect($enrollment->status)->toBe('in_progress');

    // 2. Akumulasi tambahan 135 menit -> Total 180 menit -> Tepat 4.0 JP
    $enrollment->addMinutes(135);
    $enrollment->refresh();
    expect($enrollment->accumulated_minutes)->toBe(180);
    expect($enrollment->accumulated_jp)->toBe(4.0);
    expect($enrollment->isCompletedJp())->toBeFalse();

    // 3. Akumulasi hingga 899 menit (kurang 1 menit dari 900) -> 19.98 JP -> Belum memicu kelulusan
    $enrollment->addMinutes(719); // 180 + 719 = 899
    $enrollment->refresh();
    expect($enrollment->accumulated_minutes)->toBe(899);
    expect($enrollment->isCompletedJp())->toBeFalse();
    expect($enrollment->status)->toBe('in_progress');
    expect(GraduationSubmission::where('enrollment_id', $enrollment->id)->exists())->toBeFalse();

    // 4. Tambah 1 menit terakhir -> Tepat 900 menit -> Tepat 20.0 JP
    $enrollment->addMinutes(1);
    $enrollment->refresh();

    expect($enrollment->accumulated_minutes)->toBe(900);
    expect($enrollment->accumulated_jp)->toBe(20.0);
    expect($enrollment->isCompletedJp())->toBeTrue();

    // Otomatis beralih ke status review_pending
    expect($enrollment->status)->toBe('review_pending');

    // Otomatis dibuatkan record GraduationSubmission berstatus pending
    $submission = GraduationSubmission::where('enrollment_id', $enrollment->id)->first();
    expect($submission)->not->toBeNull();
    expect($submission->status)->toBe('pending');
    expect($submission->total_jp_earned)->toBe(20.0);
    expect($submission->trainer_id)->toBe($trainer->id);
});

/*
|--------------------------------------------------------------------------
| 3. UJI WORKFLOW APPROVAL & REJECT KELULUSAN OLEH TRAINER
|--------------------------------------------------------------------------
*/
test('trainer dapat menyetujui (approve) kelulusan dan menerbitkan sertifikat unik beserta QR code', function () {
    /** @var TestCase $this */
    $branch = Branch::first();
    $trainer = User::where('email', 'trainer1@lms.test')->first();
    $student = User::where('email', 'peserta1@lms.test')->first();

    $class = TrainingClass::create([
        'branch_id' => $branch->id,
        'trainer_id' => $trainer->id,
        'title' => 'Kelas Approval Trainer',
        'slug' => 'kelas-approval-trainer',
        'type' => 'online',
        'online_capacity' => 50,
        'required_jp' => 20,
        'start_date' => now()->toDateString(),
        'end_date' => now()->addDays(14)->toDateString(),
        'status' => 'ongoing',
    ]);

    $enrollment = ClassEnrollment::create([
        'class_id' => $class->id,
        'user_id' => $student->id,
        'attendance_mode' => 'online',
        'total_minutes_accumulated' => 900,
        'status' => 'review_pending',
        'enrolled_at' => now(),
    ]);

    $submission = GraduationSubmission::create([
        'enrollment_id' => $enrollment->id,
        'trainer_id' => $trainer->id,
        'total_jp_earned' => 20.0,
        'avg_quiz_score' => 92.5,
        'status' => 'pending',
    ]);

    // Trainer melakukan Approval
    $response = $this->actingAs($trainer)->post(route('trainer.graduations.approve', $submission));
    $response->assertRedirect(route('trainer.graduations.show', $submission));
    $response->assertSessionHas('success');

    $submission->refresh();
    $enrollment->refresh();

    // Status terverifikasi
    expect($submission->status)->toBe('approved');
    expect($enrollment->status)->toBe('graduated');
    expect($submission->certificate_number)->not->toBeNull();
    expect($submission->qr_verification_code)->not->toBeNull();

    // Nomor sertifikat mematuhi format baku: CERT-YYYY-BRANCH-XXXXX
    expect($submission->certificate_number)->toMatch('/^CERT-\d{4}-.+-\d{5}$/');

    // Halaman verifikasi publik terbuka dan dapat diakses siapa saja tanpa login
    $verifyUrl = route('certificates.verify', $submission->certificate_number);
    $publicResponse = $this->get($verifyUrl);
    $publicResponse->assertStatus(200);
    $publicResponse->assertSee($submission->certificate_number);
    $publicResponse->assertSee('RESMI TERVERIFIKASI');

    // Peserta dapat mengunduh dokumen PDF sertifikat resmi
    $downloadResponse = $this->actingAs($student)->get(route('peserta.certificates.download', $submission));
    $downloadResponse->assertStatus(200);
    $downloadResponse->assertHeader('content-type', 'application/pdf');
});

test('trainer dapat menolak (reject) kelulusan dengan catatan perbaikan minimal 10 karakter', function () {
    /** @var TestCase $this */
    $branch = Branch::first();
    $trainer = User::where('email', 'trainer1@lms.test')->first();
    $student = User::where('email', 'peserta1@lms.test')->first();

    $class = TrainingClass::create([
        'branch_id' => $branch->id,
        'trainer_id' => $trainer->id,
        'title' => 'Kelas Rejection Trainer',
        'slug' => 'kelas-rejection-trainer',
        'type' => 'online',
        'online_capacity' => 50,
        'required_jp' => 20,
        'start_date' => now()->toDateString(),
        'end_date' => now()->addDays(14)->toDateString(),
        'status' => 'ongoing',
    ]);

    $enrollment = ClassEnrollment::create([
        'class_id' => $class->id,
        'user_id' => $student->id,
        'attendance_mode' => 'online',
        'total_minutes_accumulated' => 900,
        'status' => 'review_pending',
        'enrolled_at' => now(),
    ]);

    $submission = GraduationSubmission::create([
        'enrollment_id' => $enrollment->id,
        'trainer_id' => $trainer->id,
        'total_jp_earned' => 20.0,
        'avg_quiz_score' => 65.0,
        'status' => 'pending',
    ]);

    // Penolakan tanpa feedback gagal validasi
    $failedReject = $this->actingAs($trainer)->post(route('trainer.graduations.reject', $submission), [
        'trainer_feedback' => 'Kurang', // < 10 karakter
    ]);
    $failedReject->assertSessionHasErrors('trainer_feedback');

    // Penolakan dengan feedback memadai
    $validReject = $this->actingAs($trainer)->post(route('trainer.graduations.reject', $submission), [
        'trainer_feedback' => 'Nilai kuis rata-rata masih 65.0. Silakan ikuti remedial kuis sesi 3 dan 4.',
    ]);
    $validReject->assertRedirect(route('trainer.graduations.show', $submission));

    $submission->refresh();
    $enrollment->refresh();

    expect($submission->status)->toBe('rejected');
    expect($enrollment->status)->toBe('rejected');
    expect($submission->trainer_feedback)->toContain('remedial');

    // Peserta dapat melihat feedback pada lembar sertifikat
    $studentResponse = $this->actingAs($student)->get(route('peserta.certificates.show', $submission));
    $studentResponse->assertStatus(200);
    $studentResponse->assertSee('remedial');
});

/*
|--------------------------------------------------------------------------
| 4. UJI ROLE-BASED ACCESS CONTROL & ISOLASI DATA MULTI-CABANG
|--------------------------------------------------------------------------
*/
test('isolasi data cabang ketat: admin jakarta dilarang mengelola cabang surabaya', function () {
    /** @var TestCase $this */
    $adminJakarta = User::where('email', 'admin.jkt@lms.test')->first();
    $adminSurabaya = User::where('email', 'admin.sby@lms.test')->first();

    $classSurabaya = TrainingClass::create([
        'branch_id' => $adminSurabaya->branch_id,
        'trainer_id' => User::where('email', 'trainer1@lms.test')->first()->id,
        'title' => 'Kelas Rahasia Surabaya',
        'slug' => 'kelas-rahasia-surabaya',
        'type' => 'online',
        'online_capacity' => 30,
        'required_jp' => 20,
        'start_date' => now()->toDateString(),
        'end_date' => now()->addDays(14)->toDateString(),
        'status' => 'open',
    ]);

    // Admin Jakarta coba edit kelas Surabaya -> 403 Forbidden
    $responseEdit = $this->actingAs($adminJakarta)->get(route('cabang.classes.edit', $classSurabaya));
    $responseEdit->assertStatus(403);

    // Admin Jakarta coba update kelas Surabaya -> 403 Forbidden
    $responseUpdate = $this->actingAs($adminJakarta)->put(route('cabang.classes.update', $classSurabaya), [
        'title' => 'Pembajakan Kelas Surabaya',
        'trainer_id' => $classSurabaya->trainer_id,
        'type' => 'online',
        'online_capacity' => 20,
        'required_jp' => 20,
        'start_date' => now()->toDateString(),
        'end_date' => now()->addDays(14)->toDateString(),
        'status' => 'open',
    ]);
    $responseUpdate->assertStatus(403);

    // Admin Jakarta coba hapus kelas Surabaya -> 403 Forbidden
    $responseDelete = $this->actingAs($adminJakarta)->delete(route('cabang.classes.destroy', $classSurabaya));
    $responseDelete->assertStatus(403);
});

test('proteksi role: peserta dilarang mengakses dashboard admin atau trainer', function () {
    /** @var TestCase $this */
    $student = User::where('email', 'peserta1@lms.test')->first();

    // Peserta coba akses dashboard super admin -> 403
    $this->actingAs($student)->get(route('admin.dashboard'))->assertStatus(403);

    // Peserta coba akses dashboard admin cabang -> 403
    $this->actingAs($student)->get(route('cabang.dashboard'))->assertStatus(403);

    // Peserta coba akses dashboard trainer -> 403
    $this->actingAs($student)->get(route('trainer.dashboard'))->assertStatus(403);
});
