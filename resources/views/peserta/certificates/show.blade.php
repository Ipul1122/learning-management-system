<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div>
                <a href="{{ route('peserta.certificates.index') }}" class="inline-flex items-center gap-1.5 text-xs text-[#FF6B00] hover:text-[#EA580C] font-montserrat mb-1 font-bold">
                    &larr; Kembali ke Daftar Sertifikat
                </a>
                <h2 class="font-montserrat font-extrabold text-2xl sm:text-3xl text-[#1E1B18] tracking-tight leading-tight">
                    Lembar Verifikasi Kelulusan
                </h2>
            </div>
            <div class="flex items-center gap-2">
                @if($submission->isApproved())
                    <a href="{{ route('peserta.certificates.download', $submission) }}" class="px-5 py-2.5 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-500 hover:to-indigo-500 text-white rounded-xl text-xs font-bold font-montserrat flex items-center gap-2 shadow-md transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                        <span>Unduh E-Sertifikat PDF</span>
                    </a>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-8 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
        @php
            $enrollment = $submission->enrollment;
            $class = $enrollment->trainingClass;
            $trainer = $submission->trainer ?? $class->trainer;
        @endphp

        <!-- Status Card -->
        @if($submission->isApproved())
            <div class="bg-white rounded-2xl p-6 sm:p-8 border border-emerald-200 shadow-sm relative overflow-hidden">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 relative z-10">
                    <div class="space-y-2">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold font-montserrat bg-emerald-50 text-emerald-700 border border-emerald-200">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            SELAMAT! KELULUSAN ANDA TELAH TERVERIFIKASI
                        </span>
                        <h3 class="text-xl sm:text-2xl font-montserrat font-extrabold text-[#1E1B18]">E-Sertifikat Resmi Telah Diterbitkan</h3>
                        <p class="text-xs text-[#6E675F] max-w-xl font-quicksand leading-relaxed">
                            Anda telah berhasil menuntaskan seluruh rangkaian kurikulum, akumulasi 20 Jam Pelajaran (JP), dan evaluasi kuis dengan persetujuan Trainer resmi.
                        </p>
                        <div class="pt-2 flex flex-wrap items-center gap-4 text-xs font-quicksand text-[#6E675F]">
                            <span>Nomor Sertifikat: <strong class="text-[#1E1B18] font-mono">{{ $submission->certificate_number }}</strong></span>
                            <span>•</span>
                            <span>Tanggal Terbit: <strong class="text-[#1E1B18]">{{ $submission->reviewed_at ? \Carbon\Carbon::parse($submission->reviewed_at)->translatedFormat('d F Y') : '-' }}</strong></span>
                        </div>
                    </div>
                    <div class="flex flex-col sm:flex-row items-center gap-3">
                        <a href="{{ route('peserta.certificates.preview', $submission) }}" target="_blank" class="w-full sm:w-auto px-4 py-2.5 bg-[#FAF8F5] hover:bg-white text-[#1E1B18] rounded-xl text-xs font-bold font-montserrat flex items-center justify-center gap-2 border border-[#EBE5DF] transition shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            <span>Pratinjau Layar</span>
                        </a>
                        <a href="{{ route('peserta.certificates.download', $submission) }}" class="w-full sm:w-auto px-5 py-2.5 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-500 hover:to-indigo-500 text-white rounded-xl text-xs font-bold font-montserrat flex items-center justify-center gap-2 shadow-md transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                            <span>Unduh Sertifikat PDF</span>
                        </a>
                    </div>
                </div>
            </div>
        @elseif($submission->isRejected())
            <div class="bg-white rounded-2xl p-6 sm:p-8 border border-rose-200 shadow-sm space-y-4">
                <div class="flex items-start gap-4">
                    <div class="w-10 h-10 rounded-xl bg-rose-50 text-rose-600 border border-rose-200 flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    </div>
                    <div class="space-y-1">
                        <span class="inline-flex items-center gap-1 px-3 py-0.5 rounded-full text-[11px] font-bold font-montserrat bg-rose-50 text-rose-700 border border-rose-200">
                            PERLU PERBAIKAN / REMEDIAL
                        </span>
                        <h3 class="text-lg font-montserrat font-extrabold text-[#1E1B18]">Trainer Memberikan Masukan Evaluasi</h3>
                        <p class="text-xs text-[#6E675F] font-quicksand">
                            Pengajuan kelulusan Anda belum disetujui. Silakan periksa catatan instruktur di bawah ini dan lengkapi materi atau remedial kuis yang diinstruksikan.
                        </p>
                    </div>
                </div>

                <div class="p-4 rounded-xl bg-[#FAF8F5] border border-rose-200 space-y-2">
                    <span class="text-xs font-bold text-rose-700 font-montserrat uppercase tracking-wider block">Catatan & Masukan Trainer:</span>
                    <p class="text-sm text-[#1E1B18] leading-relaxed italic bg-white p-3 rounded-lg border border-[#EBE5DF]">
                        "{{ $submission->trainer_feedback }}"
                    </p>
                    <div class="flex items-center justify-between text-xs text-[#6E675F] pt-1 font-quicksand">
                        <span>Oleh: <strong class="text-[#1E1B18] font-montserrat">{{ $trainer->name }}</strong></span>
                        <span>{{ $submission->reviewed_at ? \Carbon\Carbon::parse($submission->reviewed_at)->diffForHumans() : '' }}</span>
                    </div>
                </div>

                <div class="pt-2 flex items-center justify-between">
                    <a href="{{ route('peserta.study.show', $class) }}" class="px-4 py-2.5 bg-[#FAF8F5] hover:bg-white text-[#1E1B18] rounded-xl text-xs font-bold font-montserrat border border-[#EBE5DF] transition shadow-sm">
                        Buka Ruang Belajar & Silabus &rarr;
                    </a>
                    <form method="POST" action="{{ route('peserta.certificates.requestReview', $class) }}">
                        @csrf
                        <button type="submit" class="px-5 py-2.5 bg-gradient-to-r from-[#FF6B00] to-[#E11D48] hover:from-[#EA580C] hover:to-[#DC2626] text-white rounded-xl text-xs font-bold font-montserrat flex items-center gap-2 shadow-md transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                            <span>Ajukan Ulang Verifikasi</span>
                        </button>
                    </form>
                </div>
            </div>
        @else
            <div class="bg-white rounded-2xl p-6 sm:p-8 border border-amber-200 shadow-sm space-y-2">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold font-montserrat bg-amber-50 text-amber-700 border border-amber-200">
                    <svg class="w-3.5 h-3.5 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    MENUNGGU REVIEW TRAINER
                </span>
                <h3 class="text-lg sm:text-xl font-montserrat font-extrabold text-[#1E1B18]">Pengajuan Sedang Dalam Antrean Audit</h3>
                <p class="text-xs text-[#6E675F] font-quicksand leading-relaxed">
                    Trainer pengampu (<strong>{{ $trainer->name }}</strong>) sedang memeriksa akumulasi 20 JP, rekaman kehadiran sesi Zoom, dan hasil nilai kuis Anda. Hasil verifikasi akan diumumkan di halaman ini.
                </p>
            </div>
        @endif

        <!-- 3-Column Metrics Audit Card -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- 1. Jam Pelajaran (JP) -->
            <div class="bg-white border border-[#EBE5DF] rounded-2xl p-5 space-y-3 shadow-sm">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-[#6E675F] uppercase tracking-wider font-montserrat">Jam Pelajaran (JP)</span>
                    <span class="p-1.5 rounded-lg bg-orange-50 text-[#FF6B00] border border-orange-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </span>
                </div>
                <div class="flex items-baseline gap-2">
                    <span class="text-3xl font-extrabold font-montserrat text-[#1E1B18]">{{ $submission->total_jp_earned }}</span>
                    <span class="text-xs text-[#6E675F] font-mono">/ Min. 20.0 JP</span>
                </div>
                <div class="w-full bg-[#EBE5DF] h-2 rounded-full overflow-hidden">
                    <div class="h-full bg-emerald-500 rounded-full" style="width: 100%"></div>
                </div>
                <p class="text-[11px] text-[#6E675F] font-mono">
                    Total Waktu: {{ $enrollment->total_minutes_accumulated }} Menit (Memenuhi Syarat 900 Menit)
                </p>
            </div>

            <!-- 2. Rerata Nilai Kuis -->
            <div class="bg-white border border-[#EBE5DF] rounded-2xl p-5 space-y-3 shadow-sm">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-[#6E675F] uppercase tracking-wider font-montserrat">Nilai Kuis Evaluasi</span>
                    <span class="p-1.5 rounded-lg bg-blue-50 text-blue-600 border border-blue-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </span>
                </div>
                <div class="flex items-baseline gap-2">
                    <span class="text-3xl font-extrabold font-montserrat {{ ($submission->avg_quiz_score >= 70) ? 'text-emerald-700' : 'text-amber-600' }}">
                        {{ $submission->avg_quiz_score ? number_format($submission->avg_quiz_score, 1) : '0.0' }}
                    </span>
                    <span class="text-xs text-[#6E675F] font-mono">/ Skala 100</span>
                </div>
                <div class="w-full bg-[#EBE5DF] h-2 rounded-full overflow-hidden">
                    <div class="h-full {{ ($submission->avg_quiz_score >= 70) ? 'bg-emerald-500' : 'bg-amber-500' }} rounded-full"
                         style="width: {{ min(100, $submission->avg_quiz_score ?? 0) }}%"></div>
                </div>
                <p class="text-[11px] text-[#6E675F] font-mono">
                    Standar Kelulusan: Min. 70.0
                </p>
            </div>

            <!-- 3. Informasi Kelas & Trainer -->
            <div class="bg-white border border-[#EBE5DF] rounded-2xl p-5 space-y-2 shadow-sm">
                <span class="text-xs font-bold text-[#6E675F] uppercase tracking-wider font-montserrat block">Kelas & Instruktur</span>
                <h4 class="font-montserrat font-extrabold text-sm text-[#1E1B18] line-clamp-1">{{ $class->title }}</h4>
                <div class="text-xs text-[#6E675F] space-y-1 font-quicksand pt-1">
                    <div>Instruktur: <span class="text-[#1E1B18] font-bold font-montserrat">{{ $trainer->name }}</span></div>
                    <div>Cabang: <span class="text-[#1E1B18]">{{ $class->branch->name ?? 'Pusat' }}</span></div>
                    <div>Tipe: <span class="text-[#1E1B18] uppercase font-bold">{{ $class->delivery_mode }}</span></div>
                </div>
            </div>
        </div>

        <!-- Riwayat Kehadiran Sesi -->
        <div class="bg-white border border-[#EBE5DF] rounded-2xl p-6 space-y-4 shadow-sm">
            <h3 class="font-montserrat font-extrabold text-base text-[#1E1B18] flex items-center gap-2">
                <span class="p-1.5 rounded-lg bg-purple-50 text-purple-600 border border-purple-100">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </span>
                <span>Rekam Presensi Sesi Pelatihan</span>
            </h3>

            @if($enrollment->attendances->isEmpty())
                <div class="p-6 rounded-xl bg-[#FAF8F5] border border-dashed border-[#EBE5DF] text-xs text-[#6E675F] text-center font-quicksand">
                    Belum ada log rekaman presensi mandiri atau sesi tatap muka pada kelas ini.
                </div>
            @else
                <div class="overflow-x-auto rounded-xl border border-[#EBE5DF]">
                    <table class="w-full text-left text-xs text-[#1E1B18]">
                        <thead class="bg-[#FAF8F5] text-[#6E675F] uppercase font-montserrat font-bold text-[11px] border-b border-[#EBE5DF]">
                            <tr>
                                <th class="py-3 px-4">Sesi Ke</th>
                                <th class="py-3 px-4">Judul Sesi</th>
                                <th class="py-3 px-4">Durasi</th>
                                <th class="py-3 px-4">Waktu Presensi</th>
                                <th class="py-3 px-4">Status Hadir</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#EBE5DF] font-quicksand">
                            @foreach($enrollment->attendances as $att)
                                <tr class="hover:bg-[#FAF8F5]/60 transition">
                                    <td class="py-3 px-4 font-mono font-bold text-[#FF6B00]">#{{ $att->session->session_order ?? '-' }}</td>
                                    <td class="py-3 px-4 font-bold">{{ $att->session->title ?? 'Sesi Pelatihan' }}</td>
                                    <td class="py-3 px-4 font-mono">{{ $att->session->minute_duration ?? 0 }} Menit</td>
                                    <td class="py-3 px-4 font-mono text-[#6E675F]">
                                        {{ $att->joined_at ? \Carbon\Carbon::parse($att->joined_at)->translatedFormat('d M Y H:i') : '-' }}
                                    </td>
                                    <td class="py-3 px-4">
                                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold font-montserrat bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            HADIR
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
