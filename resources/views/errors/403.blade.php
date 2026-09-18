<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>403 - Akses Ditolak | {{ config('app.name', 'LMS Multi-Cabang') }}</title>

    <!-- Google Fonts: Montserrat & Quicksand -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,500;0,600;0,700;0,800;1,700&family=Quicksand:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full bg-[#090D16] text-[#FAF8F5] font-quicksand antialiased flex flex-col justify-between overflow-x-hidden selection:bg-[#E11D48] selection:text-white">

    <!-- Ambient Background Lighting & Effects -->
    <div class="fixed inset-0 pointer-events-none overflow-hidden z-0">
        <!-- Glowing Orbs -->
        <div class="absolute -top-40 left-1/2 -translate-x-1/2 w-[700px] h-[500px] bg-gradient-to-b from-[#E11D48]/20 via-[#FF6B00]/15 to-transparent rounded-full blur-[120px]"></div>
        <div class="absolute -bottom-40 right-10 w-[500px] h-[500px] bg-[#6366F1]/10 rounded-full blur-[140px]"></div>
        
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
                <div class="hidden sm:flex items-center gap-2 px-3 py-1.5 rounded-xl bg-white/5 border border-white/10 text-xs text-slate-300">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    <span>Masuk sebagai: <strong class="text-white font-montserrat">{{ Auth::user()->name }}</strong></span>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="px-3.5 py-1.5 rounded-xl text-xs font-bold font-montserrat text-slate-300 hover:text-white bg-white/5 hover:bg-white/10 border border-white/10 transition">
                        Keluar
                    </button>
                </form>
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

            <!-- Security Badge & Giant 403 Graphic -->
            <div class="relative inline-flex items-center justify-center mb-6">
                <!-- Watermark Background Digits -->
                <div class="text-[120px] sm:text-[160px] font-montserrat font-extrabold tracking-tighter text-white/[0.04] select-none leading-none absolute">
                    403
                </div>

                <!-- Glowing Security Shield Icon -->
                <div class="relative z-10 w-24 h-24 sm:w-28 sm:h-28 rounded-3xl bg-gradient-to-br from-[#271518] via-[#1E1116] to-[#120B10] border-2 border-[#E11D48]/40 shadow-2xl shadow-[#E11D48]/30 flex items-center justify-center group hover:scale-105 transition-transform duration-300">
                    <div class="absolute inset-0 rounded-3xl bg-gradient-to-tr from-[#E11D48]/20 to-transparent pointer-events-none"></div>
                    
                    <!-- Pulsing lock graphic -->
                    <div class="relative flex items-center justify-center">
                        <svg class="w-12 h-12 sm:w-14 sm:h-14 text-[#E11D48] filter drop-shadow-[0_0_12px_rgba(225,29,72,0.6)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                        <span class="absolute -top-1 -right-1 flex h-3.5 w-3.5">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-[#E11D48] opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-3.5 w-3.5 bg-[#E11D48]"></span>
                        </span>
                    </div>
                </div>
            </div>

            <!-- Error Status Pill -->
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-[#E11D48]/15 border border-[#E11D48]/30 text-xs font-bold font-montserrat text-[#FB7185] mb-5 shadow-sm">
                <svg class="w-4 h-4 text-[#E11D48]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                <span>HTTP 403 • AKSES DITOLAK (FORBIDDEN)</span>
            </div>

            <!-- Title & Explanation -->
            <h1 class="font-montserrat font-extrabold text-2xl sm:text-4xl text-white tracking-tight leading-tight mb-4">
                Area Terbatas & <span class="bg-gradient-to-r from-[#E11D48] via-[#FF6B00] to-[#F59E0B] bg-clip-text text-transparent">Izin Tidak Memadai</span>
            </h1>

            <p class="font-quicksand text-sm sm:text-base text-slate-300 max-w-lg mx-auto leading-relaxed mb-6">
                {{ $exception->getMessage() ?: 'Mohon maaf, peran akun Anda tidak memiliki hak akses untuk membuka halaman atau data ini. Kebijakan ini diterapkan demi menjaga kepatuhan dan isolasi data operasional LMS.' }}
            </p>

            <!-- User Context / Diagnostics Card (if authenticated) -->
            @auth
                <div class="bg-white/[0.04] backdrop-blur-md border border-white/10 rounded-2xl p-4 max-w-md mx-auto mb-8 text-left text-xs font-quicksand">
                    <div class="text-slate-400 font-bold uppercase font-montserrat text-[10px] tracking-wider mb-2 flex items-center justify-between">
                        <span>Informasi Sesi Anda</span>
                        <span class="text-emerald-400 font-mono">Aktif</span>
                    </div>
                    <div class="flex items-center justify-between py-1 border-b border-white/5">
                        <span class="text-slate-400">Pengguna</span>
                        <span class="text-white font-bold font-montserrat">{{ Auth::user()->name }}</span>
                    </div>
                    <div class="flex items-center justify-between py-1 border-b border-white/5">
                        <span class="text-slate-400">Peran Akun</span>
                        <span class="px-2 py-0.5 rounded-full bg-white/10 text-white font-mono text-[11px] font-bold capitalize">
                            {{ Auth::user()->roles->first()?->name ?? 'Pengguna' }}
                        </span>
                    </div>
                    @if(Auth::user()->branch)
                        <div class="flex items-center justify-between py-1">
                            <span class="text-slate-400">Cabang Regional</span>
                            <span class="text-[#FF8A3D] font-mono font-bold">{{ Auth::user()->branch->name }}</span>
                        </div>
                    @endif
                </div>
            @endauth

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

            <!-- Help & Guidance -->
            <div class="mt-8 text-center text-xs text-slate-400">
                Merasa ini adalah kekeliruan? Silakan hubungi 
                <a href="mailto:support@lms.go.id" class="text-[#FF8A3D] hover:underline font-semibold">Administrator Lembaga</a> 
                untuk penyesuaian hak akses akun Anda.
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="relative z-10 w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-5 border-t border-white/5 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-slate-400">
        <div>
            &copy; {{ date('Y') }} LMS Multi-Cabang. Seluruh Hak Cipta Dilindungi.
        </div>
        <div class="flex items-center gap-4 text-[11px] font-mono">
            <span class="text-slate-400">Status Keamanan: Terproteksi</span>
            <span class="text-slate-400">•</span>
            <span class="text-[#E11D48] font-bold">Akses Dibatasi</span>
        </div>
    </footer>

</body>
</html>
