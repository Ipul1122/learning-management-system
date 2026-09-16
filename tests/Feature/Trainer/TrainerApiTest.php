<?php

use App\Models\ClassSession;
use App\Models\Question;
use App\Models\TrainingClass;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

beforeEach(function () {
    /** @var TestCase $this */
    $this->seed();
});

test('api trainer: dapat melihat daftar dan membuat bank soal', function () {
    /** @var TestCase $this */
    $trainer = User::where('email', 'trainer1@lms.test')->first();
    Sanctum::actingAs($trainer);

    $indexRes = $this->getJson(route('api.v1.trainer.questions.index'));
    $indexRes->assertStatus(200);

    $payload = [
        'question_text' => 'Soal dari REST API',
        'question_type' => 'multiple_choice',
        'options' => [
            ['option_text' => 'Pilihan 1', 'is_correct' => '1'],
            ['option_text' => 'Pilihan 2', 'is_correct' => '0'],
        ],
    ];

    $storeRes = $this->postJson(route('api.v1.trainer.questions.store'), $payload);
    $storeRes->assertStatus(201);
    $storeRes->assertJsonPath('data.question_text', 'Soal dari REST API');
    $storeRes->assertJsonPath('data.score_weight', 1);

    $questionId = $storeRes->json('data.id');

    // Show API
    $showRes = $this->getJson(route('api.v1.trainer.questions.show', $questionId));
    $showRes->assertStatus(200);

    // Update API
    $updateRes = $this->putJson(route('api.v1.trainer.questions.update', $questionId), [
        'question_text' => 'Soal dari REST API (Updated)',
        'question_type' => 'multiple_choice',
        'options' => [
            ['option_text' => 'Pilihan 1 Baru', 'is_correct' => '1'],
            ['option_text' => 'Pilihan 2 Baru', 'is_correct' => '0'],
        ],
    ]);
    $updateRes->assertStatus(200);
    $updateRes->assertJsonPath('data.question_text', 'Soal dari REST API (Updated)');

    // Delete API
    $deleteRes = $this->deleteJson(route('api.v1.trainer.questions.destroy', $questionId));
    $deleteRes->assertStatus(200);
});

test('api trainer: dapat membuat dan mengelola paket kuis', function () {
    /** @var TestCase $this */
    $trainer = User::where('email', 'trainer1@lms.test')->first();
    Sanctum::actingAs($trainer);

    $class = TrainingClass::where('branch_id', $trainer->branch_id)->where('trainer_id', $trainer->id)->first();
    if (! $class) {
        $class = TrainingClass::create([
            'branch_id' => $trainer->branch_id,
            'trainer_id' => $trainer->id,
            'title' => 'Kelas API Quiz Test',
            'slug' => 'kelas-api-quiz-test',
            'type' => 'online',
            'online_capacity' => 50,
            'required_jp' => 20,
            'start_date' => now()->toDateString(),
            'end_date' => now()->addDays(14)->toDateString(),
            'status' => 'ongoing',
        ]);
    }

    $q = Question::create([
        'branch_id' => $trainer->branch_id,
        'creator_id' => $trainer->id,
        'question_text' => 'Soal Untuk Kuis API',
        'question_type' => 'essay',
        'score_weight' => 1,
    ]);

    $payload = [
        'class_id' => $class->id,
        'title' => 'Kuis Dari REST API',
        'time_limit_minutes' => 40,
        'passing_grade' => 75.5,
        'is_randomized' => true,
        'max_attempts' => 1,
        'questions' => [$q->id],
    ];

    $storeRes = $this->postJson(route('api.v1.trainer.quizzes.store'), $payload);
    $storeRes->assertStatus(201);
    $storeRes->assertJsonPath('data.title', 'Kuis Dari REST API');

    $quizId = $storeRes->json('data.id');

    // Index API
    $indexRes = $this->getJson(route('api.v1.trainer.quizzes.index'));
    $indexRes->assertStatus(200);

    // Show API
    $showRes = $this->getJson(route('api.v1.trainer.quizzes.show', $quizId));
    $showRes->assertStatus(200);

    // Update API
    $updateRes = $this->putJson(route('api.v1.trainer.quizzes.update', $quizId), [
        'class_id' => $class->id,
        'title' => 'Kuis Dari REST API (Updated)',
        'time_limit_minutes' => 60,
        'passing_grade' => 80.0,
        'is_randomized' => false,
        'max_attempts' => 2,
        'questions' => [$q->id],
    ]);
    $updateRes->assertStatus(200);
    $updateRes->assertJsonPath('data.title', 'Kuis Dari REST API (Updated)');

    // Delete API
    $deleteRes = $this->deleteJson(route('api.v1.trainer.quizzes.destroy', $quizId));
    $deleteRes->assertStatus(200);
});

test('api trainer: dapat melihat kelas dan memperbarui tautan Zoom sesi', function () {
    /** @var TestCase $this */
    $trainer = User::where('email', 'trainer1@lms.test')->first();
    Sanctum::actingAs($trainer);

    $class = TrainingClass::where('branch_id', $trainer->branch_id)->where('trainer_id', $trainer->id)->first();
    if (! $class) {
        $class = TrainingClass::create([
            'branch_id' => $trainer->branch_id,
            'trainer_id' => $trainer->id,
            'title' => 'Kelas API Zoom Test',
            'slug' => 'kelas-api-zoom-test',
            'type' => 'online',
            'online_capacity' => 50,
            'required_jp' => 20,
            'start_date' => now()->toDateString(),
            'end_date' => now()->addDays(14)->toDateString(),
            'status' => 'ongoing',
        ]);
    }

    $session = ClassSession::create([
        'class_id' => $class->id,
        'session_order' => 1,
        'title' => 'Sesi Zoom API',
        'jp_duration' => 2,
        'minute_duration' => 90,
        'session_date' => now()->addDay(),
        'created_by_user_id' => $trainer->id,
    ]);

    // Index Classes API
    $classesRes = $this->getJson(route('api.v1.trainer.classes.index'));
    $classesRes->assertStatus(200);

    // Update Zoom API
    $zoomRes = $this->patchJson(route('api.v1.trainer.classes.sessions.zoom', [$class, $session]), [
        'zoom_url' => 'https://zoom.us/j/1234567890',
        'zoom_meeting_id' => '123 456 7890',
        'zoom_passcode' => '998877',
    ]);
    $zoomRes->assertStatus(200);
    $zoomRes->assertJsonPath('session.zoom_url', 'https://zoom.us/j/1234567890');
    $zoomRes->assertJsonPath('session.zoom_meeting_id', '123 456 7890');
    $zoomRes->assertJsonPath('session.zoom_passcode', '998877');
});
