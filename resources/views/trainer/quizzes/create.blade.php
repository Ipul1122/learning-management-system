<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="{{ route('trainer.quizzes.index') }}" class="p-2.5 rounded-xl bg-white hover:bg-[#FAF8F5] border border-[#EBE5DF] text-[#1E1B18] shadow-sm transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>
                <div>
                    <h1 class="text-2xl font-extrabold text-[#1E1B18] tracking-tight font-montserrat">Rancang Paket Kuis Baru</h1>
                    <p class="text-xs text-[#6E675F] mt-0.5 font-quicksand">Tentukan parameter ujian dan pilih butir soal dari Bank Soal Cabang {{ $branch->name }}.</p>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto" x-data="quizBuilder({{ json_encode($questions) }})">
        <form method="POST" action="{{ route('trainer.quizzes.store') }}" class="space-y-6">
            @csrf

            <!-- Form Errors Alert -->
            @if ($errors->any())
                <div class="bg-rose-50 border border-rose-200 text-rose-800 rounded-2xl p-4 text-xs shadow-sm">
                    <div class="font-bold flex items-center gap-2 mb-1 font-montserrat">
                        <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Mohon periksa kembali formulir kuis Anda:
                    </div>
                    <ul class="list-disc list-inside space-y-0.5 font-quicksand">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Left Column: Quiz Parameters (1 Col) -->
                <div class="space-y-6">
                    <div class="bg-white border border-[#EBE5DF] rounded-2xl p-5 sm:p-6 space-y-4 shadow-sm">
                        <h2 class="text-sm font-bold text-[#1E1B18] uppercase tracking-wider flex items-center gap-2 pb-2 border-b border-[#EBE5DF] font-montserrat">
                            <svg class="w-4 h-4 text-[#EA580C]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            Parameter Kuis
                        </h2>

                        <!-- Class Selection -->
                        @php
                            /** @var \Illuminate\Database\Eloquent\Collection<\App\Models\TrainingClass> $trainingClasses */
                            $classList = $trainingClasses ?? $classes;
                        @endphp
                        <div>
                            <label class="block text-xs font-bold text-[#1E1B18] mb-1.5 font-montserrat">
                                Kelas Pelatihan <span class="text-rose-500">*</span>
                            </label>
                            <select name="class_id" required class="w-full text-xs rounded-xl bg-white border border-[#EBE5DF] text-[#1E1B18] focus:ring-2 focus:ring-[#FF6B00] font-quicksand">
                                <option value="">-- Pilih Kelas yang Diampu --</option>
                                @foreach($classList as $c)
                                    <option value="{{ $c->id }}" {{ old('class_id', request('class_id')) == $c->id ? 'selected' : '' }}>
                                        {{ $c->title }} ({{ strtoupper($c->type) }})
                                    </option>
                                @endforeach
                            </select>
                            @error('class_id')
                                <p class="text-[11px] text-rose-500 mt-1 font-mono">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Quiz Title -->
                        <div>
                            <label class="block text-xs font-bold text-[#1E1B18] mb-1.5 font-montserrat">
                                Judul Kuis / Ujian <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" name="title" value="{{ old('title') }}" required placeholder="Contoh: Kuis Evaluasi Modul 1 - Fundamental"
                                   class="w-full text-xs rounded-xl bg-white border border-[#EBE5DF] text-[#1E1B18] placeholder-[#A8A29E] focus:ring-2 focus:ring-[#FF6B00] font-quicksand">
                            @error('title')
                                <p class="text-[11px] text-rose-500 mt-1 font-mono">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div>
                            <label class="block text-xs font-bold text-[#1E1B18] mb-1.5 font-montserrat">
                                Instruksi / Deskripsi
                            </label>
                            <textarea name="description" rows="3" placeholder="Petunjuk pengerjaan untuk peserta..."
                                      class="w-full text-xs rounded-xl bg-white border border-[#EBE5DF] text-[#1E1B18] placeholder-[#A8A29E] focus:ring-2 focus:ring-[#FF6B00] font-quicksand">{{ old('description') }}</textarea>
                        </div>

                        <div class="grid grid-cols-2 gap-3 pt-2">
                            <!-- Time Limit -->
                            <div>
                                <label class="block text-[11px] font-bold text-[#1E1B18] mb-1 font-montserrat">
                                    Durasi (Menit) <span class="text-rose-500">*</span>
                                </label>
                                <input type="number" name="time_limit_minutes" min="5" max="360" value="{{ old('time_limit_minutes', 30) }}" required
                                       class="w-full text-xs rounded-xl bg-white border border-[#EBE5DF] text-[#1E1B18] focus:ring-2 focus:ring-[#FF6B00] font-mono">
                            </div>

                            <!-- Passing Grade -->
                            <div>
                                <label class="block text-[11px] font-bold text-[#1E1B18] mb-1 font-montserrat">
                                    Passing Grade (%) <span class="text-rose-500">*</span>
                                </label>
                                <input type="number" step="1" min="0" max="100" name="passing_grade" value="{{ old('passing_grade', 75) }}" required
                                       class="w-full text-xs rounded-xl bg-white border border-[#EBE5DF] text-[#1E1B18] focus:ring-2 focus:ring-[#FF6B00] font-mono">
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <!-- Max Attempts -->
                            <div>
                                <label class="block text-[11px] font-bold text-[#1E1B18] mb-1 font-montserrat">
                                    Maks. Percobaan <span class="text-rose-500">*</span>
                                </label>
                                <input type="number" min="1" max="10" name="max_attempts" value="{{ old('max_attempts', 1) }}" required
                                       class="w-full text-xs rounded-xl bg-white border border-[#EBE5DF] text-[#1E1B18] focus:ring-2 focus:ring-[#FF6B00] font-mono">
                            </div>

                            <!-- Randomized Checkbox -->
                            <div class="flex items-center pt-5">
                                <label class="relative flex items-center cursor-pointer">
                                    <input type="checkbox" name="is_randomized" value="1" {{ old('is_randomized', '1') ? 'checked' : '' }} class="sr-only peer">
                                    <div class="w-9 h-5 bg-[#EBE5DF] peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-[#EBE5DF] after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-[#FF6B00]"></div>
                                    <span class="ml-2 text-[11px] font-bold text-[#1E1B18] font-montserrat">Acak Soal</span>
                                </label>
                            </div>
                        </div>

                        <!-- Rule Notice: Bobot Setara -->
                        <div class="bg-[#FFF7ED] border border-[#FED7AA] rounded-xl p-3.5 text-[11px] text-[#7C2D12] leading-relaxed font-quicksand">
                            <span class="font-bold flex items-center gap-1.5 text-[#EA580C] mb-1 font-montserrat">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Aturan Bobot Setara (PRD)
                            </span>
                            Semua butir soal memiliki bobot setara (1 poin). Nilai akhir dihitung proporsional dari 0 s/d 100 berdasarkan butir soal yang berhasil dijawab benar.
                        </div>

                        <!-- Submit Section -->
                        <div class="pt-2">
                            <button type="submit" :disabled="selectedIds.length === 0"
                                    class="w-full py-3 text-xs font-bold text-white bg-gradient-to-r from-[#FF6B00] to-[#E11D48] hover:from-[#EA580C] hover:to-[#DC2626] disabled:opacity-50 disabled:cursor-not-allowed rounded-xl shadow-md shadow-orange-500/10 transition flex items-center justify-center gap-2 font-montserrat">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                Simpan & Publikasikan Kuis
                            </button>
                            <div x-show="selectedIds.length === 0" class="text-[11px] text-amber-800 bg-amber-50 border border-amber-200 rounded-xl p-2.5 mt-2.5 flex items-start gap-2 font-quicksand">
                                <span class="text-amber-500 font-bold shrink-0">⚠️</span>
                                <span>Pilih minimal <strong>1 butir soal</strong> di kolom sebelah kanan untuk mengaktifkan tombol simpan.</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Question Bank Selector (2 Cols) -->
                <div class="lg:col-span-2 space-y-4">
                    <div class="bg-white border border-[#EBE5DF] rounded-2xl p-5 sm:p-6 space-y-4 shadow-sm">
                        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 pb-3 border-b border-[#EBE5DF]">
                            <div>
                                <h2 class="text-sm font-bold text-[#1E1B18] uppercase tracking-wider flex items-center gap-2 font-montserrat">
                                    <svg class="w-4 h-4 text-[#EA580C]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                    </svg>
                                    Pilih Butir Soal Dari Bank Soal Cabang
                                </h2>
                                <p class="text-xs text-[#6E675F] mt-0.5 font-quicksand">Centang butir soal yang ingin diikutsertakan dalam paket kuis ini.</p>
                            </div>

                            <div class="flex items-center gap-2">
                                <span class="px-3 py-1 rounded-xl bg-[#FFF7ED] border border-[#FED7AA] text-[#EA580C] text-xs font-bold font-montserrat">
                                    <span x-text="selectedIds.length"></span> Soal Dipilih
                                </span>
                            </div>
                        </div>

                        <!-- Filter & Search Questions in Alpine -->
                        <div class="flex flex-col sm:flex-row gap-3">
                            <div class="flex-1 relative">
                                <input type="text" x-model="searchQuery" placeholder="Cari teks soal..."
                                       class="w-full pl-9 pr-3 py-2 text-xs rounded-xl bg-[#FAF8F5] border border-[#EBE5DF] text-[#1E1B18] placeholder-[#A8A29E] focus:ring-2 focus:ring-[#FF6B00] font-quicksand">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-[#A8A29E]">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </div>
                            </div>

                            <select x-model="typeFilter" class="sm:w-44 py-2 px-3 text-xs rounded-xl bg-[#FAF8F5] border border-[#EBE5DF] text-[#1E1B18] focus:ring-2 focus:ring-[#FF6B00] font-quicksand">
                                <option value="">Semua Tipe</option>
                                <option value="multiple_choice">Pilihan Ganda</option>
                                <option value="true_false">Benar / Salah</option>
                                <option value="essay">Esai</option>
                            </select>

                            <button type="button" @click="selectAllFiltered()" class="px-3.5 py-2 text-xs font-bold text-[#1E1B18] bg-white hover:bg-[#FAF8F5] rounded-xl border border-[#EBE5DF] transition font-montserrat shadow-xs">
                                Pilih Semua
                            </button>
                            <button type="button" @click="deselectAll()" class="px-3.5 py-2 text-xs font-bold text-rose-600 bg-rose-50 hover:bg-rose-100 rounded-xl border border-rose-200 transition font-montserrat">
                                Batalkan
                            </button>
                        </div>

                        <!-- Questions List -->
                        <div class="space-y-3 max-h-[600px] overflow-y-auto pr-1">
                            <template x-for="q in filteredQuestions" :key="q.id">
                                <label :class="isSelected(q.id) ? 'bg-[#FFF7ED] border-[#FED7AA]' : 'bg-[#FAF8F5] border-[#EBE5DF] hover:border-[#FF6B00]/40'"
                                       class="block p-3.5 rounded-xl border transition cursor-pointer">
                                    <div class="flex items-start gap-3">
                                        <input type="checkbox" name="questions[]" :value="q.id" :checked="isSelected(q.id)" @change="toggleQuestion(q.id)"
                                               class="mt-1 rounded border-[#EBE5DF] text-[#FF6B00] focus:ring-[#FF6B00] bg-white">

                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center gap-2 mb-1.5">
                                                <!-- Type Badge -->
                                                <span :class="{
                                                    'bg-blue-50 text-blue-700 border-blue-200': q.question_type === 'multiple_choice',
                                                    'bg-purple-50 text-purple-700 border-purple-200': q.question_type === 'true_false',
                                                    'bg-emerald-50 text-emerald-700 border-emerald-200': q.question_type === 'essay'
                                                }" class="px-2 py-0.5 text-[10px] font-bold rounded-lg border font-montserrat">
                                                    <span x-text="q.question_type === 'multiple_choice' ? 'Pilihan Ganda' : (q.question_type === 'true_false' ? 'Benar / Salah' : 'Esai')"></span>
                                                </span>
                                                <span class="text-[10px] text-[#A8A29E] font-mono">Bobot: 1 Poin</span>
                                            </div>

                                            <p class="text-xs text-[#1E1B18] font-medium line-clamp-2 font-quicksand" x-text="q.question_text"></p>

                                            <!-- Display Options if MC/TF -->
                                            <template x-if="q.options && q.options.length > 0">
                                                <div class="mt-2 grid grid-cols-1 sm:grid-cols-2 gap-1.5 text-[11px] font-quicksand">
                                                    <template x-for="opt in q.options" :key="opt.id">
                                                        <div :class="opt.is_correct ? 'text-emerald-900 bg-emerald-50 border border-emerald-200 font-semibold' : 'text-[#6E675F] bg-white border border-[#EBE5DF]'"
                                                             class="px-2.5 py-1 rounded-lg flex items-center justify-between">
                                                            <span class="truncate" x-text="opt.option_text"></span>
                                                            <template x-if="opt.is_correct">
                                                                <svg class="w-3.5 h-3.5 text-emerald-600 shrink-0 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                                </svg>
                                                            </template>
                                                        </div>
                                                    </template>
                                                </div>
                                            </template>
                                        </div>
                                    </div>
                                </label>
                            </template>

                            <!-- Empty State when Question Bank has 0 items -->
                            <template x-if="questions.length === 0">
                                <div class="py-12 px-4 text-center bg-[#FAF8F5] rounded-2xl border border-dashed border-[#EBE5DF]">
                                    <div class="w-12 h-12 rounded-2xl bg-amber-50 text-[#FF6B00] border border-amber-200 flex items-center justify-center mx-auto mb-3 text-xl">
                                        📝
                                    </div>
                                    <h3 class="font-montserrat font-bold text-sm text-[#1E1B18] mb-1">Bank Soal Cabang Masih Kosong</h3>
                                    <p class="text-xs text-[#6E675F] max-w-sm mx-auto mb-4 font-quicksand">
                                        Cabang ini belum memiliki butir soal yang tersimpan. Anda perlu menambahkan butir soal ke Bank Soal terlebih dahulu sebelum dapat merancang dan menyimpan paket kuis.
                                    </p>
                                    <a href="{{ route('trainer.questions.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 text-xs font-montserrat font-bold text-white bg-gradient-to-r from-[#FF6B00] to-[#E11D48] hover:from-[#EA580C] hover:to-[#DC2626] rounded-xl shadow-md transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                        </svg>
                                        <span>+ Buat Butir Soal di Bank Soal</span>
                                    </a>
                                </div>
                            </template>

                            <template x-if="questions.length > 0 && filteredQuestions.length === 0">
                                <div class="py-10 text-center text-[#A8A29E] text-xs font-quicksand">
                                    Tidak ada butir soal yang cocok dengan filter atau kata kunci pencarian.
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script>
        function quizBuilder(allQuestions) {
            return {
                questions: allQuestions || [],
                selectedIds: @json(old('questions', [])).map(id => parseInt(id)),
                searchQuery: '',
                typeFilter: '',

                get filteredQuestions() {
                    return this.questions.filter(q => {
                        const matchType = !this.typeFilter || q.question_type === this.typeFilter;
                        const matchSearch = !this.searchQuery || q.question_text.toLowerCase().includes(this.searchQuery.toLowerCase());
                        return matchType && matchSearch;
                    });
                },

                isSelected(id) {
                    return this.selectedIds.includes(parseInt(id));
                },

                toggleQuestion(id) {
                    const numId = parseInt(id);
                    const index = this.selectedIds.indexOf(numId);
                    if (index > -1) {
                        this.selectedIds.splice(index, 1);
                    } else {
                        this.selectedIds.push(numId);
                    }
                },

                selectAllFiltered() {
                    this.filteredQuestions.forEach(q => {
                        if (!this.selectedIds.includes(q.id)) {
                            this.selectedIds.push(q.id);
                        }
                    });
                },

                deselectAll() {
                    this.selectedIds = [];
                }
            }
        }
    </script>
</x-app-layout>
