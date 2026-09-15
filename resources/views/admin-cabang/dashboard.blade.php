<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
            <div>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold font-montserrat bg-[#FEF2F2] text-[#DC2626] border border-[#FECACA] mb-1">
                    <span class="w-2 h-2 rounded-full bg-[#DC2626]"></span>
                    Admin Cabang: {{ $branch->name ?? 'Belum Ditugaskan' }}
                </span>
                <h2 class="font-montserrat font-extrabold text-2xl text-[#1E1B18] leading-tight">
                    Dashboard Operasional Cabang
                </h2>
            </div>
            <div class="text-xs text-[#6E675F]">
                Kode Cabang: <span class="font-bold text-[#EA580C]">{{ $branch->code ?? '-' }}</span> | Kota: {{ $branch->city ?? '-' }}
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Hero Banner -->
            <div class="bg-gradient-to-r from-[#2D1B18] via-[#3D2520] to-[#1E1B18] rounded-3xl p-6 sm:p-8 text-white shadow-xl relative overflow-hidden">
                <div class="absolute -right-8 -bottom-8 w-44 h-44 bg-[#DC2626]/25 rounded-full blur-3xl pointer-events-none"></div>
                <div class="relative z-10 max-w-2xl">
                    <span class="text-xs font-bold tracking-widest uppercase text-[#F87171] font-montserrat">Operasional Wilayah {{ $branch->name ?? '' }}</span>
                    <h1 class="text-2xl sm:text-3xl font-montserrat font-extrabold mt-1 mb-2 text-white">
                        Halo, {{ $user->name }}
                    </h1>
                    <p class="text-sm text-white/80 leading-relaxed font-quicksand">
                        Selamat datang di panel kendali operasional cabang. Kelola instruktur, buka kelas pelatihan offline (maksimal 40 orang), online & hybrid, atur jadwal sesi tatap muka daring via Zoom, dan pantau log audit trail cabang.
                    </p>
                    <div class="mt-5 flex flex-wrap gap-3">
                        <a href="{{ route('cabang.classes.create') }}"
                           class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-xl font-montserrat font-bold text-xs bg-gradient-to-r from-[#FF6B00] to-[#E11D48] hover:from-[#EA580C] hover:to-[#DC2626] text-white shadow-md hover:shadow-lg transition transform hover:-translate-y-0.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            <span>Buka Kelas Baru</span>
                        </a>
                        <a href="{{ route('cabang.trainers.create') }}"
                           class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-xl font-montserrat font-bold text-xs bg-white/10 hover:bg-white/20 text-white border border-white/20 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                            </svg>
                            <span>Tambah Trainer</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Stats Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                <!-- Trainer Cabang -->
                <div class="bg-white rounded-2xl p-5 border border-[#EBE5DF] shadow-sm">
                    <div class="flex items-center justify-between text-[#FF6B00] mb-2">
                        <span class="text-xs font-bold font-montserrat uppercase tracking-wider text-[#6E675F]">Trainer Cabang</span>
                        <div class="w-10 h-10 rounded-xl bg-[#FFF7ED] flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        </div>
                    </div>
                    <div class="text-3xl font-montserrat font-extrabold text-[#1E1B18]">{{ $trainersCount }}</div>
                    <div class="mt-2 pt-2 border-t border-[#FAF8F5] flex justify-between items-center text-xs">
                        <span class="text-[#6E675F]">Instruktur Aktif</span>
                        <a href="{{ route('cabang.trainers.index') }}" class="font-bold text-[#FF6B00] hover:underline">Kelola →</a>
                    </div>
                </div>

                <!-- Total Kelas -->
                <div class="bg-white rounded-2xl p-5 border border-[#EBE5DF] shadow-sm">
                    <div class="flex items-center justify-between text-[#10B981] mb-2">
                        <span class="text-xs font-bold font-montserrat uppercase tracking-wider text-[#6E675F]">Total Kelas</span>
                        <div class="w-10 h-10 rounded-xl bg-[#ECFDF5] flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                        </div>
                    </div>
                    <div class="text-3xl font-montserrat font-extrabold text-[#1E1B18]">{{ $classesCount }}</div>
                    <div class="mt-2 pt-2 border-t border-[#FAF8F5] flex justify-between items-center text-xs">
                        <span class="text-[#6E675F]">Semua Status</span>
                        <a href="{{ route('cabang.classes.index') }}" class="font-bold text-[#10B981] hover:underline">Lihat →</a>
                    </div>
                </div>

                <!-- Kelas Berjalan / Dibuka -->
                <div class="bg-white rounded-2xl p-5 border border-[#EBE5DF] shadow-sm">
                    <div class="flex items-center justify-between text-[#0284C7] mb-2">
                        <span class="text-xs font-bold font-montserrat uppercase tracking-wider text-[#6E675F]">Kelas Aktif</span>
                        <div class="w-10 h-10 rounded-xl bg-[#F0F9FF] flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                    </div>
                    <div class="text-3xl font-montserrat font-extrabold text-[#1E1B18]">{{ $activeClassesCount }}</div>
                    <div class="mt-2 pt-2 border-t border-[#FAF8F5] flex justify-between items-center text-xs">
                        <span class="text-[#6E675F]">Open & Ongoing</span>
                        <span class="text-xs font-semibold text-[#0284C7]">Terselenggara</span>
                    </div>
                </div>

                <!-- Aturan Ketat Kapasitas -->
                <div class="bg-white rounded-2xl p-5 border border-[#EBE5DF] shadow-sm">
                    <div class="flex items-center justify-between text-[#DC2626] mb-2">
                        <span class="text-xs font-bold font-montserrat uppercase tracking-wider text-[#6E675F]">Standar Kapasitas</span>
                        <div class="w-10 h-10 rounded-xl bg-[#FEF2F2] flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        </div>
                    </div>
                    <div class="text-xl font-montserrat font-extrabold text-[#DC2626]">Maks 40 Fisik</div>
                    <div class="mt-2 pt-2 border-t border-[#FAF8F5] text-xs text-[#6E675F]">
                        Online/Hybrid: Ratusan (Zoom)
                    </div>
                </div>
            </div>

            <!-- Two-column Layout: Recent Classes & Recent Branch Logs -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                <!-- Recent Classes (2 Cols) -->
                <div class="lg:col-span-2 bg-white rounded-3xl border border-[#EBE5DF] shadow-sm overflow-hidden flex flex-col">
                    <div class="p-6 border-b border-[#EBE5DF] flex items-center justify-between">
                        <div>
                            <h3 class="font-montserrat font-bold text-base text-[#1E1B18]">Kelas Pelatihan Terbaru</h3>
                            <p class="font-quicksand text-xs text-[#6E675F]">Kelas yang baru dibuka atau sedang berlangsung di cabang {{ $branch->name ?? '' }}.</p>
                        </div>
                        <a href="{{ route('cabang.classes.index') }}" class="font-montserrat font-bold text-xs text-[#FF6B00] hover:underline">
                            Semua Kelas →
                        </a>
                    </div>

                    <div class="divide-y divide-[#EBE5DF] flex-1">
                        @forelse($recentClasses as $c)
                            <div class="p-4 sm:p-5 hover:bg-[#FAF8F5]/60 transition flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                <div class="space-y-1">
                                    <div class="flex items-center gap-2">
                                        <span class="px-2 py-0.5 rounded-full font-mono text-[10px] font-bold uppercase
                                            {{ $c->type === 'offline' ? 'bg-[#FEF2F2] text-[#DC2626] border border-[#FECACA]' : ($c->type === 'online' ? 'bg-[#FFF7ED] text-[#FF6B00] border border-[#FED7AA]' : 'bg-[#ECFDF5] text-[#10B981] border border-[#A7F3D0]') }}">
                                            {{ $c->type }}
                                        </span>
                                        <a href="{{ route('cabang.classes.show', $c) }}" class="font-montserrat font-bold text-sm text-[#1E1B18] hover:text-[#FF6B00] transition">
                                            {{ $c->title }}
                                        </a>
                                    </div>
                                    <div class="text-xs text-[#6E675F] flex flex-wrap items-center gap-x-4 gap-y-1">
                                        <span>Trainer: <strong class="text-[#1E1B18]">{{ $c->trainer?->name ?? 'Belum ada' }}</strong></span>
                                        <span>Jadwal: {{ $c->sessions->count() }} Sesi ({{ $c->totalAccumulatedJp() }}/20 JP)</span>
                                    </div>
                                </div>

                                <div class="flex items-center gap-2 self-start sm:self-center">
                                    @php
                                        $statusClass = match($c->status) {
                                            'draft' => 'bg-gray-100 text-gray-700 border-gray-200',
                                            'open' => 'bg-[#ECFDF5] text-[#10B981] border-[#A7F3D0]',
                                            'ongoing' => 'bg-[#FFF7ED] text-[#FF6B00] border-[#FED7AA]',
                                            'completed' => 'bg-blue-50 text-blue-700 border-blue-200',
                                            'cancelled' => 'bg-[#FEF2F2] text-[#DC2626] border-[#FECACA]',
                                        };
                                    @endphp
                                    <span class="px-2.5 py-1 rounded-full text-xs font-bold font-montserrat border {{ $statusClass }}">
                                        {{ ucfirst($c->status) }}
                                    </span>
                                    <a href="{{ route('cabang.classes.show', $c) }}"
                                       class="px-3 py-1 rounded-xl text-xs font-montserrat font-bold bg-[#FAF8F5] text-[#1E1B18] border border-[#EBE5DF] hover:bg-[#FFF7ED] hover:text-[#FF6B00] transition">
                                        Detail
                                    </a>
                                </div>
                            </div>
                        @empty
                            <div class="p-8 text-center text-xs text-[#6E675F]">
                                Belum ada kelas yang dibuat. <a href="{{ route('cabang.classes.create') }}" class="font-bold text-[#FF6B00] hover:underline">Buka kelas pertama sekarang!</a>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Recent Branch Logs (1 Col) -->
                <div class="bg-white rounded-3xl border border-[#EBE5DF] shadow-sm overflow-hidden flex flex-col">
                    <div class="p-6 border-b border-[#EBE5DF] flex items-center justify-between">
                        <div>
                            <h3 class="font-montserrat font-bold text-base text-[#1E1B18]">Aktivitas Cabang</h3>
                            <p class="font-quicksand text-xs text-[#6E675F]">Mutasi terbaru internal cabang</p>
                        </div>
                        <a href="{{ route('cabang.logs.index') }}" class="font-montserrat font-bold text-xs text-[#FF6B00] hover:underline">
                            Log Audit →
                        </a>
                    </div>

                    <div class="divide-y divide-[#EBE5DF] flex-1">
                        @forelse($recentLogs as $log)
                            <div class="p-4 hover:bg-[#FAF8F5]/60 transition space-y-1">
                                <div class="flex items-center justify-between text-xs">
                                    <span class="font-bold text-[#1E1B18] font-montserrat">{{ $log->user?->name ?? 'System' }}</span>
                                    <span class="px-2 py-0.5 rounded-full font-mono text-[10px] font-bold border
                                        {{ $log->action === 'CREATE' ? 'bg-[#ECFDF5] text-[#10B981] border-[#A7F3D0]' : ($log->action === 'UPDATE' ? 'bg-[#FFF7ED] text-[#FF6B00] border-[#FED7AA]' : 'bg-[#FEF2F2] text-[#DC2626] border-[#FECACA]') }}">
                                        {{ $log->action }}
                                    </span>
                                </div>
                                <p class="text-xs text-[#6E675F] line-clamp-2 leading-relaxed">{{ $log->description }}</p>
                                <div class="text-[10px] text-[#6E675F] font-mono pt-1">
                                    {{ $log->created_at->diffForHumans() }}
                                </div>
                            </div>
                        @empty
                            <div class="p-8 text-center text-xs text-[#6E675F]">
                                Belum ada rekam jejak log aktivitas di cabang ini.
                            </div>
                        @endforelse
                    </div>
                </div>

            </div>

        </div>
    </div>
</x-app-layout>
