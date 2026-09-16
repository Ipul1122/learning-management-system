<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div class="flex items-center gap-3">
                <a href="{{ route('peserta.study.index') }}" class="p-2 rounded-xl bg-slate-800/80 hover:bg-slate-700/80 border border-slate-700 text-slate-300 hover:text-white transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>
                <div>
                    <div class="flex items-center gap-2 mb-1">
                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-bold font-montserrat bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                            {{ $class->branch?->name ?? 'Cabang Terpadu' }}
                        </span>
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[10px] font-bold font-mono uppercase {{ $enrollment->isAttendanceOffline() ? 'bg-amber-500/10 text-amber-400 border border-amber-500/20' : 'bg-blue-500/10 text-blue-400 border border-blue-500/20' }}">
                            Mode: {{ $enrollment->attendance_mode }}
                        </span>
                    </div>
                    <h1 class="text-2xl font-extrabold text-white tracking-tight font-montserrat">
                        {{ $class->title }}
                    </h1>
                </div>
            </div>
            <div class="flex items-center gap-2 flex-wrap">
                <a href="{{ route('classes.forum.index', $class) }}" class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-xl bg-[#FF6B00]/10 hover:bg-[#FF6B00]/20 text-[#FF6B00] border border-[#FF6B00]/30 font-montserrat font-bold text-xs transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                    </svg>
                    Forum Diskusi
                </a>
                <span class="px-3 py-1.5 rounded-xl text-xs font-bold font-montserrat {{ $enrollment->isCompletedJp() ? 'bg-emerald-500/20 text-emerald-400 border border-emerald-500/30' : 'bg-slate-800 text-slate-300 border border-slate-700' }}">
                    Status: {{ strtoupper(str_replace('_', ' ', $enrollment->status)) }}
                </span>
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
        @if(session('error'))
            <div class="p-4 rounded-2xl bg-rose-500/10 border border-rose-500/30 text-rose-400 text-xs font-medium flex items-center gap-3">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <!-- Card Tracking Progres JP di Kelas Ini -->
        <div class="bg-gradient-to-br from-slate-900 via-slate-900 to-slate-950 border border-slate-800 rounded-2xl p-5 sm:p-6 text-white shadow-xl relative overflow-hidden">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-4">
                <div>
                    <span class="text-xs font-bold uppercase tracking-wider text-emerald-400 font-montserrat">Progres Pembelajaran Kelas</span>
                    <h3 class="text-lg font-bold font-montserrat text-white mt-0.5">Akumulasi JP & Presensi Peserta</h3>
                    <p class="text-xs text-slate-400 mt-1 font-quicksand">
                        Instruktur: <strong class="text-white">{{ $class->trainer?->name ?? 'Trainer Belum Ditugaskan' }}</strong> • 1 JP = 45 Menit
                    </p>
                </div>
                <div class="text-left sm:text-right">
                    <div class="text-2xl sm:text-3xl font-extrabold font-montserrat text-emerald-400">
                        {{ $enrollment->accumulated_jp }} <span class="text-sm text-slate-400">/ 20.0 JP</span>
                    </div>
                    <span class="text-xs text-slate-400 font-mono">
                        {{ $enrollment->accumulated_minutes }} dari 900 Menit ({{ $enrollment->progressPercentage() }}%)
                    </span>
                </div>
            </div>

            <!-- Progress Bar -->
            <div class="w-full bg-slate-800 h-3 rounded-full overflow-hidden p-0.5 border border-slate-700/60 mb-3">
                <div class="h-full rounded-full transition-all duration-500 bg-gradient-to-r from-emerald-500 via-teal-400 to-cyan-400"
                     style="width: {{ $enrollment->progressPercentage() }}%"></div>
            </div>

            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-2 text-[11px] text-slate-400 pt-2 border-t border-slate-800">
                <span class="flex items-center gap-1.5">
                    @if($enrollment->isCompletedJp())
                        <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span class="text-emerald-400 font-semibold">Target 20 JP di kelas ini telah tercapai.</span>
                    @else
                        <span class="w-2 h-2 rounded-full bg-orange-400 animate-pulse"></span>
                        <span>Klik tombol <strong>"Masuk Zoom"</strong> pada sesi yang sedang berjalan untuk mencatat kehadiran dan menambah menit belajar.</span>
                    @endif
                </span>
                <span class="font-mono text-slate-400">Presensi Sesi: {{ $attendancesBySessionId->count() }} / {{ $class->sessions->count() }} Dihadiri</span>
            </div>
        </div>

        <!-- Section 1: Silabus Sesi Pertemuan & 1-Klik Masuk Zoom (PRD 4.6) -->
        <div class="bg-slate-900/60 border border-slate-800/80 rounded-2xl p-6 space-y-4">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                <div>
                    <h3 class="text-base font-extrabold text-white font-montserrat flex items-center gap-2">
                        <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                        </svg>
                        Silabus Sesi Pertemuan & Presensi Zoom ({{ $class->sessions->count() }} Sesi)
                    </h3>
                    <p class="text-xs text-slate-400 mt-0.5">Tekan tombol Masuk Zoom untuk bergabung ke ruang pertemuan online dan mencatat presensi secara instan.</p>
                </div>
            </div>

            @if($class->sessions->isEmpty())
                <div class="p-8 rounded-xl bg-slate-800/40 border border-slate-800 text-center text-xs text-slate-400">
                    Trainer belum menambahkan sesi pertemuan untuk kelas ini.
                </div>
            @else
                <div class="space-y-3">
                    @foreach($class->sessions->sortBy('session_order') as $session)
                        @php
                            $attendance = $attendancesBySessionId->get($session->id);
                            $isAttended = (bool) $attendance;
                        @endphp
                        <div class="p-4 rounded-xl bg-slate-800/40 border {{ $isAttended ? 'border-emerald-500/30 bg-emerald-500/[0.02]' : 'border-slate-700/60' }} transition flex flex-col md:flex-row md:items-center justify-between gap-4">
                            <div class="flex items-start gap-3">
                                <div class="w-9 h-9 rounded-xl {{ $isAttended ? 'bg-emerald-500/20 text-emerald-400 border border-emerald-500/30' : 'bg-slate-800 text-slate-400' }} flex items-center justify-center font-bold text-xs font-montserrat flex-shrink-0 mt-0.5">
                                    #{{ $session->session_order }}
                                </div>
                                <div class="space-y-1">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <h4 class="text-sm font-bold text-white font-montserrat">{{ $session->title }}</h4>
                                        @if($isAttended)
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-500/15 text-emerald-400 border border-emerald-500/30 font-montserrat">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                                Presensi Tercatat (+{{ $attendance->minutes_earned }} Menit)
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-medium bg-slate-800 text-slate-400 border border-slate-700">
                                                Belum Hadir
                                            </span>
                                        @endif
                                    </div>

                                    <p class="text-xs text-slate-400 font-quicksand line-clamp-1">
                                        {{ $session->description ?: 'Pertemuan pembelajaran materi kompetensi terpadu' }}
                                    </p>

                                    <div class="flex flex-wrap items-center gap-3 text-[11px] text-slate-400 font-mono">
                                        <span>Tanggal: {{ $session->session_date ? \Carbon\Carbon::parse($session->session_date)->translatedFormat('d M Y') : 'Jadwal Fleksibel' }}</span>
                                        @if($session->start_time && $session->end_time)
                                            <span>• Jam: {{ substr($session->start_time, 0, 5) }} - {{ substr($session->end_time, 0, 5) }} WIB</span>
                                        @endif
                                        <span>• Bobot: {{ $session->minute_duration }} Menit ({{ round($session->minute_duration / 45, 1) }} JP)</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Action 1-Klik Masuk Zoom -->
                            <div class="flex items-center gap-2 flex-shrink-0 self-end md:self-center">
                                @if(!empty($session->zoom_url))
                                    <form method="POST" action="{{ route('peserta.study.zoom', [$class, $session]) }}" target="_blank">
                                        @csrf
                                        <button type="submit" class="px-4 py-2 text-xs font-bold text-white bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 rounded-xl shadow-md shadow-blue-500/20 transition flex items-center gap-2 font-montserrat">
                                            <svg class="w-4 h-4 text-cyan-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                            </svg>
                                            <span>{{ $isAttended ? 'Buka Ruang Zoom Lagi' : '1-Klik Masuk Zoom' }}</span>
                                        </button>
                                    </form>
                                @else
                                    <button type="button" disabled class="px-3.5 py-2 text-xs font-medium text-slate-500 bg-slate-800/80 border border-slate-700/60 rounded-xl cursor-not-allowed flex items-center gap-1.5 font-montserrat">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/></svg>
                                        <span>Link Zoom Belum Tersedia</span>
                                    </button>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Section 2: Paket Kuis & Evaluasi Belajar (PRD 4.5) -->
        <div class="bg-slate-900/60 border border-slate-800/80 rounded-2xl p-6 space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-base font-extrabold text-white font-montserrat flex items-center gap-2">
                        <svg class="w-5 h-5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                        </svg>
                        Paket Kuis & Evaluasi Pemahaman ({{ $class->quizzes->count() }} Paket)
                    </h3>
                    <p class="text-xs text-slate-400 mt-0.5">Kerjakan kuis interaktif dengan timer countdown dan sistem auto-grading otomatis.</p>
                </div>
            </div>

            @if($class->quizzes->isEmpty())
                <div class="p-8 rounded-xl bg-slate-800/40 border border-slate-800 text-center text-xs text-slate-400">
                    Belum ada kuis yang ditugaskan pada kelas ini.
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($class->quizzes as $quiz)
                        @php
                            $attempts = $userQuizAttempts->get($quiz->id, collect());
                            $attemptCount = $attempts->count();
                            $bestScore = $attempts->max('total_score');
                            $hasPassed = $attempts->contains('is_passed', true);
                            $canAttempt = $attemptCount < $quiz->max_attempts;
                        @endphp
                        <div class="p-5 rounded-xl bg-slate-800/40 border border-slate-700/60 flex flex-col justify-between gap-4">
                            <div class="space-y-2">
                                <div class="flex items-center justify-between gap-2">
                                    <span class="text-[10px] font-bold uppercase tracking-wider text-amber-400 font-montserrat bg-amber-500/10 px-2 py-0.5 rounded-full border border-amber-500/20">
                                        KKM: {{ $quiz->passing_grade }}%
                                    </span>
                                    <span class="text-xs font-mono text-slate-400">
                                        Percobaan: {{ $attemptCount }} / {{ $quiz->max_attempts }}
                                    </span>
                                </div>

                                <h4 class="text-sm font-bold text-white font-montserrat">{{ $quiz->title }}</h4>
                                <p class="text-xs text-slate-400 font-quicksand line-clamp-2">
                                    {{ $quiz->description ?: 'Evaluasi materi untuk menguji pemahaman peserta pelatihan.' }}
                                </p>

                                <div class="flex items-center gap-4 text-xs text-slate-300 font-mono pt-2 border-t border-slate-700/60">
                                    <span>⏱ {{ $quiz->time_limit_minutes }} Menit</span>
                                    <span>📝 {{ $quiz->questions_count }} Soal</span>
                                    @if($attemptCount > 0)
                                        <span class="font-bold {{ $hasPassed ? 'text-emerald-400' : 'text-rose-400' }}">
                                            Skor Terbaik: {{ $bestScore }}
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="pt-3 border-t border-slate-700/60 flex items-center justify-between gap-2">
                                <div>
                                    @if($hasPassed)
                                        <span class="inline-flex items-center gap-1 text-[11px] font-bold text-emerald-400 font-montserrat">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                            Lulus Kuis
                                        </span>
                                    @elseif($attemptCount > 0)
                                        <span class="text-[11px] font-medium text-amber-400 font-montserrat">
                                            Belum Lulus
                                        </span>
                                    @else
                                        <span class="text-[11px] text-slate-400 font-montserrat">
                                            Belum Dicoba
                                        </span>
                                    @endif
                                </div>

                                <a href="{{ route('peserta.quizzes.show', [$class, $quiz]) }}" class="px-4 py-2 text-xs font-bold text-white bg-slate-800 hover:bg-slate-700 border border-slate-700 rounded-xl transition flex items-center gap-1.5 font-montserrat">
                                    <span>{{ $attemptCount > 0 ? 'Lihat Hasil & Ulang' : 'Buka Kuis' }}</span>
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
