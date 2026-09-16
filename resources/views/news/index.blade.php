<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold font-montserrat bg-blue-500/10 text-blue-400 border border-blue-500/20 mb-1">
                    <span class="w-2 h-2 rounded-full bg-blue-400"></span>
                    Portal Informasi & Pengumuman
                </span>
                <h2 class="font-montserrat font-extrabold text-2xl text-white leading-tight">
                    Berita & Pengumuman LMS
                </h2>
            </div>
            @if(auth()->user()->hasRole(['super-admin', 'admin-cabang', 'trainer']))
                <div>
                    <a href="{{ route('admin.news.create') }}" class="px-4 py-2 bg-gradient-to-r from-orange-500 to-rose-600 hover:from-orange-600 hover:to-rose-700 text-white rounded-xl text-xs font-bold font-montserrat shadow-md transition flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        <span>Tulis Berita Baru</span>
                    </a>
                </div>
            @endif
        </div>
    </x-slot>

    <div class="py-6 space-y-8">
        <!-- Filter & Search Bar -->
        <form method="GET" action="{{ route('news.index') }}" class="bg-slate-900/60 border border-slate-800 rounded-2xl p-4 flex flex-col md:flex-row items-center gap-3">
            <!-- Search -->
            <div class="relative flex-1 w-full">
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Cari judul atau topik pengumuman..."
                       class="w-full pl-10 pr-4 py-2 text-xs rounded-xl bg-slate-800/80 border border-slate-700 text-white placeholder-slate-500 focus:ring-2 focus:ring-orange-500 font-quicksand">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
            </div>

            <!-- Category Filter -->
            <div class="w-full md:w-48">
                <select name="category" onchange="this.form.submit()"
                        class="w-full py-2 px-3 text-xs rounded-xl bg-slate-800/80 border border-slate-700 text-white focus:ring-2 focus:ring-orange-500">
                    <option value="">-- Semua Kategori --</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Branch Filter -->
            <div class="w-full md:w-56">
                <select name="branch_id" onchange="this.form.submit()"
                        class="w-full py-2 px-3 text-xs rounded-xl bg-slate-800/80 border border-slate-700 text-white focus:ring-2 focus:ring-orange-500">
                    <option value="">-- Semua Wilayah Cabang --</option>
                    @foreach($branches as $b)
                        <option value="{{ $b->id }}" {{ request('branch_id') == $b->id ? 'selected' : '' }}>{{ $b->name }}</option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="w-full md:w-auto px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-200 text-xs font-bold rounded-xl font-montserrat border border-slate-700 transition shrink-0">
                Filter
            </button>
            @if(request()->anyFilled(['search', 'category', 'branch_id']))
                <a href="{{ route('news.index') }}" class="text-xs text-rose-400 hover:text-rose-300 font-semibold shrink-0">
                    Reset
                </a>
            @endif
        </form>

        <!-- Featured Headline Banner (Jika Halaman 1 & Tanpa Filter Aktif) -->
        @if($featuredPost && $newsPosts->currentPage() === 1 && !request()->anyFilled(['search', 'category', 'branch_id']))
            <a href="{{ route('news.show', $featuredPost->slug) }}" class="block relative rounded-3xl overflow-hidden border border-slate-800 bg-slate-900/80 group hover:border-slate-700 transition shadow-2xl">
                <div class="grid grid-cols-1 lg:grid-cols-12 min-h-[300px]">
                    <div class="lg:col-span-7 p-6 sm:p-8 flex flex-col justify-between space-y-4">
                        <div class="space-y-3">
                            <div class="flex items-center gap-2">
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold font-montserrat bg-orange-500/15 text-orange-400 border border-orange-500/30">
                                    {{ $featuredPost->category }}
                                </span>
                                <span class="text-xs font-mono text-slate-400">
                                    {{ $featuredPost->isGlobal() ? 'Nasional / Seluruh Cabang' : ($featuredPost->branch->name ?? 'Cabang') }}
                                </span>
                            </div>
                            <h3 class="text-xl sm:text-2xl lg:text-3xl font-montserrat font-extrabold text-white group-hover:text-orange-400 transition leading-snug">
                                {{ $featuredPost->title }}
                            </h3>
                            <p class="text-xs sm:text-sm text-slate-300 line-clamp-3 leading-relaxed font-quicksand">
                                {{ Str::limit(strip_tags($featuredPost->content), 220) }}
                            </p>
                        </div>
                        <div class="flex items-center justify-between text-xs text-slate-400 pt-3 border-t border-slate-800 font-mono">
                            <div class="flex items-center gap-2">
                                <span>Oleh: <strong class="text-slate-200">{{ $featuredPost->author->name ?? 'Admin LMS' }}</strong></span>
                                <span>&bull;</span>
                                <span>{{ $featuredPost->published_at ? $featuredPost->published_at->translatedFormat('d F Y') : $featuredPost->created_at->translatedFormat('d F Y') }}</span>
                            </div>
                            <span class="text-orange-400 font-bold font-montserrat flex items-center gap-1 group-hover:translate-x-1 transition">
                                Baca Selengkapnya &rarr;
                            </span>
                        </div>
                    </div>
                    <div class="lg:col-span-5 relative h-56 lg:h-auto overflow-hidden">
                        <img src="{{ $featuredPost->getThumbnailUrl() }}" alt="{{ $featuredPost->title }}"
                             class="w-full h-full object-cover group-hover:scale-105 transition duration-700">
                        <div class="absolute inset-0 bg-gradient-to-t lg:bg-gradient-to-r from-slate-900 via-transparent to-transparent"></div>
                    </div>
                </div>
            </a>
        @endif

        <!-- Grid Berita -->
        @if($newsPosts->isEmpty())
            <div class="p-12 rounded-2xl bg-slate-900/60 border border-slate-800 text-center space-y-3">
                <div class="w-12 h-12 rounded-2xl bg-slate-800 text-slate-400 mx-auto flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                </div>
                <h4 class="font-montserrat font-bold text-white text-base">Tidak Ada Berita / Pengumuman Ditemukan</h4>
                <p class="text-xs text-slate-400 max-w-sm mx-auto">
                    Coba sesuaikan kata kunci pencarian atau ubah filter kategori dan cabang Anda.
                </p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($newsPosts as $post)
                    <article class="bg-slate-900/60 border border-slate-800 hover:border-slate-700 rounded-2xl overflow-hidden flex flex-col justify-between group transition">
                        <div>
                            <!-- Thumbnail -->
                            <a href="{{ route('news.show', $post->slug) }}" class="block relative h-44 overflow-hidden bg-slate-800">
                                <img src="{{ $post->getThumbnailUrl() }}" alt="{{ $post->title }}"
                                     class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                                <div class="absolute top-3 left-3 flex items-center gap-1.5">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold font-montserrat bg-slate-900/80 backdrop-blur-md text-white border border-slate-700">
                                        {{ $post->category }}
                                    </span>
                                </div>
                                @if($post->isGlobal())
                                    <span class="absolute top-3 right-3 px-2 py-0.5 rounded text-[10px] font-bold font-mono bg-blue-500/20 text-blue-300 border border-blue-500/30 backdrop-blur-md">
                                        Nasional
                                    </span>
                                @else
                                    <span class="absolute top-3 right-3 px-2 py-0.5 rounded text-[10px] font-bold font-mono bg-amber-500/20 text-amber-300 border border-amber-500/30 backdrop-blur-md">
                                        {{ $post->branch->name ?? 'Cabang' }}
                                    </span>
                                @endif
                            </a>

                            <!-- Body -->
                            <div class="p-5 space-y-2.5">
                                <div class="text-[11px] text-slate-400 font-mono flex items-center gap-2">
                                    <span>{{ $post->published_at ? $post->published_at->translatedFormat('d M Y') : $post->created_at->translatedFormat('d M Y') }}</span>
                                    <span>&bull;</span>
                                    <span>{{ $post->getReadingTime() }} mnt baca</span>
                                </div>
                                <h4 class="font-montserrat font-bold text-base text-white group-hover:text-orange-400 transition line-clamp-2">
                                    <a href="{{ route('news.show', $post->slug) }}">
                                        {{ $post->title }}
                                    </a>
                                </h4>
                                <p class="text-xs text-slate-300 line-clamp-3 leading-relaxed font-quicksand">
                                    {{ Str::limit(strip_tags($post->content), 120) }}
                                </p>
                            </div>
                        </div>

                        <!-- Footer -->
                        <div class="px-5 pb-5 pt-3 border-t border-slate-800/80 flex items-center justify-between text-xs">
                            <span class="text-slate-400 truncate max-w-[150px]">
                                Oleh: <strong class="text-slate-300">{{ $post->author->name ?? 'Admin' }}</strong>
                            </span>
                            <a href="{{ route('news.show', $post->slug) }}" class="text-orange-400 hover:text-orange-300 font-bold font-montserrat flex items-center gap-1 shrink-0">
                                Baca &rarr;
                            </a>
                        </div>
                    </article>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="pt-4">
                {{ $newsPosts->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
