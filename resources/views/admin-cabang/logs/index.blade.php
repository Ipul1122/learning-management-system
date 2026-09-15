<x-app-layout>
    <div class="py-8" x-data="branchActivityLogViewer()">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Header Section -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="font-montserrat font-extrabold text-2xl sm:text-3xl text-[#1E1B18] tracking-tight">
                        Log Aktivitas Cabang {{ $branch->name }}
                    </h1>
                    <p class="font-quicksand text-sm text-[#6E675F] mt-1">
                        Rekam jejak audit internal seluruh pengelolaan kelas, jadwal sesi Zoom, dan mutasi data pelatih di cabang Anda.
                    </p>
                </div>
            </div>

            <!-- Metric Cards (Hari Ini di Cabang) -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                <div class="bg-white p-5 rounded-2xl border border-[#EBE5DF] shadow-sm">
                    <div class="font-quicksand text-xs font-semibold text-[#6E675F] uppercase tracking-wider">Log Hari Ini</div>
                    <div class="font-montserrat font-extrabold text-2xl text-[#1E1B18] mt-1">{{ $todayStats['total'] }}</div>
                </div>

                <div class="bg-white p-5 rounded-2xl border border-[#EBE5DF] shadow-sm">
                    <div class="font-quicksand text-xs font-semibold text-[#10B981] uppercase tracking-wider">Data Dibuat (CREATE)</div>
                    <div class="font-montserrat font-extrabold text-2xl text-[#10B981] mt-1">{{ $todayStats['creates'] }}</div>
                </div>

                <div class="bg-white p-5 rounded-2xl border border-[#EBE5DF] shadow-sm">
                    <div class="font-quicksand text-xs font-semibold text-[#FF6B00] uppercase tracking-wider">Data Diedit (UPDATE)</div>
                    <div class="font-montserrat font-extrabold text-2xl text-[#FF6B00] mt-1">{{ $todayStats['updates'] }}</div>
                </div>

                <div class="bg-white p-5 rounded-2xl border border-[#EBE5DF] shadow-sm">
                    <div class="font-quicksand text-xs font-semibold text-[#DC2626] uppercase tracking-wider">Data Dihapus (DELETE)</div>
                    <div class="font-montserrat font-extrabold text-2xl text-[#DC2626] mt-1">{{ $todayStats['deletes'] }}</div>
                </div>
            </div>

            <!-- Filter & Search Bar -->
            <div class="bg-white p-4 rounded-2xl border border-[#EBE5DF] shadow-sm">
                <form method="GET" action="{{ route('cabang.logs.index') }}" class="grid grid-cols-1 sm:grid-cols-5 gap-3">
                    <!-- Search query -->
                    <div class="sm:col-span-2 relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-[#6E675F]">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </span>
                        <input type="text"
                               name="search"
                               value="{{ request('search') }}"
                               placeholder="Cari aktivitas atau IP..."
                               class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-[#EBE5DF] bg-[#FAF8F5] text-sm text-[#1E1B18] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#FF6B00] font-quicksand transition">
                    </div>

                    <!-- Action filter -->
                    <div>
                        <select name="action"
                                onchange="this.form.submit()"
                                class="w-full py-2.5 px-3 rounded-xl border border-[#EBE5DF] bg-[#FAF8F5] text-sm text-[#1E1B18] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#FF6B00] font-quicksand">
                            <option value="">Semua Tipe Aksi</option>
                            <option value="CREATE" {{ request('action') === 'CREATE' ? 'selected' : '' }}>CREATE</option>
                            <option value="UPDATE" {{ request('action') === 'UPDATE' ? 'selected' : '' }}>UPDATE</option>
                            <option value="DELETE" {{ request('action') === 'DELETE' ? 'selected' : '' }}>DELETE</option>
                        </select>
                    </div>

                    <!-- User filter (Cabang ini) -->
                    <div>
                        <select name="user_id"
                                onchange="this.form.submit()"
                                class="w-full py-2.5 px-3 rounded-xl border border-[#EBE5DF] bg-[#FAF8F5] text-sm text-[#1E1B18] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#FF6B00] font-quicksand">
                            <option value="">Semua User Cabang</option>
                            @foreach($users as $u)
                                <option value="{{ $u->id }}" {{ request('user_id') == $u->id ? 'selected' : '' }}>
                                    {{ $u->name }} ({{ $u->roles->first()?->name ?? 'User' }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Date & Reset -->
                    <div class="flex gap-2">
                        <input type="date"
                               name="date_from"
                               value="{{ request('date_from') }}"
                               title="Dari Tanggal"
                               class="w-full py-2.5 px-2 rounded-xl border border-[#EBE5DF] bg-[#FAF8F5] text-xs text-[#1E1B18] focus:bg-white font-quicksand">

                        @if(request()->hasAny(['search', 'action', 'user_id', 'date_from']))
                            <a href="{{ route('cabang.logs.index') }}"
                               class="px-3 py-2.5 bg-[#F3EFEA] hover:bg-[#EBE5DF] text-[#1E1B18] font-montserrat font-bold text-xs rounded-xl transition flex items-center">
                                Reset
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <!-- Table / Logs List -->
            <div class="bg-white rounded-3xl border border-[#EBE5DF] shadow-sm overflow-hidden">
                @if($logs->isEmpty())
                    <div class="p-12 text-center">
                        <div class="w-16 h-16 mx-auto rounded-full bg-[#FFF7ED] text-[#FF6B00] flex items-center justify-center mb-4">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <h3 class="font-montserrat font-bold text-lg text-[#1E1B18]">Belum ada riwayat aktivitas</h3>
                        <p class="font-quicksand text-sm text-[#6E675F] mt-1 max-w-md mx-auto">
                            Tidak ada rekam jejak log yang cocok dengan filter atau pencarian Anda di cabang ini.
                        </p>
                    </div>
                @else
                    <!-- Desktop Table -->
                    <div class="hidden md:block overflow-x-auto">
                        <table class="w-full text-left text-sm">
                            <thead class="bg-[#FAF8F5] border-b border-[#EBE5DF] text-xs font-montserrat font-bold text-[#6E675F] uppercase tracking-wider">
                                <tr>
                                    <th class="py-4 px-6">Waktu</th>
                                    <th class="py-4 px-6">Pengguna</th>
                                    <th class="py-4 px-6 text-center">Aksi</th>
                                    <th class="py-4 px-6">Deskripsi Aktivitas</th>
                                    <th class="py-4 px-6">IP / Target</th>
                                    <th class="py-4 px-6 text-right">Detail</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-[#EBE5DF] font-quicksand">
                                @foreach($logs as $log)
                                    <tr class="hover:bg-[#FAF8F5]/60 transition">
                                        <td class="py-4 px-6 whitespace-nowrap text-xs text-[#6E675F]">
                                            <div class="font-semibold text-[#1E1B18]">{{ $log->created_at->format('d M Y') }}</div>
                                            <div>{{ $log->created_at->format('H:i:s') }} WIB</div>
                                        </td>
                                        <td class="py-4 px-6 whitespace-nowrap">
                                            <div class="flex items-center gap-2.5">
                                                <div class="w-7 h-7 rounded-full bg-[#1E1B18] text-white flex items-center justify-center font-montserrat font-bold text-xs">
                                                    {{ strtoupper(substr($log->user?->name ?? 'S', 0, 1)) }}
                                                </div>
                                                <div>
                                                    <div class="font-montserrat font-bold text-xs text-[#1E1B18]">{{ $log->user?->name ?? 'System' }}</div>
                                                    <span class="text-[10px] text-[#6E675F] uppercase tracking-wider font-semibold">
                                                        {{ $log->user?->roles->first()?->name ?? 'None' }}
                                                    </span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="py-4 px-6 text-center whitespace-nowrap">
                                            @php
                                                $badgeStyle = match(strtoupper($log->action)) {
                                                    'CREATE' => 'bg-[#ECFDF5] text-[#10B981] border-[#A7F3D0]',
                                                    'UPDATE' => 'bg-[#FFF7ED] text-[#FF6B00] border-[#FED7AA]',
                                                    'DELETE' => 'bg-[#FEF2F2] text-[#DC2626] border-[#FECACA]',
                                                    default => 'bg-gray-100 text-gray-700 border-gray-200',
                                                };
                                            @endphp
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold font-mono border {{ $badgeStyle }}">
                                                {{ $log->action }}
                                            </span>
                                        </td>
                                        <td class="py-4 px-6">
                                            <p class="text-xs text-[#1E1B18] font-medium leading-relaxed">{{ $log->description }}</p>
                                        </td>
                                        <td class="py-4 px-6 whitespace-nowrap text-xs text-[#6E675F] font-mono">
                                            <div>IP: {{ $log->ip_address ?? 'N/A' }}</div>
                                            <div class="text-[11px] text-[#FF6B00]">{{ class_basename($log->target_entity) }} #{{ $log->target_id }}</div>
                                        </td>
                                        <td class="py-4 px-6 text-right whitespace-nowrap">
                                            <button type="button"
                                                    @click="openModal({{ json_encode($log) }})"
                                                    class="inline-flex items-center gap-1 px-3 py-1.5 rounded-xl font-montserrat font-bold text-xs bg-[#FAF8F5] hover:bg-[#FFF7ED] text-[#FF6B00] border border-[#EBE5DF] transition">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                                <span>Diff</span>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Mobile List View -->
                    <div class="block md:hidden divide-y divide-[#EBE5DF]">
                        @foreach($logs as $log)
                            <div class="p-4 space-y-2">
                                <div class="flex items-center justify-between text-xs">
                                    <span class="font-mono text-[#6E675F]">{{ $log->created_at->format('d/m/Y H:i') }}</span>
                                    <span class="px-2 py-0.5 rounded-full font-mono text-[10px] font-bold border
                                        {{ $log->action === 'CREATE' ? 'bg-[#ECFDF5] text-[#10B981] border-[#A7F3D0]' : ($log->action === 'UPDATE' ? 'bg-[#FFF7ED] text-[#FF6B00] border-[#FED7AA]' : 'bg-[#FEF2F2] text-[#DC2626] border-[#FECACA]') }}">
                                        {{ $log->action }}
                                    </span>
                                </div>
                                <p class="text-xs font-semibold text-[#1E1B18]">{{ $log->description }}</p>
                                <div class="flex items-center justify-between pt-1">
                                    <span class="text-xs text-[#6E675F]">{{ $log->user?->name ?? 'System' }}</span>
                                    <button type="button"
                                            @click="openModal({{ json_encode($log) }})"
                                            class="text-xs font-montserrat font-bold text-[#FF6B00]">
                                        Lihat Diff →
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    @if($logs->hasPages())
                        <div class="p-4 border-t border-[#EBE5DF]">
                            {{ $logs->links() }}
                        </div>
                    @endif
                @endif
            </div>

            <!-- Inspect Change Modal (Old vs New Diff) -->
            <div x-show="isOpen"
                 x-cloak
                 class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-xs transition-opacity"
                 @keydown.escape.window="isOpen = false">
                <div @click.away="isOpen = false"
                     class="bg-white rounded-3xl border border-[#EBE5DF] shadow-2xl max-w-2xl w-full max-h-[90vh] flex flex-col overflow-hidden animate-in fade-in zoom-in-95 duration-200">

                    <!-- Modal Header -->
                    <div class="p-6 border-b border-[#EBE5DF] flex items-center justify-between bg-[#FAF8F5]/50">
                        <div class="flex items-center gap-3">
                            <span class="px-2.5 py-1 rounded-full text-xs font-bold font-mono border"
                                  :class="{
                                      'bg-[#ECFDF5] text-[#10B981] border-[#A7F3D0]': currentLog.action === 'CREATE',
                                      'bg-[#FFF7ED] text-[#FF6B00] border-[#FED7AA]': currentLog.action === 'UPDATE',
                                      'bg-[#FEF2F2] text-[#DC2626] border-[#FECACA]': currentLog.action === 'DELETE'
                                  }"
                                  x-text="currentLog.action"></span>
                            <h3 class="font-montserrat font-bold text-base text-[#1E1B18]">Inspeksi Perubahan Data</h3>
                        </div>
                        <button @click="isOpen = false" class="text-[#6E675F] hover:text-[#1E1B18] p-1.5 rounded-xl hover:bg-[#FAF8F5]">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <!-- Modal Body -->
                    <div class="p-6 overflow-y-auto space-y-4">
                        <div class="bg-[#FAF8F5] p-3 rounded-2xl border border-[#EBE5DF] text-xs space-y-1">
                            <div>
                                <span class="font-semibold text-[#6E675F]">Deskripsi:</span>
                                <span class="text-[#1E1B18] font-bold" x-text="currentLog.description"></span>
                            </div>
                            <div>
                                <span class="font-semibold text-[#6E675F]">Eksekutor:</span>
                                <span class="text-[#1E1B18] font-bold" x-text="currentLog.user?.name || 'System'"></span>
                            </div>
                            <div>
                                <span class="font-semibold text-[#6E675F]">Target Entity:</span>
                                <span class="font-mono text-[#1E1B18]" x-text="(currentLog.target_entity || '-') + ' #' + (currentLog.target_id || '')"></span>
                            </div>
                            <div>
                                <span class="font-semibold text-[#6E675F]">IP Address:</span>
                                <span class="font-mono text-[#1E1B18]" x-text="currentLog.ip_address || '-'"></span>
                            </div>
                        </div>

                        <!-- Diff Comparison (Old vs New) -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Old Properties -->
                            <div>
                                <div class="font-montserrat font-bold text-xs uppercase tracking-wider text-[#DC2626] mb-1.5 flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                                    </svg>
                                    <span>Nilai Sebelumnya (Old)</span>
                                </div>
                                <div class="bg-[#FEF2F2]/40 rounded-xl p-3 border border-[#FECACA] font-mono text-xs text-[#991B1B] overflow-x-auto max-h-60">
                                    <pre x-text="formatJson(currentLog.properties_old)"></pre>
                                </div>
                            </div>

                            <!-- New Properties -->
                            <div>
                                <div class="font-montserrat font-bold text-xs uppercase tracking-wider text-[#10B981] mb-1.5 flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                    </svg>
                                    <span>Nilai Terbaru (New)</span>
                                </div>
                                <div class="bg-[#ECFDF5]/40 rounded-xl p-3 border border-[#A7F3D0] font-mono text-xs text-[#065F46] overflow-x-auto max-h-60">
                                    <pre x-text="formatJson(currentLog.properties_new)"></pre>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal Footer -->
                    <div class="p-4 bg-[#FAF8F5] border-t border-[#EBE5DF] flex justify-end">
                        <button type="button"
                                @click="isOpen = false"
                                class="px-5 py-2 rounded-xl font-montserrat font-bold text-xs bg-[#1E1B18] text-white hover:bg-[#322E2B] transition">
                            Tutup
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </div>

    @push('scripts')
        <script>
            function branchActivityLogViewer() {
                return {
                    isOpen: false,
                    currentLog: {},
                    openModal(log) {
                        this.currentLog = log;
                        this.isOpen = true;
                    },
                    formatJson(obj) {
                        if (!obj) return 'Tidak ada data.';
                        try {
                            return JSON.stringify(obj, null, 2);
                        } catch (e) {
                            return String(obj);
                        }
                    }
                }
            }
        </script>
    @endpush
</x-app-layout>
