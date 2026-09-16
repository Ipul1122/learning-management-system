<x-guest-layout>
    <div class="space-y-6">
        <div>
            <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-bold font-montserrat bg-[#FFF7ED] text-[#EA580C] border border-[#FED7AA] mb-2">
                <span>🔐</span>
                <span>Bantuan Akses Akun</span>
            </div>
            <h2 class="font-montserrat font-extrabold text-2xl text-[#1E1B18] tracking-tight">
                Lupa Kata Sandi?
            </h2>
            <p class="font-quicksand text-xs text-[#6E675F] mt-1 leading-relaxed">
                Jangan khawatir. Masukkan alamat email akun Anda, dan sistem kami akan mengirimkan 6 digit kode OTP verifikasi untuk mengatur ulang kata sandi.
            </p>
        </div>

        <!-- Session Status -->
        @if (session('status'))
            <div class="p-3.5 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-700 text-xs font-medium flex items-center gap-2">
                <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                <span>{{ session('status') }}</span>
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
            @csrf

            <!-- Email Address -->
            <div>
                <label for="email" class="block font-montserrat font-bold text-xs uppercase tracking-wider text-[#1E1B18] mb-1.5">
                    Alamat Email Terdaftar <span class="text-[#DC2626]">*</span>
                </label>
                <input id="email"
                       type="email"
                       name="email"
                       value="{{ old('email') }}"
                       required
                       autofocus
                       placeholder="nama@example.com"
                       class="w-full px-4 py-2.5 rounded-xl border @error('email') border-red-500 @else border-[#EBE5DF] @enderror bg-[#FAF8F5] text-sm text-[#1E1B18] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#FF6B00] focus:border-transparent font-quicksand transition">
                @error('email')
                    <p class="mt-1.5 text-xs text-[#DC2626] font-semibold">{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit Button -->
            <div class="pt-2">
                <button type="submit"
                        class="w-full py-3 px-4 rounded-xl font-montserrat font-bold text-sm text-white bg-gradient-to-r from-[#FF6B00] to-[#E11D48] hover:from-[#EA580C] hover:to-[#DC2626] shadow-md hover:shadow-lg transition-all duration-200 transform hover:-translate-y-0.5 flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                    <span>Kirim Kode OTP Pemulihan</span>
                </button>
            </div>
        </form>

        <!-- Back to Login Link -->
        <div class="pt-2 text-center border-t border-[#EBE5DF]">
            <p class="font-quicksand text-xs text-[#6E675F]">
                Ingat kembali kata sandi Anda?
                <a href="{{ route('login') }}" class="font-montserrat font-bold text-[#FF6B00] hover:underline">
                    Kembali ke Halaman Masuk
                </a>
            </p>
        </div>
    </div>
</x-guest-layout>
