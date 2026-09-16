<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
            <div>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold font-montserrat bg-[#FFF7ED] text-[#EA580C] border border-[#FED7AA] mb-1">
                    <span class="w-2 h-2 rounded-full bg-[#FF6B00]"></span>
                    Instruktur / Trainer
                </span>
                <h2 class="font-montserrat font-extrabold text-2xl text-[#1E1B18] leading-tight">
                    Dashboard Trainer
                </h2>
            </div>
            <div class="text-xs font-quicksand text-[#6E675F]">
                Cabang: <span class="font-bold text-[#1E1B18] font-montserrat">{{ $branch->name ?? 'Pusat' }}</span>
            </div>
        </div>
    </x-slot>

    <div class="py-8 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
        <!-- Hero Welcome Banner -->
        <div class="bg-gradient-to-r from-[#FF6B00] via-[#F97316] to-[#E11D48] rounded-3xl p-6 sm:p-8 text-white shadow-xl relative overflow-hidden">
            <div class="relative z-10 max-w-2xl">
                <span class="text-xs font-bold tracking-widest uppercase text-white/90 font-montserrat">Pusat Pengajaran & Evaluasi</span>
                <h1 class="text-2xl sm:text-3xl font-montserrat font-extrabold mt-1 mb-2 text-white">
                    Selamat Datang, {{ $user->name }}
                </h1>
                <p class="text-sm text-white/90 leading-relaxed font-quicksand">
                    Kelola silabus kelas, langsung mulai pertemuan tatap muka daring melalui Zoom, terbitkan bank soal dengan bobot setara, dan pantau kuis evaluasi kelas Anda.
                </p>
            </div>
        </div>

        <!-- Metric Stat Cards -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white border border-[#EBE5DF] rounded-2xl p-5 flex items-center gap-4 shadow-sm hover:shadow transition">
                <div class="w-12 h-12 rounded-xl bg-orange-50 border border-orange-200/80 flex items-center justify-center text-[#FF6B00]">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                </div>
                <div>
                    <span class="text-xs font-semibold text-[#6E675F] font-quicksand uppercase tracking-wider block">Kelas Diampu</span>
                    <h3 class="text-2xl font-montserrat font-extrabold text-[#1E1B18]">{{ number_format($assignedClassesCount) }}</h3>
                    <span class="text-[10px] text-emerald-700 font-semibold font-montserrat">{{ $activeClassesCount }} kelas aktif</span>
                </div>
            </div>

            <div class="bg-white border border-[#EBE5DF] rounded-2xl p-5 flex items-center gap-4 shadow-sm hover:shadow transition">
                <div class="w-12 h-12 rounded-xl bg-sky-50 border border-sky-200/80 flex items-center justify-center text-sky-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <span class="text-xs font-semibold text-[#6E675F] font-quicksand uppercase tracking-wider block">Bank Soal Cabang</span>
                    <h3 class="text-2xl font-montserrat font-extrabold text-[#1E1B18]">{{ number_format($questionsCount) }}</h3>
                    <span class="text-[10px] text-sky-700 font-semibold font-montserrat">Bobot Setara (1 poin)</span>
                </div>
            </div>

            <div class="bg-white border border-[#EBE5DF] rounded-2xl p-5 flex items-center gap-4 shadow-sm hover:shadow transition">
                <div class="w-12 h-12 rounded-xl bg-amber-50 border border-amber-200/80 flex items-center justify-center text-amber-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                    </svg>
                </div>
                <div>
                    <span class="text-xs font-semibold text-[#6E675F] font-quicksand uppercase tracking-wider block">Paket Kuis Saya</span>
                    <h3 class="text-2xl font-montserrat font-extrabold text-[#1E1B18]">{{ number_format($quizzesCount) }}</h3>
                    <span class="text-[10px] text-amber-700 font-semibold font-montserrat">Terkait Kelas</span>
                </div>
            </div>

            <div class="bg-white border border-[#EBE5DF] rounded-2xl p-5 flex items-center gap-4 shadow-sm hover:shadow transition">
                <div class="w-12 h-12 rounded-xl bg-purple-50 border border-purple-200/80 flex items-center justify-center text-purple-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                    </svg>
                </div>
                <div>
                    <span class="text-xs font-semibold text-[#6E675F] font-quicksand uppercase tracking-wider block">Live Sesi Zoom</span>
                    <h3 class="text-2xl font-montserrat font-extrabold text-[#1E1B18]">{{ $upcomingSessions->count() }}</h3>
                    <span class="text-[10px] text-purple-700 font-semibold font-montserrat">Sesi Siap/Akan Datang</span>
                </div>
            </div>
        </div>

        <!-- Upcoming Teaching Sessions & Instant Zoom Access (PRD 3.6) -->
        <div class="bg-white border border-[#EBE5DF] rounded-2xl p-6 shadow-sm">
            <div class="flex items-center justify-between pb-4 border-b border-[#EBE5DF] mb-5">
                <div>
                    <h2 class="text-base font-montserrat font-extrabold text-[#1E1B18] flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-sky-500 animate-ping"></span>
                        Jadwal Pengajaran & Akses Cepat Zoom
                    </h2>
                    <p class="text-xs font-quicksand text-[#6E675F] mt-1">Sesi pertemuan aktif pada kelas yang Anda bimbing.</p>
                </div>
                <a href="{{ route('trainer.classes.index') }}" class="text-xs font-montserrat font-bold text-[#FF6B00] hover:text-[#EA580C]">
                    Lihat Semua Kelas &rarr;
                </a>
            </div>

            @if($upcomingSessions->isEmpty())
                <div class="py-8 text-center bg-[#FAF8F5] rounded-xl border border-dashed border-[#EBE5DF] p-6 text-[#6E675F] text-xs font-quicksand">
                    Tidak ada jadwal sesi pengajaran aktif hari ini atau yang akan datang.
                </div>
            @else
                <div class="space-y-3">
                    @foreach($upcomingSessions as $sess)
                        <div class="bg-[#FAF8F5] border border-[#EBE5DF] hover:border-sky-300 rounded-xl p-4 flex flex-col sm:flex-row sm:items-center justify-between gap-3 transition shadow-sm hover:shadow">
                            <div>
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="px-2 py-0.5 text-[10px] font-montserrat font-bold rounded-md uppercase tracking-wider
                                        {{ $sess->trainingClass->type === 'online' ? 'bg-sky-50 text-sky-700 border border-sky-200' : 'bg-purple-50 text-purple-700 border border-purple-200' }}">
                                        {{ $sess->trainingClass->title }}
                                    </span>
                                    <span class="text-xs font-quicksand text-[#6E675F]">
                                        {{ $sess->session_date ? $sess->session_date->translatedFormat('d M Y, H:i') . ' WIB' : 'Jadwal Fleksibel' }}
                                    </span>
                                </div>
                                <h3 class="text-sm font-montserrat font-bold text-[#1E1B18]">
                                    Sesi #{{ $sess->session_order }}: {{ $sess->title }} ({{ $sess->jp_duration }} JP)
                                </h3>
                            </div>

                            <div class="flex items-center gap-2 shrink-0">
                                @if($sess->zoom_url)
                                    <a href="{{ $sess->zoom_url }}" target="_blank"
                                       class="px-3.5 py-1.5 text-xs font-montserrat font-bold text-white bg-sky-600 hover:bg-sky-500 rounded-xl shadow-sm hover:shadow transition flex items-center gap-1.5">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                        </svg>
                                        Buka Zoom
                                    </a>
                                @endif
                                <a href="{{ route('trainer.classes.show', $sess->trainingClass) }}"
                                   class="px-3.5 py-1.5 text-xs font-montserrat font-bold text-[#1E1B18] bg-white hover:bg-[#FAF8F5] rounded-xl border border-[#EBE5DF] shadow-sm transition">
                                    {{ $sess->zoom_url ? 'Detail Sesi' : 'Set Link Zoom' }}
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Quick Access Module Cards (PRD 3.1 & 3.2) -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
            <!-- Bank Soal Card -->
            <div class="bg-white border border-[#EBE5DF] hover:border-[#FF6B00]/40 rounded-2xl p-5 flex flex-col justify-between shadow-sm hover:shadow-lg transition-all duration-200 group">
                <div>
                    <div class="w-10 h-10 rounded-xl bg-orange-50 text-[#FF6B00] border border-orange-200/80 flex items-center justify-center mb-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="text-base font-montserrat font-extrabold text-[#1E1B18] group-hover:text-[#FF6B00] transition">3.1 Bank Soal Setara</h3>
                    <p class="text-xs font-quicksand text-[#6E675F] mt-1 leading-relaxed">Kelola butir soal tipe pilihan ganda, benar/salah, dan esai dengan bobot setara (1 poin) untuk cabang Anda.</p>
                </div>
                <div class="pt-4 mt-4 border-t border-[#EBE5DF] flex items-center justify-between">
                    <a href="{{ route('trainer.questions.index') }}" class="text-xs font-montserrat font-bold text-[#FF6B00] hover:text-[#EA580C]">
                        Buka Bank Soal &rarr;
                    </a>
                    <a href="{{ route('trainer.questions.create') }}" class="px-3 py-1.5 text-xs font-montserrat font-bold text-[#1E1B18] bg-[#FAF8F5] hover:bg-[#EBE5DF] rounded-xl border border-[#EBE5DF] transition">
                        + Buat Soal
                    </a>
                </div>
            </div>

            <!-- Paket Kuis Card -->
            <div class="bg-white border border-[#EBE5DF] hover:border-[#FF6B00]/40 rounded-2xl p-5 flex flex-col justify-between shadow-sm hover:shadow-lg transition-all duration-200 group">
                <div>
                    <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 border border-amber-200/80 flex items-center justify-center mb-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                        </svg>
                    </div>
                    <h3 class="text-base font-montserrat font-extrabold text-[#1E1B18] group-hover:text-amber-600 transition">3.2 Paket Kuis Evaluasi</h3>
                    <p class="text-xs font-quicksand text-[#6E675F] mt-1 leading-relaxed">Rancang kuis per kelas, atur passing grade (default 75%), batas waktu pengerjaan, dan pengacakan soal.</p>
                </div>
                <div class="pt-4 mt-4 border-t border-[#EBE5DF] flex items-center justify-between">
                    <a href="{{ route('trainer.quizzes.index') }}" class="text-xs font-montserrat font-bold text-amber-600 hover:text-amber-700">
                        Kelola Kuis &rarr;
                    </a>
                    <a href="{{ route('trainer.quizzes.create') }}" class="px-3 py-1.5 text-xs font-montserrat font-bold text-[#1E1B18] bg-[#FAF8F5] hover:bg-[#EBE5DF] rounded-xl border border-[#EBE5DF] transition">
                        + Buat Kuis
                    </a>
                </div>
            </div>

            <!-- Kelas & Silabus Card -->
            <div class="bg-white border border-[#EBE5DF] hover:border-[#FF6B00]/40 rounded-2xl p-5 flex flex-col justify-between shadow-sm hover:shadow-lg transition-all duration-200 group">
                <div>
                    <div class="w-10 h-10 rounded-xl bg-sky-50 text-sky-600 border border-sky-200/80 flex items-center justify-center mb-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                    </div>
                    <h3 class="text-base font-montserrat font-extrabold text-[#1E1B18] group-hover:text-sky-600 transition">3.6 Kelas & Zoom Pertemuan</h3>
                    <p class="text-xs font-quicksand text-[#6E675F] mt-1 leading-relaxed">Pantau silabus 20 JP per kelas, perbarui URL meeting, ID dan passcode Zoom untuk peserta daring.</p>
                </div>
                <div class="pt-4 mt-4 border-t border-[#EBE5DF] flex items-center justify-between">
                    <a href="{{ route('trainer.classes.index') }}" class="text-xs font-montserrat font-bold text-sky-600 hover:text-sky-700">
                        Daftar Kelas &rarr;
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
