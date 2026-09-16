<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="text-2xl font-extrabold text-white tracking-tight flex items-center gap-3">
                    <span class="p-2.5 rounded-xl bg-gradient-to-br from-amber-500/20 to-orange-500/20 border border-amber-500/30 text-amber-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                        </svg>
                    </span>
                    Kelola Paket Kuis Kelas
                </h1>
                <p class="text-xs text-slate-400 mt-1">Konfigurasi evaluasi, passing grade, dan integrasi bank soal untuk kelas pelatihan Anda di Cabang {{ $branch->name }}.</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('trainer.questions.index') }}" class="px-4 py-2 text-xs font-semibold text-slate-300 bg-slate-800 hover:bg-slate-700 rounded-xl border border-slate-700 transition">
                    Bank Soal Cabang
                </a>
                <a href="{{ route('trainer.quizzes.create') }}" class="px-4 py-2 text-xs font-bold text-white bg-gradient-to-r from-orange-500 to-amber-500 hover:from-orange-600 hover:to-amber-600 rounded-xl shadow-lg shadow-orange-500/20 transition flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Buat Kuis Baru
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6 space-y-6">
        <!-- Stat Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="bg-slate-900/60 border border-slate-800/80 rounded-2xl p-4 flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-orange-500/10 border border-orange-500/20 flex items-center justify-center text-orange-400">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <div>
                    <span class="text-xs font-medium text-slate-400">Total Kuis Saya</span>
                    <h3 class="text-xl font-bold text-white">{{ number_format($stats['total_quizzes']) }}</h3>
                </div>
            </div>

            <div class="bg-slate-900/60 border border-slate-800/80 rounded-2xl p-4 flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-emerald-500/10 border border-emerald-500/20 flex items-center justify-center text-emerald-400">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                </div>
                <div>
                    <span class="text-xs font-medium text-slate-400">Kelas Diampu</span>
                    <h3 class="text-xl font-bold text-white">{{ number_format($stats['total_classes']) }}</h3>
                </div>
            </div>

            <div class="bg-slate-900/60 border border-slate-800/80 rounded-2xl p-4 flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-blue-500/10 border border-blue-500/20 flex items-center justify-center text-blue-400">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <span class="text-xs font-medium text-slate-400">Tersedia di Bank Soal</span>
                    <h3 class="text-xl font-bold text-white">{{ number_format($stats['total_questions_bank']) }} Butir</h3>
                </div>
            </div>
        </div>

        <!-- Filter & Search Bar -->
        <div class="bg-slate-900/60 border border-slate-800/80 rounded-2xl p-4">
            <form method="GET" action="{{ route('trainer.quizzes.index') }}" class="flex flex-col sm:flex-row gap-3">
                <div class="flex-1 relative">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari judul kuis..."
                           class="w-full pl-10 pr-4 py-2 text-xs rounded-xl bg-slate-800/80 border border-slate-700/80 text-white placeholder-slate-500 focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-500">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                </div>

                <div class="sm:w-64">
                    <select name="class_id" onchange="this.form.submit()"
                            class="w-full py-2 px-3 text-xs rounded-xl bg-slate-800/80 border border-slate-700/80 text-white focus:ring-2 focus:ring-orange-500">
                        <option value="">-- Semua Kelas Diampu --</option>
                        @foreach($myClasses as $c)
                            <option value="{{ $c->id }}" {{ request('class_id') == $c->id ? 'selected' : '' }}>
                                {{ $c->title }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="px-4 py-2 text-xs font-semibold text-white bg-slate-800 hover:bg-slate-700 rounded-xl border border-slate-700 transition">
                    Filter
                </button>
                @if(request()->hasAny(['search', 'class_id']))
                    <a href="{{ route('trainer.quizzes.index') }}" class="px-3 py-2 text-xs font-semibold text-rose-400 bg-rose-500/10 hover:bg-rose-500/20 border border-rose-500/20 rounded-xl transition text-center">
                        Reset
                    </a>
                @endif
            </form>
        </div>

        <!-- Quiz List -->
        @if($quizzes->isEmpty())
            <div class="bg-slate-900/40 border border-slate-800/80 rounded-2xl p-12 text-center">
                <div class="w-16 h-16 rounded-full bg-slate-800/80 flex items-center justify-center mx-auto text-slate-500 mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                </div>
                <h3 class="text-base font-bold text-white mb-1">Belum Ada Paket Kuis</h3>
                <p class="text-xs text-slate-400 max-w-sm mx-auto mb-6">Anda belum membuat kuis untuk kelas yang Anda ampu. Buat kuis untuk menguji pemahaman peserta pelatihan.</p>
                <a href="{{ route('trainer.quizzes.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 text-xs font-bold text-white bg-gradient-to-r from-orange-500 to-amber-500 rounded-xl shadow-lg shadow-orange-500/20 hover:from-orange-600 hover:to-amber-600 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Buat Kuis Sekarang
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                @foreach($quizzes as $quiz)
                    <div class="bg-slate-900/60 border border-slate-800/80 hover:border-slate-700/80 rounded-2xl p-5 flex flex-col justify-between transition group shadow-sm hover:shadow-lg">
                        <div>
                            <!-- Class Badge & Actions Dropdown -->
                            <div class="flex items-center justify-between gap-2 mb-3">
                                <span class="px-2.5 py-1 text-[10px] font-bold rounded-lg uppercase tracking-wider
                                    {{ $quiz->class->type === 'online' ? 'bg-sky-500/10 text-sky-400 border border-sky-500/20' : ($quiz->class->type === 'offline' ? 'bg-amber-500/10 text-amber-400 border border-amber-500/20' : 'bg-purple-500/10 text-purple-400 border border-purple-500/20') }}">
                                    {{ $quiz->class->title }}
                                </span>
                                <span class="text-[11px] text-slate-400 flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    {{ $quiz->time_limit_minutes }} Menit
                                </span>
                            </div>

                            <!-- Quiz Title & Description -->
                            <h3 class="text-base font-bold text-white group-hover:text-orange-400 transition mb-1 line-clamp-1">
                                {{ $quiz->title }}
                            </h3>
                            <p class="text-xs text-slate-400 line-clamp-2 mb-4">
                                {{ $quiz->description ?: 'Tidak ada deskripsi tambahan.' }}
                            </p>

                            <!-- Specifications Grid -->
                            <div class="grid grid-cols-2 gap-2 bg-slate-950/40 border border-slate-800/60 rounded-xl p-3 mb-4 text-xs">
                                <div>
                                    <span class="text-[10px] text-slate-500 uppercase tracking-wider block">Passing Grade</span>
                                    <span class="font-bold text-emerald-400">{{ (float) $quiz->passing_grade }}%</span>
                                </div>
                                <div>
                                    <span class="text-[10px] text-slate-500 uppercase tracking-wider block">Jumlah Soal</span>
                                    <span class="font-bold text-white">{{ $quiz->questions_count }} Butir</span>
                                </div>
                                <div>
                                    <span class="text-[10px] text-slate-500 uppercase tracking-wider block">Acak Soal</span>
                                    <span class="font-medium {{ $quiz->is_randomized ? 'text-blue-400' : 'text-slate-400' }}">
                                        {{ $quiz->is_randomized ? 'Ya (Acak)' : 'Urut' }}
                                    </span>
                                </div>
                                <div>
                                    <span class="text-[10px] text-slate-500 uppercase tracking-wider block">Maks. Percobaan</span>
                                    <span class="font-medium text-slate-300">{{ $quiz->max_attempts }}x Percobaan</span>
                                </div>
                            </div>
                        </div>

                        <!-- Footer Actions -->
                        <div class="pt-3 border-t border-slate-800/80 flex items-center justify-between gap-2">
                            <a href="{{ route('trainer.quizzes.show', $quiz) }}" class="px-3 py-1.5 text-xs font-semibold text-slate-300 bg-slate-800 hover:bg-slate-700 rounded-lg transition">
                                Detail & Soal
                            </a>

                            <div class="flex items-center gap-1.5">
                                <a href="{{ route('trainer.quizzes.edit', $quiz) }}" class="p-1.5 text-slate-400 hover:text-amber-400 hover:bg-amber-400/10 rounded-lg transition" title="Edit Kuis">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </a>

                                <form action="{{ route('trainer.quizzes.destroy', $quiz) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus paket kuis ini?');" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 text-slate-400 hover:text-rose-400 hover:bg-rose-400/10 rounded-lg transition" title="Hapus Kuis">
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
