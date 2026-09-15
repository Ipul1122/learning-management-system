<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
            <div>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold font-montserrat bg-[#FFF7ED] text-[#EA580C] border border-[#FED7AA] mb-1">
                    <span class="w-2 h-2 rounded-full bg-[#FF6B00]"></span>
                    Instruktur / Trainer
                </span>
                <h2 class="font-montserrat font-extrabold text-2xl text-[#1E1B18] leading-tight">
                    Dashboard Trainer
                </h2>
            </div>
            <div class="text-xs text-[#6E675F]">
                Cabang: <span class="font-bold text-[#1E1B18]">{{ $branch->name ?? 'Pusat' }}</span>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Hero Banner -->
            <div class="bg-gradient-to-r from-[#FF6B00] via-[#F97316] to-[#E11D48] rounded-3xl p-6 sm:p-8 text-white shadow-xl relative overflow-hidden">
                <div class="relative z-10 max-w-2xl">
                    <span class="text-xs font-bold tracking-widest uppercase text-white/90 font-montserrat">Pusat Pengajaran & Evaluasi</span>
                    <h1 class="text-2xl sm:text-3xl font-montserrat font-extrabold mt-1 mb-2 text-white">
                        Selamat Datang, {{ $user->name }}
                    </h1>
                    <p class="text-sm text-white/90 leading-relaxed font-quicksand">
                        Bimbing peserta dalam pemenuhan 20 Jam Pelajaran (900 menit), selenggarakan live session Zoom, evaluasi kuis, diskusikan materi di forum, dan verifikasi kelulusan peserta.
                    </p>
                </div>
            </div>

            <!-- Fitur Utama Trainer Sesuai PRD 3.1 - 3.6 -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                <div class="bg-white rounded-2xl p-5 border border-[#EBE5DF] shadow-sm flex flex-col justify-between">
                    <div>
                        <div class="w-10 h-10 rounded-xl bg-[#FEF2F2] text-[#DC2626] flex items-center justify-center mb-3">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <h4 class="font-montserrat font-bold text-base text-[#1E1B18]">3.5 Verifikasi Kelulusan 20 JP</h4>
                        <p class="text-xs text-[#6E675F] mt-1">Terima (*Approve*) atau tolak (*Reject*) pengajuan kelulusan peserta dengan catatan perbaikan.</p>
                    </div>
                    <span class="inline-block mt-4 text-xs font-bold text-[#DC2626] font-montserrat">Fase 5 • Verifikasi &rarr;</span>
                </div>

                <div class="bg-white rounded-2xl p-5 border border-[#EBE5DF] shadow-sm flex flex-col justify-between">
                    <div>
                        <div class="w-10 h-10 rounded-xl bg-[#FFF7ED] text-[#FF6B00] flex items-center justify-center mb-3">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                        </div>
                        <h4 class="font-montserrat font-bold text-base text-[#1E1B18]">3.6 Buat Link Zoom</h4>
                        <p class="text-xs text-[#6E675F] mt-1">Buat dan perbarui tautan pertemuan live Zoom untuk sesi tatap muka daring.</p>
                    </div>
                    <span class="inline-block mt-4 text-xs font-bold text-[#FF6B00] font-montserrat">Fase 3 • Integrasi Zoom &rarr;</span>
                </div>

                <div class="bg-white rounded-2xl p-5 border border-[#EBE5DF] shadow-sm flex flex-col justify-between">
                    <div>
                        <div class="w-10 h-10 rounded-xl bg-[#FFF7ED] text-[#EA580C] flex items-center justify-center mb-3">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                        </div>
                        <h4 class="font-montserrat font-bold text-base text-[#1E1B18]">3.1 & 3.2 Bank Soal & Kuis</h4>
                        <p class="text-xs text-[#6E675F] mt-1">Susun bank soal setara (tanpa tingkat kesulitan) dan terbitkan kuis evaluasi kelas.</p>
                    </div>
                    <span class="inline-block mt-4 text-xs font-bold text-[#EA580C] font-montserrat">Fase 3 • Kuis &rarr;</span>
                </div>

                <div class="bg-white rounded-2xl p-5 border border-[#EBE5DF] shadow-sm flex flex-col justify-between">
                    <div>
                        <div class="w-10 h-10 rounded-xl bg-[#FAF8F5] text-[#1E1B18] flex items-center justify-center mb-3">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                        </div>
                        <h4 class="font-montserrat font-bold text-base text-[#1E1B18]">3.3 Forum & Komunitas</h4>
                        <p class="text-xs text-[#6E675F] mt-1">Interaksi tanya jawab materi, sematkan pengumuman, dan moderasi thread peserta.</p>
                    </div>
                    <span class="inline-block mt-4 text-xs font-bold text-[#6E675F] font-montserrat">Fase 6 • Forum &rarr;</span>
                </div>

                <div class="bg-white rounded-2xl p-5 border border-[#EBE5DF] shadow-sm flex flex-col justify-between">
                    <div>
                        <div class="w-10 h-10 rounded-xl bg-[#FAF8F5] text-[#1E1B18] flex items-center justify-center mb-3">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                        </div>
                        <h4 class="font-montserrat font-bold text-base text-[#1E1B18]">3.4 Berita & Informasi</h4>
                        <p class="text-xs text-[#6E675F] mt-1">Publikasi pengumuman penting bagi peserta kelas.</p>
                    </div>
                    <span class="inline-block mt-4 text-xs font-bold text-[#6E675F] font-montserrat">Fase 6 • Berita &rarr;</span>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
