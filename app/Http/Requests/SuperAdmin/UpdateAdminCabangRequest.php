<?php

namespace App\Http\Requests\SuperAdmin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAdminCabangRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->hasRole('super-admin') ?? false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $adminId = $this->route('admin')?->id ?? $this->route('admin');

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($adminId),
            ],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'branch_id' => ['required', 'integer', 'exists:branches,id'],
            'phone_number' => ['nullable', 'string', 'max:30'],
            'status' => ['required', 'in:active,inactive'],
        ];
    }

    /**
     * Custom validation error messages.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Alamat email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email ini sudah digunakan oleh akun lain.',
            'password.min' => 'Kata sandi minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
            'branch_id.required' => 'Cabang penugasan wajib dipilih.',
            'branch_id.exists' => 'Kantor cabang yang dipilih tidak valid.',
            'status.required' => 'Status akun wajib ditentukan.',
        ];
    }
}
