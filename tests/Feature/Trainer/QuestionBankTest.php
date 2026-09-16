<?php

use App\Models\Branch;
use App\Models\Question;
use App\Models\User;
use Tests\TestCase;

beforeEach(function () {
    /** @var TestCase $this */
    $this->seed();
});

test('trainer dapat melihat daftar bank soal cabang miliknya', function () {
    /** @var TestCase $this */
    $trainer = User::where('email', 'trainer1@lms.test')->first();

    $response = $this->actingAs($trainer)->get(route('trainer.questions.index'));
    $response->assertStatus(200);
    $response->assertSee('Bank Soal Setara');
    $response->assertSee($trainer->branch->name);
});

test('trainer tidak dapat melihat soal milik cabang lain', function () {
    /** @var TestCase $this */
    $trainer1 = User::where('email', 'trainer1@lms.test')->first(); // Jakarta
    $branchSby = Branch::where('code', 'SBY-01')->first();

    // Buat soal di cabang Surabaya
    $otherQuestion = Question::create([
        'branch_id' => $branchSby->id,
        'creator_id' => $trainer1->id,
        'question_text' => 'Soal Rahasia Surabaya Yang Tidak Boleh Terlihat',
        'question_type' => 'essay',
        'score_weight' => 1,
    ]);

    $response = $this->actingAs($trainer1)->get(route('trainer.questions.index'));
    $response->assertStatus(200);
    $response->assertDontSee('Soal Rahasia Surabaya Yang Tidak Boleh Terlihat');
});

test('trainer dapat membuat butir soal pilihan ganda dengan bobot setara', function () {
    /** @var TestCase $this */
    $trainer = User::where('email', 'trainer1@lms.test')->first();

    $payload = [
        'question_text' => 'Apa output dari echo 2 + 2 di PHP?',
        'question_type' => 'multiple_choice',
        'explanation' => 'Operasi penjumlahan aritmatika biasa.',
        'options' => [
            ['option_text' => '3', 'is_correct' => '0'],
            ['option_text' => '4', 'is_correct' => '1'],
            ['option_text' => '22', 'is_correct' => '0'],
        ],
    ];

    $response = $this->actingAs($trainer)->post(route('trainer.questions.store'), $payload);
    $response->assertRedirect(route('trainer.questions.index'));
    $response->assertSessionHas('success');

    $this->assertDatabaseHas('questions', [
        'branch_id' => $trainer->branch_id,
        'creator_id' => $trainer->id,
        'question_text' => 'Apa output dari echo 2 + 2 di PHP?',
        'question_type' => 'multiple_choice',
        'score_weight' => 1, // Memastikan aturan bobot setara
    ]);

    $question = Question::where('question_text', 'Apa output dari echo 2 + 2 di PHP?')->first();
    expect($question->options)->toHaveCount(3);
    expect($question->correctOption()->option_text)->toBe('4');
});

test('trainer gagal membuat butir pilihan ganda jika tidak ada kunci jawaban benar', function () {
    /** @var TestCase $this */
    $trainer = User::where('email', 'trainer1@lms.test')->first();

    $payload = [
        'question_text' => 'Soal Pilihan Ganda Tanpa Kunci',
        'question_type' => 'multiple_choice',
        'options' => [
            ['option_text' => 'A', 'is_correct' => '0'],
            ['option_text' => 'B', 'is_correct' => '0'],
        ],
    ];

    $response = $this->actingAs($trainer)->post(route('trainer.questions.store'), $payload);
    $response->assertSessionHasErrors('options');
});

test('trainer dapat membuat soal benar salah', function () {
    /** @var TestCase $this */
    $trainer = User::where('email', 'trainer1@lms.test')->first();

    $payload = [
        'question_text' => 'Laravel adalah framework berbasis Python.',
        'question_type' => 'true_false',
        'options' => [
            ['option_text' => 'Benar', 'is_correct' => '0'],
            ['option_text' => 'Salah', 'is_correct' => '1'],
        ],
    ];

    $response = $this->actingAs($trainer)->post(route('trainer.questions.store'), $payload);
    $response->assertRedirect(route('trainer.questions.index'));

    $this->assertDatabaseHas('questions', [
        'question_text' => 'Laravel adalah framework berbasis Python.',
        'question_type' => 'true_false',
        'score_weight' => 1,
    ]);
});

test('trainer dapat membuat soal esai', function () {
    /** @var TestCase $this */
    $trainer = User::where('email', 'trainer1@lms.test')->first();

    $payload = [
        'question_text' => 'Jelaskan konsep Dependency Injection pada Laravel!',
        'question_type' => 'essay',
        'explanation' => 'Membahas inversi kontrol dan service container.',
    ];

    $response = $this->actingAs($trainer)->post(route('trainer.questions.store'), $payload);
    $response->assertRedirect(route('trainer.questions.index'));

    $this->assertDatabaseHas('questions', [
        'question_text' => 'Jelaskan konsep Dependency Injection pada Laravel!',
        'question_type' => 'essay',
        'score_weight' => 1,
    ]);
});

test('trainer dapat memperbarui butir soal', function () {
    /** @var TestCase $this */
    $trainer = User::where('email', 'trainer1@lms.test')->first();

    $question = Question::create([
        'branch_id' => $trainer->branch_id,
        'creator_id' => $trainer->id,
        'question_text' => 'Pertanyaan awal sebelum revisi',
        'question_type' => 'essay',
        'score_weight' => 1,
    ]);

    $payload = [
        'question_text' => 'Pertanyaan setelah direvisi trainer',
        'question_type' => 'essay',
        'explanation' => 'Penjelasan baru',
    ];

    $response = $this->actingAs($trainer)->put(route('trainer.questions.update', $question), $payload);
    $response->assertRedirect(route('trainer.questions.index'));

    expect($question->fresh()->question_text)->toBe('Pertanyaan setelah direvisi trainer');
});

test('trainer dapat menghapus butir soal', function () {
    /** @var TestCase $this */
    $trainer = User::where('email', 'trainer1@lms.test')->first();

    $question = Question::create([
        'branch_id' => $trainer->branch_id,
        'creator_id' => $trainer->id,
        'question_text' => 'Soal yang akan segera dihapus',
        'question_type' => 'essay',
        'score_weight' => 1,
    ]);

    $response = $this->actingAs($trainer)->delete(route('trainer.questions.destroy', $question));
    $response->assertRedirect(route('trainer.questions.index'));

    $this->assertDatabaseMissing('questions', ['id' => $question->id]);
});
