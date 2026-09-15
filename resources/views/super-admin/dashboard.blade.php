<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
            <div>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold font-montserrat bg-[#FFF7ED] text-[#EA580C] border border-[#FED7AA] mb-1">
                    <span class="w-2 h-2 rounded-full bg-[#EA580C]"></span>
                    Super Administrator
                </span>
                <h2 class="font-montserrat font-extrabold text-2xl text-[#1E1B18] leading-tight">
                    Dashboard Super Admin
                </h2>
            </div>
            <div class="text-xs text-[#6E675F]">
                Audit & Manajemen Terpusat Multi-Cabang
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Hero Banner -->
            <div class="bg-gradient-to-r from-[#1E1B18] via-[#2D2824] to-[#1E1B18] rounded-3xl p-6 sm:p-8 text-white shadow-xl relative overflow-hidden">
                <div class="absolute -right-8 -bottom-8 w-44 h-44 bg-[#FF6B00]/25 rounded-full blur-3xl pointer-events-none"></div>
                <div class="relative z-10 max-w-2xl">
                    <span class="text-xs font-bold tracking-widest uppercase text-[#FB923C] font-montserrat">Pusat Kendali Sistem</span>
                    <h1 class="text-2xl sm:text-3xl font-montserrat font-extrabold mt-1 mb-2 text-white">
                        Selamat Datang, {{ auth()->user()->name }}
                    </h1>
                    <p class="text-sm text-white/80 leading-relaxed font-quicksand">
                        Kelola seluruh kantor cabang, akun admin cabang, pantau audit aktivitas real-time, dan pastikan standar pelatihan 20 JP berjalan seragam di seluruh wilayah.
                    </p>
                </div>
            </div>

            <!-- Stats Grid -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
                <!-- Total Cabang -->
                <a href="{{ route('admin.branches.index') }}" class="bg-white rounded-2xl p-5 border border-[#EBE5DF] shadow-sm hover:shadow-md hover:border-[#FF6B00]/40 transition-all block">
                    <div class="flex items-center justify-between text-[#EA580C] mb-3">
                        <span class="text-xs font-bold font-montserrat uppercase tracking-wider text-[#6E675F]">Total Cabang</span>
                        <div class="w-10 h-10 rounded-xl bg-[#FFF7ED] flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        </div>
                    </div>
                    <div class="text-3xl font-montserrat font-extrabold text-[#1E1B18]">{{ $branchesCount }}</div>
                    <p class="text-xs text-[#EA580C] font-semibold mt-1">Kelola Cabang &rarr;</p>
                </a>

                <!-- Admin Cabang -->
                <a href="{{ route('admin.admins.index') }}" class="bg-white rounded-2xl p-5 border border-[#EBE5DF] shadow-sm hover:shadow-md hover:border-[#DC2626]/40 transition-all block">
                    <div class="flex items-center justify-between text-[#DC2626] mb-3">
                        <span class="text-xs font-bold font-montserrat uppercase tracking-wider text-[#6E675F]">Admin Cabang</span>
                        <div class="w-10 h-10 rounded-xl bg-[#FEF2F2] flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                        </div>
                    </div>
                    <div class="text-3xl font-montserrat font-extrabold text-[#1E1B18]">{{ $adminsCount }}</div>
                    <p class="text-xs text-[#DC2626] font-semibold mt-1">Kelola Akun Admin &rarr;</p>
                </a>

                <!-- Total Trainer -->
                <div class="bg-white rounded-2xl p-5 border border-[#EBE5DF] shadow-sm">
                    <div class="flex items-center justify-between text-[#FF6B00] mb-3">
                        <span class="text-xs font-bold font-montserrat uppercase tracking-wider text-[#6E675F]">Instruktur / Trainer</span>
                        <div class="w-10 h-10 rounded-xl bg-[#FFF7ED] flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        </div>
                    </div>
                    <div class="text-3xl font-montserrat font-extrabold text-[#1E1B18]">{{ $trainersCount }}</div>
                    <p class="text-xs text-[#6E675F] mt-1">Tenaga pengajar cabang</p>
                </div>

                <!-- Total Peserta -->
                <div class="bg-white rounded-2xl p-5 border border-[#EBE5DF] shadow-sm">
                    <div class="flex items-center justify-between text-[#10B981] mb-3">
                        <span class="text-xs font-bold font-montserrat uppercase tracking-wider text-[#6E675F]">Total Peserta</span>
                        <div class="w-10 h-10 rounded-xl bg-[#ECFDF5] flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        </div>
                    </div>
                    <div class="text-3xl font-montserrat font-extrabold text-[#1E1B18]">{{ $studentsCount }}</div>
                    <p class="text-xs text-[#6E675F] mt-1">Trainee terdaftar</p>
                </div>
            </div>

            <!-- Fitur Utama Super Admin Sesuai PRD -->
            <div class="bg-white rounded-3xl p-6 sm:p-8 border border-[#EBE5DF] shadow-sm">
                <h3 class="text-lg font-montserrat font-bold text-[#1E1B18] mb-4">Modul Operasional Super Admin</h3>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                    <!-- 1.1 CRUD Admin Cabang -->
                    <a href="{{ route('admin.admins.index') }}" class="p-5 rounded-2xl bg-[#FFF7ED] border border-[#FED7AA] hover:shadow-md transition-all flex flex-col justify-between block group">
                        <div>
                            <div class="w-10 h-10 rounded-xl bg-[#FF6B00] text-white flex items-center justify-center mb-3 group-hover:scale-105 transition-transform">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                            </div>
                            <h4 class="font-montserrat font-bold text-base text-[#1E1B18]">1.1 CRUD Akun Admin</h4>
                            <p class="text-xs text-[#6E675F] mt-1">Kelola akun Admin Cabang, penugasan wilayah, status aktif, dan reset akses sandi.</p>
                        </div>
                        <span class="inline-block mt-4 text-xs font-bold text-[#EA580C] font-montserrat">Buka Akun Admin &rarr;</span>
                    </a>

                    <!-- 1.2 Log Aktivitas Admin -->
                    <a href="{{ route('admin.logs.index') }}" class="p-5 rounded-2xl bg-[#FEF2F2] border border-[#FECACA] hover:shadow-md transition-all flex flex-col justify-between block group">
                        <div>
                            <div class="w-10 h-10 rounded-xl bg-[#DC2626] text-white flex items-center justify-center mb-3 group-hover:scale-105 transition-transform">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            </div>
                            <h4 class="font-montserrat font-bold text-base text-[#1E1B18]">1.2 Log Aktivitas Admin</h4>
                            <p class="text-xs text-[#6E675F] mt-1">Audit trail seluruh aksi penting admin: mutasi kelas, link zoom, kelulusan, dan perubahan data.</p>
                        </div>
                        <span class="inline-block mt-4 text-xs font-bold text-[#DC2626] font-montserrat">Lihat Audit Trail &rarr;</span>
                    </a>

                    <!-- Master Cabang -->
                    <a href="{{ route('admin.branches.index') }}" class="p-5 rounded-2xl bg-[#FAF8F5] border border-[#EBE5DF] hover:shadow-md transition-all flex flex-col justify-between block group">
                        <div>
                            <div class="w-10 h-10 rounded-xl bg-[#1E1B18] text-white flex items-center justify-center mb-3 group-hover:scale-105 transition-transform">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                            </div>
                            <h4 class="font-montserrat font-bold text-base text-[#1E1B18]">Master Data Cabang</h4>
                            <p class="text-xs text-[#6E675F] mt-1">Tambah, perbarui profil, dan kelola kantor cabang fisik di seluruh wilayah.</p>
                        </div>
                        <span class="inline-block mt-4 text-xs font-bold text-[#1E1B18] font-montserrat">Kelola Cabang &rarr;</span>
                    </a>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
