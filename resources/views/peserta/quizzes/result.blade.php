<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div class="flex items-center gap-3">
                <a href="{{ route('peserta.quizzes.show', [$class, $quiz]) }}" class="p-2 rounded-xl bg-white hover:bg-[#FAF8F5] border border-[#EBE5DF] text-[#6E675F] hover:text-[#1E1B18] shadow-sm transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>
                <div>
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-bold font-montserrat bg-[#FFF7ED] text-[#EA580C] border border-[#FED7AA] mb-1">
                        {{ $quiz->title }} • Hasil Percobaan #{{ $attempt->attempt_number }}
                    </span>
                    <h1 class="text-2xl font-extrabold text-[#1E1B18] tracking-tight font-montserrat">
                        Lembar Hasil & Pembahasan Kuis
                    </h1>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('peserta.study.show', $class) }}" class="px-4 py-2 text-xs font-bold text-[#1E1B18] bg-white hover:bg-[#FAF8F5] border border-[#EBE5DF] rounded-xl shadow-sm transition flex items-center gap-2 font-montserrat">
                    Ruang Belajar &rarr;
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6 space-y-6">
        <!-- Notifikasi Berhasil Submit -->
        @if(session('success'))
            <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-medium flex items-center gap-3 shadow-sm">
                <svg class="w-5 h-5 flex-shrink-0 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <!-- Card Rangkuman Nilai & Evaluasi Instan -->
        <div class="bg-gradient-to-br from-[#1E1B18] via-[#2D2824] to-[#1E1B18] border border-[#3E3834] rounded-3xl p-6 sm:p-8 text-white shadow-xl relative overflow-hidden">
            <div class="absolute -right-10 -bottom-10 w-60 h-60 {{ $attempt->is_passed ? 'bg-emerald-500/20' : 'bg-rose-500/15' }} rounded-full blur-3xl pointer-events-none"></div>

            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-6 relative z-10">
                <div class="space-y-2">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold font-montserrat {{ $attempt->is_passed ? 'bg-emerald-500/20 text-emerald-300 border border-emerald-500/30' : 'bg-rose-500/20 text-rose-300 border border-rose-500/30' }}">
                        <span class="w-2 h-2 rounded-full {{ $attempt->is_passed ? 'bg-emerald-400' : 'bg-rose-400' }}"></span>
                        {{ $attempt->is_passed ? 'LULUS EVALUASI KUIS' : 'BELUM MENCAPAI PASSING GRADE' }}
                    </span>
                    <h2 class="text-2xl sm:text-3xl font-montserrat font-extrabold text-white">
                        {{ $attempt->is_passed ? 'Selamat! Pemahaman Anda Sangat Baik' : 'Tetap Semangat! Tinjau Pembahasan di Bawah' }}
                    </h2>
                    <p class="text-xs text-[#A8A29E] font-quicksand">
                        Waktu Pengiriman: {{ $attempt->submitted_at ? \Carbon\Carbon::parse($attempt->submitted_at)->translatedFormat('d F Y, H:i') . ' WIB' : '-' }}
                        • Passing Grade Minimal: <strong class="text-amber-400">{{ $quiz->passing_grade }}%</strong>
                    </p>
                </div>

                <!-- Nilai Skor Besar -->
                <div class="text-left sm:text-right bg-white/10 backdrop-blur-sm p-5 rounded-2xl border border-white/15 flex-shrink-0">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-[#A8A29E] font-montserrat block">Skor Akhir Pengerjaan</span>
                    <div class="text-4xl sm:text-5xl font-extrabold font-montserrat tracking-tight {{ $attempt->is_passed ? 'text-emerald-400' : 'text-rose-400' }} mt-1">
                        {{ $attempt->total_score }} <span class="text-lg text-white/60 font-normal">/ 100</span>
                    </div>
                    <span class="text-[11px] text-[#D6D3D1] font-mono block mt-1">
                        Status KKM: {{ $attempt->is_passed ? 'Terpenuhi (>= '.$quiz->passing_grade.'%)' : 'Belum Terpenuhi (< '.$quiz->passing_grade.'%)' }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Review & Pembahasan Jawaban -->
        <div class="space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-extrabold text-[#1E1B18] font-montserrat flex items-center gap-2">
                        <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Pembahasan Kunci Jawaban & Evaluasi Butir Soal
                    </h3>
                    <p class="text-xs text-[#6E675F] mt-0.5 font-quicksand">Tinjau kembali jawaban Anda dan bandingkan dengan kunci jawaban yang benar.</p>
                </div>
            </div>

            <div class="space-y-5">
                @foreach($attempt->answers as $index => $answer)
                    @php
                        $question = $answer->question;
                        $selectedOption = $answer->selectedOption;
                        $isCorrect = (bool) $answer->is_correct;
                    @endphp
                    <div class="bg-white border {{ $isCorrect ? 'border-emerald-200 shadow-emerald-500/5' : 'border-rose-200 shadow-rose-500/5' }} rounded-2xl p-6 sm:p-7 space-y-4 shadow-sm">
                        <div class="flex items-start justify-between gap-4">
                            <div class="flex items-center gap-2">
                                <span class="w-8 h-8 rounded-lg {{ $isCorrect ? 'bg-emerald-50 text-emerald-600 border border-emerald-200' : 'bg-rose-50 text-rose-600 border border-rose-200' }} flex items-center justify-center font-bold text-xs font-mono shadow-sm">
                                    {{ $index + 1 }}
                                </span>
                                <span class="text-[10px] uppercase font-bold tracking-wider px-2.5 py-0.5 rounded-md bg-[#FAF8F5] border border-[#EBE5DF] text-[#6E675F] font-montserrat">
                                    {{ $question->type === 'multiple_choice' ? 'Pilihan Ganda' : ($question->type === 'true_false' ? 'Benar / Salah' : 'Uraian Singkat') }}
                                </span>
                            </div>

                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold font-montserrat {{ $isCorrect ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-rose-50 text-rose-700 border border-rose-200' }}">
                                @if($isCorrect)
                                    <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    <span>Benar (+1.00 Poin)</span>
                                @else
                                    <svg class="w-3.5 h-3.5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                    <span>Salah (0.00 Poin)</span>
                                @endif
                            </span>
                        </div>

                        <!-- Teks Soal -->
                        <div class="text-sm sm:text-base text-[#1E1B18] font-semibold leading-relaxed font-quicksand whitespace-pre-line pl-1">
                            {{ $question->question_text }}
                        </div>

                        <!-- Opsi Jawaban -->
                        @if(in_array($question->type, ['multiple_choice', 'true_false']))
                            <div class="space-y-2 pt-2">
                                @php
                                    $optionLetters = ['A', 'B', 'C', 'D', 'E'];
                                @endphp
                                @foreach($question->options as $optIndex => $option)
                                    @php
                                        $isSelected = $selectedOption && $selectedOption->id === $option->id;
                                        $isThisCorrect = (bool) $option->is_correct;
                                    @endphp
                                    <div class="flex items-center justify-between p-3 rounded-xl border text-xs sm:text-sm font-quicksand transition
                                        @if($isThisCorrect)
                                            border-emerald-300 bg-emerald-50/80 text-emerald-950 font-medium
                                        @elseif($isSelected && !$isThisCorrect)
                                            border-rose-300 bg-rose-50/80 text-rose-950
                                        @else
                                            border-[#EBE5DF] bg-[#FAF8F5] text-[#6E675F]
                                        @endif">
                                        <div class="flex items-center gap-3">
                                            <span class="w-6 h-6 rounded-md flex items-center justify-center text-xs font-bold font-mono shadow-xs
                                                @if($isThisCorrect) bg-emerald-600 text-white
                                                @elseif($isSelected) bg-rose-500 text-white
                                                @else bg-white border border-[#EBE5DF] text-[#6E675F] @endif">
                                                {{ $optionLetters[$optIndex] ?? ($optIndex + 1) }}
                                            </span>
                                            <span>{{ $option->option_text }}</span>
                                        </div>

                                        <div class="flex items-center gap-2 font-montserrat text-[11px] font-bold">
                                            @if($isSelected && $isThisCorrect)
                                                <span class="text-emerald-700">Pilihan Anda (Benar)</span>
                                            @elseif($isSelected && !$isThisCorrect)
                                                <span class="text-rose-700">Pilihan Anda (Salah)</span>
                                            @elseif($isThisCorrect)
                                                <span class="text-emerald-700">Kunci Jawaban</span>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <!-- Jawaban Essay -->
                            <div class="p-3.5 rounded-xl bg-[#FAF8F5] border border-[#EBE5DF] text-xs text-[#1E1B18] font-quicksand">
                                <span class="font-bold text-[#A8A29E] block mb-1">Jawaban Anda:</span>
                                {{ $answer->essay_answer ?: '(Tidak ada jawaban uraian)' }}
                            </div>
                        @endif

                        <!-- Pembahasan & Penjelasan Soal -->
                        @if(!empty($question->explanation))
                            <div class="p-4 rounded-xl bg-[#FFF7ED] border border-[#FED7AA] space-y-1 mt-3">
                                <div class="flex items-center gap-1.5 text-xs font-bold text-[#EA580C] font-montserrat">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span>Pembahasan & Teori Terkait:</span>
                                </div>
                                <p class="text-xs text-[#7C2D12] font-quicksand leading-relaxed">
                                    {{ $question->explanation }}
                                </p>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>

            <!-- Footer Actions -->
            <div class="pt-4 flex flex-col sm:flex-row items-center justify-between gap-4">
                <a href="{{ route('peserta.quizzes.show', [$class, $quiz]) }}" class="w-full sm:w-auto px-6 py-2.5 text-xs font-semibold text-[#1E1B18] bg-white hover:bg-[#FAF8F5] border border-[#EBE5DF] rounded-xl shadow-sm transition flex items-center justify-center gap-2 font-montserrat">
                    &larr; Kembali ke Riwayat Kuis
                </a>

                <a href="{{ route('peserta.study.show', $class) }}" class="w-full sm:w-auto px-6 py-2.5 text-xs font-bold text-white bg-gradient-to-r from-[#FF6B00] to-[#E11D48] hover:from-[#EA580C] hover:to-[#DC2626] rounded-xl shadow-md shadow-orange-500/10 transition flex items-center justify-center gap-2 font-montserrat">
                    Lanjut Belajar di Ruang Kelas &rarr;
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
