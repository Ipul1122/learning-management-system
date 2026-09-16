<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <a href="{{ auth()->user()->hasRole('trainer') ? route('trainer.classes.show', $class) : route('peserta.study.show', $class) }}"
                   class="inline-flex items-center gap-1.5 text-xs text-orange-400 hover:text-orange-300 font-mono font-bold mb-1">
                    &larr; Kembali ke Ruang Belajar Kelas
                </a>
                <h2 class="font-montserrat font-extrabold text-2xl text-white leading-tight">
                    Forum Komunitas &bull; {{ $class->title }}
                </h2>
            </div>
            <div>
                <a href="{{ route('classes.forum.create', $class) }}"
                   class="px-4 py-2 bg-gradient-to-r from-orange-500 to-rose-600 hover:from-orange-600 hover:to-rose-700 text-white rounded-xl text-xs font-bold font-montserrat shadow-md transition flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    <span>Buat Topik Baru</span>
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6 space-y-6">
        <!-- Flash Alert -->
        @if(session('success'))
            <div class="p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-xs font-medium flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="p-4 rounded-xl bg-rose-500/10 border border-rose-500/30 text-rose-400 text-xs font-medium flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <!-- Forum Banner Intro -->
        <div class="bg-gradient-to-r from-slate-900 via-slate-900 to-slate-950 border border-slate-800 rounded-2xl p-5 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div class="space-y-1">
                <h3 class="text-sm font-bold font-montserrat text-white flex items-center gap-2">
                    <svg class="w-4 h-4 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"/></svg>
                    Ruang Diskusi & Konsultasi Interaktif
                </h3>
                <p class="text-xs text-slate-400 font-quicksand">
                    Ajukan pertanyaan seputar materi pelatihan, berdiskusi dengan rekan sekelas, dan dapatkan bimbingan langsung dari Trainer pengampu.
                </p>
            </div>
            <div class="text-xs font-mono text-slate-400 shrink-0">
                Instruktur: <strong class="text-slate-200">{{ $class->trainer?->name ?? 'Trainer' }}</strong>
            </div>
        </div>

        <!-- Threads List -->
        @if($threads->isEmpty())
            <div class="p-12 rounded-2xl bg-slate-900/60 border border-slate-800 text-center space-y-3">
                <div class="w-12 h-12 rounded-2xl bg-slate-800 text-slate-400 mx-auto flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                </div>
                <h4 class="font-montserrat font-bold text-white text-base">Belum Ada Topik Diskusi</h4>
                <p class="text-xs text-slate-400 max-w-sm mx-auto font-quicksand">
                    Jadilah orang pertama yang memulai percakapan atau menanyakan materi yang belum dipahami di kelas ini.
                </p>
                <div class="pt-2">
                    <a href="{{ route('classes.forum.create', $class) }}" class="px-4 py-2 bg-orange-600 hover:bg-orange-500 text-white rounded-xl text-xs font-bold font-montserrat transition inline-block">
                        Mulai Diskusi Baru
                    </a>
                </div>
            </div>
        @else
            <div class="space-y-3">
                @foreach($threads as $thread)
                    <div class="bg-slate-900/60 border {{ $thread->is_pinned ? 'border-amber-500/40 bg-gradient-to-r from-amber-500/5 via-slate-900/60 to-slate-900/60' : 'border-slate-800' }} hover:border-slate-700 rounded-2xl p-5 transition group">
                        <div class="flex items-start justify-between gap-4">
                            <div class="space-y-1.5 flex-1 min-w-0">
                                <div class="flex flex-wrap items-center gap-2">
                                    @if($thread->is_pinned)
                                        <span class="px-2 py-0.5 rounded-md text-[10px] font-bold font-montserrat bg-amber-500/20 text-amber-300 border border-amber-500/30 flex items-center gap-1">
                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path d="M5 4a2 2 0 012-2h6a2 2 0 012 2v14l-5-2.5L5 18V4z"/></svg>
                                            DISEMATKAN
                                        </span>
                                    @endif

                                    @if($thread->is_locked)
                                        <span class="px-2 py-0.5 rounded-md text-[10px] font-bold font-montserrat bg-rose-500/20 text-rose-300 border border-rose-500/30 flex items-center gap-1">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                            TERKUNCI
                                        </span>
                                    @endif

                                    <span class="text-[11px] text-slate-400 font-mono">
                                        {{ $thread->created_at->diffForHumans() }}
                                    </span>
                                </div>

                                <h3 class="text-base font-montserrat font-bold text-white group-hover:text-orange-400 transition leading-snug">
                                    <a href="{{ route('classes.forum.show', [$class, $thread]) }}">
                                        {{ $thread->title }}
                                    </a>
                                </h3>

                                <p class="text-xs text-slate-300 line-clamp-2 leading-relaxed font-quicksand">
                                    {{ Str::limit(strip_tags($thread->content), 160) }}
                                </p>

                                <div class="flex items-center gap-3 pt-2 text-xs text-slate-400">
                                    <div class="flex items-center gap-1.5 font-medium">
                                        <div class="w-5 h-5 rounded-full bg-slate-800 text-slate-300 font-bold text-[10px] flex items-center justify-center">
                                            {{ strtoupper(substr($thread->author->name ?? 'U', 0, 1)) }}
                                        </div>
                                        <span class="text-slate-300">{{ $thread->author->name ?? 'Pengguna' }}</span>
                                        @if($thread->author_id === $class->trainer_id)
                                            <span class="text-[9px] px-1.5 py-0.2 rounded bg-purple-500/20 text-purple-300 font-mono font-bold">Trainer</span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Replies Count Badge -->
                            <div class="text-center shrink-0 p-3 rounded-xl bg-slate-800/60 border border-slate-700/60 min-w-[65px]">
                                <span class="block text-lg font-montserrat font-extrabold text-white">{{ $thread->replies_count }}</span>
                                <span class="block text-[10px] text-slate-400 font-mono uppercase">Balasan</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="pt-4">
                {{ $threads->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
