<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold font-montserrat bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 mb-1">
                    <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                    Peserta Pelatihan • {{ $user->name }}
                </span>
                <h2 class="font-montserrat font-extrabold text-2xl text-white leading-tight">
                    Dashboard Belajar Saya
                </h2>
            </div>
            <div class="flex items-center gap-2 text-xs text-slate-400">
                <span>Target Kelulusan:</span>
                <span class="font-bold text-orange-400 bg-orange-500/10 px-2.5 py-1 rounded-lg border border-orange-500/20 font-mono">
                    20 JP (900 Menit)
                </span>
            </div>
        </div>
    </x-slot>

    <div class="py-6 space-y-6">
        @php
            $percentage = min(100, round(($totalMinutes / 900) * 100, 1));
            $isCompleted = $totalJp >= 20.0;
        @endphp

        <!-- 20 JP Visual Progress Widget Sesuai PRD 4.6 & desain.md 5.2 -->
        <div class="bg-gradient-to-br from-slate-900 via-slate-900 to-slate-950 rounded-3xl p-6 sm:p-8 text-white shadow-xl relative overflow-hidden border border-slate-800">
            <div class="absolute -right-10 -bottom-10 w-56 h-56 {{ $isCompleted ? 'bg-emerald-500/20' : 'bg-orange-500/15' }} rounded-full blur-3xl pointer-events-none"></div>

            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-4 relative z-10">
                <div>
                    <span class="text-xs font-bold tracking-wider uppercase {{ $isCompleted ? 'text-emerald-400' : 'text-orange-400' }} font-montserrat">
                        {{ $isCompleted ? 'Target Kelulusan Terpenuhi' : 'Syarat Kelulusan Pelatihan' }}
                    </span>
                    <h2 class="text-xl sm:text-2xl font-montserrat font-extrabold text-white">Akumulasi Jam Pelajaran (JP)</h2>
                    <p class="text-xs text-slate-400 mt-1">1 JP = 45 Menit • Minimal 20 JP untuk diverifikasi Trainer & diterbitkan sertifikat</p>
                </div>
                <div class="text-left sm:text-right">
                    <span class="text-3xl sm:text-4xl font-extrabold font-montserrat text-transparent bg-clip-text bg-gradient-to-r {{ $isCompleted ? 'from-emerald-400 to-teal-300' : 'from-orange-400 to-amber-300' }}">
                        {{ $totalJp }} <span class="text-lg text-slate-400">/ 20.0 JP</span>
                    </span>
                    <p class="text-xs text-slate-400 font-mono mt-0.5">{{ $totalMinutes }} dari 900 Menit Tuntas ({{ $percentage }}%)</p>
                </div>
            </div>

            <!-- Progress Track Bar -->
            <div class="w-full bg-slate-800 h-3.5 rounded-full p-0.5 mb-3 relative z-10 border border-slate-700/60 overflow-hidden">
                <div class="h-full rounded-full transition-all duration-700 shadow-sm bg-gradient-to-r {{ $isCompleted ? 'from-emerald-500 via-teal-400 to-cyan-400' : 'from-orange-500 via-amber-400 to-rose-400' }}"
                     style="width: {{ $percentage }}%"></div>
            </div>

            <!-- Status Box -->
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-2 text-xs text-slate-300 pt-2 border-t border-slate-800 relative z-10">
                <span class="flex items-center gap-1.5 font-medium">
                    @if($isCompleted)
                        <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span class="text-emerald-300">Akumulasi 20 JP selesai! Pastikan paket kuis kelas juga telah diselesaikan.</span>
                    @elseif($enrolledClassesCount === 0)
                        <span class="w-2 h-2 rounded-full bg-orange-400 animate-pulse"></span>
                        Silakan pilih kelas pelatihan terlebih dahulu di Katalog untuk memulai pengumpulan JP.
                    @else
                        <span class="w-2 h-2 rounded-full bg-orange-400 animate-pulse"></span>
                        Ikuti sesi Zoom atau kelas tatap muka untuk menambah {{ max(0, round(20.0 - $totalJp, 1)) }} JP lagi.
                    @endif
                </span>
                <a href="{{ route('peserta.study.index') }}" class="text-orange-400 hover:text-orange-300 font-semibold flex items-center gap-1">
                    Detail Progres Kelas &rarr;
                </a>
            </div>
        </div>

        <!-- Quick Access Nav Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
            <!-- 4.2 Katalog Kelas -->
            <a href="{{ route('peserta.catalog.index') }}" class="bg-slate-900/60 border border-slate-800 hover:border-slate-700 rounded-2xl p-5 shadow-sm flex flex-col justify-between group transition">
                <div>
                    <div class="w-10 h-10 rounded-xl bg-orange-500/10 text-orange-400 border border-orange-500/20 flex items-center justify-center mb-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                    </div>
                    <h4 class="font-montserrat font-bold text-base text-white group-hover:text-orange-400 transition">Katalog Kelas Pelatihan</h4>
                    <p class="text-xs text-slate-400 mt-1">Pilih kelas Offline (maks 40 kursi), Online & Hybrid dengan kuota realtime.</p>
                </div>
                <span class="inline-block mt-4 text-xs font-bold text-orange-400 font-montserrat flex items-center gap-1">
                    Jelajahi Katalog &rarr;
                </span>
            </a>

            <!-- Ruang Belajar & Zoom -->
            <a href="{{ route('peserta.study.index') }}" class="bg-slate-900/60 border border-slate-800 hover:border-slate-700 rounded-2xl p-5 shadow-sm flex flex-col justify-between group transition">
                <div>
                    <div class="w-10 h-10 rounded-xl bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 flex items-center justify-center mb-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    </div>
                    <h4 class="font-montserrat font-bold text-base text-white group-hover:text-emerald-400 transition">Ruang Belajar Saya</h4>
                    <p class="text-xs text-slate-400 mt-1">Akses silabus pertemuan, 1-klik masuk Zoom, dan kerjakan kuis evaluasi.</p>
                </div>
                <span class="inline-block mt-4 text-xs font-bold text-emerald-400 font-montserrat flex items-center gap-1">
                    Buka Ruang Belajar ({{ $enrolledClassesCount }} Kelas) &rarr;
                </span>
            </a>

            <!-- Sertifikat Kelulusan -->
            <div class="bg-slate-900/40 border border-slate-800/60 rounded-2xl p-5 shadow-sm flex flex-col justify-between opacity-80">
                <div>
                    <div class="w-10 h-10 rounded-xl bg-purple-500/10 text-purple-400 border border-purple-500/20 flex items-center justify-center mb-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <h4 class="font-montserrat font-bold text-base text-white">E-Sertifikat Kelulusan</h4>
                    <p class="text-xs text-slate-400 mt-1">Sertifikat otomatis diterbitkan setelah verifikasi trainer selesai (Fase 5).</p>
                </div>
                <span class="inline-block mt-4 text-xs font-semibold text-slate-500 font-montserrat">
                    Tersedia di Fase 5
                </span>
            </div>
        </div>

        <!-- Sesi Pertemuan Mendatang (Live Zoom) -->
        <div class="bg-slate-900/60 border border-slate-800/80 rounded-2xl p-6 space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-base font-extrabold text-white font-montserrat flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                        </svg>
                        Jadwal Sesi Mendatang & Live Zoom
                    </h3>
                    <p class="text-xs text-slate-400 mt-0.5">Bergabung ke ruang tatap muka online dengan 1-klik untuk otomatis mencatat presensi.</p>
                </div>
            </div>

            @if($upcomingSessions->isEmpty())
                <div class="p-8 rounded-xl bg-slate-800/40 border border-slate-800 text-center text-xs text-slate-400">
                    Tidak ada jadwal sesi tatap muka online mendatang dari kelas yang Anda ikuti saat ini.
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($upcomingSessions as $session)
                        <div class="p-4 rounded-xl bg-slate-800/40 border border-slate-700/60 flex flex-col justify-between gap-3">
                            <div>
                                <div class="flex items-center justify-between text-[10px] text-slate-400 font-mono mb-1">
                                    <span>{{ $session->trainingClass->title }}</span>
                                    <span class="text-emerald-400 font-bold">#Sesi {{ $session->session_order }}</span>
                                </div>
                                <h4 class="text-sm font-bold text-white font-montserrat">{{ $session->title }}</h4>
                                <div class="text-xs text-slate-400 font-mono mt-1">
                                    {{ $session->session_date ? \Carbon\Carbon::parse($session->session_date)->translatedFormat('d M Y') : 'Jadwal Fleksibel' }}
                                    @if($session->start_time)
                                        • {{ substr($session->start_time, 0, 5) }} WIB
                                    @endif
                                    • {{ $session->minute_duration }} Menit
                                </div>
                            </div>

                            <div class="pt-2 border-t border-slate-700/60 flex items-center justify-between gap-2">
                                <span class="text-[11px] text-slate-400 font-mono">Bobot: {{ round($session->minute_duration / 45, 1) }} JP</span>
                                @if(!empty($session->zoom_url))
                                    <form method="POST" action="{{ route('peserta.study.zoom', [$session->trainingClass, $session]) }}" target="_blank">
                                        @csrf
                                        <button type="submit" class="px-3 py-1.5 text-xs font-bold text-white bg-blue-600 hover:bg-blue-500 rounded-lg transition flex items-center gap-1.5 font-montserrat">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                            <span>Masuk Zoom</span>
                                        </button>
                                    </form>
                                @else
                                    <a href="{{ route('peserta.study.show', $session->trainingClass) }}" class="text-xs text-slate-400 hover:text-white font-semibold">
                                        Lihat Silabus &rarr;
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
