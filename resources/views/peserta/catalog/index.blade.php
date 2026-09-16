<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold font-montserrat bg-[#FFF7ED] text-[#EA580C] border border-[#FED7AA] mb-2">
                    <span>🏢</span>
                    <span>Katalog Pembelajaran</span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-montserrat font-extrabold text-[#1E1B18] tracking-tight flex items-center gap-3">
                    <span class="p-2 rounded-xl bg-orange-50 border border-orange-200/80 text-[#FF6B00]">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                    </span>
                    <span>Katalog Kelas Pelatihan</span>
                </h1>
                <p class="text-sm font-quicksand text-[#6E675F] mt-1">Pilih kelas pelatihan offline, online, atau hybrid untuk memenuhi syarat 20 JP (900 menit) sertifikasi Anda.</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('peserta.study.index') }}" class="px-5 py-2.5 text-xs font-montserrat font-bold text-white bg-gradient-to-r from-[#FF6B00] to-[#E11D48] hover:from-[#EA580C] hover:to-[#DC2626] rounded-xl shadow-md hover:shadow-lg transition flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                    <span>Kelas Saya & 20 JP</span>
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
        <!-- Filter & Search Bar -->
        <div class="bg-white border border-[#EBE5DF] rounded-2xl p-4 shadow-sm">
            <form method="GET" action="{{ route('peserta.catalog.index') }}" class="flex flex-col sm:flex-row gap-3">
                <div class="flex-1 relative">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari judul kelas atau materi..."
                           class="w-full pl-10 pr-4 py-2.5 text-xs rounded-xl bg-[#FAF8F5] border border-[#EBE5DF] text-[#1E1B18] placeholder-[#9CA3AF] focus:bg-white focus:ring-2 focus:ring-[#FF6B00] focus:border-transparent font-quicksand transition">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-[#6E675F]">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                </div>

                <div class="sm:w-52">
                    <select name="branch_id" onchange="this.form.submit()"
                            class="w-full py-2.5 px-3 text-xs rounded-xl bg-[#FAF8F5] border border-[#EBE5DF] text-[#1E1B18] focus:bg-white focus:ring-2 focus:ring-[#FF6B00] font-quicksand">
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
                            class="w-full py-2.5 px-3 text-xs rounded-xl bg-[#FAF8F5] border border-[#EBE5DF] text-[#1E1B18] focus:bg-white focus:ring-2 focus:ring-[#FF6B00] font-quicksand">
                        <option value="">-- Semua Tipe --</option>
                        <option value="offline" {{ request('type') == 'offline' ? 'selected' : '' }}>Offline (Maks 40)</option>
                        <option value="online" {{ request('type') == 'online' ? 'selected' : '' }}>Online (Daring)</option>
                        <option value="hybrid" {{ request('type') == 'hybrid' ? 'selected' : '' }}>Hybrid (Fisik + Zoom)</option>
                    </select>
                </div>

                <button type="submit" class="px-4 py-2.5 text-xs font-montserrat font-bold text-[#1E1B18] bg-[#FAF8F5] hover:bg-[#EBE5DF] rounded-xl border border-[#EBE5DF] transition">
                    Filter
                </button>
                @if(request()->hasAny(['search', 'branch_id', 'type']))
                    <a href="{{ route('peserta.catalog.index') }}" class="px-3.5 py-2.5 text-xs font-montserrat font-bold text-rose-600 bg-rose-50 hover:bg-rose-100 border border-rose-200 rounded-xl transition text-center">
                        Reset
                    </a>
                @endif
            </form>
        </div>

        <!-- Catalog Grid -->
        @if($classes->isEmpty())
            <div class="bg-white border border-[#EBE5DF] rounded-2xl p-12 text-center shadow-sm">
                <div class="w-16 h-16 rounded-2xl bg-orange-50 border border-orange-100 flex items-center justify-center mx-auto text-[#FF6B00] mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                </div>
                <h3 class="text-base font-montserrat font-extrabold text-[#1E1B18] mb-1">Tidak Ada Kelas Ditemukan</h3>
                <p class="text-xs font-quicksand text-[#6E675F] max-w-sm mx-auto">Tidak ada kelas pelatihan yang sesuai dengan kriteria filter pencarian Anda saat ini.</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($classes as $c)
                    @php
                        $isEnrolled = in_array($c->id, $myEnrolledClassIds);
                        $remainingOffline = $c->remainingOfflineSeats();
                    @endphp
                    <div class="bg-white border border-[#EBE5DF] hover:border-[#FF6B00]/40 rounded-2xl p-5 sm:p-6 flex flex-col justify-between transition group shadow-sm hover:shadow-lg duration-200">
                        <div>
                            <!-- Type & Branch Badge -->
                            <div class="flex items-center justify-between gap-2 mb-3">
                                <span class="px-2.5 py-1 text-[10px] font-montserrat font-bold rounded-lg uppercase tracking-wider
                                    {{ $c->type === 'online' ? 'bg-sky-50 text-sky-700 border border-sky-200' : ($c->type === 'offline' ? 'bg-amber-50 text-amber-700 border border-amber-200' : 'bg-purple-50 text-purple-700 border border-purple-200') }}">
                                    {{ strtoupper($c->type) }}
                                </span>
                                <span class="text-xs font-quicksand text-[#6E675F] flex items-center gap-1 truncate">
                                    🏢 {{ $c->branch->name }}
                                </span>
                            </div>

                            <!-- Class Title & Description -->
                            <h3 class="text-base font-montserrat font-extrabold text-[#1E1B18] group-hover:text-[#FF6B00] transition mb-1.5 line-clamp-1">
                                {{ $c->title }}
                            </h3>
                            <p class="text-xs font-quicksand text-[#6E675F] line-clamp-2 mb-4 leading-relaxed">
                                {{ $c->description ?: 'Pelatihan pemenuhan kompetensi terakreditasi 20 JP.' }}
                            </p>

                            <!-- Trainer & Specs -->
                            <div class="bg-[#FAF8F5] border border-[#EBE5DF] rounded-xl p-3.5 mb-4 text-xs space-y-2.5">
                                <div class="flex items-center justify-between">
                                    <span class="text-[#6E675F] text-[11px] font-quicksand">Instruktur / Trainer:</span>
                                    <span class="text-[#1E1B18] font-bold font-montserrat truncate max-w-[140px]">{{ $c->trainer->name }}</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-[#6E675F] text-[11px] font-quicksand">Beban Belajar:</span>
                                    <span class="text-emerald-700 font-bold font-montserrat">{{ $c->required_jp }} JP ({{ $c->required_jp * 45 }} Menit)</span>
                                </div>

                                <!-- Seat Indicator (Fitur PRD 4.2) -->
                                <div class="pt-2 border-t border-[#EBE5DF] flex items-center justify-between">
                                    <span class="text-[#6E675F] text-[11px] font-quicksand">Ketersediaan Kursi:</span>
                                    @if($c->isOffline())
                                        @if($remainingOffline > 0)
                                            <span class="text-[#EA580C] font-bold font-montserrat">
                                                Tersisa {{ $remainingOffline }} dari {{ $c->offline_capacity }} Kursi
                                            </span>
                                        @else
                                            <span class="px-2 py-0.5 rounded text-[10px] font-bold font-montserrat bg-rose-50 text-rose-700 border border-rose-200">
                                                Penuh (Maks 40)
                                            </span>
                                        @endif
                                    @elseif($c->isHybrid())
                                        <div class="text-right">
                                            <span class="text-[#EA580C] text-[11px] block font-bold font-montserrat">
                                                Fisik: {{ $remainingOffline > 0 ? "Tersisa {$remainingOffline}" : 'Penuh' }}
                                            </span>
                                            <span class="text-sky-700 text-[10px] block font-semibold font-montserrat">Daring: Tersedia</span>
                                        </div>
                                    @else
                                        <span class="text-sky-700 font-bold font-montserrat">Kuota Daring Tersedia</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Card Footer / Actions -->
                        <div class="pt-3 border-t border-[#EBE5DF] flex items-center justify-between gap-2">
                            <span class="text-[11px] font-quicksand text-[#6E675F] flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 text-[#6E675F]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                {{ $c->start_date ? $c->start_date->format('d/m/Y') : '-' }} s/d {{ $c->end_date ? $c->end_date->format('d/m/Y') : '-' }}
                            </span>

                            @if($isEnrolled)
                                <a href="{{ route('peserta.study.show', $c) }}"
                                   class="px-3.5 py-1.5 text-xs font-montserrat font-bold text-emerald-700 bg-emerald-50 hover:bg-emerald-100 border border-emerald-200 rounded-xl transition flex items-center gap-1.5 shadow-sm">
                                    <span>Sudah Terdaftar</span>
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                    </svg>
                                </a>
                            @elseif($c->isOffline() && $c->isFullOffline())
                                <button disabled class="px-3.5 py-1.5 text-xs font-montserrat font-bold text-[#6E675F] bg-[#FAF8F5] rounded-xl cursor-not-allowed border border-[#EBE5DF]">
                                    Kursi Penuh
                                </button>
                            @else
                                <a href="{{ route('peserta.catalog.show', $c) }}"
                                   class="px-3.5 py-1.5 text-xs font-montserrat font-bold text-white bg-gradient-to-r from-[#FF6B00] to-[#E11D48] hover:from-[#EA580C] hover:to-[#DC2626] rounded-xl shadow-md hover:shadow-lg transition flex items-center gap-1.5">
                                    <span>Detail & Daftar</span>
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
