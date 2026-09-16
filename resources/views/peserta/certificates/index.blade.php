<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold font-montserrat bg-purple-50 text-purple-700 border border-purple-200 mb-1">
                    <span class="w-2 h-2 rounded-full bg-purple-500"></span>
                    E-Sertifikat Resmi • Terakreditasi 20 JP
                </span>
                <h2 class="font-montserrat font-extrabold text-2xl sm:text-3xl text-[#1E1B18] tracking-tight leading-tight">
                    Sertifikat & Kelulusan Saya
                </h2>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('peserta.study.index') }}" class="px-4 py-2.5 text-xs font-montserrat font-bold text-[#1E1B18] bg-white hover:bg-[#FAF8F5] rounded-xl border border-[#EBE5DF] shadow-sm transition flex items-center gap-2">
                    <svg class="w-4 h-4 text-[#FF6B00]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    <span>Ruang Belajar</span>
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
        <!-- Flash Alert -->
        @if(session('success'))
            <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm flex items-center justify-between font-quicksand">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 flex-shrink-0 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>{{ session('success') }}</span>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-700 text-sm flex items-center justify-between font-quicksand">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 flex-shrink-0 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>{{ session('error') }}</span>
                </div>
            </div>
        @endif

        <!-- Banner Info Standar Sertifikasi -->
        <div class="bg-white border border-[#EBE5DF] rounded-2xl p-6 shadow-sm flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
            <div class="space-y-1">
                <h3 class="font-montserrat font-extrabold text-[#1E1B18] text-base flex items-center gap-2.5">
                    <span class="p-2 rounded-xl bg-purple-50 text-purple-600 border border-purple-200/80">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
                    </span>
                    <span>Standar Kelulusan & Verifikasi Sertifikat</span>
                </h3>
                <p class="text-xs font-quicksand text-[#6E675F] leading-relaxed">
                    Sertifikat resmi diterbitkan setelah Anda menyelesaikan akumulasi belajar minimal <strong>20 JP (900 menit)</strong>, evaluasi kuis, serta memperoleh persetujuan resmi dari Trainer pengampu. Setiap sertifikat dilengkapi <strong>QR Code otentikasi publik</strong>.
                </p>
            </div>
            <div class="shrink-0">
                <span class="px-3.5 py-1.5 rounded-xl bg-purple-50 text-purple-700 border border-purple-200 text-xs font-montserrat font-bold">
                    1 JP = 45 Menit
                </span>
            </div>
        </div>

        <!-- Daftar Kelas & Status Sertifikat -->
        @if($enrollments->isEmpty())
            <div class="p-12 rounded-2xl bg-white border border-[#EBE5DF] text-center space-y-3 shadow-sm">
                <div class="w-14 h-14 rounded-2xl bg-orange-50 text-[#FF6B00] border border-orange-100 mx-auto flex items-center justify-center mb-3">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                </div>
                <h4 class="font-montserrat font-extrabold text-[#1E1B18] text-base">Belum Ada Kelas Terdaftar</h4>
                <p class="text-xs font-quicksand text-[#6E675F] max-w-sm mx-auto">
                    Anda belum mendaftar di kelas pelatihan manapun. Kunjungi katalog kelas untuk mulai belajar.
                </p>
                <div class="pt-3">
                    <a href="{{ route('peserta.catalog.index') }}" class="px-5 py-2.5 bg-gradient-to-r from-[#FF6B00] to-[#E11D48] hover:from-[#EA580C] hover:to-[#DC2626] text-white text-xs font-montserrat font-bold rounded-xl shadow-md hover:shadow-lg transition inline-block">
                        Lihat Katalog Kelas &rarr;
                    </a>
                </div>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($enrollments as $enrollment)
                    @php
                        $class = $enrollment->trainingClass;
                        $submission = $enrollment->graduationSubmission;
                        $jpPercent = min(100, round(($enrollment->total_minutes_accumulated / 900) * 100, 1));
                    @endphp
                    <div class="bg-white border border-[#EBE5DF] hover:border-[#FF6B00]/40 rounded-2xl p-5 sm:p-6 flex flex-col justify-between transition-all duration-200 shadow-sm hover:shadow-lg group">
                        <div>
                            <!-- Header Card: Category & Status Badge -->
                            <div class="flex items-center justify-between gap-2 mb-3">
                                <span class="text-[10px] font-montserrat font-bold px-2.5 py-0.5 rounded-md bg-[#FAF8F5] text-[#6E675F] border border-[#EBE5DF]">
                                    {{ $class->category ?? 'Pelatihan' }} • {{ $class->branch->name ?? 'Pusat' }}
                                </span>
                                @if($submission?->isApproved())
                                    <span class="inline-flex items-center gap-1 text-[10px] font-bold font-montserrat px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                        LULUS
                                    </span>
                                @elseif($submission?->isPending())
                                    <span class="inline-flex items-center gap-1 text-[10px] font-bold font-montserrat px-2.5 py-1 rounded-full bg-amber-50 text-amber-700 border border-amber-200">
                                        MENUNGGU REVIEW
                                    </span>
                                @elseif($submission?->isRejected())
                                    <span class="inline-flex items-center gap-1 text-[10px] font-bold font-montserrat px-2.5 py-1 rounded-full bg-rose-50 text-rose-700 border border-rose-200">
                                        PERLU REMEDIAL
                                    </span>
                                @elseif($enrollment->isCompletedJp())
                                    <span class="inline-flex items-center gap-1 text-[10px] font-bold font-montserrat px-2.5 py-1 rounded-full bg-blue-50 text-blue-700 border border-blue-200">
                                        SIAP AJUKAN
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 text-[10px] font-bold font-montserrat px-2.5 py-1 rounded-full bg-[#FAF8F5] text-[#6E675F] border border-[#EBE5DF]">
                                        SEDANG BELAJAR
                                    </span>
                                @endif
                            </div>

                            <!-- Class Title -->
                            <h4 class="font-montserrat font-extrabold text-base text-[#1E1B18] line-clamp-2 group-hover:text-[#FF6B00] transition">
                                {{ $class->title }}
                            </h4>
                            <p class="text-xs font-quicksand text-[#6E675F] mt-1 flex items-center gap-1">
                                <span>Instruktur:</span>
                                <span class="text-[#1E1B18] font-bold font-montserrat">{{ $class->trainer->name ?? 'Trainer' }}</span>
                            </p>

                            <!-- JP Progress -->
                            <div class="mt-4 pt-3 border-t border-[#EBE5DF] space-y-1.5">
                                <div class="flex items-center justify-between text-xs">
                                    <span class="text-[#6E675F] font-quicksand">Akumulasi Belajar:</span>
                                    <span class="font-montserrat font-bold {{ $enrollment->isCompletedJp() ? 'text-emerald-700' : 'text-[#FF6B00]' }}">
                                        {{ $enrollment->total_jp_accumulated }} / 20 JP ({{ $enrollment->total_minutes_accumulated }}m)
                                    </span>
                                </div>
                                <div class="w-full bg-[#EBE5DF] h-2 rounded-full overflow-hidden">
                                    <div class="h-full rounded-full transition-all duration-500 {{ $enrollment->isCompletedJp() ? 'bg-gradient-to-r from-emerald-500 to-teal-400' : 'bg-gradient-to-r from-[#FF6B00] to-amber-400' }}"
                                         style="width: {{ $jpPercent }}%"></div>
                                </div>
                            </div>

                            <!-- Certificate / Status Box -->
                            @if($submission?->isApproved())
                                <div class="mt-4 p-3.5 rounded-xl bg-purple-50 border border-purple-200 space-y-1.5">
                                    <div class="flex items-center justify-between text-[11px]">
                                        <span class="text-purple-700 font-semibold font-montserrat">No. Sertifikat:</span>
                                        <span class="font-mono font-bold text-[#1E1B18]">{{ $submission->certificate_number }}</span>
                                    </div>
                                    <div class="flex items-center justify-between text-[11px] text-[#6E675F] font-quicksand">
                                        <span>Diterbitkan:</span>
                                        <span>{{ $submission->reviewed_at ? \Carbon\Carbon::parse($submission->reviewed_at)->translatedFormat('d M Y') : '-' }}</span>
                                    </div>
                                    <div class="flex items-center justify-between text-[11px] text-[#6E675F] font-quicksand">
                                        <span>Rerata Kuis:</span>
                                        <span class="font-montserrat text-emerald-700 font-bold">{{ $submission->avg_quiz_score ? number_format($submission->avg_quiz_score, 1) : '-' }}</span>
                                    </div>
                                </div>
                            @elseif($submission?->isRejected())
                                <div class="mt-4 p-3.5 rounded-xl bg-rose-50 border border-rose-200 space-y-1">
                                    <span class="text-[11px] font-bold text-rose-700 uppercase tracking-wider block font-montserrat">
                                        Catatan Perbaikan Instruktur:
                                    </span>
                                    <p class="text-xs text-[#6E675F] line-clamp-2 italic font-quicksand">
                                        "{{ $submission->trainer_feedback }}"
                                    </p>
                                </div>
                            @elseif($submission?->isPending())
                                <div class="mt-4 p-3.5 rounded-xl bg-amber-50 border border-amber-200 text-xs text-amber-800 flex items-center gap-2 font-quicksand">
                                    <svg class="w-4 h-4 flex-shrink-0 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    <span>Sedang dalam antrean review Trainer. Notifikasi kelulusan akan diperbarui di sini.</span>
                                </div>
                            @endif
                        </div>

                        <!-- Action Buttons -->
                        <div class="mt-5 pt-4 border-t border-[#EBE5DF] space-y-2">
                            @if($submission?->isApproved())
                                <div class="grid grid-cols-2 gap-2">
                                    <a href="{{ route('peserta.certificates.download', $submission) }}" class="px-3 py-2 bg-purple-600 hover:bg-purple-500 text-white rounded-xl text-xs font-bold font-montserrat flex items-center justify-center gap-1.5 transition shadow-sm">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                        <span>Unduh PDF</span>
                                    </a>
                                    <a href="{{ route('peserta.certificates.preview', $submission) }}" target="_blank" class="px-3 py-2 bg-[#FAF8F5] hover:bg-white text-[#1E1B18] rounded-xl text-xs font-bold font-montserrat flex items-center justify-center gap-1.5 border border-[#EBE5DF] transition shadow-sm">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        <span>Pratinjau</span>
                                    </a>
                                </div>
                                <a href="{{ route('certificates.verify', $submission->certificate_number) }}" target="_blank" class="w-full px-3 py-1.5 bg-[#FAF8F5] hover:bg-white text-[#6E675F] hover:text-[#1E1B18] rounded-lg text-[11px] font-mono flex items-center justify-center gap-1 transition border border-[#EBE5DF]">
                                    <svg class="w-3 h-3 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                                    <span>Halaman Verifikasi Publik &rarr;</span>
                                </a>
                            @elseif($submission?->isRejected())
                                <a href="{{ route('peserta.certificates.show', $submission) }}" class="w-full px-3 py-2 bg-rose-600 hover:bg-rose-500 text-white rounded-xl text-xs font-bold font-montserrat flex items-center justify-center gap-1.5 transition shadow-sm">
                                    <span>Lihat Instruksi & Ajukan Ulang</span>
                                </a>
                            @elseif($submission?->isPending())
                                <a href="{{ route('peserta.certificates.show', $submission) }}" class="w-full px-3 py-2 bg-[#FAF8F5] hover:bg-white text-[#1E1B18] rounded-xl text-xs font-bold font-montserrat flex items-center justify-center gap-1.5 border border-[#EBE5DF] transition shadow-sm">
                                    <span>Lembar Pengajuan Review</span>
                                </a>
                            @elseif($enrollment->isCompletedJp())
                                <form method="POST" action="{{ route('peserta.certificates.requestReview', $class) }}">
                                    @csrf
                                    <button type="submit" class="w-full px-3.5 py-2.5 bg-gradient-to-r from-[#FF6B00] to-[#E11D48] hover:from-[#EA580C] hover:to-[#DC2626] text-white rounded-xl text-xs font-bold font-montserrat flex items-center justify-center gap-1.5 transition shadow-md hover:shadow-lg">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        <span>Ajukan Verifikasi Kelulusan (20 JP)</span>
                                    </button>
                                </form>
                            @else
                                <a href="{{ route('peserta.study.show', $class) }}" class="w-full px-3 py-2 bg-[#FAF8F5] hover:bg-white text-[#1E1B18] rounded-xl text-xs font-bold font-montserrat flex items-center justify-center gap-1.5 border border-[#EBE5DF] transition shadow-sm">
                                    <span>Lanjutkan Belajar (Butuh {{ max(0, round(20 - $enrollment->total_jp_accumulated, 1)) }} JP)</span>
                                </a>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="pt-4">
                {{ $enrollments->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
