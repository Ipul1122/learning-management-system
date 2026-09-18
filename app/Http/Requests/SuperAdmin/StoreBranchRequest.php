<?php

namespace App\Http\Requests\SuperAdmin;

use App\Models\Branch;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreBranchRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->hasRole('super-admin') ?? false;
    }

    /**
     * Prepare data before validation.
     */
    protected function prepareForValidation(): void
    {
        if ($this->filled('existing_branch_id')) {
            return;
        }

        if ($this->filled('name')) {
            $this->merge([
                'name' => trim(preg_replace('/\s+/', ' ', $this->name)),
            ]);
        }

        if (! $this->filled('code') && $this->filled('name')) {
            $this->merge([
                'code' => Branch::generateCode($this->name),
            ]);
        }

        if ($this->filled('code')) {
            $this->merge([
                'code' => strtoupper(trim($this->code)),
            ]);
        }

        // Set default is_active to true if not specified
        $this->merge([
            'is_active' => $this->boolean('is_active', true),
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // Jika menggunakan kantor cabang yang sudah ada untuk menambah admin
        if ($this->filled('existing_branch_id')) {
            return [
                'existing_branch_id' => ['required', 'exists:branches,id'],
                'reuse_branch_choice' => ['nullable', 'string'],
                'action' => ['nullable', 'string'],
                'name' => ['nullable', 'string', 'max:150'],
                'code' => ['nullable', 'string', 'max:20'],
                'address' => ['nullable', 'string'],
                'city' => ['nullable', 'string', 'max:100'],
                'phone' => ['nullable', 'string', 'max:30'],
                'is_active' => ['nullable', 'boolean'],
                'create_admin_account' => ['nullable', 'boolean'],
                'admin_name' => ['required', 'string', 'max:255'],
                'admin_email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
                'admin_password' => ['required', 'string', 'min:8', 'confirmed'],
                'admin_phone_number' => ['nullable', 'string', 'max:30'],
                'admin_status' => ['nullable', 'in:active,inactive'],
            ];
        }

        $rules = [
            'name' => ['required', 'string', 'max:150', 'unique:branches,name'],
            'code' => ['required', 'string', 'max:20', 'unique:branches,code'],
            'address' => ['required', 'string'],
            'city' => ['required', 'string', 'max:100'],
            'phone' => ['nullable', 'string', 'max:30'],
            'is_active' => ['boolean'],
            'create_admin_account' => ['nullable', 'boolean'],
            'existing_branch_id' => ['nullable', 'exists:branches,id'],
            'reuse_branch_choice' => ['nullable', 'string'],
            'action' => ['nullable', 'string'],
        ];

        $hasAdmin = $this->boolean('create_admin_account') ||
            $this->filled('admin_name') ||
            $this->filled('admin_email') ||
            $this->filled('admin_password');

        if ($hasAdmin) {
            $rules['admin_name'] = ['required', 'string', 'max:255'];
            $rules['admin_email'] = ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'];
            $rules['admin_password'] = ['required', 'string', 'min:8', 'confirmed'];
            $rules['admin_phone_number'] = ['nullable', 'string', 'max:30'];
            $rules['admin_status'] = ['nullable', 'in:active,inactive'];
        }

        return $rules;
    }

    /**
     * Custom validation messages.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Nama cabang wajib diisi.',
            'name.unique' => 'Cabang ini sudah pernah diinput sebelumnya dan tidak dapat didaftarkan kembali.',
            'code.required' => 'Kode cabang wajib diisi.',
            'code.unique' => 'Kode cabang sudah terdaftar, gunakan kode lain.',
            'address.required' => 'Alamat cabang wajib diisi.',
            'city.required' => 'Kota cabang wajib diisi.',
            'admin_name.required' => 'Nama lengkap admin cabang wajib diisi.',
            'admin_email.required' => 'Alamat email admin cabang wajib diisi.',
            'admin_email.email' => 'Format email admin cabang tidak valid.',
            'admin_email.unique' => 'Email admin ini sudah terdaftar di sistem.',
            'admin_password.required' => 'Kata sandi admin cabang wajib diisi.',
            'admin_password.min' => 'Kata sandi admin cabang minimal 8 karakter.',
            'admin_password.confirmed' => 'Konfirmasi kata sandi admin tidak cocok.',
            'existing_branch_id.exists' => 'Kantor cabang yang dipilih tidak valid atau tidak ditemukan.',
        ];
    }

    /**
     * Additional validation hook to check normalized/core branch name duplicates.
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            if ($this->filled('existing_branch_id')) {
                return; // Lewati pemeriksaan duplikasi karena cabang memang sengaja digunakan kembali
            }

            if ($this->filled('name')) {
                $inputRaw = strtolower(trim($this->name));
                $inputCore = preg_replace('/^(kantor\s+cabang|kantor\s+operasional|kantor|cabang|branch|kcp|kc|pusat\s+pelatihan)\s+/i', '', $inputRaw);
                $inputCore = trim($inputCore);

                $branches = Branch::select('id', 'name')->get();
                foreach ($branches as $branch) {
                    $bRaw = strtolower(trim($branch->name));
                    $bCore = preg_replace('/^(kantor\s+cabang|kantor\s+operasional|kantor|cabang|branch|kcp|kc|pusat\s+pelatihan)\s+/i', '', $bRaw);
                    $bCore = trim($bCore);

                    if ($bRaw === $inputRaw || ($inputCore !== '' && $bCore !== '' && $inputCore === $bCore)) {
                        $validator->errors()->add('name', "Cabang '{$branch->name}' sudah pernah diinput sebelumnya dan tidak dapat didaftarkan kembali.");
                        break;
                    }
                }
            }
        });
    }
}
