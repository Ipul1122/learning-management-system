<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
            <div>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold font-montserrat bg-[#ECFDF5] text-[#10B981] border border-[#A7F3D0] mb-1">
                    <span class="w-2 h-2 rounded-full bg-[#10B981]"></span>
                    Peserta Pelatihan
                </span>
                <h2 class="font-montserrat font-extrabold text-2xl text-[#1E1B18] leading-tight">
                    Dashboard Belajar Saya
                </h2>
            </div>
            <div class="text-xs text-[#6E675F]">
                Target Wajib: <span class="font-bold text-[#FF6B00]">20 Jam Pelajaran (900 Menit)</span>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- 20 JP Visual Progress Widget Sesuai desain.md 5.2 -->
            <div class="bg-gradient-to-br from-[#1E1B18] to-[#2D2824] rounded-3xl p-6 sm:p-8 text-white shadow-xl relative overflow-hidden">
                <div class="absolute -right-10 -bottom-10 w-52 h-52 bg-[#FF6B00]/20 rounded-full blur-3xl pointer-events-none"></div>

                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-4 relative z-10">
                    <div>
                        <span class="text-xs font-bold tracking-wider uppercase text-[#FB923C] font-montserrat">Syarat Kelulusan Pelatihan</span>
                        <h2 class="text-xl sm:text-2xl font-montserrat font-extrabold text-white">Akumulasi Jam Pelajaran (JP)</h2>
                        <p class="text-xs text-white/70 mt-1">1 JP = 45 Menit • Selesaikan minimal 20 JP untuk diverifikasi Trainer</p>
                    </div>
                    <div class="text-left sm:text-right">
                        <span class="text-3xl sm:text-4xl font-extrabold font-montserrat text-transparent bg-clip-text bg-gradient-to-r from-[#FB923C] to-[#F87171]">
                            0.0 <span class="text-lg text-white/70">/ 20.0 JP</span>
                        </span>
                        <p class="text-xs text-white/60">0 dari 900 Menit Tuntas (0%)</p>
                    </div>
                </div>

                <!-- Progress Track Bar -->
                <div class="w-full bg-white/10 h-3.5 rounded-full p-0.5 mb-3 relative z-10">
                    <div class="bg-gradient-to-r from-[#FF6B00] via-[#FB923C] to-[#E11D48] h-full rounded-full transition-all duration-700 shadow-sm" style="width: 5%"></div>
                </div>

                <!-- Status Box -->
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-2 text-xs text-white/80 pt-2 border-t border-white/10 relative z-10">
                    <span class="flex items-center gap-1.5 font-medium">
                        <span class="w-2 h-2 rounded-full bg-[#FB923C] animate-pulse"></span>
                        Silakan pilih kelas pelatihan terlebih dahulu untuk memulai pengumpulan JP
                    </span>
                    <span class="text-[#FED7AA] font-semibold">1 JP = 45 Menit</span>
                </div>
            </div>

            <!-- Fitur Utama Peserta Sesuai PRD 4.1 - 4.6 -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                <!-- 4.2 Pilih Kelas -->
                <div class="bg-white rounded-2xl p-5 border border-[#EBE5DF] shadow-sm flex flex-col justify-between">
                    <div>
                        <div class="w-10 h-10 rounded-xl bg-[#FFF7ED] text-[#FF6B00] flex items-center justify-center mb-3">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                        </div>
                        <h4 class="font-montserrat font-bold text-base text-[#1E1B18]">4.2 Memilih Kelas</h4>
                        <p class="text-xs text-[#6E675F] mt-1">Daftar kelas Offline (maks 40 orang), Online & Hybrid dengan kuota realtime.</p>
                    </div>
                    <span class="inline-block mt-4 text-xs font-bold text-[#FF6B00] font-montserrat">Fase 4 • Pilih Kelas &rarr;</span>
                </div>

                <!-- 4.6 Masuk Zoom -->
                <div class="bg-white rounded-2xl p-5 border border-[#EBE5DF] shadow-sm flex flex-col justify-between">
                    <div>
                        <div class="w-10 h-10 rounded-xl bg-[#FEF2F2] text-[#DC2626] flex items-center justify-center mb-3">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                        </div>
                        <h4 class="font-montserrat font-bold text-base text-[#1E1B18]">4.6 Masuk ke Zoom</h4>
                        <p class="text-xs text-[#6E675F] mt-1">1-Klik bergabung ke sesi tatap muka online dan presensi kehadiran terdata otomatis.</p>
                    </div>
                    <span class="inline-block mt-4 text-xs font-bold text-[#DC2626] font-montserrat">Fase 4 • 1-Click Zoom &rarr;</span>
                </div>

                <!-- 4.5 Cek Hasil & Sertifikat -->
                <div class="bg-white rounded-2xl p-5 border border-[#EBE5DF] shadow-sm flex flex-col justify-between">
                    <div>
                        <div class="w-10 h-10 rounded-xl bg-[#ECFDF5] text-[#10B981] flex items-center justify-center mb-3">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <h4 class="font-montserrat font-bold text-base text-[#1E1B18]">4.5 Cek Hasil & Sertifikat</h4>
                        <p class="text-xs text-[#6E675F] mt-1">Pantau status verifikasi kelulusan 20 JP oleh Trainer dan unduh sertifikat PDF.</p>
                    </div>
                    <span class="inline-block mt-4 text-xs font-bold text-[#10B981] font-montserrat">Fase 5 • Hasil Kelulusan &rarr;</span>
                </div>

                <!-- 4.4 Kunjungi Forum -->
                <div class="bg-white rounded-2xl p-5 border border-[#EBE5DF] shadow-sm flex flex-col justify-between">
                    <div>
                        <div class="w-10 h-10 rounded-xl bg-[#FAF8F5] text-[#1E1B18] flex items-center justify-center mb-3">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                        </div>
                        <h4 class="font-montserrat font-bold text-base text-[#1E1B18]">4.4 Mengunjungi Forum</h4>
                        <p class="text-xs text-[#6E675F] mt-1">Tanya jawab dengan instruktur dan bertukar wawasan dengan sesama peserta.</p>
                    </div>
                    <span class="inline-block mt-4 text-xs font-bold text-[#6E675F] font-montserrat">Fase 6 • Forum Komunitas &rarr;</span>
                </div>

                <!-- 4.3 Melihat Berita -->
                <div class="bg-white rounded-2xl p-5 border border-[#EBE5DF] shadow-sm flex flex-col justify-between">
                    <div>
                        <div class="w-10 h-10 rounded-xl bg-[#FAF8F5] text-[#1E1B18] flex items-center justify-center mb-3">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                        </div>
                        <h4 class="font-montserrat font-bold text-base text-[#1E1B18]">4.3 Melihat Berita</h4>
                        <p class="text-xs text-[#6E675F] mt-1">Pengumuman jadwal, artikel edukatif, dan informasi penting pelatihan.</p>
                    </div>
                    <span class="inline-block mt-4 text-xs font-bold text-[#6E675F] font-montserrat">Fase 6 • Informasi &rarr;</span>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
