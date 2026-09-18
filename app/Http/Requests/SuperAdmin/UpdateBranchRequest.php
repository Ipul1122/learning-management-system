<?php

namespace App\Http\Requests\SuperAdmin;

use App\Models\Branch;
use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateBranchRequest extends FormRequest
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
        if ($this->filled('name')) {
            $this->merge([
                'name' => trim(preg_replace('/\s+/', ' ', $this->name)),
            ]);
        }

        if ($this->filled('code')) {
            $this->merge([
                'code' => strtoupper(trim($this->code)),
            ]);
        }

        if ($this->has('is_active')) {
            $this->merge([
                'is_active' => $this->boolean('is_active'),
            ]);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $branch = $this->route('branch');
        $branchId = $branch instanceof Branch ? $branch->id : $branch;

        $rules = [
            'name' => [
                'required',
                'string',
                'max:150',
                Rule::unique('branches', 'name')->ignore($branchId),
            ],
            'code' => [
                'required',
                'string',
                'max:20',
                Rule::unique('branches', 'code')->ignore($branchId),
            ],
            'address' => ['required', 'string'],
            'city' => ['required', 'string', 'max:100'],
            'phone' => ['nullable', 'string', 'max:30'],
            'is_active' => ['boolean'],
            'create_admin_account' => ['nullable', 'boolean'],
        ];

        // Cari data admin cabang saat ini jika ada
        $existingAdmin = null;
        if ($branch instanceof Branch) {
            $existingAdmin = $branch->users()->role('admin-cabang')->first();
        } elseif ($branchId) {
            $existingAdmin = User::role('admin-cabang')->where('branch_id', $branchId)->first();
        }

        $hasAdminData = $this->filled('admin_name') ||
            $this->filled('admin_email') ||
            $this->filled('admin_password') ||
            $this->boolean('create_admin_account');

        if ($existingAdmin) {
            if ($hasAdminData) {
                $rules['admin_name'] = ['required', 'string', 'max:255'];
                $rules['admin_email'] = [
                    'required',
                    'string',
                    'lowercase',
                    'email',
                    'max:255',
                    Rule::unique('users', 'email')->ignore($existingAdmin->id),
                ];
                $rules['admin_password'] = ['nullable', 'string', 'min:8', 'confirmed'];
                $rules['admin_phone_number'] = ['nullable', 'string', 'max:30'];
                $rules['admin_status'] = ['nullable', 'in:active,inactive'];
            }
        } elseif ($hasAdminData) {
            $rules['admin_name'] = ['required', 'string', 'max:255'];
            $rules['admin_email'] = [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                'unique:users,email',
            ];
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
            'name.unique' => 'Cabang dengan nama ini sudah terdaftar pada cabang lain.',
            'code.required' => 'Kode cabang wajib diisi.',
            'code.unique' => 'Kode cabang sudah terdaftar pada cabang lain.',
            'address.required' => 'Alamat cabang wajib diisi.',
            'city.required' => 'Kota cabang wajib diisi.',
            'admin_name.required' => 'Nama lengkap admin cabang wajib diisi.',
            'admin_email.required' => 'Alamat email admin cabang wajib diisi.',
            'admin_email.email' => 'Format email admin cabang tidak valid.',
            'admin_email.unique' => 'Email admin ini sudah terdaftar di sistem.',
            'admin_password.required' => 'Kata sandi admin cabang wajib diisi.',
            'admin_password.min' => 'Kata sandi admin cabang minimal 8 karakter.',
            'admin_password.confirmed' => 'Konfirmasi kata sandi admin tidak cocok.',
        ];
    }

    /**
     * Additional validation hook to check normalized/core branch name duplicates on update.
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $branchId = $this->route('branch')?->id ?? $this->route('branch');

            if ($this->filled('name')) {
                $inputRaw = strtolower(trim($this->name));
                $inputCore = preg_replace('/^(kantor\s+cabang|kantor\s+operasional|kantor|cabang|branch|kcp|kc|pusat\s+pelatihan)\s+/i', '', $inputRaw);
                $inputCore = trim($inputCore);

                $branches = \App\Models\Branch::where('id', '!=', $branchId)->select('id', 'name')->get();
                foreach ($branches as $branch) {
                    $bRaw = strtolower(trim($branch->name));
                    $bCore = preg_replace('/^(kantor\s+cabang|kantor\s+operasional|kantor|cabang|branch|kcp|kc|pusat\s+pelatihan)\s+/i', '', $bRaw);
                    $bCore = trim($bCore);

                    if ($bRaw === $inputRaw || ($inputCore !== '' && $bCore !== '' && $inputCore === $bCore)) {
                        $validator->errors()->add('name', "Cabang '{$branch->name}' sudah pernah didaftarkan pada cabang lain.");
                        break;
                    }
                }
            }
        });
    }
}
