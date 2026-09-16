<x-app-layout>
    <div class="py-8" x-data="{ previewModal: false, activeQuestion: null }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Header Section -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold font-montserrat bg-[#FFF7ED] text-[#EA580C] border border-[#FED7AA] mb-2">
                        <span>🏢</span>
                        <span>Cabang {{ $branch->name }}</span>
                    </div>
                    <h1 class="font-montserrat font-extrabold text-2xl sm:text-3xl text-[#1E1B18] tracking-tight">
                        Bank Soal Pembelajaran
                    </h1>
                    <p class="font-quicksand text-sm text-[#6E675F] mt-1">
                        Kumpulan butir soal evaluasi standar setara (tanpa tingkat kesulitan) untuk dirangkai ke dalam paket kuis kelas.
                    </p>
                </div>
                <div>
                    <a href="{{ route('trainer.questions.create') }}"
                       class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl font-montserrat font-bold text-sm text-white bg-gradient-to-r from-[#FF6B00] to-[#E11D48] hover:from-[#EA580C] hover:to-[#DC2626] shadow-md hover:shadow-lg transition-all duration-200 transform hover:-translate-y-0.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        <span>Tambah Butir Soal</span>
                    </a>
                </div>
            </div>

            <!-- Metric Cards -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                <div class="bg-white p-5 rounded-2xl border border-[#EBE5DF] shadow-sm">
                    <div class="font-quicksand text-xs font-semibold text-[#6E675F] uppercase tracking-wider">Total Soal</div>
                    <div class="font-montserrat font-extrabold text-2xl text-[#1E1B18] mt-1">{{ $stats['total'] }}</div>
                </div>

                <div class="bg-white p-5 rounded-2xl border border-[#EBE5DF] shadow-sm">
                    <div class="font-quicksand text-xs font-semibold text-[#7C3AED] uppercase tracking-wider">Pilihan Ganda</div>
                    <div class="font-montserrat font-extrabold text-2xl text-[#7C3AED] mt-1">{{ $stats['multiple_choice'] }}</div>
                </div>

                <div class="bg-white p-5 rounded-2xl border border-[#EBE5DF] shadow-sm">
                    <div class="font-quicksand text-xs font-semibold text-[#0284C7] uppercase tracking-wider">Benar / Salah</div>
                    <div class="font-montserrat font-extrabold text-2xl text-[#0284C7] mt-1">{{ $stats['true_false'] }}</div>
                </div>

                <div class="bg-white p-5 rounded-2xl border border-[#EBE5DF] shadow-sm">
                    <div class="font-quicksand text-xs font-semibold text-[#10B981] uppercase tracking-wider">Esai Singkat</div>
                    <div class="font-montserrat font-extrabold text-2xl text-[#10B981] mt-1">{{ $stats['essay'] }}</div>
                </div>
            </div>

            <!-- Filter & Search Bar -->
            <div class="bg-white p-4 rounded-2xl border border-[#EBE5DF] shadow-sm">
                <form method="GET" action="{{ route('trainer.questions.index') }}" class="grid grid-cols-1 sm:grid-cols-4 gap-3">
                    <div class="sm:col-span-2 relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-[#6E675F]">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </span>
                        <input type="text"
                               name="search"
                               value="{{ request('search') }}"
                               placeholder="Cari teks butir pertanyaan..."
                               class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-[#EBE5DF] bg-[#FAF8F5] text-sm text-[#1E1B18] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#FF6B00] font-quicksand transition">
                    </div>

                    <div>
                        <select name="type"
                                onchange="this.form.submit()"
                                class="w-full py-2.5 px-3 rounded-xl border border-[#EBE5DF] bg-[#FAF8F5] text-sm text-[#1E1B18] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#FF6B00] font-quicksand">
                            <option value="">Semua Tipe Soal</option>
                            <option value="multiple_choice" {{ request('type') === 'multiple_choice' ? 'selected' : '' }}>Pilihan Ganda</option>
                            <option value="true_false" {{ request('type') === 'true_false' ? 'selected' : '' }}>Benar / Salah</option>
                            <option value="essay" {{ request('type') === 'essay' ? 'selected' : '' }}>Esai Singkat</option>
                        </select>
                    </div>

                    <div>
                        @if(request()->hasAny(['search', 'type']))
                            <a href="{{ route('trainer.questions.index') }}"
                               class="w-full py-2.5 px-4 bg-[#F3EFEA] hover:bg-[#EBE5DF] text-[#1E1B18] font-montserrat font-bold text-xs rounded-xl transition flex items-center justify-center">
                                Reset Filter
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <!-- Table of Questions -->
            <div class="bg-white rounded-3xl border border-[#EBE5DF] shadow-sm overflow-hidden">
                @if($questions->isEmpty())
                    <div class="p-12 text-center">
                        <div class="w-16 h-16 mx-auto rounded-full bg-[#FFF7ED] text-[#FF6B00] flex items-center justify-center mb-4">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h3 class="font-montserrat font-bold text-lg text-[#1E1B18]">Belum ada butir soal di bank soal</h3>
                        <p class="font-quicksand text-sm text-[#6E675F] mt-1 max-w-md mx-auto">
                            Mulai susun butir soal pilihan ganda, benar/salah, atau esai untuk kelas pelatihan Anda.
                        </p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm">
                            <thead class="bg-[#FAF8F5] border-b border-[#EBE5DF] text-xs font-montserrat font-bold text-[#6E675F] uppercase tracking-wider">
                                <tr>
                                    <th class="py-4 px-6">Tipe Soal</th>
                                    <th class="py-4 px-6">Teks Pertanyaan</th>
                                    <th class="py-4 px-6 text-center">Opsi Jawaban</th>
                                    <th class="py-4 px-6">Penyusun</th>
                                    <th class="py-4 px-6 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-[#EBE5DF] font-quicksand">
                                @foreach($questions as $q)
                                    <tr class="hover:bg-[#FAF8F5]/60 transition">
                                        <td class="py-4 px-6 whitespace-nowrap">
                                            @php
                                                $badgeStyle = match($q->question_type) {
                                                    'multiple_choice' => 'bg-[#F5F3FF] text-[#7C3AED] border-[#DDD6FE]',
                                                    'true_false' => 'bg-[#F0F9FF] text-[#0284C7] border-[#BAE6FD]',
                                                    'essay' => 'bg-[#ECFDF5] text-[#10B981] border-[#A7F3D0]',
                                                };
                                                $typeLabel = match($q->question_type) {
                                                    'multiple_choice' => 'Pilihan Ganda',
                                                    'true_false' => 'Benar / Salah',
                                                    'essay' => 'Esai Singkat',
                                                };
                                            @endphp
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold font-montserrat border {{ $badgeStyle }}">
                                                {{ $typeLabel }}
                                            </span>
                                            <div class="text-[10px] text-[#6E675F] font-mono mt-1">Bobot: {{ $q->score_weight }} Poin Setara</div>
                                        </td>

                                        <td class="py-4 px-6">
                                            <div class="font-montserrat font-semibold text-sm text-[#1E1B18] line-clamp-2">
                                                {{ $q->question_text }}
                                            </div>
                                            @if($q->explanation)
                                                <div class="text-xs text-[#6E675F] mt-1 italic flex items-center gap-1">
                                                    <span>💡 Pembahasan tersedia</span>
                                                </div>
                                            @endif
                                        </td>

                                        <td class="py-4 px-6 text-center whitespace-nowrap">
                                            @if($q->isEssay())
                                                <span class="text-xs text-[#6E675F] italic">Penilaian Manual</span>
                                            @else
                                                <div class="text-xs font-bold text-[#1E1B18]">
                                                    {{ $q->options->count() }} Pilihan
                                                </div>
                                                <div class="text-[11px] text-[#10B981] font-semibold">
                                                    Kunci: {{ $q->correctOption()?->option_text ? Str::limit($q->correctOption()->option_text, 25) : '-' }}
                                                </div>
                                            @endif
                                        </td>

                                        <td class="py-4 px-6 whitespace-nowrap text-xs">
                                            <div class="font-semibold text-[#1E1B18]">{{ $q->creator?->name ?? 'System' }}</div>
                                            <div class="text-[#6E675F]">{{ $q->created_at->format('d M Y') }}</div>
                                        </td>

                                        <td class="py-4 px-6 text-right whitespace-nowrap">
                                            <div class="inline-flex items-center gap-1.5 justify-end">
                                                <!-- Preview Button -->
                                                <button type="button"
                                                        @click="activeQuestion = {{ json_encode($q->load('options')) }}; previewModal = true"
                                                        class="p-2 rounded-lg text-[#6E675F] hover:text-[#0284C7] hover:bg-[#F0F9FF] transition"
                                                        title="Pratinjau Soal">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                </button>

                                                <!-- Edit Button -->
                                                <a href="{{ route('trainer.questions.edit', $q) }}"
                                                   class="p-2 rounded-lg text-[#6E675F] hover:text-[#FF6B00] hover:bg-[#FFF7ED] transition"
                                                   title="Edit Soal">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                    </svg>
                                                </a>

                                                <!-- Delete Button -->
                                                <button type="button"
                                                        onclick="handleDeleteQuestion('{{ $q->id }}')"
                                                        class="p-2 rounded-lg text-[#6E675F] hover:text-[#DC2626] hover:bg-[#FEF2F2] transition"
                                                        title="Hapus Soal">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>

                                                <form id="delete-question-{{ $q->id }}"
                                                      method="POST"
                                                      action="{{ route('trainer.questions.destroy', $q) }}"
                                                      class="hidden">
                                                    @csrf
                                                    @method('DELETE')
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    @if($questions->hasPages())
                        <div class="p-4 border-t border-[#EBE5DF]">
                            {{ $questions->links() }}
                        </div>
                    @endif
                @endif
            </div>

            <!-- Preview Modal -->
            <div x-show="previewModal"
                 x-cloak
                 class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-xs transition-opacity"
                 @keydown.escape.window="previewModal = false">
                <div @click.away="previewModal = false"
                     class="bg-white rounded-3xl border border-[#EBE5DF] shadow-2xl max-w-xl w-full flex flex-col overflow-hidden animate-in fade-in zoom-in-95 duration-200">

                    <div class="p-6 border-b border-[#EBE5DF] flex items-center justify-between bg-[#FAF8F5]/50">
                        <div class="flex items-center gap-2">
                            <span class="text-xs font-bold font-montserrat uppercase px-2.5 py-1 rounded-full bg-[#FFF7ED] text-[#FF6B00] border border-[#FED7AA]"
                                  x-text="activeQuestion?.question_type"></span>
                            <h3 class="font-montserrat font-bold text-base text-[#1E1B18]">Pratinjau Butir Soal</h3>
                        </div>
                        <button @click="previewModal = false" class="text-[#6E675F] hover:text-[#1E1B18] p-1.5 rounded-xl hover:bg-[#FAF8F5]">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <div class="p-6 space-y-4 max-h-[75vh] overflow-y-auto">
                        <!-- Teks Pertanyaan -->
                        <div class="text-sm font-semibold text-[#1E1B18] font-montserrat leading-relaxed bg-[#FAF8F5] p-4 rounded-2xl border border-[#EBE5DF]"
                             x-text="activeQuestion?.question_text"></div>

                        <!-- Opsi Jawaban (jika ada) -->
                        <template x-if="activeQuestion?.options && activeQuestion.options.length > 0">
                            <div class="space-y-2">
                                <div class="text-xs font-bold font-montserrat uppercase tracking-wider text-[#6E675F]">Pilihan Jawaban</div>
                                <template x-for="(opt, idx) in activeQuestion.options" :key="idx">
                                    <div class="p-3 rounded-xl border flex items-center justify-between text-xs"
                                         :class="opt.is_correct ? 'bg-[#ECFDF5] border-[#A7F3D0] text-[#065F46] font-bold' : 'bg-white border-[#EBE5DF] text-[#1E1B18]'">
                                        <div class="flex items-center gap-2">
                                            <span class="w-5 h-5 rounded-full flex items-center justify-center font-mono text-[10px]"
                                                  :class="opt.is_correct ? 'bg-[#10B981] text-white' : 'bg-[#FAF8F5] text-[#6E675F]'"
                                                  x-text="String.fromCharCode(65 + idx)"></span>
                                            <span x-text="opt.option_text"></span>
                                        </div>
                                        <span x-show="opt.is_correct" class="text-[11px] text-[#10B981] font-bold">✓ Kunci Benar</span>
                                    </div>
                                </template>
                            </div>
                        </template>

                        <!-- Pembahasan -->
                        <template x-if="activeQuestion?.explanation">
                            <div class="p-4 rounded-2xl bg-[#FFFBEB] border border-[#FDE68A] text-xs space-y-1">
                                <div class="font-montserrat font-bold text-[#B45309] flex items-center gap-1.5">
                                    <span>💡</span>
                                    <span>Kunci & Penjelasan Pembahasan</span>
                                </div>
                                <p class="text-[#92400E] font-quicksand leading-relaxed" x-text="activeQuestion.explanation"></p>
                            </div>
                        </template>
                    </div>

                    <div class="p-4 bg-[#FAF8F5] border-t border-[#EBE5DF] flex justify-end">
                        <button type="button"
                                @click="previewModal = false"
                                class="px-5 py-2 rounded-xl font-montserrat font-bold text-xs bg-[#1E1B18] text-white hover:bg-[#322E2B] transition">
                            Tutup Pratinjau
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </div>

    @push('scripts')
        <script>
            function handleDeleteQuestion(questionId) {
                window.confirmAction({
                    title: 'Hapus Butir Soal?',
                    text: 'Anda yakin ingin menghapus butir soal ini dari bank soal? Soal yang terikat pada kuis aktif mungkin terpengaruh.',
                    confirmText: 'Ya, Hapus Sekarang',
                    cancelText: 'Batal',
                    icon: 'warning'
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById(`delete-question-${questionId}`).submit();
                    }
                });
            }
        </script>
    @endpush
</x-app-layout>
