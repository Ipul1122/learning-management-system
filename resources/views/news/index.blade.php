<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold font-montserrat bg-[#FFF7ED] text-[#EA580C] border border-[#FED7AA] mb-1">
                    <span class="w-2 h-2 rounded-full bg-[#FF6B00]"></span>
                    Portal Informasi & Pengumuman
                </span>
                <h2 class="font-montserrat font-extrabold text-2xl sm:text-3xl text-[#1E1B18] tracking-tight leading-tight">
                    Berita & Pengumuman LMS
                </h2>
            </div>
            @if(auth()->user()->hasRole(['super-admin', 'admin-cabang', 'trainer']))
                <div>
                    <a href="{{ route('admin.news.create') }}" class="px-5 py-2.5 bg-gradient-to-r from-[#FF6B00] to-[#E11D48] hover:from-[#EA580C] hover:to-[#DC2626] text-white rounded-xl text-xs font-bold font-montserrat shadow-md hover:shadow-lg transition flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        <span>Tulis Berita Baru</span>
                    </a>
                </div>
            @endif
        </div>
    </x-slot>

    <div class="py-8 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
        <!-- Filter & Search Bar -->
        <form method="GET" action="{{ route('news.index') }}" class="bg-white border border-[#EBE5DF] rounded-2xl p-4 shadow-sm flex flex-col md:flex-row items-center gap-3">
            <!-- Search -->
            <div class="relative flex-1 w-full">
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Cari judul atau topik pengumuman..."
                       class="w-full pl-10 pr-4 py-2.5 text-xs rounded-xl bg-[#FAF8F5] border border-[#EBE5DF] text-[#1E1B18] placeholder-[#9CA3AF] focus:bg-white focus:ring-2 focus:ring-[#FF6B00] font-quicksand transition">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-[#6E675F]">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
            </div>

            <!-- Category Filter -->
            <div class="w-full md:w-48">
                <select name="category" onchange="this.form.submit()"
                        class="w-full py-2.5 px-3 text-xs rounded-xl bg-[#FAF8F5] border border-[#EBE5DF] text-[#1E1B18] focus:bg-white focus:ring-2 focus:ring-[#FF6B00] font-quicksand">
                    <option value="">-- Semua Kategori --</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Branch Filter -->
            <div class="w-full md:w-56">
                <select name="branch_id" onchange="this.form.submit()"
                        class="w-full py-2.5 px-3 text-xs rounded-xl bg-[#FAF8F5] border border-[#EBE5DF] text-[#1E1B18] focus:bg-white focus:ring-2 focus:ring-[#FF6B00] font-quicksand">
                    <option value="">-- Semua Wilayah Cabang --</option>
                    @foreach($branches as $b)
                        <option value="{{ $b->id }}" {{ request('branch_id') == $b->id ? 'selected' : '' }}>{{ $b->name }}</option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="w-full md:w-auto px-5 py-2.5 bg-[#FAF8F5] hover:bg-[#EBE5DF] text-[#1E1B18] text-xs font-bold rounded-xl font-montserrat border border-[#EBE5DF] transition shrink-0">
                Filter
            </button>
            @if(request()->anyFilled(['search', 'category', 'branch_id']))
                <a href="{{ route('news.index') }}" class="text-xs text-rose-600 hover:text-rose-700 font-bold font-montserrat shrink-0">
                    Reset
                </a>
            @endif
        </form>

        <!-- Featured Headline Banner (Jika Halaman 1 & Tanpa Filter Aktif) -->
        @if($featuredPost && $newsPosts->currentPage() === 1 && !request()->anyFilled(['search', 'category', 'branch_id']))
            <a href="{{ route('news.show', $featuredPost->slug) }}" class="block relative rounded-3xl overflow-hidden border border-[#EBE5DF] bg-white group hover:border-[#FF6B00]/40 transition-all duration-300 shadow-sm hover:shadow-xl">
                <div class="grid grid-cols-1 lg:grid-cols-12 min-h-[300px]">
                    <div class="lg:col-span-7 p-6 sm:p-8 flex flex-col justify-between space-y-4">
                        <div class="space-y-3">
                            <div class="flex items-center gap-2">
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold font-montserrat bg-[#FFF7ED] text-[#EA580C] border border-[#FED7AA]">
                                    {{ $featuredPost->category }}
                                </span>
                                <span class="text-xs font-mono text-[#6E675F]">
                                    {{ $featuredPost->isGlobal() ? 'Nasional / Seluruh Cabang' : ($featuredPost->branch->name ?? 'Cabang') }}
                                </span>
                            </div>
                            <h3 class="text-xl sm:text-2xl lg:text-3xl font-montserrat font-extrabold text-[#1E1B18] group-hover:text-[#FF6B00] transition leading-snug">
                                {{ $featuredPost->title }}
                            </h3>
                            <p class="text-xs sm:text-sm text-[#6E675F] line-clamp-3 leading-relaxed font-quicksand">
                                {{ Str::limit(strip_tags($featuredPost->content), 220) }}
                            </p>
                        </div>
                        <div class="flex items-center justify-between text-xs text-[#6E675F] pt-3 border-t border-[#EBE5DF] font-quicksand">
                            <div class="flex items-center gap-2">
                                <span>Oleh: <strong class="text-[#1E1B18] font-bold font-montserrat">{{ $featuredPost->author->name ?? 'Admin LMS' }}</strong></span>
                                <span>&bull;</span>
                                <span>{{ $featuredPost->published_at ? $featuredPost->published_at->translatedFormat('d F Y') : $featuredPost->created_at->translatedFormat('d F Y') }}</span>
                            </div>
                            <span class="text-[#FF6B00] font-bold font-montserrat flex items-center gap-1 group-hover:translate-x-1 transition">
                                Baca Selengkapnya &rarr;
                            </span>
                        </div>
                    </div>
                    <div class="lg:col-span-5 relative h-56 lg:h-auto overflow-hidden bg-[#FAF8F5]">
                        <img src="{{ $featuredPost->getThumbnailUrl() }}" alt="{{ $featuredPost->title }}"
                             class="w-full h-full object-cover group-hover:scale-105 transition duration-700">
                    </div>
                </div>
            </a>
        @endif

        <!-- Grid Berita -->
        @if($newsPosts->isEmpty())
            <div class="p-12 rounded-2xl bg-white border border-[#EBE5DF] text-center space-y-3 shadow-sm">
                <div class="w-14 h-14 rounded-2xl bg-orange-50 text-[#FF6B00] border border-orange-100 mx-auto flex items-center justify-center mb-3">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                </div>
                <h4 class="font-montserrat font-extrabold text-[#1E1B18] text-base">Tidak Ada Berita / Pengumuman Ditemukan</h4>
                <p class="text-xs text-[#6E675F] max-w-sm mx-auto font-quicksand">
                    Coba sesuaikan kata kunci pencarian atau ubah filter kategori dan cabang Anda.
                </p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($newsPosts as $post)
                    <article class="bg-white border border-[#EBE5DF] hover:border-[#FF6B00]/40 rounded-2xl overflow-hidden flex flex-col justify-between group transition shadow-sm hover:shadow-lg duration-200">
                        <div>
                            <!-- Thumbnail -->
                            <a href="{{ route('news.show', $post->slug) }}" class="block relative h-48 overflow-hidden bg-[#FAF8F5]">
                                <img src="{{ $post->getThumbnailUrl() }}" alt="{{ $post->title }}"
                                     class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                                <div class="absolute top-3 left-3 flex items-center gap-1.5">
                                    <span class="px-2.5 py-1 rounded-md text-[10px] font-bold font-montserrat bg-black/60 backdrop-blur-md text-white border border-white/20">
                                        {{ $post->category }}
                                    </span>
                                </div>
                                @if($post->isGlobal())
                                    <span class="absolute top-3 right-3 px-2.5 py-1 rounded-md text-[10px] font-bold font-mono bg-blue-600/90 text-white backdrop-blur-md shadow-sm">
                                        Nasional
                                    </span>
                                @else
                                    <span class="absolute top-3 right-3 px-2.5 py-1 rounded-md text-[10px] font-bold font-mono bg-amber-600/90 text-white backdrop-blur-md shadow-sm">
                                        {{ $post->branch->name ?? 'Cabang' }}
                                    </span>
                                @endif
                            </a>

                            <!-- Body -->
                            <div class="p-5 space-y-2.5">
                                <div class="text-[11px] text-[#6E675F] font-mono flex items-center gap-2">
                                    <span>{{ $post->published_at ? $post->published_at->translatedFormat('d M Y') : $post->created_at->translatedFormat('d M Y') }}</span>
                                    <span>&bull;</span>
                                    <span>{{ $post->getReadingTime() }} mnt baca</span>
                                </div>
                                <h4 class="font-montserrat font-extrabold text-base text-[#1E1B18] group-hover:text-[#FF6B00] transition line-clamp-2">
                                    <a href="{{ route('news.show', $post->slug) }}">
                                        {{ $post->title }}
                                    </a>
                                </h4>
                                <p class="text-xs text-[#6E675F] line-clamp-3 leading-relaxed font-quicksand">
                                    {{ Str::limit(strip_tags($post->content), 120) }}
                                </p>
                            </div>
                        </div>

                        <!-- Footer -->
                        <div class="px-5 pb-5 pt-3 border-t border-[#EBE5DF] flex items-center justify-between text-xs font-quicksand">
                            <span class="text-[#6E675F] truncate max-w-[150px]">
                                Oleh: <strong class="text-[#1E1B18] font-bold font-montserrat">{{ $post->author->name ?? 'Admin' }}</strong>
                            </span>
                            <a href="{{ route('news.show', $post->slug) }}" class="text-[#FF6B00] hover:text-[#EA580C] font-bold font-montserrat flex items-center gap-1 shrink-0">
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
