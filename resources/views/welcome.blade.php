<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'LMS Multi-Cabang') }} - Sistem Manajemen Pelatihan Terpadu</title>

        <!-- Fonts (Montserrat & Quicksand) -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,500;0,600;0,700;0,800;1,700&family=Quicksand:wght@400;500;600;700&display=swap" rel="stylesheet">

        <!-- Styles / Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-quicksand antialiased text-[#1E1B18] bg-[#FAF8F5] min-h-screen flex flex-col justify-between selection:bg-[#FF6B00] selection:text-white">

        <!-- Navbar -->
        <header class="bg-white/90 backdrop-blur-md border-b border-[#EBE5DF] sticky top-0 z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
                <!-- Logo -->
                <a href="/" class="flex items-center gap-3">
                    <div class="w-11 h-11 rounded-2xl bg-gradient-to-tr from-[#FF6B00] to-[#E11D48] flex items-center justify-center text-white shadow-md font-montserrat font-extrabold text-xl">
                        L
                    </div>
                    <div>
                        <span class="font-montserrat font-extrabold text-lg tracking-tight text-[#1E1B18]">
                            LMS <span class="text-[#FF6B00]">Multi-Cabang</span>
                        </span>
                        <p class="text-[11px] text-[#6E675F] font-semibold hidden sm:block">Sistem Manajemen Pelatihan Terpadu</p>
                    </div>
                </a>

                <!-- Nav Actions -->
                <div class="flex items-center gap-3">
                    @auth
                        <a href="{{ route('dashboard') }}"
                           class="px-5 py-2.5 rounded-xl font-montserrat font-bold text-sm text-white bg-gradient-to-r from-[#FF6B00] to-[#E11D48] hover:from-[#EA580C] hover:to-[#DC2626] shadow-md hover:shadow-lg transition transform hover:-translate-y-0.5">
                            Buka Dashboard &rarr;
                        </a>
                    @else
                        <a href="{{ route('login') }}"
                           class="px-5 py-2.5 rounded-xl font-montserrat font-bold text-sm text-[#1E1B18] hover:text-[#FF6B00] hover:bg-[#FAF8F5] border border-transparent hover:border-[#EBE5DF] transition">
                            Masuk
                        </a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}"
                               class="px-5 py-2.5 rounded-xl font-montserrat font-bold text-sm text-white bg-gradient-to-r from-[#FF6B00] to-[#E11D48] hover:from-[#EA580C] hover:to-[#DC2626] shadow-md hover:shadow-lg transition transform hover:-translate-y-0.5">
                                Daftar Peserta
                            </a>
                        @endif
                    @endauth
                </div>
            </div>
        </header>

        <!-- Hero Section -->
        <main class="py-12 sm:py-20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center max-w-3xl mx-auto space-y-6">
                    <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full text-xs font-bold font-montserrat bg-[#FFF7ED] text-[#EA580C] border border-[#FED7AA] shadow-xs">
                        <span class="w-2 h-2 rounded-full bg-[#EA580C] animate-pulse"></span>
                        Platform Pelatihan Standar 20 JP Multi-Cabang
                    </div>

                    <h1 class="font-montserrat font-extrabold text-3xl sm:text-5xl lg:text-6xl text-[#1E1B18] tracking-tight leading-tight">
                        Pusat Pembelajaran Terpadu <span class="bg-gradient-to-r from-[#FF6B00] to-[#E11D48] bg-clip-text text-transparent">Multi-Wilayah</span>
                    </h1>

                    <p class="font-quicksand text-base sm:text-lg text-[#6E675F] leading-relaxed">
                        Satu gerbang akses terpusat untuk <strong>Super Admin</strong>, <strong>Admin Cabang</strong>, <strong>Instruktur/Trainer</strong>, dan <strong>Peserta Pelatihan</strong>. Dilengkapi kuota kelas fisik ketat 40 orang, integrasi Zoom instan, dan pelacakan jam pelajaran 20 JP.
                    </p>

                    <div class="flex flex-col sm:flex-row items-center justify-center gap-4 pt-4">
                        <a href="{{ route('login') }}"
                           class="w-full sm:w-auto px-8 py-3.5 rounded-2xl font-montserrat font-bold text-base text-white bg-gradient-to-r from-[#FF6B00] to-[#E11D48] hover:from-[#EA580C] hover:to-[#DC2626] shadow-lg hover:shadow-xl transition-all duration-200 transform hover:-translate-y-0.5">
                            Masuk ke Portal &rarr;
                        </a>
                        <a href="{{ route('register') }}"
                           class="w-full sm:w-auto px-8 py-3.5 rounded-2xl font-montserrat font-bold text-base text-[#1E1B18] bg-white border border-[#EBE5DF] hover:border-[#FF6B00] hover:text-[#FF6B00] shadow-sm hover:shadow-md transition">
                            Daftar Akun Peserta
                        </a>
                    </div>
                </div>

                <!-- 4 Pillars Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mt-16 sm:mt-24">
                    <!-- Super Admin -->
                    <div class="bg-white rounded-3xl p-6 border border-[#EBE5DF] shadow-sm hover:shadow-md transition">
                        <div class="w-12 h-12 rounded-2xl bg-[#FFF7ED] text-[#EA580C] flex items-center justify-center mb-4">
                            <span class="text-2xl">👑</span>
                        </div>
                        <h3 class="font-montserrat font-bold text-lg text-[#1E1B18]">Super Administrator</h3>
                        <p class="font-quicksand text-xs text-[#6E675F] mt-2 leading-relaxed">
                            Master data seluruh kantor cabang, pembuatan akun Admin Cabang, dan audit trail mutasi data global real-time.
                        </p>
                    </div>

                    <!-- Admin Cabang -->
                    <div class="bg-white rounded-3xl p-6 border border-[#EBE5DF] shadow-sm hover:shadow-md transition">
                        <div class="w-12 h-12 rounded-2xl bg-[#FEF2F2] text-[#DC2626] flex items-center justify-center mb-4">
                            <span class="text-2xl">🏢</span>
                        </div>
                        <h3 class="font-montserrat font-bold text-lg text-[#1E1B18]">Admin Cabang</h3>
                        <p class="font-quicksand text-xs text-[#6E675F] mt-2 leading-relaxed">
                            Manajemen operasional cabang, pembuatan akun trainer, pembuatan kelas (kuota 40 fisik / ratusan online), dan jadwal sesi Zoom.
                        </p>
                    </div>

                    <!-- Trainer -->
                    <div class="bg-white rounded-3xl p-6 border border-[#EBE5DF] shadow-sm hover:shadow-md transition">
                        <div class="w-12 h-12 rounded-2xl bg-[#FFF7ED] text-[#FF6B00] flex items-center justify-center mb-4">
                            <span class="text-2xl">👨‍🏫</span>
                        </div>
                        <h3 class="font-montserrat font-bold text-lg text-[#1E1B18]">Trainer / Instruktur</h3>
                        <p class="font-quicksand text-xs text-[#6E675F] mt-2 leading-relaxed">
                            Penyusunan bank soal dengan bobot setara, kuis evaluasi, manajemen tautan Zoom live, dan persetujuan verifikasi kelulusan.
                        </p>
                    </div>

                    <!-- Peserta -->
                    <div class="bg-white rounded-3xl p-6 border border-[#EBE5DF] shadow-sm hover:shadow-md transition">
                        <div class="w-12 h-12 rounded-2xl bg-[#ECFDF5] text-[#10B981] flex items-center justify-center mb-4">
                            <span class="text-2xl">🎓</span>
                        </div>
                        <h3 class="font-montserrat font-bold text-lg text-[#1E1B18]">Peserta Pelatihan</h3>
                        <p class="font-quicksand text-xs text-[#6E675F] mt-2 leading-relaxed">
                            Pendaftaran mandiri, pemilihan kelas online/offline, 1-click ruang Zoom, tracking 20 JP (900 menit), dan sertifikat digital QR code.
                        </p>
                    </div>
                </div>

                <!-- Testing Demo Credentials Banner -->
                <div class="mt-16 bg-white rounded-3xl p-6 sm:p-8 border border-[#EBE5DF] shadow-sm">
                    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
                        <div>
                            <div class="flex items-center gap-2 mb-1">
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-bold font-montserrat bg-[#FFF7ED] text-[#FF6B00]">
                                    Akses Pengujian Cepat
                                </span>
                                <span class="text-xs text-[#6E675F] font-mono">Password: password</span>
                            </div>
                            <h3 class="font-montserrat font-bold text-xl text-[#1E1B18]">Coba Langsung Seluruh Peran Pengguna</h3>
                            <p class="font-quicksand text-xs text-[#6E675F] mt-1">
                                Klik tombol di bawah atau gunakan akun demo pada halaman login untuk melihat dashboard dan hak akses masing-masing peran.
                            </p>
                        </div>

                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                            <a href="{{ route('login') }}" class="p-3 rounded-2xl bg-[#FAF8F5] hover:bg-[#FFF7ED] border border-[#EBE5DF] hover:border-[#FF6B00] transition text-center">
                                <div class="font-montserrat font-bold text-xs text-[#1E1B18]">Super Admin</div>
                                <div class="text-[10px] text-[#6E675F] font-mono truncate">superadmin@...</div>
                            </a>
                            <a href="{{ route('login') }}" class="p-3 rounded-2xl bg-[#FAF8F5] hover:bg-[#FEF2F2] border border-[#EBE5DF] hover:border-[#DC2626] transition text-center">
                                <div class="font-montserrat font-bold text-xs text-[#1E1B18]">Admin Cabang</div>
                                <div class="text-[10px] text-[#6E675F] font-mono truncate">admin.jkt@...</div>
                            </a>
                            <a href="{{ route('login') }}" class="p-3 rounded-2xl bg-[#FAF8F5] hover:bg-[#FFF7ED] border border-[#EBE5DF] hover:border-[#FF6B00] transition text-center">
                                <div class="font-montserrat font-bold text-xs text-[#1E1B18]">Trainer</div>
                                <div class="text-[10px] text-[#6E675F] font-mono truncate">trainer1@...</div>
                            </a>
                            <a href="{{ route('login') }}" class="p-3 rounded-2xl bg-[#FAF8F5] hover:bg-[#ECFDF5] border border-[#EBE5DF] hover:border-[#10B981] transition text-center">
                                <div class="font-montserrat font-bold text-xs text-[#1E1B18]">Peserta</div>
                                <div class="text-[10px] text-[#6E675F] font-mono truncate">peserta1@...</div>
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </main>

        <!-- Footer -->
        <footer class="bg-white border-t border-[#EBE5DF] py-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-[#6E675F]">
                <div>
                    &copy; {{ date('Y') }} LMS Terpadu Multi-Cabang. Semua hak dilindungi.
                </div>
                <div class="flex items-center gap-6">
                    <span class="hover:text-[#FF6B00] transition">Standar 20 JP = 900 Menit</span>
                    <span>•</span>
                    <span class="hover:text-[#FF6B00] transition">Kapasitas Fisik Maks 40 Orang</span>
                </div>
            </div>
        </footer>

    </body>
</html>
