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
                Kode Cabang: <span class="font-bold text-[#EA580C]">{{ $branch->code ?? '-' }}</span> | {{ $branch->city ?? '-' }}
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Hero Banner -->
            <div class="bg-gradient-to-r from-[#2D1B18] via-[#3D2520] to-[#1E1B18] rounded-3xl p-6 sm:p-8 text-white shadow-xl relative overflow-hidden">
                <div class="absolute -right-8 -bottom-8 w-44 h-44 bg-[#DC2626]/25 rounded-full blur-3xl pointer-events-none"></div>
                <div class="relative z-10 max-w-2xl">
                    <span class="text-xs font-bold tracking-widest uppercase text-[#F87171] font-montserrat">Operasional Pelatihan Wilayah</span>
                    <h1 class="text-2xl sm:text-3xl font-montserrat font-extrabold mt-1 mb-2 text-white">
                        Halo, {{ $user->name }}
                    </h1>
                    <p class="text-sm text-white/80 leading-relaxed font-quicksand">
                        Kelola instruktur cabang, publikasi kelas offline (maksimal 40 orang), online & hybrid, tautan Zoom sesi live, bank soal, dan pantau log aktivitas cabang Anda.
                    </p>
                </div>
            </div>

            <!-- Stats Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 sm:gap-6">
                <!-- Trainer Cabang -->
                <div class="bg-white rounded-2xl p-5 border border-[#EBE5DF] shadow-sm">
                    <div class="flex items-center justify-between text-[#FF6B00] mb-3">
                        <span class="text-xs font-bold font-montserrat uppercase tracking-wider text-[#6E675F]">Trainer Terdaftar</span>
                        <div class="w-10 h-10 rounded-xl bg-[#FFF7ED] flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        </div>
                    </div>
                    <div class="text-3xl font-montserrat font-extrabold text-[#1E1B18]">{{ $trainersCount }}</div>
                    <p class="text-xs text-[#6E675F] mt-1">Instruktur di {{ $branch->name ?? 'cabang ini' }}</p>
                </div>

                <!-- Peserta Cabang -->
                <div class="bg-white rounded-2xl p-5 border border-[#EBE5DF] shadow-sm">
                    <div class="flex items-center justify-between text-[#10B981] mb-3">
                        <span class="text-xs font-bold font-montserrat uppercase tracking-wider text-[#6E675F]">Peserta Aktif</span>
                        <div class="w-10 h-10 rounded-xl bg-[#ECFDF5] flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                        </div>
                    </div>
                    <div class="text-3xl font-montserrat font-extrabold text-[#1E1B18]">{{ $studentsCount }}</div>
                    <p class="text-xs text-[#6E675F] mt-1">Mengikuti pelatihan cabang</p>
                </div>

                <!-- Aturan Kapasitas -->
                <div class="bg-white rounded-2xl p-5 border border-[#EBE5DF] shadow-sm">
                    <div class="flex items-center justify-between text-[#DC2626] mb-3">
                        <span class="text-xs font-bold font-montserrat uppercase tracking-wider text-[#6E675F]">Standar Kapasitas</span>
                        <div class="w-10 h-10 rounded-xl bg-[#FEF2F2] flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        </div>
                    </div>
                    <div class="text-xl font-montserrat font-extrabold text-[#1E1B18]">Maks 40 Offline</div>
                    <p class="text-xs text-[#6E675F] mt-1">Online & Hybrid: Ratusan</p>
                </div>
            </div>

            <!-- Modul Admin Cabang Sesuai PRD 2.1 - 2.6 -->
            <div class="bg-white rounded-3xl p-6 sm:p-8 border border-[#EBE5DF] shadow-sm">
                <h3 class="text-lg font-montserrat font-bold text-[#1E1B18] mb-4">Fitur Operasional Cabang</h3>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="p-4 rounded-2xl bg-[#FFF7ED] border border-[#FED7AA]">
                        <h4 class="font-montserrat font-bold text-sm text-[#1E1B18]">2.1 CRUD Trainer</h4>
                        <p class="text-xs text-[#6E675F] mt-1">Registrasi dan penugasan instruktur pengajar cabang.</p>
                    </div>
                    <div class="p-4 rounded-2xl bg-[#FFF7ED] border border-[#FED7AA]">
                        <h4 class="font-montserrat font-bold text-sm text-[#1E1B18]">2.6 CRUD Kelas</h4>
                        <p class="text-xs text-[#6E675F] mt-1">Buka kelas Offline (maks 40), Online & Hybrid.</p>
                    </div>
                    <div class="p-4 rounded-2xl bg-[#FFF7ED] border border-[#FED7AA]">
                        <h4 class="font-montserrat font-bold text-sm text-[#1E1B18]">2.3 Input Link Zoom</h4>
                        <p class="text-xs text-[#6E675F] mt-1">Tautkan URL Zoom meeting pada jadwal sesi kelas.</p>
                    </div>
                    <div class="p-4 rounded-2xl bg-[#FFF7ED] border border-[#FED7AA]">
                        <h4 class="font-montserrat font-bold text-sm text-[#1E1B18]">2.2 CRUD Bank Soal</h4>
                        <p class="text-xs text-[#6E675F] mt-1">Kelola bank soal setara untuk evaluasi kuis.</p>
                    </div>
                    <div class="p-4 rounded-2xl bg-[#FFF7ED] border border-[#FED7AA]">
                        <h4 class="font-montserrat font-bold text-sm text-[#1E1B18]">2.5 CRUD Kuis</h4>
                        <p class="text-xs text-[#6E675F] mt-1">Paket kuis evaluasi pemenuhan kelulusan.</p>
                    </div>
                    <div class="p-4 rounded-2xl bg-[#FEF2F2] border border-[#FECACA]">
                        <h4 class="font-montserrat font-bold text-sm text-[#1E1B18]">2.4 Log Aktivitas Cabang</h4>
                        <p class="text-xs text-[#6E675F] mt-1">Audit trail operasional khusus cabang ini.</p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
