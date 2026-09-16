<?php

use App\Models\ClassEnrollment;
use App\Models\ForumThread;
use App\Models\NewsPost;
use App\Models\TrainingClass;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

beforeEach(function () {
    /** @var TestCase $this */
    $this->seed();
});

test('api: user dapat mengambil daftar berita yang dipublikasikan', function () {
    /** @var TestCase $this */
    $user = User::where('email', 'peserta1@lms.test')->first();
    $admin = User::where('email', 'superadmin@lms.test')->first();

    NewsPost::create([
        'author_id' => $admin->id,
        'title' => 'API Info Pembelajaran Terpadu',
        'slug' => 'api-info-pembelajaran-terpadu',
        'category' => 'informasi',
        'content' => 'Konten pengumuman API',
        'is_published' => true,
        'published_at' => now(),
    ]);

    NewsPost::create([
        'author_id' => $admin->id,
        'title' => 'Draft Rahasia Internal',
        'slug' => 'draft-rahasia-internal-api',
        'category' => 'pengumuman',
        'content' => 'Draft tidak boleh muncul di API publik',
        'is_published' => false,
        'published_at' => null,
    ]);

    Sanctum::actingAs($user);

    $response = $this->getJson('/api/v1/news');

    $response->assertStatus(200);
    $response->assertJsonStructure([
        'data' => [
            '*' => ['id', 'title', 'slug', 'category', 'is_published', 'published_at', 'author'],
        ],
    ]);
    $response->assertJsonFragment(['slug' => 'api-info-pembelajaran-terpadu']);
    $response->assertJsonMissing(['slug' => 'draft-rahasia-internal-api']);
});

test('api: user dapat mengambil detail berita yang dipublikasikan', function () {
    /** @var TestCase $this */
    $user = User::where('email', 'peserta1@lms.test')->first();
    $admin = User::where('email', 'superadmin@lms.test')->first();

    $news = NewsPost::create([
        'author_id' => $admin->id,
        'title' => 'API Detail Berita',
        'slug' => 'api-detail-berita',
        'category' => 'edukasi',
        'content' => 'Detail lengkap artikel berita via API',
        'is_published' => true,
        'published_at' => now(),
    ]);

    Sanctum::actingAs($user);

    $response = $this->getJson('/api/v1/news/'.$news->slug);

    $response->assertStatus(200);
    $response->assertJsonPath('data.title', 'API Detail Berita');
    $response->assertJsonPath('data.category', 'edukasi');
});

test('api: admin dapat membuat berita baru via endpoint api', function () {
    /** @var TestCase $this */
    $superAdmin = User::where('email', 'superadmin@lms.test')->first();

    Sanctum::actingAs($superAdmin);

    $payload = [
        'title' => 'Berita Baru via API Endpoint',
        'category' => 'kegiatan',
        'content' => 'Konten dikirim melalui API client.',
        'is_published' => true,
    ];

    $response = $this->postJson('/api/v1/news', $payload);

    $response->assertStatus(201);
    $response->assertJsonPath('data.title', 'Berita Baru via API Endpoint');
    $this->assertDatabaseHas('news_posts', [
        'title' => 'Berita Baru via API Endpoint',
        'is_published' => true,
    ]);
});

test('api: peserta terdaftar dapat mengakses forum dan membuat topik', function () {
    /** @var TestCase $this */
    $trainer = User::where('email', 'trainer1@lms.test')->first();
    $student = User::where('email', 'peserta1@lms.test')->first();

    $class = TrainingClass::firstOrCreate([
        'slug' => 'kelas-api-forum-test',
    ], [
        'branch_id' => $trainer->branch_id,
        'trainer_id' => $trainer->id,
        'title' => 'Kelas API Forum Test',
        'type' => 'online',
        'online_capacity' => 30,
        'required_jp' => 20,
        'start_date' => now()->toDateString(),
        'end_date' => now()->addDays(14)->toDateString(),
        'status' => 'ongoing',
    ]);

    ClassEnrollment::firstOrCreate([
        'class_id' => $class->id,
        'user_id' => $student->id,
    ], [
        'status' => 'enrolled',
        'total_minutes_accumulated' => 60,
        'enrolled_at' => now(),
    ]);

    Sanctum::actingAs($student);

    // List forum threads
    $listResponse = $this->getJson("/api/v1/classes/{$class->id}/forum");
    $listResponse->assertStatus(200);

    // Create thread
    $threadResponse = $this->postJson("/api/v1/classes/{$class->id}/forum", [
        'title' => 'Pertanyaan Teknis REST API',
        'content' => 'Bagaimana otentikasi Bearer token bekerja?',
    ]);

    $threadResponse->assertStatus(201);
    $threadResponse->assertJsonPath('data.title', 'Pertanyaan Teknis REST API');
    $threadId = $threadResponse->json('data.id');

    // Create reply
    $replyResponse = $this->postJson("/api/v1/classes/{$class->id}/forum/{$threadId}/replies", [
        'reply_content' => 'Token dikirim via header Authorization.',
    ]);
    $replyResponse->assertStatus(201);
    $replyResponse->assertJsonPath('data.reply_content', 'Token dikirim via header Authorization.');
});

test('api: peserta tidak terdaftar ditolak mengakses forum kelas (403)', function () {
    /** @var TestCase $this */
    $trainer = User::where('email', 'trainer1@lms.test')->first();
    $studentNotEnrolled = User::where('email', 'peserta2@lms.test')->first();

    $class = TrainingClass::firstOrCreate([
        'slug' => 'kelas-api-forum-locked',
    ], [
        'branch_id' => $trainer->branch_id,
        'trainer_id' => $trainer->id,
        'title' => 'Kelas API Forum Locked',
        'type' => 'online',
        'online_capacity' => 30,
        'required_jp' => 20,
        'start_date' => now()->toDateString(),
        'end_date' => now()->addDays(14)->toDateString(),
        'status' => 'ongoing',
    ]);

    Sanctum::actingAs($studentNotEnrolled);

    $response = $this->getJson("/api/v1/classes/{$class->id}/forum");
    $response->assertStatus(403);
});

test('api: trainer kelas dapat pin dan lock thread via endpoint api', function () {
    /** @var TestCase $this */
    $trainer = User::where('email', 'trainer1@lms.test')->first();

    $class = TrainingClass::firstOrCreate([
        'slug' => 'kelas-api-forum-mod',
    ], [
        'branch_id' => $trainer->branch_id,
        'trainer_id' => $trainer->id,
        'title' => 'Kelas API Forum Mod',
        'type' => 'online',
        'online_capacity' => 30,
        'required_jp' => 20,
        'start_date' => now()->toDateString(),
        'end_date' => now()->addDays(14)->toDateString(),
        'status' => 'ongoing',
    ]);

    $thread = ForumThread::create([
        'class_id' => $class->id,
        'author_id' => $trainer->id,
        'title' => 'Diskusi Silabus Resmi',
        'content' => 'Silakan baca silabus sebelum bertanya.',
        'is_pinned' => false,
        'is_locked' => false,
    ]);

    Sanctum::actingAs($trainer);

    // Toggle pin
    $pinResponse = $this->patchJson("/api/v1/classes/{$class->id}/forum/{$thread->id}/pin");
    $pinResponse->assertStatus(200);
    $this->assertTrue($thread->fresh()->is_pinned);

    // Toggle lock
    $lockResponse = $this->patchJson("/api/v1/classes/{$class->id}/forum/{$thread->id}/lock");
    $lockResponse->assertStatus(200);
    $this->assertTrue($thread->fresh()->is_locked);
});
