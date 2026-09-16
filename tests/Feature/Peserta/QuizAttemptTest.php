<?php

use App\Models\Branch;
use App\Models\ClassEnrollment;
use App\Models\Question;
use App\Models\QuestionOption;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\TrainingClass;
use App\Models\User;
use Tests\TestCase;

beforeEach(function () {
    /** @var TestCase $this */
    $this->seed();
});

test('peserta dapat melihat briefing kuis dan riwayat percobaan', function () {
    /** @var TestCase $this */
    $peserta = User::where('email', 'peserta1@lms.test')->first();
    $trainer = User::where('email', 'trainer1@lms.test')->first();
    $branch = Branch::first();

    $class = TrainingClass::create([
        'branch_id' => $branch->id,
        'trainer_id' => $trainer->id,
        'title' => 'Kelas Ujian Kuis',
        'slug' => 'kelas-ujian-kuis',
        'type' => 'online',
        'online_capacity' => 100,
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
        'title' => 'Kuis Evaluasi Modul 1',
        'slug' => 'kuis-evaluasi-modul-1',
        'description' => 'Evaluasi materi fundamental modul 1',
        'time_limit_minutes' => 30,
        'passing_grade' => 75.00,
        'max_attempts' => 3,
        'is_randomized' => true,
        'is_active' => true,
        'creator_id' => $trainer->id,
    ]);

    $response = $this->actingAs($peserta)->get(route('peserta.quizzes.show', [$class, $quiz]));
    $response->assertStatus(200);
    $response->assertSee('Briefing Kuis Interaktif');
    $response->assertSee('30');
    $response->assertSee('75%');
});

test('peserta dapat memulai kuis baru dan diarahkan ke lembar soal', function () {
    /** @var TestCase $this */
    $peserta = User::where('email', 'peserta1@lms.test')->first();
    $trainer = User::where('email', 'trainer1@lms.test')->first();
    $branch = Branch::first();

    $class = TrainingClass::create([
        'branch_id' => $branch->id,
        'trainer_id' => $trainer->id,
        'title' => 'Kelas Start Kuis',
        'slug' => 'kelas-start-kuis',
        'type' => 'online',
        'online_capacity' => 100,
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
        'title' => 'Kuis Baru Start',
        'slug' => 'kuis-baru-start',
        'time_limit_minutes' => 20,
        'passing_grade' => 70.00,
        'max_attempts' => 2,
        'is_randomized' => false,
        'is_active' => true,
        'creator_id' => $trainer->id,
    ]);

    $response = $this->actingAs($peserta)->post(route('peserta.quizzes.start', [$class, $quiz]));

    $attempt = QuizAttempt::where('quiz_id', $quiz->id)->where('user_id', $peserta->id)->first();
    expect($attempt)->not->toBeNull();
    expect($attempt->attempt_number)->toBe(1);

    $response->assertRedirect(route('peserta.quizzes.take', [$class, $quiz, $attempt]));
});

test('peserta mengirim jawaban kuis dan sistem melakukan auto-grading otomatis secara instan', function () {
    /** @var TestCase $this */
    $peserta = User::where('email', 'peserta1@lms.test')->first();
    $trainer = User::where('email', 'trainer1@lms.test')->first();
    $branch = Branch::first();

    $class = TrainingClass::create([
        'branch_id' => $branch->id,
        'trainer_id' => $trainer->id,
        'title' => 'Kelas Auto-grading',
        'slug' => 'kelas-auto-grading',
        'type' => 'online',
        'online_capacity' => 100,
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
        'title' => 'Kuis Auto-grading PG',
        'slug' => 'kuis-auto-grading-pg',
        'time_limit_minutes' => 15,
        'passing_grade' => 50.00,
        'max_attempts' => 3,
        'is_randomized' => false,
        'is_active' => true,
        'creator_id' => $trainer->id,
    ]);

    // Buat 2 soal PG: 1 dijawab benar, 1 dijawab salah
    $q1 = Question::create([
        'branch_id' => $branch->id,
        'creator_id' => $trainer->id,
        'question_text' => 'Berapa menit dalam 1 JP standar LMS?',
        'question_type' => 'multiple_choice',
        'score_weight' => 1,
    ]);
    $opt1Correct = QuestionOption::create([
        'question_id' => $q1->id,
        'option_text' => '45 Menit',
        'is_correct' => true,
        'option_order' => 1,
    ]);
    $opt1Wrong = QuestionOption::create([
        'question_id' => $q1->id,
        'option_text' => '60 Menit',
        'is_correct' => false,
        'option_order' => 2,
    ]);
    $quiz->questions()->attach($q1->id, ['order_number' => 1]);

    $q2 = Question::create([
        'branch_id' => $branch->id,
        'creator_id' => $trainer->id,
        'question_text' => 'Apakah kuota offline dibatasi maksimal 40 kursi?',
        'question_type' => 'true_false',
        'score_weight' => 1,
    ]);
    $opt2Correct = QuestionOption::create([
        'question_id' => $q2->id,
        'option_text' => 'Benar',
        'is_correct' => true,
        'option_order' => 1,
    ]);
    $opt2Wrong = QuestionOption::create([
        'question_id' => $q2->id,
        'option_text' => 'Salah',
        'is_correct' => false,
        'option_order' => 2,
    ]);
    $quiz->questions()->attach($q2->id, ['order_number' => 2]);

    $attempt = QuizAttempt::create([
        'quiz_id' => $quiz->id,
        'user_id' => $peserta->id,
        'attempt_number' => 1,
        'started_at' => now(),
        'total_score' => 0.00,
        'is_passed' => false,
    ]);

    // Kirim: Q1 benar, Q2 salah -> 1 dari 2 benar = 50.00%
    $payload = [
        'answers' => [
            [
                'question_id' => $q1->id,
                'selected_option_id' => $opt1Correct->id,
            ],
            [
                'question_id' => $q2->id,
                'selected_option_id' => $opt2Wrong->id,
            ],
        ],
    ];

    $response = $this->actingAs($peserta)->post(
        route('peserta.quizzes.submit', [$class, $quiz, $attempt]),
        $payload
    );

    $response->assertRedirect(route('peserta.quizzes.result', [$class, $quiz, $attempt]));

    $attempt->refresh();
    expect($attempt->submitted_at)->not->toBeNull();
    expect((float) $attempt->total_score)->toBe(50.0);
    expect($attempt->is_passed)->toBeTrue(); // Passing grade adalah 50%

    // Verifikasi catatan jawaban
    $this->assertDatabaseHas('quiz_attempt_answers', [
        'attempt_id' => $attempt->id,
        'question_id' => $q1->id,
        'selected_option_id' => $opt1Correct->id,
        'is_correct' => true,
        'score_earned' => 1.00,
    ]);

    $this->assertDatabaseHas('quiz_attempt_answers', [
        'attempt_id' => $attempt->id,
        'question_id' => $q2->id,
        'selected_option_id' => $opt2Wrong->id,
        'is_correct' => false,
        'score_earned' => 0.00,
    ]);
});

test('peserta dilarang memulai attempt baru jika sudah mencapai batas max_attempts', function () {
    /** @var TestCase $this */
    $peserta = User::where('email', 'peserta1@lms.test')->first();
    $trainer = User::where('email', 'trainer1@lms.test')->first();
    $branch = Branch::first();

    $class = TrainingClass::create([
        'branch_id' => $branch->id,
        'trainer_id' => $trainer->id,
        'title' => 'Kelas Limit Attempt',
        'slug' => 'kelas-limit-attempt',
        'type' => 'online',
        'online_capacity' => 100,
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
        'title' => 'Kuis 1 Kali Kesempatan',
        'slug' => 'kuis-1-kali-kesempatan',
        'time_limit_minutes' => 10,
        'passing_grade' => 80.00,
        'max_attempts' => 1,
        'is_randomized' => false,
        'is_active' => true,
        'creator_id' => $trainer->id,
    ]);

    // Attempt 1 sudah selesai
    QuizAttempt::create([
        'quiz_id' => $quiz->id,
        'user_id' => $peserta->id,
        'attempt_number' => 1,
        'started_at' => now()->subMinutes(15),
        'submitted_at' => now()->subMinutes(5),
        'total_score' => 60.00,
        'is_passed' => false,
    ]);

    $response = $this->actingAs($peserta)->post(route('peserta.quizzes.start', [$class, $quiz]));
    $response->assertStatus(403);
});
