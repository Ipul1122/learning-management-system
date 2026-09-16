<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold font-montserrat bg-[#FFF7ED] text-[#EA580C] border border-[#FED7AA] mb-2">
                    <span>🏢</span>
                    <span>Cabang {{ $branch->name }}</span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-montserrat font-extrabold text-[#1E1B18] tracking-tight flex items-center gap-3">
                    <span class="p-2 rounded-xl bg-amber-50 border border-amber-200/80 text-amber-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                        </svg>
                    </span>
                    <span>Kelola Paket Kuis Kelas</span>
                </h1>
                <p class="text-sm font-quicksand text-[#6E675F] mt-1">Konfigurasi evaluasi, passing grade, dan integrasi bank soal untuk kelas pelatihan Anda di Cabang {{ $branch->name }}.</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('trainer.questions.index') }}" class="px-4 py-2.5 text-xs font-montserrat font-bold text-[#1E1B18] bg-white hover:bg-[#FAF8F5] rounded-xl border border-[#EBE5DF] shadow-sm transition">
                    Bank Soal Cabang
                </a>
                <a href="{{ route('trainer.quizzes.create') }}" class="px-5 py-2.5 text-xs font-montserrat font-bold text-white bg-gradient-to-r from-[#FF6B00] to-[#E11D48] hover:from-[#EA580C] hover:to-[#DC2626] rounded-xl shadow-md hover:shadow-lg transition-all duration-200 transform hover:-translate-y-0.5 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    <span>Buat Kuis Baru</span>
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
        <!-- Stat Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="bg-white border border-[#EBE5DF] rounded-2xl p-5 flex items-center gap-4 shadow-sm hover:shadow transition">
                <div class="w-12 h-12 rounded-xl bg-orange-50 border border-orange-200/80 flex items-center justify-center text-[#FF6B00]">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <div>
                    <span class="text-xs font-semibold font-quicksand text-[#6E675F] uppercase tracking-wider block">Total Kuis Saya</span>
                    <h3 class="text-2xl font-montserrat font-extrabold text-[#1E1B18]">{{ number_format($stats['total_quizzes']) }}</h3>
                </div>
            </div>

            <div class="bg-white border border-[#EBE5DF] rounded-2xl p-5 flex items-center gap-4 shadow-sm hover:shadow transition">
                <div class="w-12 h-12 rounded-xl bg-emerald-50 border border-emerald-200/80 flex items-center justify-center text-emerald-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                </div>
                <div>
                    <span class="text-xs font-semibold font-quicksand text-[#6E675F] uppercase tracking-wider block">Kelas Diampu</span>
                    <h3 class="text-2xl font-montserrat font-extrabold text-[#1E1B18]">{{ number_format($stats['total_classes']) }}</h3>
                </div>
            </div>

            <div class="bg-white border border-[#EBE5DF] rounded-2xl p-5 flex items-center gap-4 shadow-sm hover:shadow transition">
                <div class="w-12 h-12 rounded-xl bg-sky-50 border border-sky-200/80 flex items-center justify-center text-sky-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <span class="text-xs font-semibold font-quicksand text-[#6E675F] uppercase tracking-wider block">Tersedia di Bank Soal</span>
                    <h3 class="text-2xl font-montserrat font-extrabold text-[#1E1B18]">{{ number_format($stats['total_questions_bank']) }} Butir</h3>
                </div>
            </div>
        </div>

        <!-- Filter & Search Bar -->
        <div class="bg-white border border-[#EBE5DF] rounded-2xl p-4 shadow-sm">
            <form method="GET" action="{{ route('trainer.quizzes.index') }}" class="flex flex-col sm:flex-row gap-3">
                <div class="flex-1 relative">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari judul kuis..."
                           class="w-full pl-10 pr-4 py-2.5 text-xs rounded-xl bg-[#FAF8F5] border border-[#EBE5DF] text-[#1E1B18] placeholder-[#9CA3AF] focus:bg-white focus:ring-2 focus:ring-[#FF6B00] focus:border-transparent font-quicksand transition">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-[#6E675F]">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                </div>

                <div class="sm:w-64">
                    <select name="class_id" onchange="this.form.submit()"
                            class="w-full py-2.5 px-3 text-xs rounded-xl bg-[#FAF8F5] border border-[#EBE5DF] text-[#1E1B18] focus:bg-white focus:ring-2 focus:ring-[#FF6B00] font-quicksand">
                        <option value="">-- Semua Kelas Diampu --</option>
                        @foreach($myClasses as $c)
                            <option value="{{ $c->id }}" {{ request('class_id') == $c->id ? 'selected' : '' }}>
                                {{ $c->title }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="px-4 py-2.5 text-xs font-montserrat font-bold text-[#1E1B18] bg-[#FAF8F5] hover:bg-[#EBE5DF] rounded-xl border border-[#EBE5DF] transition">
                    Filter
                </button>
                @if(request()->hasAny(['search', 'class_id']))
                    <a href="{{ route('trainer.quizzes.index') }}" class="px-3.5 py-2.5 text-xs font-montserrat font-bold text-rose-600 bg-rose-50 hover:bg-rose-100 border border-rose-200 rounded-xl transition text-center">
                        Reset
                    </a>
                @endif
            </form>
        </div>

        <!-- Quiz List -->
        @if($quizzes->isEmpty())
            <div class="bg-white border border-[#EBE5DF] rounded-2xl p-12 text-center shadow-sm">
                <div class="w-16 h-16 rounded-2xl bg-amber-50 border border-amber-100 flex items-center justify-center mx-auto text-amber-600 mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                </div>
                <h3 class="text-base font-montserrat font-extrabold text-[#1E1B18] mb-1">Belum Ada Paket Kuis</h3>
                <p class="text-xs font-quicksand text-[#6E675F] max-w-sm mx-auto mb-6">Anda belum membuat kuis untuk kelas yang Anda ampu. Buat kuis untuk menguji pemahaman peserta pelatihan.</p>
                <a href="{{ route('trainer.quizzes.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 text-xs font-montserrat font-bold text-white bg-gradient-to-r from-[#FF6B00] to-[#E11D48] hover:from-[#EA580C] hover:to-[#DC2626] rounded-xl shadow-md hover:shadow-lg transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    <span>Buat Kuis Sekarang</span>
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($quizzes as $quiz)
                    <div class="bg-white border border-[#EBE5DF] hover:border-[#FF6B00]/40 rounded-2xl p-5 sm:p-6 flex flex-col justify-between transition group shadow-sm hover:shadow-lg duration-200">
                        <div>
                            <!-- Class Badge & Actions Dropdown -->
                            <div class="flex items-center justify-between gap-2 mb-3">
                                <span class="px-2.5 py-1 text-[10px] font-montserrat font-bold rounded-lg uppercase tracking-wider
                                    {{ $quiz->class->type === 'online' ? 'bg-sky-50 text-sky-700 border border-sky-200' : ($quiz->class->type === 'offline' ? 'bg-amber-50 text-amber-700 border border-amber-200' : 'bg-purple-50 text-purple-700 border border-purple-200') }}">
                                    {{ $quiz->class->title }}
                                </span>
                                <span class="text-[11px] font-quicksand text-[#6E675F] flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5 text-[#FF6B00]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    {{ $quiz->time_limit_minutes }} Menit
                                </span>
                            </div>

                            <!-- Quiz Title & Description -->
                            <h3 class="text-base font-montserrat font-extrabold text-[#1E1B18] group-hover:text-[#FF6B00] transition mb-1.5 line-clamp-1">
                                {{ $quiz->title }}
                            </h3>
                            <p class="text-xs font-quicksand text-[#6E675F] line-clamp-2 mb-4 leading-relaxed">
                                {{ $quiz->description ?: 'Tidak ada deskripsi tambahan.' }}
                            </p>

                            <!-- Specifications Grid -->
                            <div class="grid grid-cols-2 gap-2.5 bg-[#FAF8F5] border border-[#EBE5DF] rounded-xl p-3.5 mb-4 text-xs">
                                <div>
                                    <span class="text-[10px] font-montserrat font-bold text-[#6E675F] uppercase tracking-wider block">Passing Grade</span>
                                    <span class="font-montserrat font-extrabold text-emerald-600">{{ (float) $quiz->passing_grade }}%</span>
                                </div>
                                <div>
                                    <span class="text-[10px] font-montserrat font-bold text-[#6E675F] uppercase tracking-wider block">Jumlah Soal</span>
                                    <span class="font-montserrat font-extrabold text-[#1E1B18]">{{ $quiz->questions_count }} Butir</span>
                                </div>
                                <div>
                                    <span class="text-[10px] font-montserrat font-bold text-[#6E675F] uppercase tracking-wider block">Acak Soal</span>
                                    <span class="font-montserrat font-bold {{ $quiz->is_randomized ? 'text-blue-600' : 'text-[#6E675F]' }}">
                                        {{ $quiz->is_randomized ? 'Ya (Acak)' : 'Urut' }}
                                    </span>
                                </div>
                                <div>
                                    <span class="text-[10px] font-montserrat font-bold text-[#6E675F] uppercase tracking-wider block">Maks. Percobaan</span>
                                    <span class="font-montserrat font-bold text-[#1E1B18]">{{ $quiz->max_attempts }}x Percobaan</span>
                                </div>
                            </div>
                        </div>

                        <!-- Footer Actions -->
                        <div class="pt-3 border-t border-[#EBE5DF] flex items-center justify-between gap-2">
                            <a href="{{ route('trainer.quizzes.show', $quiz) }}" class="px-3.5 py-1.5 text-xs font-montserrat font-bold text-[#1E1B18] bg-[#FAF8F5] hover:bg-white rounded-xl border border-[#EBE5DF] shadow-sm transition">
                                Detail & Soal
                            </a>

                            <div class="flex items-center gap-1.5">
                                <a href="{{ route('trainer.quizzes.edit', $quiz) }}" class="p-1.5 text-[#6E675F] hover:text-[#FF6B00] hover:bg-orange-50 rounded-lg transition" title="Edit Kuis">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </a>

                                <form action="{{ route('trainer.quizzes.destroy', $quiz) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus paket kuis ini?');" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 text-[#6E675F] hover:text-rose-600 hover:bg-rose-50 rounded-lg transition" title="Hapus Kuis">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-6">
                {{ $quizzes->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
