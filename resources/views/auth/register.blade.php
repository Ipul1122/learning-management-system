<x-guest-layout :fullPage="true" title="Pendaftaran Peserta Baru">
    <div class="min-h-screen w-full flex flex-col lg:flex-row bg-[#FAF8F5]">
        
        <!-- LEFT PANEL: Hero Branding & Student Journey Showcase (Desktop Model) -->
        <div class="lg:w-1/2 xl:w-7/12 bg-[#0F172A] relative flex flex-col justify-between p-8 sm:p-12 lg:p-16 text-white overflow-hidden shrink-0">
            <!-- Ambient Glow Gradients -->
            <div class="absolute -top-32 -left-32 w-96 h-96 rounded-full bg-gradient-to-br from-[#10B981]/20 to-[#FF6B00]/20 blur-3xl pointer-events-none"></div>
            <div class="absolute -bottom-32 -right-32 w-96 h-96 rounded-full bg-gradient-to-tl from-[#E11D48]/20 to-[#10B981]/15 blur-3xl pointer-events-none"></div>
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
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold font-montserrat bg-[#10B981]/20 text-[#34D399] border border-[#10B981]/30">Peserta Baru</span>
                        </div>
                        <div class="font-quicksand text-xs font-semibold text-slate-400">
                            Pusat Pelatihan & Sertifikasi Terstandarisasi
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
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-white/10 backdrop-blur-md border border-white/15 text-xs font-bold font-montserrat text-[#34D399] mb-6">
                    <span class="w-2 h-2 rounded-full bg-[#10B981] animate-pulse"></span>
                    <span>Registrasi Peserta Terbuka</span>
                </div>

                <h1 class="font-montserrat font-extrabold text-3xl sm:text-4xl lg:text-5xl text-white tracking-tight leading-[1.15] mb-5">
                    Kembangkan Potensi & Raih <span class="bg-gradient-to-r from-[#10B981] via-[#F59E0B] to-[#FF6B00] bg-clip-text text-transparent">Sertifikasi Resmi 20 JP</span>
                </h1>

                <p class="font-quicksand text-sm sm:text-base text-slate-300 leading-relaxed mb-8">
                    Daftarkan diri Anda untuk mengakses katalog kelas kejuruan, sesi interaktif Zoom, evaluasi kuis real-time, serta sertifikat digital kelulusan ber-QR code.
                </p>

                <!-- Student Roadmap Step Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5 mb-8">
                    <div class="p-4 rounded-2xl bg-white/[0.04] border border-white/10 backdrop-blur-sm flex items-start gap-3">
                        <div class="w-8 h-8 rounded-xl bg-emerald-500/20 text-emerald-400 flex items-center justify-center font-bold text-xs font-montserrat shrink-0">
                            1
                        </div>
                        <div>
                            <h4 class="font-montserrat font-bold text-xs text-white mb-0.5">Verifikasi OTP Email</h4>
                            <p class="font-quicksand text-[11px] text-slate-400 leading-relaxed">Aktivasi akun secara instan dengan kode 6 digit aman.</p>
                        </div>
                    </div>

                    <div class="p-4 rounded-2xl bg-white/[0.04] border border-white/10 backdrop-blur-sm flex items-start gap-3">
                        <div class="w-8 h-8 rounded-xl bg-[#FF6B00]/20 text-[#FF8A3D] flex items-center justify-center font-bold text-xs font-montserrat shrink-0">
                            2
                        </div>
                        <div>
                            <h4 class="font-montserrat font-bold text-xs text-white mb-0.5">Katalog & Silabus</h4>
                            <p class="font-quicksand text-[11px] text-slate-400 leading-relaxed">Pilih kelas offline/online dengan materi terstruktur.</p>
                        </div>
                    </div>

                    <div class="p-4 rounded-2xl bg-white/[0.04] border border-white/10 backdrop-blur-sm flex items-start gap-3">
                        <div class="w-8 h-8 rounded-xl bg-amber-500/20 text-amber-300 flex items-center justify-center font-bold text-xs font-montserrat shrink-0">
                            3
                        </div>
                        <div>
                            <h4 class="font-montserrat font-bold text-xs text-white mb-0.5">Penuhi 20 JP & Kuis</h4>
                            <p class="font-quicksand text-[11px] text-slate-400 leading-relaxed">Akumulasi 900 menit belajar dan capai batas nilai kuis.</p>
                        </div>
                    </div>

                    <div class="p-4 rounded-2xl bg-white/[0.04] border border-white/10 backdrop-blur-sm flex items-start gap-3">
                        <div class="w-8 h-8 rounded-xl bg-indigo-500/20 text-indigo-300 flex items-center justify-center font-bold text-xs font-montserrat shrink-0">
                            4
                        </div>
                        <div>
                            <h4 class="font-montserrat font-bold text-xs text-white mb-0.5">E-Sertifikat Sah</h4>
                            <p class="font-quicksand text-[11px] text-slate-400 leading-relaxed">Unduh sertifikat PDF resmi dengan validasi QR publik.</p>
                        </div>
                    </div>
                </div>

                <!-- Testimonial Quote -->
                <div class="p-4 rounded-2xl bg-gradient-to-r from-white/[0.06] to-transparent border-l-4 border-[#10B981] border-y border-r border-white/10 flex items-center gap-3.5">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-[#10B981] to-[#3B82F6] text-white flex items-center justify-center font-bold text-xs font-montserrat shrink-0">
                        DP
                    </div>
                    <div>
                        <p class="font-quicksand text-xs text-slate-200 italic leading-relaxed">
                            "Proses pendaftaran mudah, materi tatap muka Zoom tepat waktu, dan e-sertifikatnya langsung terverifikasi dengan QR code."
                        </p>
                        <div class="text-[11px] font-montserrat font-bold text-slate-400 mt-1">
                            Dimas Pratama &bull; <span class="text-slate-500 font-normal">Alumni Pelatihan Kejuruan</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bottom Security Badges -->
            <div class="relative z-10 pt-6 border-t border-white/10 flex flex-wrap items-center justify-between gap-4 text-xs font-quicksand text-slate-400">
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                    <span>Pendaftaran Mandiri Peserta Terlindungi</span>
                </div>
                <div class="flex items-center gap-4 text-slate-500 text-[11px]">
                    <span>Standar Mutu Kejuruan</span>
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
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-bold font-montserrat bg-[#ECFDF5] text-[#059669] border border-[#A7F3D0]">
                        <svg class="w-3.5 h-3.5 text-[#10B981]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/></svg>
                        <span>Khusus Pendaftaran Akun Peserta</span>
                    </div>
                    <h2 class="font-montserrat font-extrabold text-2xl sm:text-3xl text-[#1E1B18] tracking-tight">
                        Buat Akun Peserta Baru
                    </h2>
                    <p class="font-quicksand text-xs sm:text-sm text-[#6E675F]">
                        Daftarkan diri Anda untuk mulai mengikuti kelas pelatihan dan memantau target 20 JP kelulusan.
                    </p>
                </div>

                <!-- Info Alert -->
                <div class="p-3.5 bg-[#FFF7ED] rounded-2xl border border-[#FED7AA] flex items-start gap-3 text-xs text-[#9A3412]">
                    <svg class="w-4 h-4 text-[#FF6B00] shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="font-quicksand leading-relaxed">
                        <strong>Catatan Akses:</strong> Akun Trainer dan Admin Cabang didaftarkan secara terpusat oleh instansi dan tidak melalui formulir publik ini.
                    </p>
                </div>

                <!-- Register Form -->
                <form method="POST" action="{{ route('register') }}" class="space-y-4">
                    @csrf

                    <!-- Name -->
                    <div>
                        <label for="name" class="block font-montserrat font-bold text-xs uppercase tracking-wider text-[#1E1B18] mb-1.5">
                            Nama Lengkap <span class="text-[#DC2626]">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-[#6E675F]">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            </div>
                            <input id="name"
                                   type="text"
                                   name="name"
                                   value="{{ old('name') }}"
                                   required
                                   autofocus
                                   placeholder="Contoh: Dimas Pratama"
                                   class="w-full pl-10 pr-4 py-3 rounded-xl border @error('name') border-red-500 @else border-[#EBE5DF] @enderror bg-[#FAF8F5] text-sm text-[#1E1B18] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#FF6B00] focus:border-transparent font-quicksand transition">
                        </div>
                        @error('name')
                            <p class="mt-1.5 text-xs text-[#DC2626] font-semibold flex items-center gap-1 font-quicksand">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <span>{{ $message }}</span>
                            </p>
                        @enderror
                    </div>

                    <!-- Email Address -->
                    <div>
                        <label for="email" class="block font-montserrat font-bold text-xs uppercase tracking-wider text-[#1E1B18] mb-1.5">
                            Alamat Email <span class="text-[#DC2626]">*</span>
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

                    <!-- Password and Confirmation (2 Columns on Desktop) -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                        <!-- Password -->
                        <div>
                            <label for="password" class="block font-montserrat font-bold text-xs uppercase tracking-wider text-[#1E1B18] mb-1.5">
                                Kata Sandi <span class="text-[#DC2626]">*</span>
                            </label>
                            <div class="relative">
                                <input id="password"
                                       type="password"
                                       name="password"
                                       required
                                       autocomplete="new-password"
                                       placeholder="Min. 8 karakter"
                                       class="w-full px-3.5 py-3 rounded-xl border @error('password') border-red-500 @else border-[#EBE5DF] @enderror bg-[#FAF8F5] text-sm text-[#1E1B18] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#FF6B00] focus:border-transparent font-quicksand transition">
                            </div>
                            @error('password')
                                <p class="mt-1.5 text-xs text-[#DC2626] font-semibold font-quicksand">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Confirm Password -->
                        <div>
                            <label for="password_confirmation" class="block font-montserrat font-bold text-xs uppercase tracking-wider text-[#1E1B18] mb-1.5">
                                Konfirmasi Sandi <span class="text-[#DC2626]">*</span>
                            </label>
                            <div class="relative">
                                <input id="password_confirmation"
                                       type="password"
                                       name="password_confirmation"
                                       required
                                       autocomplete="new-password"
                                       placeholder="Ulangi sandi"
                                       class="w-full px-3.5 py-3 rounded-xl border @error('password_confirmation') border-red-500 @else border-[#EBE5DF] @enderror bg-[#FAF8F5] text-sm text-[#1E1B18] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#FF6B00] focus:border-transparent font-quicksand transition">
                            </div>
                            @error('password_confirmation')
                                <p class="mt-1.5 text-xs text-[#DC2626] font-semibold font-quicksand">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-2">
                        <button type="submit"
                                class="w-full py-3.5 px-5 rounded-xl font-montserrat font-bold text-sm text-white bg-gradient-to-r from-[#FF6B00] via-[#EA580C] to-[#E11D48] hover:from-[#EA580C] hover:to-[#DC2626] shadow-lg shadow-[#FF6B00]/25 hover:shadow-xl hover:shadow-[#FF6B00]/35 transition-all duration-200 transform hover:-translate-y-0.5 flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            <span>Kirim Kode OTP Verifikasi</span>
                        </button>
                        <p class="text-[11px] text-center text-[#6E675F] mt-2.5 font-quicksand">
                            Kode verifikasi 6 digit akan dikirimkan ke alamat email di atas untuk mengaktifkan akun.
                        </p>
                    </div>
                </form>

                <!-- Login Link -->
                <div class="pt-3 text-center border-t border-[#EBE5DF]">
                    <p class="font-quicksand text-xs sm:text-sm text-[#6E675F]">
                        Sudah memiliki akun terdaftar?
                        <a href="{{ route('login') }}" class="font-montserrat font-bold text-[#FF6B00] hover:text-[#EA580C] hover:underline transition">
                            Masuk ke Akun Anda &rarr;
                        </a>
                    </p>
                </div>

            </div>
        </div>

    </div>
</x-guest-layout>
