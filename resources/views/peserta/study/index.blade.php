<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="text-2xl font-extrabold text-white tracking-tight flex items-center gap-3 font-montserrat">
                    <span class="p-2.5 rounded-xl bg-gradient-to-br from-emerald-500/20 to-teal-500/20 border border-emerald-500/30 text-emerald-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                    </span>
                    Ruang Belajar & Progres 20 JP
                </h1>
                <p class="text-xs text-slate-400 mt-1 font-quicksand">Pantau akumulasi jam pelajaran (JP), silabus pertemuan tatap muka, dan evaluasi kuis pelatihan Anda.</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('peserta.catalog.index') }}" class="px-4 py-2 text-xs font-bold text-white bg-slate-800 hover:bg-slate-700 border border-slate-700 rounded-xl transition flex items-center gap-2 font-montserrat">
                    <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                    Katalog Kelas
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6 space-y-6">
        <!-- Hero: 20 JP Global Tracking Card (PRD 4.6 & desain.md 5.2) -->
        @php
            $percentage = min(100, round(($totalMinutesAllClasses / 900) * 100, 1));
            $isCompleted = $totalJpAllClasses >= 20.0;
        @endphp
        <div class="bg-gradient-to-br from-slate-900 via-slate-900 to-slate-950 border border-slate-800 rounded-3xl p-6 sm:p-8 text-white shadow-2xl relative overflow-hidden">
            <div class="absolute -right-12 -bottom-12 w-64 h-64 {{ $isCompleted ? 'bg-emerald-500/20' : 'bg-orange-500/15' }} rounded-full blur-3xl pointer-events-none"></div>

            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-5 relative z-10">
                <div>
                    <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-bold font-montserrat {{ $isCompleted ? 'bg-emerald-500/20 text-emerald-400 border border-emerald-500/30' : 'bg-orange-500/20 text-orange-400 border border-orange-500/30' }}">
                        <span class="w-2 h-2 rounded-full {{ $isCompleted ? 'bg-emerald-400' : 'bg-orange-400 animate-pulse' }}"></span>
                        {{ $isCompleted ? 'Target 20 JP Telah Terpenuhi' : 'Akumulasi Jam Pelajaran Menuju Kelulusan' }}
                    </span>
                    <h2 class="text-2xl sm:text-3xl font-montserrat font-extrabold text-white mt-2">
                        Tracking 20 Jam Pelajaran (JP)
                    </h2>
                    <p class="text-xs text-slate-400 mt-1 font-quicksand">
                        Standar Kurikulum Terpadu: 1 JP = 45 Menit • Total 900 Menit Belajar Wajib
                    </p>
                </div>
                <div class="text-left sm:text-right">
                    <div class="text-3xl sm:text-4xl font-extrabold font-montserrat tracking-tight text-transparent bg-clip-text bg-gradient-to-r {{ $isCompleted ? 'from-emerald-400 to-teal-300' : 'from-orange-400 to-amber-300' }}">
                        {{ $totalJpAllClasses }} <span class="text-lg text-slate-400">/ 20.0 JP</span>
                    </div>
                    <p class="text-xs text-slate-400 font-mono mt-1">
                        {{ $totalMinutesAllClasses }} dari 900 Menit ({{ $percentage }}%)
                    </p>
                </div>
            </div>

            <!-- Progress Bar Bar Track -->
            <div class="w-full bg-slate-800/80 h-4 rounded-full p-0.5 mb-4 relative z-10 border border-slate-700/60 overflow-hidden">
                <div class="h-full rounded-full transition-all duration-700 shadow-md bg-gradient-to-r {{ $isCompleted ? 'from-emerald-500 via-teal-400 to-cyan-400' : 'from-orange-500 via-amber-400 to-rose-400' }}"
                     style="width: {{ $percentage }}%"></div>
            </div>

            <!-- Indicator Footer -->
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 text-xs text-slate-300 pt-3 border-t border-slate-800/80 relative z-10">
                <div class="flex items-center gap-2">
                    @if($isCompleted)
                        <svg class="w-4 h-4 text-emerald-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="text-emerald-300 font-medium">Selamat! Anda telah memenuhi syarat akumulasi 20 JP. Lengkapi seluruh kuis untuk evaluasi kelulusan.</span>
                    @else
                        <span class="w-2 h-2 rounded-full bg-orange-400 animate-pulse flex-shrink-0"></span>
                        <span>Anda memerlukan <strong>{{ max(0, round(20.0 - $totalJpAllClasses, 1)) }} JP ({{ max(0, 900 - $totalMinutesAllClasses) }} menit)</strong> lagi untuk melengkapi syarat 20 JP.</span>
                    @endif
                </div>
                <span class="text-slate-400 font-mono text-[11px]">1 Sesi Zoom = Menit Sesi Ditambahkan Langsung</span>
            </div>
        </div>

        <!-- Daftar Kelas yang Diikuti -->
        <div class="space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-extrabold text-white font-montserrat flex items-center gap-2">
                        <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                        Kelas Pelatihan yang Diikuti ({{ $enrollments->total() }} Kelas)
                    </h3>
                    <p class="text-xs text-slate-400 mt-0.5">Pilih kelas untuk masuk ke ruang belajar, mengikuti tatap muka Zoom, dan mengerjakan kuis.</p>
                </div>
            </div>

            @if($enrollments->isEmpty())
                <div class="bg-slate-900/40 border border-slate-800/80 rounded-2xl p-12 text-center">
                    <div class="w-16 h-16 rounded-full bg-slate-800/80 flex items-center justify-center mx-auto text-slate-500 mb-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                    </div>
                    <h4 class="text-base font-bold text-white font-montserrat">Anda Belum Terdaftar di Kelas Apapun</h4>
                    <p class="text-xs text-slate-400 mt-1 max-w-sm mx-auto font-quicksand">
                        Silakan buka katalog kelas pelatihan untuk memilih kelas offline, online, atau hybrid yang sesuai.
                    </p>
                    <div class="mt-5">
                        <a href="{{ route('peserta.catalog.index') }}" class="px-5 py-2.5 text-xs font-bold text-white bg-gradient-to-r from-orange-500 to-amber-500 hover:from-orange-600 hover:to-amber-600 rounded-xl shadow-lg shadow-orange-500/20 transition inline-flex items-center gap-2 font-montserrat">
                            Jelajahi Katalog Kelas Sekarang &rarr;
                        </a>
                    </div>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($enrollments as $enr)
                        @php
                            $trainingClass = $enr->trainingClass;
                        @endphp
                        <div class="bg-slate-900/60 border border-slate-800/80 hover:border-slate-700/80 rounded-2xl p-5 flex flex-col justify-between transition-all duration-300 shadow-md group">
                            <div class="space-y-3">
                                <div class="flex items-center justify-between gap-2">
                                    <span class="text-[10px] font-bold uppercase tracking-wider text-emerald-400 font-montserrat bg-emerald-500/10 px-2.5 py-0.5 rounded-full border border-emerald-500/20">
                                        {{ $trainingClass->branch?->name ?? 'Cabang Terpadu' }}
                                    </span>

                                    <!-- Mode Kehadiran Badge -->
                                    <span class="text-[10px] font-bold px-2 py-0.5 rounded-md font-mono uppercase {{ $enr->isAttendanceOffline() ? 'bg-amber-500/10 text-amber-400 border border-amber-500/20' : 'bg-blue-500/10 text-blue-400 border border-blue-500/20' }}">
                                        {{ $enr->attendance_mode }}
                                    </span>
                                </div>

                                <div>
                                    <h4 class="text-base font-bold text-white font-montserrat group-hover:text-emerald-400 transition">
                                        {{ $trainingClass->title }}
                                    </h4>
                                    <p class="text-xs text-slate-400 font-quicksand mt-1 line-clamp-2">
                                        {{ $trainingClass->description ?: 'Pelatihan kompetensi terpadu dengan instruktur berpengalaman.' }}
                                    </p>
                                </div>

                                <!-- Trainer Info -->
                                <div class="flex items-center gap-2 text-xs text-slate-400 pt-2 border-t border-slate-800">
                                    <div class="w-6 h-6 rounded-full bg-slate-800 text-emerald-400 flex items-center justify-center font-bold text-[10px]">
                                        {{ strtoupper(substr($trainingClass->trainer?->name ?? 'T', 0, 1)) }}
                                    </div>
                                    <span class="truncate font-medium">{{ $trainingClass->trainer?->name ?? 'Trainer Belum Ditugaskan' }}</span>
                                </div>

                                <!-- Progress Bar JP Kelas Ini -->
                                <div class="bg-slate-800/40 rounded-xl p-3 border border-slate-700/60 space-y-1.5">
                                    <div class="flex justify-between text-[11px] font-montserrat">
                                        <span class="text-slate-400">Progres Kelas:</span>
                                        <span class="font-bold text-emerald-400">{{ $enr->accumulated_jp }} / 20.0 JP</span>
                                    </div>
                                    <div class="w-full bg-slate-700/60 h-2 rounded-full overflow-hidden">
                                        <div class="h-full bg-gradient-to-r from-emerald-500 to-teal-400 rounded-full"
                                             style="width: {{ $enr->progressPercentage() }}%"></div>
                                    </div>
                                    <div class="flex justify-between text-[10px] text-slate-400 font-mono">
                                        <span>{{ $enr->accumulated_minutes }} Menit</span>
                                        <span>{{ $enr->progressPercentage() }}%</span>
                                    </div>
                                </div>
                            </div>

                            <div class="pt-4 mt-4 border-t border-slate-800/80">
                                <a href="{{ route('peserta.study.show', $trainingClass) }}" class="w-full py-2.5 text-xs font-bold text-white bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700 rounded-xl shadow-md shadow-emerald-500/20 transition flex items-center justify-center gap-2 font-montserrat">
                                    <span>Masuk Ruang Belajar & Zoom</span>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-6">
                    {{ $enrollments->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
