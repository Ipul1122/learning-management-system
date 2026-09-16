<?php

use App\Models\ClassEnrollment;
use App\Models\ForumReply;
use App\Models\ForumThread;
use App\Models\TrainingClass;
use App\Models\User;
use Tests\TestCase;

beforeEach(function () {
    /** @var TestCase $this */
    $this->seed();
});

function createForumTestSetup(): array
{
    $trainer = User::where('email', 'trainer1@lms.test')->first();
    $student1 = User::where('email', 'peserta1@lms.test')->first();
    $student2 = User::where('email', 'peserta2@lms.test')->first();

    $class = TrainingClass::firstOrCreate([
        'slug' => 'kelas-forum-test',
    ], [
        'branch_id' => $trainer->branch_id,
        'trainer_id' => $trainer->id,
        'title' => 'Kelas Forum Komunitas Test',
        'type' => 'online',
        'online_capacity' => 40,
        'required_jp' => 20,
        'start_date' => now()->toDateString(),
        'end_date' => now()->addDays(14)->toDateString(),
        'status' => 'ongoing',
    ]);

    // Enroll student 1
    ClassEnrollment::firstOrCreate([
        'class_id' => $class->id,
        'user_id' => $student1->id,
    ], [
        'status' => 'enrolled',
        'total_minutes_accumulated' => 120,
        'enrolled_at' => now(),
    ]);

    return compact('trainer', 'student1', 'student2', 'class');
}

test('peserta terdaftar dapat melihat forum dan membuat topik diskusi baru', function () {
    /** @var TestCase $this */
    ['trainer' => $trainer, 'student1' => $student1, 'class' => $class] = createForumTestSetup();

    $responseList = $this->actingAs($student1)->get(route('classes.forum.index', $class));
    $responseList->assertStatus(200);
    $responseList->assertSee('Forum Komunitas');

    $payload = [
        'title' => 'Pertanyaan mengenai materi instalasi Docker pada Sesi 2',
        'content' => 'Apakah kita wajib menggunakan WSL2 atau bisa langsung native di Linux?',
    ];

    $responseStore = $this->actingAs($student1)->post(route('classes.forum.store', $class), $payload);

    $responseStore->assertRedirect();
    $this->assertDatabaseHas('forum_threads', [
        'class_id' => $class->id,
        'author_id' => $student1->id,
        'title' => 'Pertanyaan mengenai materi instalasi Docker pada Sesi 2',
    ]);
});

test('peserta yang belum terdaftar di kelas ditolak aksesnya (403)', function () {
    /** @var TestCase $this */
    ['student2' => $student2, 'class' => $class] = createForumTestSetup();

    $response = $this->actingAs($student2)->get(route('classes.forum.index', $class));
    $response->assertStatus(403);

    $responsePost = $this->actingAs($student2)->post(route('classes.forum.store', $class), [
        'title' => 'Topik Ilegal',
        'content' => 'Konten topik ilegal',
    ]);
    $responsePost->assertStatus(403);
});

test('peserta terdaftar dapat mengirim balasan utama dan balasan bertingkat (nested)', function () {
    /** @var TestCase $this */
    ['student1' => $student1, 'trainer' => $trainer, 'class' => $class] = createForumTestSetup();

    $thread = ForumThread::create([
        'class_id' => $class->id,
        'author_id' => $student1->id,
        'title' => 'Diskusi Modul Database',
        'content' => 'Bagaimana cara migrasi schema yang aman di production?',
    ]);

    // Trainer membalas balasan utama
    $replyResponse = $this->actingAs($trainer)->post(route('classes.forum.reply', [$class, $thread]), [
        'reply_content' => 'Gunakan fitur zero-downtime migration dan rollback strategy.',
    ]);
    $replyResponse->assertRedirect(route('classes.forum.show', [$class, $thread]));

    $rootReply = ForumReply::where('thread_id', $thread->id)->whereNull('parent_reply_id')->first();
    $this->assertNotNull($rootReply);
    $this->assertEquals('Gunakan fitur zero-downtime migration dan rollback strategy.', $rootReply->reply_content);

    // Student membalas balasan trainer (nested reply)
    $nestedResponse = $this->actingAs($student1)->post(route('classes.forum.reply', [$class, $thread]), [
        'parent_reply_id' => $rootReply->id,
        'reply_content' => 'Terima kasih atas sarannya pak, akan kami coba praktikkan!',
    ]);
    $nestedResponse->assertRedirect(route('classes.forum.show', [$class, $thread]));

    $this->assertDatabaseHas('forum_replies', [
        'thread_id' => $thread->id,
        'author_id' => $student1->id,
        'parent_reply_id' => $rootReply->id,
        'reply_content' => 'Terima kasih atas sarannya pak, akan kami coba praktikkan!',
    ]);
});

test('trainer kelas dapat menyematkan (pin) dan melepas pin topik diskusi', function () {
    /** @var TestCase $this */
    ['trainer' => $trainer, 'student1' => $student1, 'class' => $class] = createForumTestSetup();

    $thread = ForumThread::create([
        'class_id' => $class->id,
        'author_id' => $student1->id,
        'title' => 'Peraturan dan Tata Tertib Diskusi Kelas',
        'content' => 'Harap saling menghormati dan tidak melakukan spam.',
        'is_pinned' => false,
    ]);

    // Trainer pin thread
    $responsePin = $this->actingAs($trainer)->patch(route('classes.forum.pin', [$class, $thread]));
    $responsePin->assertRedirect();
    $this->assertTrue($thread->fresh()->is_pinned);

    // Trainer unpin thread
    $responseUnpin = $this->actingAs($trainer)->patch(route('classes.forum.pin', [$class, $thread]));
    $responseUnpin->assertRedirect();
    $this->assertFalse($thread->fresh()->is_pinned);

    // Peserta biasa tidak diizinkan pin thread
    $responseStudentPin = $this->actingAs($student1)->patch(route('classes.forum.pin', [$class, $thread]));
    $responseStudentPin->assertStatus(403);
});

test('topik yang dikunci (locked) oleh trainer tidak dapat menerima balasan baru', function () {
    /** @var TestCase $this */
    ['trainer' => $trainer, 'student1' => $student1, 'class' => $class] = createForumTestSetup();

    $thread = ForumThread::create([
        'class_id' => $class->id,
        'author_id' => $trainer->id,
        'title' => 'Pengumuman Resmi Ditutup',
        'content' => 'Diskusi ini sudah selesai dan dikunci.',
        'is_locked' => false,
    ]);

    // Lock thread
    $responseLock = $this->actingAs($trainer)->patch(route('classes.forum.lock', [$class, $thread]));
    $responseLock->assertRedirect();
    $this->assertTrue($thread->fresh()->is_locked);

    // Student coba membalas topik yang terkunci
    $responseReply = $this->actingAs($student1)->post(route('classes.forum.reply', [$class, $thread]), [
        'reply_content' => 'Mencoba membalas topik terkunci.',
    ]);
    $responseReply->assertRedirect();
    $responseReply->assertSessionHas('error');

    $this->assertDatabaseMissing('forum_replies', [
        'thread_id' => $thread->id,
        'reply_content' => 'Mencoba membalas topik terkunci.',
    ]);
});

test('pembuat topik atau trainer dapat menghapus topik diskusi', function () {
    /** @var TestCase $this */
    ['trainer' => $trainer, 'student1' => $student1, 'class' => $class] = createForumTestSetup();

    $thread = ForumThread::create([
        'class_id' => $class->id,
        'author_id' => $student1->id,
        'title' => 'Topik yang Ingin Dihapus',
        'content' => 'Sudah tidak relevan.',
    ]);

    $responseDelete = $this->actingAs($student1)->delete(route('classes.forum.destroy', [$class, $thread]));
    $responseDelete->assertRedirect(route('classes.forum.index', $class));

    $this->assertDatabaseMissing('forum_threads', [
        'id' => $thread->id,
    ]);
});
