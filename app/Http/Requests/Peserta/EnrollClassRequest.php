<?php

namespace App\Http\Requests\Peserta;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class EnrollClassRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user() && $this->user()->hasRole('peserta');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'attendance_mode' => ['required', 'string', 'in:offline,online'],
        ];
    }

    /**
     * Custom validation error messages in Indonesian.
     */
    public function messages(): array
    {
        return [
            'attendance_mode.required' => 'Pilih metode kehadiran (hadir fisik atau daring).',
            'attendance_mode.in' => 'Metode kehadiran hanya boleh bernilai offline atau online.',
        ];
    }
}
