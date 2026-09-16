<?php

namespace App\Http\Requests\Trainer;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreQuizRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user() && $this->user()->hasRole('trainer') && $this->user()->branch_id !== null;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $user = $this->user();
        $branchId = $user->branch_id;

        return [
            'class_id' => [
                'required',
                'integer',
                Rule::exists('classes', 'id')->where(function ($query) use ($user, $branchId) {
                    $query->where('branch_id', $branchId)
                        ->where('trainer_id', $user->id);
                }),
            ],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'time_limit_minutes' => ['required', 'integer', 'min:5', 'max:360'],
            'passing_grade' => ['required', 'numeric', 'min:0', 'max:100'],
            'is_randomized' => ['nullable', 'boolean'],
            'max_attempts' => ['required', 'integer', 'min:1', 'max:10'],
            'questions' => ['required', 'array', 'min:1'],
            'questions.*' => [
                'integer',
                Rule::exists('questions', 'id')->where(function ($query) use ($branchId) {
                    $query->where('branch_id', $branchId);
                }),
            ],
        ];
    }

    /**
     * Custom error messages in Indonesian.
     */
    public function messages(): array
    {
        return [
            'class_id.required' => 'Pilih kelas pelatihan yang Anda ampu.',
            'class_id.exists' => 'Kelas pelatihan tidak valid atau bukan kelas yang Anda ampu di cabang ini.',
            'title.required' => 'Judul kuis wajib diisi.',
            'time_limit_minutes.required' => 'Durasi waktu pengerjaan wajib ditentukan.',
            'time_limit_minutes.min' => 'Durasi minimal kuis adalah 5 menit.',
            'time_limit_minutes.max' => 'Durasi maksimal kuis adalah 360 menit (6 jam).',
            'passing_grade.required' => 'Nilai kelulusan (passing grade) wajib diisi.',
            'passing_grade.min' => 'Nilai kelulusan minimal adalah 0.',
            'passing_grade.max' => 'Nilai kelulusan maksimal adalah 100.',
            'max_attempts.required' => 'Batas percobaan wajib diisi.',
            'max_attempts.min' => 'Batas percobaan minimal 1 kali.',
            'questions.required' => 'Pilih minimal satu butir soal dari bank soal.',
            'questions.min' => 'Pilih minimal satu butir soal dari bank soal.',
            'questions.*.exists' => 'Salah satu butir soal yang dipilih tidak valid atau bukan milik cabang Anda.',
        ];
    }

    /**
     * Prepare inputs before validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_randomized' => $this->boolean('is_randomized'),
        ]);
    }
}
