<x-guest-layout>
    <div class="space-y-6">
        <div>
            <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-bold font-montserrat bg-[#ECFDF5] text-[#10B981] border border-[#A7F3D0] mb-2">
                <span>🎓</span>
                <span>Khusus Akun Peserta</span>
            </div>
            <h2 class="font-montserrat font-extrabold text-2xl text-[#1E1B18] tracking-tight">
                Pendaftaran Peserta Baru
            </h2>
            <p class="font-quicksand text-xs text-[#6E675F] mt-1">
                Daftarkan diri Anda untuk mengikuti kelas pembelajaran online/offline dan melacak target 20 JP pelatihan.
            </p>
        </div>

        <!-- Info Alert -->
        <div class="p-3 bg-[#FFF7ED] rounded-xl border border-[#FED7AA] flex items-start gap-2.5 text-xs text-[#9A3412]">
            <svg class="w-4 h-4 text-[#FF6B00] shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <p class="font-quicksand">
                <strong>Catatan:</strong> Akun Trainer dan Admin Cabang didaftarkan oleh Super Admin / Admin Cabang dan tidak melalui formulir publik ini.
            </p>
        </div>

        <form method="POST" action="{{ route('register') }}" class="space-y-4">
            @csrf

            <!-- Name -->
            <div>
                <label for="name" class="block font-montserrat font-bold text-xs uppercase tracking-wider text-[#1E1B18] mb-1.5">
                    Nama Lengkap <span class="text-[#DC2626]">*</span>
                </label>
                <input id="name"
                       type="text"
                       name="name"
                       value="{{ old('name') }}"
                       required
                       autofocus
                       placeholder="Contoh: Budi Pratama"
                       class="w-full px-4 py-2.5 rounded-xl border @error('name') border-red-500 @else border-[#EBE5DF] @enderror bg-[#FAF8F5] text-sm text-[#1E1B18] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#FF6B00] focus:border-transparent font-quicksand transition">
                @error('name')
                    <p class="mt-1 text-xs text-[#DC2626] font-semibold">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email Address -->
            <div>
                <label for="email" class="block font-montserrat font-bold text-xs uppercase tracking-wider text-[#1E1B18] mb-1.5">
                    Alamat Email <span class="text-[#DC2626]">*</span>
                </label>
                <input id="email"
                       type="email"
                       name="email"
                       value="{{ old('email') }}"
                       required
                       placeholder="budi@example.com"
                       class="w-full px-4 py-2.5 rounded-xl border @error('email') border-red-500 @else border-[#EBE5DF] @enderror bg-[#FAF8F5] text-sm text-[#1E1B18] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#FF6B00] focus:border-transparent font-quicksand transition">
                @error('email')
                    <p class="mt-1 text-xs text-[#DC2626] font-semibold">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block font-montserrat font-bold text-xs uppercase tracking-wider text-[#1E1B18] mb-1.5">
                    Kata Sandi <span class="text-[#DC2626]">*</span>
                </label>
                <input id="password"
                       type="password"
                       name="password"
                       required
                       autocomplete="new-password"
                       placeholder="Minimal 8 karakter"
                       class="w-full px-4 py-2.5 rounded-xl border @error('password') border-red-500 @else border-[#EBE5DF] @enderror bg-[#FAF8F5] text-sm text-[#1E1B18] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#FF6B00] focus:border-transparent font-quicksand transition">
                @error('password')
                    <p class="mt-1 text-xs text-[#DC2626] font-semibold">{{ $message }}</p>
                @enderror
            </div>

            <!-- Confirm Password -->
            <div>
                <label for="password_confirmation" class="block font-montserrat font-bold text-xs uppercase tracking-wider text-[#1E1B18] mb-1.5">
                    Konfirmasi Kata Sandi <span class="text-[#DC2626]">*</span>
                </label>
                <input id="password_confirmation"
                       type="password"
                       name="password_confirmation"
                       required
                       autocomplete="new-password"
                       placeholder="Ulangi kata sandi Anda"
                       class="w-full px-4 py-2.5 rounded-xl border @error('password_confirmation') border-red-500 @else border-[#EBE5DF] @enderror bg-[#FAF8F5] text-sm text-[#1E1B18] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#FF6B00] focus:border-transparent font-quicksand transition">
                @error('password_confirmation')
                    <p class="mt-1 text-xs text-[#DC2626] font-semibold">{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit Button -->
            <div class="pt-2">
                <button type="submit"
                        class="w-full py-3 px-4 rounded-xl font-montserrat font-bold text-sm text-white bg-gradient-to-r from-[#FF6B00] to-[#E11D48] hover:from-[#EA580C] hover:to-[#DC2626] shadow-md hover:shadow-lg transition-all duration-200 transform hover:-translate-y-0.5">
                    Daftar sebagai Peserta
                </button>
            </div>
        </form>

        <!-- Login Link -->
        <div class="pt-2 text-center border-t border-[#EBE5DF]">
            <p class="font-quicksand text-xs text-[#6E675F]">
                Sudah memiliki akun?
                <a href="{{ route('login') }}" class="font-montserrat font-bold text-[#FF6B00] hover:underline">
                    Masuk ke Akun Anda
                </a>
            </p>
        </div>
    </div>
</x-guest-layout>
