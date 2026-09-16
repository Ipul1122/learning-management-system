<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <a href="{{ route('classes.forum.index', $class) }}" class="inline-flex items-center gap-1.5 text-xs text-[#EA580C] hover:text-[#C2410C] font-montserrat font-bold mb-1 transition">
                    &larr; Kembali ke Forum Diskusi Kelas
                </a>
                <h2 class="font-montserrat font-extrabold text-xl sm:text-2xl text-[#1E1B18] leading-tight">
                    {{ $thread->title }}
                </h2>
                <div class="flex items-center gap-2 text-xs text-[#6E675F] font-mono mt-1">
                    <span>Kelas: {{ $class->title }}</span>
                    <span>&bull;</span>
                    <span>{{ $thread->created_at->translatedFormat('d F Y H:i') }}</span>
                </div>
            </div>

            <!-- Moderation Buttons (Khusus Trainer / Admin) -->
            @if($thread->canModerate(auth()->user()))
                <div class="flex items-center gap-2">
                    <!-- Pin / Unpin Form -->
                    <form method="POST" action="{{ route('classes.forum.pin', [$class, $thread]) }}">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="px-3 py-1.5 rounded-xl text-xs font-bold font-montserrat transition flex items-center gap-1.5 shadow-sm {{ $thread->is_pinned ? 'bg-[#FFF7ED] text-[#EA580C] border border-[#FED7AA] hover:bg-[#FFEDD5]' : 'bg-white text-[#6E675F] hover:text-[#1E1B18] border border-[#EBE5DF]' }}">
                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="M5 4a2 2 0 012-2h6a2 2 0 012 2v14l-5-2.5L5 18V4z"/></svg>
                            <span>{{ $thread->is_pinned ? 'Lepas Semat' : 'Sematkan' }}</span>
                        </button>
                    </form>

                    <!-- Lock / Unlock Form -->
                    <form method="POST" action="{{ route('classes.forum.lock', [$class, $thread]) }}">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="px-3 py-1.5 rounded-xl text-xs font-bold font-montserrat transition flex items-center gap-1.5 shadow-sm {{ $thread->is_locked ? 'bg-rose-50 text-rose-700 border border-rose-200 hover:bg-rose-100' : 'bg-white text-[#6E675F] hover:text-[#1E1B18] border border-[#EBE5DF]' }}">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                            <span>{{ $thread->is_locked ? 'Buka Kunci' : 'Kunci Diskusi' }}</span>
                        </button>
                    </form>

                    <!-- Delete Form -->
                    <form method="POST" action="{{ route('classes.forum.destroy', [$class, $thread]) }}" onsubmit="return confirm('Hapus topik diskusi ini dan seluruh balasannya?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="p-2 rounded-xl bg-rose-50 text-rose-600 hover:bg-rose-100 border border-rose-200 transition shadow-sm" title="Hapus Topik">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                    </form>
                </div>
            @endif
        </div>
    </x-slot>

    <div class="py-6 max-w-4xl mx-auto space-y-6" x-data="{ replyTo: null, replyAuthorName: '' }">
        <!-- Flash Alert -->
        @if(session('success'))
            <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-medium flex items-center gap-2 shadow-sm">
                <svg class="w-4 h-4 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-medium flex items-center gap-2 shadow-sm">
                <svg class="w-4 h-4 text-rose-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <!-- Status Warning Banners -->
        @if($thread->is_locked)
            <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs flex items-center gap-3 shadow-sm">
                <svg class="w-5 h-5 text-rose-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                <span>Diskusi ini telah dikunci oleh instruktur. Tanggapan baru dinonaktifkan.</span>
            </div>
        @endif

        <!-- Main Thread Box -->
        <article class="bg-white border {{ $thread->is_pinned ? 'border-[#FED7AA] shadow-orange-500/5' : 'border-[#EBE5DF]' }} rounded-2xl p-6 sm:p-8 space-y-5 shadow-sm">
            <!-- Author Meta Header -->
            <div class="flex items-center justify-between gap-3 border-b border-[#EBE5DF] pb-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-gradient-to-tr {{ $thread->author_id === $class->trainer_id ? 'from-purple-600 to-indigo-600' : 'from-[#FF6B00] to-[#E11D48]' }} flex items-center justify-center text-white font-bold font-montserrat text-sm shadow-md">
                        {{ strtoupper(substr($thread->author->name ?? 'U', 0, 1)) }}
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="font-montserrat font-bold text-sm text-[#1E1B18]">{{ $thread->author->name ?? 'Pengguna' }}</span>
                            @if($thread->author_id === $class->trainer_id)
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold font-mono bg-purple-50 text-purple-700 border border-purple-200">
                                    Trainer Pengampu
                                </span>
                            @else
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold font-mono bg-[#FAF8F5] text-[#6E675F] border border-[#EBE5DF]">
                                    Peserta
                                </span>
                            @endif
                        </div>
                        <span class="text-[11px] text-[#A8A29E] font-mono">{{ $thread->created_at->diffForHumans() }}</span>
                    </div>
                </div>

                <div class="flex items-center gap-1.5">
                    @if($thread->is_pinned)
                        <span class="px-2.5 py-1 rounded-lg text-[10px] font-bold font-montserrat bg-[#FFF7ED] text-[#EA580C] border border-[#FED7AA]">
                            PINNED
                        </span>
                    @endif
                </div>
            </div>

            <!-- Content -->
            <div class="text-[#1E1B18] text-sm leading-relaxed whitespace-pre-line font-quicksand">
                {{ $thread->content }}
            </div>

            <!-- Thread Footer -->
            <div class="pt-4 border-t border-[#EBE5DF] flex items-center justify-between text-xs text-[#6E675F]">
                <span class="font-mono">Total {{ $thread->replies->count() }} Tanggapan Diskusi</span>
                @if(!$thread->is_locked)
                    <button type="button" @click="replyTo = null; replyAuthorName = '{{ $thread->author->name }}'; document.getElementById('reply-box').scrollIntoView({behavior: 'smooth'})"
                            class="text-[#EA580C] hover:text-[#C2410C] font-bold font-montserrat flex items-center gap-1 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/></svg>
                        <span>Beri Tanggapan</span>
                    </button>
                @endif
            </div>
        </article>

        <!-- Daftar Tanggapan / Balasan Berjenjang -->
        <div class="space-y-4 pt-2">
            <h3 class="font-montserrat font-extrabold text-base text-[#1E1B18] flex items-center gap-2">
                <span>Tanggapan Diskusi</span>
                <span class="text-xs font-mono font-normal text-[#A8A29E]">({{ $thread->replies->count() }})</span>
            </h3>

            @if($thread->rootReplies->isEmpty())
                <div class="p-8 rounded-2xl bg-white border border-[#EBE5DF] text-center text-xs text-[#6E675F] shadow-sm font-quicksand">
                    Belum ada tanggapan pada topik ini. Berikan jawaban atau masukan pertama Anda di bawah.
                </div>
            @else
                <div class="space-y-4">
                    @foreach($thread->rootReplies as $reply)
                        <div class="bg-white border border-[#EBE5DF] rounded-2xl p-5 space-y-3 shadow-sm">
                            <!-- Root Reply Header -->
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-8 h-8 rounded-xl bg-[#FAF8F5] border border-[#EBE5DF] text-[#1E1B18] font-bold text-xs flex items-center justify-center shadow-xs">
                                        {{ strtoupper(substr($reply->author->name ?? 'U', 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <span class="font-bold text-xs text-[#1E1B18] font-montserrat">{{ $reply->author->name ?? 'Pengguna' }}</span>
                                            @if($reply->author_id === $class->trainer_id)
                                                <span class="px-2 py-0.2 rounded text-[9px] font-bold font-mono bg-purple-50 text-purple-700 border border-purple-200">
                                                    Trainer
                                                </span>
                                            @endif
                                        </div>
                                        <span class="text-[10px] text-[#A8A29E] font-mono">{{ $reply->created_at->diffForHumans() }}</span>
                                    </div>
                                </div>

                                @if(!$thread->is_locked)
                                    <button type="button" @click="replyTo = {{ $reply->id }}; replyAuthorName = '{{ $reply->author->name }}'; document.getElementById('reply-box').scrollIntoView({behavior: 'smooth'})"
                                            class="text-xs text-[#6E675F] hover:text-[#FF6B00] transition font-mono flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/></svg>
                                        <span>Balas</span>
                                    </button>
                                @endif
                            </div>

                            <!-- Root Reply Body -->
                            <div class="text-xs text-[#1E1B18] leading-relaxed font-quicksand pl-10 whitespace-pre-line">
                                {{ $reply->reply_content }}
                            </div>

                            <!-- Nested / Children Replies -->
                            @if($reply->children->isNotEmpty())
                                <div class="ml-10 mt-3 pt-3 border-l-2 border-[#EBE5DF] pl-4 space-y-3">
                                    @foreach($reply->children as $child)
                                        <div class="bg-[#FAF8F5] rounded-xl p-3.5 border border-[#EBE5DF] space-y-2">
                                            <div class="flex items-center justify-between">
                                                <div class="flex items-center gap-2">
                                                    <div class="w-6 h-6 rounded-lg bg-white border border-[#EBE5DF] text-[#6E675F] text-[10px] font-bold flex items-center justify-center shadow-xs">
                                                        {{ strtoupper(substr($child->author->name ?? 'U', 0, 1)) }}
                                                    </div>
                                                    <span class="font-bold text-xs text-[#1E1B18]">{{ $child->author->name ?? 'Pengguna' }}</span>
                                                    @if($child->author_id === $class->trainer_id)
                                                        <span class="px-1.5 py-0.2 rounded text-[9px] font-bold font-mono bg-purple-50 text-purple-700 border border-purple-200">
                                                            Trainer
                                                        </span>
                                                    @endif
                                                </div>
                                                <span class="text-[10px] text-[#A8A29E] font-mono">{{ $child->created_at->diffForHumans() }}</span>
                                            </div>
                                            <div class="text-xs text-[#1E1B18] leading-relaxed font-quicksand pl-8 whitespace-pre-line">
                                                {{ $child->reply_content }}
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Form Kirim Tanggapan Baru (Jika Tidak Terkunci) -->
        @if(!$thread->is_locked)
            <div id="reply-box" class="bg-white border border-[#EBE5DF] rounded-2xl p-6 sm:p-8 space-y-4 shadow-sm">
                <div class="flex items-center justify-between">
                    <h4 class="font-montserrat font-bold text-sm text-[#1E1B18] flex items-center gap-2">
                        <svg class="w-4 h-4 text-[#EA580C]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                        <span>Kirim Tanggapan</span>
                    </h4>

                    <!-- Indicator jika sedang membalas child reply -->
                    <div x-show="replyTo" class="flex items-center gap-2 text-xs text-[#EA580C] font-mono">
                        <span>Membalas: <strong x-text="replyAuthorName"></strong></span>
                        <button type="button" @click="replyTo = null; replyAuthorName = ''" class="text-rose-600 hover:text-rose-700 text-[10px] font-bold underline">
                            (Batal)
                        </button>
                    </div>
                </div>

                <form method="POST" action="{{ route('classes.forum.reply', [$class, $thread]) }}" class="space-y-4">
                    @csrf
                    <input type="hidden" name="parent_reply_id" :value="replyTo">

                    <textarea name="reply_content" rows="4" required
                              placeholder="Tuliskan jawaban, solusi, atau penjelasan tambahan Anda di sini..."
                              class="w-full px-4 py-3 text-xs sm:text-sm rounded-xl bg-white border border-[#EBE5DF] text-[#1E1B18] placeholder-[#A8A29E] focus:ring-2 focus:ring-[#FF6B00] focus:border-transparent font-quicksand leading-relaxed shadow-inner"></textarea>

                    <div class="flex items-center justify-end gap-3">
                        <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-[#FF6B00] to-[#E11D48] hover:from-[#EA580C] hover:to-[#DC2626] text-white rounded-xl text-xs font-bold font-montserrat shadow-md shadow-orange-500/10 transition">
                            Kirim Balasan
                        </button>
                    </div>
                </form>
            </div>
        @endif
    </div>
</x-app-layout>
