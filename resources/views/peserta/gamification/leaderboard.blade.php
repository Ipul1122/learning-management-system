<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold font-montserrat bg-[#FFF7ED] text-[#EA580C] border border-[#FED7AA] mb-1">
                    <span>🏆</span>
                    <span>Papan Peringkat Kompetensi Nasional</span>
                </span>
                <h2 class="font-montserrat font-extrabold text-2xl sm:text-3xl text-[#1E1B18] tracking-tight leading-tight">
                    Leaderboard Peserta
                </h2>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('peserta.badges') }}" class="px-4 py-2.5 bg-white border border-[#EBE5DF] hover:border-[#FF6B00] rounded-xl text-xs font-bold font-montserrat text-[#1E1B18] hover:text-[#FF6B00] transition flex items-center gap-2 shadow-sm">
                    <span>🎖️</span>
                    <span>Koleksi Lencana Saya</span>
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
        
        <!-- Logged-in User Current Standing Banner -->
        @php
            $authUser = Auth::user();
        @endphp
        <div class="bg-gradient-to-r from-[#1E1B18] via-[#2A2420] to-[#1E1B18] rounded-3xl p-6 sm:p-8 text-white shadow-xl relative overflow-hidden border border-[#3E3833]">
            <div class="absolute -right-8 -top-8 w-48 h-48 bg-[#FF6B00]/20 rounded-full blur-3xl pointer-events-none"></div>
            
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 relative z-10">
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 rounded-2xl bg-gradient-to-tr from-[#FF6B00] to-[#E11D48] flex items-center justify-center text-white text-xl font-montserrat font-extrabold shadow-lg shadow-[#FF6B00]/30 shrink-0 border border-white/20">
                        {{ strtoupper(substr($authUser->name, 0, 2)) }}
                    </div>
                    <div>
                        <div class="flex items-center gap-2.5 flex-wrap">
                            <h3 class="text-xl sm:text-2xl font-montserrat font-extrabold text-white">{{ $authUser->name }}</h3>
                            <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold font-montserrat bg-[#FF6B00]/20 text-[#FF8A3D] border border-[#FF6B00]/30">
                                Level {{ $authUser->level }} &bull; {{ $authUser->rank_title }}
                            </span>
                        </div>
                        <p class="text-xs text-slate-300 font-quicksand mt-1">
                            Kantor Cabang: <strong>{{ $authUser->branch->name ?? 'Pusat' }}</strong> &bull; Total Lencana: <strong>{{ $authUser->badges->count() }} Lencana Terbuka</strong>
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-6 sm:gap-8 border-t md:border-t-0 md:border-l border-white/10 pt-4 md:pt-0 md:pl-8">
                    <div>
                        <div class="text-[11px] text-slate-400 font-montserrat uppercase tracking-wider">Peringkat Global</div>
                        <div class="text-2xl sm:text-3xl font-montserrat font-extrabold text-amber-400 flex items-center gap-1.5 mt-0.5">
                            <span>#{{ $myGlobalRank }}</span>
                            <span class="text-xs text-slate-400 font-normal">dari {{ \App\Models\User::role('peserta')->count() }}</span>
                        </div>
                    </div>
                    <div>
                        <div class="text-[11px] text-slate-400 font-montserrat uppercase tracking-wider">Total Perolehan XP</div>
                        <div class="text-2xl sm:text-3xl font-montserrat font-extrabold text-[#FF8A3D] mt-0.5">
                            {{ number_format($authUser->total_points ?? 0) }} <span class="text-xs text-white/60 font-normal">XP</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter Bar -->
        <div class="bg-white p-4 rounded-2xl border border-[#EBE5DF] shadow-sm flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-2 text-xs font-montserrat font-bold text-[#1E1B18]">
                <span>📍 Filter Berdasarkan Wilayah:</span>
            </div>

            <form method="GET" action="{{ route('peserta.leaderboard') }}" class="flex items-center gap-2 w-full sm:w-auto">
                <select name="branch_id"
                        onchange="this.form.submit()"
                        class="text-xs rounded-xl border-[#EBE5DF] bg-[#FAF8F5] focus:bg-white focus:ring-[#FF6B00] font-quicksand py-2 px-3 w-full sm:w-64">
                    <option value="">Semua Cabang (Nasional)</option>
                    @foreach($branches as $branch)
                        <option value="{{ $branch->id }}" {{ request('branch_id') == $branch->id ? 'selected' : '' }}>
                            {{ $branch->name }} ({{ $branch->city }})
                        </option>
                    @endforeach
                </select>
                @if(request('branch_id'))
                    <a href="{{ route('peserta.leaderboard') }}" class="px-3 py-2 text-xs font-bold text-[#6E675F] hover:text-[#DC2626] font-montserrat transition">
                        Reset
                    </a>
                @endif
            </form>
        </div>

        <!-- TOP 3 PODIUM JUARA (Desktop & Tablet) -->
        @if($topThree->isNotEmpty())
            <div class="relative pt-8 pb-4">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-end max-w-4xl mx-auto">
                    
                    <!-- PODIUM 2 (Silver) -->
                    @if(isset($topThree[1]))
                        @php $second = $topThree[1]; @endphp
                        <div class="order-2 md:order-1 bg-white border border-slate-200 rounded-3xl p-6 shadow-md text-center relative flex flex-col items-center hover:scale-[1.02] transition-transform">
                            <div class="absolute -top-6 w-12 h-12 rounded-full bg-gradient-to-tr from-slate-200 to-slate-400 border-2 border-white shadow-md flex items-center justify-center text-white text-lg font-bold">
                                🥈
                            </div>
                            <div class="w-16 h-16 rounded-2xl bg-gradient-to-tr from-slate-300 to-slate-500 text-white flex items-center justify-center text-lg font-bold font-montserrat shadow-md mt-4 mb-3">
                                {{ strtoupper(substr($second->name, 0, 2)) }}
                            </div>
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold font-montserrat bg-slate-100 text-slate-700 mb-1">
                                Juara 2 &bull; Silver Tier
                            </span>
                            <h4 class="font-montserrat font-bold text-base text-[#1E1B18] line-clamp-1">{{ $second->name }}</h4>
                            <p class="text-xs text-[#6E675F] font-quicksand">{{ $second->branch->name ?? 'Pusat' }}</p>
                            <div class="mt-4 pt-3 border-t border-slate-100 w-full flex items-center justify-around text-xs">
                                <div>
                                    <div class="text-[10px] text-[#6E675F]">Lencana</div>
                                    <div class="font-bold text-[#1E1B18]">{{ $second->badges->count() }} 🎖️</div>
                                </div>
                                <div>
                                    <div class="text-[10px] text-[#6E675F]">Total XP</div>
                                    <div class="font-bold text-[#FF6B00] font-mono">{{ number_format($second->total_points) }} XP</div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="hidden md:block order-1"></div>
                    @endif

                    <!-- PODIUM 1 (Gold - Elevated) -->
                    @if(isset($topThree[0]))
                        @php $first = $topThree[0]; @endphp
                        <div class="order-1 md:order-2 bg-gradient-to-b from-amber-50/70 via-white to-white border-2 border-amber-300 rounded-3xl p-7 shadow-xl text-center relative flex flex-col items-center md:-translate-y-4 hover:scale-[1.03] transition-transform">
                            <div class="absolute -top-7 w-14 h-14 rounded-full bg-gradient-to-tr from-amber-400 via-yellow-400 to-amber-500 border-2 border-white shadow-lg flex items-center justify-center text-white text-2xl font-bold">
                                👑
                            </div>
                            <div class="w-20 h-20 rounded-2xl bg-gradient-to-tr from-amber-400 to-amber-600 text-white flex items-center justify-center text-2xl font-bold font-montserrat shadow-lg mt-4 mb-3 border-2 border-white">
                                {{ strtoupper(substr($first->name, 0, 2)) }}
                            </div>
                            <span class="px-3 py-0.5 rounded-full text-xs font-bold font-montserrat bg-amber-100 text-amber-800 border border-amber-200 mb-1">
                                🏆 Juara 1 &bull; Gold Champion
                            </span>
                            <h4 class="font-montserrat font-extrabold text-lg text-[#1E1B18] line-clamp-1">{{ $first->name }}</h4>
                            <p class="text-xs text-[#6E675F] font-quicksand">{{ $first->branch->name ?? 'Pusat' }}</p>
                            <div class="mt-4 pt-3 border-t border-amber-100 w-full flex items-center justify-around text-xs">
                                <div>
                                    <div class="text-[10px] text-[#6E675F]">Lencana</div>
                                    <div class="font-bold text-[#1E1B18]">{{ $first->badges->count() }} 🎖️</div>
                                </div>
                                <div>
                                    <div class="text-[10px] text-[#6E675F]">Total XP</div>
                                    <div class="font-extrabold text-xl text-amber-600 font-mono">{{ number_format($first->total_points) }} XP</div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- PODIUM 3 (Bronze) -->
                    @if(isset($topThree[2]))
                        @php $third = $topThree[2]; @endphp
                        <div class="order-3 bg-white border border-amber-200/70 rounded-3xl p-6 shadow-md text-center relative flex flex-col items-center hover:scale-[1.02] transition-transform">
                            <div class="absolute -top-6 w-12 h-12 rounded-full bg-gradient-to-tr from-amber-600 to-amber-800 border-2 border-white shadow-md flex items-center justify-center text-white text-lg font-bold">
                                🥉
                            </div>
                            <div class="w-16 h-16 rounded-2xl bg-gradient-to-tr from-amber-700 to-amber-900 text-white flex items-center justify-center text-lg font-bold font-montserrat shadow-md mt-4 mb-3">
                                {{ strtoupper(substr($third->name, 0, 2)) }}
                            </div>
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold font-montserrat bg-amber-50 text-amber-900 mb-1">
                                Juara 3 &bull; Bronze Tier
                            </span>
                            <h4 class="font-montserrat font-bold text-base text-[#1E1B18] line-clamp-1">{{ $third->name }}</h4>
                            <p class="text-xs text-[#6E675F] font-quicksand">{{ $third->branch->name ?? 'Pusat' }}</p>
                            <div class="mt-4 pt-3 border-t border-slate-100 w-full flex items-center justify-around text-xs">
                                <div>
                                    <div class="text-[10px] text-[#6E675F]">Lencana</div>
                                    <div class="font-bold text-[#1E1B18]">{{ $third->badges->count() }} 🎖️</div>
                                </div>
                                <div>
                                    <div class="text-[10px] text-[#6E675F]">Total XP</div>
                                    <div class="font-bold text-[#FF6B00] font-mono">{{ number_format($third->total_points) }} XP</div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="hidden md:block order-3"></div>
                    @endif

                </div>
            </div>
        @endif

        <!-- TABEL KESELURUHAN PERINGKAT -->
        <div class="bg-white border border-[#EBE5DF] rounded-3xl shadow-sm overflow-hidden">
            <div class="p-6 border-b border-[#EBE5DF] flex items-center justify-between">
                <div>
                    <h3 class="font-montserrat font-extrabold text-lg text-[#1E1B18]">Daftar Peringkat Peserta Lengkap</h3>
                    <p class="text-xs text-[#6E675F] font-quicksand mt-0.5">Pembaruan posisi otomatis saat peserta menyelesaikan aktivitas belajar.</p>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-[#1E1B18]">
                    <thead class="bg-[#FAF8F5] text-[#6E675F] uppercase font-montserrat font-bold text-[11px] border-b border-[#EBE5DF]">
                        <tr>
                            <th class="py-3.5 px-5 text-center w-16">Rank</th>
                            <th class="py-3.5 px-5">Nama Peserta</th>
                            <th class="py-3.5 px-5">Kantor Cabang</th>
                            <th class="py-3.5 px-5">Tingkat / Level</th>
                            <th class="py-3.5 px-5 text-center">Lencana Diraih</th>
                            <th class="py-3.5 px-5 text-right">Akumulasi Poin XP</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#EBE5DF] font-quicksand">
                        @forelse($leaderboard as $index => $player)
                            @php
                                $rankNumber = $leaderboard->firstItem() + $index;
                                $isCurrentAuth = $player->id === Auth::id();
                            @endphp
                            <tr class="hover:bg-[#FAF8F5]/80 transition {{ $isCurrentAuth ? 'bg-orange-50/60 font-semibold' : '' }}">
                                <td class="py-4 px-5 text-center">
                                    @if($rankNumber === 1)
                                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-amber-100 text-amber-800 font-bold font-montserrat text-sm border border-amber-300">
                                            🥇
                                        </span>
                                    @elseif($rankNumber === 2)
                                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-slate-100 text-slate-700 font-bold font-montserrat text-sm border border-slate-300">
                                            🥈
                                        </span>
                                    @elseif($rankNumber === 3)
                                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-amber-50 text-amber-900 font-bold font-montserrat text-sm border border-amber-200">
                                            🥉
                                        </span>
                                    @else
                                        <span class="font-montserrat font-bold text-[#6E675F] text-sm">#{{ $rankNumber }}</span>
                                    @endif
                                </td>
                                <td class="py-4 px-5">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-xl bg-gradient-to-tr {{ $isCurrentAuth ? 'from-[#FF6B00] to-[#E11D48]' : 'from-slate-200 to-slate-300' }} {{ $isCurrentAuth ? 'text-white' : 'text-slate-700' }} flex items-center justify-center font-bold font-montserrat text-xs shrink-0">
                                            {{ strtoupper(substr($player->name, 0, 2)) }}
                                        </div>
                                        <div>
                                            <div class="font-montserrat font-bold text-sm text-[#1E1B18] flex items-center gap-2">
                                                <span>{{ $player->name }}</span>
                                                @if($isCurrentAuth)
                                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-[#FF6B00] text-white">Anda</span>
                                                @endif
                                            </div>
                                            <div class="text-[11px] text-[#6E675F]">{{ $player->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4 px-5">
                                    <span class="inline-block px-2.5 py-1 rounded-lg bg-[#FAF8F5] border border-[#EBE5DF] text-xs font-medium text-[#334155]">
                                        {{ $player->branch->name ?? 'Kantor Pusat' }}
                                    </span>
                                </td>
                                <td class="py-4 px-5">
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold font-montserrat bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        <span>Level {{ $player->level }}</span>
                                        <span>&bull;</span>
                                        <span class="font-normal">{{ $player->rank_title }}</span>
                                    </span>
                                </td>
                                <td class="py-4 px-5 text-center">
                                    <span class="font-montserrat font-bold text-sm text-[#1E1B18] inline-flex items-center gap-1">
                                        <span>{{ $player->badges->count() }}</span>
                                        <span class="text-base">🎖️</span>
                                    </span>
                                </td>
                                <td class="py-4 px-5 text-right font-mono">
                                    <span class="text-sm font-extrabold text-[#FF6B00]">{{ number_format($player->total_points) }}</span>
                                    <span class="text-[11px] text-[#6E675F] ml-0.5 font-sans">XP</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-8 px-4 text-center text-xs text-[#6E675F]">
                                    Belum ada data peserta pada filter cabang ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($leaderboard->hasPages())
                <div class="p-4 border-t border-[#EBE5DF]">
                    {{ $leaderboard->links() }}
                </div>
            @endif
        </div>

    </div>
</x-app-layout>
