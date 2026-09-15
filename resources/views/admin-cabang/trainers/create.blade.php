<x-app-layout>
    <div class="py-8">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Breadcrumb & Title -->
            <div>
                <a href="{{ route('cabang.trainers.index') }}" class="inline-flex items-center gap-1.5 text-xs font-montserrat font-bold text-[#6E675F] hover:text-[#FF6B00] transition mb-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    <span>Kembali ke Daftar Trainer</span>
                </a>
                <h1 class="font-montserrat font-extrabold text-2xl sm:text-3xl text-[#1E1B18] tracking-tight">
                    Tambah Trainer Baru
                </h1>
                <p class="font-quicksand text-sm text-[#6E675F] mt-1">
                    Daftarkan akun instruktur pengajar baru yang akan ditugaskan di <strong>{{ $branch->name }}</strong>.
                </p>
            </div>

            <!-- Form Card -->
            <div class="bg-white rounded-2xl border border-[#EBE5DF] shadow-sm p-6 sm:p-8">
                <form method="POST" action="{{ route('cabang.trainers.store') }}" class="space-y-6">
                    @csrf

                    <!-- Info Box -->
                    <div class="p-3 bg-[#FFF7ED] rounded-xl border border-[#FED7AA] flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-[#FF6B00] text-white flex items-center justify-center shrink-0">
                            🏢
                        </div>
                        <div class="text-xs text-[#9A3412] font-quicksand">
                            Akun trainer ini otomatis terikat pada kantor cabang <strong>{{ $branch->name }} ({{ $branch->code }})</strong>.
                        </div>
                    </div>

                    <!-- Row: Nama & Email -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <label for="name" class="block font-montserrat font-bold text-xs uppercase tracking-wider text-[#1E1B18] mb-2">
                                Nama Lengkap & Gelar <span class="text-[#DC2626]">*</span>
                            </label>
                            <input type="text"
                                   id="name"
                                   name="name"
                                   value="{{ old('name') }}"
                                   placeholder="Contoh: Dr. Budi Santoso, M.Kom"
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
                                   value="{{ old('email') }}"
                                   placeholder="Contoh: trainer.budi@lms.test"
                                   required
                                   class="w-full px-4 py-2.5 rounded-xl border @error('email') border-red-500 @else border-[#EBE5DF] @enderror bg-[#FAF8F5] text-sm text-[#1E1B18] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#FF6B00] focus:border-transparent font-quicksand transition">
                            @error('email')
                                <p class="mt-1.5 text-xs text-[#DC2626] font-semibold">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Row: Nomor Telepon -->
                    <div>
                        <label for="phone_number" class="block font-montserrat font-bold text-xs uppercase tracking-wider text-[#1E1B18] mb-2">
                            Nomor Handphone / WhatsApp
                        </label>
                        <input type="text"
                               id="phone_number"
                               name="phone_number"
                               value="{{ old('phone_number') }}"
                               placeholder="Contoh: 08123456789"
                               class="w-full px-4 py-2.5 rounded-xl border @error('phone_number') border-red-500 @else border-[#EBE5DF] @enderror bg-[#FAF8F5] text-sm text-[#1E1B18] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#FF6B00] focus:border-transparent font-quicksand transition">
                        @error('phone_number')
                            <p class="mt-1.5 text-xs text-[#DC2626] font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Row: Password & Konfirmasi Password -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <label for="password" class="block font-montserrat font-bold text-xs uppercase tracking-wider text-[#1E1B18] mb-2">
                                Kata Sandi <span class="text-[#DC2626]">*</span>
                            </label>
                            <input type="password"
                                   id="password"
                                   name="password"
                                   required
                                   placeholder="Minimal 8 karakter"
                                   class="w-full px-4 py-2.5 rounded-xl border @error('password') border-red-500 @else border-[#EBE5DF] @enderror bg-[#FAF8F5] text-sm text-[#1E1B18] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#FF6B00] focus:border-transparent font-quicksand transition">
                            @error('password')
                                <p class="mt-1.5 text-xs text-[#DC2626] font-semibold">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="password_confirmation" class="block font-montserrat font-bold text-xs uppercase tracking-wider text-[#1E1B18] mb-2">
                                Konfirmasi Kata Sandi <span class="text-[#DC2626]">*</span>
                            </label>
                            <input type="password"
                                   id="password_confirmation"
                                   name="password_confirmation"
                                   required
                                   placeholder="Ketik ulang kata sandi"
                                   class="w-full px-4 py-2.5 rounded-xl border border-[#EBE5DF] bg-[#FAF8F5] text-sm text-[#1E1B18] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#FF6B00] focus:border-transparent font-quicksand transition">
                        </div>
                    </div>

                    <!-- Status Akun -->
                    <div>
                        <label class="block font-montserrat font-bold text-xs uppercase tracking-wider text-[#1E1B18] mb-2">
                            Status Keaktifan Akun <span class="text-[#DC2626]">*</span>
                        </label>
                        <div class="flex gap-4">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="status" value="active" {{ old('status', 'active') === 'active' ? 'checked' : '' }} class="text-[#FF6B00] focus:ring-[#FF6B00]">
                                <span class="text-sm font-quicksand text-[#1E1B18]">Aktif</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="status" value="inactive" {{ old('status') === 'inactive' ? 'checked' : '' }} class="text-[#FF6B00] focus:ring-[#FF6B00]">
                                <span class="text-sm font-quicksand text-[#1E1B18]">Nonaktif</span>
                            </label>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="pt-4 flex items-center justify-end gap-3 border-t border-[#EBE5DF]">
                        <a href="{{ route('cabang.trainers.index') }}"
                           class="px-5 py-2.5 rounded-xl font-montserrat font-bold text-sm text-[#6E675F] hover:text-[#1E1B18] hover:bg-[#FAF8F5] transition">
                            Batal
                        </a>
                        <button type="submit"
                                class="px-6 py-2.5 rounded-xl font-montserrat font-bold text-sm text-white bg-gradient-to-r from-[#FF6B00] to-[#E11D48] hover:from-[#EA580C] hover:to-[#DC2626] shadow-md hover:shadow-lg transition-all duration-200 transform hover:-translate-y-0.5">
                            Simpan Akun Trainer
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>
