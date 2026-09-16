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
            <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-bold font-montserrat bg-[#FFF7ED] text-[#EA580C] border border-[#FED7AA] mb-2">
                <span class="w-2 h-2 rounded-full bg-[#EA580C] animate-pulse"></span>
                <span>Langkah Terakhir Pendaftaran</span>
            </div>
            <h2 class="font-montserrat font-extrabold text-2xl text-[#1E1B18] tracking-tight">
                Masukkan Kode OTP
            </h2>
            <p class="font-quicksand text-xs text-[#6E675F] mt-1 leading-relaxed">
                Kami telah mengirimkan 6 digit kode verifikasi ke alamat email:
                <strong class="text-[#1E1B18] font-mono block sm:inline mt-0.5 sm:mt-0">{{ $email }}</strong>
            </p>
        </div>

        <!-- Session Flash Status -->
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

        <form method="POST" action="{{ route('register.otp.verify') }}" class="space-y-5">
            @csrf
            <input type="hidden" name="email" value="{{ $email }}">

            <!-- OTP Code Input -->
            <div>
                <label for="otp_code" class="block font-montserrat font-bold text-xs uppercase tracking-wider text-[#1E1B18] mb-1.5 text-center">
                    6 Digit Kode Verifikasi OTP <span class="text-[#DC2626]">*</span>
                </label>
                <div class="relative max-w-xs mx-auto">
                    <input id="otp_code"
                           type="text"
                           name="otp_code"
                           maxlength="6"
                           pattern="[0-9]{6}"
                           inputmode="numeric"
                           autocomplete="one-time-code"
                           required
                           autofocus
                           placeholder="••••••"
                           class="w-full text-center tracking-[12px] font-mono text-2xl font-extrabold px-4 py-3 rounded-2xl border @error('otp_code') border-red-500 @else border-[#EBE5DF] @enderror bg-[#FAF8F5] text-[#1E1B18] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#FF6B00] focus:border-transparent transition shadow-inner">
                </div>
                @error('otp_code')
                    <p class="mt-2 text-xs text-[#DC2626] font-semibold text-center">{{ $message }}</p>
                @enderror
                <p class="text-[11px] text-[#A8A29E] text-center mt-2 font-mono">
                    Kode berlaku selama 10 menit sejak dikirimkan
                </p>
            </div>

            <!-- Submit Button -->
            <div class="pt-2">
                <button type="submit"
                        class="w-full py-3 px-4 rounded-xl font-montserrat font-bold text-sm text-white bg-gradient-to-r from-[#FF6B00] to-[#E11D48] hover:from-[#EA580C] hover:to-[#DC2626] shadow-md hover:shadow-lg transition-all duration-200 transform hover:-translate-y-0.5 flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>Verifikasi & Aktifkan Akun</span>
                </button>
            </div>
        </form>

        <!-- Resend OTP Action -->
        <div class="pt-3 border-t border-[#EBE5DF] flex flex-col sm:flex-row items-center justify-between gap-3 text-xs font-quicksand text-[#6E675F]">
            <div>
                Belum menerima kode OTP?
            </div>
            <div>
                <form method="POST" action="{{ route('register.otp.resend') }}">
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

        <!-- Back Link -->
        <div class="text-center pt-2">
            <a href="{{ route('register') }}" class="text-xs text-[#A8A29E] hover:text-[#1E1B18] font-mono transition">
                &larr; Ganti alamat email pendaftaran
            </a>
        </div>
    </div>
</x-guest-layout>
