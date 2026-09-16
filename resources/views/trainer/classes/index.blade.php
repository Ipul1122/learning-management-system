<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="text-2xl font-extrabold text-white tracking-tight flex items-center gap-3">
                    <span class="p-2.5 rounded-xl bg-gradient-to-br from-blue-500/20 to-sky-500/20 border border-blue-500/30 text-blue-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                    </span>
                    Kelas Pelatihan & Jadwal Pengajaran
                </h1>
                <p class="text-xs text-slate-400 mt-1">Kelola silabus, konfigurasi tautan Zoom live session, dan kuis kelas Anda di Cabang {{ $branch->name }}.</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('trainer.quizzes.index') }}" class="px-4 py-2 text-xs font-semibold text-slate-300 bg-slate-800 hover:bg-slate-700 rounded-xl border border-slate-700 transition">
                    Kelola Kuis
                </a>
                <a href="{{ route('trainer.questions.index') }}" class="px-4 py-2 text-xs font-bold text-white bg-slate-800 hover:bg-slate-700 rounded-xl border border-slate-700 transition">
                    Bank Soal
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6 space-y-8" x-data="{
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
        <div class="bg-gradient-to-r from-blue-950/40 via-slate-900/60 to-slate-900/60 border border-blue-900/40 rounded-2xl p-6 shadow-lg">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 pb-4 border-b border-slate-800/80 mb-4">
                <div>
                    <h2 class="text-base font-bold text-white flex items-center gap-2">
                        <span class="p-1.5 rounded-lg bg-sky-500/20 text-sky-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                            </svg>
                        </span>
                        Akses Cepat Tautan Zoom Sesi Pembelajaran
                    </h2>
                    <p class="text-xs text-slate-400 mt-0.5">Sesi pertemuan mendatang pada kelas online/hybrid Anda. Perbarui link Zoom langsung dari sini.</p>
                </div>
            </div>

            @if($upcomingSessions->isEmpty())
                <div class="py-8 text-center text-slate-500 text-xs">
                    Belum ada jadwal sesi pertemuan aktif untuk kelas yang Anda ampu saat ini.
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($upcomingSessions as $session)
                        <div class="bg-slate-900/80 border border-slate-800/80 hover:border-slate-700 rounded-xl p-4 flex flex-col justify-between transition group">
                            <div>
                                <div class="flex items-center justify-between gap-2 mb-2">
                                    <span class="px-2 py-0.5 text-[10px] font-bold rounded-md uppercase tracking-wider
                                        {{ $session->trainingClass->type === 'online' ? 'bg-sky-500/10 text-sky-400 border border-sky-500/20' : 'bg-purple-500/10 text-purple-400 border border-purple-500/20' }}">
                                        {{ $session->trainingClass->title }}
                                    </span>
                                    <span class="text-[11px] text-slate-400 font-medium">
                                        {{ $session->session_date ? $session->session_date->translatedFormat('d M Y, H:i') : 'Jadwal fleksibel' }}
                                    </span>
                                </div>

                                <h3 class="text-sm font-bold text-white group-hover:text-sky-400 transition">
                                    Sesi #{{ $session->session_order }}: {{ $session->title }}
                                </h3>

                                <div class="flex items-center gap-3 mt-2 text-xs text-slate-400">
                                    <span>{{ $session->jp_duration }} JP ({{ $session->minute_duration }} menit)</span>
                                    <span>•</span>
                                    @if($session->zoom_url)
                                        <span class="text-emerald-400 font-medium flex items-center gap-1">
                                            <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                                            Zoom Siap
                                        </span>
                                    @else
                                        <span class="text-amber-400/90 italic flex items-center gap-1">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-400"></span>
                                            Belum ada tautan Zoom
                                        </span>
                                    @endif
                                </div>

                                @if($session->zoom_meeting_id || $session->zoom_passcode)
                                    <div class="mt-2.5 px-2.5 py-1.5 bg-slate-950/50 rounded-lg text-[11px] text-slate-400 flex items-center gap-3">
                                        @if($session->zoom_meeting_id)
                                            <span>ID: <strong class="text-slate-200">{{ $session->zoom_meeting_id }}</strong></span>
                                        @endif
                                        @if($session->zoom_passcode)
                                            <span>Passcode: <strong class="text-slate-200">{{ $session->zoom_passcode }}</strong></span>
                                        @endif
                                    </div>
                                @endif
                            </div>

                            <div class="pt-3 mt-3 border-t border-slate-800/80 flex items-center justify-between gap-2">
                                @if($session->zoom_url)
                                    <a href="{{ $session->zoom_url }}" target="_blank" rel="noopener noreferrer"
                                       class="px-3 py-1.5 text-xs font-bold text-white bg-sky-600 hover:bg-sky-500 rounded-lg shadow-sm shadow-sky-600/30 transition flex items-center gap-1.5">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                        </svg>
                                        Buka Zoom Sekarang
                                    </a>
                                @else
                                    <span class="text-[11px] text-slate-500 italic">Siapkan link sebelum sesi dimulai</span>
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
                                        class="px-3 py-1.5 text-xs font-semibold text-slate-300 bg-slate-800 hover:bg-slate-700 hover:text-white rounded-lg border border-slate-700 transition flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                <h2 class="text-lg font-bold text-white flex items-center gap-2">
                    <svg class="w-5 h-5 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                    Semua Kelas Pelatihan yang Anda Ampu ({{ $paginatedClasses->total() }})
                </h2>
            </div>

            @if($paginatedClasses->isEmpty())
                <div class="bg-slate-900/40 border border-slate-800/80 rounded-2xl p-10 text-center text-slate-500 text-xs">
                    Anda belum ditugaskan mengampu kelas apapun di cabang ini. Silakan hubungi Admin Cabang Anda.
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                    @foreach($paginatedClasses as $c)
                        <div class="bg-slate-900/60 border border-slate-800/80 hover:border-slate-700 rounded-2xl p-5 flex flex-col justify-between transition group">
                            <div>
                                <div class="flex items-center justify-between gap-2 mb-3">
                                    <span class="px-2.5 py-1 text-[10px] font-bold rounded-lg uppercase tracking-wider
                                        {{ $c->type === 'online' ? 'bg-sky-500/10 text-sky-400 border border-sky-500/20' : ($c->type === 'offline' ? 'bg-amber-500/10 text-amber-400 border border-amber-500/20' : 'bg-purple-500/10 text-purple-400 border border-purple-500/20') }}">
                                        {{ strtoupper($c->type) }}
                                    </span>
                                    <span class="px-2 py-0.5 text-[10px] font-semibold rounded-md
                                        {{ $c->status === 'ongoing' ? 'bg-emerald-500/10 text-emerald-400' : ($c->status === 'draft' ? 'bg-slate-800 text-slate-400' : 'bg-blue-500/10 text-blue-400') }}">
                                        {{ ucfirst($c->status) }}
                                    </span>
                                </div>

                                <h3 class="text-base font-bold text-white group-hover:text-orange-400 transition mb-1 line-clamp-1">
                                    {{ $c->title }}
                                </h3>
                                <p class="text-xs text-slate-400 line-clamp-2 mb-4">
                                    {{ $c->description ?: 'Tidak ada deskripsi rincian kelas.' }}
                                </p>

                                <div class="grid grid-cols-2 gap-2 bg-slate-950/40 border border-slate-800/60 rounded-xl p-3 text-xs mb-4">
                                    <div>
                                        <span class="text-[10px] text-slate-500 uppercase tracking-wider block">Target JP</span>
                                        <span class="font-bold text-white">{{ $c->required_jp }} JP</span>
                                    </div>
                                    <div>
                                        <span class="text-[10px] text-slate-500 uppercase tracking-wider block">Sesi Pertemuan</span>
                                        <span class="font-bold text-white">{{ $c->sessions_count }} Sesi</span>
                                    </div>
                                    <div>
                                        <span class="text-[10px] text-slate-500 uppercase tracking-wider block">Peserta Terdaftar</span>
                                        <span class="font-bold text-emerald-400">{{ $c->enrolled_offline + $c->enrolled_online }} Orang</span>
                                    </div>
                                    <div>
                                        <span class="text-[10px] text-slate-500 uppercase tracking-wider block">Paket Kuis</span>
                                        <span class="font-bold text-amber-400">{{ $c->quizzes_count }} Kuis</span>
                                    </div>
                                </div>
                            </div>

                            <div class="pt-3 border-t border-slate-800/80 flex items-center justify-between">
                                <span class="text-[11px] text-slate-500">
                                    {{ $c->start_date ? $c->start_date->format('d/m/Y') : '-' }} s/d {{ $c->end_date ? $c->end_date->format('d/m/Y') : '-' }}
                                </span>

                                <a href="{{ route('trainer.classes.show', $c) }}"
                                   class="px-3.5 py-1.5 text-xs font-semibold text-white bg-slate-800 hover:bg-slate-700 rounded-xl border border-slate-700 transition flex items-center gap-1.5">
                                    Silabus & Sesi
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
                <div class="fixed inset-0 transition-opacity bg-slate-950/80 backdrop-blur-sm" @click="zoomModalOpen = false"></div>

                <div class="relative inline-block w-full max-w-md p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-slate-900 border border-slate-800 rounded-2xl shadow-2xl">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-800">
                        <h3 class="text-sm font-bold text-white flex items-center gap-2">
                            <span class="p-1.5 rounded-lg bg-sky-500/20 text-sky-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                </svg>
                            </span>
                            Update Tautan Zoom Sesi
                        </h3>
                        <button @click="zoomModalOpen = false" class="text-slate-400 hover:text-white transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <div class="py-2">
                        <span class="text-[11px] text-slate-400 block" x-text="zoomData.classTitle"></span>
                        <h4 class="text-xs font-bold text-white" x-text="zoomData.sessionTitle"></h4>
                    </div>

                    <form :action="zoomActionUrl" method="POST" class="space-y-4 mt-2">
                        @csrf
                        @method('PATCH')

                        <div>
                            <label class="block text-xs font-semibold text-slate-300 mb-1">
                                Tautan URL Zoom (Join URL)
                            </label>
                            <input type="url" name="zoom_url" x-model="zoomData.url" placeholder="https://zoom.us/j/1234567890?pwd=..."
                                   class="w-full text-xs rounded-xl bg-slate-800/80 border border-slate-700 text-white placeholder-slate-500 focus:ring-2 focus:ring-sky-500">
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-semibold text-slate-300 mb-1">
                                    Meeting ID
                                </label>
                                <input type="text" name="zoom_meeting_id" x-model="zoomData.meetingId" placeholder="Mis: 890 1234 5678"
                                       class="w-full text-xs rounded-xl bg-slate-800/80 border border-slate-700 text-white placeholder-slate-500 focus:ring-2 focus:ring-sky-500">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-300 mb-1">
                                    Passcode
                                </label>
                                <input type="text" name="zoom_passcode" x-model="zoomData.passcode" placeholder="Mis: 123456"
                                       class="w-full text-xs rounded-xl bg-slate-800/80 border border-slate-700 text-white placeholder-slate-500 focus:ring-2 focus:ring-sky-500">
                            </div>
                        </div>

                        <div class="pt-3 border-t border-slate-800 flex justify-end gap-2">
                            <button type="button" @click="zoomModalOpen = false" class="px-4 py-2 text-xs font-semibold text-slate-300 bg-slate-800 hover:bg-slate-700 rounded-xl transition">
                                Batal
                            </button>
                            <button type="submit" class="px-4 py-2 text-xs font-bold text-white bg-sky-600 hover:bg-sky-500 rounded-xl shadow-lg shadow-sky-600/20 transition">
                                Simpan Link Zoom
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
