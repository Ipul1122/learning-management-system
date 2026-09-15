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
                        Manajemen Instruktur / Trainer
                    </h1>
                    <p class="font-quicksand text-sm text-[#6E675F] mt-1">
                        Daftarkan dan kelola instruktur pengajar khusus pada cabang {{ $branch->name }}.
                    </p>
                </div>
                <div>
                    <a href="{{ route('cabang.trainers.create') }}"
                       class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl font-montserrat font-bold text-sm text-white bg-gradient-to-r from-[#FF6B00] to-[#E11D48] hover:from-[#EA580C] hover:to-[#DC2626] shadow-md hover:shadow-lg transition-all duration-200 transform hover:-translate-y-0.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                        </svg>
                        <span>Tambah Trainer Baru</span>
                    </a>
                </div>
            </div>

            <!-- Metric Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="bg-white p-5 rounded-2xl border border-[#EBE5DF] shadow-sm flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-[#FFF7ED] text-[#FF6B00] flex items-center justify-center font-bold">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <div>
                        <div class="font-quicksand text-xs font-semibold text-[#6E675F] uppercase tracking-wider">Total Trainer</div>
                        <div class="font-montserrat font-extrabold text-2xl text-[#1E1B18]">{{ $stats['total'] }}</div>
                    </div>
                </div>

                <div class="bg-white p-5 rounded-2xl border border-[#EBE5DF] shadow-sm flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-[#ECFDF5] text-[#10B981] flex items-center justify-center font-bold">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <div class="font-quicksand text-xs font-semibold text-[#6E675F] uppercase tracking-wider">Trainer Aktif</div>
                        <div class="font-montserrat font-extrabold text-2xl text-[#1E1B18]">{{ $stats['active'] }}</div>
                    </div>
                </div>

                <div class="bg-white p-5 rounded-2xl border border-[#EBE5DF] shadow-sm flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-[#FEF2F2] text-[#DC2626] flex items-center justify-center font-bold">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                        </svg>
                    </div>
                    <div>
                        <div class="font-quicksand text-xs font-semibold text-[#6E675F] uppercase tracking-wider">Trainer Nonaktif</div>
                        <div class="font-montserrat font-extrabold text-2xl text-[#1E1B18]">{{ $stats['inactive'] }}</div>
                    </div>
                </div>
            </div>

            <!-- Filter & Search Bar -->
            <div class="bg-white p-4 rounded-2xl border border-[#EBE5DF] shadow-sm">
                <form method="GET" action="{{ route('cabang.trainers.index') }}" class="flex flex-col sm:flex-row gap-3">
                    <div class="flex-1 relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-[#6E675F]">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </span>
                        <input type="text"
                               name="search"
                               value="{{ request('search') }}"
                               placeholder="Cari nama, email, atau telepon instruktur..."
                               class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-[#EBE5DF] bg-[#FAF8F5] text-sm text-[#1E1B18] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#FF6B00] focus:border-transparent font-quicksand transition">
                    </div>

                    <div class="w-full sm:w-48">
                        <select name="status"
                                onchange="this.form.submit()"
                                class="w-full py-2.5 px-3 rounded-xl border border-[#EBE5DF] bg-[#FAF8F5] text-sm text-[#1E1B18] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#FF6B00] focus:border-transparent font-quicksand">
                            <option value="">Semua Status</option>
                            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Aktif Saja</option>
                            <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Nonaktif Saja</option>
                        </select>
                    </div>

                    <div class="flex gap-2">
                        <button type="submit"
                                class="px-5 py-2.5 bg-[#1E1B18] hover:bg-[#322E2B] text-white font-montserrat font-bold text-sm rounded-xl transition shadow-sm">
                            Filter
                        </button>
                        @if(request()->hasAny(['search', 'status']))
                            <a href="{{ route('cabang.trainers.index') }}"
                               class="px-4 py-2.5 bg-[#F3EFEA] hover:bg-[#EBE5DF] text-[#1E1B18] font-montserrat font-bold text-sm rounded-xl transition flex items-center">
                                Reset
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <!-- Table / Card List -->
            <div class="bg-white rounded-2xl border border-[#EBE5DF] shadow-sm overflow-hidden">
                @if($trainers->isEmpty())
                    <div class="p-12 text-center">
                        <div class="w-16 h-16 mx-auto rounded-full bg-[#FFF7ED] text-[#FF6B00] flex items-center justify-center mb-4">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </div>
                        <h3 class="font-montserrat font-bold text-lg text-[#1E1B18]">Belum ada trainer di cabang ini</h3>
                        <p class="font-quicksand text-sm text-[#6E675F] mt-1 max-w-md mx-auto">
                            Daftarkan instruktur pengajar baru untuk mulai menugaskan kelas pelatihan dan pembuatan soal.
                        </p>
                    </div>
                @else
                    <!-- Desktop Table -->
                    <div class="hidden md:block overflow-x-auto">
                        <table class="w-full text-left text-sm">
                            <thead class="bg-[#FAF8F5] border-b border-[#EBE5DF] text-xs font-montserrat font-bold text-[#6E675F] uppercase tracking-wider">
                                <tr>
                                    <th class="py-4 px-6">Nama & Email Trainer</th>
                                    <th class="py-4 px-6">Nomor Telepon</th>
                                    <th class="py-4 px-6 text-center">Kelas Diampu</th>
                                    <th class="py-4 px-6 text-center">Status</th>
                                    <th class="py-4 px-6 text-center">Terdaftar</th>
                                    <th class="py-4 px-6 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-[#EBE5DF] font-quicksand">
                                @foreach($trainers as $trainer)
                                    <tr class="hover:bg-[#FAF8F5]/60 transition">
                                        <td class="py-4 px-6">
                                            <div class="flex items-center gap-3">
                                                <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-[#FF6B00] to-[#E11D48] text-white flex items-center justify-center font-montserrat font-bold text-sm shadow-xs">
                                                    {{ strtoupper(substr($trainer->name, 0, 1)) }}
                                                </div>
                                                <div>
                                                    <div class="font-montserrat font-bold text-sm text-[#1E1B18]">{{ $trainer->name }}</div>
                                                    <div class="text-xs text-[#6E675F]">{{ $trainer->email }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="py-4 px-6 text-[#1E1B18]">
                                            {{ $trainer->phone_number ?: '-' }}
                                        </td>
                                        <td class="py-4 px-6 text-center">
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold font-montserrat bg-[#FFF7ED] text-[#FF6B00]">
                                                {{ $trainer->assigned_classes_count }} Kelas
                                            </span>
                                        </td>
                                        <td class="py-4 px-6 text-center">
                                            @if($trainer->status === 'active')
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold font-montserrat bg-[#ECFDF5] text-[#10B981] border border-[#A7F3D0]">
                                                    ● Aktif
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold font-montserrat bg-[#FEF2F2] text-[#DC2626] border border-[#FECACA]">
                                                    ○ Nonaktif
                                                </span>
                                            @endif
                                        </td>
                                        <td class="py-4 px-6 text-center text-xs text-[#6E675F]">
                                            {{ $trainer->created_at->format('d M Y') }}
                                        </td>
                                        <td class="py-4 px-6 text-right">
                                            <div class="inline-flex items-center gap-1.5 justify-end">
                                                <!-- Toggle Status -->
                                                <form method="POST" action="{{ route('cabang.trainers.toggle-status', $trainer) }}" class="inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit"
                                                            title="{{ $trainer->status === 'active' ? 'Nonaktifkan Trainer' : 'Aktifkan Trainer' }}"
                                                            class="p-2 rounded-lg text-[#6E675F] hover:text-[#1E1B18] hover:bg-[#F3EFEA] transition">
                                                        @if($trainer->status === 'active')
                                                            <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                            </svg>
                                                        @else
                                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                            </svg>
                                                        @endif
                                                    </button>
                                                </form>

                                                <!-- Edit -->
                                                <a href="{{ route('cabang.trainers.edit', $trainer) }}"
                                                   title="Edit Data Trainer"
                                                   class="p-2 rounded-lg text-[#6E675F] hover:text-[#FF6B00] hover:bg-[#FFF7ED] transition">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                    </svg>
                                                </a>

                                                <!-- Delete -->
                                                <button type="button"
                                                        title="Hapus Trainer"
                                                        onclick="handleDeleteTrainer('{{ $trainer->id }}', '{{ addslashes($trainer->name) }}', {{ $trainer->assigned_classes_count }})"
                                                        class="p-2 rounded-lg text-[#6E675F] hover:text-[#DC2626] hover:bg-[#FEF2F2] transition">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>

                                                <form id="delete-form-{{ $trainer->id }}"
                                                      method="POST"
                                                      action="{{ route('cabang.trainers.destroy', $trainer) }}"
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
                        @foreach($trainers as $trainer)
                            <div class="p-4 space-y-3">
                                <div class="flex items-start justify-between gap-2">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-[#FF6B00] to-[#E11D48] text-white flex items-center justify-center font-montserrat font-bold text-sm">
                                            {{ strtoupper(substr($trainer->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <h3 class="font-montserrat font-bold text-sm text-[#1E1B18]">{{ $trainer->name }}</h3>
                                            <div class="text-xs text-[#6E675F]">{{ $trainer->email }}</div>
                                        </div>
                                    </div>
                                    @if($trainer->status === 'active')
                                        <span class="px-2 py-0.5 rounded-full text-xs font-bold font-montserrat bg-[#ECFDF5] text-[#10B981]">Aktif</span>
                                    @else
                                        <span class="px-2 py-0.5 rounded-full text-xs font-bold font-montserrat bg-[#FEF2F2] text-[#DC2626]">Nonaktif</span>
                                    @endif
                                </div>

                                <div class="flex items-center justify-between pt-2 border-t border-[#FAF8F5] text-xs">
                                    <div class="font-bold text-[#FF6B00]">
                                        {{ $trainer->assigned_classes_count }} Kelas Diampu
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('cabang.trainers.edit', $trainer) }}"
                                           class="px-2.5 py-1 text-xs font-montserrat font-bold rounded-lg bg-[#FFF7ED] text-[#FF6B00]">
                                            Edit
                                        </a>
                                        <button type="button"
                                                onclick="handleDeleteTrainer('{{ $trainer->id }}', '{{ addslashes($trainer->name) }}', {{ $trainer->assigned_classes_count }})"
                                                class="px-2.5 py-1 text-xs font-montserrat font-bold rounded-lg bg-[#FEF2F2] text-[#DC2626]">
                                            Hapus
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    @if($trainers->hasPages())
                        <div class="p-4 border-t border-[#EBE5DF] bg-[#FAF8F5]">
                            {{ $trainers->links() }}
                        </div>
                    @endif
                @endif
            </div>

        </div>
    </div>

    @push('scripts')
        <script>
            function handleDeleteTrainer(trainerId, trainerName, classesCount) {
                if (classesCount > 0) {
                    window.Swal.fire({
                        icon: 'warning',
                        title: 'Tidak Dapat Dihapus',
                        text: `Trainer ${trainerName} masih mengampu ${classesCount} kelas aktif. Silakan alihkan kelas ke trainer lain atau nonaktifkan akun sebagai gantinya.`,
                        confirmButtonText: 'Mengerti',
                        confirmButtonColor: '#FF6B00',
                        customClass: {
                            popup: 'font-quicksand rounded-3xl p-6 border border-[#EBE5DF]',
                            title: 'font-montserrat font-bold text-lg',
                            confirmButton: 'px-5 py-2.5 rounded-xl font-montserrat font-bold text-sm text-white'
                        },
                        buttonsStyling: false
                    });
                    return;
                }

                window.confirmAction({
                    title: 'Hapus Akun Trainer?',
                    text: `Anda yakin ingin menghapus akun ${trainerName}? Tindakan ini tidak dapat dibatalkan.`,
                    confirmText: 'Ya, Hapus Sekarang',
                    cancelText: 'Batal',
                    icon: 'warning'
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById(`delete-form-${trainerId}`).submit();
                    }
                });
            }
        </script>
    @endpush
</x-app-layout>
