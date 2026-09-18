<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>404 - Halaman Tidak Ditemukan | {{ config('app.name', 'LMS Multi-Cabang') }}</title>

    <!-- Google Fonts: Montserrat & Quicksand -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,500;0,600;0,700;0,800;1,700&family=Quicksand:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full bg-[#090D16] text-[#FAF8F5] font-quicksand antialiased flex flex-col justify-between overflow-x-hidden selection:bg-[#FF6B00] selection:text-white">

    <!-- Ambient Background Lighting & Effects -->
    <div class="fixed inset-0 pointer-events-none overflow-hidden z-0">
        <!-- Glowing Orbs -->
        <div class="absolute -top-40 left-1/2 -translate-x-1/2 w-[700px] h-[500px] bg-gradient-to-b from-[#FF6B00]/20 via-[#F59E0B]/15 to-transparent rounded-full blur-[120px]"></div>
        <div class="absolute -bottom-40 left-10 w-[500px] h-[500px] bg-[#6366F1]/10 rounded-full blur-[140px]"></div>
        
        <!-- Subtle Hex Grid Pattern Overlay -->
        <div class="absolute inset-0 bg-[radial-gradient(#ffffff0d_1px,transparent_1px)] [background-size:28px_28px] opacity-60"></div>
    </div>

    <!-- Header Navigation -->
    <header class="relative z-10 w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 flex items-center justify-between">
        <a href="{{ url('/') }}" class="flex items-center gap-3.5 group">
            <div class="w-11 h-11 rounded-2xl bg-gradient-to-tr from-[#FF6B00] via-[#EA580C] to-[#E11D48] flex items-center justify-center text-white shadow-lg shadow-[#FF6B00]/25 font-montserrat font-extrabold text-xl group-hover:scale-105 transition-transform duration-200">
                L
            </div>
            <div>
                <div class="font-montserrat font-extrabold text-lg tracking-tight text-white flex items-center gap-2">
                    LMS <span class="bg-gradient-to-r from-[#FF6B00] to-[#F59E0B] bg-clip-text text-transparent">Multi-Cabang</span>
                </div>
                <div class="text-[11px] font-semibold text-slate-400">
                    Sistem Pelatihan & Sertifikasi Terpadu
                </div>
            </div>
        </a>

        <div class="flex items-center gap-3">
            @auth
                <a href="{{ route('dashboard') }}" class="px-4 py-2 rounded-xl text-xs font-bold font-montserrat text-white bg-white/5 hover:bg-white/10 border border-white/10 transition flex items-center gap-2">
                    <svg class="w-4 h-4 text-[#FF6B00]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    <span>Dashboard</span>
                </a>
            @else
                <a href="{{ route('login') }}" class="px-4 py-2 rounded-xl text-xs font-bold font-montserrat text-white bg-gradient-to-r from-[#FF6B00] to-[#E11D48] hover:opacity-90 shadow-md shadow-[#FF6B00]/20 transition">
                    Masuk Akun
                </a>
            @endauth
        </div>
    </header>

    <!-- Main Hero Content -->
    <main class="relative z-10 flex-1 flex items-center justify-center px-4 sm:px-6 lg:px-8 py-10">
        <div class="w-full max-w-2xl text-center">

            <!-- Radar Graphic & Giant 404 -->
            <div class="relative inline-flex items-center justify-center mb-6">
                <!-- Watermark Background Digits -->
                <div class="text-[120px] sm:text-[160px] font-montserrat font-extrabold tracking-tighter text-white/[0.04] select-none leading-none absolute">
                    404
                </div>

                <!-- Glowing Radar Explorer Icon -->
                <div class="relative z-10 w-24 h-24 sm:w-28 sm:h-28 rounded-3xl bg-gradient-to-br from-[#2D1B10] via-[#1E140C] to-[#120B08] border-2 border-[#FF6B00]/40 shadow-2xl shadow-[#FF6B00]/30 flex items-center justify-center group hover:scale-105 transition-transform duration-300">
                    <div class="absolute inset-0 rounded-3xl bg-gradient-to-tr from-[#FF6B00]/20 to-transparent pointer-events-none"></div>
                    
                    <!-- Compass / Radar Graphic -->
                    <div class="relative flex items-center justify-center">
                        <svg class="w-12 h-12 sm:w-14 sm:h-14 text-[#FF6B00] filter drop-shadow-[0_0_12px_rgba(255,107,0,0.6)] animate-[spin_12s_linear_infinite]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" />
                        </svg>
                        <span class="absolute -top-1 -right-1 flex h-3.5 w-3.5">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-[#FF6B00] opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-3.5 w-3.5 bg-[#FF6B00]"></span>
                        </span>
                    </div>
                </div>
            </div>

            <!-- Error Status Pill -->
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-[#FF6B00]/15 border border-[#FF6B00]/30 text-xs font-bold font-montserrat text-[#FF8A3D] mb-5 shadow-sm">
                <svg class="w-4 h-4 text-[#FF6B00]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <span>HTTP 404 • HALAMAN TIDAK DITEMUKAN (NOT FOUND)</span>
            </div>

            <!-- Title & Explanation -->
            <h1 class="font-montserrat font-extrabold text-2xl sm:text-4xl text-white tracking-tight leading-tight mb-4">
                Oops! Halaman Ini <span class="bg-gradient-to-r from-[#FF6B00] via-[#F59E0B] to-[#E11D48] bg-clip-text text-transparent">Tidak Ditemukan</span>
            </h1>

            <p class="font-quicksand text-sm sm:text-base text-slate-300 max-w-lg mx-auto leading-relaxed mb-8">
                {{ $exception->getMessage() ?: 'Halaman yang Anda tuju mungkin telah dihapus, dipindahkan ke alamat baru, atau terdapat kekeliruan dalam pengetikan tautan URL.' }}
            </p>

            <!-- Quick Navigation Suggestions -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 max-w-xl mx-auto mb-8 text-left">
                @auth
                    <a href="{{ route('dashboard') }}" class="p-3 rounded-2xl bg-white/[0.04] hover:bg-white/[0.08] border border-white/10 hover:border-[#FF6B00]/40 transition group">
                        <div class="text-lg mb-1">🏠</div>
                        <div class="font-montserrat font-bold text-xs text-white group-hover:text-[#FF8A3D] transition">Dashboard</div>
                        <div class="text-[10px] text-slate-400 truncate">Pusat kendali akun</div>
                    </a>

                    <a href="{{ route('peserta.catalog.index') }}" class="p-3 rounded-2xl bg-white/[0.04] hover:bg-white/[0.08] border border-white/10 hover:border-[#FF6B00]/40 transition group">
                        <div class="text-lg mb-1">📚</div>
                        <div class="font-montserrat font-bold text-xs text-white group-hover:text-[#FF8A3D] transition">Katalog</div>
                        <div class="text-[10px] text-slate-400 truncate">Daftar kelas baru</div>
                    </a>

                    <a href="{{ route('peserta.leaderboard') }}" class="p-3 rounded-2xl bg-white/[0.04] hover:bg-white/[0.08] border border-white/10 hover:border-[#FF6B00]/40 transition group">
                        <div class="text-lg mb-1">🏆</div>
                        <div class="font-montserrat font-bold text-xs text-white group-hover:text-[#FF8A3D] transition">Ranking</div>
                        <div class="text-[10px] text-slate-400 truncate">Papan peringkat XP</div>
                    </a>

                    <a href="{{ route('news.index') }}" class="p-3 rounded-2xl bg-white/[0.04] hover:bg-white/[0.08] border border-white/10 hover:border-[#FF6B00]/40 transition group">
                        <div class="text-lg mb-1">📰</div>
                        <div class="font-montserrat font-bold text-xs text-white group-hover:text-[#FF8A3D] transition">Berita</div>
                        <div class="text-[10px] text-slate-400 truncate">Kabar & info terkini</div>
                    </a>
                @else
                    <a href="{{ url('/') }}" class="p-3 rounded-2xl bg-white/[0.04] hover:bg-white/[0.08] border border-white/10 hover:border-[#FF6B00]/40 transition group">
                        <div class="text-lg mb-1">🏠</div>
                        <div class="font-montserrat font-bold text-xs text-white group-hover:text-[#FF8A3D] transition">Beranda</div>
                        <div class="text-[10px] text-slate-400 truncate">Halaman muka utama</div>
                    </a>

                    <a href="{{ route('login') }}" class="p-3 rounded-2xl bg-white/[0.04] hover:bg-white/[0.08] border border-white/10 hover:border-[#FF6B00]/40 transition group">
                        <div class="text-lg mb-1">🔐</div>
                        <div class="font-montserrat font-bold text-xs text-white group-hover:text-[#FF8A3D] transition">Masuk</div>
                        <div class="text-[10px] text-slate-400 truncate">Akses akun Anda</div>
                    </a>

                    <a href="{{ route('register') }}" class="p-3 rounded-2xl bg-white/[0.04] hover:bg-white/[0.08] border border-white/10 hover:border-[#FF6B00]/40 transition group">
                        <div class="text-lg mb-1">✨</div>
                        <div class="font-montserrat font-bold text-xs text-white group-hover:text-[#FF8A3D] transition">Daftar</div>
                        <div class="text-[10px] text-slate-400 truncate">Buat akun peserta</div>
                    </a>

                    <a href="{{ route('news.index') }}" class="p-3 rounded-2xl bg-white/[0.04] hover:bg-white/[0.08] border border-white/10 hover:border-[#FF6B00]/40 transition group">
                        <div class="text-lg mb-1">📰</div>
                        <div class="font-montserrat font-bold text-xs text-white group-hover:text-[#FF8A3D] transition">Berita</div>
                        <div class="text-[10px] text-slate-400 truncate">Informasi pelatihan</div>
                    </a>
                @endauth
            </div>

            <!-- Call-to-Action Buttons -->
            <div class="flex flex-col sm:flex-row items-center justify-center gap-3 sm:gap-4">
                @auth
                    <a href="{{ route('dashboard') }}" class="w-full sm:w-auto px-6 py-3 rounded-xl font-montserrat font-bold text-xs sm:text-sm text-white bg-gradient-to-r from-[#FF6B00] via-[#EA580C] to-[#E11D48] hover:opacity-95 shadow-lg shadow-[#FF6B00]/25 transition flex items-center justify-center gap-2 group">
                        <svg class="w-4 h-4 group-hover:-translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        <span>Ke Dashboard Saya</span>
                    </a>
                @else
                    <a href="{{ url('/') }}" class="w-full sm:w-auto px-6 py-3 rounded-xl font-montserrat font-bold text-xs sm:text-sm text-white bg-gradient-to-r from-[#FF6B00] via-[#EA580C] to-[#E11D48] hover:opacity-95 shadow-lg shadow-[#FF6B00]/25 transition flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        <span>Kembali ke Beranda</span>
                    </a>
                @endauth

                <button onclick="window.history.back()" class="w-full sm:w-auto px-5 py-3 rounded-xl font-montserrat font-bold text-xs sm:text-sm text-slate-300 hover:text-white bg-white/5 hover:bg-white/10 border border-white/10 transition flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    <span>Halaman Sebelumnya</span>
                </button>
            </div>

            <!-- Help Search Suggestion -->
            <div class="mt-8 text-center text-xs text-slate-400">
                Butuh bantuan menemukan pelatihan yang tepat? Silakan hubungi 
                <a href="mailto:info@lms.go.id" class="text-[#FF8A3D] hover:underline font-semibold">Pusat Informasi LMS</a>.
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="relative z-10 w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-5 border-t border-white/5 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-slate-400">
        <div>
            &copy; {{ date('Y') }} LMS Multi-Cabang. Seluruh Hak Cipta Dilindungi.
        </div>
        <div class="flex items-center gap-4 text-[11px] font-mono">
            <span class="text-slate-400">Pusat Navigasi Terpadu</span>
            <span class="text-slate-400">•</span>
            <span class="text-[#FF8A3D] font-bold">Error 404</span>
        </div>
    </footer>

</body>
</html>
