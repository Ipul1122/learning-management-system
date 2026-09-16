<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold font-montserrat bg-[#FFF7ED] text-[#EA580C] border border-[#FED7AA] mb-2">
                    <span>🏢</span>
                    <span>Cabang {{ $branch->name }}</span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-montserrat font-extrabold text-[#1E1B18] tracking-tight flex items-center gap-3">
                    <span class="p-2 rounded-xl bg-orange-50 border border-orange-200 text-[#FF6B00]">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                    </span>
                    <span>Kelas Pelatihan & Jadwal Pengajaran</span>
                </h1>
                <p class="text-sm font-quicksand text-[#6E675F] mt-1">Kelola silabus, konfigurasi tautan Zoom live session, dan kuis kelas Anda di Cabang {{ $branch->name }}.</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('trainer.quizzes.index') }}" class="px-4 py-2.5 text-xs font-montserrat font-bold text-[#1E1B18] bg-white hover:bg-[#FAF8F5] rounded-xl border border-[#EBE5DF] shadow-sm transition">
                    Kelola Kuis
                </a>
                <a href="{{ route('trainer.questions.index') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl font-montserrat font-bold text-xs text-white bg-gradient-to-r from-[#FF6B00] to-[#E11D48] hover:from-[#EA580C] hover:to-[#DC2626] shadow-sm hover:shadow-md transition-all duration-200 transform hover:-translate-y-0.5">
                    <span>Bank Soal</span>
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8" x-data="{
        zoomModalOpen: false,
        zoomActionUrl: '',
        zoomData: {
            sessionTitle: '',
            classTitle: '',
            url: '',
            meetingId: '',
            passcode: ''
        },
        openZoomModal(actionUrl, sessionTitle, classTitle, url, meetingId, passcode) {
            this.zoomActionUrl = actionUrl;
            this.zoomData.sessionTitle = sessionTitle;
            this.zoomData.classTitle = classTitle;
            this.zoomData.url = url || '';
            this.zoomData.meetingId = meetingId || '';
            this.zoomData.passcode = passcode || '';
            this.zoomModalOpen = true;
        }
    }">
        <!-- Quick Zoom Section (Fitur PRD 3.6 & Flow.md 118) -->
        <div class="bg-white border border-[#EBE5DF] rounded-2xl p-6 shadow-sm">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 pb-4 border-b border-[#EBE5DF] mb-5">
                <div>
                    <h2 class="text-base font-montserrat font-extrabold text-[#1E1B18] flex items-center gap-2.5">
                        <span class="p-2 rounded-xl bg-sky-50 text-sky-600 border border-sky-200/80">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                            </svg>
                        </span>
                        <span>Akses Cepat Tautan Zoom Sesi Pembelajaran</span>
                    </h2>
                    <p class="text-xs font-quicksand text-[#6E675F] mt-1">Sesi pertemuan mendatang pada kelas online/hybrid Anda. Perbarui tautan Zoom langsung dari sini.</p>
                </div>
            </div>

            @if($upcomingSessions->isEmpty())
                <div class="py-10 text-center bg-[#FAF8F5] rounded-xl border border-dashed border-[#EBE5DF] p-6 flex flex-col items-center justify-center">
                    <div class="w-12 h-12 rounded-xl bg-sky-50 text-sky-500 border border-sky-100 flex items-center justify-center mb-3">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <p class="text-sm font-montserrat font-bold text-[#1E1B18]">Belum Ada Sesi Pertemuan Aktif</p>
                    <p class="text-xs font-quicksand text-[#6E675F] mt-1">Belum ada jadwal sesi pertemuan aktif untuk kelas yang Anda ampu saat ini.</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($upcomingSessions as $session)
                        <div class="bg-[#FAF8F5] border border-[#EBE5DF] hover:border-sky-300 hover:bg-white rounded-xl p-5 flex flex-col justify-between transition-all duration-200 group shadow-sm hover:shadow">
                            <div>
                                <div class="flex items-center justify-between gap-2 mb-2.5">
                                    <span class="px-2.5 py-0.5 text-[10px] font-montserrat font-bold rounded-md uppercase tracking-wider
                                        {{ $session->trainingClass->type === 'online' ? 'bg-sky-50 text-sky-700 border border-sky-200' : 'bg-purple-50 text-purple-700 border border-purple-200' }}">
                                        {{ $session->trainingClass->title }}
                                    </span>
                                    <span class="text-[11px] text-[#6E675F] font-semibold font-quicksand">
                                        {{ $session->session_date ? $session->session_date->translatedFormat('d M Y, H:i') : 'Jadwal fleksibel' }}
                                    </span>
                                </div>

                                <h3 class="text-sm font-montserrat font-bold text-[#1E1B18] group-hover:text-sky-600 transition line-clamp-1">
                                    Sesi #{{ $session->session_order }}: {{ $session->title }}
                                </h3>

                                <div class="flex items-center gap-3 mt-2 text-xs font-quicksand text-[#6E675F]">
                                    <span>{{ $session->jp_duration }} JP ({{ $session->minute_duration }} menit)</span>
                                    <span>•</span>
                                    @if($session->zoom_url)
                                        <span class="text-emerald-700 font-semibold flex items-center gap-1.5 bg-emerald-50 px-2 py-0.5 rounded-md border border-emerald-200 text-[11px]">
                                            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                                            Zoom Siap
                                        </span>
                                    @else
                                        <span class="text-amber-700 font-medium italic flex items-center gap-1.5 bg-amber-50 px-2 py-0.5 rounded-md border border-amber-200 text-[11px]">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                            Belum ada tautan Zoom
                                        </span>
                                    @endif
                                </div>

                                @if($session->zoom_meeting_id || $session->zoom_passcode)
                                    <div class="mt-3 px-3 py-2 bg-white rounded-lg border border-[#EBE5DF] text-[11px] text-[#6E675F] font-mono flex items-center gap-3">
                                        @if($session->zoom_meeting_id)
                                            <span>ID: <strong class="text-[#1E1B18]">{{ $session->zoom_meeting_id }}</strong></span>
                                        @endif
                                        @if($session->zoom_passcode)
                                            <span>Passcode: <strong class="text-[#1E1B18]">{{ $session->zoom_passcode }}</strong></span>
                                        @endif
                                    </div>
                                @endif
                            </div>

                            <div class="pt-3 mt-4 border-t border-[#EBE5DF] flex items-center justify-between gap-2">
                                @if($session->zoom_url)
                                    <a href="{{ $session->zoom_url }}" target="_blank" rel="noopener noreferrer"
                                       class="px-3.5 py-1.5 text-xs font-montserrat font-bold text-white bg-sky-600 hover:bg-sky-500 rounded-xl shadow-sm hover:shadow transition flex items-center gap-1.5">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                        </svg>
                                        Buka Zoom Sekarang
                                    </a>
                                @else
                                    <span class="text-[11px] font-quicksand text-[#6E675F] italic">Siapkan tautan sebelum sesi</span>
                                @endif

                                <button type="button"
                                        @click="openZoomModal(
                                            '{{ route('trainer.classes.sessions.zoom', [$session->trainingClass, $session]) }}',
                                            'Sesi #{{ $session->session_order }}: {{ addslashes($session->title) }}',
                                            '{{ addslashes($session->trainingClass->title) }}',
                                            '{{ addslashes($session->zoom_url) }}',
                                            '{{ addslashes($session->zoom_meeting_id) }}',
                                            '{{ addslashes($session->zoom_passcode) }}'
                                        )"
                                        class="px-3.5 py-1.5 text-xs font-montserrat font-bold text-[#1E1B18] bg-white hover:bg-[#FAF8F5] rounded-xl border border-[#EBE5DF] shadow-sm transition flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5 text-[#FF6B00]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                    </svg>
                                    {{ $session->zoom_url ? 'Edit Zoom' : 'Set Link Zoom' }}
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- My Classes List -->
        @php
            /** @var \Illuminate\Pagination\LengthAwarePaginator<\App\Models\TrainingClass> $trainingClasses */
            $paginatedClasses = $trainingClasses ?? $classes;
        @endphp
        <div class="space-y-4">
            <div class="flex items-center justify-between">
                <h2 class="text-lg sm:text-xl font-montserrat font-extrabold text-[#1E1B18] flex items-center gap-2.5">
                    <span class="p-2 rounded-xl bg-orange-50 border border-orange-200/60 text-[#FF6B00]">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                    </span>
                    <span>Semua Kelas Pelatihan yang Anda Ampu</span>
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-montserrat font-bold bg-[#FFF7ED] text-[#EA580C] border border-[#FED7AA]">
                        {{ $paginatedClasses->total() }}
                    </span>
                </h2>
            </div>

            @if($paginatedClasses->isEmpty())
                <div class="bg-white border border-[#EBE5DF] rounded-2xl p-12 text-center shadow-sm">
                    <div class="w-12 h-12 rounded-xl bg-orange-50 text-[#FF6B00] border border-orange-100 flex items-center justify-center mx-auto mb-3">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                    </div>
                    <p class="text-sm font-montserrat font-bold text-[#1E1B18]">Belum Ada Kelas Diampu</p>
                    <p class="text-xs font-quicksand text-[#6E675F] mt-1">Anda belum ditugaskan mengampu kelas apapun di cabang ini. Silakan hubungi Admin Cabang Anda.</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($paginatedClasses as $c)
                        <div class="bg-white border border-[#EBE5DF] hover:border-[#FF6B00]/40 rounded-2xl p-5 sm:p-6 flex flex-col justify-between shadow-sm hover:shadow-lg transition-all duration-200 group">
                            <div>
                                <div class="flex items-center justify-between gap-2 mb-3">
                                    <span class="px-2.5 py-1 text-[10px] font-montserrat font-bold rounded-lg uppercase tracking-wider
                                        {{ $c->type === 'online' ? 'bg-sky-50 text-sky-700 border border-sky-200' : ($c->type === 'offline' ? 'bg-amber-50 text-amber-700 border border-amber-200' : 'bg-purple-50 text-purple-700 border border-purple-200') }}">
                                        {{ strtoupper($c->type) }}
                                    </span>
                                    <span class="px-2.5 py-0.5 text-[10px] font-montserrat font-bold rounded-md
                                        {{ $c->status === 'ongoing' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : ($c->status === 'draft' ? 'bg-[#F3EFEA] text-[#6E675F] border border-[#EBE5DF]' : ($c->status === 'open' ? 'bg-blue-50 text-blue-700 border border-blue-200' : 'bg-[#F3EFEA] text-[#6E675F] border border-[#EBE5DF]')) }}">
                                        {{ ucfirst($c->status) }}
                                    </span>
                                </div>

                                <h3 class="text-base font-montserrat font-extrabold text-[#1E1B18] group-hover:text-[#FF6B00] transition mb-1.5 line-clamp-1">
                                    {{ $c->title }}
                                </h3>
                                <p class="text-xs font-quicksand text-[#6E675F] line-clamp-2 mb-4 leading-relaxed">
                                    {{ $c->description ?: 'Tidak ada deskripsi rincian kelas.' }}
                                </p>

                                <div class="grid grid-cols-2 gap-2.5 bg-[#FAF8F5] border border-[#EBE5DF] rounded-xl p-3.5 text-xs mb-4">
                                    <div>
                                        <span class="text-[10px] font-montserrat font-bold text-[#6E675F] uppercase tracking-wider block">Target JP</span>
                                        <span class="font-montserrat font-extrabold text-[#1E1B18] text-sm">{{ $c->required_jp }} JP</span>
                                    </div>
                                    <div>
                                        <span class="text-[10px] font-montserrat font-bold text-[#6E675F] uppercase tracking-wider block">Sesi Pertemuan</span>
                                        <span class="font-montserrat font-extrabold text-[#1E1B18] text-sm">{{ $c->sessions_count }} Sesi</span>
                                    </div>
                                    <div>
                                        <span class="text-[10px] font-montserrat font-bold text-[#6E675F] uppercase tracking-wider block">Peserta Terdaftar</span>
                                        <span class="font-montserrat font-extrabold text-emerald-600 text-sm">{{ $c->enrolled_offline + $c->enrolled_online }} Orang</span>
                                    </div>
                                    <div>
                                        <span class="text-[10px] font-montserrat font-bold text-[#6E675F] uppercase tracking-wider block">Paket Kuis</span>
                                        <span class="font-montserrat font-extrabold text-[#FF6B00] text-sm">{{ $c->quizzes_count }} Kuis</span>
                                    </div>
                                </div>
                            </div>

                            <div class="pt-3 border-t border-[#EBE5DF] flex items-center justify-between gap-2">
                                <span class="text-[11px] font-quicksand font-medium text-[#6E675F] flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5 text-[#6E675F]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    {{ $c->start_date ? $c->start_date->format('d/m/Y') : '-' }} s/d {{ $c->end_date ? $c->end_date->format('d/m/Y') : '-' }}
                                </span>

                                <a href="{{ route('trainer.classes.show', $c) }}"
                                   class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl font-montserrat font-bold text-xs text-[#1E1B18] bg-[#FAF8F5] hover:bg-gradient-to-r hover:from-[#FF6B00] hover:to-[#E11D48] hover:text-white border border-[#EBE5DF] hover:border-transparent transition-all shadow-sm">
                                    <span>Silabus & Sesi</span>
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-6">
                    {{ $paginatedClasses->links() }}
                </div>
            @endif
        </div>

        <!-- Zoom Update Modal (PRD 3.6 Quick Generator/Updater) -->
        <div x-show="zoomModalOpen" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;" x-cloak>
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
                <div class="fixed inset-0 transition-opacity bg-black/40 backdrop-blur-sm" @click="zoomModalOpen = false"></div>

                <div class="relative inline-block w-full max-w-md p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-white border border-[#EBE5DF] rounded-2xl shadow-2xl">
                    <div class="flex items-center justify-between pb-3 border-b border-[#EBE5DF]">
                        <h3 class="text-sm font-montserrat font-bold text-[#1E1B18] flex items-center gap-2">
                            <span class="p-1.5 rounded-lg bg-sky-50 text-sky-600 border border-sky-100">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                </svg>
                            </span>
                            Update Tautan Zoom Sesi
                        </h3>
                        <button @click="zoomModalOpen = false" class="text-[#6E675F] hover:text-[#1E1B18] transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <div class="py-3">
                        <span class="text-[11px] font-quicksand text-[#6E675F] block" x-text="zoomData.classTitle"></span>
                        <h4 class="text-xs font-montserrat font-bold text-[#1E1B18]" x-text="zoomData.sessionTitle"></h4>
                    </div>

                    <form :action="zoomActionUrl" method="POST" class="space-y-4">
                        @csrf
                        @method('PATCH')

                        <div>
                            <label class="block text-xs font-montserrat font-bold text-[#1E1B18] mb-1">
                                Tautan URL Zoom (Join URL)
                            </label>
                            <input type="url" name="zoom_url" x-model="zoomData.url" placeholder="https://zoom.us/j/1234567890?pwd=..."
                                   class="w-full text-xs rounded-xl bg-[#FAF8F5] border border-[#EBE5DF] text-[#1E1B18] placeholder-[#9CA3AF] focus:bg-white focus:ring-2 focus:ring-[#FF6B00] focus:border-transparent font-quicksand transition">
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-montserrat font-bold text-[#1E1B18] mb-1">
                                    Meeting ID
                                </label>
                                <input type="text" name="zoom_meeting_id" x-model="zoomData.meetingId" placeholder="Mis: 890 1234 5678"
                                       class="w-full text-xs rounded-xl bg-[#FAF8F5] border border-[#EBE5DF] text-[#1E1B18] placeholder-[#9CA3AF] focus:bg-white focus:ring-2 focus:ring-[#FF6B00] focus:border-transparent font-quicksand transition">
                            </div>
                            <div>
                                <label class="block text-xs font-montserrat font-bold text-[#1E1B18] mb-1">
                                    Passcode
                                </label>
                                <input type="text" name="zoom_passcode" x-model="zoomData.passcode" placeholder="Mis: 123456"
                                       class="w-full text-xs rounded-xl bg-[#FAF8F5] border border-[#EBE5DF] text-[#1E1B18] placeholder-[#9CA3AF] focus:bg-white focus:ring-2 focus:ring-[#FF6B00] focus:border-transparent font-quicksand transition">
                            </div>
                        </div>

                        <div class="pt-3 border-t border-[#EBE5DF] flex justify-end gap-2">
                            <button type="button" @click="zoomModalOpen = false" class="px-4 py-2 text-xs font-montserrat font-bold text-[#1E1B18] bg-[#FAF8F5] hover:bg-[#EBE5DF] rounded-xl border border-[#EBE5DF] transition">
                                Batal
                            </button>
                            <button type="submit" class="px-4 py-2 text-xs font-montserrat font-bold text-white bg-gradient-to-r from-[#FF6B00] to-[#E11D48] hover:from-[#EA580C] hover:to-[#DC2626] rounded-xl shadow-md hover:shadow-lg transition">
                                Simpan Link Zoom
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
