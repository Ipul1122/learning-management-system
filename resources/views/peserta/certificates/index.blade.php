<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold font-montserrat bg-purple-500/10 text-purple-400 border border-purple-500/20 mb-1">
                    <span class="w-2 h-2 rounded-full bg-purple-400"></span>
                    E-Sertifikat Resmi • Terakreditasi 20 JP
                </span>
                <h2 class="font-montserrat font-extrabold text-2xl text-white leading-tight">
                    Sertifikat & Kelulusan Saya
                </h2>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('peserta.study.index') }}" class="px-4 py-2 text-xs font-bold text-slate-300 bg-slate-800 hover:bg-slate-700 rounded-xl border border-slate-700 transition flex items-center gap-2 font-montserrat">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    <span>Ruang Belajar</span>
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6 space-y-6">
        <!-- Flash Alert -->
        @if(session('success'))
            <div class="p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-sm flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>{{ session('success') }}</span>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="p-4 rounded-xl bg-rose-500/10 border border-rose-500/30 text-rose-400 text-sm flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>{{ session('error') }}</span>
                </div>
            </div>
        @endif

        <!-- Banner Info Standar Sertifikasi -->
        <div class="bg-gradient-to-r from-purple-900/40 via-indigo-900/30 to-slate-900 rounded-2xl p-5 border border-purple-800/40 flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
            <div class="space-y-1">
                <h3 class="font-montserrat font-bold text-white text-sm flex items-center gap-2">
                    <svg class="w-4 h-4 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
                    Standar Kelulusan & Verifikasi Sertifikat
                </h3>
                <p class="text-xs text-slate-300">
                    Sertifikat resmi diterbitkan setelah Anda menyelesaikan akumulasi belajar minimal <strong>20 JP (900 menit)</strong>, evaluasi kuis, serta memperoleh persetujuan resmi dari Trainer pengampu. Setiap sertifikat dilengkapi <strong>QR Code otentikasi publik</strong>.
                </p>
            </div>
            <div class="shrink-0">
                <span class="px-3 py-1.5 rounded-xl bg-purple-500/20 text-purple-300 border border-purple-500/30 text-xs font-mono font-bold">
                    1 JP = 45 Menit
                </span>
            </div>
        </div>

        <!-- Daftar Kelas & Status Sertifikat -->
        @if($enrollments->isEmpty())
            <div class="p-12 rounded-2xl bg-slate-900/60 border border-slate-800 text-center space-y-3">
                <div class="w-12 h-12 rounded-2xl bg-slate-800 text-slate-400 mx-auto flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                </div>
                <h4 class="font-montserrat font-bold text-white text-base">Belum Ada Kelas Terdaftar</h4>
                <p class="text-xs text-slate-400 max-w-sm mx-auto">
                    Anda belum mendaftar di kelas pelatihan manapun. Kunjungi katalog kelas untuk mulai belajar.
                </p>
                <div class="pt-2">
                    <a href="{{ route('peserta.catalog.index') }}" class="px-4 py-2 bg-orange-600 hover:bg-orange-500 text-white text-xs font-bold font-montserrat rounded-xl transition inline-block">
                        Lihat Katalog Kelas
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
                    <div class="bg-slate-900/60 border {{ $submission?->isApproved() ? 'border-purple-500/40 shadow-lg shadow-purple-500/5' : 'border-slate-800' }} rounded-2xl p-5 flex flex-col justify-between hover:border-slate-700 transition">
                        <div>
                            <!-- Header Card: Category & Status Badge -->
                            <div class="flex items-center justify-between gap-2 mb-3">
                                <span class="text-[10px] font-mono font-semibold px-2 py-0.5 rounded bg-slate-800 text-slate-400">
                                    {{ $class->category ?? 'Pelatihan' }} • {{ $class->branch->name ?? 'Pusat' }}
                                </span>
                                @if($submission?->isApproved())
                                    <span class="inline-flex items-center gap-1 text-[10px] font-bold font-montserrat px-2.5 py-1 rounded-full bg-emerald-500/15 text-emerald-400 border border-emerald-500/30">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                        LULUS & TERSERTIFIKASI
                                    </span>
                                @elseif($submission?->isPending())
                                    <span class="inline-flex items-center gap-1 text-[10px] font-bold font-montserrat px-2.5 py-1 rounded-full bg-amber-500/15 text-amber-400 border border-amber-500/30">
                                        <svg class="w-3 h-3 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                        MENUNGGU REVIEW
                                    </span>
                                @elseif($submission?->isRejected())
                                    <span class="inline-flex items-center gap-1 text-[10px] font-bold font-montserrat px-2.5 py-1 rounded-full bg-rose-500/15 text-rose-400 border border-rose-500/30">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                        PERLU REMEDIAL
                                    </span>
                                @elseif($enrollment->isCompletedJp())
                                    <span class="inline-flex items-center gap-1 text-[10px] font-bold font-montserrat px-2.5 py-1 rounded-full bg-blue-500/15 text-blue-400 border border-blue-500/30">
                                        SIAP AJUKAN
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 text-[10px] font-bold font-montserrat px-2.5 py-1 rounded-full bg-slate-800 text-slate-400 border border-slate-700">
                                        SEDANG BELAJAR
                                    </span>
                                @endif
                            </div>

                            <!-- Class Title -->
                            <h4 class="font-montserrat font-bold text-base text-white line-clamp-2">
                                {{ $class->title }}
                            </h4>
                            <p class="text-xs text-slate-400 mt-1 flex items-center gap-1">
                                <span>Instruktur:</span>
                                <span class="text-slate-300 font-semibold">{{ $class->trainer->name ?? 'Trainer' }}</span>
                            </p>

                            <!-- JP Progress -->
                            <div class="mt-4 pt-3 border-t border-slate-800 space-y-1.5">
                                <div class="flex items-center justify-between text-xs">
                                    <span class="text-slate-400">Akumulasi Belajar:</span>
                                    <span class="font-mono font-bold {{ $enrollment->isCompletedJp() ? 'text-emerald-400' : 'text-orange-400' }}">
                                        {{ $enrollment->total_jp_accumulated }} / 20 JP ({{ $enrollment->total_minutes_accumulated }}m)
                                    </span>
                                </div>
                                <div class="w-full bg-slate-800 h-2 rounded-full overflow-hidden">
                                    <div class="h-full rounded-full transition-all duration-500 {{ $enrollment->isCompletedJp() ? 'bg-gradient-to-r from-emerald-500 to-teal-400' : 'bg-gradient-to-r from-orange-500 to-amber-400' }}"
                                         style="width: {{ $jpPercent }}%"></div>
                                </div>
                            </div>

                            <!-- Certificate / Status Box -->
                            @if($submission?->isApproved())
                                <div class="mt-4 p-3 rounded-xl bg-purple-500/10 border border-purple-500/20 space-y-1.5">
                                    <div class="flex items-center justify-between text-[11px]">
                                        <span class="text-purple-300 font-semibold">No. Sertifikat:</span>
                                        <span class="font-mono font-bold text-white">{{ $submission->certificate_number }}</span>
                                    </div>
                                    <div class="flex items-center justify-between text-[11px] text-slate-400">
                                        <span>Diterbitkan:</span>
                                        <span>{{ $submission->reviewed_at ? \Carbon\Carbon::parse($submission->reviewed_at)->translatedFormat('d M Y') : '-' }}</span>
                                    </div>
                                    <div class="flex items-center justify-between text-[11px] text-slate-400">
                                        <span>Rerata Kuis:</span>
                                        <span class="font-mono text-emerald-400 font-bold">{{ $submission->avg_quiz_score ? number_format($submission->avg_quiz_score, 1) : '-' }}</span>
                                    </div>
                                </div>
                            @elseif($submission?->isRejected())
                                <div class="mt-4 p-3 rounded-xl bg-rose-500/10 border border-rose-500/20 space-y-1">
                                    <span class="text-[11px] font-bold text-rose-400 uppercase tracking-wider block font-montserrat">
                                        Catatan Perbaikan Instruktur:
                                    </span>
                                    <p class="text-xs text-slate-300 line-clamp-2 italic">
                                        "{{ $submission->trainer_feedback }}"
                                    </p>
                                </div>
                            @elseif($submission?->isPending())
                                <div class="mt-4 p-3 rounded-xl bg-amber-500/10 border border-amber-500/20 text-xs text-amber-300/90 flex items-center gap-2">
                                    <svg class="w-4 h-4 flex-shrink-0 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    <span>Sedang dalam antrean review Trainer. Notifikasi kelulusan akan diperbarui di sini.</span>
                                </div>
                            @endif
                        </div>

                        <!-- Action Buttons -->
                        <div class="mt-5 pt-4 border-t border-slate-800/80 space-y-2">
                            @if($submission?->isApproved())
                                <div class="grid grid-cols-2 gap-2">
                                    <a href="{{ route('peserta.certificates.download', $submission) }}" class="px-3 py-2 bg-purple-600 hover:bg-purple-500 text-white rounded-xl text-xs font-bold font-montserrat flex items-center justify-center gap-1.5 transition">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                        <span>Unduh PDF</span>
                                    </a>
                                    <a href="{{ route('peserta.certificates.preview', $submission) }}" target="_blank" class="px-3 py-2 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded-xl text-xs font-bold font-montserrat flex items-center justify-center gap-1.5 border border-slate-700 transition">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        <span>Pratinjau</span>
                                    </a>
                                </div>
                                <a href="{{ route('certificates.verify', $submission->certificate_number) }}" target="_blank" class="w-full px-3 py-1.5 bg-slate-800/50 hover:bg-slate-800 text-slate-400 hover:text-slate-300 rounded-lg text-[11px] font-mono flex items-center justify-center gap-1 transition">
                                    <svg class="w-3 h-3 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                                    <span>Halaman Verifikasi Publik &rarr;</span>
                                </a>
                            @elseif($submission?->isRejected())
                                <a href="{{ route('peserta.certificates.show', $submission) }}" class="w-full px-3 py-2 bg-rose-600 hover:bg-rose-500 text-white rounded-xl text-xs font-bold font-montserrat flex items-center justify-center gap-1.5 transition">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    <span>Lihat Instruksi & Ajukan Ulang</span>
                                </a>
                            @elseif($submission?->isPending())
                                <a href="{{ route('peserta.certificates.show', $submission) }}" class="w-full px-3 py-2 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded-xl text-xs font-bold font-montserrat flex items-center justify-center gap-1.5 border border-slate-700 transition">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    <span>Lembar Pengajuan Review</span>
                                </a>
                            @elseif($enrollment->isCompletedJp())
                                <form method="POST" action="{{ route('peserta.certificates.requestReview', $class) }}">
                                    @csrf
                                    <button type="submit" class="w-full px-3 py-2 bg-blue-600 hover:bg-blue-500 text-white rounded-xl text-xs font-bold font-montserrat flex items-center justify-center gap-1.5 transition shadow-lg shadow-blue-500/20">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        <span>Ajukan Verifikasi Kelulusan (20 JP)</span>
                                    </button>
                                </form>
                            @else
                                <a href="{{ route('peserta.study.show', $class) }}" class="w-full px-3 py-2 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded-xl text-xs font-bold font-montserrat flex items-center justify-center gap-1.5 border border-slate-700 transition">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
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
