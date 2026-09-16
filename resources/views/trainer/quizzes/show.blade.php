<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div class="flex items-center gap-3">
                <a href="{{ route('trainer.quizzes.index') }}" class="p-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-400 hover:text-white transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>
                <div>
                    <div class="flex items-center gap-2">
                        <span class="px-2.5 py-0.5 text-[10px] font-bold rounded-lg uppercase tracking-wider
                            {{ $quiz->class->type === 'online' ? 'bg-sky-500/10 text-sky-400 border border-sky-500/20' : ($quiz->class->type === 'offline' ? 'bg-amber-500/10 text-amber-400 border border-amber-500/20' : 'bg-purple-500/10 text-purple-400 border border-purple-500/20') }}">
                            {{ $quiz->class->title }}
                        </span>
                        <span class="text-xs text-slate-500">•</span>
                        <span class="text-xs text-slate-400">Cabang {{ $branch->name }}</span>
                    </div>
                    <h1 class="text-2xl font-extrabold text-white tracking-tight mt-1">{{ $quiz->title }}</h1>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('trainer.quizzes.edit', $quiz) }}" class="px-4 py-2 text-xs font-semibold text-slate-200 bg-slate-800 hover:bg-slate-700 rounded-xl border border-slate-700 transition flex items-center gap-2">
                    <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Edit Kuis
                </a>
                <form action="{{ route('trainer.quizzes.destroy', $quiz) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus paket kuis ini?');" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-3 py-2 text-xs font-semibold text-rose-400 bg-rose-500/10 hover:bg-rose-500/20 border border-rose-500/20 rounded-xl transition">
                        Hapus
                    </button>
                </form>
            </div>
        </div>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto space-y-6">
        <!-- Quiz Specs Card -->
        <div class="bg-slate-900/60 border border-slate-800/80 rounded-2xl p-6">
            <h2 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-4">Spesifikasi & Konfigurasi Evaluasi</h2>
            
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">
                <div class="bg-slate-950/40 border border-slate-800/60 rounded-xl p-3">
                    <span class="text-[10px] text-slate-500 uppercase tracking-wider block">Durasi Pengerjaan</span>
                    <span class="text-base font-bold text-white flex items-center gap-1 mt-0.5">
                        <svg class="w-4 h-4 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        {{ $quiz->time_limit_minutes }} Menit
                    </span>
                </div>

                <div class="bg-slate-950/40 border border-slate-800/60 rounded-xl p-3">
                    <span class="text-[10px] text-slate-500 uppercase tracking-wider block">Passing Grade</span>
                    <span class="text-base font-bold text-emerald-400 mt-0.5 block">
                        {{ (float) $quiz->passing_grade }}%
                    </span>
                </div>

                <div class="bg-slate-950/40 border border-slate-800/60 rounded-xl p-3">
                    <span class="text-[10px] text-slate-500 uppercase tracking-wider block">Total Butir Soal</span>
                    <span class="text-base font-bold text-white mt-0.5 block">
                        {{ $quiz->questions->count() }} Butir
                    </span>
                </div>

                <div class="bg-slate-950/40 border border-slate-800/60 rounded-xl p-3">
                    <span class="text-[10px] text-slate-500 uppercase tracking-wider block">Total Bobot Skor</span>
                    <span class="text-base font-bold text-amber-400 mt-0.5 block">
                        {{ $quiz->totalScoreWeight() }} Poin
                    </span>
                </div>

                <div class="bg-slate-950/40 border border-slate-800/60 rounded-xl p-3">
                    <span class="text-[10px] text-slate-500 uppercase tracking-wider block">Urutan Soal</span>
                    <span class="text-sm font-semibold {{ $quiz->is_randomized ? 'text-sky-400' : 'text-slate-300' }} mt-0.5 block">
                        {{ $quiz->is_randomized ? 'Acak (Random)' : 'Sesuai Urutan' }}
                    </span>
                </div>

                <div class="bg-slate-950/40 border border-slate-800/60 rounded-xl p-3">
                    <span class="text-[10px] text-slate-500 uppercase tracking-wider block">Batas Percobaan</span>
                    <span class="text-sm font-semibold text-slate-300 mt-0.5 block">
                        {{ $quiz->max_attempts }}x Percobaan
                    </span>
                </div>
            </div>

            @if($quiz->description)
                <div class="mt-4 pt-4 border-t border-slate-800/80">
                    <span class="text-[10px] text-slate-500 uppercase tracking-wider block mb-1">Instruksi Pengerjaan:</span>
                    <p class="text-xs text-slate-300 whitespace-pre-line">{{ $quiz->description }}</p>
                </div>
            @endif
        </div>

        <!-- Questions Breakdown -->
        <div class="space-y-4">
            <div class="flex items-center justify-between">
                <h2 class="text-base font-bold text-white flex items-center gap-2">
                    <svg class="w-5 h-5 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    Daftar Butir Soal dalam Paket ({{ $quiz->questions->count() }})
                </h2>
            </div>

            <div class="space-y-4">
                @foreach($quiz->questions as $index => $question)
                    <div class="bg-slate-900/60 border border-slate-800/80 rounded-2xl p-5 space-y-3">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2.5">
                                <span class="w-7 h-7 rounded-xl bg-orange-500/10 border border-orange-500/20 text-orange-400 text-xs font-bold flex items-center justify-center">
                                    {{ $index + 1 }}
                                </span>
                                <span class="px-2 py-0.5 text-[10px] font-bold rounded-lg border
                                    {{ $question->question_type === 'multiple_choice' ? 'bg-blue-500/10 text-blue-400 border-blue-500/20' : ($question->question_type === 'true_false' ? 'bg-purple-500/10 text-purple-400 border-purple-500/20' : 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20') }}">
                                    {{ $question->question_type === 'multiple_choice' ? 'Pilihan Ganda' : ($question->question_type === 'true_false' ? 'Benar / Salah' : 'Esai') }}
                                </span>
                            </div>
                            <span class="text-xs text-slate-500">Bobot: {{ $question->score_weight }} Poin</span>
                        </div>

                        <!-- Question Text -->
                        <div class="text-sm font-semibold text-white">
                            {{ $question->question_text }}
                        </div>

                        <!-- Options (if MC / TF) -->
                        @if($question->options->isNotEmpty())
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 pt-2">
                                @foreach($question->options as $opt)
                                    <div class="px-3 py-2 rounded-xl text-xs flex items-center justify-between
                                        {{ $opt->is_correct ? 'bg-emerald-500/10 border border-emerald-500/30 text-emerald-300 font-semibold' : 'bg-slate-950/40 border border-slate-800/60 text-slate-400' }}">
                                        <div class="flex items-center gap-2">
                                            <span class="w-4 h-4 rounded-full border flex items-center justify-center text-[10px]
                                                {{ $opt->is_correct ? 'border-emerald-400 text-emerald-400' : 'border-slate-600 text-slate-500' }}">
                                                {{ chr(64 + $loop->iteration) }}
                                            </span>
                                            <span>{{ $opt->option_text }}</span>
                                        </div>
                                        @if($opt->is_correct)
                                            <span class="text-[10px] font-bold bg-emerald-500/20 text-emerald-400 px-2 py-0.5 rounded-md">
                                                Kunci Jawaban
                                            </span>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="p-3 rounded-xl bg-slate-950/40 border border-slate-800/60 text-xs text-slate-400 italic">
                                Soal tipe esai memerlukan penilaian jawaban mandiri / manual oleh instruktur setelah peserta selesai mengerjakan.
                            </div>
                        @endif

                        <!-- Explanation if any -->
                        @if($question->explanation)
                            <div class="bg-slate-950/30 border border-slate-800/40 rounded-xl p-3 text-xs text-slate-400">
                                <span class="font-bold text-slate-300 block mb-0.5">Penjelasan / Pembahasan:</span>
                                {{ $question->explanation }}
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>
