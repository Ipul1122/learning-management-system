<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-bold font-montserrat bg-amber-500/10 text-amber-400 border border-amber-500/20 mb-1">
                    {{ $quiz->title }} • Percobaan #{{ $attempt->attempt_number }}
                </span>
                <h1 class="text-xl sm:text-2xl font-extrabold text-white tracking-tight font-montserrat">
                    Lembar Pengerjaan Kuis
                </h1>
            </div>
            <!-- Sticky / Live Countdown Timer with Alpine.js -->
            <div x-data="quizCountdown({{ $secondsRemaining }})"
                 x-init="initTimer()"
                 class="flex items-center gap-3 px-4 py-2 rounded-2xl border transition-all duration-300"
                 :class="remainingSeconds < 300 ? 'bg-rose-500/20 border-rose-500/40 text-rose-400 animate-pulse' : 'bg-slate-900/90 border-slate-700/80 text-amber-400'">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div class="text-right">
                    <span class="text-[10px] uppercase font-bold tracking-wider font-montserrat block">Sisa Waktu</span>
                    <span class="text-lg font-extrabold font-mono tracking-wider" x-text="formatTime()"></span>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-6 space-y-6" x-data="quizFormManager()">
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

        <form id="quiz-form" x-ref="form" method="POST" action="{{ route('peserta.quizzes.submit', [$class, $quiz, $attempt]) }}" class="space-y-6">
            @csrf

            <!-- Soal List Container -->
            <div class="space-y-6">
                @foreach($questions as $index => $question)
                    <div class="bg-slate-900/60 border border-slate-800/80 rounded-2xl p-6 space-y-4">
                        <input type="hidden" name="answers[{{ $index }}][question_id]" value="{{ $question->id }}">

                        <div class="flex items-start justify-between gap-4">
                            <div class="flex items-center gap-2">
                                <span class="w-8 h-8 rounded-lg bg-gradient-to-br from-emerald-500/20 to-teal-500/20 text-emerald-400 border border-emerald-500/30 flex items-center justify-center font-bold text-xs font-mono flex-shrink-0">
                                    {{ $index + 1 }}
                                </span>
                                <span class="text-[10px] uppercase font-bold tracking-wider px-2 py-0.5 rounded-md bg-slate-800 text-slate-400 font-montserrat">
                                    {{ $question->type === 'multiple_choice' ? 'Pilihan Ganda' : ($question->type === 'true_false' ? 'Benar / Salah' : 'Uraian Singkat') }}
                                </span>
                            </div>
                            <span class="text-xs font-mono text-slate-500">Bobot: 1 Poin</span>
                        </div>

                        <!-- Konten Teks Soal -->
                        <div class="text-sm sm:text-base text-slate-100 font-medium leading-relaxed font-quicksand whitespace-pre-line pl-1">
                            {{ $question->question_text }}
                        </div>

                        <!-- Butir Pilihan Ganda / True-False -->
                        @if(in_array($question->type, ['multiple_choice', 'true_false']))
                            <div class="space-y-2.5 pt-2">
                                @php
                                    $optionLetters = ['A', 'B', 'C', 'D', 'E'];
                                @endphp
                                @foreach($question->options as $optIndex => $option)
                                    <label class="flex items-center gap-3 p-3.5 rounded-xl border border-slate-800 bg-slate-800/40 hover:bg-slate-800/70 hover:border-slate-700 cursor-pointer transition group">
                                        <input type="radio"
                                               name="answers[{{ $index }}][selected_option_id]"
                                               value="{{ $option->id }}"
                                               class="w-4 h-4 text-emerald-500 bg-slate-900 border-slate-700 focus:ring-emerald-500 focus:ring-offset-slate-900">
                                        <span class="w-6 h-6 rounded-md bg-slate-700/60 text-slate-300 group-hover:text-white group-hover:bg-slate-600 flex items-center justify-center text-xs font-bold font-mono">
                                            {{ $optionLetters[$optIndex] ?? ($optIndex + 1) }}
                                        </span>
                                        <span class="text-xs sm:text-sm text-slate-200 font-quicksand group-hover:text-white">
                                            {{ $option->option_text }}
                                        </span>
                                    </label>
                                @endforeach
                            </div>
                        @else
                            <!-- Soal Essay -->
                            <div class="pt-2">
                                <textarea name="answers[{{ $index }}][essay_answer]"
                                          rows="4"
                                          placeholder="Tuliskan jawaban Anda di sini..."
                                          class="w-full px-4 py-3 text-xs sm:text-sm rounded-xl bg-slate-800/70 border border-slate-700 text-white placeholder-slate-500 focus:ring-2 focus:ring-emerald-500 font-quicksand"></textarea>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>

            <!-- Action Submit Footer Bar -->
            <div class="sticky bottom-4 z-20 bg-slate-900/90 backdrop-blur-md border border-slate-700/80 rounded-2xl p-4 sm:p-5 shadow-2xl flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="text-xs text-slate-400 font-quicksand">
                    Pastikan Anda telah memeriksa kembali seluruh butir jawaban sebelum mengirimkan kuis ini.
                </div>

                <div class="flex items-center gap-3 w-full sm:w-auto">
                    <button type="button"
                            @click="confirmSubmit()"
                            class="w-full sm:w-auto px-8 py-3 text-xs font-bold text-white bg-gradient-to-r from-orange-500 to-amber-500 hover:from-orange-600 hover:to-amber-600 rounded-xl shadow-lg shadow-orange-500/20 transition flex items-center justify-center gap-2 font-montserrat">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span>Kirim & Selesaikan Kuis</span>
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Script Timer & SweetAlert2 Confirmation -->
    <script>
        function quizCountdown(initialSeconds) {
            return {
                remainingSeconds: initialSeconds,
                timerInterval: null,
                initTimer() {
                    this.timerInterval = setInterval(() => {
                        if (this.remainingSeconds > 0) {
                            this.remainingSeconds--;
                        } else {
                            clearInterval(this.timerInterval);
                            this.autoSubmit();
                        }
                    }, 1000);
                },
                formatTime() {
                    const minutes = Math.floor(this.remainingSeconds / 60);
                    const seconds = this.remainingSeconds % 60;
                    return `${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
                },
                autoSubmit() {
                    if (window.Swal) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Waktu Pengerjaan Habis!',
                            text: 'Jawaban Anda akan dikirimkan secara otomatis untuk proses penilaian.',
                            timer: 3000,
                            showConfirmButton: false,
                            background: '#1E1B18',
                            color: '#FFFFFF'
                        }).then(() => {
                            document.getElementById('quiz-form').submit();
                        });
                    } else {
                        alert('Waktu pengerjaan telah habis. Lembar kuis akan dikirimkan sekarang.');
                        document.getElementById('quiz-form').submit();
                    }
                }
            }
        }

        function quizFormManager() {
            return {
                confirmSubmit() {
                    if (window.Swal) {
                        Swal.fire({
                            title: 'Kirim Jawaban Kuis?',
                            text: 'Apakah Anda yakin ingin menyelesaikan kuis ini sekarang? Jawaban yang telah dikirim tidak dapat diubah lagi.',
                            icon: 'question',
                            showCancelButton: true,
                            confirmButtonText: 'Ya, Kirim Sekarang',
                            cancelButtonText: 'Periksa Kembali',
                            confirmButtonColor: '#FF6B00',
                            cancelButtonColor: '#322E2B',
                            background: '#1E1B18',
                            color: '#FFFFFF'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                this.$refs.form.submit();
                            }
                        });
                    } else {
                        if (confirm('Apakah Anda yakin ingin menyelesaikan kuis ini?')) {
                            this.$refs.form.submit();
                        }
                    }
                }
            }
        }
    </script>
</x-app-layout>
