<x-guest-layout>
    <div class="space-y-6" x-data="{
        cooldown: 60,
        timer: null,
        init() {
            this.timer = setInterval(() => {
                if (this.cooldown > 0) {
                    this.cooldown--;
                }
            }, 1000);
        }
    }">
        <div>
            <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-bold font-montserrat bg-[#ECFDF5] text-[#10B981] border border-[#A7F3D0] mb-2">
                <span>🔑</span>
                <span>Verifikasi OTP & Reset Password</span>
            </div>
            <h2 class="font-montserrat font-extrabold text-2xl text-[#1E1B18] tracking-tight">
                Atur Kata Sandi Baru
            </h2>
            <p class="font-quicksand text-xs text-[#6E675F] mt-1 leading-relaxed">
                Silakan masukkan 6 digit kode OTP yang kami kirimkan ke email Anda, lalu buat kata sandi baru yang kuat.
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

        @if (session('error'))
            <div class="p-3.5 rounded-xl bg-rose-500/10 border border-rose-500/20 text-rose-700 text-xs font-medium flex items-center gap-2">
                <svg class="w-4 h-4 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <form method="POST" action="{{ route('password.reset.otp.update') }}" class="space-y-4">
            @csrf

            <!-- Email Address -->
            <div>
                <label for="email" class="block font-montserrat font-bold text-xs uppercase tracking-wider text-[#1E1B18] mb-1.5">
                    Alamat Email <span class="text-[#DC2626]">*</span>
                </label>
                <input id="email"
                       type="email"
                       name="email"
                       value="{{ old('email', $email) }}"
                       required
                       readonly
                       class="w-full px-4 py-2.5 rounded-xl border border-[#EBE5DF] bg-[#F5F3EF] text-sm text-[#78716C] font-mono cursor-not-allowed">
                @error('email')
                    <p class="mt-1 text-xs text-[#DC2626] font-semibold">{{ $message }}</p>
                @enderror
            </div>

            <!-- OTP Code -->
            <div>
                <label for="otp_code" class="block font-montserrat font-bold text-xs uppercase tracking-wider text-[#1E1B18] mb-1.5">
                    Kode OTP 6 Digit <span class="text-[#DC2626]">*</span>
                </label>
                <input id="otp_code"
                       type="text"
                       name="otp_code"
                       maxlength="6"
                       pattern="[0-9]{6}"
                       inputmode="numeric"
                       required
                       autofocus
                       placeholder="Contoh: 123456"
                       class="w-full text-center tracking-[8px] font-mono text-xl font-bold px-4 py-2.5 rounded-xl border @error('otp_code') border-red-500 @else border-[#EBE5DF] @enderror bg-[#FAF8F5] text-[#1E1B18] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#FF6B00] focus:border-transparent transition">
                @error('otp_code')
                    <p class="mt-1 text-xs text-[#DC2626] font-semibold">{{ $message }}</p>
                @enderror
            </div>

            <!-- New Password -->
            <div>
                <label for="password" class="block font-montserrat font-bold text-xs uppercase tracking-wider text-[#1E1B18] mb-1.5">
                    Kata Sandi Baru <span class="text-[#DC2626]">*</span>
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
                    Konfirmasi Kata Sandi Baru <span class="text-[#DC2626]">*</span>
                </label>
                <input id="password_confirmation"
                       type="password"
                       name="password_confirmation"
                       required
                       autocomplete="new-password"
                       placeholder="Ulangi kata sandi baru"
                       class="w-full px-4 py-2.5 rounded-xl border @error('password_confirmation') border-red-500 @else border-[#EBE5DF] @enderror bg-[#FAF8F5] text-sm text-[#1E1B18] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#FF6B00] focus:border-transparent font-quicksand transition">
                @error('password_confirmation')
                    <p class="mt-1 text-xs text-[#DC2626] font-semibold">{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit Button -->
            <div class="pt-2">
                <button type="submit"
                        class="w-full py-3 px-4 rounded-xl font-montserrat font-bold text-sm text-white bg-gradient-to-r from-[#FF6B00] to-[#E11D48] hover:from-[#EA580C] hover:to-[#DC2626] shadow-md hover:shadow-lg transition-all duration-200 transform hover:-translate-y-0.5 flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <span>Perbarui Kata Sandi Saya</span>
                </button>
            </div>
        </form>

        <!-- Resend OTP Action -->
        <div class="pt-3 border-t border-[#EBE5DF] flex flex-col sm:flex-row items-center justify-between gap-3 text-xs font-quicksand text-[#6E675F]">
            <div>
                Tidak menerima kode OTP?
            </div>
            <div>
                <form method="POST" action="{{ route('password.reset.otp.resend') }}">
                    @csrf
                    <input type="hidden" name="email" value="{{ $email }}">
                    <button type="submit"
                            :disabled="cooldown > 0"
                            class="font-montserrat font-bold text-[#FF6B00] hover:text-[#EA580C] disabled:opacity-50 disabled:cursor-not-allowed transition">
                        <span x-show="cooldown > 0">Kirim ulang dalam (<span x-text="cooldown"></span>s)</span>
                        <span x-show="cooldown <= 0">Kirim Ulang Kode OTP &rarr;</span>
                    </button>
                </form>
            </div>
        </div>

        <!-- Back to Login Link -->
        <div class="text-center pt-2">
            <a href="{{ route('login') }}" class="text-xs text-[#A8A29E] hover:text-[#1E1B18] font-mono transition">
                &larr; Batalkan & kembali ke login
            </a>
        </div>
    </div>
</x-guest-layout>
