<?php

namespace App\Http\Requests\Trainer;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateZoomSessionRequest extends FormRequest
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
        return [
            'zoom_url' => ['nullable', 'url', 'max:500'],
            'zoom_meeting_id' => ['nullable', 'string', 'max:100'],
            'zoom_passcode' => ['nullable', 'string', 'max:100'],
        ];
    }

    /**
     * Custom validation messages.
     */
    public function messages(): array
    {
        return [
            'zoom_url.url' => 'Format URL tautan Zoom tidak valid.',
            'zoom_url.max' => 'URL tautan Zoom maksimal 500 karakter.',
            'zoom_meeting_id.max' => 'Meeting ID Zoom maksimal 100 karakter.',
            'zoom_passcode.max' => 'Passcode Zoom maksimal 100 karakter.',
        ];
    }
}
