<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Header Section -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="font-montserrat font-extrabold text-2xl sm:text-3xl text-[#1E1B18] tracking-tight">
                        Manajemen Admin Cabang
                    </h1>
                    <p class="font-quicksand text-sm text-[#6E675F] mt-1">
                        Kelola akun pengelola operasional kantor cabang, hak akses, dan penugasan wilayah.
                    </p>
                </div>
                <div>
                    <a href="{{ route('admin.branches.create') }}"
                       class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl font-montserrat font-bold text-sm text-white bg-gradient-to-r from-[#FF6B00] to-[#E11D48] hover:from-[#EA580C] hover:to-[#DC2626] shadow-md hover:shadow-lg transition-all duration-200 transform hover:-translate-y-0.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                        </svg>
                        <span>Tambah Cabang & Admin</span>
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
                        <div class="font-quicksand text-xs font-semibold text-[#6E675F] uppercase tracking-wider">Total Admin Cabang</div>
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
                        <div class="font-quicksand text-xs font-semibold text-[#6E675F] uppercase tracking-wider">Admin Aktif</div>
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
                        <div class="font-quicksand text-xs font-semibold text-[#6E675F] uppercase tracking-wider">Admin Nonaktif</div>
                        <div class="font-montserrat font-extrabold text-2xl text-[#1E1B18]">{{ $stats['inactive'] }}</div>
                    </div>
                </div>
            </div>

            <!-- Filter & Search Bar -->
            <div class="bg-white p-4 rounded-2xl border border-[#EBE5DF] shadow-sm">
                <form method="GET" action="{{ route('admin.admins.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-3">
                    <!-- Search query (Nama, Email, Cabang, Kota) -->
                    <div class="sm:col-span-2 lg:col-span-3 relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-[#6E675F]">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </span>
                        <input type="text"
                               name="search"
                               value="{{ request('search') }}"
                               placeholder="Cari nama, email, kota..."
                               class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-[#EBE5DF] bg-[#FAF8F5] text-sm text-[#1E1B18] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#FF6B00] focus:border-transparent font-quicksand transition">
                    </div>

                    <!-- Filter Cabang -->
                    <div class="lg:col-span-2">
                        <select name="branch_id"
                                onchange="this.form.submit()"
                                class="w-full py-2.5 px-3 rounded-xl border border-[#EBE5DF] bg-[#FAF8F5] text-sm text-[#1E1B18] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#FF6B00] focus:border-transparent font-quicksand">
                            <option value="">Semua Cabang</option>
                            @foreach($branches as $branch)
                                <option value="{{ $branch->id }}" {{ request('branch_id') == $branch->id ? 'selected' : '' }}>
                                    {{ $branch->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Filter Kota -->
                    <div class="lg:col-span-2">
                        <select name="city"
                                onchange="this.form.submit()"
                                class="w-full py-2.5 px-3 rounded-xl border border-[#EBE5DF] bg-[#FAF8F5] text-sm text-[#1E1B18] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#FF6B00] focus:border-transparent font-quicksand">
                            <option value="">Semua Kota</option>
                            @foreach($cities as $cityOption)
                                <option value="{{ $cityOption }}" {{ request('city') == $cityOption ? 'selected' : '' }}>
                                    {{ $cityOption }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Filter Nomor Telepon -->
                    <div class="lg:col-span-2 relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-[#6E675F]">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                        </span>
                        <input type="text"
                               name="phone"
                               value="{{ request('phone') }}"
                               placeholder="Filter no. telp..."
                               class="w-full pl-9 pr-3 py-2.5 rounded-xl border border-[#EBE5DF] bg-[#FAF8F5] text-sm text-[#1E1B18] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#FF6B00] focus:border-transparent font-quicksand transition">
                    </div>

                    <!-- Filter Status -->
                    <div class="lg:col-span-2">
                        <select name="status"
                                onchange="this.form.submit()"
                                class="w-full py-2.5 px-3 rounded-xl border border-[#EBE5DF] bg-[#FAF8F5] text-sm text-[#1E1B18] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#FF6B00] focus:border-transparent font-quicksand">
                            <option value="">Semua Status</option>
                            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Aktif Saja</option>
                            <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Nonaktif Saja</option>
                        </select>
                    </div>

                    <!-- Tombol Filter & Reset -->
                    <div class="lg:col-span-1 flex gap-1.5">
                        <button type="submit"
                                title="Terapkan Filter"
                                class="flex-1 py-2.5 bg-[#1E1B18] hover:bg-[#322E2B] text-white font-montserrat font-bold text-xs rounded-xl transition flex items-center justify-center shadow-xs">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                            </svg>
                        </button>
                        @if(request()->hasAny(['search', 'branch_id', 'city', 'phone', 'status']))
                            <a href="{{ route('admin.admins.index') }}"
                               title="Reset Semua Filter"
                               class="py-2.5 px-3 bg-[#F3EFEA] hover:bg-[#EBE5DF] text-[#1E1B18] font-montserrat font-bold text-xs rounded-xl transition flex items-center justify-center">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <!-- Table / Card List -->
            <div class="bg-white rounded-2xl border border-[#EBE5DF] shadow-sm overflow-hidden">
                @if($admins->isEmpty())
                    <div class="p-12 text-center">
                        <div class="w-16 h-16 mx-auto rounded-full bg-[#FFF7ED] text-[#FF6B00] flex items-center justify-center mb-4">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <h3 class="font-montserrat font-bold text-lg text-[#1E1B18]">Belum ada data Admin Cabang</h3>
                        <p class="font-quicksand text-sm text-[#6E675F] mt-1 max-w-md mx-auto">
                            Tidak ditemukan akun Admin Cabang sesuai kriteria pencarian. Buat akun baru atau setel ulang filter pencarian Anda.
                        </p>
                    </div>
                @else
                    <!-- Desktop Table -->
                    <div class="hidden md:block overflow-x-auto">
                        <table class="w-full text-left text-sm">
                            <thead class="bg-[#FAF8F5] border-b border-[#EBE5DF] text-xs font-montserrat font-bold text-[#6E675F] uppercase tracking-wider">
                                <tr>
                                    <th class="py-4 px-6">Nama & Email</th>
                                    <th class="py-4 px-6">Cabang Penugasan</th>
                                    <th class="py-4 px-6">Kota</th>
                                    <th class="py-4 px-6">Nomor Telepon</th>
                                    <th class="py-4 px-6 text-center">Status</th>
                                    <th class="py-4 px-6 text-center">Terdaftar</th>
                                    <th class="py-4 px-6 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-[#EBE5DF] font-quicksand">
                                @foreach($admins as $admin)
                                    <tr class="hover:bg-[#FAF8F5]/60 transition">
                                        <td class="py-4 px-6">
                                            <div class="flex items-center gap-3">
                                                <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-[#FF6B00] to-[#E11D48] text-white flex items-center justify-center font-montserrat font-bold text-sm shadow-xs">
                                                    {{ strtoupper(substr($admin->name, 0, 1)) }}
                                                </div>
                                                <div>
                                                    <div class="font-montserrat font-bold text-sm text-[#1E1B18]">{{ $admin->name }}</div>
                                                    <div class="text-xs text-[#6E675F]">{{ $admin->email }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="py-4 px-6">
                                            @if($admin->branch)
                                                <div class="space-y-0.5">
                                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold bg-[#FFF7ED] text-[#FF6B00] border border-[#FED7AA]">
                                                        {{ $admin->branch->name }}
                                                    </span>
                                                    <div class="text-[11px] font-mono text-[#6E675F]">
                                                        {{ $admin->branch->code }}
                                                    </div>
                                                </div>
                                            @else
                                                <span class="text-xs text-[#6E675F] italic">Belum Ditugaskan</span>
                                            @endif
                                        </td>
                                        <td class="py-4 px-6">
                                            @if($admin->branch?->city)
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-semibold bg-[#FAF8F5] text-[#1E1B18] border border-[#EBE5DF]">
                                                    <svg class="w-3.5 h-3.5 text-[#FF6B00] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    </svg>
                                                    <span>{{ $admin->branch->city }}</span>
                                                </span>
                                            @else
                                                <span class="text-xs text-[#6E675F] italic">-</span>
                                            @endif
                                        </td>
                                        <td class="py-4 px-6">
                                            <div class="space-y-1">
                                                @if($admin->phone_number)
                                                    @php
                                                        $cleanAdminPhone = preg_replace('/[^0-9+]/', '', $admin->phone_number);
                                                        $cleanAdminWa = preg_replace('/^0/', '62', preg_replace('/[^0-9]/', '', $admin->phone_number));
                                                    @endphp
                                                    <div class="flex items-center gap-1.5">
                                                        <a href="tel:{{ $cleanAdminPhone }}"
                                                           title="Hubungi telepon: {{ $admin->phone_number }}"
                                                           class="inline-flex items-center gap-1.5 text-xs font-semibold text-[#1E1B18] hover:text-[#FF6B00] transition group">
                                                            <span class="w-6 h-6 rounded-lg bg-[#ECFDF5] group-hover:bg-[#FFF7ED] text-[#10B981] group-hover:text-[#FF6B00] flex items-center justify-center transition shrink-0">
                                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                                                </svg>
                                                            </span>
                                                            <span class="group-hover:underline">{{ $admin->phone_number }}</span>
                                                        </a>

                                                        @if(str_starts_with($cleanAdminPhone, '08') || str_starts_with($cleanAdminPhone, '+628') || str_starts_with($cleanAdminPhone, '628'))
                                                            <a href="https://wa.me/{{ $cleanAdminWa }}"
                                                               target="_blank"
                                                               rel="noopener noreferrer"
                                                               title="Hubungi via WhatsApp ({{ $admin->phone_number }})"
                                                               class="w-6 h-6 rounded-lg bg-emerald-50 hover:bg-emerald-100 text-emerald-600 flex items-center justify-center transition shrink-0">
                                                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24">
                                                                    <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.711 2.598 2.664-.699c.971.53 1.968.815 2.796.815 3.182 0 5.768-2.586 5.768-5.766 0-3.18-2.586-5.766-5.768-5.766zm9.969 5.766c0 5.514-4.486 10-10 10-1.748 0-3.385-.45-4.819-1.238l-7.181 1.882 1.916-6.997c-.88-1.488-1.396-3.23-1.396-5.087 0-5.514 4.486-10 10-10 5.514 0 10 4.486 10 10z"/>
                                                                </svg>
                                                            </a>
                                                        @endif
                                                    </div>
                                                @else
                                                    <span class="text-xs text-[#6E675F] italic">-</span>
                                                @endif

                                                @if($admin->branch?->phone && $admin->branch->phone !== $admin->phone_number)
                                                    @php
                                                        $cleanBranchPhone = preg_replace('/[^0-9+]/', '', $admin->branch->phone);
                                                    @endphp
                                                    <div class="text-[11px] text-[#6E675F] flex items-center gap-1">
                                                        <span class="font-medium text-[10px] uppercase tracking-wider text-[#9CA3AF]">Kantor:</span>
                                                        <a href="tel:{{ $cleanBranchPhone }}"
                                                           title="Hubungi telepon kantor cabang: {{ $admin->branch->phone }}"
                                                           class="font-mono text-[#6E675F] hover:text-[#FF6B00] hover:underline transition">
                                                            {{ $admin->branch->phone }}
                                                        </a>
                                                    </div>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="py-4 px-6 text-center">
                                            @if($admin->status === 'active')
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold font-montserrat bg-[#ECFDF5] text-[#10B981] border border-[#A7F3D0]">
                                                    Aktif
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold font-montserrat bg-[#FEF2F2] text-[#DC2626] border border-[#FECACA]">
                                                    Nonaktif
                                                </span>
                                            @endif
                                        </td>
                                        <td class="py-4 px-6 text-center text-xs text-[#6E675F]">
                                            {{ $admin->created_at->format('d M Y') }}
                                        </td>
                                        <td class="py-4 px-6 text-right">
                                            <div class="inline-flex items-center gap-1.5 justify-end">
                                                <!-- Toggle Status -->
                                                <form method="POST" action="{{ route('admin.admins.toggle-status', $admin) }}" class="inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit"
                                                            title="{{ $admin->status === 'active' ? 'Nonaktifkan Akun' : 'Aktifkan Akun' }}"
                                                            class="p-2 rounded-lg text-[#6E675F] hover:text-[#1E1B18] hover:bg-[#F3EFEA] transition">
                                                        @if($admin->status === 'active')
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
                                                <a href="{{ route('admin.admins.edit', $admin) }}"
                                                   title="Edit Admin Cabang"
                                                   class="p-2 rounded-lg text-[#6E675F] hover:text-[#FF6B00] hover:bg-[#FFF7ED] transition">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                    </svg>
                                                </a>

                                                <!-- Delete -->
                                                <button type="button"
                                                        title="Hapus Akun"
                                                        onclick="handleDeleteAdmin('{{ $admin->id }}', '{{ addslashes($admin->name) }}')"
                                                        class="p-2 rounded-lg text-[#6E675F] hover:text-[#DC2626] hover:bg-[#FEF2F2] transition">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>

                                                <form id="delete-form-{{ $admin->id }}"
                                                      method="POST"
                                                      action="{{ route('admin.admins.destroy', $admin) }}"
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
                        @foreach($admins as $admin)
                            <div class="p-4 space-y-3">
                                <div class="flex items-start justify-between gap-2">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-[#FF6B00] to-[#E11D48] text-white flex items-center justify-center font-montserrat font-bold text-sm">
                                            {{ strtoupper(substr($admin->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <h3 class="font-montserrat font-bold text-sm text-[#1E1B18]">{{ $admin->name }}</h3>
                                            <div class="text-xs text-[#6E675F]">{{ $admin->email }}</div>
                                        </div>
                                    </div>
                                    @if($admin->status === 'active')
                                        <span class="px-2 py-0.5 rounded-full text-xs font-bold font-montserrat bg-[#ECFDF5] text-[#10B981]">Aktif</span>
                                    @else
                                        <span class="px-2 py-0.5 rounded-full text-xs font-bold font-montserrat bg-[#FEF2F2] text-[#DC2626]">Nonaktif</span>
                                    @endif
                                </div>

                                <!-- Fields: Cabang, Kota, Telepon -->
                                <div class="grid grid-cols-2 gap-2 pt-2 border-t border-[#FAF8F5] text-xs font-quicksand bg-[#FAF8F5]/60 p-2.5 rounded-xl border border-[#EBE5DF]/60">
                                    <div>
                                        <span class="text-[#6E675F] text-[10px] uppercase font-bold tracking-wider block">Cabang & Kota</span>
                                        <span class="font-semibold text-[#1E1B18] truncate block">{{ $admin->branch?->name ?? 'Tanpa Cabang' }}</span>
                                        @if($admin->branch?->city)
                                            <span class="text-[#FF6B00] text-[11px] font-medium flex items-center gap-0.5 mt-0.5">
                                                📍 {{ $admin->branch->city }}
                                            </span>
                                        @endif
                                    </div>
                                    <div>
                                        <span class="text-[#6E675F] text-[10px] uppercase font-bold tracking-wider block">Nomor Telepon</span>
                                        @if($admin->phone_number)
                                            @php
                                                $cleanAdminPhone = preg_replace('/[^0-9+]/', '', $admin->phone_number);
                                                $cleanAdminWa = preg_replace('/^0/', '62', preg_replace('/[^0-9]/', '', $admin->phone_number));
                                            @endphp
                                            <div class="flex items-center gap-1.5 mt-0.5">
                                                <a href="tel:{{ $cleanAdminPhone }}"
                                                   title="Hubungi telepon: {{ $admin->phone_number }}"
                                                   class="font-semibold text-[#1E1B18] hover:text-[#FF6B00] hover:underline flex items-center gap-1">
                                                    <svg class="w-3 h-3 text-[#10B981] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                                    </svg>
                                                    <span>{{ $admin->phone_number }}</span>
                                                </a>
                                                @if(str_starts_with($cleanAdminPhone, '08') || str_starts_with($cleanAdminPhone, '+628') || str_starts_with($cleanAdminPhone, '628'))
                                                    <a href="https://wa.me/{{ $cleanAdminWa }}"
                                                       target="_blank"
                                                       rel="noopener noreferrer"
                                                       title="Chat via WhatsApp"
                                                       class="p-0.5 rounded text-emerald-600 hover:text-emerald-700 hover:bg-emerald-50 transition">
                                                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24">
                                                            <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.711 2.598 2.664-.699c.971.53 1.968.815 2.796.815 3.182 0 5.768-2.586 5.768-5.766 0-3.18-2.586-5.766-5.768-5.766zm9.969 5.766c0 5.514-4.486 10-10 10-1.748 0-3.385-.45-4.819-1.238l-7.181 1.882 1.916-6.997c-.88-1.488-1.396-3.23-1.396-5.087 0-5.514 4.486-10 10-10 5.514 0 10 4.486 10 10z"/>
                                                        </svg>
                                                    </a>
                                                @endif
                                            </div>
                                        @else
                                            <span class="text-xs text-[#6E675F] italic block mt-0.5">-</span>
                                        @endif
                                        @if($admin->branch?->phone && $admin->branch->phone !== $admin->phone_number)
                                            @php
                                                $cleanBranchPhone = preg_replace('/[^0-9+]/', '', $admin->branch->phone);
                                            @endphp
                                            <a href="tel:{{ $cleanBranchPhone }}"
                                               title="Hubungi telepon kantor cabang"
                                               class="text-[#6E675F] hover:text-[#FF6B00] text-[10px] block mt-1 truncate hover:underline">
                                                Kantor: {{ $admin->branch->phone }}
                                            </a>
                                        @endif
                                    </div>
                                </div>

                                <div class="flex items-center justify-between pt-1 text-xs">
                                    <span class="text-[11px] text-[#6E675F]">Terdaftar: {{ $admin->created_at->format('d M Y') }}</span>
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('admin.admins.edit', $admin) }}"
                                           class="px-2.5 py-1 text-xs font-montserrat font-bold rounded-lg bg-[#FFF7ED] text-[#FF6B00] border border-[#FED7AA]">
                                            Edit
                                        </a>
                                        <button type="button"
                                                onclick="handleDeleteAdmin('{{ $admin->id }}', '{{ addslashes($admin->name) }}')"
                                                class="px-2.5 py-1 text-xs font-montserrat font-bold rounded-lg bg-[#FEF2F2] text-[#DC2626] border border-[#FECACA]">
                                            Hapus
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    @if($admins->hasPages())
                        <div class="p-4 border-t border-[#EBE5DF] bg-[#FAF8F5]">
                            {{ $admins->links() }}
                        </div>
                    @endif
                @endif
            </div>

        </div>
    </div>

    @push('scripts')
        <script>
            function handleDeleteAdmin(adminId, adminName) {
                window.confirmAction({
                    title: 'Hapus Akun Admin Cabang?',
                    text: `Anda yakin ingin menghapus akun ${adminName}? Tindakan ini akan mencabut seluruh akses pengelolaan cabang bersangkutan.`,
                    confirmText: 'Ya, Hapus Sekarang',
                    cancelText: 'Batal',
                    icon: 'warning'
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById(`delete-form-${adminId}`).submit();
                    }
                });
            }
        </script>
    @endpush
</x-app-layout>
