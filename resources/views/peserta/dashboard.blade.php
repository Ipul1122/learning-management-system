<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold font-montserrat bg-[#FFF7ED] text-[#EA580C] border border-[#FED7AA] mb-1">
                    <span class="w-2 h-2 rounded-full bg-[#FF6B00]"></span>
                    Peserta Pelatihan • {{ $user->name }}
                </span>
                <h2 class="font-montserrat font-extrabold text-2xl sm:text-3xl text-[#1E1B18] tracking-tight leading-tight">
                    Dashboard Belajar Saya
                </h2>
            </div>
            <div class="flex items-center gap-2 text-xs font-quicksand text-[#6E675F]">
                <span>Target Kelulusan:</span>
                <span class="font-bold text-[#FF6B00] bg-[#FFF7ED] px-3 py-1 rounded-lg border border-[#FED7AA] font-montserrat">
                    20 JP (900 Menit)
                </span>
            </div>
        </div>
    </x-slot>

    <div class="py-8 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
        @php
            $percentage = min(100, round(($totalMinutes / 900) * 100, 1));
            $isCompleted = $totalJp >= 20.0;
        @endphp

        <!-- 20 JP Visual Progress Widget Sesuai PRD 4.6 & desain.md 5.2 -->
        <div class="bg-gradient-to-br from-[#1E1B18] via-[#2D2723] to-[#1E1B18] rounded-3xl p-6 sm:p-8 text-white shadow-xl relative overflow-hidden border border-[#3E3833]">
            <div class="absolute -right-10 -bottom-10 w-56 h-56 {{ $isCompleted ? 'bg-emerald-500/20' : 'bg-[#FF6B00]/15' }} rounded-full blur-3xl pointer-events-none"></div>

            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-4 relative z-10">
                <div>
                    <span class="text-xs font-bold tracking-wider uppercase {{ $isCompleted ? 'text-emerald-400' : 'text-[#FF6B00]' }} font-montserrat">
                        {{ $isCompleted ? 'Target Kelulusan Terpenuhi' : 'Syarat Kelulusan Pelatihan' }}
                    </span>
                    <h2 class="text-xl sm:text-2xl font-montserrat font-extrabold text-white mt-1">Akumulasi Jam Pelajaran (JP)</h2>
                    <p class="text-xs text-[#FAF8F5]/70 mt-1 font-quicksand">1 JP = 45 Menit • Minimal 20 JP untuk diverifikasi Trainer & diterbitkan sertifikat</p>
                </div>
                <div class="text-left sm:text-right">
                    <span class="text-3xl sm:text-4xl font-extrabold font-montserrat text-transparent bg-clip-text bg-gradient-to-r {{ $isCompleted ? 'from-emerald-400 to-teal-300' : 'from-[#FF6B00] to-amber-300' }}">
                        {{ $totalJp }} <span class="text-lg text-white/60">/ 20.0 JP</span>
                    </span>
                    <p class="text-xs text-white/70 font-mono mt-0.5">{{ $totalMinutes }} dari 900 Menit Tuntas ({{ $percentage }}%)</p>
                </div>
            </div>

            <!-- Progress Track Bar -->
            <div class="w-full bg-[#141210] h-3.5 rounded-full p-0.5 mb-3 relative z-10 border border-[#3E3833] overflow-hidden">
                <div class="h-full rounded-full transition-all duration-700 shadow-sm bg-gradient-to-r {{ $isCompleted ? 'from-emerald-500 via-teal-400 to-cyan-400' : 'from-[#FF6B00] via-amber-400 to-rose-400' }}"
                     style="width: {{ $percentage }}%"></div>
            </div>

            <!-- Status Box -->
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-2 text-xs text-white/80 pt-3 border-t border-white/10 relative z-10 font-quicksand">
                <span class="flex items-center gap-1.5 font-medium">
                    @if($isCompleted)
                        <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span class="text-emerald-300">Akumulasi 20 JP selesai! Pastikan paket kuis kelas juga telah diselesaikan.</span>
                    @elseif($enrolledClassesCount === 0)
                        <span class="w-2 h-2 rounded-full bg-[#FF6B00] animate-pulse"></span>
                        Silakan pilih kelas pelatihan terlebih dahulu di Katalog untuk memulai pengumpulan JP.
                    @else
                        <span class="w-2 h-2 rounded-full bg-[#FF6B00] animate-pulse"></span>
                        Ikuti sesi Zoom atau kelas tatap muka untuk menambah {{ max(0, round(20.0 - $totalJp, 1)) }} JP lagi.
                    @endif
                </span>
                <a href="{{ route('peserta.study.index') }}" class="text-[#FF6B00] hover:text-amber-300 font-bold font-montserrat flex items-center gap-1">
                    Detail Progres Kelas &rarr;
                </a>
            </div>
        </div>

        <!-- Quick Access Nav Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
            <!-- 4.2 Katalog Kelas -->
            <a href="{{ route('peserta.catalog.index') }}" class="bg-white border border-[#EBE5DF] hover:border-[#FF6B00]/40 rounded-2xl p-6 shadow-sm hover:shadow-lg transition-all duration-200 flex flex-col justify-between group">
                <div>
                    <div class="w-11 h-11 rounded-xl bg-orange-50 text-[#FF6B00] border border-orange-200/80 flex items-center justify-center mb-4">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                    </div>
                    <h4 class="font-montserrat font-extrabold text-base text-[#1E1B18] group-hover:text-[#FF6B00] transition">Katalog Kelas Pelatihan</h4>
                    <p class="text-xs font-quicksand text-[#6E675F] mt-1.5 leading-relaxed">Pilih kelas Offline (maks 40 kursi), Online & Hybrid dengan kuota realtime.</p>
                </div>
                <span class="inline-flex items-center gap-1 mt-5 text-xs font-bold text-[#FF6B00] font-montserrat group-hover:translate-x-0.5 transition">
                    Jelajahi Katalog &rarr;
                </span>
            </a>

            <!-- Ruang Belajar & Zoom -->
            <a href="{{ route('peserta.study.index') }}" class="bg-white border border-[#EBE5DF] hover:border-emerald-300 rounded-2xl p-6 shadow-sm hover:shadow-lg transition-all duration-200 flex flex-col justify-between group">
                <div>
                    <div class="w-11 h-11 rounded-xl bg-emerald-50 text-emerald-600 border border-emerald-200/80 flex items-center justify-center mb-4">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    </div>
                    <h4 class="font-montserrat font-extrabold text-base text-[#1E1B18] group-hover:text-emerald-600 transition">Ruang Belajar Saya</h4>
                    <p class="text-xs font-quicksand text-[#6E675F] mt-1.5 leading-relaxed">Akses silabus pertemuan, 1-klik masuk Zoom, dan kerjakan kuis evaluasi.</p>
                </div>
                <span class="inline-flex items-center gap-1 mt-5 text-xs font-bold text-emerald-600 font-montserrat group-hover:translate-x-0.5 transition">
                    Buka Ruang Belajar ({{ $enrolledClassesCount }} Kelas) &rarr;
                </span>
            </a>

            <!-- Sertifikat Kelulusan (Fase 5) -->
            <a href="{{ route('peserta.certificates.index') }}" class="bg-white border border-[#EBE5DF] hover:border-purple-300 rounded-2xl p-6 shadow-sm hover:shadow-lg transition-all duration-200 flex flex-col justify-between group">
                <div>
                    <div class="w-11 h-11 rounded-xl bg-purple-50 text-purple-600 border border-purple-200/80 flex items-center justify-center mb-4">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <h4 class="font-montserrat font-extrabold text-base text-[#1E1B18] group-hover:text-purple-600 transition">E-Sertifikat Kelulusan</h4>
                    <p class="text-xs font-quicksand text-[#6E675F] mt-1.5 leading-relaxed">Unduh E-Sertifikat resmi ber-QR Code setelah verifikasi 20 JP disetujui Trainer.</p>
                </div>
                <span class="inline-flex items-center gap-1 mt-5 text-xs font-bold text-purple-600 font-montserrat group-hover:translate-x-0.5 transition">
                    Buka Sertifikat &rarr;
                </span>
            </a>
        </div>

        <!-- Sesi Pertemuan Mendatang (Live Zoom) -->
        <div class="bg-white border border-[#EBE5DF] rounded-2xl p-6 shadow-sm space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-[#EBE5DF]">
                <div>
                    <h3 class="text-base font-extrabold text-[#1E1B18] font-montserrat flex items-center gap-2">
                        <span class="p-1.5 rounded-lg bg-sky-50 text-sky-600 border border-sky-100">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                            </svg>
                        </span>
                        <span>Jadwal Sesi Mendatang & Live Zoom</span>
                    </h3>
                    <p class="text-xs font-quicksand text-[#6E675F] mt-1">Bergabung ke ruang tatap muka online dengan 1-klik untuk otomatis mencatat presensi.</p>
                </div>
            </div>

            @if($upcomingSessions->isEmpty())
                <div class="p-8 rounded-xl bg-[#FAF8F5] border border-dashed border-[#EBE5DF] text-center text-xs font-quicksand text-[#6E675F]">
                    Tidak ada jadwal sesi tatap muka online mendatang dari kelas yang Anda ikuti saat ini.
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($upcomingSessions as $session)
                        <div class="p-4 rounded-xl bg-[#FAF8F5] border border-[#EBE5DF] hover:border-sky-300 rounded-xl flex flex-col justify-between gap-3 shadow-sm transition">
                            <div>
                                <div class="flex items-center justify-between text-[10px] text-[#6E675F] font-mono mb-1">
                                    <span class="font-bold">{{ $session->trainingClass->title }}</span>
                                    <span class="text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded border border-emerald-200 font-bold">#Sesi {{ $session->session_order }}</span>
                                </div>
                                <h4 class="text-sm font-bold text-[#1E1B18] font-montserrat">{{ $session->title }}</h4>
                                <div class="text-xs text-[#6E675F] font-quicksand mt-1">
                                    {{ $session->session_date ? \Carbon\Carbon::parse($session->session_date)->translatedFormat('d M Y') : 'Jadwal Fleksibel' }}
                                    @if($session->start_time)
                                        • {{ substr($session->start_time, 0, 5) }} WIB
                                    @endif
                                    • {{ $session->minute_duration }} Menit
                                </div>
                            </div>

                            <div class="pt-3 border-t border-[#EBE5DF] flex items-center justify-between gap-2">
                                <span class="text-[11px] text-[#6E675F] font-mono">Bobot: {{ round($session->minute_duration / 45, 1) }} JP</span>
                                @if(!empty($session->zoom_url))
                                    <form method="POST" action="{{ route('peserta.study.zoom', [$session->trainingClass, $session]) }}" target="_blank">
                                        @csrf
                                        <button type="submit" class="px-3.5 py-1.5 text-xs font-bold text-white bg-sky-600 hover:bg-sky-500 rounded-xl transition flex items-center gap-1.5 font-montserrat shadow-sm">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                            <span>Masuk Zoom</span>
                                        </button>
                                    </form>
                                @else
                                    <a href="{{ route('peserta.study.show', $session->trainingClass) }}" class="text-xs text-[#6E675F] hover:text-[#FF6B00] font-bold font-montserrat">
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
