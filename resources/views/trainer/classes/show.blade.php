<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div class="flex items-center gap-3">
                <a href="{{ route('trainer.classes.index') }}" class="p-2.5 rounded-xl bg-white hover:bg-[#FAF8F5] text-[#1E1B18] border border-[#EBE5DF] shadow-sm transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>
                <div>
                    <div class="flex items-center gap-2">
                        <span class="px-2.5 py-0.5 text-[10px] font-montserrat font-bold rounded-lg uppercase tracking-wider
                            {{ $class->type === 'online' ? 'bg-sky-50 text-sky-700 border border-sky-200' : ($class->type === 'offline' ? 'bg-amber-50 text-amber-700 border border-amber-200' : 'bg-purple-50 text-purple-700 border border-purple-200') }}">
                            {{ strtoupper($class->type) }}
                        </span>
                        <span class="text-xs text-[#6E675F]">•</span>
                        <span class="text-xs font-quicksand text-[#6E675F]">Cabang {{ $branch->name }}</span>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-montserrat font-extrabold text-[#1E1B18] tracking-tight mt-1">{{ $class->title }}</h1>
                </div>
            </div>

            <div class="flex items-center gap-2 flex-wrap">
                <a href="{{ route('classes.forum.index', $class) }}" class="px-4 py-2 text-xs font-montserrat font-bold text-[#FF6B00] bg-[#FFF7ED] hover:bg-[#FFEDD5] border border-[#FED7AA] rounded-xl transition flex items-center gap-2 shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                    </svg>
                    Forum Diskusi Kelas
                </a>
                <a href="{{ route('trainer.quizzes.create') }}?class_id={{ $class->id }}" class="px-4 py-2 text-xs font-montserrat font-bold text-white bg-gradient-to-r from-[#FF6B00] to-[#E11D48] hover:from-[#EA580C] hover:to-[#DC2626] rounded-xl shadow-md hover:shadow-lg transition flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Tambah Kuis ke Kelas Ini
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6" x-data="{
        zoomModalOpen: false,
        zoomActionUrl: '',
        zoomData: {
            sessionTitle: '',
            url: '',
            meetingId: '',
            passcode: ''
        },
        openZoomModal(actionUrl, sessionTitle, url, meetingId, passcode) {
            this.zoomActionUrl = actionUrl;
            this.zoomData.sessionTitle = sessionTitle;
            this.zoomData.url = url || '';
            this.zoomData.meetingId = meetingId || '';
            this.zoomData.passcode = passcode || '';
            this.zoomModalOpen = true;
        }
    }">
        <!-- Class Meta Stats -->
        <div class="bg-white border border-[#EBE5DF] rounded-2xl p-6 shadow-sm">
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                <div class="bg-[#FAF8F5] border border-[#EBE5DF] rounded-xl p-3.5">
                    <span class="text-[10px] font-montserrat font-bold text-[#6E675F] uppercase tracking-wider block">Target / Akumulasi JP</span>
                    <span class="text-base font-montserrat font-extrabold text-[#1E1B18] mt-0.5 block">
                        {{ $class->totalAccumulatedJp() }} / {{ $class->required_jp }} JP
                    </span>
                </div>
                <div class="bg-[#FAF8F5] border border-[#EBE5DF] rounded-xl p-3.5">
                    <span class="text-[10px] font-montserrat font-bold text-[#6E675F] uppercase tracking-wider block">Sesi Terjadwal</span>
                    <span class="text-base font-montserrat font-extrabold text-sky-600 mt-0.5 block">
                        {{ $class->sessions->count() }} Pertemuan
                    </span>
                </div>
                <div class="bg-[#FAF8F5] border border-[#EBE5DF] rounded-xl p-3.5">
                    <span class="text-[10px] font-montserrat font-bold text-[#6E675F] uppercase tracking-wider block">Total Peserta</span>
                    <span class="text-base font-montserrat font-extrabold text-emerald-600 mt-0.5 block">
                        {{ $class->enrolled_offline + $class->enrolled_online }} Peserta
                    </span>
                </div>
                <div class="bg-[#FAF8F5] border border-[#EBE5DF] rounded-xl p-3.5">
                    <span class="text-[10px] font-montserrat font-bold text-[#6E675F] uppercase tracking-wider block">Paket Kuis</span>
                    <span class="text-base font-montserrat font-extrabold text-[#FF6B00] mt-0.5 block">
                        {{ $class->quizzes->count() }} Paket
                    </span>
                </div>
            </div>

            @if($class->description)
                <div class="mt-4 pt-4 border-t border-[#EBE5DF] text-xs font-quicksand text-[#6E675F] leading-relaxed">
                    <span class="font-montserrat font-bold text-[#1E1B18] block mb-0.5">Deskripsi Kelas:</span>
                    {{ $class->description }}
                </div>
            @endif
        </div>

        <!-- Syllabus / Sessions List -->
        <div class="space-y-4">
            <div class="flex items-center justify-between">
                <h2 class="text-lg sm:text-xl font-montserrat font-extrabold text-[#1E1B18] flex items-center gap-2.5">
                    <span class="p-2 rounded-xl bg-sky-50 border border-sky-200/80 text-sky-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </span>
                    <span>Silabus & Jadwal Pertemuan Sesi ({{ $class->sessions->count() }})</span>
                </h2>
            </div>

            @if($class->sessions->isEmpty())
                <div class="bg-white border border-[#EBE5DF] rounded-2xl p-10 text-center shadow-sm">
                    <div class="w-12 h-12 rounded-xl bg-sky-50 text-sky-500 border border-sky-100 flex items-center justify-center mx-auto mb-3">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <p class="text-sm font-montserrat font-bold text-[#1E1B18]">Belum Ada Silabus Sesi</p>
                    <p class="text-xs font-quicksand text-[#6E675F] mt-1">Admin Cabang belum menambahkan silabus sesi pertemuan pada kelas ini.</p>
                </div>
            @else
                <div class="space-y-3">
                    @foreach($class->sessions as $session)
                        <div class="bg-white border border-[#EBE5DF] hover:border-sky-300 rounded-2xl p-5 flex flex-col md:flex-row md:items-center justify-between gap-4 shadow-sm hover:shadow transition-all duration-200">
                            <div class="flex items-start gap-3.5">
                                <div class="w-10 h-10 rounded-xl bg-orange-50 border border-orange-200/80 flex items-center justify-center font-montserrat font-extrabold text-[#FF6B00] text-sm shrink-0">
                                    {{ $session->session_order }}
                                </div>
                                <div>
                                    <h3 class="text-sm font-montserrat font-bold text-[#1E1B18]">{{ $session->title }}</h3>
                                    <div class="flex flex-wrap items-center gap-2 mt-1 text-xs font-quicksand text-[#6E675F]">
                                        <span>{{ $session->jp_duration }} JP ({{ $session->minute_duration }} menit)</span>
                                        <span>•</span>
                                        <span>
                                            {{ $session->session_date ? $session->session_date->translatedFormat('d F Y, H:i') . ' WIB' : 'Jadwal belum ditentukan' }}
                                        </span>
                                    </div>

                                    @if($session->zoom_url)
                                        <div class="mt-2 text-xs text-sky-600 flex flex-wrap items-center gap-2">
                                            <span class="text-emerald-700 font-semibold flex items-center gap-1.5 bg-emerald-50 px-2 py-0.5 rounded-md border border-emerald-200 text-[11px]">
                                                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                                                Zoom Siap
                                            </span>
                                            <a href="{{ $session->zoom_url }}" target="_blank" class="underline hover:text-sky-700 truncate max-w-xs font-mono text-[11px]">
                                                {{ $session->zoom_url }}
                                            </a>
                                            @if($session->zoom_meeting_id)
                                                <span class="text-[11px] font-mono text-[#6E675F] bg-[#FAF8F5] px-2 py-0.5 rounded border border-[#EBE5DF]">ID: {{ $session->zoom_meeting_id }}</span>
                                            @endif
                                            @if($session->zoom_passcode)
                                                <span class="text-[11px] font-mono text-[#6E675F] bg-[#FAF8F5] px-2 py-0.5 rounded border border-[#EBE5DF]">Pass: {{ $session->zoom_passcode }}</span>
                                            @endif
                                        </div>
                                    @else
                                        <div class="mt-2 text-xs text-amber-700 italic flex items-center gap-1.5 bg-amber-50 px-2.5 py-1 rounded-md border border-amber-200 w-fit">
                                            <svg class="w-3.5 h-3.5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                            </svg>
                                            <span>Belum ada tautan Zoom untuk sesi ini.</span>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="flex items-center gap-2 shrink-0 self-end md:self-center">
                                @if($session->zoom_url)
                                    <a href="{{ $session->zoom_url }}" target="_blank" class="px-3.5 py-1.5 text-xs font-montserrat font-bold text-white bg-sky-600 hover:bg-sky-500 rounded-xl shadow-sm hover:shadow transition flex items-center gap-1.5">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                        </svg>
                                        Buka Zoom
                                    </a>
                                @endif

                                <button type="button"
                                        @click="openZoomModal(
                                            '{{ route('trainer.classes.sessions.zoom', [$class, $session]) }}',
                                            'Sesi #{{ $session->session_order }}: {{ addslashes($session->title) }}',
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

        <!-- Attached Quizzes -->
        <div class="space-y-4 pt-4">
            <div class="flex items-center justify-between">
                <h2 class="text-lg sm:text-xl font-montserrat font-extrabold text-[#1E1B18] flex items-center gap-2.5">
                    <span class="p-2 rounded-xl bg-amber-50 border border-amber-200/80 text-amber-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </span>
                    <span>Paket Kuis Terhubung ({{ $class->quizzes->count() }})</span>
                </h2>
                <a href="{{ route('trainer.quizzes.create') }}?class_id={{ $class->id }}" class="text-xs font-montserrat font-bold text-[#FF6B00] hover:text-[#EA580C]">
                    + Buat Kuis Baru
                </a>
            </div>

            @if($class->quizzes->isEmpty())
                <div class="bg-white border border-[#EBE5DF] rounded-2xl p-8 text-center shadow-sm">
                    <p class="text-sm font-montserrat font-bold text-[#1E1B18]">Belum Ada Paket Kuis</p>
                    <p class="text-xs font-quicksand text-[#6E675F] mt-1">Belum ada kuis yang dibuat khusus untuk kelas pelatihan ini.</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($class->quizzes as $quiz)
                        <div class="bg-white border border-[#EBE5DF] hover:border-[#FF6B00]/40 rounded-2xl p-4 flex items-center justify-between gap-4 shadow-sm hover:shadow transition-all duration-200">
                            <div>
                                <h3 class="text-sm font-montserrat font-bold text-[#1E1B18]">{{ $quiz->title }}</h3>
                                <div class="flex items-center gap-3 mt-1 text-xs font-quicksand text-[#6E675F]">
                                    <span>{{ $quiz->questions_count }} Butir Soal</span>
                                    <span>•</span>
                                    <span>Durasi: {{ $quiz->time_limit_minutes }} Mnt</span>
                                    <span>•</span>
                                    <span class="text-emerald-700 font-semibold">Passing: {{ (float) $quiz->passing_grade }}%</span>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <a href="{{ route('trainer.quizzes.show', $quiz) }}" class="px-3.5 py-1.5 text-xs font-montserrat font-bold text-[#1E1B18] bg-[#FAF8F5] hover:bg-white rounded-xl border border-[#EBE5DF] shadow-sm transition">
                                    Detail Soal
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Zoom Update Modal -->
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
