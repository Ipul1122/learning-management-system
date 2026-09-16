<x-app-layout>
    <div class="py-8" x-data="questionFormManager()">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Breadcrumb -->
            <div>
                <a href="{{ route('trainer.questions.index') }}" class="inline-flex items-center gap-1.5 text-xs font-montserrat font-bold text-[#6E675F] hover:text-[#FF6B00] transition mb-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    <span>Kembali ke Bank Soal</span>
                </a>
                <h1 class="font-montserrat font-extrabold text-2xl sm:text-3xl text-[#1E1B18] tracking-tight">
                    Tambah Butir Soal Baru
                </h1>
                <p class="font-quicksand text-xs text-[#6E675F] mt-1">
                    Susun butir pertanyaan untuk bank soal cabang <strong class="text-[#1E1B18]">{{ $branch->name }}</strong> dengan sistem bobot setara (sama rata).
                </p>
            </div>

            <!-- Form Card -->
            <div class="bg-white rounded-3xl border border-[#EBE5DF] shadow-sm overflow-hidden">
                <form method="POST" action="{{ route('trainer.questions.store') }}" class="p-6 sm:p-8 space-y-6">
                    @csrf

                    <!-- Tipe Soal -->
                    <div>
                        <label class="block font-montserrat font-bold text-xs uppercase tracking-wider text-[#1E1B18] mb-2">
                            Pilih Format / Tipe Soal <span class="text-[#DC2626]">*</span>
                        </label>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                            <label class="relative flex items-center p-3.5 rounded-2xl border cursor-pointer transition"
                                   :class="questionType === 'multiple_choice' ? 'border-[#FF6B00] bg-[#FFF7ED]' : 'border-[#EBE5DF] bg-[#FAF8F5] hover:bg-white'">
                                <input type="radio" name="question_type" value="multiple_choice" x-model="questionType" @change="handleTypeChange('multiple_choice')" class="sr-only">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-xl flex items-center justify-center font-bold text-sm"
                                         :class="questionType === 'multiple_choice' ? 'bg-[#FF6B00] text-white' : 'bg-white text-[#6E675F] border border-[#EBE5DF]'">
                                        A
                                    </div>
                                    <div>
                                        <div class="font-montserrat font-bold text-xs text-[#1E1B18]">Pilihan Ganda</div>
                                        <div class="text-[11px] text-[#6E675F]">Opsi A, B, C, D, dst</div>
                                    </div>
                                </div>
                            </label>

                            <label class="relative flex items-center p-3.5 rounded-2xl border cursor-pointer transition"
                                   :class="questionType === 'true_false' ? 'border-[#0284C7] bg-[#F0F9FF]' : 'border-[#EBE5DF] bg-[#FAF8F5] hover:bg-white'">
                                <input type="radio" name="question_type" value="true_false" x-model="questionType" @change="handleTypeChange('true_false')" class="sr-only">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-xl flex items-center justify-center font-bold text-sm"
                                         :class="questionType === 'true_false' ? 'bg-[#0284C7] text-white' : 'bg-white text-[#6E675F] border border-[#EBE5DF]'">
                                        ✓
                                    </div>
                                    <div>
                                        <div class="font-montserrat font-bold text-xs text-[#1E1B18]">Benar / Salah</div>
                                        <div class="text-[11px] text-[#6E675F]">2 Opsi Pernyataan</div>
                                    </div>
                                </div>
                            </label>

                            <label class="relative flex items-center p-3.5 rounded-2xl border cursor-pointer transition"
                                   :class="questionType === 'essay' ? 'border-[#10B981] bg-[#ECFDF5]' : 'border-[#EBE5DF] bg-[#FAF8F5] hover:bg-white'">
                                <input type="radio" name="question_type" value="essay" x-model="questionType" @change="handleTypeChange('essay')" class="sr-only">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-xl flex items-center justify-center font-bold text-sm"
                                         :class="questionType === 'essay' ? 'bg-[#10B981] text-white' : 'bg-white text-[#6E675F] border border-[#EBE5DF]'">
                                        ✎
                                    </div>
                                    <div>
                                        <div class="font-montserrat font-bold text-xs text-[#1E1B18]">Esai Singkat</div>
                                        <div class="text-[11px] text-[#6E675F]">Koreksi & Skor Manual</div>
                                    </div>
                                </div>
                            </label>
                        </div>
                        @error('question_type')
                            <p class="text-xs text-[#DC2626] mt-1.5 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Teks Pertanyaan -->
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label for="question_text" class="block font-montserrat font-bold text-xs uppercase tracking-wider text-[#1E1B18]">
                                Teks Butir Pertanyaan <span class="text-[#DC2626]">*</span>
                            </label>
                            <span class="text-[11px] font-mono text-[#6E675F] bg-[#FAF8F5] px-2 py-0.5 rounded-lg border border-[#EBE5DF]">
                                Bobot: 1 Poin Setara
                            </span>
                        </div>
                        <textarea id="question_text"
                                  name="question_text"
                                  rows="4"
                                  required
                                  placeholder="Tuliskan pertanyaan atau deskripsi soal secara jelas di sini..."
                                  class="w-full px-4 py-3 rounded-2xl border border-[#EBE5DF] bg-[#FAF8F5] text-sm text-[#1E1B18] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#FF6B00] font-quicksand transition">{{ old('question_text') }}</textarea>
                        @error('question_text')
                            <p class="text-xs text-[#DC2626] mt-1 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Dynamic Options (Pilihan Ganda & Benar/Salah) -->
                    <div x-show="questionType !== 'essay'" class="space-y-3">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="font-montserrat font-bold text-xs uppercase tracking-wider text-[#1E1B18]">
                                    Pilihan Jawaban & Kunci Benar <span class="text-[#DC2626]">*</span>
                                </h3>
                                <p class="text-[11px] text-[#6E675F]">Centang / pilih tombol radio pada opsi yang merupakan kunci jawaban yang benar.</p>
                            </div>

                            <button type="button"
                                    x-show="questionType === 'multiple_choice' && options.length < 6"
                                    @click="addOption()"
                                    class="inline-flex items-center gap-1 text-xs font-montserrat font-bold text-[#FF6B00] hover:text-[#E11D48] transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                                <span>Tambah Opsi</span>
                            </button>
                        </div>

                        <div class="space-y-2.5">
                            <template x-for="(option, index) in options" :key="index">
                                <div class="flex items-center gap-3 p-3 rounded-2xl border transition"
                                     :class="option.is_correct ? 'bg-[#ECFDF5] border-[#A7F3D0]' : 'bg-[#FAF8F5] border-[#EBE5DF]'">
                                    <!-- Radio / Checkbox for Correct Answer -->
                                    <label class="flex items-center gap-2 cursor-pointer select-none">
                                        <input type="radio"
                                               name="correct_selector"
                                               :checked="option.is_correct"
                                               @change="setCorrect(index)"
                                               class="w-4 h-4 text-[#10B981] focus:ring-[#10B981] border-[#EBE5DF]">
                                        <span class="w-6 h-6 rounded-lg bg-white border border-[#EBE5DF] flex items-center justify-center font-mono font-bold text-xs text-[#1E1B18]"
                                              x-text="String.fromCharCode(65 + index)"></span>
                                    </label>

                                    <!-- Option Text Input -->
                                    <input type="text"
                                           :name="`options[${index}][option_text]`"
                                           x-model="option.option_text"
                                           :placeholder="`Teks pilihan ${String.fromCharCode(65 + index)}...`"
                                           required
                                           class="flex-1 px-3.5 py-2 rounded-xl border border-[#EBE5DF] bg-white text-sm text-[#1E1B18] focus:outline-none focus:ring-2 focus:ring-[#FF6B00] font-quicksand transition">

                                    <!-- Hidden is_correct Input -->
                                    <input type="hidden"
                                           :name="`options[${index}][is_correct]`"
                                           :value="option.is_correct ? '1' : '0'">

                                    <!-- Remove Option Button (Only if > 2 options in multiple choice) -->
                                    <button type="button"
                                            x-show="questionType === 'multiple_choice' && options.length > 2"
                                            @click="removeOption(index)"
                                            class="text-[#6E675F] hover:text-[#DC2626] p-1.5 rounded-lg hover:bg-white transition"
                                            title="Hapus Opsi">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                            </template>
                        </div>
                        @error('options')
                            <p class="text-xs text-[#DC2626] mt-1 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Notice for Essay -->
                    <div x-show="questionType === 'essay'" class="p-4 rounded-2xl bg-[#ECFDF5] border border-[#A7F3D0] text-xs text-[#065F46] space-y-1">
                        <div class="font-montserrat font-bold flex items-center gap-1.5">
                            <span>ℹ️</span>
                            <span>Format Soal Esai Singkat</span>
                        </div>
                        <p class="font-quicksand">Peserta akan menjawab dengan kolom teks bebas. Trainer dapat memeriksa dan memberikan penilaian manual skor setelah peserta mengumpulkan kuis.</p>
                    </div>

                    <!-- Pembahasan / Kunci Jawaban -->
                    <div>
                        <label for="explanation" class="block font-montserrat font-bold text-xs uppercase tracking-wider text-[#1E1B18] mb-1.5">
                            Kunci & Penjelasan Pembahasan (Opsional)
                        </label>
                        <textarea id="explanation"
                                  name="explanation"
                                  rows="3"
                                  placeholder="Tuliskan alasan kunci jawaban atau materi pendukung agar peserta dapat mempelajari pembahasan setelah kuis berakhir..."
                                  class="w-full px-4 py-3 rounded-2xl border border-[#EBE5DF] bg-[#FAF8F5] text-sm text-[#1E1B18] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#FF6B00] font-quicksand transition">{{ old('explanation') }}</textarea>
                        @error('explanation')
                            <p class="text-xs text-[#DC2626] mt-1 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Action Buttons -->
                    <div class="pt-4 border-t border-[#EBE5DF] flex items-center justify-end gap-3">
                        <a href="{{ route('trainer.questions.index') }}"
                           class="px-6 py-2.5 rounded-xl font-montserrat font-bold text-xs text-[#6E675F] hover:text-[#1E1B18] hover:bg-[#FAF8F5] border border-[#EBE5DF] transition">
                            Batal
                        </a>
                        <button type="submit"
                                class="px-6 py-2.5 rounded-xl font-montserrat font-bold text-xs text-white bg-gradient-to-r from-[#FF6B00] to-[#E11D48] hover:from-[#EA580C] hover:to-[#DC2626] shadow-md hover:shadow-lg transition transform hover:-translate-y-0.5">
                            Simpan ke Bank Soal
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>

    @push('scripts')
        <script>
            function questionFormManager() {
                return {
                    questionType: '{{ old('question_type', 'multiple_choice') }}',
                    options: [
                        { option_text: '', is_correct: true },
                        { option_text: '', is_correct: false },
                        { option_text: '', is_correct: false },
                        { option_text: '', is_correct: false }
                    ],
                    handleTypeChange(type) {
                        this.questionType = type;
                        if (type === 'true_false') {
                            this.options = [
                                { option_text: 'Benar', is_correct: true },
                                { option_text: 'Salah', is_correct: false }
                            ];
                        } else if (type === 'multiple_choice') {
                            this.options = [
                                { option_text: '', is_correct: true },
                                { option_text: '', is_correct: false },
                                { option_text: '', is_correct: false },
                                { option_text: '', is_correct: false }
                            ];
                        } else {
                            this.options = [];
                        }
                    },
                    setCorrect(index) {
                        this.options.forEach((opt, idx) => {
                            opt.is_correct = (idx === index);
                        });
                    },
                    addOption() {
                        if (this.options.length < 6) {
                            this.options.push({ option_text: '', is_correct: false });
                        }
                    },
                    removeOption(index) {
                        if (this.options.length > 2) {
                            const removedWasCorrect = this.options[index].is_correct;
                            this.options.splice(index, 1);
                            if (removedWasCorrect && this.options.length > 0) {
                                this.options[0].is_correct = true;
                            }
                        }
                    }
                }
            }
        </script>
    @endpush
</x-app-layout>
