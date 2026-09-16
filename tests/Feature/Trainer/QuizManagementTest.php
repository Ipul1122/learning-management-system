<?php

use App\Models\Question;
use App\Models\QuestionOption;
use App\Models\Quiz;
use App\Models\TrainingClass;
use App\Models\User;
use Tests\TestCase;

beforeEach(function () {
    /** @var TestCase $this */
    $this->seed();
});

test('trainer dapat melihat daftar kuis untuk kelas yang diampunya', function () {
    /** @var TestCase $this */
    $trainer = User::where('email', 'trainer1@lms.test')->first();

    $response = $this->actingAs($trainer)->get(route('trainer.quizzes.index'));
    $response->assertStatus(200);
    $response->assertSee('Kelola Paket Kuis Kelas');
});

test('trainer dapat membuat kuis baru beserta memilih butir soal dari bank soal', function () {
    /** @var TestCase $this */
    $trainer = User::where('email', 'trainer1@lms.test')->first();
    $branchId = $trainer->branch_id;

    // Ambil kelas yang diampu oleh trainer1
    $class = TrainingClass::where('branch_id', $branchId)->where('trainer_id', $trainer->id)->first();
    if (! $class) {
        $class = TrainingClass::create([
            'branch_id' => $branchId,
            'trainer_id' => $trainer->id,
            'title' => 'Kelas Training PHP Advance',
            'slug' => 'kelas-training-php-advance',
            'type' => 'online',
            'online_capacity' => 100,
            'required_jp' => 20,
            'start_date' => now()->toDateString(),
            'end_date' => now()->addDays(14)->toDateString(),
            'status' => 'ongoing',
        ]);
    }

    // Buat 2 soal di bank soal cabang
    $q1 = Question::create([
        'branch_id' => $branchId,
        'creator_id' => $trainer->id,
        'question_text' => 'Soal Kuis Nomor Satu',
        'question_type' => 'multiple_choice',
        'score_weight' => 1,
    ]);
    QuestionOption::create(['question_id' => $q1->id, 'option_text' => 'A', 'is_correct' => true, 'option_order' => 1]);
    QuestionOption::create(['question_id' => $q1->id, 'option_text' => 'B', 'is_correct' => false, 'option_order' => 2]);

    $q2 = Question::create([
        'branch_id' => $branchId,
        'creator_id' => $trainer->id,
        'question_text' => 'Soal Kuis Nomor Dua',
        'question_type' => 'true_false',
        'score_weight' => 1,
    ]);
    QuestionOption::create(['question_id' => $q2->id, 'option_text' => 'Benar', 'is_correct' => true, 'option_order' => 1]);
    QuestionOption::create(['question_id' => $q2->id, 'option_text' => 'Salah', 'is_correct' => false, 'option_order' => 2]);

    $payload = [
        'class_id' => $class->id,
        'title' => 'Kuis Evaluasi Modul 1',
        'description' => 'Kerjakan kuis ini dalam batas waktu 45 menit.',
        'time_limit_minutes' => 45,
        'passing_grade' => 80.00,
        'is_randomized' => '1',
        'max_attempts' => 2,
        'questions' => [$q1->id, $q2->id],
    ];

    $response = $this->actingAs($trainer)->post(route('trainer.quizzes.store'), $payload);
    $response->assertRedirect(route('trainer.quizzes.index'));
    $response->assertSessionHas('success');

    $this->assertDatabaseHas('quizzes', [
        'class_id' => $class->id,
        'creator_id' => $trainer->id,
        'title' => 'Kuis Evaluasi Modul 1',
        'time_limit_minutes' => 45,
        'passing_grade' => 80.00,
        'is_randomized' => true,
        'max_attempts' => 2,
    ]);

    $quiz = Quiz::where('title', 'Kuis Evaluasi Modul 1')->first();
    expect($quiz->questions)->toHaveCount(2);
    expect($quiz->totalScoreWeight())->toBe(2); // 2 soal * 1 bobot = 2
});

test('trainer ditolak saat membuat kuis untuk kelas yang diampu trainer lain', function () {
    /** @var TestCase $this */
    $trainer1 = User::where('email', 'trainer1@lms.test')->first();
    $trainer2 = User::where('email', 'trainer2@lms.test')->first();

    // Buat kelas untuk trainer 2
    $classTrainer2 = TrainingClass::create([
        'branch_id' => $trainer2->branch_id,
        'trainer_id' => $trainer2->id,
        'title' => 'Kelas Khusus Trainer 2',
        'slug' => 'kelas-khusus-trainer-2',
        'type' => 'offline',
        'offline_capacity' => 30,
        'required_jp' => 20,
        'start_date' => now()->toDateString(),
        'end_date' => now()->addDays(14)->toDateString(),
        'status' => 'ongoing',
    ]);

    $q = Question::create([
        'branch_id' => $trainer1->branch_id,
        'creator_id' => $trainer1->id,
        'question_text' => 'Soal Percobaan',
        'question_type' => 'essay',
        'score_weight' => 1,
    ]);

    $payload = [
        'class_id' => $classTrainer2->id, // Kelas milik trainer2
        'title' => 'Kuis Ilegal',
        'time_limit_minutes' => 30,
        'passing_grade' => 75,
        'max_attempts' => 1,
        'questions' => [$q->id],
    ];

    // Trainer 1 mencoba membuat kuis di kelas Trainer 2
    $response = $this->actingAs($trainer1)->post(route('trainer.quizzes.store'), $payload);
    $response->assertSessionHasErrors('class_id');
});

test('trainer dapat memperbarui kuis dan detailnya', function () {
    /** @var TestCase $this */
    $trainer = User::where('email', 'trainer1@lms.test')->first();

    $class = TrainingClass::where('branch_id', $trainer->branch_id)->where('trainer_id', $trainer->id)->first();
    if (! $class) {
        $class = TrainingClass::create([
            'branch_id' => $trainer->branch_id,
            'trainer_id' => $trainer->id,
            'title' => 'Kelas Training Update Test',
            'slug' => 'kelas-training-update-test',
            'type' => 'online',
            'online_capacity' => 50,
            'required_jp' => 20,
            'start_date' => now()->toDateString(),
            'end_date' => now()->addDays(14)->toDateString(),
            'status' => 'ongoing',
        ]);
    }

    $q1 = Question::create([
        'branch_id' => $trainer->branch_id,
        'creator_id' => $trainer->id,
        'question_text' => 'Soal 1',
        'question_type' => 'essay',
        'score_weight' => 1,
    ]);

    $q2 = Question::create([
        'branch_id' => $trainer->branch_id,
        'creator_id' => $trainer->id,
        'question_text' => 'Soal 2',
        'question_type' => 'essay',
        'score_weight' => 1,
    ]);

    $quiz = Quiz::create([
        'class_id' => $class->id,
        'creator_id' => $trainer->id,
        'title' => 'Judul Kuis Awal',
        'time_limit_minutes' => 30,
        'passing_grade' => 70,
        'is_randomized' => true,
        'max_attempts' => 1,
    ]);
    $quiz->questions()->attach([$q1->id => ['order_number' => 1]]);

    $updatePayload = [
        'class_id' => $class->id,
        'title' => 'Judul Kuis Terupdate',
        'description' => 'Deskripsi kuis baru',
        'time_limit_minutes' => 60,
        'passing_grade' => 85,
        'is_randomized' => '0',
        'max_attempts' => 3,
        'questions' => [$q1->id, $q2->id],
    ];

    $response = $this->actingAs($trainer)->put(route('trainer.quizzes.update', $quiz), $updatePayload);
    $response->assertRedirect(route('trainer.quizzes.index'));

    $quiz->refresh();
    expect($quiz->title)->toBe('Judul Kuis Terupdate');
    expect($quiz->time_limit_minutes)->toBe(60);
    expect((float) $quiz->passing_grade)->toBe(85.0);
    expect($quiz->is_randomized)->toBeFalse();
    expect($quiz->questions)->toHaveCount(2);
});

test('trainer dapat melihat detail kuis', function () {
    /** @var TestCase $this */
    $trainer = User::where('email', 'trainer1@lms.test')->first();

    $class = TrainingClass::where('branch_id', $trainer->branch_id)->where('trainer_id', $trainer->id)->first();
    if (! $class) {
        $class = TrainingClass::create([
            'branch_id' => $trainer->branch_id,
            'trainer_id' => $trainer->id,
            'title' => 'Kelas Detail Show Test',
            'slug' => 'kelas-detail-show-test',
            'type' => 'online',
            'online_capacity' => 50,
            'required_jp' => 20,
            'start_date' => now()->toDateString(),
            'end_date' => now()->addDays(14)->toDateString(),
            'status' => 'ongoing',
        ]);
    }

    $quiz = Quiz::create([
        'class_id' => $class->id,
        'creator_id' => $trainer->id,
        'title' => 'Kuis Untuk Show',
        'time_limit_minutes' => 30,
        'passing_grade' => 75,
        'is_randomized' => true,
        'max_attempts' => 1,
    ]);

    $response = $this->actingAs($trainer)->get(route('trainer.quizzes.show', $quiz));
    $response->assertStatus(200);
    $response->assertSee('Kuis Untuk Show');
    $response->assertSee('75%');
});

test('trainer dapat menghapus kuis', function () {
    /** @var TestCase $this */
    $trainer = User::where('email', 'trainer1@lms.test')->first();

    $class = TrainingClass::where('branch_id', $trainer->branch_id)->where('trainer_id', $trainer->id)->first();
    if (! $class) {
        $class = TrainingClass::create([
            'branch_id' => $trainer->branch_id,
            'trainer_id' => $trainer->id,
            'title' => 'Kelas Hapus Test',
            'slug' => 'kelas-hapus-test',
            'type' => 'online',
            'online_capacity' => 50,
            'required_jp' => 20,
            'start_date' => now()->toDateString(),
            'end_date' => now()->addDays(14)->toDateString(),
            'status' => 'ongoing',
        ]);
    }

    $quiz = Quiz::create([
        'class_id' => $class->id,
        'creator_id' => $trainer->id,
        'title' => 'Kuis Akan Dihapus',
        'time_limit_minutes' => 30,
        'passing_grade' => 75,
        'is_randomized' => true,
        'max_attempts' => 1,
    ]);

    $response = $this->actingAs($trainer)->delete(route('trainer.quizzes.destroy', $quiz));
    $response->assertRedirect(route('trainer.quizzes.index'));

    $this->assertDatabaseMissing('quizzes', ['id' => $quiz->id]);
});
