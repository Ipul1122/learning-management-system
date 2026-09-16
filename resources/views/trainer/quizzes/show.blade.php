<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div class="flex items-center gap-3">
                <a href="{{ route('trainer.quizzes.index') }}" class="p-2.5 rounded-xl bg-white hover:bg-[#FAF8F5] border border-[#EBE5DF] text-[#1E1B18] shadow-sm transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>
                <div>
                    <div class="flex items-center gap-2">
                        <span class="px-2.5 py-0.5 text-[10px] font-bold rounded-lg uppercase tracking-wider font-montserrat
                            {{ $quiz->class->type === 'online' ? 'bg-sky-50 text-sky-700 border border-sky-200' : ($quiz->class->type === 'offline' ? 'bg-amber-50 text-amber-700 border border-amber-200' : 'bg-purple-50 text-purple-700 border border-purple-200') }}">
                            {{ $quiz->class->title }}
                        </span>
                        <span class="text-xs text-[#A8A29E]">•</span>
                        <span class="text-xs text-[#6E675F] font-medium">Cabang {{ $branch->name }}</span>
                    </div>
                    <h1 class="text-2xl font-extrabold text-[#1E1B18] tracking-tight mt-1 font-montserrat">{{ $quiz->title }}</h1>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('trainer.quizzes.edit', $quiz) }}" class="px-4 py-2.5 text-xs font-bold text-[#1E1B18] bg-white hover:bg-[#FAF8F5] rounded-xl border border-[#EBE5DF] shadow-sm transition flex items-center gap-2 font-montserrat">
                    <svg class="w-4 h-4 text-[#FF6B00]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Edit Kuis
                </a>
                <form action="{{ route('trainer.quizzes.destroy', $quiz) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus paket kuis ini?');" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-3 py-2.5 text-xs font-bold text-rose-600 bg-rose-50 hover:bg-rose-100 border border-rose-200 rounded-xl transition shadow-sm font-montserrat">
                        Hapus
                    </button>
                </form>
            </div>
        </div>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto space-y-6">
        <!-- Quiz Specs Card -->
        <div class="bg-white border border-[#EBE5DF] rounded-2xl p-6 shadow-sm">
            <h2 class="text-xs font-bold text-[#A8A29E] uppercase tracking-wider mb-4 font-montserrat">Spesifikasi & Konfigurasi Evaluasi</h2>
            
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">
                <div class="bg-[#FAF8F5] border border-[#EBE5DF] rounded-xl p-3.5">
                    <span class="text-[10px] text-[#A8A29E] uppercase tracking-wider block font-montserrat">Durasi Pengerjaan</span>
                    <span class="text-base font-extrabold text-[#1E1B18] flex items-center gap-1.5 mt-0.5 font-montserrat">
                        <svg class="w-4 h-4 text-[#EA580C]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        {{ $quiz->time_limit_minutes }} Menit
                    </span>
                </div>

                <div class="bg-[#FAF8F5] border border-[#EBE5DF] rounded-xl p-3.5">
                    <span class="text-[10px] text-[#A8A29E] uppercase tracking-wider block font-montserrat">Passing Grade</span>
                    <span class="text-base font-extrabold text-[#EA580C] mt-0.5 block font-montserrat">
                        {{ (float) $quiz->passing_grade }}%
                    </span>
                </div>

                <div class="bg-[#FAF8F5] border border-[#EBE5DF] rounded-xl p-3.5">
                    <span class="text-[10px] text-[#A8A29E] uppercase tracking-wider block font-montserrat">Total Butir Soal</span>
                    <span class="text-base font-extrabold text-[#1E1B18] mt-0.5 block font-montserrat">
                        {{ $quiz->questions->count() }} Butir
                    </span>
                </div>

                <div class="bg-[#FAF8F5] border border-[#EBE5DF] rounded-xl p-3.5">
                    <span class="text-[10px] text-[#A8A29E] uppercase tracking-wider block font-montserrat">Maks Percobaan</span>
                    <span class="text-base font-extrabold text-[#1E1B18] mt-0.5 block font-montserrat">
                        {{ $quiz->max_attempts }} Kali
                    </span>
                </div>

                <div class="bg-[#FAF8F5] border border-[#EBE5DF] rounded-xl p-3.5">
                    <span class="text-[10px] text-[#A8A29E] uppercase tracking-wider block font-montserrat">Soal Diacak</span>
                    <span class="text-base font-extrabold mt-0.5 block font-montserrat {{ $quiz->is_randomized ? 'text-emerald-600' : 'text-[#6E675F]' }}">
                        {{ $quiz->is_randomized ? 'Ya (Aktif)' : 'Tidak' }}
                    </span>
                </div>

                <div class="bg-[#FAF8F5] border border-[#EBE5DF] rounded-xl p-3.5">
                    <span class="text-[10px] text-[#A8A29E] uppercase tracking-wider block font-montserrat">Status Paket</span>
                    <span class="text-base font-extrabold mt-0.5 block font-montserrat {{ $quiz->is_active ? 'text-emerald-600' : 'text-slate-400' }}">
                        {{ $quiz->is_active ? 'Dipublikasikan' : 'Draf' }}
                    </span>
                </div>
            </div>

            @if($quiz->description)
                <div class="mt-4 pt-4 border-t border-[#EBE5DF] text-xs text-[#6E675F] font-quicksand leading-relaxed">
                    {{ $quiz->description }}
                </div>
            @endif
        </div>

        <!-- Questions Breakdown -->
        <div class="space-y-4">
            <div class="flex items-center justify-between">
                <h2 class="text-base font-bold text-[#1E1B18] font-montserrat flex items-center gap-2">
                    <svg class="w-5 h-5 text-[#EA580C]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    Daftar Butir Soal dalam Paket ({{ $quiz->questions->count() }})
                </h2>
            </div>

            <div class="space-y-4">
                @foreach($quiz->questions as $index => $question)
                    <div class="bg-white border border-[#EBE5DF] rounded-2xl p-5 sm:p-6 space-y-3 shadow-sm">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2.5">
                                <span class="w-7 h-7 rounded-xl bg-[#FFF7ED] border border-[#FED7AA] text-[#EA580C] text-xs font-bold font-mono flex items-center justify-center shadow-xs">
                                    {{ $index + 1 }}
                                </span>
                                <span class="px-2.5 py-0.5 text-[10px] font-bold rounded-lg border font-montserrat
                                    {{ $question->question_type === 'multiple_choice' ? 'bg-sky-50 text-sky-700 border-sky-200' : ($question->question_type === 'true_false' ? 'bg-purple-50 text-purple-700 border-purple-200' : 'bg-emerald-50 text-emerald-700 border-emerald-200') }}">
                                    {{ $question->question_type === 'multiple_choice' ? 'Pilihan Ganda' : ($question->question_type === 'true_false' ? 'Benar / Salah' : 'Esai') }}
                                </span>
                            </div>
                            <span class="text-xs text-[#A8A29E] font-mono">Bobot: {{ $question->score_weight }} Poin</span>
                        </div>

                        <!-- Question Text -->
                        <div class="text-sm font-semibold text-[#1E1B18] font-quicksand">
                            {{ $question->question_text }}
                        </div>

                        <!-- Options (if MC / TF) -->
                        @if($question->options->isNotEmpty())
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 pt-2">
                                @foreach($question->options as $opt)
                                    <div class="px-3.5 py-2.5 rounded-xl text-xs flex items-center justify-between font-quicksand
                                        {{ $opt->is_correct ? 'bg-emerald-50 border border-emerald-200 text-emerald-900 font-semibold' : 'bg-[#FAF8F5] border border-[#EBE5DF] text-[#6E675F]' }}">
                                        <div class="flex items-center gap-2">
                                            <span class="w-5 h-5 rounded-md border flex items-center justify-center text-[10px] font-bold font-mono
                                                {{ $opt->is_correct ? 'bg-emerald-600 border-emerald-600 text-white' : 'bg-white border-[#EBE5DF] text-[#6E675F]' }}">
                                                {{ chr(64 + $loop->iteration) }}
                                            </span>
                                            <span>{{ $opt->option_text }}</span>
                                        </div>
                                        @if($opt->is_correct)
                                            <span class="text-[10px] font-bold bg-emerald-100 text-emerald-800 px-2 py-0.5 rounded-md font-montserrat">
                                                Kunci Jawaban
                                            </span>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="p-3 rounded-xl bg-[#FAF8F5] border border-[#EBE5DF] text-xs text-[#6E675F] italic font-quicksand">
                                Soal tipe esai memerlukan penilaian jawaban mandiri / manual oleh instruktur setelah peserta selesai mengerjakan.
                            </div>
                        @endif

                        <!-- Explanation if any -->
                        @if($question->explanation)
                            <div class="bg-[#FFF7ED] border border-[#FED7AA] rounded-xl p-3 text-xs text-[#7C2D12] font-quicksand">
                                <span class="font-bold text-[#EA580C] block mb-0.5 font-montserrat">Penjelasan / Pembahasan:</span>
                                {{ $question->explanation }}
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>
