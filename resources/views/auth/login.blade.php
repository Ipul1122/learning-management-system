<x-guest-layout>
    <div class="space-y-6">
        <div>
            <h2 class="font-montserrat font-extrabold text-2xl text-[#1E1B18] tracking-tight">
                Masuk ke Akun
            </h2>
            <p class="font-quicksand text-xs text-[#6E675F] mt-1">
                Portal terpadu untuk Super Admin, Admin Cabang, Trainer, dan Peserta.
            </p>
        </div>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4 text-xs font-semibold text-emerald-600" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}" class="space-y-4">
            @csrf

            <!-- Email Address -->
            <div>
                <label for="email" class="block font-montserrat font-bold text-xs uppercase tracking-wider text-[#1E1B18] mb-1.5">
                    Alamat Email
                </label>
                <input id="email"
                       type="email"
                       name="email"
                       value="{{ old('email') }}"
                       required
                       autofocus
                       placeholder="nama@email.com"
                       class="w-full px-4 py-2.5 rounded-xl border @error('email') border-red-500 @else border-[#EBE5DF] @enderror bg-[#FAF8F5] text-sm text-[#1E1B18] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#FF6B00] focus:border-transparent font-quicksand transition">
                @error('email')
                    <p class="mt-1 text-xs text-[#DC2626] font-semibold">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password -->
            <div>
                <div class="flex items-center justify-between mb-1.5">
                    <label for="password" class="block font-montserrat font-bold text-xs uppercase tracking-wider text-[#1E1B18]">
                        Kata Sandi
                    </label>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-xs text-[#FF6B00] hover:text-[#EA580C] font-semibold">
                            Lupa sandi?
                        </a>
                    @endif
                </div>
                <input id="password"
                       type="password"
                       name="password"
                       required
                       autocomplete="current-password"
                       placeholder="••••••••"
                       class="w-full px-4 py-2.5 rounded-xl border @error('password') border-red-500 @else border-[#EBE5DF] @enderror bg-[#FAF8F5] text-sm text-[#1E1B18] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#FF6B00] focus:border-transparent font-quicksand transition">
                @error('password')
                    <p class="mt-1 text-xs text-[#DC2626] font-semibold">{{ $message }}</p>
                @enderror
            </div>

            <!-- Remember Me -->
            <div class="flex items-center justify-between pt-1">
                <label for="remember_me" class="inline-flex items-center cursor-pointer">
                    <input id="remember_me"
                           type="checkbox"
                           name="remember"
                           class="w-4 h-4 text-[#FF6B00] rounded border-[#EBE5DF] focus:ring-[#FF6B00]">
                    <span class="ms-2 text-xs font-quicksand text-[#6E675F]">Ingat saya di perangkat ini</span>
                </label>
            </div>

            <!-- Submit Button -->
            <div class="pt-2">
                <button type="submit"
                        class="w-full py-3 px-4 rounded-xl font-montserrat font-bold text-sm text-white bg-gradient-to-r from-[#FF6B00] to-[#E11D48] hover:from-[#EA580C] hover:to-[#DC2626] shadow-md hover:shadow-lg transition-all duration-200 transform hover:-translate-y-0.5">
                    Masuk Sekarang
                </button>
            </div>
        </form>

        <!-- Register Link for Peserta -->
        <div class="pt-2 text-center border-t border-[#EBE5DF]">
            <p class="font-quicksand text-xs text-[#6E675F]">
                Peserta baru belum punya akun?
                <a href="{{ route('register') }}" class="font-montserrat font-bold text-[#FF6B00] hover:underline">
                    Daftar sebagai Peserta
                </a>
            </p>
        </div>

        <!-- Demo / Testing Quick Login Credentials -->
        <div class="p-3.5 bg-[#FAF8F5] rounded-2xl border border-[#EBE5DF] space-y-2">
            <div class="flex items-center justify-between">
                <span class="font-montserrat font-bold text-[11px] uppercase tracking-wider text-[#6E675F]">
                    ⚡ Akun Demo Pengujian (1-Klik Isi)
                </span>
                <span class="text-[10px] text-[#6E675F] font-mono">pwd: password</span>
            </div>
            <div class="grid grid-cols-2 gap-1.5 text-xs">
                <button type="button"
                        onclick="fillCredentials('superadmin@lms.test', 'password')"
                        class="p-2 rounded-xl bg-white border border-[#EBE5DF] hover:border-[#FF6B00] text-left transition flex items-center gap-1.5 group">
                    <span class="w-2 h-2 rounded-full bg-[#EA580C]"></span>
                    <div>
                        <div class="font-montserrat font-bold text-[11px] text-[#1E1B18] group-hover:text-[#FF6B00]">Super Admin</div>
                        <div class="text-[10px] text-[#6E675F] truncate max-w-[110px]">superadmin@...</div>
                    </div>
                </button>

                <button type="button"
                        onclick="fillCredentials('admin.jkt@lms.test', 'password')"
                        class="p-2 rounded-xl bg-white border border-[#EBE5DF] hover:border-[#DC2626] text-left transition flex items-center gap-1.5 group">
                    <span class="w-2 h-2 rounded-full bg-[#DC2626]"></span>
                    <div>
                        <div class="font-montserrat font-bold text-[11px] text-[#1E1B18] group-hover:text-[#DC2626]">Admin Cabang</div>
                        <div class="text-[10px] text-[#6E675F] truncate max-w-[110px]">admin.jkt@...</div>
                    </div>
                </button>

                <button type="button"
                        onclick="fillCredentials('trainer1@lms.test', 'password')"
                        class="p-2 rounded-xl bg-white border border-[#EBE5DF] hover:border-[#FF6B00] text-left transition flex items-center gap-1.5 group">
                    <span class="w-2 h-2 rounded-full bg-[#FF6B00]"></span>
                    <div>
                        <div class="font-montserrat font-bold text-[11px] text-[#1E1B18] group-hover:text-[#FF6B00]">Trainer</div>
                        <div class="text-[10px] text-[#6E675F] truncate max-w-[110px]">trainer1@...</div>
                    </div>
                </button>

                <button type="button"
                        onclick="fillCredentials('peserta1@lms.test', 'password')"
                        class="p-2 rounded-xl bg-white border border-[#EBE5DF] hover:border-[#10B981] text-left transition flex items-center gap-1.5 group">
                    <span class="w-2 h-2 rounded-full bg-[#10B981]"></span>
                    <div>
                        <div class="font-montserrat font-bold text-[11px] text-[#1E1B18] group-hover:text-[#10B981]">Peserta</div>
                        <div class="text-[10px] text-[#6E675F] truncate max-w-[110px]">peserta1@...</div>
                    </div>
                </button>
            </div>
        </div>
    </div>

    <script>
        function fillCredentials(email, password) {
            document.getElementById('email').value = email;
            document.getElementById('password').value = password;
        }
    </script>
</x-guest-layout>
