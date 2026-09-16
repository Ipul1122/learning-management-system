<?php

use App\Models\Branch;
use App\Models\ClassEnrollment;
use App\Models\ClassSession;
use App\Models\Question;
use App\Models\QuestionOption;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\TrainingClass;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

beforeEach(function () {
    /** @var TestCase $this */
    $this->seed();
});

test('api peserta dapat melihat katalog kelas', function () {
    /** @var TestCase $this */
    $peserta = User::where('email', 'peserta1@lms.test')->first();
    Sanctum::actingAs($peserta, ['*']);

    $response = $this->getJson(route('api.v1.peserta.catalog.index'));
    $response->assertStatus(200);
    $response->assertJsonStructure(['data', 'current_page']);
});

test('api peserta dapat mendaftar kelas', function () {
    /** @var TestCase $this */
    $peserta = User::where('email', 'peserta1@lms.test')->first();
    $trainer = User::where('email', 'trainer1@lms.test')->first();
    $branch = Branch::first();
    Sanctum::actingAs($peserta, ['*']);

    $class = TrainingClass::create([
        'branch_id' => $branch->id,
        'trainer_id' => $trainer->id,
        'title' => 'Kelas API Peserta',
        'slug' => 'kelas-api-peserta',
        'type' => 'online',
        'online_capacity' => 50,
        'required_jp' => 20,
        'start_date' => now()->toDateString(),
        'end_date' => now()->addDays(14)->toDateString(),
        'status' => 'open',
    ]);

    $response = $this->postJson(route('api.v1.peserta.catalog.enroll', $class), [
        'attendance_mode' => 'online',
    ]);

    $response->assertStatus(201);
    $response->assertJsonPath('data.attendance_mode', 'online');
    $this->assertDatabaseHas('class_enrollments', [
        'class_id' => $class->id,
        'user_id' => $peserta->id,
    ]);
});

test('api peserta dapat melihat daftar kelas belajar dan akumulasi 20 JP', function () {
    /** @var TestCase $this */
    $peserta = User::where('email', 'peserta1@lms.test')->first();
    $trainer = User::where('email', 'trainer1@lms.test')->first();
    $branch = Branch::first();
    Sanctum::actingAs($peserta, ['*']);

    $class = TrainingClass::create([
        'branch_id' => $branch->id,
        'trainer_id' => $trainer->id,
        'title' => 'Kelas API Study',
        'slug' => 'kelas-api-study',
        'type' => 'online',
        'online_capacity' => 50,
        'required_jp' => 20,
        'start_date' => now()->toDateString(),
        'end_date' => now()->addDays(14)->toDateString(),
        'status' => 'ongoing',
    ]);

    ClassEnrollment::create([
        'class_id' => $class->id,
        'user_id' => $peserta->id,
        'attendance_mode' => 'online',
        'accumulated_minutes' => 180, // 4 JP
        'accumulated_jp' => 4.00,
        'status' => 'enrolled',
        'enrolled_at' => now(),
    ]);

    $response = $this->getJson(route('api.v1.peserta.study.index'));
    $response->assertStatus(200);
    $response->assertJsonPath('total_accumulated_minutes', 180);
    $response->assertJsonPath('total_accumulated_jp', 4);
});

test('api peserta masuk zoom mencatat presensi dan mengembalikan tautan zoom', function () {
    /** @var TestCase $this */
    $peserta = User::where('email', 'peserta1@lms.test')->first();
    $trainer = User::where('email', 'trainer1@lms.test')->first();
    $branch = Branch::first();
    Sanctum::actingAs($peserta, ['*']);

    $class = TrainingClass::create([
        'branch_id' => $branch->id,
        'trainer_id' => $trainer->id,
        'title' => 'Kelas API Zoom',
        'slug' => 'kelas-api-zoom',
        'type' => 'online',
        'online_capacity' => 50,
        'required_jp' => 20,
        'start_date' => now()->toDateString(),
        'end_date' => now()->addDays(14)->toDateString(),
        'status' => 'ongoing',
    ]);

    $enrollment = ClassEnrollment::create([
        'class_id' => $class->id,
        'user_id' => $peserta->id,
        'attendance_mode' => 'online',
        'accumulated_minutes' => 0,
        'accumulated_jp' => 0.00,
        'status' => 'enrolled',
        'enrolled_at' => now(),
    ]);

    $session = ClassSession::create([
        'class_id' => $class->id,
        'session_order' => 1,
        'title' => 'Sesi Zoom API',
        'jp_duration' => 2,
        'minute_duration' => 90,
        'session_date' => now()->addDay(),
        'zoom_url' => 'https://zoom.us/j/111222333',
        'created_by_user_id' => $peserta->id,
    ]);

    $response = $this->postJson(route('api.v1.peserta.study.zoom', [$class, $session]));
    $response->assertStatus(200);
    $response->assertJsonPath('zoom_url', 'https://zoom.us/j/111222333');

    $enrollment->refresh();
    expect($enrollment->accumulated_minutes)->toBe(90);
    expect((float) $enrollment->accumulated_jp)->toBe(2.0);
});

test('api peserta dapat memulai dan mengirimkan jawaban kuis dengan auto-grading instan', function () {
    /** @var TestCase $this */
    $peserta = User::where('email', 'peserta1@lms.test')->first();
    $trainer = User::where('email', 'trainer1@lms.test')->first();
    $branch = Branch::first();
    Sanctum::actingAs($peserta, ['*']);

    $class = TrainingClass::create([
        'branch_id' => $branch->id,
        'trainer_id' => $trainer->id,
        'title' => 'Kelas API Quiz',
        'slug' => 'kelas-api-quiz',
        'type' => 'online',
        'online_capacity' => 50,
        'required_jp' => 20,
        'start_date' => now()->toDateString(),
        'end_date' => now()->addDays(14)->toDateString(),
        'status' => 'ongoing',
    ]);

    ClassEnrollment::create([
        'class_id' => $class->id,
        'user_id' => $peserta->id,
        'attendance_mode' => 'online',
        'accumulated_minutes' => 90,
        'accumulated_jp' => 2.00,
        'status' => 'enrolled',
        'enrolled_at' => now(),
    ]);

    $quiz = Quiz::create([
        'class_id' => $class->id,
        'creator_id' => $trainer->id,
        'title' => 'Kuis API Peserta',
        'slug' => 'kuis-api-peserta',
        'time_limit_minutes' => 15,
        'passing_grade' => 70.00,
        'max_attempts' => 2,
        'is_randomized' => false,
        'is_active' => true,
        'created_by_user_id' => $peserta->id,
    ]);

    $q1 = Question::create([
        'branch_id' => $branch->id,
        'creator_id' => $trainer->id,
        'question_text' => 'Soal API 1',
        'question_type' => 'multiple_choice',
        'score_weight' => 1,
    ]);
    $optCorrect = QuestionOption::create([
        'question_id' => $q1->id,
        'option_text' => 'Benar Sekali',
        'is_correct' => true,
        'option_order' => 1,
    ]);
    $quiz->questions()->attach($q1->id, ['order_number' => 1]);

    // Start attempt via API
    $startResponse = $this->postJson(route('api.v1.peserta.quizzes.start', [$class, $quiz]));
    $startResponse->assertStatus(200);
    $attemptId = $startResponse->json('attempt.id');

    $attempt = QuizAttempt::find($attemptId);

    // Submit via API
    $submitResponse = $this->postJson(
        route('api.v1.peserta.quizzes.submit', [$class, $quiz, $attempt]),
        [
            'answers' => [
                [
                    'question_id' => $q1->id,
                    'selected_option_id' => $optCorrect->id,
                ],
            ],
        ]
    );

    $submitResponse->assertStatus(200);
    $submitResponse->assertJsonPath('data.total_score', 100);
    $submitResponse->assertJsonPath('data.is_passed', true);
});
