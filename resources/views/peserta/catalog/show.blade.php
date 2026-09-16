<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div class="flex items-center gap-3">
                <a href="{{ route('peserta.catalog.index') }}" class="p-2.5 rounded-xl bg-white hover:bg-[#FAF8F5] border border-[#EBE5DF] text-[#1E1B18] shadow-sm transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>
                <div>
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-bold font-montserrat bg-[#FFF7ED] text-[#EA580C] border border-[#FED7AA] mb-1">
                        🏢 {{ $class->branch?->name ?? 'Cabang Terpadu' }}
                    </span>
                    <h1 class="text-2xl sm:text-3xl font-extrabold text-[#1E1B18] tracking-tight font-montserrat">
                        {{ $class->title }}
                    </h1>
                </div>
            </div>
            <div class="flex items-center gap-2">
                @if($enrollment)
                    <a href="{{ route('peserta.study.show', $class) }}" class="px-5 py-2.5 text-xs font-bold text-white bg-gradient-to-r from-[#FF6B00] to-[#E11D48] hover:from-[#EA580C] hover:to-[#DC2626] rounded-xl shadow-md hover:shadow-lg transition flex items-center gap-2 font-montserrat">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                        <span>Buka Ruang Belajar</span>
                    </a>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-8 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
        <!-- Notifikasi Session Flash -->
        @if(session('success'))
            <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-700 text-xs font-medium flex items-center gap-3 font-quicksand">
                <svg class="w-5 h-5 flex-shrink-0 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif
        @if(session('info'))
            <div class="p-4 rounded-2xl bg-blue-50 border border-blue-200 text-blue-700 text-xs font-medium flex items-center gap-3 font-quicksand">
                <svg class="w-5 h-5 flex-shrink-0 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ session('info') }}</span>
            </div>
        @endif
        @if($errors->any())
            <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-700 text-xs font-medium space-y-1 font-quicksand">
                @foreach($errors->all() as $err)
                    <div class="flex items-center gap-2">
                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                        <span>{{ $err }}</span>
                    </div>
                @endforeach
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Kolom Kiri: Detail, Silabus & Kuis (2 Kolom) -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Deskripsi & Info Pengajar -->
                <div class="bg-white border border-[#EBE5DF] rounded-2xl p-6 space-y-5 shadow-sm">
                    <div class="flex flex-wrap items-center gap-2">
                        <!-- Badge Tipe -->
                        @if($class->type === 'offline')
                            <span class="px-3 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200 flex items-center gap-1.5 font-montserrat">
                                <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                                Offline (Tatap Muka)
                            </span>
                        @elseif($class->type === 'online')
                            <span class="px-3 py-1 rounded-full text-xs font-bold bg-sky-50 text-sky-700 border border-sky-200 flex items-center gap-1.5 font-montserrat">
                                <span class="w-2 h-2 rounded-full bg-sky-500"></span>
                                Online (Live Zoom)
                            </span>
                        @else
                            <span class="px-3 py-1 rounded-full text-xs font-bold bg-purple-50 text-purple-700 border border-purple-200 flex items-center gap-1.5 font-montserrat">
                                <span class="w-2 h-2 rounded-full bg-purple-500"></span>
                                Hybrid (Fisik + Zoom)
                            </span>
                        @endif

                        <!-- Badge Status -->
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-bold font-montserrat bg-[#FAF8F5] text-[#6E675F] border border-[#EBE5DF] capitalize">
                            Status: {{ $class->status }}
                        </span>

                        <!-- Sisa Kuota Offline -->
                        @if($class->isOffline() || $class->isHybrid())
                            @php
                                $remainingOffline = max(0, 40 - (int)$class->enrolled_offline);
                            @endphp
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-bold font-montserrat {{ $remainingOffline > 0 ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-rose-50 text-rose-700 border border-rose-200' }}">
                                Kursi Offline: {{ $remainingOffline }} / 40 Tersisa
                            </span>
                        @endif
                    </div>

                    <div>
                        <h3 class="text-xs font-bold uppercase tracking-wider text-[#6E675F] font-montserrat mb-2">Tentang Pelatihan</h3>
                        <p class="text-sm text-[#1E1B18] leading-relaxed font-quicksand whitespace-pre-line">
                            {{ $class->description ?: 'Belum ada ringkasan deskripsi pelatihan untuk kelas ini.' }}
                        </p>
                    </div>

                    <!-- Profil Trainer -->
                    <div class="pt-4 border-t border-[#EBE5DF] flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-orange-50 text-[#FF6B00] border border-orange-200 flex items-center justify-center font-bold text-lg font-montserrat shadow-sm">
                            {{ strtoupper(substr($class->trainer?->name ?? 'T', 0, 1)) }}
                        </div>
                        <div>
                            <span class="text-[11px] uppercase tracking-wider text-[#6E675F] font-montserrat block">Instruktur / Trainer</span>
                            <span class="font-extrabold text-[#1E1B18] text-sm font-montserrat">{{ $class->trainer?->name ?? 'Belum Ditugaskan' }}</span>
                            <span class="text-xs text-[#6E675F] font-quicksand block">{{ $class->trainer?->email }}</span>
                        </div>
                    </div>
                </div>

                <!-- Silabus Sesi Pertemuan -->
                <div class="bg-white border border-[#EBE5DF] rounded-2xl p-6 space-y-4 shadow-sm">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 pb-3 border-b border-[#EBE5DF]">
                        <div>
                            <h3 class="text-base font-extrabold text-[#1E1B18] font-montserrat flex items-center gap-2">
                                <span class="p-1.5 rounded-lg bg-sky-50 text-sky-600 border border-sky-100">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </span>
                                <span>Silabus Sesi Pertemuan ({{ $class->sessions->count() }} Sesi)</span>
                            </h3>
                            <p class="text-xs text-[#6E675F] mt-0.5 font-quicksand">Setiap sesi memiliki bobot durasi belajar terstruktur (1 JP = 45 menit).</p>
                        </div>
                        <span class="text-xs font-bold text-[#FF6B00] font-montserrat bg-[#FFF7ED] px-3 py-1 rounded-lg border border-[#FED7AA] self-start sm:self-auto">
                            Total: {{ $class->sessions->sum('minute_duration') }} Menit ({{ round($class->sessions->sum('minute_duration') / 45, 1) }} JP)
                        </span>
                    </div>

                    @if($class->sessions->isEmpty())
                        <div class="p-8 rounded-xl bg-[#FAF8F5] border border-dashed border-[#EBE5DF] text-center text-xs text-[#6E675F] font-quicksand">
                            Jadwal sesi pertemuan belum diterbitkan oleh Trainer kelas ini.
                        </div>
                    @else
                        <div class="space-y-3">
                            @foreach($class->sessions->sortBy('session_order') as $session)
                                <div class="p-4 rounded-xl bg-[#FAF8F5] border border-[#EBE5DF] hover:border-sky-300 transition flex flex-col sm:flex-row sm:items-center justify-between gap-3 shadow-sm">
                                    <div class="flex items-start gap-3">
                                        <div class="w-8 h-8 rounded-lg bg-orange-50 text-[#FF6B00] border border-orange-200 flex items-center justify-center font-bold text-xs font-montserrat flex-shrink-0 mt-0.5">
                                            #{{ $session->session_order }}
                                        </div>
                                        <div>
                                            <h4 class="text-sm font-bold text-[#1E1B18] font-montserrat">{{ $session->title }}</h4>
                                            <p class="text-xs text-[#6E675F] font-quicksand mt-0.5 line-clamp-1">
                                                {{ $session->description ?: 'Topik materi tatap muka / live meeting' }}
                                            </p>
                                            <div class="flex items-center gap-3 text-[11px] text-[#6E675F] mt-1 font-quicksand">
                                                <span>{{ $session->session_date ? \Carbon\Carbon::parse($session->session_date)->translatedFormat('d M Y') : 'Jadwal fleksibel' }}</span>
                                                @if($session->start_time && $session->end_time)
                                                    <span>• {{ substr($session->start_time, 0, 5) }} - {{ substr($session->end_time, 0, 5) }} WIB</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-right flex-shrink-0">
                                        <span class="inline-block px-2.5 py-1 rounded-lg text-xs font-bold bg-white text-[#1E1B18] border border-[#EBE5DF] font-mono shadow-sm">
                                            {{ $session->minute_duration }} Menit ({{ round($session->minute_duration / 45, 1) }} JP)
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- Paket Kuis Kelas -->
                <div class="bg-white border border-[#EBE5DF] rounded-2xl p-6 space-y-4 shadow-sm">
                    <div class="pb-3 border-b border-[#EBE5DF]">
                        <h3 class="text-base font-extrabold text-[#1E1B18] font-montserrat flex items-center gap-2">
                            <span class="p-1.5 rounded-lg bg-amber-50 text-amber-600 border border-amber-100">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                                </svg>
                            </span>
                            <span>Paket Kuis & Evaluasi ({{ $class->quizzes->count() }} Paket)</span>
                        </h3>
                        <p class="text-xs text-[#6E675F] mt-0.5 font-quicksand">Selesaikan kuis untuk menguji pemahaman Anda dengan batas passing grade.</p>
                    </div>

                    @if($class->quizzes->isEmpty())
                        <div class="p-8 rounded-xl bg-[#FAF8F5] border border-dashed border-[#EBE5DF] text-center text-xs text-[#6E675F] font-quicksand">
                            Belum ada paket kuis yang dialokasikan pada kelas ini.
                        </div>
                    @else
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            @foreach($class->quizzes as $quiz)
                                <div class="p-4 rounded-xl bg-[#FAF8F5] border border-[#EBE5DF] flex flex-col justify-between gap-3 shadow-sm">
                                    <div>
                                        <span class="text-[10px] font-bold uppercase tracking-wider text-amber-700 font-montserrat">Kuis Pelatihan</span>
                                        <h4 class="text-sm font-bold text-[#1E1B18] font-montserrat mt-0.5">{{ $quiz->title }}</h4>
                                        <p class="text-xs text-[#6E675F] font-quicksand mt-1 line-clamp-2">{{ $quiz->description }}</p>
                                    </div>
                                    <div class="flex items-center justify-between text-xs text-[#1E1B18] pt-2 border-t border-[#EBE5DF] font-mono">
                                        <span>Durasi: {{ $quiz->time_limit_minutes }} Menit</span>
                                        <span class="text-emerald-700 font-bold">KKM: {{ $quiz->passing_grade }}%</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <!-- Kolom Kanan: Pendaftaran & Syarat (1 Kolom) -->
            <div class="space-y-6">
                <!-- Card Pendaftaran -->
                <div class="bg-white border border-[#EBE5DF] rounded-3xl p-6 shadow-sm relative overflow-hidden">
                    @if($enrollment)
                        <!-- State: Sudah Terdaftar -->
                        <div class="space-y-4">
                            <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 border border-emerald-200 flex items-center justify-center mx-auto mb-2">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                            <div class="text-center">
                                <span class="text-xs font-bold uppercase tracking-wider text-emerald-700 font-montserrat">Status Kepesertaan</span>
                                <h3 class="text-lg font-extrabold text-[#1E1B18] font-montserrat mt-0.5">Anda Sudah Terdaftar</h3>
                                <p class="text-xs text-[#6E675F] mt-1 font-quicksand">
                                    Mode Kehadiran: <strong class="text-[#1E1B18] uppercase font-montserrat">{{ $enrollment->attendance_mode }}</strong>
                                </p>
                            </div>

                            <!-- Progres Kelas -->
                            <div class="bg-[#FAF8F5] border border-[#EBE5DF] rounded-2xl p-4 space-y-2">
                                <div class="flex justify-between items-center text-xs font-montserrat">
                                    <span class="text-[#6E675F]">Progres JP di Kelas Ini:</span>
                                    <span class="font-bold text-emerald-700">{{ $enrollment->accumulated_jp }} / 20.0 JP</span>
                                </div>
                                <div class="w-full bg-[#EBE5DF] h-2.5 rounded-full overflow-hidden">
                                    <div class="h-full bg-gradient-to-r from-emerald-500 to-teal-400 rounded-full transition-all duration-500"
                                         style="width: {{ $enrollment->progressPercentage() }}%"></div>
                                </div>
                                <div class="flex justify-between items-center text-[10px] text-[#6E675F] font-mono">
                                    <span>{{ $enrollment->accumulated_minutes }} dari 900 Menit</span>
                                    <span>{{ $enrollment->progressPercentage() }}%</span>
                                </div>
                            </div>

                            <a href="{{ route('peserta.study.show', $class) }}" class="w-full py-3 text-xs font-bold text-white bg-gradient-to-r from-[#FF6B00] to-[#E11D48] hover:from-[#EA580C] hover:to-[#DC2626] rounded-xl shadow-md hover:shadow-lg transition flex items-center justify-center gap-2 font-montserrat">
                                <span>Masuk ke Ruang Belajar &rarr;</span>
                            </a>
                        </div>
                    @else
                        <!-- State: Belum Terdaftar -->
                        <div class="space-y-4">
                            <div>
                                <span class="text-xs font-bold uppercase tracking-wider text-[#FF6B00] font-montserrat">Formulir Pendaftaran</span>
                                <h3 class="text-lg font-extrabold text-[#1E1B18] font-montserrat mt-0.5">Daftar Kelas Ini</h3>
                                <p class="text-xs text-[#6E675F] mt-1 font-quicksand">Pendaftaran tidak dipungut biaya. Kuota offline dibatasi maksimal 40 peserta demi kenyamanan kelas fisik.</p>
                            </div>

                            @if(!in_array($class->status, ['open', 'ongoing']))
                                <div class="p-4 rounded-xl bg-[#FAF8F5] border border-[#EBE5DF] text-xs text-[#6E675F] text-center font-quicksand">
                                    Pendaftaran untuk kelas ini saat ini ditutup (Status: {{ $class->status }}).
                                </div>
                            @else
                                <form method="POST" action="{{ route('peserta.catalog.enroll', $class) }}" class="space-y-4">
                                    @csrf

                                    <!-- Pilihan Modalitas Kehadiran -->
                                    <div class="space-y-2">
                                        <label class="block text-xs font-bold text-[#1E1B18] font-montserrat">Pilih Cara Kehadiran:</label>

                                        @if($class->isOffline())
                                            <input type="hidden" name="attendance_mode" value="offline">
                                            <div class="p-3 rounded-xl bg-amber-50 border border-amber-200 text-xs text-[#1E1B18]">
                                                <div class="font-bold text-amber-700 flex items-center gap-1.5 font-montserrat">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                                                    Kehadiran Fisik (Offline)
                                                </div>
                                                <p class="text-[11px] text-[#6E675F] mt-1 font-quicksand">Dilaksanakan langsung di fasilitas pelatihan {{ $class->branch?->name }}.</p>
                                            </div>
                                        @elseif($class->isOnline())
                                            <input type="hidden" name="attendance_mode" value="online">
                                            <div class="p-3 rounded-xl bg-sky-50 border border-sky-200 text-xs text-[#1E1B18]">
                                                <div class="font-bold text-sky-700 flex items-center gap-1.5 font-montserrat">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                                    Kehadiran Daring (Online via Zoom)
                                                </div>
                                                <p class="text-[11px] text-[#6E675F] mt-1 font-quicksand">Tatap muka daring interaktif via Zoom Meeting.</p>
                                            </div>
                                        @else
                                            <!-- Hybrid -->
                                            <div class="space-y-2">
                                                <!-- Radio Offline -->
                                                <label class="flex items-start gap-3 p-3 rounded-xl border {{ $class->isFullOffline() ? 'border-[#EBE5DF] bg-[#FAF8F5] opacity-60 cursor-not-allowed' : 'border-[#EBE5DF] bg-white hover:border-[#FF6B00] cursor-pointer' }} transition shadow-sm">
                                                    <input type="radio" name="attendance_mode" value="offline" {{ $class->isFullOffline() ? 'disabled' : 'checked' }} class="mt-0.5 text-[#FF6B00] focus:ring-[#FF6B00]">
                                                    <div class="text-xs">
                                                        <span class="font-bold text-[#1E1B18] font-montserrat">Hadir Langsung di Lokasi (Offline)</span>
                                                        <span class="block text-[11px] text-[#6E675F] font-quicksand">
                                                            @if($class->isFullOffline())
                                                                <span class="text-rose-600 font-semibold">(Kuota 40 Kursi Offline Telah Penuh)</span>
                                                            @else
                                                                Tersisa {{ 40 - $class->enrolled_offline }} kursi fisik di {{ $class->branch?->name }}
                                                            @endif
                                                        </span>
                                                    </div>
                                                </label>

                                                <!-- Radio Online -->
                                                <label class="flex items-start gap-3 p-3 rounded-xl border border-[#EBE5DF] bg-white hover:border-[#FF6B00] cursor-pointer transition shadow-sm">
                                                    <input type="radio" name="attendance_mode" value="online" {{ $class->isFullOffline() ? 'checked' : '' }} class="mt-0.5 text-[#FF6B00] focus:ring-[#FF6B00]">
                                                    <div class="text-xs">
                                                        <span class="font-bold text-[#1E1B18] font-montserrat">Hadir Daring (Online via Zoom)</span>
                                                        <span class="block text-[11px] text-[#6E675F] font-quicksand">Akses live meeting tanpa batasan kuota kursi fisik</span>
                                                    </div>
                                                </label>
                                            </div>
                                        @endif
                                    </div>

                                    @if($class->isOffline() && $class->isFullOffline())
                                        <div class="p-3 rounded-xl bg-rose-50 border border-rose-200 text-rose-700 text-xs text-center font-bold font-montserrat">
                                            Kuota kelas offline ini telah penuh (40/40 Kursi).
                                        </div>
                                    @else
                                        <button type="submit" class="w-full py-3 text-xs font-bold text-white bg-gradient-to-r from-[#FF6B00] to-[#E11D48] hover:from-[#EA580C] hover:to-[#DC2626] rounded-xl shadow-md hover:shadow-lg transition flex items-center justify-center gap-2 font-montserrat">
                                            Konfirmasi Pendaftaran Kelas
                                        </button>
                                    @endif
                                </form>
                            @endif
                        </div>
                    @endif
                </div>

                <!-- Info Proteksi Kuota & 20 JP -->
                <div class="bg-white border border-[#EBE5DF] rounded-2xl p-5 space-y-3 text-xs text-[#6E675F] shadow-sm">
                    <h4 class="font-bold text-[#1E1B18] font-montserrat flex items-center gap-2">
                        <svg class="w-4 h-4 text-[#FF6B00]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Ketentuan Jam Pelajaran (JP)
                    </h4>
                    <ul class="space-y-2 font-quicksand">
                        <li class="flex items-start gap-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-[#FF6B00] mt-1.5 flex-shrink-0"></span>
                            <span><strong>1 JP = 45 Menit</strong>. Target kelulusan minimal adalah <strong>20 JP (900 menit)</strong>.</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-[#FF6B00] mt-1.5 flex-shrink-0"></span>
                            <span>Presensi dihitung otomatis saat Anda menekan tombol <strong>Masuk Zoom</strong> pada jadwal sesi terkait.</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-[#FF6B00] mt-1.5 flex-shrink-0"></span>
                            <span>Setelah memenuhi 20 JP dan menuntaskan kuis, sertifikat akan diverifikasi oleh Trainer.</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
