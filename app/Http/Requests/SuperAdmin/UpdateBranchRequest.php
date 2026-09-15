<?php

namespace App\Http\Requests\SuperAdmin;

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
        $branchId = $this->route('branch')?->id ?? $this->route('branch');

        return [
            'name' => ['required', 'string', 'max:150'],
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
        ];
    }

    /**
     * Custom validation messages.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Nama cabang wajib diisi.',
            'code.required' => 'Kode cabang wajib diisi.',
            'code.unique' => 'Kode cabang sudah terdaftar pada cabang lain.',
            'address.required' => 'Alamat cabang wajib diisi.',
            'city.required' => 'Kota cabang wajib diisi.',
        ];
    }
}
