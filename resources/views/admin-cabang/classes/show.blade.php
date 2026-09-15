<x-app-layout>
    <div class="py-8" x-data="{ sessionModal: false }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Breadcrumb & Actions -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <a href="{{ route('cabang.classes.index') }}" class="inline-flex items-center gap-1.5 text-xs font-montserrat font-bold text-[#6E675F] hover:text-[#FF6B00] transition mb-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        <span>Kembali ke Daftar Kelas</span>
                    </a>
                    <div class="flex flex-wrap items-center gap-3">
                        <h1 class="font-montserrat font-extrabold text-2xl sm:text-3xl text-[#1E1B18] tracking-tight">
                            {{ $class->title }}
                        </h1>
                        @php
                            $statusConfig = match($class->status) {
                                'draft' => ['label' => 'Draft', 'class' => 'bg-gray-100 text-gray-700 border-gray-200'],
                                'open' => ['label' => 'Buka Pendaftaran', 'class' => 'bg-[#ECFDF5] text-[#10B981] border-[#A7F3D0]'],
                                'ongoing' => ['label' => 'Sedang Berjalan', 'class' => 'bg-[#FFF7ED] text-[#FF6B00] border-[#FED7AA]'],
                                'completed' => ['label' => 'Selesai', 'class' => 'bg-blue-50 text-blue-700 border-blue-200'],
                                'cancelled' => ['label' => 'Dibatalkan', 'class' => 'bg-[#FEF2F2] text-[#DC2626] border-[#FECACA]'],
                            };
                        @endphp
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold font-montserrat border {{ $statusConfig['class'] }}">
                            ● {{ $statusConfig['label'] }}
                        </span>
                    </div>
                    <p class="font-quicksand text-xs text-[#6E675F] mt-1">
                        Cabang: <strong>{{ $branch->name }}</strong> • Periode: {{ $class->start_date->format('d M Y') }} s/d {{ $class->end_date->format('d M Y') }}
                    </p>
                </div>

                <div class="flex items-center gap-2">
                    <a href="{{ route('cabang.classes.edit', $class) }}"
                       class="px-4 py-2 rounded-xl font-montserrat font-bold text-xs bg-white hover:bg-[#FAF8F5] border border-[#EBE5DF] text-[#1E1B18] transition shadow-xs">
                        Edit Kelas
                    </a>
                    <button type="button"
                            @click="sessionModal = true"
                            class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl font-montserrat font-bold text-xs text-white bg-gradient-to-r from-[#FF6B00] to-[#E11D48] hover:from-[#EA580C] hover:to-[#DC2626] shadow-md hover:shadow-lg transition transform hover:-translate-y-0.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        <span>Jadwalkan Sesi Zoom</span>
                    </button>
                </div>
            </div>

            <!-- Overview & Progress Cards -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Card 1: Pelatih & Format Kelas -->
                <div class="bg-white rounded-3xl p-6 border border-[#EBE5DF] shadow-sm space-y-4">
                    <div class="font-montserrat font-bold text-xs uppercase tracking-wider text-[#6E675F]">
                        Informasi Penyelenggara
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-11 h-11 rounded-2xl bg-gradient-to-tr from-[#FF6B00] to-[#E11D48] text-white flex items-center justify-center font-montserrat font-bold text-base shadow-xs">
                            {{ strtoupper(substr($class->trainer?->name ?? 'T', 0, 1)) }}
                        </div>
                        <div>
                            <div class="font-montserrat font-bold text-sm text-[#1E1B18]">{{ $class->trainer?->name ?? 'Belum ada trainer' }}</div>
                            <div class="text-xs text-[#6E675F]">{{ $class->trainer?->email }}</div>
                        </div>
                    </div>
                    <div class="pt-3 border-t border-[#FAF8F5] space-y-2 text-xs">
                        <div class="flex justify-between">
                            <span class="text-[#6E675F]">Format Kelas:</span>
                            <span class="font-bold text-[#1E1B18] uppercase">{{ $class->type }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-[#6E675F]">Status Operasional:</span>
                            <form method="POST" action="{{ route('cabang.classes.update-status', $class) }}" class="inline">
                                @csrf
                                @method('PATCH')
                                <select name="status" onchange="this.form.submit()" class="py-0.5 px-2 text-xs rounded-lg border border-[#EBE5DF] bg-[#FAF8F5] font-semibold text-[#1E1B18]">
                                    <option value="draft" {{ $class->status === 'draft' ? 'selected' : '' }}>Draft</option>
                                    <option value="open" {{ $class->status === 'open' ? 'selected' : '' }}>Buka Pendaftaran</option>
                                    <option value="ongoing" {{ $class->status === 'ongoing' ? 'selected' : '' }}>Sedang Berjalan</option>
                                    <option value="completed" {{ $class->status === 'completed' ? 'selected' : '' }}>Selesai</option>
                                    <option value="cancelled" {{ $class->status === 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                                </select>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Card 2: Kuota & Kapasitas Sesuai Aturan Ketat -->
                <div class="bg-white rounded-3xl p-6 border border-[#EBE5DF] shadow-sm space-y-4">
                    <div class="font-montserrat font-bold text-xs uppercase tracking-wider text-[#6E675F]">
                        Kapasitas & Sisa Kuota
                    </div>

                    @if($class->isOffline() || $class->isHybrid())
                        <div class="p-3 bg-[#FEF2F2] rounded-2xl border border-[#FECACA]">
                            <div class="flex items-center justify-between text-xs mb-1">
                                <span class="font-montserrat font-bold text-[#DC2626]">Kursi Fisik di Kelas (Maks 40)</span>
                                <span class="font-mono font-bold text-[#1E1B18]">{{ $class->enrolled_offline }} / {{ $class->offline_capacity }}</span>
                            </div>
                            <div class="w-full bg-white rounded-full h-2 overflow-hidden border border-[#FECACA]">
                                @php
                                    $offlinePercent = $class->offline_capacity > 0 ? min(100, ($class->enrolled_offline / $class->offline_capacity) * 100) : 0;
                                @endphp
                                <div class="bg-[#DC2626] h-full rounded-full transition-all duration-300" style="width: {{ $offlinePercent }}%"></div>
                            </div>
                            <div class="text-[11px] text-[#DC2626] mt-1 font-semibold">
                                Tersisa {{ $class->remainingOfflineSeats() }} kursi tatap muka fisik
                            </div>
                        </div>
                    @endif

                    @if($class->isOnline() || $class->isHybrid())
                        <div class="p-3 bg-[#FFF7ED] rounded-2xl border border-[#FED7AA]">
                            <div class="flex items-center justify-between text-xs mb-1">
                                <span class="font-montserrat font-bold text-[#FF6B00]">Kuota Daring (Zoom)</span>
                                <span class="font-mono font-bold text-[#1E1B18]">{{ $class->enrolled_online }} / {{ $class->online_capacity }}</span>
                            </div>
                            <div class="w-full bg-white rounded-full h-2 overflow-hidden border border-[#FED7AA]">
                                @php
                                    $onlinePercent = $class->online_capacity > 0 ? min(100, ($class->enrolled_online / $class->online_capacity) * 100) : 0;
                                @endphp
                                <div class="bg-[#FF6B00] h-full rounded-full transition-all duration-300" style="width: {{ $onlinePercent }}%"></div>
                            </div>
                            <div class="text-[11px] text-[#6E675F] mt-1 font-semibold">
                                Tersisa {{ $class->remainingOnlineSeats() }} kuota daring online
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Card 3: Target 20 JP (900 Menit) Tracker -->
                <div class="bg-white rounded-3xl p-6 border border-[#EBE5DF] shadow-sm space-y-4">
                    <div class="font-montserrat font-bold text-xs uppercase tracking-wider text-[#6E675F]">
                        Target Kelulusan 20 JP (900 Menit)
                    </div>

                    @php
                        $totalJp = $class->totalAccumulatedJp();
                        $totalMinutes = $class->totalAccumulatedMinutes();
                        $targetJp = $class->required_jp ?: 20;
                        $percentJp = min(100, ($totalJp / $targetJp) * 100);
                    @endphp

                    <div>
                        <div class="flex items-baseline justify-between mb-1.5">
                            <div class="font-montserrat font-extrabold text-3xl text-[#1E1B18]">
                                {{ $totalJp }} <span class="text-sm font-semibold text-[#6E675F]">/ {{ $targetJp }} JP</span>
                            </div>
                            <span class="text-xs font-bold font-mono text-[#FF6B00]">{{ round($percentJp) }}% Terjadwal</span>
                        </div>

                        <div class="w-full bg-[#FAF8F5] rounded-full h-3 overflow-hidden border border-[#EBE5DF]">
                            <div class="bg-gradient-to-r from-[#FF6B00] to-[#E11D48] h-full rounded-full transition-all duration-300"
                                 style="width: {{ $percentJp }}%"></div>
                        </div>

                        <div class="flex items-center justify-between text-xs text-[#6E675F] mt-2">
                            <span>Akumulasi: <strong>{{ $totalMinutes }}</strong> / 900 Menit</span>
                            <span>{{ $class->sessions->count() }} Sesi</span>
                        </div>

                        @if($totalJp < $targetJp)
                            <p class="text-[11px] text-[#EA580C] mt-2 bg-[#FFF7ED] p-2 rounded-xl border border-[#FED7AA]">
                                ℹ️ Butuh <strong>{{ $targetJp - $totalJp }} JP lagi</strong> untuk melengkapi kurikulum standar 20 JP.
                            </p>
                        @else
                            <p class="text-[11px] text-[#065F46] mt-2 bg-[#ECFDF5] p-2 rounded-xl border border-[#A7F3D0] font-semibold">
                                ✓ Kurikulum 20 JP telah lengkap terjadwal!
                            </p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Tabel Sesi Pertemuan & Integrasi Zoom -->
            <div class="bg-white rounded-3xl border border-[#EBE5DF] shadow-sm overflow-hidden">
                <div class="p-6 border-b border-[#EBE5DF] flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h3 class="font-montserrat font-bold text-lg text-[#1E1B18]">
                            Jadwal Sesi Pembelajaran & Tautan Zoom
                        </h3>
                        <p class="font-quicksand text-xs text-[#6E675F] mt-0.5">
                            Setiap sesi memiliki durasi jam pelajaran (1 JP = 45 menit) dan link Zoom meeting untuk tatap muka daring.
                        </p>
                    </div>

                    <button type="button"
                            @click="sessionModal = true"
                            class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl font-montserrat font-bold text-xs text-white bg-[#1E1B18] hover:bg-[#322E2B] transition shadow-xs">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        <span>Tambah Sesi Baru</span>
                    </button>
                </div>

                @if($class->sessions->isEmpty())
                    <div class="p-12 text-center">
                        <div class="w-16 h-16 mx-auto rounded-full bg-[#FFF7ED] text-[#FF6B00] flex items-center justify-center mb-4">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <h4 class="font-montserrat font-bold text-base text-[#1E1B18]">Belum ada jadwal sesi pertemuan</h4>
                        <p class="font-quicksand text-xs text-[#6E675F] mt-1 max-w-md mx-auto">
                            Klik tombol "Tambah Sesi Baru" di atas untuk menjadwalkan pertemuan kelas dan menambahkan tautan Zoom.
                        </p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm">
                            <thead class="bg-[#FAF8F5] border-b border-[#EBE5DF] text-xs font-montserrat font-bold text-[#6E675F] uppercase tracking-wider">
                                <tr>
                                    <th class="py-4 px-6 text-center">Urutan</th>
                                    <th class="py-4 px-6">Topik Sesi</th>
                                    <th class="py-4 px-6">Durasi JP & Menit</th>
                                    <th class="py-4 px-6">Jadwal Tanggal & Waktu</th>
                                    <th class="py-4 px-6">Informasi Zoom Meeting</th>
                                    <th class="py-4 px-6 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-[#EBE5DF] font-quicksand">
                                @foreach($class->sessions as $session)
                                    <tr class="hover:bg-[#FAF8F5]/60 transition">
                                        <td class="py-4 px-6 text-center">
                                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-xl bg-[#FFF7ED] text-[#FF6B00] font-montserrat font-bold text-xs">
                                                #{{ $session->session_order }}
                                            </span>
                                        </td>
                                        <td class="py-4 px-6">
                                            <div class="font-montserrat font-bold text-sm text-[#1E1B18]">{{ $session->title }}</div>
                                            <div class="text-xs text-[#6E675F]">Dibuat oleh: {{ $session->creator?->name ?? 'System' }}</div>
                                        </td>
                                        <td class="py-4 px-6 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold font-mono bg-[#FAF8F5] border border-[#EBE5DF] text-[#1E1B18]">
                                                {{ $session->jp_duration }} JP ({{ $session->minute_duration }} Menit)
                                            </span>
                                        </td>
                                        <td class="py-4 px-6 whitespace-nowrap text-xs">
                                            <div class="font-semibold text-[#1E1B18]">{{ $session->session_date->format('d M Y') }}</div>
                                            <div class="text-[#6E675F]">{{ $session->session_date->format('H:i') }} WIB</div>
                                        </td>
                                        <td class="py-4 px-6">
                                            @if($session->zoom_url)
                                                <div class="space-y-1">
                                                    <a href="{{ $session->zoom_url }}" target="_blank"
                                                       class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-[#0284C7] hover:bg-[#0369A1] text-white font-montserrat font-bold text-xs transition">
                                                        <span>📹 Buka Zoom Meeting</span>
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                                        </svg>
                                                    </a>
                                                    @if($session->zoom_meeting_id || $session->zoom_passcode)
                                                        <div class="text-[11px] text-[#6E675F] font-mono">
                                                            ID: {{ $session->zoom_meeting_id ?: '-' }} • Passcode: {{ $session->zoom_passcode ?: '-' }}
                                                        </div>
                                                    @endif
                                                </div>
                                            @else
                                                <span class="text-xs text-[#6E675F] italic">Belum ada link Zoom</span>
                                            @endif
                                        </td>
                                        <td class="py-4 px-6 text-right whitespace-nowrap">
                                            <div class="inline-flex items-center gap-1.5 justify-end">
                                                <a href="{{ route('cabang.sessions.edit', [$class, $session]) }}"
                                                   class="p-2 rounded-lg text-[#6E675F] hover:text-[#FF6B00] hover:bg-[#FFF7ED] transition" title="Edit Sesi">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                    </svg>
                                                </a>

                                                <button type="button"
                                                        onclick="handleDeleteSession('{{ $class->id }}', '{{ $session->id }}', '{{ addslashes($session->title) }}')"
                                                        class="p-2 rounded-lg text-[#6E675F] hover:text-[#DC2626] hover:bg-[#FEF2F2] transition" title="Hapus Sesi">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>

                                                <form id="delete-session-{{ $session->id }}"
                                                      method="POST"
                                                      action="{{ route('cabang.sessions.destroy', [$class, $session]) }}"
                                                      class="hidden">
                                                    @csrf
                                                    @method('DELETE')
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

            <!-- Modal Tambah Sesi Zoom -->
            <div x-show="sessionModal"
                 x-cloak
                 class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-xs transition-opacity"
                 @keydown.escape.window="sessionModal = false">
                <div @click.away="sessionModal = false"
                     class="bg-white rounded-3xl border border-[#EBE5DF] shadow-2xl max-w-xl w-full flex flex-col overflow-hidden animate-in fade-in zoom-in-95 duration-200">

                    <div class="p-6 border-b border-[#EBE5DF] flex items-center justify-between">
                        <div>
                            <h3 class="font-montserrat font-bold text-lg text-[#1E1B18]">Tambah Sesi Jadwal Pertemuan</h3>
                            <p class="font-quicksand text-xs text-[#6E675F]">Tambahkan sesi baru, durasi JP, dan tautan Zoom untuk kelas ini.</p>
                        </div>
                        <button @click="sessionModal = false" class="text-[#6E675F] hover:text-[#1E1B18] p-2 rounded-xl hover:bg-[#FAF8F5]">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <form method="POST" action="{{ route('cabang.sessions.store', $class) }}" class="p-6 space-y-4">
                        @csrf

                        <!-- Topik & Urutan Sesi -->
                        <div class="grid grid-cols-4 gap-4">
                            <div class="col-span-3">
                                <label for="modal_title" class="block font-montserrat font-bold text-xs uppercase tracking-wider text-[#1E1B18] mb-1">
                                    Topik Materi Sesi <span class="text-[#DC2626]">*</span>
                                </label>
                                <input type="text"
                                       id="modal_title"
                                       name="title"
                                       required
                                       placeholder="Contoh: Pengenalan Dasar HTML & Struktur Dokumen"
                                       class="w-full px-4 py-2 rounded-xl border border-[#EBE5DF] bg-[#FAF8F5] text-sm text-[#1E1B18] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#FF6B00] font-quicksand transition">
                            </div>

                            <div>
                                <label for="modal_session_order" class="block font-montserrat font-bold text-xs uppercase tracking-wider text-[#1E1B18] mb-1">
                                    Sesi Ke <span class="text-[#DC2626]">*</span>
                                </label>
                                <input type="number"
                                       id="modal_session_order"
                                       name="session_order"
                                       value="{{ $class->sessions->count() + 1 }}"
                                       min="1"
                                       required
                                       class="w-full px-3 py-2 rounded-xl border border-[#EBE5DF] bg-[#FAF8F5] text-sm font-bold text-[#1E1B18] focus:bg-white font-mono transition">
                            </div>
                        </div>

                        <!-- Durasi JP & Waktu Sesi -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="modal_jp_duration" class="block font-montserrat font-bold text-xs uppercase tracking-wider text-[#1E1B18] mb-1">
                                    Durasi JP <span class="text-[#DC2626]">*</span>
                                </label>
                                <div class="relative">
                                    <input type="number"
                                           id="modal_jp_duration"
                                           name="jp_duration"
                                           value="2"
                                           min="1"
                                           max="10"
                                           required
                                           class="w-full px-4 py-2 rounded-xl border border-[#EBE5DF] bg-[#FAF8F5] text-sm font-bold text-[#FF6B00] focus:bg-white font-mono transition">
                                    <span class="absolute right-3 top-2 text-xs text-[#6E675F]">JP (x45m)</span>
                                </div>
                            </div>

                            <div>
                                <label for="modal_session_date" class="block font-montserrat font-bold text-xs uppercase tracking-wider text-[#1E1B18] mb-1">
                                    Waktu Pelaksanaan <span class="text-[#DC2626]">*</span>
                                </label>
                                <input type="datetime-local"
                                       id="modal_session_date"
                                       name="session_date"
                                       required
                                       value="{{ date('Y-m-d\T09:00') }}"
                                       class="w-full px-3 py-2 rounded-xl border border-[#EBE5DF] bg-[#FAF8F5] text-sm text-[#1E1B18] focus:bg-white font-quicksand transition">
                            </div>
                        </div>

                        <!-- Zoom URL -->
                        <div>
                            <label for="modal_zoom_url" class="block font-montserrat font-bold text-xs uppercase tracking-wider text-[#1E1B18] mb-1">
                                Tautan Masuk Zoom Meeting
                            </label>
                            <input type="url"
                                   id="modal_zoom_url"
                                   name="zoom_url"
                                   placeholder="https://zoom.us/j/1234567890?pwd=..."
                                   class="w-full px-4 py-2 rounded-xl border border-[#EBE5DF] bg-[#FAF8F5] text-sm text-[#1E1B18] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#FF6B00] font-quicksand transition">
                        </div>

                        <!-- Zoom Meeting ID & Passcode -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="modal_zoom_meeting_id" class="block font-quicksand text-xs font-semibold text-[#6E675F] mb-1">
                                    Meeting ID (Opsional)
                                </label>
                                <input type="text"
                                       id="modal_zoom_meeting_id"
                                       name="zoom_meeting_id"
                                       placeholder="Misal: 812 3456 7890"
                                       class="w-full px-3 py-2 rounded-xl border border-[#EBE5DF] bg-[#FAF8F5] text-sm font-mono text-[#1E1B18] focus:bg-white transition">
                            </div>

                            <div>
                                <label for="modal_zoom_passcode" class="block font-quicksand text-xs font-semibold text-[#6E675F] mb-1">
                                    Passcode (Opsional)
                                </label>
                                <input type="text"
                                       id="modal_zoom_passcode"
                                       name="zoom_passcode"
                                       placeholder="Misal: LMS123"
                                       class="w-full px-3 py-2 rounded-xl border border-[#EBE5DF] bg-[#FAF8F5] text-sm font-mono text-[#1E1B18] focus:bg-white transition">
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="pt-4 flex items-center justify-end gap-3 border-t border-[#EBE5DF]">
                            <button type="button"
                                    @click="sessionModal = false"
                                    class="px-4 py-2 rounded-xl font-montserrat font-bold text-xs text-[#6E675F] hover:bg-[#FAF8F5]">
                                Batal
                            </button>
                            <button type="submit"
                                    class="px-5 py-2.5 rounded-xl font-montserrat font-bold text-xs text-white bg-gradient-to-r from-[#FF6B00] to-[#E11D48] hover:from-[#EA580C] hover:to-[#DC2626] shadow-md transition">
                                Simpan Sesi Pertemuan
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>

    @push('scripts')
        <script>
            function handleDeleteSession(classId, sessionId, title) {
                window.confirmAction({
                    title: 'Hapus Sesi Pertemuan?',
                    text: `Anda yakin ingin menghapus "${title}"? Durasi JP kelas akan disesuaikan otomatis.`,
                    confirmText: 'Ya, Hapus Sesi',
                    cancelText: 'Batal',
                    icon: 'warning'
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById(`delete-session-${sessionId}`).submit();
                    }
                });
            }
        </script>
    @endpush
</x-app-layout>
