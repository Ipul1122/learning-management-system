<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div class="flex items-center gap-3">
                <a href="{{ route('peserta.study.index') }}" class="p-2.5 rounded-xl bg-white hover:bg-[#FAF8F5] border border-[#EBE5DF] text-[#1E1B18] shadow-sm transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>
                <div>
                    <div class="flex items-center gap-2 mb-1">
                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-bold font-montserrat bg-[#FFF7ED] text-[#EA580C] border border-[#FED7AA]">
                            🏢 {{ $class->branch?->name ?? 'Cabang Terpadu' }}
                        </span>
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[10px] font-bold font-montserrat uppercase {{ $enrollment->isAttendanceOffline() ? 'bg-amber-50 text-amber-700 border border-amber-200' : 'bg-blue-50 text-blue-700 border border-blue-200' }}">
                            Mode: {{ $enrollment->attendance_mode }}
                        </span>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-extrabold text-[#1E1B18] tracking-tight font-montserrat">
                        {{ $class->title }}
                    </h1>
                </div>
            </div>
            <div class="flex items-center gap-2 flex-wrap">
                <a href="{{ route('classes.forum.index', $class) }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-[#FFF7ED] hover:bg-[#FFEDD5] text-[#FF6B00] border border-[#FED7AA] font-montserrat font-bold text-xs transition shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                    </svg>
                    <span>Forum Diskusi</span>
                </a>
                <span class="px-3 py-2 rounded-xl text-xs font-bold font-montserrat {{ $enrollment->isCompletedJp() ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-[#FAF8F5] text-[#6E675F] border border-[#EBE5DF]' }}">
                    Status: {{ strtoupper(str_replace('_', ' ', $enrollment->status)) }}
                </span>
            </div>
        </div>
    </x-slot>

    <div class="py-8 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
        <!-- Notifikasi Session Flash -->
        @if(session('success'))
            <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-700 text-xs font-medium flex items-center gap-3 font-quicksand">
                <svg class="w-5 h-5 flex-shrink-0 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif
        @if(session('error'))
            <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-700 text-xs font-medium flex items-center gap-3 font-quicksand">
                <svg class="w-5 h-5 flex-shrink-0 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <!-- Card Tracking Progres JP di Kelas Ini -->
        <div class="bg-gradient-to-br from-[#1E1B18] via-[#2D2723] to-[#1E1B18] border border-[#3E3833] rounded-3xl p-6 sm:p-8 text-white shadow-xl relative overflow-hidden">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-4">
                <div>
                    <span class="text-xs font-bold uppercase tracking-wider text-emerald-400 font-montserrat">Progres Pembelajaran Kelas</span>
                    <h3 class="text-xl font-extrabold font-montserrat text-white mt-1">Akumulasi JP & Presensi Peserta</h3>
                    <p class="text-xs text-white/70 mt-1 font-quicksand">
                        Instruktur: <strong class="text-white">{{ $class->trainer?->name ?? 'Trainer Belum Ditugaskan' }}</strong> • 1 JP = 45 Menit
                    </p>
                </div>
                <div class="text-left sm:text-right">
                    <div class="text-3xl font-extrabold font-montserrat text-emerald-400">
                        {{ $enrollment->accumulated_jp }} <span class="text-sm text-white/60">/ 20.0 JP</span>
                    </div>
                    <span class="text-xs text-white/70 font-mono">
                        {{ $enrollment->accumulated_minutes }} dari 900 Menit ({{ $enrollment->progressPercentage() }}%)
                    </span>
                </div>
            </div>

            <!-- Progress Bar -->
            <div class="w-full bg-[#141210] h-3 rounded-full overflow-hidden p-0.5 border border-[#3E3833] mb-3">
                <div class="h-full rounded-full transition-all duration-500 bg-gradient-to-r from-emerald-500 via-teal-400 to-cyan-400"
                     style="width: {{ $enrollment->progressPercentage() }}%"></div>
            </div>

            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-2 text-xs text-white/70 pt-3 border-t border-white/10 font-quicksand">
                <span class="flex items-center gap-1.5">
                    @if($enrollment->isCompletedJp())
                        <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span class="text-emerald-300 font-semibold">Target 20 JP di kelas ini telah tercapai.</span>
                    @else
                        <span class="w-2 h-2 rounded-full bg-[#FF6B00] animate-pulse"></span>
                        <span>Klik tombol <strong>"Masuk Zoom"</strong> pada sesi yang sedang berjalan untuk mencatat kehadiran dan menambah menit belajar.</span>
                    @endif
                </span>
                <span class="font-mono text-white/80">Presensi Sesi: {{ $attendancesBySessionId->count() }} / {{ $class->sessions->count() }} Dihadiri</span>
            </div>
        </div>

        <!-- Section 1: Silabus Sesi Pertemuan & 1-Klik Masuk Zoom (PRD 4.6) -->
        <div class="bg-white border border-[#EBE5DF] rounded-2xl p-6 space-y-4 shadow-sm">
            <div class="pb-3 border-b border-[#EBE5DF]">
                <h3 class="text-base font-extrabold text-[#1E1B18] font-montserrat flex items-center gap-2">
                    <span class="p-1.5 rounded-lg bg-sky-50 text-sky-600 border border-sky-100">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                        </svg>
                    </span>
                    <span>Silabus Sesi Pertemuan & Presensi Zoom ({{ $class->sessions->count() }} Sesi)</span>
                </h3>
                <p class="text-xs text-[#6E675F] mt-1 font-quicksand">Tekan tombol Masuk Zoom untuk bergabung ke ruang pertemuan online dan mencatat presensi secara instan.</p>
            </div>

            @if($class->sessions->isEmpty())
                <div class="p-8 rounded-xl bg-[#FAF8F5] border border-dashed border-[#EBE5DF] text-center text-xs text-[#6E675F] font-quicksand">
                    Trainer belum menambahkan sesi pertemuan untuk kelas ini.
                </div>
            @else
                <div class="space-y-3">
                    @foreach($class->sessions->sortBy('session_order') as $session)
                        @php
                            $attendance = $attendancesBySessionId->get($session->id);
                            $isAttended = (bool) $attendance;
                        @endphp
                        <div class="p-4 rounded-xl bg-[#FAF8F5] border {{ $isAttended ? 'border-emerald-300 bg-emerald-50/20' : 'border-[#EBE5DF]' }} transition flex flex-col md:flex-row md:items-center justify-between gap-4 shadow-sm">
                            <div class="flex items-start gap-3">
                                <div class="w-9 h-9 rounded-xl {{ $isAttended ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-white text-[#6E675F] border border-[#EBE5DF]' }} flex items-center justify-center font-bold text-xs font-montserrat flex-shrink-0 mt-0.5">
                                    #{{ $session->session_order }}
                                </div>
                                <div class="space-y-1">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <h4 class="text-sm font-bold text-[#1E1B18] font-montserrat">{{ $session->title }}</h4>
                                        @if($isAttended)
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 font-montserrat">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                                Presensi Tercatat (+{{ $attendance->minutes_earned }} Menit)
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold font-montserrat bg-[#FAF8F5] text-[#6E675F] border border-[#EBE5DF]">
                                                Belum Hadir
                                            </span>
                                        @endif
                                    </div>

                                    <p class="text-xs text-[#6E675F] font-quicksand line-clamp-1">
                                        {{ $session->description ?: 'Pertemuan pembelajaran materi kompetensi terpadu' }}
                                    </p>

                                    <div class="flex flex-wrap items-center gap-3 text-[11px] text-[#6E675F] font-quicksand">
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
                                        <button type="submit" class="px-4 py-2 text-xs font-bold text-white bg-gradient-to-r from-[#FF6B00] to-[#E11D48] hover:from-[#EA580C] hover:to-[#DC2626] rounded-xl shadow-md hover:shadow-lg transition flex items-center gap-2 font-montserrat">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                            </svg>
                                            <span>{{ $isAttended ? 'Buka Ruang Zoom Lagi' : '1-Klik Masuk Zoom' }}</span>
                                        </button>
                                    </form>
                                @else
                                    <button type="button" disabled class="px-3.5 py-2 text-xs font-bold text-[#6E675F] bg-white border border-[#EBE5DF] rounded-xl cursor-not-allowed flex items-center gap-1.5 font-montserrat">
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
        <div class="bg-white border border-[#EBE5DF] rounded-2xl p-6 space-y-4 shadow-sm">
            <div class="pb-3 border-b border-[#EBE5DF]">
                <h3 class="text-base font-extrabold text-[#1E1B18] font-montserrat flex items-center gap-2">
                    <span class="p-1.5 rounded-lg bg-amber-50 text-amber-600 border border-amber-100">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                        </svg>
                    </span>
                    <span>Paket Kuis & Evaluasi Pemahaman ({{ $class->quizzes->count() }} Paket)</span>
                </h3>
                <p class="text-xs text-[#6E675F] mt-1 font-quicksand">Kerjakan kuis interaktif dengan timer countdown dan sistem auto-grading otomatis.</p>
            </div>

            @if($class->quizzes->isEmpty())
                <div class="p-8 rounded-xl bg-[#FAF8F5] border border-dashed border-[#EBE5DF] text-center text-xs text-[#6E675F] font-quicksand">
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
                        <div class="p-5 rounded-xl bg-[#FAF8F5] border border-[#EBE5DF] hover:border-[#FF6B00]/40 flex flex-col justify-between gap-4 transition shadow-sm">
                            <div class="space-y-2">
                                <div class="flex items-center justify-between gap-2">
                                    <span class="text-[10px] font-bold uppercase tracking-wider text-amber-700 font-montserrat bg-amber-50 px-2.5 py-0.5 rounded-full border border-amber-200">
                                        KKM: {{ $quiz->passing_grade }}%
                                    </span>
                                    <span class="text-xs font-mono text-[#6E675F]">
                                        Percobaan: {{ $attemptCount }} / {{ $quiz->max_attempts }}
                                    </span>
                                </div>

                                <h4 class="text-sm font-bold text-[#1E1B18] font-montserrat">{{ $quiz->title }}</h4>
                                <p class="text-xs text-[#6E675F] font-quicksand line-clamp-2">
                                    {{ $quiz->description ?: 'Evaluasi materi untuk menguji pemahaman peserta pelatihan.' }}
                                </p>

                                <div class="flex items-center gap-4 text-xs text-[#6E675F] font-quicksand pt-2 border-t border-[#EBE5DF]">
                                    <span>⏱ {{ $quiz->time_limit_minutes }} Menit</span>
                                    <span>📝 {{ $quiz->questions_count }} Soal</span>
                                    @if($attemptCount > 0)
                                        <span class="font-bold {{ $hasPassed ? 'text-emerald-700' : 'text-rose-600' }} font-montserrat">
                                            Skor Terbaik: {{ $bestScore }}
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="pt-3 border-t border-[#EBE5DF] flex items-center justify-between gap-2">
                                <div>
                                    @if($hasPassed)
                                        <span class="inline-flex items-center gap-1 text-[11px] font-bold text-emerald-700 font-montserrat bg-emerald-50 px-2 py-0.5 rounded border border-emerald-200">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                            Lulus Kuis
                                        </span>
                                    @elseif($attemptCount > 0)
                                        <span class="text-[11px] font-bold text-amber-700 bg-amber-50 px-2 py-0.5 rounded border border-amber-200 font-montserrat">
                                            Belum Lulus
                                        </span>
                                    @else
                                        <span class="text-[11px] text-[#6E675F] font-montserrat">
                                            Belum Dicoba
                                        </span>
                                    @endif
                                </div>

                                <a href="{{ route('peserta.quizzes.show', [$class, $quiz]) }}" class="px-4 py-2 text-xs font-bold text-[#1E1B18] bg-white hover:bg-[#FAF8F5] border border-[#EBE5DF] rounded-xl transition flex items-center gap-1.5 font-montserrat shadow-sm">
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
