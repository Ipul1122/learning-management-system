<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold font-montserrat bg-orange-500/10 text-orange-400 border border-orange-500/20 mb-1">
                    <span class="w-2 h-2 rounded-full bg-orange-400"></span>
                    Manajemen Konten Publikasi
                </span>
                <h2 class="font-montserrat font-extrabold text-2xl text-white leading-tight">
                    Kelola Berita & Pengumuman
                </h2>
            </div>
            <div>
                <a href="{{ route('admin.news.create') }}" class="px-4 py-2 bg-gradient-to-r from-orange-500 to-rose-600 hover:from-orange-600 hover:to-rose-700 text-white rounded-xl text-xs font-bold font-montserrat shadow-md transition flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    <span>Tulis Berita Baru</span>
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

        <!-- Filter & Search Bar -->
        <form method="GET" action="{{ route('admin.news.index') }}" class="bg-slate-900/60 border border-slate-800 rounded-2xl p-4 flex flex-col md:flex-row items-center gap-3">
            <div class="relative flex-1 w-full">
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Cari judul artikel berita..."
                       class="w-full pl-10 pr-4 py-2 text-xs rounded-xl bg-slate-800/80 border border-slate-700 text-white placeholder-slate-500 focus:ring-2 focus:ring-orange-500 font-quicksand">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
            </div>

            @if(auth()->user()->hasRole('super-admin'))
                <div class="w-full md:w-56">
                    <select name="branch_id" onchange="this.form.submit()"
                            class="w-full py-2 px-3 text-xs rounded-xl bg-slate-800/80 border border-slate-700 text-white focus:ring-2 focus:ring-orange-500">
                        <option value="">-- Semua Cakupan Wilayah --</option>
                        <option value="global" {{ request('branch_id') === 'global' ? 'selected' : '' }}>Nasional (Semua Cabang)</option>
                        @foreach($branches as $b)
                            <option value="{{ $b->id }}" {{ request('branch_id') == $b->id ? 'selected' : '' }}>{{ $b->name }}</option>
                        @endforeach
                    </select>
                </div>
            @endif

            <button type="submit" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-200 text-xs font-bold rounded-xl font-montserrat border border-slate-700 transition shrink-0">
                Filter
            </button>
        </form>

        <!-- Table View -->
        <div class="bg-slate-900/60 border border-slate-800 rounded-2xl overflow-hidden shadow-xl">
            @if($posts->isEmpty())
                <div class="p-12 text-center text-xs text-slate-400 space-y-2">
                    <p>Belum ada artikel berita yang dipublikasikan.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs text-slate-300">
                        <thead class="bg-slate-800/70 text-slate-400 uppercase font-mono text-[11px] border-b border-slate-800">
                            <tr>
                                <th class="py-3 px-4">Judul & Kategori</th>
                                <th class="py-3 px-4">Cakupan Wilayah</th>
                                <th class="py-3 px-4">Penulis</th>
                                <th class="py-3 px-4">Status Publikasi</th>
                                <th class="py-3 px-4">Tanggal Rilis</th>
                                <th class="py-3 px-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800/70">
                            @foreach($posts as $post)
                                <tr class="hover:bg-slate-800/30 transition">
                                    <td class="py-3 px-4">
                                        <div class="font-montserrat font-bold text-white text-sm line-clamp-1">
                                            {{ $post->title }}
                                        </div>
                                        <span class="inline-block mt-0.5 text-[10px] font-bold font-montserrat px-2 py-0.2 rounded bg-orange-500/10 text-orange-400 border border-orange-500/20">
                                            {{ $post->category }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-4 font-mono">
                                        @if($post->isGlobal())
                                            <span class="text-blue-400 font-bold">Nasional (Global)</span>
                                        @else
                                            <span class="text-slate-300">{{ $post->branch->name ?? '-' }}</span>
                                        @endif
                                    </td>
                                    <td class="py-3 px-4 font-semibold text-slate-300">
                                        {{ $post->author->name ?? '-' }}
                                    </td>
                                    <td class="py-3 px-4">
                                        @if($post->is_published)
                                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold font-montserrat bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                                                DIPUBLIKASIKAN
                                            </span>
                                        @else
                                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold font-montserrat bg-slate-800 text-slate-400 border border-slate-700">
                                                DRAFT
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-3 px-4 font-mono text-slate-400">
                                        {{ $post->published_at ? $post->published_at->translatedFormat('d M Y') : '-' }}
                                    </td>
                                    <td class="py-3 px-4 text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            <a href="{{ route('news.show', $post->slug) }}" target="_blank" class="p-1.5 rounded-lg bg-slate-800 text-slate-300 hover:text-white hover:bg-slate-700 transition" title="Lihat Tampilan Publik">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                            </a>
                                            <a href="{{ route('admin.news.edit', $post) }}" class="p-1.5 rounded-lg bg-blue-500/10 text-blue-400 hover:bg-blue-500/20 transition" title="Edit Berita">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                            </a>
                                            <form method="POST" action="{{ route('admin.news.destroy', $post) }}" onsubmit="return confirm('Hapus artikel berita ini secara permanen?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-1.5 rounded-lg bg-rose-500/10 text-rose-400 hover:bg-rose-500/20 transition" title="Hapus Berita">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="p-4 border-t border-slate-800">
                    {{ $posts->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
