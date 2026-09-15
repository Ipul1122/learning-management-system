<x-app-layout>
    <div class="py-8">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Breadcrumb & Title -->
            <div>
                <a href="{{ route('admin.admins.index') }}" class="inline-flex items-center gap-1.5 text-xs font-montserrat font-bold text-[#6E675F] hover:text-[#FF6B00] transition mb-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    <span>Kembali ke Daftar Admin Cabang</span>
                </a>
                <div class="flex items-center gap-3">
                    <h1 class="font-montserrat font-extrabold text-2xl sm:text-3xl text-[#1E1B18] tracking-tight">
                        Edit Admin: {{ $admin->name }}
                    </h1>
                    @if($admin->status === 'active')
                        <span class="px-2.5 py-1 rounded-full text-xs font-bold font-montserrat bg-[#ECFDF5] text-[#10B981] border border-[#A7F3D0]">Aktif</span>
                    @else
                        <span class="px-2.5 py-1 rounded-full text-xs font-bold font-montserrat bg-[#FEF2F2] text-[#DC2626] border border-[#FECACA]">Nonaktif</span>
                    @endif
                </div>
                <p class="font-quicksand text-sm text-[#6E675F] mt-1">
                    Perbarui informasi akun, penugasan cabang operasional, atau reset kata sandi akun ini.
                </p>
            </div>

            <!-- Form Card -->
            <div class="bg-white rounded-2xl border border-[#EBE5DF] shadow-sm p-6 sm:p-8">
                <form method="POST" action="{{ route('admin.admins.update', $admin) }}" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- Row: Nama & Email -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <label for="name" class="block font-montserrat font-bold text-xs uppercase tracking-wider text-[#1E1B18] mb-2">
                                Nama Lengkap <span class="text-[#DC2626]">*</span>
                            </label>
                            <input type="text"
                                   id="name"
                                   name="name"
                                   value="{{ old('name', $admin->name) }}"
                                   required
                                   class="w-full px-4 py-2.5 rounded-xl border @error('name') border-red-500 @else border-[#EBE5DF] @enderror bg-[#FAF8F5] text-sm text-[#1E1B18] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#FF6B00] focus:border-transparent font-quicksand transition">
                            @error('name')
                                <p class="mt-1.5 text-xs text-[#DC2626] font-semibold">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="email" class="block font-montserrat font-bold text-xs uppercase tracking-wider text-[#1E1B18] mb-2">
                                Alamat Email <span class="text-[#DC2626]">*</span>
                            </label>
                            <input type="email"
                                   id="email"
                                   name="email"
                                   value="{{ old('email', $admin->email) }}"
                                   required
                                   class="w-full px-4 py-2.5 rounded-xl border @error('email') border-red-500 @else border-[#EBE5DF] @enderror bg-[#FAF8F5] text-sm text-[#1E1B18] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#FF6B00] focus:border-transparent font-quicksand transition">
                            @error('email')
                                <p class="mt-1.5 text-xs text-[#DC2626] font-semibold">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Row: Cabang & Telepon -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <label for="branch_id" class="block font-montserrat font-bold text-xs uppercase tracking-wider text-[#1E1B18] mb-2">
                                Cabang Penugasan <span class="text-[#DC2626]">*</span>
                            </label>
                            <select id="branch_id"
                                    name="branch_id"
                                    required
                                    class="w-full px-4 py-2.5 rounded-xl border @error('branch_id') border-red-500 @else border-[#EBE5DF] @enderror bg-[#FAF8F5] text-sm text-[#1E1B18] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#FF6B00] focus:border-transparent font-quicksand transition">
                                @foreach($branches as $branch)
                                    <option value="{{ $branch->id }}" {{ old('branch_id', $admin->branch_id) == $branch->id ? 'selected' : '' }}>
                                        {{ $branch->name }} ({{ $branch->code }} - {{ $branch->city }})
                                        {{ !$branch->is_active ? '[Cabang Nonaktif]' : '' }}
                                    </option>
                                @endforeach
                            </select>
                            @error('branch_id')
                                <p class="mt-1.5 text-xs text-[#DC2626] font-semibold">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="phone_number" class="block font-montserrat font-bold text-xs uppercase tracking-wider text-[#1E1B18] mb-2">
                                Nomor Handphone / WhatsApp
                            </label>
                            <input type="text"
                                   id="phone_number"
                                   name="phone_number"
                                   value="{{ old('phone_number', $admin->phone_number) }}"
                                   placeholder="Contoh: 08123456789"
                                   class="w-full px-4 py-2.5 rounded-xl border @error('phone_number') border-red-500 @else border-[#EBE5DF] @enderror bg-[#FAF8F5] text-sm text-[#1E1B18] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#FF6B00] focus:border-transparent font-quicksand transition">
                            @error('phone_number')
                                <p class="mt-1.5 text-xs text-[#DC2626] font-semibold">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Password Reset Section (Opsional) -->
                    <div class="p-4 rounded-xl bg-[#FAF8F5] border border-[#EBE5DF] space-y-4">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-[#FF6B00]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                            <span class="font-montserrat font-bold text-xs uppercase tracking-wider text-[#1E1B18]">
                                Reset Kata Sandi (Kosongkan jika tidak diubah)
                            </span>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="password" class="block font-quicksand text-xs font-semibold text-[#6E675F] mb-1">
                                    Kata Sandi Baru
                                </label>
                                <input type="password"
                                       id="password"
                                       name="password"
                                       placeholder="Minimal 8 karakter"
                                       class="w-full px-4 py-2 rounded-xl border @error('password') border-red-500 @else border-[#EBE5DF] @enderror bg-white text-sm text-[#1E1B18] focus:outline-none focus:ring-2 focus:ring-[#FF6B00] focus:border-transparent font-quicksand transition">
                                @error('password')
                                    <p class="mt-1 text-xs text-[#DC2626] font-semibold">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="password_confirmation" class="block font-quicksand text-xs font-semibold text-[#6E675F] mb-1">
                                    Konfirmasi Kata Sandi Baru
                                </label>
                                <input type="password"
                                       id="password_confirmation"
                                       name="password_confirmation"
                                       placeholder="Ketik ulang kata sandi baru"
                                       class="w-full px-4 py-2 rounded-xl border border-[#EBE5DF] bg-white text-sm text-[#1E1B18] focus:outline-none focus:ring-2 focus:ring-[#FF6B00] focus:border-transparent font-quicksand transition">
                            </div>
                        </div>
                    </div>

                    <!-- Status Akun -->
                    <div>
                        <label class="block font-montserrat font-bold text-xs uppercase tracking-wider text-[#1E1B18] mb-2">
                            Status Akun <span class="text-[#DC2626]">*</span>
                        </label>
                        <div class="flex gap-4">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="status" value="active" {{ old('status', $admin->status) === 'active' ? 'checked' : '' }} class="text-[#FF6B00] focus:ring-[#FF6B00]">
                                <span class="text-sm font-quicksand text-[#1E1B18]">Aktif</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="status" value="inactive" {{ old('status', $admin->status) === 'inactive' ? 'checked' : '' }} class="text-[#FF6B00] focus:ring-[#FF6B00]">
                                <span class="text-sm font-quicksand text-[#1E1B18]">Nonaktif</span>
                            </label>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="pt-4 flex items-center justify-end gap-3 border-t border-[#EBE5DF]">
                        <a href="{{ route('admin.admins.index') }}"
                           class="px-5 py-2.5 rounded-xl font-montserrat font-bold text-sm text-[#6E675F] hover:text-[#1E1B18] hover:bg-[#FAF8F5] transition">
                            Batal
                        </a>
                        <button type="submit"
                                class="px-6 py-2.5 rounded-xl font-montserrat font-bold text-sm text-white bg-gradient-to-r from-[#FF6B00] to-[#E11D48] hover:from-[#EA580C] hover:to-[#DC2626] shadow-md hover:shadow-lg transition-all duration-200 transform hover:-translate-y-0.5">
                            Perbarui Data Admin
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>
