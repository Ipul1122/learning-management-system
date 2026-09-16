<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div class="flex items-center gap-3">
                <a href="{{ route('peserta.catalog.index') }}" class="p-2 rounded-xl bg-slate-800/80 hover:bg-slate-700/80 border border-slate-700 text-slate-300 hover:text-white transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>
                <div>
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-bold font-montserrat bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 mb-1">
                        {{ $class->branch?->name ?? 'Cabang Terpadu' }}
                    </span>
                    <h1 class="text-2xl font-extrabold text-white tracking-tight font-montserrat">
                        {{ $class->title }}
                    </h1>
                </div>
            </div>
            <div class="flex items-center gap-2">
                @if($enrollment)
                    <a href="{{ route('peserta.study.show', $class) }}" class="px-5 py-2.5 text-xs font-bold text-white bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700 rounded-xl shadow-lg shadow-emerald-500/20 transition flex items-center gap-2 font-montserrat">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                        Buka Ruang Belajar
                    </a>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-6 space-y-6">
        <!-- Notifikasi Session Flash -->
        @if(session('success'))
            <div class="p-4 rounded-2xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-xs font-medium flex items-center gap-3">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif
        @if(session('info'))
            <div class="p-4 rounded-2xl bg-blue-500/10 border border-blue-500/30 text-blue-400 text-xs font-medium flex items-center gap-3">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ session('info') }}</span>
            </div>
        @endif
        @if($errors->any())
            <div class="p-4 rounded-2xl bg-rose-500/10 border border-rose-500/30 text-rose-400 text-xs font-medium space-y-1">
                @foreach($errors->all() as $err)
                    <div class="flex items-center gap-2">
                        <span class="w-1.5 h-1.5 rounded-full bg-rose-400"></span>
                        <span>{{ $err }}</span>
                    </div>
                @endforeach
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Kolom Kiri: Detail, Silabus & Kuis (2 Kolom) -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Deskripsi & Info Pengajar -->
                <div class="bg-slate-900/60 border border-slate-800/80 rounded-2xl p-6 space-y-5">
                    <div class="flex flex-wrap items-center gap-2">
                        <!-- Badge Tipe -->
                        @if($class->type === 'offline')
                            <span class="px-3 py-1 rounded-full text-xs font-bold bg-amber-500/10 text-amber-400 border border-amber-500/30 flex items-center gap-1.5 font-montserrat">
                                <span class="w-2 h-2 rounded-full bg-amber-400"></span>
                                Offline (Tatap Muka)
                            </span>
                        @elseif($class->type === 'online')
                            <span class="px-3 py-1 rounded-full text-xs font-bold bg-blue-500/10 text-blue-400 border border-blue-500/30 flex items-center gap-1.5 font-montserrat">
                                <span class="w-2 h-2 rounded-full bg-blue-400"></span>
                                Online (Live Zoom)
                            </span>
                        @else
                            <span class="px-3 py-1 rounded-full text-xs font-bold bg-purple-500/10 text-purple-400 border border-purple-500/30 flex items-center gap-1.5 font-montserrat">
                                <span class="w-2 h-2 rounded-full bg-purple-400"></span>
                                Hybrid (Fisik + Zoom)
                            </span>
                        @endif

                        <!-- Badge Status -->
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-800 text-slate-300 border border-slate-700 capitalize">
                            Status: {{ $class->status }}
                        </span>

                        <!-- Sisa Kuota Offline -->
                        @if($class->isOffline() || $class->isHybrid())
                            @php
                                $remainingOffline = max(0, 40 - (int)$class->enrolled_offline);
                            @endphp
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-bold {{ $remainingOffline > 0 ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20' : 'bg-rose-500/10 text-rose-400 border border-rose-500/20' }}">
                                Kursi Offline: {{ $remainingOffline }} / 40 Tersisa
                            </span>
                        @endif
                    </div>

                    <div>
                        <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 font-montserrat mb-2">Tentang Pelatihan</h3>
                        <p class="text-sm text-slate-300 leading-relaxed font-quicksand whitespace-pre-line">
                            {{ $class->description ?: 'Belum ada ringkasan deskripsi pelatihan untuk kelas ini.' }}
                        </p>
                    </div>

                    <!-- Profil Trainer -->
                    <div class="pt-4 border-t border-slate-800/80 flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-tr from-emerald-500 to-teal-500 flex items-center justify-center font-bold text-white text-lg font-montserrat shadow-md">
                            {{ strtoupper(substr($class->trainer?->name ?? 'T', 0, 1)) }}
                        </div>
                        <div>
                            <span class="text-[11px] uppercase tracking-wider text-slate-400 font-montserrat block">Instruktur / Trainer</span>
                            <span class="font-bold text-white text-sm font-montserrat">{{ $class->trainer?->name ?? 'Belum Ditugaskan' }}</span>
                            <span class="text-xs text-slate-400 block">{{ $class->trainer?->email }}</span>
                        </div>
                    </div>
                </div>

                <!-- Silabus Sesi Pertemuan -->
                <div class="bg-slate-900/60 border border-slate-800/80 rounded-2xl p-6 space-y-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-base font-extrabold text-white font-montserrat flex items-center gap-2">
                                <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                Silabus Sesi Pertemuan ({{ $class->sessions->count() }} Sesi)
                            </h3>
                            <p class="text-xs text-slate-400 mt-0.5">Setiap sesi memiliki bobot durasi belajar terstruktur (1 JP = 45 menit).</p>
                        </div>
                        <span class="text-xs font-bold text-emerald-400 font-montserrat bg-emerald-500/10 px-2.5 py-1 rounded-lg border border-emerald-500/20">
                            Total: {{ $class->sessions->sum('minute_duration') }} Menit ({{ round($class->sessions->sum('minute_duration') / 45, 1) }} JP)
                        </span>
                    </div>

                    @if($class->sessions->isEmpty())
                        <div class="p-6 rounded-xl bg-slate-800/40 border border-slate-800 text-center text-xs text-slate-400">
                            Jadwal sesi pertemuan belum diterbitkan oleh Trainer kelas ini.
                        </div>
                    @else
                        <div class="space-y-3">
                            @foreach($class->sessions->sortBy('session_order') as $session)
                                <div class="p-4 rounded-xl bg-slate-800/40 border border-slate-700/60 hover:border-slate-600/80 transition flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                                    <div class="flex items-start gap-3">
                                        <div class="w-8 h-8 rounded-lg bg-emerald-500/20 text-emerald-400 flex items-center justify-center font-bold text-xs font-montserrat flex-shrink-0 mt-0.5">
                                            #{{ $session->session_order }}
                                        </div>
                                        <div>
                                            <h4 class="text-sm font-bold text-white font-montserrat">{{ $session->title }}</h4>
                                            <p class="text-xs text-slate-400 font-quicksand mt-0.5 line-clamp-1">
                                                {{ $session->description ?: 'Topik materi tatap muka / live meeting' }}
                                            </p>
                                            <div class="flex items-center gap-3 text-[11px] text-slate-400 mt-1">
                                                <span>{{ $session->session_date ? \Carbon\Carbon::parse($session->session_date)->translatedFormat('d M Y') : 'Jadwal fleksibel' }}</span>
                                                @if($session->start_time && $session->end_time)
                                                    <span>• {{ substr($session->start_time, 0, 5) }} - {{ substr($session->end_time, 0, 5) }} WIB</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-right flex-shrink-0">
                                        <span class="inline-block px-2.5 py-1 rounded-lg text-xs font-bold bg-slate-800 text-slate-200 border border-slate-700 font-mono">
                                            {{ $session->minute_duration }} Menit ({{ round($session->minute_duration / 45, 1) }} JP)
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- Paket Kuis Kelas -->
                <div class="bg-slate-900/60 border border-slate-800/80 rounded-2xl p-6 space-y-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-base font-extrabold text-white font-montserrat flex items-center gap-2">
                                <svg class="w-5 h-5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                                </svg>
                                Paket Kuis & Evaluasi ({{ $class->quizzes->count() }} Paket)
                            </h3>
                            <p class="text-xs text-slate-400 mt-0.5">Selesaikan kuis untuk menguji pemahaman Anda dengan batas passing grade.</p>
                        </div>
                    </div>

                    @if($class->quizzes->isEmpty())
                        <div class="p-6 rounded-xl bg-slate-800/40 border border-slate-800 text-center text-xs text-slate-400">
                            Belum ada paket kuis yang dialokasikan pada kelas ini.
                        </div>
                    @else
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            @foreach($class->quizzes as $quiz)
                                <div class="p-4 rounded-xl bg-slate-800/40 border border-slate-700/60 flex flex-col justify-between gap-3">
                                    <div>
                                        <span class="text-[10px] font-bold uppercase tracking-wider text-amber-400 font-montserrat">Kuis Pelatihan</span>
                                        <h4 class="text-sm font-bold text-white font-montserrat mt-0.5">{{ $quiz->title }}</h4>
                                        <p class="text-xs text-slate-400 font-quicksand mt-1 line-clamp-2">{{ $quiz->description }}</p>
                                    </div>
                                    <div class="flex items-center justify-between text-xs text-slate-300 pt-2 border-t border-slate-700/60 font-mono">
                                        <span>Durasi: {{ $quiz->time_limit_minutes }} Menit</span>
                                        <span class="text-amber-400 font-bold">KKM: {{ $quiz->passing_grade }}%</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <!-- Kolom Kanan: Pendaftaran & Syarat (1 Kolom) -->
            <div class="space-y-6">
                <!-- Card Pendaftaran -->
                <div class="bg-gradient-to-br from-slate-900 via-slate-900 to-slate-950 border border-slate-800 rounded-3xl p-6 shadow-xl relative overflow-hidden">
                    <div class="absolute -right-8 -top-8 w-32 h-32 bg-emerald-500/10 rounded-full blur-2xl pointer-events-none"></div>

                    @if($enrollment)
                        <!-- State: Sudah Terdaftar -->
                        <div class="space-y-4">
                            <div class="w-12 h-12 rounded-2xl bg-emerald-500/20 text-emerald-400 flex items-center justify-center mx-auto mb-2">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                            <div class="text-center">
                                <span class="text-xs font-bold uppercase tracking-wider text-emerald-400 font-montserrat">Status Kepesertaan</span>
                                <h3 class="text-lg font-extrabold text-white font-montserrat mt-0.5">Anda Sudah Terdaftar</h3>
                                <p class="text-xs text-slate-400 mt-1">
                                    Mode Kehadiran: <strong class="text-white uppercase">{{ $enrollment->attendance_mode }}</strong>
                                </p>
                            </div>

                            <!-- Progres Kelas -->
                            <div class="bg-slate-800/60 border border-slate-700/60 rounded-2xl p-4 space-y-2">
                                <div class="flex justify-between items-center text-xs font-montserrat">
                                    <span class="text-slate-400">Progres JP di Kelas Ini:</span>
                                    <span class="font-bold text-emerald-400">{{ $enrollment->accumulated_jp }} / 20.0 JP</span>
                                </div>
                                <div class="w-full bg-slate-700/60 h-2.5 rounded-full overflow-hidden">
                                    <div class="h-full bg-gradient-to-r from-emerald-500 to-teal-400 rounded-full transition-all duration-500"
                                         style="width: {{ $enrollment->progressPercentage() }}%"></div>
                                </div>
                                <div class="flex justify-between items-center text-[10px] text-slate-400">
                                    <span>{{ $enrollment->accumulated_minutes }} dari 900 Menit</span>
                                    <span>{{ $enrollment->progressPercentage() }}%</span>
                                </div>
                            </div>

                            <a href="{{ route('peserta.study.show', $class) }}" class="w-full py-3 text-xs font-bold text-white bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700 rounded-xl shadow-lg shadow-emerald-500/20 transition flex items-center justify-center gap-2 font-montserrat">
                                Masuk ke Ruang Belajar &rarr;
                            </a>
                        </div>
                    @else
                        <!-- State: Belum Terdaftar -->
                        <div class="space-y-4">
                            <div>
                                <span class="text-xs font-bold uppercase tracking-wider text-orange-400 font-montserrat">Formulir Pendaftaran</span>
                                <h3 class="text-lg font-extrabold text-white font-montserrat mt-0.5">Daftar Kelas Ini</h3>
                                <p class="text-xs text-slate-400 mt-1">Pendaftaran tidak dipungut biaya. Kuota offline dibatasi maksimal 40 peserta demi kenyamanan kelas fisik.</p>
                            </div>

                            @if(!in_array($class->status, ['open', 'ongoing']))
                                <div class="p-4 rounded-xl bg-slate-800/60 border border-slate-700 text-xs text-slate-400 text-center">
                                    Pendaftaran untuk kelas ini saat ini ditutup (Status: {{ $class->status }}).
                                </div>
                            @else
                                <form method="POST" action="{{ route('peserta.catalog.enroll', $class) }}" class="space-y-4">
                                    @csrf

                                    <!-- Pilihan Modalitas Kehadiran -->
                                    <div class="space-y-2">
                                        <label class="block text-xs font-bold text-slate-300 font-montserrat">Pilih Cara Kehadiran:</label>

                                        @if($class->isOffline())
                                            <input type="hidden" name="attendance_mode" value="offline">
                                            <div class="p-3 rounded-xl bg-slate-800/80 border border-amber-500/40 text-xs text-slate-200">
                                                <div class="font-bold text-amber-400 flex items-center gap-1.5">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                                                    Kehadiran Fisik (Offline)
                                                </div>
                                                <p class="text-[11px] text-slate-400 mt-1">Dilaksanakan langsung di fasilitas pelatihan {{ $class->branch?->name }}.</p>
                                            </div>
                                        @elseif($class->isOnline())
                                            <input type="hidden" name="attendance_mode" value="online">
                                            <div class="p-3 rounded-xl bg-slate-800/80 border border-blue-500/40 text-xs text-slate-200">
                                                <div class="font-bold text-blue-400 flex items-center gap-1.5">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                                    Kehadiran Daring (Online via Zoom)
                                                </div>
                                                <p class="text-[11px] text-slate-400 mt-1">Tatap muka daring interaktif via Zoom Meeting.</p>
                                            </div>
                                        @else
                                            <!-- Hybrid -->
                                            <div class="space-y-2">
                                                <!-- Radio Offline -->
                                                <label class="flex items-start gap-3 p-3 rounded-xl border {{ $class->isFullOffline() ? 'border-slate-800 bg-slate-900/40 opacity-60 cursor-not-allowed' : 'border-slate-700 bg-slate-800/60 hover:border-emerald-500 cursor-pointer' }} transition">
                                                    <input type="radio" name="attendance_mode" value="offline" {{ $class->isFullOffline() ? 'disabled' : 'checked' }} class="mt-0.5 text-emerald-500 focus:ring-emerald-500">
                                                    <div class="text-xs">
                                                        <span class="font-bold text-white font-montserrat">Hadir Langsung di Lokasi (Offline)</span>
                                                        <span class="block text-[11px] text-slate-400">
                                                            @if($class->isFullOffline())
                                                                <span class="text-rose-400 font-semibold">(Kuota 40 Kursi Offline Telah Penuh)</span>
                                                            @else
                                                                Tersisa {{ 40 - $class->enrolled_offline }} kursi fisik di {{ $class->branch?->name }}
                                                            @endif
                                                        </span>
                                                    </div>
                                                </label>

                                                <!-- Radio Online -->
                                                <label class="flex items-start gap-3 p-3 rounded-xl border border-slate-700 bg-slate-800/60 hover:border-emerald-500 cursor-pointer transition">
                                                    <input type="radio" name="attendance_mode" value="online" {{ $class->isFullOffline() ? 'checked' : '' }} class="mt-0.5 text-emerald-500 focus:ring-emerald-500">
                                                    <div class="text-xs">
                                                        <span class="font-bold text-white font-montserrat">Hadir Daring (Online via Zoom)</span>
                                                        <span class="block text-[11px] text-slate-400">Akses live meeting tanpa batasan kuota kursi fisik</span>
                                                    </div>
                                                </label>
                                            </div>
                                        @endif
                                    </div>

                                    @if($class->isOffline() && $class->isFullOffline())
                                        <div class="p-3 rounded-xl bg-rose-500/10 border border-rose-500/30 text-rose-400 text-xs text-center font-bold">
                                            Kuota kelas offline ini telah penuh (40/40 Kursi).
                                        </div>
                                    @else
                                        <button type="submit" class="w-full py-3 text-xs font-bold text-white bg-gradient-to-r from-orange-500 to-amber-500 hover:from-orange-600 hover:to-amber-600 rounded-xl shadow-lg shadow-orange-500/20 transition flex items-center justify-center gap-2 font-montserrat">
                                            Konfirmasi Pendaftaran Kelas
                                        </button>
                                    @endif
                                </form>
                            @endif
                        </div>
                    @endif
                </div>

                <!-- Info Proteksi Kuota & 20 JP -->
                <div class="bg-slate-900/40 border border-slate-800/80 rounded-2xl p-5 space-y-3 text-xs text-slate-400">
                    <h4 class="font-bold text-slate-300 font-montserrat flex items-center gap-2">
                        <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Ketentuan Jam Pelajaran (JP)
                    </h4>
                    <ul class="space-y-2 font-quicksand">
                        <li class="flex items-start gap-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 mt-1.5 flex-shrink-0"></span>
                            <span><strong>1 JP = 45 Menit</strong>. Target kelulusan minimal adalah <strong>20 JP (900 menit)</strong>.</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 mt-1.5 flex-shrink-0"></span>
                            <span>Presensi dihitung otomatis saat Anda menekan tombol <strong>Masuk Zoom</strong> pada jadwal sesi terkait.</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 mt-1.5 flex-shrink-0"></span>
                            <span>Setelah memenuhi 20 JP dan menuntaskan kuis, sertifikat akan diverifikasi oleh Trainer.</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
