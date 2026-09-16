<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="text-2xl font-extrabold text-white tracking-tight flex items-center gap-3">
                    <span class="p-2.5 rounded-xl bg-gradient-to-br from-emerald-500/20 to-teal-500/20 border border-emerald-500/30 text-emerald-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                    </span>
                    Katalog Kelas Pelatihan
                </h1>
                <p class="text-xs text-slate-400 mt-1">Pilih kelas pelatihan offline, online, atau hybrid untuk memenuhi syarat 20 JP (900 menit) sertifikasi Anda.</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('peserta.study.index') }}" class="px-4 py-2 text-xs font-bold text-white bg-gradient-to-r from-orange-500 to-amber-500 hover:from-orange-600 hover:to-amber-600 rounded-xl shadow-lg shadow-orange-500/20 transition flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                    Kelas Saya & 20 JP
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6 space-y-6">
        <!-- Filter & Search Bar -->
        <div class="bg-slate-900/60 border border-slate-800/80 rounded-2xl p-4">
            <form method="GET" action="{{ route('peserta.catalog.index') }}" class="flex flex-col sm:flex-row gap-3">
                <div class="flex-1 relative">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari judul kelas atau materi..."
                           class="w-full pl-10 pr-4 py-2 text-xs rounded-xl bg-slate-800/80 border border-slate-700/80 text-white placeholder-slate-500 focus:ring-2 focus:ring-emerald-500">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-500">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                </div>

                <div class="sm:w-52">
                    <select name="branch_id" onchange="this.form.submit()"
                            class="w-full py-2 px-3 text-xs rounded-xl bg-slate-800/80 border border-slate-700/80 text-white focus:ring-2 focus:ring-emerald-500">
                        <option value="">-- Semua Cabang --</option>
                        @foreach($branches as $b)
                            <option value="{{ $b->id }}" {{ request('branch_id') == $b->id ? 'selected' : '' }}>
                                {{ $b->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="sm:w-44">
                    <select name="type" onchange="this.form.submit()"
                            class="w-full py-2 px-3 text-xs rounded-xl bg-slate-800/80 border border-slate-700/80 text-white focus:ring-2 focus:ring-emerald-500">
                        <option value="">-- Semua Tipe --</option>
                        <option value="offline" {{ request('type') == 'offline' ? 'selected' : '' }}>Offline (Maks 40)</option>
                        <option value="online" {{ request('type') == 'online' ? 'selected' : '' }}>Online (Daring)</option>
                        <option value="hybrid" {{ request('type') == 'hybrid' ? 'selected' : '' }}>Hybrid (Fisik + Zoom)</option>
                    </select>
                </div>

                <button type="submit" class="px-4 py-2 text-xs font-semibold text-white bg-slate-800 hover:bg-slate-700 rounded-xl border border-slate-700 transition">
                    Filter
                </button>
                @if(request()->hasAny(['search', 'branch_id', 'type']))
                    <a href="{{ route('peserta.catalog.index') }}" class="px-3 py-2 text-xs font-semibold text-rose-400 bg-rose-500/10 hover:bg-rose-500/20 border border-rose-500/20 rounded-xl transition text-center">
                        Reset
                    </a>
                @endif
            </form>
        </div>

        <!-- Catalog Grid -->
        @if($classes->isEmpty())
            <div class="bg-slate-900/40 border border-slate-800/80 rounded-2xl p-12 text-center">
                <div class="w-16 h-16 rounded-full bg-slate-800/80 flex items-center justify-center mx-auto text-slate-500 mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                </div>
                <h3 class="text-base font-bold text-white mb-1">Tidak Ada Kelas Ditemukan</h3>
                <p class="text-xs text-slate-400 max-w-sm mx-auto">Tidak ada kelas pelatihan yang sesuai dengan kriteria filter pencarian Anda saat ini.</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($classes as $c)
                    @php
                        $isEnrolled = in_array($c->id, $myEnrolledClassIds);
                        $remainingOffline = $c->remainingOfflineSeats();
                    @endphp
                    <div class="bg-slate-900/60 border border-slate-800/80 hover:border-slate-700 rounded-2xl p-5 flex flex-col justify-between transition group shadow-sm hover:shadow-lg">
                        <div>
                            <!-- Type & Branch Badge -->
                            <div class="flex items-center justify-between gap-2 mb-3">
                                <span class="px-2.5 py-1 text-[10px] font-bold rounded-lg uppercase tracking-wider
                                    {{ $c->type === 'online' ? 'bg-sky-500/10 text-sky-400 border border-sky-500/20' : ($c->type === 'offline' ? 'bg-amber-500/10 text-amber-400 border border-amber-500/20' : 'bg-purple-500/10 text-purple-400 border border-purple-500/20') }}">
                                    {{ strtoupper($c->type) }}
                                </span>
                                <span class="text-xs text-slate-400 flex items-center gap-1 truncate">
                                    🏢 {{ $c->branch->name }}
                                </span>
                            </div>

                            <!-- Class Title & Description -->
                            <h3 class="text-base font-bold text-white group-hover:text-emerald-400 transition mb-1 line-clamp-1">
                                {{ $c->title }}
                            </h3>
                            <p class="text-xs text-slate-400 line-clamp-2 mb-4">
                                {{ $c->description ?: 'Pelatihan pemenuhan kompetensi terakreditasi 20 JP.' }}
                            </p>

                            <!-- Trainer & Specs -->
                            <div class="bg-slate-950/40 border border-slate-800/60 rounded-xl p-3 mb-4 text-xs space-y-2">
                                <div class="flex items-center justify-between">
                                    <span class="text-slate-400 text-[11px]">Instruktur / Trainer:</span>
                                    <span class="text-white font-semibold truncate max-w-[140px]">{{ $c->trainer->name }}</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-slate-400 text-[11px]">Beban Belajar:</span>
                                    <span class="text-emerald-400 font-bold">{{ $c->required_jp }} JP ({{ $c->required_jp * 45 }} Menit)</span>
                                </div>

                                <!-- Seat Indicator (Fitur PRD 4.2) -->
                                <div class="pt-2 border-t border-slate-800/60 flex items-center justify-between">
                                    <span class="text-slate-400 text-[11px]">Ketersediaan Kursi:</span>
                                    @if($c->isOffline())
                                        @if($remainingOffline > 0)
                                            <span class="text-amber-400 font-bold">
                                                Tersisa {{ $remainingOffline }} dari {{ $c->offline_capacity }} Kursi
                                            </span>
                                        @else
                                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-rose-500/10 text-rose-400 border border-rose-500/20">
                                                Penuh (Maks 40)
                                            </span>
                                        @endif
                                    @elseif($c->isHybrid())
                                        <div class="text-right">
                                            <span class="text-amber-400 text-[11px] block font-medium">
                                                Fisik: {{ $remainingOffline > 0 ? "Tersisa {$remainingOffline}" : 'Penuh' }}
                                            </span>
                                            <span class="text-sky-400 text-[10px] block">Daring: Tersedia</span>
                                        </div>
                                    @else
                                        <span class="text-sky-400 font-semibold">Kuota Daring Tersedia</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Card Footer / Actions -->
                        <div class="pt-3 border-t border-slate-800/80 flex items-center justify-between gap-2">
                            <span class="text-[11px] text-slate-500">
                                {{ $c->start_date ? $c->start_date->format('d/m/Y') : '-' }} s/d {{ $c->end_date ? $c->end_date->format('d/m/Y') : '-' }}
                            </span>

                            @if($isEnrolled)
                                <a href="{{ route('peserta.study.show', $c) }}"
                                   class="px-3.5 py-1.5 text-xs font-bold text-emerald-400 bg-emerald-500/10 hover:bg-emerald-500/20 border border-emerald-500/20 rounded-xl transition flex items-center gap-1.5">
                                    <span>Sudah Terdaftar</span>
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                    </svg>
                                </a>
                            @elseif($c->isOffline() && $c->isFullOffline())
                                <button disabled class="px-3.5 py-1.5 text-xs font-semibold text-slate-500 bg-slate-800/50 rounded-xl cursor-not-allowed border border-slate-800">
                                    Kursi Penuh
                                </button>
                            @else
                                <a href="{{ route('peserta.catalog.show', $c) }}"
                                   class="px-3.5 py-1.5 text-xs font-bold text-white bg-gradient-to-r from-emerald-500 to-teal-500 hover:from-emerald-600 hover:to-teal-600 rounded-xl shadow-lg shadow-emerald-500/20 transition flex items-center gap-1.5">
                                    Detail & Daftar
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                    </svg>
                                </a>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-6">
                {{ $classes->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
