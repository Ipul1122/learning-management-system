<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Header Section -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold font-montserrat bg-[#FFF7ED] text-[#EA580C] border border-[#FED7AA] mb-2">
                        <span>🏢</span>
                        <span>{{ $branch->name }} ({{ $branch->code }})</span>
                    </div>
                    <h1 class="font-montserrat font-extrabold text-2xl sm:text-3xl text-[#1E1B18] tracking-tight">
                        Manajemen Kelas Pelatihan
                    </h1>
                    <p class="font-quicksand text-sm text-[#6E675F] mt-1">
                        Kelola kelas Offline (kuota maks 40 orang), Online & Hybrid, serta jadwal sesi pertemuan Zoom.
                    </p>
                </div>
                <div>
                    <a href="{{ route('cabang.classes.create') }}"
                       class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl font-montserrat font-bold text-sm text-white bg-gradient-to-r from-[#FF6B00] to-[#E11D48] hover:from-[#EA580C] hover:to-[#DC2626] shadow-md hover:shadow-lg transition-all duration-200 transform hover:-translate-y-0.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        <span>Buka Kelas Baru</span>
                    </a>
                </div>
            </div>

            <!-- Metric Cards -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                <div class="bg-white p-5 rounded-2xl border border-[#EBE5DF] shadow-sm">
                    <div class="font-quicksand text-xs font-semibold text-[#6E675F] uppercase tracking-wider">Total Kelas</div>
                    <div class="font-montserrat font-extrabold text-2xl text-[#1E1B18] mt-1">{{ $stats['total'] }}</div>
                </div>

                <div class="bg-white p-5 rounded-2xl border border-[#EBE5DF] shadow-sm">
                    <div class="font-quicksand text-xs font-semibold text-[#10B981] uppercase tracking-wider">Buka Pendaftaran</div>
                    <div class="font-montserrat font-extrabold text-2xl text-[#10B981] mt-1">{{ $stats['open'] }}</div>
                </div>

                <div class="bg-white p-5 rounded-2xl border border-[#EBE5DF] shadow-sm">
                    <div class="font-quicksand text-xs font-semibold text-[#FF6B00] uppercase tracking-wider">Sedang Berjalan</div>
                    <div class="font-montserrat font-extrabold text-2xl text-[#FF6B00] mt-1">{{ $stats['ongoing'] }}</div>
                </div>

                <div class="bg-white p-5 rounded-2xl border border-[#EBE5DF] shadow-sm">
                    <div class="font-quicksand text-xs font-semibold text-[#6E675F] uppercase tracking-wider">Telah Selesai</div>
                    <div class="font-montserrat font-extrabold text-2xl text-[#6E675F] mt-1">{{ $stats['completed'] }}</div>
                </div>
            </div>

            <!-- Filter & Search Bar -->
            <div class="bg-white p-4 rounded-2xl border border-[#EBE5DF] shadow-sm">
                <form method="GET" action="{{ route('cabang.classes.index') }}" class="grid grid-cols-1 sm:grid-cols-4 gap-3">
                    <div class="sm:col-span-2 relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-[#6E675F]">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </span>
                        <input type="text"
                               name="search"
                               value="{{ request('search') }}"
                               placeholder="Cari judul kelas atau nama trainer..."
                               class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-[#EBE5DF] bg-[#FAF8F5] text-sm text-[#1E1B18] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#FF6B00] focus:border-transparent font-quicksand transition">
                    </div>

                    <div>
                        <select name="type"
                                onchange="this.form.submit()"
                                class="w-full py-2.5 px-3 rounded-xl border border-[#EBE5DF] bg-[#FAF8F5] text-sm text-[#1E1B18] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#FF6B00] focus:border-transparent font-quicksand">
                            <option value="">Semua Format Kelas</option>
                            <option value="offline" {{ request('type') === 'offline' ? 'selected' : '' }}>Offline (Maks 40)</option>
                            <option value="online" {{ request('type') === 'online' ? 'selected' : '' }}>Online (Ratusan)</option>
                            <option value="hybrid" {{ request('type') === 'hybrid' ? 'selected' : '' }}>Hybrid (Dual Kuota)</option>
                        </select>
                    </div>

                    <div class="flex gap-2">
                        <select name="status"
                                onchange="this.form.submit()"
                                class="w-full py-2.5 px-3 rounded-xl border border-[#EBE5DF] bg-[#FAF8F5] text-sm text-[#1E1B18] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#FF6B00] focus:border-transparent font-quicksand">
                            <option value="">Semua Status</option>
                            <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="open" {{ request('status') === 'open' ? 'selected' : '' }}>Buka Pendaftaran</option>
                            <option value="ongoing" {{ request('status') === 'ongoing' ? 'selected' : '' }}>Sedang Berjalan</option>
                            <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Selesai</option>
                            <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                        </select>

                        @if(request()->hasAny(['search', 'type', 'status']))
                            <a href="{{ route('cabang.classes.index') }}"
                               class="px-4 py-2.5 bg-[#F3EFEA] hover:bg-[#EBE5DF] text-[#1E1B18] font-montserrat font-bold text-sm rounded-xl transition flex items-center">
                                Reset
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <!-- Table / Card List -->
            <div class="bg-white rounded-2xl border border-[#EBE5DF] shadow-sm overflow-hidden">
                @if($classes->isEmpty())
                    <div class="p-12 text-center">
                        <div class="w-16 h-16 mx-auto rounded-full bg-[#FFF7ED] text-[#FF6B00] flex items-center justify-center mb-4">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                            </svg>
                        </div>
                        <h3 class="font-montserrat font-bold text-lg text-[#1E1B18]">Belum ada kelas pelatihan</h3>
                        <p class="font-quicksand text-sm text-[#6E675F] mt-1 max-w-md mx-auto">
                            Buat kelas pelatihan baru untuk cabang ini. Anda dapat menentukan format Offline (maks 40 peserta), Online, atau Hybrid.
                        </p>
                    </div>
                @else
                    <!-- Desktop Table -->
                    <div class="hidden md:block overflow-x-auto">
                        <table class="w-full text-left text-sm">
                            <thead class="bg-[#FAF8F5] border-b border-[#EBE5DF] text-xs font-montserrat font-bold text-[#6E675F] uppercase tracking-wider">
                                <tr>
                                    <th class="py-4 px-6">Kelas & Periode</th>
                                    <th class="py-4 px-6">Tipe & Kuota</th>
                                    <th class="py-4 px-6">Trainer Pengampu</th>
                                    <th class="py-4 px-6 text-center">Sesi / Durasi JP</th>
                                    <th class="py-4 px-6 text-center">Status</th>
                                    <th class="py-4 px-6 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-[#EBE5DF] font-quicksand">
                                @foreach($classes as $class)
                                    <tr class="hover:bg-[#FAF8F5]/60 transition">
                                        <td class="py-4 px-6">
                                            <a href="{{ route('cabang.classes.show', $class) }}" class="font-montserrat font-bold text-sm text-[#1E1B18] hover:text-[#FF6B00] transition">
                                                {{ $class->title }}
                                            </a>
                                            <div class="text-xs text-[#6E675F] mt-0.5">
                                                {{ $class->start_date->format('d M') }} - {{ $class->end_date->format('d M Y') }}
                                            </div>
                                        </td>
                                        <td class="py-4 px-6">
                                            @php
                                                $typeBadge = match($class->type) {
                                                    'offline' => ['label' => 'Offline (Maks 40)', 'bg' => 'bg-[#FEF2F2]', 'text' => 'text-[#DC2626]', 'border' => 'border-[#FECACA]'],
                                                    'online' => ['label' => 'Online', 'bg' => 'bg-[#FFF7ED]', 'text' => 'text-[#FF6B00]', 'border' => 'border-[#FED7AA]'],
                                                    'hybrid' => ['label' => 'Hybrid (Dual)', 'bg' => 'bg-[#ECFDF5]', 'text' => 'text-[#10B981]', 'border' => 'border-[#A7F3D0]'],
                                                };
                                            @endphp
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold font-montserrat {{ $typeBadge['bg'] }} {{ $typeBadge['text'] }} border {{ $typeBadge['border'] }}">
                                                {{ $typeBadge['label'] }}
                                            </span>
                                            <div class="text-xs text-[#6E675F] mt-1 font-semibold">
                                                @if($class->isOffline())
                                                    <span>{{ $class->enrolled_offline }} / {{ $class->offline_capacity }} Kursi</span>
                                                @elseif($class->isOnline())
                                                    <span>{{ $class->enrolled_online }} / {{ $class->online_capacity }} Peserta</span>
                                                @else
                                                    <span>Fisik: {{ $class->enrolled_offline }}/{{ $class->offline_capacity }} • Zoom: {{ $class->enrolled_online }}/{{ $class->online_capacity }}</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="py-4 px-6">
                                            <div class="font-semibold text-[#1E1B18]">{{ $class->trainer?->name ?? '-' }}</div>
                                            <div class="text-xs text-[#6E675F]">{{ $class->trainer?->email }}</div>
                                        </td>
                                        <td class="py-4 px-6 text-center">
                                            <span class="font-bold text-[#1E1B18]">{{ $class->sessions_count }} Sesi</span>
                                            <div class="text-xs font-bold text-[#FF6B00]">
                                                {{ $class->totalAccumulatedJp() }} / {{ $class->required_jp }} JP
                                            </div>
                                        </td>
                                        <td class="py-4 px-6 text-center">
                                            @php
                                                $statusConfig = match($class->status) {
                                                    'draft' => ['label' => 'Draft', 'class' => 'bg-gray-100 text-gray-700 border-gray-200'],
                                                    'open' => ['label' => 'Buka', 'class' => 'bg-[#ECFDF5] text-[#10B981] border-[#A7F3D0]'],
                                                    'ongoing' => ['label' => 'Berjalan', 'class' => 'bg-[#FFF7ED] text-[#FF6B00] border-[#FED7AA]'],
                                                    'completed' => ['label' => 'Selesai', 'class' => 'bg-blue-50 text-blue-700 border-blue-200'],
                                                    'cancelled' => ['label' => 'Batal', 'class' => 'bg-[#FEF2F2] text-[#DC2626] border-[#FECACA]'],
                                                };
                                            @endphp
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold font-montserrat border {{ $statusConfig['class'] }}">
                                                {{ $statusConfig['label'] }}
                                            </span>
                                        </td>
                                        <td class="py-4 px-6 text-right">
                                            <div class="inline-flex items-center gap-1.5 justify-end">
                                                <!-- View Detail -->
                                                <a href="{{ route('cabang.classes.show', $class) }}"
                                                   title="Kelola Sesi & Zoom"
                                                   class="p-2 rounded-lg text-[#6E675F] hover:text-[#FF6B00] hover:bg-[#FFF7ED] transition">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                </a>

                                                <!-- Edit -->
                                                <a href="{{ route('cabang.classes.edit', $class) }}"
                                                   title="Edit Kelas"
                                                   class="p-2 rounded-lg text-[#6E675F] hover:text-[#1E1B18] hover:bg-[#F3EFEA] transition">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                    </svg>
                                                </a>

                                                <!-- Delete -->
                                                <button type="button"
                                                        title="Hapus Kelas"
                                                        onclick="handleDeleteClass('{{ $class->id }}', '{{ addslashes($class->title) }}')"
                                                        class="p-2 rounded-lg text-[#6E675F] hover:text-[#DC2626] hover:bg-[#FEF2F2] transition">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>

                                                <form id="delete-form-{{ $class->id }}"
                                                      method="POST"
                                                      action="{{ route('cabang.classes.destroy', $class) }}"
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

                    <!-- Mobile Cards List -->
                    <div class="block md:hidden divide-y divide-[#EBE5DF]">
                        @foreach($classes as $class)
                            <div class="p-4 space-y-3">
                                <div class="flex items-start justify-between gap-2">
                                    <div>
                                        <a href="{{ route('cabang.classes.show', $class) }}" class="font-montserrat font-bold text-sm text-[#1E1B18]">
                                            {{ $class->title }}
                                        </a>
                                        <div class="text-xs text-[#6E675F]">{{ $class->trainer?->name ?? 'Belum ada trainer' }}</div>
                                    </div>
                                    <span class="px-2 py-0.5 rounded text-xs font-bold font-montserrat uppercase bg-[#FAF8F5] border border-[#EBE5DF]">
                                        {{ $class->type }}
                                    </span>
                                </div>

                                <div class="text-xs text-[#6E675F] flex items-center justify-between">
                                    <span>{{ $class->sessions_count }} Sesi ({{ $class->totalAccumulatedJp() }}/{{ $class->required_jp }} JP)</span>
                                    <span class="font-semibold text-[#1E1B18]">{{ $class->status }}</span>
                                </div>

                                <div class="flex items-center justify-end gap-2 pt-2 border-t border-[#FAF8F5] text-xs">
                                    <a href="{{ route('cabang.classes.show', $class) }}" class="px-2.5 py-1 text-xs font-montserrat font-bold rounded-lg bg-[#FFF7ED] text-[#FF6B00]">
                                        Detail & Sesi
                                    </a>
                                    <a href="{{ route('cabang.classes.edit', $class) }}" class="px-2.5 py-1 text-xs font-montserrat font-bold rounded-lg bg-[#FAF8F5] text-[#1E1B18]">
                                        Edit
                                    </a>
                                    <button type="button" onclick="handleDeleteClass('{{ $class->id }}', '{{ addslashes($class->title) }}')" class="px-2.5 py-1 text-xs font-montserrat font-bold rounded-lg bg-[#FEF2F2] text-[#DC2626]">
                                        Hapus
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    @if($classes->hasPages())
                        <div class="p-4 border-t border-[#EBE5DF] bg-[#FAF8F5]">
                            {{ $classes->links() }}
                        </div>
                    @endif
                @endif
            </div>

        </div>
    </div>

    @push('scripts')
        <script>
            function handleDeleteClass(classId, className) {
                window.confirmAction({
                    title: 'Hapus Kelas Pelatihan?',
                    text: `Anda yakin ingin menghapus kelas "${className}" beserta seluruh jadwal sesinya? Tindakan ini tidak dapat dibatalkan.`,
                    confirmText: 'Ya, Hapus Sekarang',
                    cancelText: 'Batal',
                    icon: 'warning'
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById(`delete-form-${classId}`).submit();
                    }
                });
            }
        </script>
    @endpush
</x-app-layout>
