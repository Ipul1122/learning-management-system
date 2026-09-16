<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold font-montserrat bg-[#FFF7ED] text-[#EA580C] border border-[#FED7AA] mb-2">
                    <span>🏢</span>
                    <span>Ruang Belajar Mandiri</span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-[#1E1B18] tracking-tight flex items-center gap-3 font-montserrat">
                    <span class="p-2 rounded-xl bg-orange-50 border border-orange-200/80 text-[#FF6B00]">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                    </span>
                    <span>Ruang Belajar & Progres 20 JP</span>
                </h1>
                <p class="text-sm text-[#6E675F] mt-1 font-quicksand">Pantau akumulasi jam pelajaran (JP), silabus pertemuan tatap muka, dan evaluasi kuis pelatihan Anda.</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('peserta.catalog.index') }}" class="px-4 py-2.5 text-xs font-montserrat font-bold text-[#1E1B18] bg-white hover:bg-[#FAF8F5] border border-[#EBE5DF] rounded-xl shadow-sm transition flex items-center gap-2">
                    <svg class="w-4 h-4 text-[#FF6B00]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                    <span>Katalog Kelas</span>
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
        <!-- Hero: 20 JP Global Tracking Card (PRD 4.6 & desain.md 5.2) -->
        @php
            $percentage = min(100, round(($totalMinutesAllClasses / 900) * 100, 1));
            $isCompleted = $totalJpAllClasses >= 20.0;
        @endphp
        <div class="bg-gradient-to-br from-[#1E1B18] via-[#2D2723] to-[#1E1B18] border border-[#3E3833] rounded-3xl p-6 sm:p-8 text-white shadow-2xl relative overflow-hidden">
            <div class="absolute -right-12 -bottom-12 w-64 h-64 {{ $isCompleted ? 'bg-emerald-500/20' : 'bg-[#FF6B00]/15' }} rounded-full blur-3xl pointer-events-none"></div>

            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-5 relative z-10">
                <div>
                    <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-bold font-montserrat {{ $isCompleted ? 'bg-emerald-500/20 text-emerald-400 border border-emerald-500/30' : 'bg-[#FFF7ED] text-[#EA580C] border border-[#FED7AA]' }}">
                        <span class="w-2 h-2 rounded-full {{ $isCompleted ? 'bg-emerald-400' : 'bg-[#FF6B00] animate-pulse' }}"></span>
                        {{ $isCompleted ? 'Target 20 JP Telah Terpenuhi' : 'Akumulasi Jam Pelajaran Menuju Kelulusan' }}
                    </span>
                    <h2 class="text-2xl sm:text-3xl font-montserrat font-extrabold text-white mt-2">
                        Tracking 20 Jam Pelajaran (JP)
                    </h2>
                    <p class="text-xs text-white/70 mt-1 font-quicksand">
                        Standar Kurikulum Terpadu: 1 JP = 45 Menit • Total 900 Menit Belajar Wajib
                    </p>
                </div>
                <div class="text-left sm:text-right">
                    <div class="text-3xl sm:text-4xl font-extrabold font-montserrat tracking-tight text-transparent bg-clip-text bg-gradient-to-r {{ $isCompleted ? 'from-emerald-400 to-teal-300' : 'from-[#FF6B00] to-amber-300' }}">
                        {{ $totalJpAllClasses }} <span class="text-lg text-white/60">/ 20.0 JP</span>
                    </div>
                    <p class="text-xs text-white/70 font-mono mt-1">
                        {{ $totalMinutesAllClasses }} dari 900 Menit ({{ $percentage }}%)
                    </p>
                </div>
            </div>

            <!-- Progress Bar Bar Track -->
            <div class="w-full bg-[#141210] h-4 rounded-full p-0.5 mb-4 relative z-10 border border-[#3E3833] overflow-hidden">
                <div class="h-full rounded-full transition-all duration-700 shadow-md bg-gradient-to-r {{ $isCompleted ? 'from-emerald-500 via-teal-400 to-cyan-400' : 'from-[#FF6B00] via-amber-400 to-rose-400' }}"
                     style="width: {{ $percentage }}%"></div>
            </div>

            <!-- Indicator Footer -->
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 text-xs text-white/80 pt-3 border-t border-white/10 relative z-10 font-quicksand">
                <div class="flex items-center gap-2">
                    @if($isCompleted)
                        <svg class="w-4 h-4 text-emerald-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="text-emerald-300 font-medium">Selamat! Anda telah memenuhi syarat akumulasi 20 JP. Lengkapi seluruh kuis untuk evaluasi kelulusan.</span>
                    @else
                        <span class="w-2 h-2 rounded-full bg-[#FF6B00] animate-pulse flex-shrink-0"></span>
                        <span>Anda memerlukan <strong>{{ max(0, round(20.0 - $totalJpAllClasses, 1)) }} JP ({{ max(0, 900 - $totalMinutesAllClasses) }} menit)</strong> lagi untuk melengkapi syarat 20 JP.</span>
                    @endif
                </div>
                <span class="text-white/60 font-mono text-[11px]">1 Sesi Zoom = Menit Sesi Ditambahkan Langsung</span>
            </div>
        </div>

        <!-- Daftar Kelas yang Diikuti -->
        <div class="space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg sm:text-xl font-extrabold text-[#1E1B18] font-montserrat flex items-center gap-2.5">
                        <span class="p-2 rounded-xl bg-orange-50 border border-orange-200/80 text-[#FF6B00]">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                            </svg>
                        </span>
                        <span>Kelas Pelatihan yang Diikuti</span>
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-bold font-montserrat bg-[#FFF7ED] text-[#EA580C] border border-[#FED7AA]">
                            {{ $enrollments->total() }} Kelas
                        </span>
                    </h3>
                    <p class="text-xs font-quicksand text-[#6E675F] mt-1">Pilih kelas untuk masuk ke ruang belajar, mengikuti tatap muka Zoom, dan mengerjakan kuis.</p>
                </div>
            </div>

            @if($enrollments->isEmpty())
                <div class="bg-white border border-[#EBE5DF] rounded-2xl p-12 text-center shadow-sm">
                    <div class="w-16 h-16 rounded-2xl bg-orange-50 text-[#FF6B00] border border-orange-100 flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                    </div>
                    <h4 class="text-base font-montserrat font-extrabold text-[#1E1B18]">Anda Belum Terdaftar di Kelas Apapun</h4>
                    <p class="text-xs font-quicksand text-[#6E675F] mt-1 max-w-sm mx-auto">
                        Silakan buka katalog kelas pelatihan untuk memilih kelas offline, online, atau hybrid yang sesuai.
                    </p>
                    <div class="mt-5">
                        <a href="{{ route('peserta.catalog.index') }}" class="px-5 py-2.5 text-xs font-montserrat font-bold text-white bg-gradient-to-r from-[#FF6B00] to-[#E11D48] hover:from-[#EA580C] hover:to-[#DC2626] rounded-xl shadow-md hover:shadow-lg transition inline-flex items-center gap-2">
                            <span>Jelajahi Katalog Kelas Sekarang</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                            </svg>
                        </a>
                    </div>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($enrollments as $enr)
                        @php
                            $trainingClass = $enr->trainingClass;
                        @endphp
                        <div class="bg-white border border-[#EBE5DF] hover:border-[#FF6B00]/40 rounded-2xl p-5 sm:p-6 flex flex-col justify-between transition-all duration-200 shadow-sm hover:shadow-lg group">
                            <div class="space-y-3">
                                <div class="flex items-center justify-between gap-2">
                                    <span class="text-[10px] font-bold uppercase tracking-wider text-emerald-700 font-montserrat bg-emerald-50 px-2.5 py-0.5 rounded-full border border-emerald-200">
                                        {{ $trainingClass->branch?->name ?? 'Cabang Terpadu' }}
                                    </span>

                                    <!-- Mode Kehadiran Badge -->
                                    <span class="text-[10px] font-bold px-2 py-0.5 rounded-md font-montserrat uppercase {{ $enr->isAttendanceOffline() ? 'bg-amber-50 text-amber-700 border border-amber-200' : 'bg-blue-50 text-blue-700 border border-blue-200' }}">
                                        {{ $enr->attendance_mode }}
                                    </span>
                                </div>

                                <div>
                                    <h4 class="text-base font-montserrat font-extrabold text-[#1E1B18] group-hover:text-[#FF6B00] transition line-clamp-1">
                                        {{ $trainingClass->title }}
                                    </h4>
                                    <p class="text-xs font-quicksand text-[#6E675F] mt-1 line-clamp-2 leading-relaxed">
                                        {{ $trainingClass->description ?: 'Pelatihan kompetensi terpadu dengan instruktur berpengalaman.' }}
                                    </p>
                                </div>

                                <!-- Trainer Info -->
                                <div class="flex items-center gap-2 text-xs text-[#6E675F] pt-2 border-t border-[#EBE5DF]">
                                    <div class="w-6 h-6 rounded-full bg-orange-50 text-[#FF6B00] border border-orange-200 flex items-center justify-center font-bold text-[10px]">
                                        {{ strtoupper(substr($trainingClass->trainer?->name ?? 'T', 0, 1)) }}
                                    </div>
                                    <span class="truncate font-medium font-quicksand">{{ $trainingClass->trainer?->name ?? 'Trainer Belum Ditugaskan' }}</span>
                                </div>

                                <!-- Progress Bar JP Kelas Ini -->
                                <div class="bg-[#FAF8F5] rounded-xl p-3.5 border border-[#EBE5DF] space-y-2">
                                    <div class="flex justify-between text-[11px] font-montserrat">
                                        <span class="text-[#6E675F]">Progres Kelas:</span>
                                        <span class="font-bold text-emerald-700">{{ $enr->accumulated_jp }} / 20.0 JP</span>
                                    </div>
                                    <div class="w-full bg-[#EBE5DF] h-2 rounded-full overflow-hidden">
                                        <div class="h-full bg-gradient-to-r from-emerald-500 to-teal-400 rounded-full"
                                             style="width: {{ $enr->progressPercentage() }}%"></div>
                                    </div>
                                    <div class="flex justify-between text-[10px] text-[#6E675F] font-mono">
                                        <span>{{ $enr->accumulated_minutes }} Menit</span>
                                        <span class="font-bold">{{ $enr->progressPercentage() }}%</span>
                                    </div>
                                </div>
                            </div>

                            <div class="pt-4 mt-4 border-t border-[#EBE5DF]">
                                <a href="{{ route('peserta.study.show', $trainingClass) }}" class="w-full py-2.5 text-xs font-montserrat font-bold text-white bg-gradient-to-r from-[#FF6B00] to-[#E11D48] hover:from-[#EA580C] hover:to-[#DC2626] rounded-xl shadow-md hover:shadow-lg transition flex items-center justify-center gap-2">
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
