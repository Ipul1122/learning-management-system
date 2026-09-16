<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="{{ route('trainer.quizzes.index') }}" class="p-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-400 hover:text-white transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>
                <div>
                    <h1 class="text-2xl font-extrabold text-white tracking-tight">Edit Konfigurasi Kuis</h1>
                    <p class="text-xs text-slate-400 mt-0.5">Ubah pengaturan evaluasi atau susunan butir soal pada kuis ini.</p>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto" x-data="quizBuilder({{ json_encode($questions) }}, {{ json_encode($selectedQuestionIds) }})">
        <form method="POST" action="{{ route('trainer.quizzes.update', $quiz) }}" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Form Errors Alert -->
            @if ($errors->any())
                <div class="bg-rose-500/10 border border-rose-500/20 text-rose-400 rounded-2xl p-4 text-xs">
                    <div class="font-bold flex items-center gap-2 mb-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Mohon periksa kembali formulir kuis Anda:
                    </div>
                    <ul class="list-disc list-inside space-y-0.5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Left Column: Quiz Parameters (1 Col) -->
                <div class="space-y-6">
                    <div class="bg-slate-900/60 border border-slate-800/80 rounded-2xl p-5 space-y-4">
                        <h2 class="text-sm font-bold text-white uppercase tracking-wider flex items-center gap-2 pb-2 border-b border-slate-800">
                            <svg class="w-4 h-4 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                            <label class="block text-xs font-semibold text-slate-300 mb-1.5">
                                Kelas Pelatihan <span class="text-rose-400">*</span>
                            </label>
                            <select name="class_id" required class="w-full text-xs rounded-xl bg-slate-800/80 border border-slate-700 text-white focus:ring-2 focus:ring-orange-500">
                                <option value="">-- Pilih Kelas yang Diampu --</option>
                                @foreach($classList as $c)
                                    <option value="{{ $c->id }}" {{ old('class_id', $quiz->class_id) == $c->id ? 'selected' : '' }}>
                                        {{ $c->title }} ({{ strtoupper($c->type) }})
                                    </option>
                                @endforeach
                            </select>
                            @error('class_id')
                                <p class="text-[11px] text-rose-400 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Quiz Title -->
                        <div>
                            <label class="block text-xs font-semibold text-slate-300 mb-1.5">
                                Judul Kuis / Ujian <span class="text-rose-400">*</span>
                            </label>
                            <input type="text" name="title" value="{{ old('title', $quiz->title) }}" required placeholder="Contoh: Kuis Evaluasi Modul 1"
                                   class="w-full text-xs rounded-xl bg-slate-800/80 border border-slate-700 text-white placeholder-slate-500 focus:ring-2 focus:ring-orange-500">
                            @error('title')
                                <p class="text-[11px] text-rose-400 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div>
                            <label class="block text-xs font-semibold text-slate-300 mb-1.5">
                                Instruksi / Deskripsi
                            </label>
                            <textarea name="description" rows="3" placeholder="Petunjuk pengerjaan..."
                                      class="w-full text-xs rounded-xl bg-slate-800/80 border border-slate-700 text-white placeholder-slate-500 focus:ring-2 focus:ring-orange-500">{{ old('description', $quiz->description) }}</textarea>
                        </div>

                        <div class="grid grid-cols-2 gap-3 pt-2">
                            <!-- Time Limit -->
                            <div>
                                <label class="block text-[11px] font-semibold text-slate-300 mb-1">
                                    Durasi (Menit) <span class="text-rose-400">*</span>
                                </label>
                                <input type="number" name="time_limit_minutes" min="5" max="360" value="{{ old('time_limit_minutes', $quiz->time_limit_minutes) }}" required
                                       class="w-full text-xs rounded-xl bg-slate-800/80 border border-slate-700 text-white focus:ring-2 focus:ring-orange-500">
                            </div>

                            <!-- Passing Grade -->
                            <div>
                                <label class="block text-[11px] font-semibold text-slate-300 mb-1">
                                    Passing Grade (%) <span class="text-rose-400">*</span>
                                </label>
                                <input type="number" step="1" min="0" max="100" name="passing_grade" value="{{ old('passing_grade', (float) $quiz->passing_grade) }}" required
                                       class="w-full text-xs rounded-xl bg-slate-800/80 border border-slate-700 text-white focus:ring-2 focus:ring-orange-500">
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <!-- Max Attempts -->
                            <div>
                                <label class="block text-[11px] font-semibold text-slate-300 mb-1">
                                    Maks. Percobaan <span class="text-rose-400">*</span>
                                </label>
                                <input type="number" min="1" max="10" name="max_attempts" value="{{ old('max_attempts', $quiz->max_attempts) }}" required
                                       class="w-full text-xs rounded-xl bg-slate-800/80 border border-slate-700 text-white focus:ring-2 focus:ring-orange-500">
                            </div>

                            <!-- Randomized Checkbox -->
                            <div class="flex items-center pt-5">
                                <label class="relative flex items-center cursor-pointer">
                                    <input type="checkbox" name="is_randomized" value="1" {{ old('is_randomized', $quiz->is_randomized) ? 'checked' : '' }} class="sr-only peer">
                                    <div class="w-9 h-5 bg-slate-800 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-orange-500"></div>
                                    <span class="ml-2 text-[11px] font-semibold text-slate-300">Acak Soal</span>
                                </label>
                            </div>
                        </div>

                        <!-- Rule Notice: Bobot Setara -->
                        <div class="bg-amber-500/10 border border-amber-500/20 rounded-xl p-3 text-[11px] text-amber-300/90 leading-relaxed">
                            <span class="font-bold flex items-center gap-1.5 text-amber-400 mb-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Aturan Bobot Setara (PRD)
                            </span>
                            Semua butir soal memiliki bobot setara (1 poin).
                        </div>

                        <!-- Submit Section -->
                        <div class="pt-2">
                            <button type="submit" :disabled="selectedIds.length === 0"
                                    class="w-full py-3 text-xs font-bold text-white bg-gradient-to-r from-orange-500 to-amber-500 hover:from-orange-600 hover:to-amber-600 disabled:opacity-50 disabled:cursor-not-allowed rounded-xl shadow-lg shadow-orange-500/20 transition flex items-center justify-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                Simpan Perubahan Kuis
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Question Bank Selector (2 Cols) -->
                <div class="lg:col-span-2 space-y-4">
                    <div class="bg-slate-900/60 border border-slate-800/80 rounded-2xl p-5 space-y-4">
                        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 pb-3 border-b border-slate-800">
                            <div>
                                <h2 class="text-sm font-bold text-white uppercase tracking-wider flex items-center gap-2">
                                    <svg class="w-4 h-4 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                    </svg>
                                    Pilih Butir Soal Dari Bank Soal Cabang
                                </h2>
                                <p class="text-xs text-slate-400 mt-0.5">Centang butir soal yang diikutsertakan dalam paket kuis ini.</p>
                            </div>

                            <div class="flex items-center gap-2">
                                <span class="px-3 py-1 rounded-xl bg-orange-500/10 border border-orange-500/30 text-orange-400 text-xs font-bold">
                                    <span x-text="selectedIds.length"></span> Soal Dipilih
                                </span>
                            </div>
                        </div>

                        <!-- Filter & Search Questions in Alpine -->
                        <div class="flex flex-col sm:flex-row gap-3">
                            <div class="flex-1 relative">
                                <input type="text" x-model="searchQuery" placeholder="Cari teks soal..."
                                       class="w-full pl-9 pr-3 py-2 text-xs rounded-xl bg-slate-800/80 border border-slate-700 text-white placeholder-slate-500 focus:ring-2 focus:ring-orange-500">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-500">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </div>
                            </div>

                            <select x-model="typeFilter" class="sm:w-44 py-2 px-3 text-xs rounded-xl bg-slate-800/80 border border-slate-700 text-white focus:ring-2 focus:ring-orange-500">
                                <option value="">Semua Tipe</option>
                                <option value="multiple_choice">Pilihan Ganda</option>
                                <option value="true_false">Benar / Salah</option>
                                <option value="essay">Esai</option>
                            </select>

                            <button type="button" @click="selectAllFiltered()" class="px-3 py-2 text-xs font-semibold text-slate-300 bg-slate-800 hover:bg-slate-700 rounded-xl border border-slate-700 transition">
                                Pilih Semua
                            </button>
                            <button type="button" @click="deselectAll()" class="px-3 py-2 text-xs font-semibold text-rose-400 bg-rose-500/10 hover:bg-rose-500/20 rounded-xl border border-rose-500/20 transition">
                                Batalkan
                            </button>
                        </div>

                        <!-- Questions List -->
                        <div class="space-y-3 max-h-[600px] overflow-y-auto pr-1">
                            <template x-for="q in filteredQuestions" :key="q.id">
                                <label :class="isSelected(q.id) ? 'bg-orange-500/5 border-orange-500/40' : 'bg-slate-800/40 border-slate-700/60 hover:border-slate-600'"
                                       class="block p-3.5 rounded-xl border transition cursor-pointer">
                                    <div class="flex items-start gap-3">
                                        <input type="checkbox" name="questions[]" :value="q.id" :checked="isSelected(q.id)" @change="toggleQuestion(q.id)"
                                               class="mt-1 rounded border-slate-700 text-orange-500 focus:ring-orange-500 bg-slate-900">

                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center gap-2 mb-1.5">
                                                <!-- Type Badge -->
                                                <span :class="{
                                                    'bg-blue-500/10 text-blue-400 border-blue-500/20': q.question_type === 'multiple_choice',
                                                    'bg-purple-500/10 text-purple-400 border-purple-500/20': q.question_type === 'true_false',
                                                    'bg-emerald-500/10 text-emerald-400 border-emerald-500/20': q.question_type === 'essay'
                                                }" class="px-2 py-0.5 text-[10px] font-bold rounded-lg border">
                                                    <span x-text="q.question_type === 'multiple_choice' ? 'Pilihan Ganda' : (q.question_type === 'true_false' ? 'Benar / Salah' : 'Esai')"></span>
                                                </span>
                                                <span class="text-[10px] text-slate-500">Bobot: 1 Poin</span>
                                            </div>

                                            <p class="text-xs text-white font-medium line-clamp-2" x-text="q.question_text"></p>

                                            <!-- Display Options if MC/TF -->
                                            <template x-if="q.options && q.options.length > 0">
                                                <div class="mt-2 grid grid-cols-1 sm:grid-cols-2 gap-1.5 text-[11px]">
                                                    <template x-for="opt in q.options" :key="opt.id">
                                                        <div :class="opt.is_correct ? 'text-emerald-400 bg-emerald-500/10 border border-emerald-500/20' : 'text-slate-400 bg-slate-900/60 border border-slate-800'"
                                                             class="px-2.5 py-1 rounded-lg flex items-center justify-between">
                                                            <span class="truncate" x-text="opt.option_text"></span>
                                                            <template x-if="opt.is_correct">
                                                                <svg class="w-3.5 h-3.5 text-emerald-400 shrink-0 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script>
        function quizBuilder(allQuestions, initialSelected) {
            return {
                questions: allQuestions || [],
                selectedIds: (@json(old('questions')) ? @json(old('questions')).map(id => parseInt(id)) : (initialSelected || [])),
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
