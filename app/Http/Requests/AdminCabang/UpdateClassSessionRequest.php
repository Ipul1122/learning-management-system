<?php

namespace App\Http\Requests\AdminCabang;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateClassSessionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->hasRole('admin-cabang') || $this->user()?->hasRole('trainer');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'session_order' => ['required', 'integer', 'min:1'],
            'jp_duration' => ['required', 'integer', 'min:1', 'max:10'],
            'session_date' => ['required', 'date'],
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
            'title.required' => 'Judul topik sesi pertemuan wajib diisi.',
            'session_order.required' => 'Urutan sesi ke-berapa wajib ditentukan.',
            'jp_duration.required' => 'Durasi JP sesi wajib ditentukan.',
            'session_date.required' => 'Waktu dan tanggal sesi wajib ditentukan.',
            'zoom_url.url' => 'Format URL meeting Zoom tidak valid.',
        ];
    }
}
