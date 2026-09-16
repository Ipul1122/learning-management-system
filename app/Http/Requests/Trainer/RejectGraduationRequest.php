<?php

namespace App\Http\Requests\Trainer;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class RejectGraduationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->hasRole('trainer') ?? false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'trainer_feedback' => ['required', 'string', 'min:10', 'max:2000'],
        ];
    }

    /**
     * Custom messages for validation errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'trainer_feedback.required' => 'Form alasan penolakan dan instruksi perbaikan materi wajib diisi.',
            'trainer_feedback.min' => 'Instruksi perbaikan materi minimal berisi 10 karakter.',
            'trainer_feedback.max' => 'Instruksi perbaikan materi maksimal 2000 karakter.',
        ];
    }
}
