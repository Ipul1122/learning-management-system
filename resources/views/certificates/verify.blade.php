<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Verifikasi Keaslian Sertifikat - {{ $submission->certificate_number }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Montserrat:wght@600;700;800;900&family=JetBrains+Mono:wght@400;500;700&display=swap" rel="stylesheet">

    <!-- Tailwind CSS (via Vite) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#FAF8F5] text-[#1E1B18] font-sans antialiased min-h-screen flex flex-col justify-between selection:bg-[#FF6B00] selection:text-white">
    <!-- Navbar Minimalis Publik -->
    <header class="border-b border-[#EBE5DF] bg-white/95 backdrop-blur-md sticky top-0 z-50">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <a href="{{ url('/') }}" class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-lg bg-gradient-to-r from-[#FF6B00] to-[#E11D48] flex items-center justify-center text-white font-montserrat font-extrabold text-sm shadow-sm">
                    LMS
                </div>
                <span class="font-montserrat font-extrabold text-lg tracking-tight text-[#1E1B18]">
                    Verifikasi<span class="text-[#FF6B00]">Sertifikat</span>
                </span>
            </a>
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-mono font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 shadow-xs">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    Sistem Otentikasi Online
                </span>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-grow py-10 px-4 sm:px-6 lg:px-8 flex items-center justify-center">
        <div class="w-full max-w-3xl space-y-6">
            <!-- Verified Header Card -->
            <div class="bg-white border border-[#EBE5DF] rounded-3xl p-6 sm:p-8 shadow-sm relative overflow-hidden text-center space-y-4">
                <div class="absolute -top-12 -right-12 w-48 h-48 bg-emerald-500/10 rounded-full blur-3xl pointer-events-none"></div>
                <div class="absolute -bottom-12 -left-12 w-48 h-48 bg-orange-500/5 rounded-full blur-3xl pointer-events-none"></div>

                <!-- Green Badge Icon -->
                <div class="w-16 h-16 rounded-2xl bg-emerald-50 border-2 border-emerald-200 text-emerald-600 mx-auto flex items-center justify-center shadow-sm relative z-10">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                </div>

                <div class="relative z-10 space-y-1">
                    <span class="text-xs font-bold uppercase tracking-widest text-emerald-700 font-montserrat">
                        DOKUMEN ASLI &bull; RESMI TERVERIFIKASI
                    </span>
                    <h1 class="text-2xl sm:text-3xl font-montserrat font-extrabold text-[#1E1B18]">
                        Sertifikat Pelatihan Kejuruan Valid
                    </h1>
                    <p class="text-xs sm:text-sm text-[#6E675F] max-w-xl mx-auto font-quicksand">
                        Dokumen digital ini diterbitkan secara sah oleh Lembaga Manajemen Pembelajaran (LMS) dan tercatat dalam pangkalan data verifikasi resmi.
                    </p>
                </div>

                <!-- Certificate Number Box -->
                <div class="inline-block bg-[#FAF8F5] border border-[#EBE5DF] rounded-xl px-5 py-2.5 font-mono text-sm sm:text-base text-[#EA580C] font-extrabold tracking-wider relative z-10 shadow-xs">
                    {{ $submission->certificate_number }}
                </div>
            </div>

            <!-- Details Sheet -->
            <div class="bg-white border border-[#EBE5DF] rounded-2xl p-6 sm:p-8 space-y-5 shadow-sm">
                <h2 class="text-xs font-bold text-[#A8A29E] uppercase tracking-wider font-montserrat border-b border-[#EBE5DF] pb-3">
                    Rincian Pemilik & Program Pelatihan
                </h2>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs sm:text-sm">
                    <div>
                        <span class="text-[#6E675F] block mb-0.5">Nama Peserta Penerima:</span>
                        <span class="font-bold text-[#1E1B18] text-base font-montserrat">{{ $student->name }}</span>
                    </div>

                    <div>
                        <span class="text-[#6E675F] block mb-0.5">Program Pelatihan:</span>
                        <span class="font-bold text-[#1E1B18] font-montserrat">{{ $class->title }}</span>
                    </div>

                    <div>
                        <span class="text-[#6E675F] block mb-0.5">Beban Studi Kumulatif:</span>
                        <span class="font-bold text-emerald-600 font-mono">{{ $submission->total_jp_earned }} Jam Pelajaran (JP) / 900 Menit</span>
                    </div>

                    <div>
                        <span class="text-[#6E675F] block mb-0.5">Lembaga / Unit Cabang:</span>
                        <span class="font-semibold text-[#1E1B18]">{{ $branch->name ?? 'Kantor Pusat' }} ({{ $branch->code ?? 'LMS' }})</span>
                    </div>

                    <div>
                        <span class="text-[#6E675F] block mb-0.5">Instruktur Pengampu:</span>
                        <span class="font-semibold text-[#1E1B18]">{{ $trainer->name }}</span>
                    </div>

                    <div>
                        <span class="text-[#6E675F] block mb-0.5">Tanggal Penerbitan Resmi:</span>
                        <span class="font-mono text-[#1E1B18]">
                            {{ $submission->reviewed_at ? \Carbon\Carbon::parse($submission->reviewed_at)->translatedFormat('d F Y H:i') . ' WIB' : '-' }}
                        </span>
                    </div>
                </div>

                <!-- Hash / Security Validation Info -->
                <div class="mt-6 pt-5 border-t border-[#EBE5DF] flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs text-[#6E675F] font-mono">
                    <div>
                        Kode Validasi: <span class="text-[#EA580C] font-bold">{{ $submission->qr_verification_code ?? substr(md5($submission->certificate_number), 0, 16) }}</span>
                    </div>
                    <div class="flex items-center gap-1.5 text-emerald-700 font-medium">
                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span>Status: Aktif & Berlaku Tanpa Batas Waktu</span>
                    </div>
                </div>
            </div>

            <!-- Footer Action -->
            <div class="text-center pt-2">
                <a href="{{ url('/') }}" class="text-xs font-bold text-[#6E675F] hover:text-[#1E1B18] transition inline-flex items-center gap-1 font-montserrat">
                    &larr; Beranda Portal Pembelajaran
                </a>
            </div>
        </div>
    </main>

    <!-- Footer Copyright -->
    <footer class="border-t border-[#EBE5DF] bg-white py-4 text-center text-xs text-[#A8A29E] font-mono">
        &copy; {{ date('Y') }} Sistem Manajemen Mutu Pelatihan LMS Multi-Cabang. Dilindungi Hak Cipta.
    </footer>
</body>
</html>
