<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <a href="{{ route('news.index') }}" class="inline-flex items-center gap-1.5 text-xs text-[#FF6B00] hover:text-[#EA580C] font-montserrat font-bold">
                &larr; Kembali ke Feed Berita
            </a>
            <div class="flex items-center gap-2">
                <span class="px-2.5 py-1 rounded-full text-xs font-bold font-montserrat bg-[#FFF7ED] text-[#EA580C] border border-[#FED7AA]">
                    {{ $post->category }}
                </span>
                @if($post->isGlobal())
                    <span class="px-2.5 py-1 rounded-full text-xs font-bold font-mono bg-blue-50 text-blue-700 border border-blue-200">
                        Nasional
                    </span>
                @else
                    <span class="px-2.5 py-1 rounded-full text-xs font-bold font-mono bg-amber-50 text-amber-700 border border-amber-200">
                        {{ $post->branch->name ?? 'Cabang' }}
                    </span>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-8 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
        <!-- Article Header -->
        <div class="space-y-4 text-center sm:text-left">
            <h1 class="text-2xl sm:text-4xl font-montserrat font-extrabold text-[#1E1B18] leading-tight">
                {{ $post->title }}
            </h1>

            <div class="flex flex-wrap items-center justify-between gap-3 text-xs text-[#6E675F] border-y border-[#EBE5DF] py-3 font-quicksand">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-orange-50 border border-orange-200 flex items-center justify-center text-[#FF6B00] font-bold font-montserrat text-xs shadow-sm">
                        {{ strtoupper(substr($post->author->name ?? 'A', 0, 1)) }}
                    </div>
                    <div>
                        <div class="text-[#1E1B18] font-bold font-montserrat">{{ $post->author->name ?? 'Admin LMS' }}</div>
                        <div class="text-[11px] text-[#6E675F]">Penulis &bull; {{ $post->branch->name ?? 'Pusat' }}</div>
                    </div>
                </div>

                <div class="flex items-center gap-4 text-[#6E675F]">
                    <span>{{ $post->published_at ? $post->published_at->translatedFormat('l, d F Y') : $post->created_at->translatedFormat('l, d F Y') }}</span>
                    <span>&bull;</span>
                    <span>{{ $post->getReadingTime() }} Menit Baca</span>
                </div>
            </div>
        </div>

        <!-- Thumbnail Hero Image -->
        @if($post->thumbnail)
            <div class="rounded-3xl overflow-hidden border border-[#EBE5DF] max-h-[420px] bg-[#FAF8F5] shadow-sm">
                <img src="{{ $post->getThumbnailUrl() }}" alt="{{ $post->title }}" class="w-full h-full object-cover">
            </div>
        @endif

        <!-- Article Body -->
        <article class="bg-white border border-[#EBE5DF] rounded-3xl p-6 sm:p-8 shadow-sm text-[#1E1B18] text-sm sm:text-base leading-relaxed space-y-4 font-quicksand">
            {!! nl2br(e($post->content)) !!}
        </article>

        <!-- Social Share / Action -->
        <div class="p-6 rounded-2xl bg-white border border-[#EBE5DF] flex flex-col sm:flex-row items-center justify-between gap-4 shadow-sm">
            <div class="space-y-1 text-center sm:text-left">
                <h4 class="font-montserrat font-extrabold text-[#1E1B18] text-sm">Bagikan Pengumuman Ini</h4>
                <p class="text-xs text-[#6E675F] font-quicksand">Salin tautan berita ini untuk dibagikan kepada rekan belajar lainnya.</p>
            </div>
            <button type="button" onclick="navigator.clipboard.writeText(window.location.href); alert('Tautan artikel berhasil disalin ke clipboard!');"
                    class="px-4 py-2 bg-[#FAF8F5] hover:bg-[#EBE5DF] text-[#1E1B18] rounded-xl text-xs font-bold font-montserrat border border-[#EBE5DF] transition flex items-center gap-2 shrink-0">
                <svg class="w-4 h-4 text-[#FF6B00]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                <span>Salin Tautan</span>
            </button>
        </div>

        <!-- Related Posts Section -->
        @if($relatedPosts->isNotEmpty())
            <div class="pt-8 border-t border-[#EBE5DF] space-y-4">
                <h3 class="text-lg font-montserrat font-extrabold text-[#1E1B18]">Berita & Artikel Terkait</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    @foreach($relatedPosts as $rel)
                        <a href="{{ route('news.show', $rel->slug) }}" class="p-5 rounded-2xl bg-white border border-[#EBE5DF] hover:border-[#FF6B00]/40 transition block group space-y-2.5 shadow-sm hover:shadow-md">
                            <span class="text-[10px] font-bold font-montserrat px-2 py-0.5 rounded bg-[#FFF7ED] text-[#EA580C] border border-[#FED7AA]">
                                {{ $rel->category }}
                            </span>
                            <h4 class="font-montserrat font-bold text-xs text-[#1E1B18] group-hover:text-[#FF6B00] transition line-clamp-2">
                                {{ $rel->title }}
                            </h4>
                            <div class="text-[10px] text-[#6E675F] font-mono">
                                {{ $rel->published_at ? $rel->published_at->translatedFormat('d M Y') : $rel->created_at->translatedFormat('d M Y') }}
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</x-app-layout>
