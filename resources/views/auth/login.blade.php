<x-guest-layout :fullPage="true" title="Masuk ke Akun">
    <div class="min-h-screen w-full flex flex-col lg:flex-row bg-[#FAF8F5]">
        
        <!-- LEFT PANEL: Hero Branding & Feature Showcase (Desktop Model) -->
        <div class="lg:w-1/2 xl:w-7/12 bg-[#0F172A] relative flex flex-col justify-between p-8 sm:p-12 lg:p-16 text-white overflow-hidden shrink-0">
            <!-- Ambient Glow Gradients -->
            <div class="absolute -top-32 -left-32 w-96 h-96 rounded-full bg-gradient-to-br from-[#FF6B00]/25 to-[#E11D48]/20 blur-3xl pointer-events-none"></div>
            <div class="absolute -bottom-32 -right-32 w-96 h-96 rounded-full bg-gradient-to-tl from-[#6366F1]/20 to-[#FF6B00]/15 blur-3xl pointer-events-none"></div>
            <div class="absolute inset-0 bg-[radial-gradient(#ffffff0a_1px,transparent_1px)] [background-size:24px_24px] pointer-events-none opacity-40"></div>

            <!-- Top Brand Header -->
            <div class="relative z-10 flex items-center justify-between">
                <a href="/" class="inline-flex items-center gap-3.5 group">
                    <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-[#FF6B00] via-[#EA580C] to-[#E11D48] flex items-center justify-center text-white shadow-lg shadow-[#FF6B00]/30 font-montserrat font-extrabold text-2xl group-hover:scale-105 transition-transform">
                        L
                    </div>
                    <div>
                        <div class="font-montserrat font-extrabold text-xl tracking-tight text-white flex items-center gap-2">
                            LMS <span class="bg-gradient-to-r from-[#FF6B00] to-[#F59E0B] bg-clip-text text-transparent">Multi-Cabang</span>
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold font-montserrat bg-white/10 text-[#FF8A3D] border border-white/15">v2.0</span>
                        </div>
                        <div class="font-quicksand text-xs font-semibold text-slate-400">
                            Sistem Manajemen Pelatihan & Sertifikasi Terpadu
                        </div>
                    </div>
                </a>

                <a href="/" class="hidden sm:inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl text-xs font-bold font-montserrat text-slate-300 hover:text-white bg-white/5 hover:bg-white/10 border border-white/10 transition">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    <span>Beranda</span>
                </a>
            </div>

            <!-- Center Hero Content -->
            <div class="relative z-10 my-10 lg:my-auto max-w-xl">
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-white/10 backdrop-blur-md border border-white/15 text-xs font-bold font-montserrat text-[#FF8A3D] mb-6">
                    <span class="w-2 h-2 rounded-full bg-[#FF6B00] animate-pulse"></span>
                    <span>Portal Akses Terintegrasi</span>
                </div>

                <h1 class="font-montserrat font-extrabold text-3xl sm:text-4xl lg:text-5xl text-white tracking-tight leading-[1.15] mb-5">
                    Satu Akses untuk Seluruh <span class="bg-gradient-to-r from-[#FF6B00] via-[#F59E0B] to-[#E11D48] bg-clip-text text-transparent">Ekosistem Pelatihan</span>
                </h1>

                <p class="font-quicksand text-sm sm:text-base text-slate-300 leading-relaxed mb-8">
                    Menghubungkan Super Admin pusat, kantor cabang regional, instruktur kejuruan, dan peserta didik dalam satu platform pembelajaran berstandar kompetensi 20 JP.
                </p>

                <!-- Value Highlights Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
                    <div class="p-4 rounded-2xl bg-white/[0.04] border border-white/10 backdrop-blur-sm">
                        <div class="w-8 h-8 rounded-xl bg-[#FF6B00]/20 text-[#FF8A3D] flex items-center justify-center mb-2.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        </div>
                        <h4 class="font-montserrat font-bold text-xs text-white mb-1">Multi-Cabang</h4>
                        <p class="font-quicksand text-[11px] text-slate-400 leading-relaxed">Pengawasan & pembagian operasional cabang se-Indonesia.</p>
                    </div>

                    <div class="p-4 rounded-2xl bg-white/[0.04] border border-white/10 backdrop-blur-sm">
                        <div class="w-8 h-8 rounded-xl bg-amber-500/20 text-amber-300 flex items-center justify-center mb-2.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <h4 class="font-montserrat font-bold text-xs text-white mb-1">Standar 20 JP</h4>
                        <p class="font-quicksand text-[11px] text-slate-400 leading-relaxed">Akumulasi 900 menit pembelajaran efektif & evaluasi kuis.</p>
                    </div>

                    <div class="p-4 rounded-2xl bg-white/[0.04] border border-white/10 backdrop-blur-sm">
                        <div class="w-8 h-8 rounded-xl bg-emerald-500/20 text-emerald-300 flex items-center justify-center mb-2.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        </div>
                        <h4 class="font-montserrat font-bold text-xs text-white mb-1">E-Sertifikat QR</h4>
                        <p class="font-quicksand text-[11px] text-slate-400 leading-relaxed">Sertifikat kelulusan sah dengan QR code verifikasi publik.</p>
                    </div>
                </div>

                <!-- Testimonial Quote -->
                <div class="p-4 rounded-2xl bg-gradient-to-r from-white/[0.06] to-transparent border-l-4 border-[#FF6B00] border-y border-r border-white/10 flex items-center gap-3.5">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-[#FF6B00] to-[#E11D48] text-white flex items-center justify-center font-bold text-xs font-montserrat shrink-0">
                        AF
                    </div>
                    <div>
                        <p class="font-quicksand text-xs text-slate-200 italic leading-relaxed">
                            "Evaluasi kuis otomatis, silabus tatap muka, dan verifikasi kelulusan 20 JP berjalan teratur dan transparan."
                        </p>
                        <div class="text-[11px] font-montserrat font-bold text-slate-400 mt-1">
                            Ahmad Fauzi, M.Kom &bull; <span class="text-slate-500 font-normal">Instruktur Pengampu</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bottom Security Badges -->
            <div class="relative z-10 pt-6 border-t border-white/10 flex flex-wrap items-center justify-between gap-4 text-xs font-quicksand text-slate-400">
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                    <span>Sistem Operasional & Terenkripsi</span>
                </div>
                <div class="flex items-center gap-4 text-slate-500 text-[11px]">
                    <span>Standard Mutu Kejuruan</span>
                    <span>&bull;</span>
                    <span>&copy; {{ date('Y') }} LMS Multi-Cabang</span>
                </div>
            </div>
        </div>

        <!-- RIGHT PANEL: Full Page Form Container -->
        <div class="lg:w-1/2 xl:w-5/12 min-h-screen flex flex-col justify-center px-6 sm:px-12 lg:px-16 py-12 bg-white relative">
            <div class="max-w-md w-full mx-auto space-y-6">
                
                <!-- Form Header -->
                <div class="space-y-2">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-bold font-montserrat bg-[#FAF8F5] text-[#6E675F] border border-[#EBE5DF]">
                        <svg class="w-3.5 h-3.5 text-[#FF6B00]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
                        <span>Otorisasi Pengguna Terdaftar</span>
                    </div>
                    <h2 class="font-montserrat font-extrabold text-2xl sm:text-3xl text-[#1E1B18] tracking-tight">
                        Masuk ke Akun Anda
                    </h2>
                    <p class="font-quicksand text-xs sm:text-sm text-[#6E675F]">
                        Masukkan alamat email dan kata sandi yang telah terdaftar pada sistem LMS.
                    </p>
                </div>

                <!-- Session Status -->
                <x-auth-session-status class="p-3.5 rounded-xl bg-emerald-50 border border-emerald-200 text-xs font-semibold text-emerald-700 font-quicksand" :status="session('status')" />

                <!-- Login Form -->
                <form method="POST" action="{{ route('login') }}" class="space-y-4">
                    @csrf

                    <!-- Email Address -->
                    <div>
                        <label for="email" class="block font-montserrat font-bold text-xs uppercase tracking-wider text-[#1E1B18] mb-1.5">
                            Alamat Email
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-[#6E675F]">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.206"/></svg>
                            </div>
                            <input id="email"
                                   type="email"
                                   name="email"
                                   value="{{ old('email') }}"
                                   required
                                   autofocus
                                   placeholder="nama@email.com"
                                   class="w-full pl-10 pr-4 py-3 rounded-xl border @error('email') border-red-500 @else border-[#EBE5DF] @enderror bg-[#FAF8F5] text-sm text-[#1E1B18] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#FF6B00] focus:border-transparent font-quicksand transition">
                        </div>
                        @error('email')
                            <p class="mt-1.5 text-xs text-[#DC2626] font-semibold flex items-center gap-1 font-quicksand">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <span>{{ $message }}</span>
                            </p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label for="password" class="block font-montserrat font-bold text-xs uppercase tracking-wider text-[#1E1B18]">
                                Kata Sandi
                            </label>
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="text-xs text-[#FF6B00] hover:text-[#EA580C] font-semibold font-quicksand transition">
                                    Lupa kata sandi?
                                </a>
                            @endif
                        </div>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-[#6E675F]">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                            </div>
                            <input id="password"
                                   type="password"
                                   name="password"
                                   required
                                   autocomplete="current-password"
                                   placeholder="••••••••"
                                   class="w-full pl-10 pr-10 py-3 rounded-xl border @error('password') border-red-500 @else border-[#EBE5DF] @enderror bg-[#FAF8F5] text-sm text-[#1E1B18] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#FF6B00] focus:border-transparent font-quicksand transition">
                            <button type="button"
                                    onclick="togglePasswordVisibility('password', this)"
                                    class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-[#6E675F] hover:text-[#1E1B18] transition">
                                <svg class="w-4 h-4 eye-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            </button>
                        </div>
                        @error('password')
                            <p class="mt-1.5 text-xs text-[#DC2626] font-semibold flex items-center gap-1 font-quicksand">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <span>{{ $message }}</span>
                            </p>
                        @enderror
                    </div>

                    <!-- Remember Me -->
                    <div class="flex items-center justify-between pt-1">
                        <label for="remember_me" class="inline-flex items-center cursor-pointer">
                            <input id="remember_me"
                                   type="checkbox"
                                   name="remember"
                                   class="w-4 h-4 text-[#FF6B00] rounded border-[#EBE5DF] focus:ring-[#FF6B00]">
                            <span class="ms-2.5 text-xs font-quicksand text-[#6E675F]">Ingat saya di perangkat ini</span>
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-2">
                        <button type="submit"
                                class="w-full py-3.5 px-5 rounded-xl font-montserrat font-bold text-sm text-white bg-gradient-to-r from-[#FF6B00] via-[#EA580C] to-[#E11D48] hover:from-[#EA580C] hover:to-[#DC2626] shadow-lg shadow-[#FF6B00]/25 hover:shadow-xl hover:shadow-[#FF6B00]/35 transition-all duration-200 transform hover:-translate-y-0.5 flex items-center justify-center gap-2">
                            <span>Masuk ke Portal LMS</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </button>
                    </div>
                </form>

                <!-- Register Link for Peserta -->
                <div class="pt-3 text-center border-t border-[#EBE5DF]">
                    <p class="font-quicksand text-xs sm:text-sm text-[#6E675F]">
                        Peserta baru belum memiliki akun?
                        <a href="{{ route('register') }}" class="font-montserrat font-bold text-[#FF6B00] hover:text-[#EA580C] hover:underline transition">
                            Daftar Sekarang &rarr;
                        </a>
                    </p>
                </div>

                <!-- 1-Click Quick Demo Login Credentials (Testing Helper) -->
                <div class="p-4 bg-[#FAF8F5] rounded-2xl border border-[#EBE5DF] space-y-2.5 shadow-sm">
                    <div class="flex items-center justify-between">
                        <span class="font-montserrat font-bold text-[11px] uppercase tracking-wider text-[#6E675F] flex items-center gap-1.5">
                            <span>⚡</span>
                            <span>Akses Demo Cepat (1-Klik Isi)</span>
                        </span>
                        <span class="text-[10px] text-[#6E675F] font-mono bg-white px-2 py-0.5 rounded border border-[#EBE5DF]">pwd: password</span>
                    </div>

                    <div class="grid grid-cols-2 gap-2 text-xs">
                        <button type="button"
                                onclick="fillCredentials('superadmin@lms.test', 'password')"
                                class="p-2.5 rounded-xl bg-white border border-[#EBE5DF] hover:border-[#FF6B00] text-left transition flex items-center gap-2 group shadow-sm hover:shadow">
                            <span class="w-2.5 h-2.5 rounded-full bg-[#EA580C] shrink-0"></span>
                            <div class="overflow-hidden">
                                <div class="font-montserrat font-bold text-[11px] text-[#1E1B18] group-hover:text-[#FF6B00] truncate">Super Admin</div>
                                <div class="text-[10px] text-[#6E675F] truncate">superadmin@...</div>
                            </div>
                        </button>

                        <button type="button"
                                onclick="fillCredentials('admin.jkt@lms.test', 'password')"
                                class="p-2.5 rounded-xl bg-white border border-[#EBE5DF] hover:border-[#DC2626] text-left transition flex items-center gap-2 group shadow-sm hover:shadow">
                            <span class="w-2.5 h-2.5 rounded-full bg-[#DC2626] shrink-0"></span>
                            <div class="overflow-hidden">
                                <div class="font-montserrat font-bold text-[11px] text-[#1E1B18] group-hover:text-[#DC2626] truncate">Admin Cabang</div>
                                <div class="text-[10px] text-[#6E675F] truncate">admin.jkt@...</div>
                            </div>
                        </button>

                        <button type="button"
                                onclick="fillCredentials('trainer1@lms.test', 'password')"
                                class="p-2.5 rounded-xl bg-white border border-[#EBE5DF] hover:border-[#FF6B00] text-left transition flex items-center gap-2 group shadow-sm hover:shadow">
                            <span class="w-2.5 h-2.5 rounded-full bg-[#FF6B00] shrink-0"></span>
                            <div class="overflow-hidden">
                                <div class="font-montserrat font-bold text-[11px] text-[#1E1B18] group-hover:text-[#FF6B00] truncate">Trainer</div>
                                <div class="text-[10px] text-[#6E675F] truncate">trainer1@...</div>
                            </div>
                        </button>

                        <button type="button"
                                onclick="fillCredentials('peserta1@lms.test', 'password')"
                                class="p-2.5 rounded-xl bg-white border border-[#EBE5DF] hover:border-[#10B981] text-left transition flex items-center gap-2 group shadow-sm hover:shadow">
                            <span class="w-2.5 h-2.5 rounded-full bg-[#10B981] shrink-0"></span>
                            <div class="overflow-hidden">
                                <div class="font-montserrat font-bold text-[11px] text-[#1E1B18] group-hover:text-[#10B981] truncate">Peserta</div>
                                <div class="text-[10px] text-[#6E675F] truncate">peserta1@...</div>
                            </div>
                        </button>
                    </div>
                </div>

            </div>
        </div>

    </div>

    <script>
        function fillCredentials(email, password) {
            document.getElementById('email').value = email;
            document.getElementById('password').value = password;
        }

        function togglePasswordVisibility(inputId, btn) {
            const input = document.getElementById(inputId);
            if (input.type === 'password') {
                input.type = 'text';
                btn.innerHTML = `<svg class="w-4 h-4 text-[#FF6B00]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/></svg>`;
            } else {
                input.type = 'password';
                btn.innerHTML = `<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>`;
            }
        }
    </script>
</x-guest-layout>
