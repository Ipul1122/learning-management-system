<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div class="flex items-center gap-3">
                <a href="{{ route('trainer.classes.index') }}" class="p-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-400 hover:text-white transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>
                <div>
                    <div class="flex items-center gap-2">
                        <span class="px-2.5 py-0.5 text-[10px] font-bold rounded-lg uppercase tracking-wider
                            {{ $class->type === 'online' ? 'bg-sky-500/10 text-sky-400 border border-sky-500/20' : ($class->type === 'offline' ? 'bg-amber-500/10 text-amber-400 border border-amber-500/20' : 'bg-purple-500/10 text-purple-400 border border-purple-500/20') }}">
                            {{ strtoupper($class->type) }}
                        </span>
                        <span class="text-xs text-slate-500">•</span>
                        <span class="text-xs text-slate-400">Cabang {{ $branch->name }}</span>
                    </div>
                    <h1 class="text-2xl font-extrabold text-white tracking-tight mt-1">{{ $class->title }}</h1>
                </div>
            </div>

            <div class="flex items-center gap-2 flex-wrap">
                <a href="{{ route('classes.forum.index', $class) }}" class="px-4 py-2 text-xs font-bold text-[#FF6B00] bg-[#FF6B00]/10 hover:bg-[#FF6B00]/20 border border-[#FF6B00]/30 rounded-xl transition flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                    </svg>
                    Forum Diskusi Kelas
                </a>
                <a href="{{ route('trainer.quizzes.create') }}?class_id={{ $class->id }}" class="px-4 py-2 text-xs font-bold text-white bg-gradient-to-r from-orange-500 to-amber-500 hover:from-orange-600 hover:to-amber-600 rounded-xl shadow-lg shadow-orange-500/20 transition flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Tambah Kuis ke Kelas Ini
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6 space-y-6 max-w-7xl mx-auto" x-data="{
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
        <div class="bg-slate-900/60 border border-slate-800/80 rounded-2xl p-6">
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                <div class="bg-slate-950/40 border border-slate-800/60 rounded-xl p-3">
                    <span class="text-[10px] text-slate-500 uppercase tracking-wider block">Target / Akumulasi JP</span>
                    <span class="text-base font-bold text-white mt-0.5 block">
                        {{ $class->totalAccumulatedJp() }} / {{ $class->required_jp }} JP
                    </span>
                </div>
                <div class="bg-slate-950/40 border border-slate-800/60 rounded-xl p-3">
                    <span class="text-[10px] text-slate-500 uppercase tracking-wider block">Sesi Terjadwal</span>
                    <span class="text-base font-bold text-sky-400 mt-0.5 block">
                        {{ $class->sessions->count() }} Pertemuan
                    </span>
                </div>
                <div class="bg-slate-950/40 border border-slate-800/60 rounded-xl p-3">
                    <span class="text-[10px] text-slate-500 uppercase tracking-wider block">Total Peserta</span>
                    <span class="text-base font-bold text-emerald-400 mt-0.5 block">
                        {{ $class->enrolled_offline + $class->enrolled_online }} Peserta
                    </span>
                </div>
                <div class="bg-slate-950/40 border border-slate-800/60 rounded-xl p-3">
                    <span class="text-[10px] text-slate-500 uppercase tracking-wider block">Paket Kuis</span>
                    <span class="text-base font-bold text-amber-400 mt-0.5 block">
                        {{ $class->quizzes->count() }} Paket
                    </span>
                </div>
            </div>

            @if($class->description)
                <div class="mt-4 pt-4 border-t border-slate-800/80 text-xs text-slate-300">
                    <span class="font-bold text-slate-400 block mb-0.5">Deskripsi Kelas:</span>
                    {{ $class->description }}
                </div>
            @endif
        </div>

        <!-- Syllabus / Sessions List -->
        <div class="space-y-4">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-bold text-white flex items-center gap-2">
                    <svg class="w-5 h-5 text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    Silabus & Jadwal Pertemuan Sesi ({{ $class->sessions->count() }})
                </h2>
            </div>

            @if($class->sessions->isEmpty())
                <div class="bg-slate-900/40 border border-slate-800/80 rounded-2xl p-8 text-center text-slate-500 text-xs">
                    Admin Cabang belum menambahkan silabus sesi pertemuan pada kelas ini.
                </div>
            @else
                <div class="space-y-3">
                    @foreach($class->sessions as $session)
                        <div class="bg-slate-900/60 border border-slate-800/80 rounded-2xl p-4 flex flex-col md:flex-row md:items-center justify-between gap-4">
                            <div class="flex items-start gap-3">
                                <div class="w-10 h-10 rounded-xl bg-slate-800 border border-slate-700 flex items-center justify-center font-bold text-white text-sm shrink-0">
                                    {{ $session->session_order }}
                                </div>
                                <div>
                                    <h3 class="text-sm font-bold text-white">{{ $session->title }}</h3>
                                    <div class="flex flex-wrap items-center gap-2 mt-1 text-xs text-slate-400">
                                        <span>{{ $session->jp_duration }} JP ({{ $session->minute_duration }} menit)</span>
                                        <span>•</span>
                                        <span class="text-slate-300">
                                            {{ $session->session_date ? $session->session_date->translatedFormat('d F Y, H:i') . ' WIB' : 'Jadwal belum ditentukan' }}
                                        </span>
                                    </div>

                                    @if($session->zoom_url)
                                        <div class="mt-2 text-xs text-sky-400 flex items-center gap-2">
                                            <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                                            <span class="font-semibold">Zoom Ready:</span>
                                            <a href="{{ $session->zoom_url }}" target="_blank" class="underline hover:text-sky-300 truncate max-w-xs">
                                                {{ $session->zoom_url }}
                                            </a>
                                            @if($session->zoom_meeting_id)
                                                <span class="text-slate-400">• ID: {{ $session->zoom_meeting_id }}</span>
                                            @endif
                                            @if($session->zoom_passcode)
                                                <span class="text-slate-400">• Pass: {{ $session->zoom_passcode }}</span>
                                            @endif
                                        </div>
                                    @else
                                        <div class="mt-2 text-xs text-amber-400/80 italic flex items-center gap-1.5">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                            </svg>
                                            Belum ada tautan Zoom untuk sesi ini.
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="flex items-center gap-2 shrink-0 self-end md:self-center">
                                @if($session->zoom_url)
                                    <a href="{{ $session->zoom_url }}" target="_blank" class="px-3 py-1.5 text-xs font-bold text-white bg-sky-600 hover:bg-sky-500 rounded-xl transition flex items-center gap-1.5">
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
                                        class="px-3 py-1.5 text-xs font-semibold text-slate-300 bg-slate-800 hover:bg-slate-700 hover:text-white rounded-xl border border-slate-700 transition flex items-center gap-1.5">
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

        <!-- Attached Quizzes -->
        <div class="space-y-4 pt-4">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-bold text-white flex items-center gap-2">
                    <svg class="w-5 h-5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    Paket Kuis Terhubung ({{ $class->quizzes->count() }})
                </h2>
                <a href="{{ route('trainer.quizzes.create') }}?class_id={{ $class->id }}" class="text-xs text-orange-400 hover:text-orange-300 font-semibold">
                    + Buat Kuis Baru
                </a>
            </div>

            @if($class->quizzes->isEmpty())
                <div class="bg-slate-900/40 border border-slate-800/80 rounded-2xl p-8 text-center text-slate-500 text-xs">
                    Belum ada kuis yang dibuat khusus untuk kelas pelatihan ini.
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($class->quizzes as $quiz)
                        <div class="bg-slate-900/60 border border-slate-800/80 rounded-2xl p-4 flex items-center justify-between gap-4">
                            <div>
                                <h3 class="text-sm font-bold text-white">{{ $quiz->title }}</h3>
                                <div class="flex items-center gap-3 mt-1 text-xs text-slate-400">
                                    <span>{{ $quiz->questions_count }} Butir Soal</span>
                                    <span>•</span>
                                    <span>Durasi: {{ $quiz->time_limit_minutes }} Mnt</span>
                                    <span>•</span>
                                    <span class="text-emerald-400 font-semibold">Passing: {{ (float) $quiz->passing_grade }}%</span>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <a href="{{ route('trainer.quizzes.show', $quiz) }}" class="px-3 py-1.5 text-xs font-semibold text-slate-300 bg-slate-800 hover:bg-slate-700 rounded-lg transition">
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
