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
                     class="bg-white rounded-3xl border border-[#EBE5DF] shadow-2xl max-w-3xl w-full max-h-[90vh] flex flex-col overflow-hidden animate-in fade-in zoom-in-95 duration-200">
                    <!-- Modal Header -->
                    <div class="p-5 sm:p-6 border-b border-[#EBE5DF] flex items-center justify-between bg-white">
                        <div class="space-y-1">
                            <div class="flex items-center gap-2.5">
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-mono font-bold border"
                                      :class="{
                                          'bg-[#ECFDF5] text-[#10B981] border-[#A7F3D0]': currentLog.action === 'CREATE',
                                          'bg-[#FFF7ED] text-[#FF6B00] border-[#FED7AA]': currentLog.action === 'UPDATE',
                                          'bg-[#FEF2F2] text-[#DC2626] border-[#FECACA]': currentLog.action === 'DELETE'
                                      }"
                                      x-text="currentLog.action"></span>
                                <h3 class="font-montserrat font-bold text-lg text-[#1E1B18]">Inspeksi Mutasi Data Cabang</h3>
                            </div>
                            <p class="font-quicksand text-xs sm:text-sm text-[#6E675F] max-w-xl font-medium" x-text="currentLog.description"></p>
                        </div>
                        <button @click="isOpen = false" class="text-[#6E675F] hover:text-[#1E1B18] p-2 rounded-xl hover:bg-[#FAF8F5] transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <!-- Modal Body -->
                    <div class="p-5 sm:p-6 overflow-y-auto space-y-5">
                        <!-- Metadata Card -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 bg-[#FAF8F5] p-3.5 sm:p-4 rounded-2xl border border-[#EBE5DF]">
                            <div class="space-y-0.5">
                                <span class="font-montserrat font-semibold text-[10px] uppercase tracking-wider text-[#6E675F]">Eksekutor</span>
                                <div class="font-montserrat font-bold text-xs sm:text-sm text-[#1E1B18] truncate" x-text="currentLog.user?.name || 'System'"></div>
                                <div class="text-[11px] text-[#6E675F] font-quicksand" x-text="currentLog.user?.roles?.[0]?.name ? 'Role: ' + currentLog.user.roles[0].name : 'Sistem'"></div>
                            </div>
                            <div class="space-y-0.5">
                                <span class="font-montserrat font-semibold text-[10px] uppercase tracking-wider text-[#6E675F]">Waktu Eksekusi</span>
                                <div class="font-montserrat font-bold text-xs text-[#1E1B18]" x-text="currentLog.formatted_created_at || formatDateTime(currentLog.created_at)"></div>
                            </div>
                            <div class="space-y-0.5">
                                <span class="font-montserrat font-semibold text-[10px] uppercase tracking-wider text-[#6E675F]">IP Address</span>
                                <div class="font-mono font-bold text-xs text-[#1E1B18] break-all" x-text="currentLog.formatted_ip_address || formatIp(currentLog.ip_address)"></div>
                            </div>
                            <div class="space-y-0.5">
                                <span class="font-montserrat font-semibold text-[10px] uppercase tracking-wider text-[#6E675F]">Target Entitas</span>
                                <div>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md font-montserrat font-bold text-xs bg-white border border-[#EBE5DF] text-[#1E1B18]"
                                          x-text="currentLog.target_entity_label || formatEntity(currentLog.target_entity, currentLog.target_id)"></span>
                                </div>
                            </div>
                        </div>

                        <!-- Tab Selection -->
                        <div class="flex items-center gap-2 border-b border-[#EBE5DF] pb-2">
                            <button type="button"
                                    @click="activeTab = 'human'"
                                    :class="activeTab === 'human' ? 'bg-[#FF6B00] text-white shadow-xs' : 'text-[#6E675F] hover:text-[#1E1B18] hover:bg-[#FAF8F5]'"
                                    class="px-3.5 py-1.5 rounded-xl font-montserrat font-bold text-xs transition flex items-center gap-1.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span>Rincian & Penjelasan Perubahan</span>
                                <template x-if="currentLog.action === 'UPDATE' && diff.changes.length > 0">
                                    <span class="ml-1 px-1.5 py-0.5 rounded-full text-[10px] bg-white/20 text-white font-bold" x-text="diff.changes.length"></span>
                                </template>
                            </button>
                            <button type="button"
                                    @click="activeTab = 'json'"
                                    :class="activeTab === 'json' ? 'bg-[#1E1B18] text-white shadow-xs' : 'text-[#6E675F] hover:text-[#1E1B18] hover:bg-[#FAF8F5]'"
                                    class="px-3.5 py-1.5 rounded-xl font-montserrat font-bold text-xs transition flex items-center gap-1.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" />
                                </svg>
                                <span>Data Teknis (JSON)</span>
                            </button>
                        </div>

                        <!-- TAB 1: Rincian Human-Readable -->
                        <div x-show="activeTab === 'human'" class="space-y-4">
                            <!-- SKENARIO UPDATE -->
                            <template x-if="currentLog.action === 'UPDATE'">
                                <div class="space-y-3">
                                    <div class="p-3.5 rounded-2xl bg-[#FFF7ED] border border-[#FF6B00]/30 flex items-start gap-3">
                                        <div class="w-7 h-7 rounded-lg bg-[#FF6B00]/10 text-[#FF6B00] flex items-center justify-center shrink-0 mt-0.5">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </div>
                                        <div class="text-xs font-quicksand text-[#1E1B18]">
                                            <template x-if="diff.changes.length > 0">
                                                <div>
                                                    Operasi pembaruan ini mengubah <strong><span x-text="diff.changes.length"></span> atribut</strong> pada entitas <strong x-text="currentLog.target_entity_label || formatEntity(currentLog.target_entity)"></strong>:
                                                </div>
                                            </template>
                                            <template x-if="diff.changes.length === 0">
                                                <div>
                                                    Tidak terdapat perbedaan nilai atribut utama yang terdeteksi antara data sebelum dan sesudah pembaruan.
                                                </div>
                                            </template>
                                        </div>
                                    </div>

                                    <div class="space-y-2.5">
                                        <template x-for="item in diff.changes" :key="item.key">
                                            <div class="p-3.5 rounded-2xl border border-[#EBE5DF] bg-white shadow-2xs space-y-2">
                                                <div class="flex items-center justify-between">
                                                    <span class="font-montserrat font-bold text-xs text-[#1E1B18]" x-text="item.label"></span>
                                                    <span class="font-mono text-[10px] text-[#6E675F] bg-[#FAF8F5] px-2 py-0.5 rounded border border-[#EBE5DF]" x-text="item.key"></span>
                                                </div>
                                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 items-center pt-0.5 font-quicksand text-xs">
                                                    <div class="p-2.5 rounded-xl bg-[#FEF2F2] border border-[#FECACA] text-[#991B1B]">
                                                        <div class="font-montserrat font-bold text-[10px] uppercase tracking-wider text-[#DC2626] mb-1 flex items-center gap-1">
                                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/></svg>
                                                            <span>Nilai Sebelumnya:</span>
                                                        </div>
                                                        <div class="font-semibold break-words" x-text="item.oldVal"></div>
                                                    </div>
                                                    <div class="p-2.5 rounded-xl bg-[#ECFDF5] border border-[#A7F3D0] text-[#065F46]">
                                                        <div class="font-montserrat font-bold text-[10px] uppercase tracking-wider text-[#10B981] mb-1 flex items-center gap-1">
                                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                                            <span>Nilai Terbaru:</span>
                                                        </div>
                                                        <div class="font-bold break-words" x-text="item.newVal"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </template>
                                    </div>

                                    <template x-if="diff.unchanged.length > 0">
                                        <div class="pt-2">
                                            <button type="button"
                                                    @click="showUnchanged = !showUnchanged"
                                                    class="text-xs font-montserrat font-bold text-[#6E675F] hover:text-[#FF6B00] flex items-center gap-1.5 transition">
                                                <svg class="w-4 h-4 transition-transform duration-200" :class="showUnchanged ? 'rotate-90' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                                </svg>
                                                <span x-text="showUnchanged ? 'Sembunyikan data yang tidak berubah' : 'Tampilkan ' + diff.unchanged.length + ' data lainnya yang tidak berubah'"></span>
                                            </button>
                                            <div x-show="showUnchanged" x-collapse class="mt-2.5 p-3 rounded-2xl bg-[#FAF8F5] border border-[#EBE5DF] grid grid-cols-1 sm:grid-cols-2 gap-2 text-xs font-quicksand">
                                                <template x-for="item in diff.unchanged" :key="item.key">
                                                    <div class="p-2 bg-white rounded-xl border border-[#EBE5DF]/80 flex items-center justify-between gap-2">
                                                        <span class="text-[#6E675F] font-medium" x-text="item.label"></span>
                                                        <span class="font-bold text-[#1E1B18] text-right truncate max-w-[180px]" x-text="item.val"></span>
                                                    </div>
                                                </template>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </template>

                            <!-- SKENARIO CREATE -->
                            <template x-if="currentLog.action === 'CREATE'">
                                <div class="space-y-3">
                                    <div class="p-3.5 rounded-2xl bg-[#ECFDF5] border border-[#A7F3D0] flex items-start gap-3">
                                        <div class="w-7 h-7 rounded-lg bg-[#10B981]/10 text-[#10B981] flex items-center justify-center shrink-0 mt-0.5">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                            </svg>
                                        </div>
                                        <div class="text-xs font-quicksand text-[#065F46]">
                                            Data baru untuk entitas <strong x-text="currentLog.target_entity_label || formatEntity(currentLog.target_entity)"></strong> berhasil didaftarkan dengan rincian:
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                                        <template x-for="item in diff.created" :key="item.key">
                                            <div class="p-3 rounded-2xl border border-[#EBE5DF] bg-white shadow-2xs">
                                                <div class="text-[11px] font-montserrat font-semibold text-[#6E675F]" x-text="item.label"></div>
                                                <div class="text-xs font-bold text-[#1E1B18] font-quicksand mt-1 break-words" x-text="item.val"></div>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </template>

                            <!-- SKENARIO DELETE -->
                            <template x-if="currentLog.action === 'DELETE'">
                                <div class="space-y-3">
                                    <div class="p-3.5 rounded-2xl bg-[#FEF2F2] border border-[#FECACA] flex items-start gap-3">
                                        <div class="w-7 h-7 rounded-lg bg-[#DC2626]/10 text-[#DC2626] flex items-center justify-center shrink-0 mt-0.5">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </div>
                                        <div class="text-xs font-quicksand text-[#991B1B]">
                                            Data entitas <strong x-text="currentLog.target_entity_label || formatEntity(currentLog.target_entity)"></strong> telah dihapus. Rekaman atribut terakhir:
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                                        <template x-for="item in diff.deleted" :key="item.key">
                                            <div class="p-3 rounded-2xl border border-[#FECACA] bg-[#FEF2F2]/40 shadow-2xs">
                                                <div class="text-[11px] font-montserrat font-semibold text-[#991B1B]" x-text="item.label"></div>
                                                <div class="text-xs font-bold text-[#1E1B18] font-quicksand mt-1 break-words" x-text="item.val"></div>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </template>
                        </div>

                        <!-- TAB 2: Data Mentah (JSON) -->
                        <div x-show="activeTab === 'json'" class="space-y-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <div class="flex items-center justify-between mb-1.5">
                                        <div class="font-montserrat font-bold text-xs uppercase tracking-wider text-[#DC2626] flex items-center gap-1.5">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                                            </svg>
                                            <span>Nilai Sebelumnya (Old)</span>
                                        </div>
                                        <button type="button"
                                                @click="copyJson(formatJson(currentLog.properties_old), 'old')"
                                                class="text-[11px] font-montserrat font-semibold text-[#6E675F] hover:text-[#1E1B18] flex items-center gap-1 transition">
                                            <span x-text="copiedOld ? 'Tersalin!' : 'Salin JSON'"></span>
                                        </button>
                                    </div>
                                    <div class="bg-[#FEF2F2]/40 rounded-2xl p-3.5 border border-[#FECACA] font-mono text-xs text-[#991B1B] overflow-x-auto max-h-72">
                                        <pre x-text="formatJson(currentLog.properties_old)"></pre>
                                    </div>
                                </div>

                                <div>
                                    <div class="flex items-center justify-between mb-1.5">
                                        <div class="font-montserrat font-bold text-xs uppercase tracking-wider text-[#10B981] flex items-center gap-1.5">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                            </svg>
                                            <span>Nilai Terbaru (New)</span>
                                        </div>
                                        <button type="button"
                                                @click="copyJson(formatJson(currentLog.properties_new), 'new')"
                                                class="text-[11px] font-montserrat font-semibold text-[#6E675F] hover:text-[#1E1B18] flex items-center gap-1 transition">
                                            <span x-text="copiedNew ? 'Tersalin!' : 'Salin JSON'"></span>
                                        </button>
                                    </div>
                                    <div class="bg-[#ECFDF5]/40 rounded-2xl p-3.5 border border-[#A7F3D0] font-mono text-xs text-[#065F46] overflow-x-auto max-h-72">
                                        <pre x-text="formatJson(currentLog.properties_new)"></pre>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal Footer -->
                    <div class="p-4 bg-[#FAF8F5] border-t border-[#EBE5DF] flex items-center justify-between">
                        <div class="text-[11px] text-[#6E675F] font-quicksand">
                            Log Audit ID: <span class="font-mono font-bold text-[#1E1B18]" x-text="'#' + (currentLog.id || '-')"></span>
                        </div>
                        <button type="button"
                                @click="isOpen = false"
                                class="px-5 py-2 rounded-xl font-montserrat font-bold text-xs bg-[#1E1B18] text-white hover:bg-[#322E2B] transition">
                            Tutup Inspeksi
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </div>

    @push('scripts')
        <script>
            function branchActivityLogViewer() {
                const FIELD_LABELS = {
                    'name': 'Nama Cabang / Nama Lengkap',
                    'code': 'Kode Cabang',
                    'address': 'Alamat Lengkap',
                    'city': 'Kota / Wilayah',
                    'phone': 'Nomor Telepon Kantor',
                    'phone_number': 'Nomor HP / WhatsApp',
                    'email': 'Alamat Email Login',
                    'is_active': 'Status Keaktifan Cabang',
                    'status': 'Status Akun Pengguna',
                    'branch_id': 'Kantor Cabang',
                    'title': 'Judul',
                    'description': 'Deskripsi / Keterangan',
                    'score': 'Nilai / Skor',
                    'passing_score': 'Batas Nilai Kelulusan',
                    'duration': 'Durasi Pengerjaan',
                    'start_time': 'Waktu Mulai',
                    'end_time': 'Waktu Selesai',
                    'question': 'Pertanyaan / Soal',
                    'password': 'Kata Sandi Login',
                    'role': 'Peran Pengguna',
                    'total_questions': 'Jumlah Soal',
                    'order': 'Urutan',
                    'status_kelulusan': 'Status Kelulusan',
                    'certificate_code': 'Nomor / Kode Sertifikat',
                };

                const ENTITY_LABELS = {
                    'App\\Models\\Branch': 'Kantor Cabang',
                    'App\\Models\\User': 'Akun Pengguna',
                    'App\\Models\\Quiz': 'Kuis',
                    'App\\Models\\Question': 'Bank Soal',
                    'App\\Models\\ClassSchedule': 'Jadwal Kelas',
                    'App\\Models\\ClassSession': 'Sesi Kelas',
                    'App\\Models\\Course': 'Kursus / Materi',
                    'App\\Models\\Enrollment': 'Pendaftaran Kelas',
                    'App\\Models\\GraduationReview': 'Review Kelulusan',
                    'App\\Models\\QuizAttempt': 'Pengerjaan Kuis',
                    'App\\Models\\Certificate': 'Sertifikat',
                    'App\\Models\\QuizQuestion': 'Pertanyaan Kuis',
                    'App\\Models\\BranchClass': 'Kelas Cabang',
                    'Branch': 'Kantor Cabang',
                    'User': 'Akun Pengguna',
                    'Quiz': 'Kuis',
                    'Question': 'Bank Soal',
                };

                const IGNORED_KEYS = ['id', 'created_at', 'updated_at', 'remember_token', 'email_verified_at', 'deleted_at'];

                return {
                    isOpen: false,
                    activeTab: 'human',
                    showUnchanged: false,
                    copiedOld: false,
                    copiedNew: false,
                    currentLog: {},
                    diff: {
                        changes: [],
                        unchanged: [],
                        created: [],
                        deleted: []
                    },

                    openModal(log) {
                        this.currentLog = log || {};
                        this.activeTab = 'human';
                        this.showUnchanged = false;
                        this.copiedOld = false;
                        this.copiedNew = false;
                        this.analyzeProperties(log.properties_old, log.properties_new, log.action);
                        this.isOpen = true;
                    },

                    formatEntity(entity, id) {
                        if (!entity) return 'Sistem / Umum';
                        const clean = entity.replace(/\\\\/g, '\\');
                        const label = ENTITY_LABELS[clean] || ENTITY_LABELS[entity] || entity.split('\\').pop();
                        return id ? `${label} (ID #${id})` : label;
                    },

                    formatIp(ip) {
                        if (!ip || ip === '127.0.0.1' || ip === '::1') {
                            return (ip || '127.0.0.1') + ' (Localhost / Server Internal)';
                        }
                        return ip;
                    },

                    formatDateTime(dt) {
                        if (!dt) return '-';
                        try {
                            const d = new Date(dt);
                            if (isNaN(d.getTime())) return dt;
                            return d.toLocaleString('id-ID', {
                                day: 'numeric',
                                month: 'short',
                                year: 'numeric',
                                hour: '2-digit',
                                minute: '2-digit',
                                second: '2-digit'
                            }) + ' WIB';
                        } catch (e) {
                            return dt;
                        }
                    },

                    formatValue(key, val) {
                        if (val === null || val === undefined || val === '') {
                            return '(Kosong)';
                        }
                        if (typeof val === 'boolean') {
                            if (key === 'is_active' || key === 'status') {
                                return val ? 'Aktif' : 'Nonaktif';
                            }
                            return val ? 'Ya' : 'Tidak';
                        }
                        if (key === 'password') {
                            return '•••••••• (Dienkripsi / Dirahasiakan)';
                        }
                        if (typeof val === 'object') {
                            return JSON.stringify(val);
                        }
                        return String(val);
                    },

                    humanizeKey(key) {
                        return FIELD_LABELS[key] || key.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
                    },

                    analyzeProperties(oldProps, newProps, action) {
                        oldProps = oldProps || {};
                        newProps = newProps || {};

                        const changes = [];
                        const unchanged = [];
                        const created = [];
                        const deleted = [];

                        if (action === 'CREATE') {
                            for (const [key, val] of Object.entries(newProps)) {
                                if (IGNORED_KEYS.includes(key)) continue;
                                created.push({
                                    key: key,
                                    label: this.humanizeKey(key),
                                    val: this.formatValue(key, val),
                                });
                            }
                        } else if (action === 'DELETE') {
                            for (const [key, val] of Object.entries(oldProps)) {
                                if (IGNORED_KEYS.includes(key)) continue;
                                deleted.push({
                                    key: key,
                                    label: this.humanizeKey(key),
                                    val: this.formatValue(key, val),
                                });
                            }
                        } else {
                            const allKeys = Array.from(new Set([...Object.keys(oldProps), ...Object.keys(newProps)]));
                            for (const key of allKeys) {
                                if (IGNORED_KEYS.includes(key)) continue;

                                const oldVal = oldProps[key];
                                const newVal = newProps[key];

                                const formattedOld = this.formatValue(key, oldVal);
                                const formattedNew = this.formatValue(key, newVal);

                                const isDifferent = JSON.stringify(oldVal) !== JSON.stringify(newVal);

                                if (isDifferent) {
                                    changes.push({
                                        key: key,
                                        label: this.humanizeKey(key),
                                        oldVal: formattedOld,
                                        newVal: formattedNew,
                                    });
                                } else {
                                    unchanged.push({
                                        key: key,
                                        label: this.humanizeKey(key),
                                        val: formattedNew,
                                    });
                                }
                            }
                        }

                        this.diff = { changes, unchanged, created, deleted };
                    },

                    formatJson(obj) {
                        if (!obj || (typeof obj === 'object' && Object.keys(obj).length === 0)) return '{\n  // Tidak ada data properti\n}';
                        try {
                            return JSON.stringify(obj, null, 2);
                        } catch (e) {
                            return String(obj);
                        }
                    },

                    copyJson(text, type) {
                        if (!text) return;
                        navigator.clipboard.writeText(text).then(() => {
                            if (type === 'old') {
                                this.copiedOld = true;
                                setTimeout(() => this.copiedOld = false, 2000);
                            } else {
                                this.copiedNew = true;
                                setTimeout(() => this.copiedNew = false, 2000);
                            }
                        });
                    }
                }
            }
        </script>
    @endpush
</x-app-layout>
