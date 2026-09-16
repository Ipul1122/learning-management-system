<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <a href="{{ route('news.index') }}" class="inline-flex items-center gap-1.5 text-xs text-orange-400 hover:text-orange-300 font-mono font-bold">
                &larr; Kembali ke Feed Berita
            </a>
            <div class="flex items-center gap-2">
                <span class="px-2.5 py-1 rounded-full text-xs font-bold font-montserrat bg-orange-500/10 text-orange-400 border border-orange-500/20">
                    {{ $post->category }}
                </span>
                @if($post->isGlobal())
                    <span class="px-2.5 py-1 rounded-full text-xs font-bold font-mono bg-blue-500/10 text-blue-400 border border-blue-500/20">
                        Nasional
                    </span>
                @else
                    <span class="px-2.5 py-1 rounded-full text-xs font-bold font-mono bg-amber-500/10 text-amber-400 border border-amber-500/20">
                        {{ $post->branch->name ?? 'Cabang' }}
                    </span>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-6 max-w-4xl mx-auto space-y-8">
        <!-- Article Header -->
        <div class="space-y-4 text-center sm:text-left">
            <h1 class="text-2xl sm:text-4xl font-montserrat font-extrabold text-white leading-tight">
                {{ $post->title }}
            </h1>

            <div class="flex flex-wrap items-center justify-between gap-3 text-xs text-slate-400 border-y border-slate-800 py-3 font-mono">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-gradient-to-tr from-orange-500 to-rose-600 flex items-center justify-center text-white font-bold font-montserrat text-xs">
                        {{ strtoupper(substr($post->author->name ?? 'A', 0, 1)) }}
                    </div>
                    <div>
                        <div class="text-slate-200 font-bold">{{ $post->author->name ?? 'Admin LMS' }}</div>
                        <div class="text-[11px] text-slate-500">Penulis &bull; {{ $post->branch->name ?? 'Pusat' }}</div>
                    </div>
                </div>

                <div class="flex items-center gap-4 text-slate-400">
                    <span>{{ $post->published_at ? $post->published_at->translatedFormat('l, d F Y') : $post->created_at->translatedFormat('l, d F Y') }}</span>
                    <span>&bull;</span>
                    <span>{{ $post->getReadingTime() }} Menit Baca</span>
                </div>
            </div>
        </div>

        <!-- Thumbnail Hero Image -->
        @if($post->thumbnail)
            <div class="rounded-3xl overflow-hidden border border-slate-800 max-h-[420px] bg-slate-900">
                <img src="{{ $post->getThumbnailUrl() }}" alt="{{ $post->title }}" class="w-full h-full object-cover">
            </div>
        @endif

        <!-- Article Body -->
        <article class="prose prose-invert prose-slate max-w-none text-slate-200 text-sm sm:text-base leading-relaxed space-y-4 font-quicksand">
            {!! nl2br(e($post->content)) !!}
        </article>

        <!-- Social Share / Action -->
        <div class="p-6 rounded-2xl bg-slate-900/60 border border-slate-800 flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="space-y-1 text-center sm:text-left">
                <h4 class="font-montserrat font-bold text-white text-sm">Bagikan Pengumuman Ini</h4>
                <p class="text-xs text-slate-400 font-quicksand">Salin tautan berita ini untuk dibagikan kepada rekan belajar lainnya.</p>
            </div>
            <button type="button" onclick="navigator.clipboard.writeText(window.location.href); alert('Tautan artikel berhasil disalin ke clipboard!');"
                    class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-200 rounded-xl text-xs font-bold font-montserrat border border-slate-700 transition flex items-center gap-2 shrink-0">
                <svg class="w-4 h-4 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                <span>Salin Tautan</span>
            </button>
        </div>

        <!-- Related Posts Section -->
        @if($relatedPosts->isNotEmpty())
            <div class="pt-8 border-t border-slate-800 space-y-4">
                <h3 class="text-lg font-montserrat font-extrabold text-white">Berita & Artikel Terkait</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    @foreach($relatedPosts as $rel)
                        <a href="{{ route('news.show', $rel->slug) }}" class="p-4 rounded-2xl bg-slate-900/60 border border-slate-800 hover:border-slate-700 transition block group space-y-2">
                            <span class="text-[10px] font-bold font-montserrat px-2 py-0.5 rounded bg-orange-500/10 text-orange-400 border border-orange-500/20">
                                {{ $rel->category }}
                            </span>
                            <h4 class="font-montserrat font-bold text-xs text-white group-hover:text-orange-400 transition line-clamp-2">
                                {{ $rel->title }}
                            </h4>
                            <div class="text-[10px] text-slate-400 font-mono">
                                {{ $rel->published_at ? $rel->published_at->translatedFormat('d M Y') : $rel->created_at->translatedFormat('d M Y') }}
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</x-app-layout>
