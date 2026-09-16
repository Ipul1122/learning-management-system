<?php

use App\Models\Branch;
use App\Models\ClassEnrollment;
use App\Models\ForumThread;
use App\Models\NewsPost;
use App\Models\Question;
use App\Models\TrainingClass;
use App\Models\User;
use Tests\TestCase;

beforeEach(function () {
    /** @var TestCase $this */
    $this->seed();
});

test('katalog kelas peserta mempertahankan parameter filter pada URL pagination', function () {
    /** @var TestCase $this */
    $peserta = User::factory()->create();
    $peserta->assignRole('peserta');

    $branch = Branch::first();
    $trainer = User::factory()->create(['branch_id' => $branch->id]);
    $trainer->assignRole('trainer');

    // Buat 15 kelas agar memicu pagination (perPage = 9)
    for ($i = 1; $i <= 15; $i++) {
        TrainingClass::create([
            'branch_id' => $branch->id,
            'trainer_id' => $trainer->id,
            'title' => 'Kelas Online Khusus Laravel '.$i,
            'slug' => 'kelas-online-khusus-laravel-'.$i.'-'.uniqid(),
            'description' => 'Materi pelatihan komprehensif',
            'type' => 'online',
            'status' => 'open',
            'quota' => 50,
            'required_jp' => 20.0,
            'start_date' => now()->toDateString(),
            'end_date' => now()->addDays(7)->toDateString(),
        ]);
    }

    $response = $this->actingAs($peserta)->get(route('peserta.catalog.index', [
        'search' => 'Laravel',
        'type' => 'online',
        'branch_id' => $branch->id,
    ]));

    $response->assertStatus(200);

    // Pastikan link pagination halaman 2 mempertahankan query parameter
    $response->assertSee('search=Laravel');
    $response->assertSee('type=online');
    $response->assertSee('branch_id='.$branch->id);
    $response->assertSee('page=2');
});

test('bank soal trainer mempertahankan query pencarian pada URL pagination', function () {
    /** @var TestCase $this */
    $branch = Branch::first();
    $trainer = User::factory()->create(['branch_id' => $branch->id]);
    $trainer->assignRole('trainer');

    // Buat 20 soal agar memicu pagination (perPage = 15)
    for ($i = 1; $i <= 20; $i++) {
        Question::create([
            'branch_id' => $branch->id,
            'creator_id' => $trainer->id,
            'type' => 'multiple_choice',
            'question_text' => 'Soal Algoritma Khusus Ujian #'.$i,
            'points' => 1,
        ]);
    }

    $response = $this->actingAs($trainer)->get(route('trainer.questions.index', [
        'search' => 'Algoritma',
    ]));

    $response->assertStatus(200);
    $response->assertSee('search=Algoritma');
    $response->assertSee('page=2');
});

test('portal berita mempertahankan filter pencarian pada URL pagination', function () {
    /** @var TestCase $this */
    $user = User::factory()->create();
    $user->assignRole('peserta');

    $superAdmin = User::factory()->create();
    $superAdmin->assignRole('super-admin');

    // Buat 12 berita agar memicu pagination (perPage = 9)
    for ($i = 1; $i <= 12; $i++) {
        NewsPost::create([
            'title' => 'Pengumuman Penting Kampus '.$i,
            'slug' => 'pengumuman-penting-kampus-'.$i.'-'.uniqid(),
            'content' => 'Isi pengumuman lengkap tentang kegiatan.',
            'category' => 'announcement',
            'is_featured' => false,
            'published_at' => now(),
            'author_id' => $superAdmin->id,
        ]);
    }

    $response = $this->actingAs($user)->get(route('news.index', [
        'search' => 'Pengumuman',
    ]));

    $response->assertStatus(200);
    $response->assertSee('search=Pengumuman');
    $response->assertSee('page=2');
});

test('ruang belajar peserta (study room) menyertakan pagination dan query string', function () {
    /** @var TestCase $this */
    $peserta = User::factory()->create();
    $peserta->assignRole('peserta');

    $branch = Branch::first();
    $trainer = User::factory()->create(['branch_id' => $branch->id]);
    $trainer->assignRole('trainer');

    // Daftarkan ke 12 kelas (perPage = 9)
    for ($i = 1; $i <= 12; $i++) {
        $class = TrainingClass::create([
            'branch_id' => $branch->id,
            'trainer_id' => $trainer->id,
            'title' => 'Kelas Pelatihan #'.$i,
            'slug' => 'kelas-pelatihan-'.$i.'-'.uniqid(),
            'type' => 'online',
            'status' => 'ongoing',
            'quota' => 50,
            'required_jp' => 20.0,
            'start_date' => now()->toDateString(),
            'end_date' => now()->addDays(7)->toDateString(),
        ]);

        ClassEnrollment::create([
            'user_id' => $peserta->id,
            'class_id' => $class->id,
            'attendance_mode' => 'online',
            'accumulated_minutes' => 90,
            'accumulated_jp' => 2.0,
            'status' => 'in_progress',
        ]);
    }

    $response = $this->actingAs($peserta)->get(route('peserta.study.index'));

    $response->assertStatus(200);
    $response->assertSee('page=2');
});

test('forum diskusi kelas menyertakan pagination dan query string', function () {
    /** @var TestCase $this */
    $branch = Branch::first();
    $trainer = User::factory()->create(['branch_id' => $branch->id]);
    $trainer->assignRole('trainer');

    $peserta = User::factory()->create(['branch_id' => $branch->id]);
    $peserta->assignRole('peserta');

    $class = TrainingClass::create([
        'branch_id' => $branch->id,
        'trainer_id' => $trainer->id,
        'title' => 'Kelas Forum Diskusi',
        'slug' => 'kelas-forum-diskusi-'.uniqid(),
        'type' => 'online',
        'status' => 'ongoing',
        'quota' => 50,
        'required_jp' => 20.0,
        'start_date' => now()->toDateString(),
        'end_date' => now()->addDays(7)->toDateString(),
    ]);

    ClassEnrollment::create([
        'user_id' => $peserta->id,
        'class_id' => $class->id,
        'attendance_mode' => 'online',
        'status' => 'in_progress',
    ]);

    // Buat 18 thread diskusi (perPage = 15)
    for ($i = 1; $i <= 18; $i++) {
        ForumThread::create([
            'class_id' => $class->id,
            'author_id' => $peserta->id,
            'title' => 'Topik Diskusi #'.$i,
            'content' => 'Isi materi diskusi thread #'.$i,
            'is_pinned' => false,
            'is_locked' => false,
        ]);
    }

    $response = $this->actingAs($peserta)->get(route('classes.forum.index', $class));

    $response->assertStatus(200);
    $response->assertSee('page=2');
});
