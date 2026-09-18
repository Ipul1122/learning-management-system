<?php

use App\Models\Badge;
use App\Models\Branch;
use App\Models\ClassEnrollment;
use App\Models\ClassSession;
use App\Models\Question;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\TrainingClass;
use App\Models\User;
use App\Services\GamificationService;
use Database\Seeders\GamificationSeeder;

beforeEach(function () {
    $this->seed(\Database\Seeders\RolePermissionSeeder::class);
    $this->seed(GamificationSeeder::class);

    $this->branch = Branch::create([
        'code' => 'JKT',
        'name' => 'Cabang Jakarta',
        'city' => 'Jakarta',
        'address' => 'Jl. Thamrin No. 1',
        'is_active' => true,
    ]);

    $this->trainer = User::factory()->create([
        'name' => 'Trainer Master',
        'email' => 'trainer.master@lms.test',
        'branch_id' => $this->branch->id,
    ]);
    $this->trainer->assignRole('trainer');

    $this->peserta = User::factory()->create([
        'name' => 'Peserta Uji',
        'email' => 'peserta.uji@lms.test',
        'branch_id' => $this->branch->id,
        'total_points' => 0,
        'level' => 1,
    ]);
    $this->peserta->assignRole('peserta');

    $this->class = TrainingClass::create([
        'branch_id' => $this->branch->id,
        'trainer_id' => $this->trainer->id,
        'title' => 'Kelas Pemrograman Web Modern',
        'slug' => 'kelas-pemrograman-web-modern',
        'description' => 'Materi pelatihan kejuruan intensif.',
        'type' => 'online',
        'status' => 'open',
        'delivery_mode' => 'online',
        'quota_offline' => 0,
        'quota_online' => 100,
        'enrolled_offline' => 0,
        'enrolled_online' => 0,
        'start_date' => now()->toDateString(),
        'end_date' => now()->addDays(14)->toDateString(),
    ]);
});

test('service gamifikasi dapat memberikan poin xp dan menaikkan level', function () {
    $service = app(GamificationService::class);

    $service->awardPoints($this->peserta, 150, 'quiz', 'Menyelesaikan Kuis 1');
    $this->peserta->refresh();

    expect($this->peserta->total_points)->toBe(150)
        ->and($this->peserta->level)->toBe(1);

    // Tambah 100 poin lagi (total 250), harus naik ke Level 2 (karena >= 200)
    $service->awardPoints($this->peserta, 100, 'quiz', 'Menyelesaikan Kuis 2');
    $this->peserta->refresh();

    expect($this->peserta->total_points)->toBe(250)
        ->and($this->peserta->level)->toBe(2)
        ->and($this->peserta->rank_title)->toBe('Penjelajah (Explorer)');
});

test('lencana langkah awal terbuka otomatis saat peserta terdaftar di kelas', function () {
    $service = app(GamificationService::class);

    ClassEnrollment::create([
        'class_id' => $this->class->id,
        'user_id' => $this->peserta->id,
        'attendance_mode' => 'online',
        'accumulated_minutes' => 0,
        'accumulated_jp' => 0.0,
        'status' => 'enrolled',
        'enrolled_at' => now(),
    ]);

    $newBadges = $service->checkAndAwardBadges($this->peserta);

    expect(count($newBadges))->toBeGreaterThanOrEqual(1)
        ->and($this->peserta->badges()->where('slug', 'langkah-awal')->exists())->toBeTrue();
});

test('lencana juara 20 jp terbuka saat akumulasi waktu mencapai 900 menit', function () {
    $service = app(GamificationService::class);

    ClassEnrollment::create([
        'class_id' => $this->class->id,
        'user_id' => $this->peserta->id,
        'attendance_mode' => 'online',
        'accumulated_minutes' => 900,
        'accumulated_jp' => 20.0,
        'status' => 'in_progress',
        'enrolled_at' => now(),
    ]);

    $service->checkAndAwardBadges($this->peserta);

    expect($this->peserta->badges()->where('slug', 'juara-20-jp')->exists())->toBeTrue()
        ->and($this->peserta->badges()->where('slug', 'pejuang-waktu')->exists())->toBeTrue();
});

test('halaman leaderboard dapat diakses oleh peserta dan menampilkan peringkat', function () {
    $response = $this->actingAs($this->peserta)->get(route('peserta.leaderboard'));

    $response->assertOk()
        ->assertViewIs('peserta.gamification.leaderboard')
        ->assertSee('Leaderboard Peserta')
        ->assertSee('Papan Peringkat Kompetensi Nasional')
        ->assertSee($this->peserta->name);
});

test('halaman leaderboard dapat difilter berdasarkan cabang', function () {
    $otherBranch = Branch::create([
        'code' => 'SBY',
        'name' => 'Cabang Surabaya',
        'city' => 'Surabaya',
        'address' => 'Jl. Pemuda No. 2',
        'is_active' => true,
    ]);

    $pesertaSby = User::factory()->create([
        'name' => 'Peserta Arek',
        'email' => 'arek@lms.test',
        'branch_id' => $otherBranch->id,
        'total_points' => 500,
    ]);
    $pesertaSby->assignRole('peserta');

    $response = $this->actingAs($this->peserta)->get(route('peserta.leaderboard', ['branch_id' => $otherBranch->id]));

    $response->assertOk()
        ->assertSee($pesertaSby->name);
});

test('halaman koleksi lencana menampilkan lencana yang diraih dan yang masih terkunci', function () {
    $response = $this->actingAs($this->peserta)->get(route('peserta.badges'));

    $response->assertOk()
        ->assertViewIs('peserta.gamification.badges')
        ->assertSee('Koleksi Lencana Prestasi')
        ->assertSee('Langkah Awal')
        ->assertSee('Master 20 JP');
});

test('pengguna tamu tidak dapat mengakses halaman leaderboard tanpa login', function () {
    $response = $this->get(route('peserta.leaderboard'));

    $response->assertRedirect(route('login'));
});
