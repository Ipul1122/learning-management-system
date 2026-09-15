<?php

namespace App\Http\Requests\AdminCabang;

use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreClassRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->hasRole('admin-cabang') && ! empty($this->user()->branch_id);
    }

    /**
     * Prepare data before validation.
     */
    protected function prepareForValidation(): void
    {
        $type = $this->input('type');

        // Normalisasi kuota berdasarkan tipe kelas
        if ($type === 'offline') {
            $this->merge([
                'online_capacity' => 0,
            ]);
        } elseif ($type === 'online') {
            $this->merge([
                'offline_capacity' => 0,
            ]);
        }

        // Default required JP jika kosong
        if (! $this->filled('required_jp')) {
            $this->merge([
                'required_jp' => 20,
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
        $branchId = $this->user()->branch_id;

        return [
            'title' => ['required', 'string', 'max:255'],
            'trainer_id' => [
                'required',
                'integer',
                Rule::exists('users', 'id')->where(function ($query) use ($branchId) {
                    $query->where('branch_id', $branchId);
                }),
            ],
            'type' => ['required', 'in:offline,online,hybrid'],
            // Kelas offline / fisik hybrid dibatasi ketat MAKSIMAL 40 orang
            'offline_capacity' => [
                'required_if:type,offline,hybrid',
                'integer',
                Rule::when(in_array($this->input('type'), ['offline', 'hybrid']), ['min:1', 'max:40']),
            ],
            // Kelas online / daring hybrid kapasitas ratusan peserta
            'online_capacity' => [
                'required_if:type,online,hybrid',
                'integer',
                Rule::when(in_array($this->input('type'), ['online', 'hybrid']), ['min:1', 'max:5000']),
            ],
            'required_jp' => ['required', 'integer', 'min:1', 'max:100'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'description' => ['nullable', 'string'],
            'status' => ['required', 'in:draft,open,ongoing,completed,cancelled'],
        ];
    }

    /**
     * Custom validation messages.
     */
    public function messages(): array
    {
        return [
            'title.required' => 'Judul kelas pelatihan wajib diisi.',
            'trainer_id.required' => 'Trainer pengampu kelas wajib dipilih.',
            'trainer_id.exists' => 'Trainer yang dipilih tidak valid atau bukan berasal dari cabang Anda.',
            'type.required' => 'Tipe format kelas wajib dipilih (Offline / Online / Hybrid).',
            'offline_capacity.max' => 'Kapasitas kelas offline / tatap muka fisik DIBATASI MAKSIMAL 40 ORANG.',
            'offline_capacity.min' => 'Kapasitas offline minimal 1 orang.',
            'online_capacity.min' => 'Kapasitas online minimal 1 orang.',
            'end_date.after_or_equal' => 'Tanggal selesai harus sama atau setelah tanggal mulai.',
        ];
    }
}
