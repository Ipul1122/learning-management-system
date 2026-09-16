<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div class="flex items-center gap-3">
                <a href="{{ route('peserta.study.show', $class) }}" class="p-2 rounded-xl bg-slate-800/80 hover:bg-slate-700/80 border border-slate-700 text-slate-300 hover:text-white transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>
                <div>
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-bold font-montserrat bg-amber-500/10 text-amber-400 border border-amber-500/20 mb-1">
                        {{ $class->title }}
                    </span>
                    <h1 class="text-2xl font-extrabold text-white tracking-tight font-montserrat">
                        {{ $quiz->title }}
                    </h1>
                </div>
            </div>
            <div class="flex items-center gap-2">
                @if($hasPassed)
                    <span class="px-3 py-1.5 rounded-xl text-xs font-bold font-montserrat bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Status: Sudah Lulus (Skor: {{ $bestScore }})
                    </span>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-6 space-y-6">
        @if(session('info'))
            <div class="p-4 rounded-2xl bg-blue-500/10 border border-blue-500/30 text-blue-400 text-xs font-medium flex items-center gap-3">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ session('info') }}</span>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Kolom Kiri: Briefing & Mulai Kuis (2 Kolom) -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Briefing Card -->
                <div class="bg-slate-900/60 border border-slate-800/80 rounded-2xl p-6 space-y-5">
                    <div>
                        <span class="text-xs font-bold uppercase tracking-wider text-amber-400 font-montserrat">Petunjuk & Peraturan Pengerjaan</span>
                        <h3 class="text-lg font-bold font-montserrat text-white mt-1">Briefing Kuis Interaktif</h3>
                        <p class="text-sm text-slate-300 leading-relaxed font-quicksand mt-2">
                            {{ $quiz->description ?: 'Kuis ini dirancang untuk menguji pemahaman materi yang telah dipelajari dalam modul kelas pelatihan ini.' }}
                        </p>
                    </div>

                    <!-- Parameter Kuis Grid -->
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 pt-2">
                        <div class="p-3.5 rounded-xl bg-slate-800/50 border border-slate-700/60 text-center">
                            <span class="text-[10px] uppercase font-bold text-slate-400 font-montserrat block">Batas Waktu</span>
                            <span class="text-lg font-extrabold text-white font-mono mt-0.5 block">{{ $quiz->time_limit_minutes }}</span>
                            <span class="text-[11px] text-slate-400">Menit</span>
                        </div>

                        <div class="p-3.5 rounded-xl bg-slate-800/50 border border-slate-700/60 text-center">
                            <span class="text-[10px] uppercase font-bold text-slate-400 font-montserrat block">Passing Grade</span>
                            <span class="text-lg font-extrabold text-amber-400 font-mono mt-0.5 block">{{ $quiz->passing_grade }}%</span>
                            <span class="text-[11px] text-slate-400">Nilai Minimal</span>
                        </div>

                        <div class="p-3.5 rounded-xl bg-slate-800/50 border border-slate-700/60 text-center">
                            <span class="text-[10px] uppercase font-bold text-slate-400 font-montserrat block">Maks Percobaan</span>
                            <span class="text-lg font-extrabold text-white font-mono mt-0.5 block">{{ $quiz->max_attempts }}</span>
                            <span class="text-[11px] text-slate-400">Kali Kesempatan</span>
                        </div>

                        <div class="p-3.5 rounded-xl bg-slate-800/50 border border-slate-700/60 text-center">
                            <span class="text-[10px] uppercase font-bold text-slate-400 font-montserrat block">Soal Diacak</span>
                            <span class="text-lg font-extrabold {{ $quiz->is_randomized ? 'text-emerald-400' : 'text-slate-400' }} font-mono mt-0.5 block">
                                {{ $quiz->is_randomized ? 'Ya' : 'Tidak' }}
                            </span>
                            <span class="text-[11px] text-slate-400">Pengacakan Butir</span>
                        </div>
                    </div>

                    <!-- Ketentuan Khusus -->
                    <div class="p-4 rounded-xl bg-slate-800/30 border border-slate-700/50 space-y-2 text-xs text-slate-400 font-quicksand">
                        <div class="flex items-start gap-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-amber-400 mt-1.5 flex-shrink-0"></span>
                            <span>Sistem akan melakukan <strong>auto-grading instan</strong> untuk butir Pilihan Ganda dan Benar/Salah setelah jawaban dikirimkan.</span>
                        </div>
                        <div class="flex items-start gap-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-amber-400 mt-1.5 flex-shrink-0"></span>
                            <span>Timer hitung mundur akan berjalan begitu Anda menekan tombol "Mulai Kerjakan". Jawaban akan otomatis dikirimkan jika waktu habis.</span>
                        </div>
                        <div class="flex items-start gap-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-amber-400 mt-1.5 flex-shrink-0"></span>
                            <span>Nilai kelulusan terbaik Anda akan dicatat sebagai syarat kelulusan verifikasi sertifikat.</span>
                        </div>
                    </div>

                    <!-- Tombol Mulai Kuis -->
                    <div class="pt-3">
                        @if($canAttempt)
                            <form method="POST" action="{{ route('peserta.quizzes.start', [$class, $quiz]) }}">
                                @csrf
                                <button type="submit" class="w-full sm:w-auto px-8 py-3 text-sm font-bold text-white bg-gradient-to-r from-orange-500 to-amber-500 hover:from-orange-600 hover:to-amber-600 rounded-xl shadow-lg shadow-orange-500/20 transition flex items-center justify-center gap-2 font-montserrat">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span>Mulai Kerjakan Kuis Sekarang (Percobaan #{{ $attempts->count() + 1 }})</span>
                                </button>
                            </form>
                        @else
                            <div class="p-4 rounded-xl bg-slate-800 text-center border border-slate-700">
                                <span class="text-xs font-bold text-slate-400 font-montserrat">
                                    Anda telah menggunakan seluruh {{ $quiz->max_attempts }} kesempatan pengerjaan untuk kuis ini.
                                </span>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Riwayat Percobaan Kuis -->
                <div class="bg-slate-900/60 border border-slate-800/80 rounded-2xl p-6 space-y-4">
                    <div class="flex items-center justify-between">
                        <h3 class="text-base font-extrabold text-white font-montserrat flex items-center gap-2">
                            <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                            </svg>
                            Riwayat Pengerjaan ({{ $attempts->count() }} Percobaan)
                        </h3>
                    </div>

                    @if($attempts->isEmpty())
                        <div class="p-8 rounded-xl bg-slate-800/40 border border-slate-800 text-center text-xs text-slate-400 font-quicksand">
                            Anda belum pernah mengerjakan kuis ini. Tekan tombol di atas untuk memulai.
                        </div>
                    @else
                        <div class="space-y-3">
                            @foreach($attempts as $att)
                                <div class="p-4 rounded-xl bg-slate-800/40 border border-slate-700/60 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-xl {{ $att->is_passed ? 'bg-emerald-500/20 text-emerald-400 border border-emerald-500/30' : 'bg-rose-500/20 text-rose-400 border border-rose-500/30' }} flex items-center justify-center font-bold text-sm font-mono flex-shrink-0">
                                            #{{ $att->attempt_number }}
                                        </div>
                                        <div>
                                            <div class="flex items-center gap-2">
                                                <span class="text-sm font-bold text-white font-montserrat">
                                                    Skor: <span class="{{ $att->is_passed ? 'text-emerald-400' : 'text-rose-400' }}">{{ $att->total_score }} / 100</span>
                                                </span>
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold uppercase font-montserrat {{ $att->is_passed ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20' : 'bg-rose-500/10 text-rose-400 border border-rose-500/20' }}">
                                                    {{ $att->is_passed ? 'Lulus' : 'Belum Lulus' }}
                                                </span>
                                            </div>
                                            <div class="text-xs text-slate-400 font-mono mt-0.5">
                                                {{ $att->submitted_at ? \Carbon\Carbon::parse($att->submitted_at)->translatedFormat('d M Y, H:i') . ' WIB' : 'Sedang berjalan...' }}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="flex items-center gap-2">
                                        @if($att->submitted_at)
                                            <a href="{{ route('peserta.quizzes.result', [$class, $quiz, $att]) }}" class="px-3.5 py-1.5 text-xs font-semibold text-slate-200 bg-slate-800 hover:bg-slate-700 border border-slate-700 rounded-lg transition flex items-center gap-1.5 font-montserrat">
                                                <span>Pembahasan</span>
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                            </a>
                                        @else
                                            <a href="{{ route('peserta.quizzes.take', [$class, $quiz, $att]) }}" class="px-3.5 py-1.5 text-xs font-bold text-white bg-orange-500 hover:bg-orange-600 rounded-lg transition flex items-center gap-1.5 font-montserrat">
                                                <span>Lanjutkan</span>
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <!-- Kolom Kanan: Rangkuman & Informasi (1 Kolom) -->
            <div class="space-y-6">
                <div class="bg-slate-900/60 border border-slate-800/80 rounded-2xl p-6 space-y-4">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400 font-montserrat">Statistik Anda</span>
                    <div class="space-y-3">
                        <div class="flex justify-between items-center text-xs pb-2 border-b border-slate-800">
                            <span class="text-slate-400">Total Percobaan:</span>
                            <span class="font-bold text-white font-mono">{{ $attempts->count() }} / {{ $quiz->max_attempts }}</span>
                        </div>
                        <div class="flex justify-between items-center text-xs pb-2 border-b border-slate-800">
                            <span class="text-slate-400">Nilai Tertinggi:</span>
                            <span class="font-bold {{ $bestScore >= $quiz->passing_grade ? 'text-emerald-400' : 'text-amber-400' }} font-mono">
                                {{ $bestScore !== null ? $bestScore : '-' }}
                            </span>
                        </div>
                        <div class="flex justify-between items-center text-xs pb-2 border-b border-slate-800">
                            <span class="text-slate-400">Status Kuis:</span>
                            <span class="font-bold {{ $hasPassed ? 'text-emerald-400' : 'text-slate-400' }} font-montserrat">
                                {{ $hasPassed ? 'LULUS' : ($attempts->count() > 0 ? 'BELUM LULUS' : 'BELUM MENGERJAKAN') }}
                            </span>
                        </div>
                    </div>

                    <div class="pt-2">
                        <a href="{{ route('peserta.study.show', $class) }}" class="w-full py-2.5 text-xs font-semibold text-slate-300 bg-slate-800 hover:bg-slate-700 border border-slate-700 rounded-xl transition flex items-center justify-center gap-2 font-montserrat">
                            &larr; Kembali ke Ruang Belajar
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
