<?php

namespace App\Http\Requests\Trainer;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class UpdateQuestionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->hasAnyRole(['trainer', 'admin-cabang']) && ! empty($this->user()->branch_id);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'question_text' => ['required', 'string'],
            'question_type' => ['required', 'in:multiple_choice,true_false,essay'],
            'score_weight' => ['nullable', 'integer', 'min:1'],
            'explanation' => ['nullable', 'string'],
            'options' => ['nullable', 'array'],
            'options.*.id' => ['nullable', 'integer'],
            'options.*.option_text' => ['required_with:options', 'string'],
            'options.*.is_correct' => ['nullable'],
        ];
    }

    /**
     * Custom validation logic to ensure correct option requirements.
     */
    public function withValidator(Validator $validator): void
    {
        $validator->after(function ($validator) {
            $type = $this->input('question_type');
            $options = $this->input('options', []);

            if (in_array($type, ['multiple_choice', 'true_false'])) {
                if (empty($options) || count($options) < 2) {
                    $validator->errors()->add('options', 'Soal pilihan ganda atau benar/salah minimal harus memiliki 2 pilihan jawaban.');
                }

                $hasCorrect = false;
                foreach ($options as $opt) {
                    if (! empty($opt['is_correct'])) {
                        $hasCorrect = true;
                        break;
                    }
                }

                if (! $hasCorrect) {
                    $validator->errors()->add('options', 'Wajib menandai minimal satu pilihan jawaban sebagai kunci jawaban yang benar.');
                }
            }
        });
    }

    /**
     * Custom error messages.
     */
    public function messages(): array
    {
        return [
            'question_text.required' => 'Teks butir soal wajib diisi.',
            'question_type.required' => 'Tipe butir soal wajib dipilih.',
            'question_type.in' => 'Tipe soal yang dipilih tidak valid.',
            'options.*.option_text.required_with' => 'Teks opsi pilihan jawaban tidak boleh kosong.',
        ];
    }
}
