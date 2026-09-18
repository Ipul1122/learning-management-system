<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold font-montserrat bg-purple-50 text-purple-700 border border-purple-200 mb-1">
                    <span>🎖️</span>
                    <span>Pencapaian & Milestone Belajar</span>
                </span>
                <h2 class="font-montserrat font-extrabold text-2xl sm:text-3xl text-[#1E1B18] tracking-tight leading-tight">
                    Koleksi Lencana Prestasi
                </h2>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('peserta.leaderboard') }}" class="px-4 py-2.5 bg-white border border-[#EBE5DF] hover:border-[#FF6B00] rounded-xl text-xs font-bold font-montserrat text-[#1E1B18] hover:text-[#FF6B00] transition flex items-center gap-2 shadow-sm">
                    <span>🏆</span>
                    <span>Buka Leaderboard Nasional</span>
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
        @php
            $authUser = Auth::user();
            $earnedCount = $userBadges->count();
            $totalBadgeCount = $allBadges->count();
            $percentage = $totalBadgeCount > 0 ? round(($earnedCount / $totalBadgeCount) * 100) : 0;
        @endphp

        <!-- Level & XP Status Banner -->
        <div class="bg-gradient-to-r from-[#1E1B18] via-[#241F1C] to-[#1E1B18] rounded-3xl p-6 sm:p-8 text-white shadow-xl relative overflow-hidden border border-[#3E3833]">
            <div class="absolute -right-12 -bottom-12 w-64 h-64 bg-purple-500/15 rounded-full blur-3xl pointer-events-none"></div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 relative z-10 items-center">
                <!-- User Profile & Level Info -->
                <div class="lg:col-span-2 space-y-4">
                    <div class="flex items-center gap-4">
                        <div class="w-16 h-16 rounded-2xl bg-gradient-to-tr from-purple-600 via-indigo-600 to-[#FF6B00] flex items-center justify-center text-white text-xl font-montserrat font-extrabold shadow-lg shadow-purple-600/30 shrink-0 border border-white/20">
                            {{ strtoupper(substr($authUser->name, 0, 2)) }}
                        </div>
                        <div>
                            <div class="flex items-center gap-2.5 flex-wrap">
                                <h3 class="text-xl sm:text-2xl font-montserrat font-extrabold text-white">{{ $authUser->name }}</h3>
                                <span class="px-3 py-0.5 rounded-full text-xs font-bold font-montserrat bg-purple-500/20 text-purple-300 border border-purple-500/30">
                                    Level {{ $authUser->level }} &bull; {{ $authUser->rank_title }}
                                </span>
                            </div>
                            <p class="text-xs text-slate-300 font-quicksand mt-1">
                                Kumpulkan poin XP dari presensi, nilai kuis, dan kelulusan 20 JP untuk menaikkan tingkat level Anda.
                            </p>
                        </div>
                    </div>

                    <!-- Progress Bar to Next Level -->
                    <div class="space-y-1.5 pt-2">
                        <div class="flex items-center justify-between text-xs font-quicksand text-slate-300">
                            <span>Progres Level {{ $authUser->level }}</span>
                            <span class="font-mono text-[#FF8A3D] font-bold">
                                {{ number_format($authUser->total_points) }} / {{ number_format($authUser->next_level_threshold) }} XP ({{ $authUser->level_progress_percentage }}%)
                            </span>
                        </div>
                        <div class="w-full bg-[#12100E] h-3 rounded-full p-0.5 border border-[#3E3833] overflow-hidden">
                            <div class="h-full rounded-full bg-gradient-to-r from-purple-500 via-[#FF6B00] to-amber-400 transition-all duration-500 shadow-sm"
                                 style="width: {{ $authUser->level_progress_percentage }}%"></div>
                        </div>
                    </div>
                </div>

                <!-- Milestone Badge Counter Card -->
                <div class="bg-white/[0.05] border border-white/10 rounded-2xl p-5 text-center flex flex-col items-center justify-center backdrop-blur-sm">
                    <div class="text-3xl font-extrabold font-montserrat text-transparent bg-clip-text bg-gradient-to-r from-purple-300 to-amber-300">
                        {{ $earnedCount }} / {{ $totalBadgeCount }}
                    </div>
                    <div class="text-xs font-montserrat font-bold text-slate-300 mt-1 uppercase tracking-wider">Lencana Diraih ({{ $percentage }}%)</div>
                    <p class="text-[11px] text-slate-400 font-quicksand mt-1">
                        {{ $totalBadgeCount - $earnedCount > 0 ? ($totalBadgeCount - $earnedCount) . ' lencana lagi dapat dibuka!' : 'Selamat! Semua lencana telah berhasil dibuka!' }}
                    </p>
                </div>
            </div>
        </div>

        <!-- BADGES SHOWCASE GALLERY -->
        <div>
            <div class="mb-4">
                <h3 class="font-montserrat font-extrabold text-xl text-[#1E1B18]">Daftar Seluruh Lencana Pembelajaran</h3>
                <p class="text-xs text-[#6E675F] font-quicksand">Setiap lencana memberikan bonus XP langsung ke profil kompetensi Anda.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                @foreach($allBadges as $badge)
                    @php
                        $isEarned = isset($userBadges[$badge->id]);
                        $earnedAt = $isEarned ? $userBadges[$badge->id]->pivot->earned_at : null;
                        
                        $iconMap = [
                            'rocket' => '🚀',
                            'clock' => '⏱️',
                            'trophy' => '🏆',
                            'target' => '🎯',
                            'medal' => '🏅',
                            'chat' => '💬',
                        ];
                        $badgeIcon = $iconMap[$badge->icon] ?? '🎖️';
                    @endphp

                    <div class="relative rounded-3xl p-6 transition-all duration-200 flex flex-col justify-between {{ $isEarned ? 'bg-white border-2 border-emerald-300 shadow-md hover:shadow-lg' : 'bg-white/60 border border-[#EBE5DF] opacity-75 hover:opacity-95' }}">
                        
                        <!-- Top Pill & Status -->
                        <div class="flex items-center justify-between mb-4">
                            @if($isEarned)
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-bold font-montserrat bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    <span>TERBUKA</span>
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-bold font-montserrat bg-[#FAF8F5] text-[#6E675F] border border-[#EBE5DF]">
                                    <svg class="w-3.5 h-3.5 text-[#6E675F]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                    <span>TERKUNCI</span>
                                </span>
                            @endif

                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-lg text-xs font-bold font-mono {{ $isEarned ? 'bg-amber-50 text-amber-700 border border-amber-200' : 'bg-[#FAF8F5] text-[#6E675F]' }}">
                                +{{ $badge->xp_reward }} XP
                            </span>
                        </div>

                        <!-- Badge Icon & Content -->
                        <div class="space-y-2">
                            <div class="w-14 h-14 rounded-2xl flex items-center justify-center text-3xl mb-3 shadow-inner {{ $isEarned ? 'bg-gradient-to-tr from-amber-100 to-amber-200 border border-amber-300' : 'bg-slate-100 border border-slate-200 filter grayscale' }}">
                                {{ $badgeIcon }}
                            </div>

                            <h4 class="font-montserrat font-extrabold text-base text-[#1E1B18] {{ $isEarned ? 'text-[#1E1B18]' : 'text-slate-700' }}">
                                {{ $badge->name }}
                            </h4>

                            <p class="text-xs text-[#6E675F] font-quicksand leading-relaxed">
                                {{ $badge->description }}
                            </p>
                        </div>

                        <!-- Footer / Date Earned -->
                        <div class="mt-5 pt-3 border-t border-[#EBE5DF] text-[11px] font-quicksand flex items-center justify-between">
                            @if($isEarned && $earnedAt)
                                <span class="text-emerald-700 font-semibold flex items-center gap-1">
                                    <span>Diraih pada:</span>
                                    <strong>{{ \Carbon\Carbon::parse($earnedAt)->translatedFormat('d M Y') }}</strong>
                                </span>
                            @else
                                <span class="text-[#6E675F] italic">
                                    Selesaikan misi untuk klaim
                                </span>
                            @endif
                        </div>

                    </div>
                @endforeach
            </div>
        </div>

        <!-- RIWAYAT MUTASI POIN XP TERAKHIR -->
        <div class="bg-white border border-[#EBE5DF] rounded-3xl shadow-sm overflow-hidden">
            <div class="p-6 border-b border-[#EBE5DF] flex items-center justify-between">
                <div>
                    <h3 class="font-montserrat font-extrabold text-lg text-[#1E1B18]">Log Riwayat Perolehan Poin XP</h3>
                    <p class="text-xs text-[#6E675F] font-quicksand mt-0.5">Catatan transparan perolehan poin dari setiap aktivitas di sistem LMS.</p>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-[#1E1B18]">
                    <thead class="bg-[#FAF8F5] text-[#6E675F] uppercase font-montserrat font-bold text-[11px] border-b border-[#EBE5DF]">
                        <tr>
                            <th class="py-3.5 px-5">Waktu Transaksi</th>
                            <th class="py-3.5 px-5">Jenis Aktivitas</th>
                            <th class="py-3.5 px-5">Deskripsi Capaian</th>
                            <th class="py-3.5 px-5 text-right">Poin XP Diperoleh</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#EBE5DF] font-quicksand">
                        @forelse($recentTransactions as $tx)
                            <tr class="hover:bg-[#FAF8F5]/80 transition">
                                <td class="py-3.5 px-5 font-mono text-[#6E675F]">
                                    {{ $tx->created_at ? $tx->created_at->translatedFormat('d M Y H:i') : '-' }}
                                </td>
                                <td class="py-3.5 px-5">
                                    @php
                                        $typeBadge = match($tx->source_type) {
                                            'badge' => ['bg' => 'bg-purple-50', 'text' => 'text-purple-700', 'border' => 'border-purple-200', 'label' => 'Lencana'],
                                            'quiz' => ['bg' => 'bg-blue-50', 'text' => 'text-blue-700', 'border' => 'border-blue-200', 'label' => 'Kuis'],
                                            'attendance' => ['bg' => 'bg-emerald-50', 'text' => 'text-emerald-700', 'border' => 'border-emerald-200', 'label' => 'Presensi'],
                                            'enrollment' => ['bg' => 'bg-orange-50', 'text' => 'text-orange-700', 'border' => 'border-orange-200', 'label' => 'Pendaftaran'],
                                            'graduation' => ['bg' => 'bg-amber-50', 'text' => 'text-amber-800', 'border' => 'border-amber-200', 'label' => 'Kelulusan 20 JP'],
                                            'forum' => ['bg' => 'bg-sky-50', 'text' => 'text-sky-700', 'border' => 'border-sky-200', 'label' => 'Forum'],
                                            default => ['bg' => 'bg-slate-50', 'text' => 'text-slate-700', 'border' => 'border-slate-200', 'label' => ucfirst($tx->source_type)]
                                        };
                                    @endphp
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold font-montserrat {{ $typeBadge['bg'] }} {{ $typeBadge['text'] }} border {{ $typeBadge['border'] }}">
                                        {{ $typeBadge['label'] }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-5 font-medium text-[#1E1B18]">
                                    {{ $tx->description }}
                                </td>
                                <td class="py-3.5 px-5 text-right font-mono font-bold text-emerald-600">
                                    +{{ $tx->points }} XP
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-8 px-4 text-center text-xs text-[#6E675F]">
                                    Belum ada catatan transaksi poin. Mulai ikuti kelas dan kuis untuk meraih poin!
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($recentTransactions->hasPages())
                <div class="p-4 border-t border-[#EBE5DF]">
                    {{ $recentTransactions->links() }}
                </div>
            @endif
        </div>

    </div>
</x-app-layout>
