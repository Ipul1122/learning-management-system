<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div class="flex items-center gap-3">
                <a href="{{ route('trainer.graduations.index') }}" class="p-2 rounded-xl bg-slate-800/80 hover:bg-slate-700/80 border border-slate-700 text-slate-300 hover:text-white transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>
                <div>
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-bold font-montserrat bg-amber-500/10 text-amber-400 border border-amber-500/20 mb-1">
                        {{ $class->title }} • {{ $class->branch?->name }}
                    </span>
                    <h1 class="text-2xl font-extrabold text-white tracking-tight font-montserrat">
                        Audit Kelulusan: {{ $student->name }}
                    </h1>
                </div>
            </div>
            <div class="flex items-center gap-2">
                @if($submission->isApproved())
                    <span class="px-3.5 py-1.5 rounded-xl text-xs font-bold font-montserrat bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Telah Disetujui (Lulus)
                    </span>
                @elseif($submission->isRejected())
                    <span class="px-3.5 py-1.5 rounded-xl text-xs font-bold font-montserrat bg-rose-500/20 text-rose-400 border border-rose-500/30 flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        Status: Remedial / Ditolak
                    </span>
                @else
                    <span class="px-3.5 py-1.5 rounded-xl text-xs font-bold font-montserrat bg-amber-500/20 text-amber-400 border border-amber-500/30 flex items-center gap-1.5 animate-pulse">
                        <span class="w-2 h-2 rounded-full bg-amber-400"></span>
                        Menunggu Keputusan Trainer
                    </span>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-6 space-y-6" x-data="graduationReviewManager()">
        <!-- Notifikasi Session Flash -->
        @if(session('success'))
            <div class="p-4 rounded-2xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-xs font-medium flex items-center gap-3">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif
        @if(session('warning'))
            <div class="p-4 rounded-2xl bg-amber-500/10 border border-amber-500/30 text-amber-400 text-xs font-medium flex items-center gap-3">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                <span>{{ session('warning') }}</span>
            </div>
        @endif
        @if($errors->any())
            <div class="p-4 rounded-2xl bg-rose-500/10 border border-rose-500/30 text-rose-400 text-xs font-medium space-y-1">
                @foreach($errors->all() as $err)
                    <div>• {{ $err }}</div>
                @endforeach
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Kolom Kiri: Rekapitulasi Pembelajaran (2 Kolom) -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Hero Metrik Capaian -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="p-5 rounded-2xl bg-slate-900/60 border border-slate-800/80">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 font-montserrat block">Akumulasi Jam Pelajaran</span>
                        <div class="text-2xl font-extrabold text-emerald-400 font-mono mt-1">
                            {{ $submission->total_jp_earned }} <span class="text-xs text-slate-400">/ 20.0 JP</span>
                        </div>
                        <span class="text-xs text-slate-400 mt-0.5 block font-mono">
                            {{ $enrollment->accumulated_minutes }} dari 900 Menit ({{ $enrollment->progressPercentage() }}%)
                        </span>
                    </div>

                    <div class="p-5 rounded-2xl bg-slate-900/60 border border-slate-800/80">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 font-montserrat block">Rata-rata Nilai Kuis</span>
                        <div class="text-2xl font-extrabold text-amber-400 font-mono mt-1">
                            {{ $submission->avg_quiz_score }}%
                        </div>
                        <span class="text-xs text-slate-400 mt-0.5 block">
                            {{ $class->quizzes->count() }} Paket Kuis Tersedia
                        </span>
                    </div>

                    <div class="p-5 rounded-2xl bg-slate-900/60 border border-slate-800/80">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 font-montserrat block">Presensi Sesi Pertemuan</span>
                        <div class="text-2xl font-extrabold text-white font-mono mt-1">
                            {{ $attendancesBySessionId->count() }} <span class="text-xs text-slate-400">/ {{ $class->sessions->count() }} Sesi</span>
                        </div>
                        <span class="text-xs text-slate-400 mt-0.5 block uppercase font-mono">
                            Mode: {{ $enrollment->attendance_mode }}
                        </span>
                    </div>
                </div>

                <!-- Rekapitulasi Presensi Sesi Zoom -->
                <div class="bg-slate-900/60 border border-slate-800/80 rounded-2xl p-6 space-y-4">
                    <h3 class="text-base font-extrabold text-white font-montserrat flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                        </svg>
                        Riwayat Presensi Sesi Tatap Muka & Zoom
                    </h3>

                    @if($class->sessions->isEmpty())
                        <div class="p-6 rounded-xl bg-slate-800/40 text-center text-xs text-slate-400">
                            Tidak ada sesi pertemuan dalam kurikulum kelas ini.
                        </div>
                    @else
                        <div class="space-y-2.5">
                            @foreach($class->sessions->sortBy('session_order') as $session)
                                @php
                                    $att = $attendancesBySessionId->get($session->id);
                                    $isAttended = (bool) $att;
                                @endphp
                                <div class="p-3.5 rounded-xl border {{ $isAttended ? 'border-emerald-500/30 bg-emerald-500/[0.02]' : 'border-slate-800 bg-slate-800/40' }} flex items-center justify-between text-xs">
                                    <div class="flex items-center gap-3">
                                        <span class="w-7 h-7 rounded-lg {{ $isAttended ? 'bg-emerald-500/20 text-emerald-400' : 'bg-slate-700 text-slate-400' }} flex items-center justify-center font-bold text-xs font-mono">
                                            #{{ $session->session_order }}
                                        </span>
                                        <div>
                                            <div class="font-bold text-white font-montserrat">{{ $session->title }}</div>
                                            <div class="text-[11px] text-slate-400 font-mono">
                                                Durasi: {{ $session->minute_duration }} Menit ({{ round($session->minute_duration / 45, 1) }} JP)
                                            </div>
                                        </div>
                                    </div>

                                    <div>
                                        @if($isAttended)
                                            <span class="inline-flex items-center gap-1 text-[11px] font-bold text-emerald-400 font-montserrat">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                                Hadir (+{{ $att->minutes_earned }} Menit)
                                            </span>
                                        @else
                                            <span class="text-[11px] text-slate-500 font-montserrat">
                                                Tidak Hadir
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- Rekapitulasi Pengerjaan Kuis -->
                <div class="bg-slate-900/60 border border-slate-800/80 rounded-2xl p-6 space-y-4">
                    <h3 class="text-base font-extrabold text-white font-montserrat flex items-center gap-2">
                        <svg class="w-5 h-5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                        </svg>
                        Hasil Evaluasi Paket Kuis
                    </h3>

                    @if($class->quizzes->isEmpty())
                        <div class="p-6 rounded-xl bg-slate-800/40 text-center text-xs text-slate-400">
                            Tidak ada paket kuis dalam kelas ini.
                        </div>
                    @else
                        <div class="space-y-2.5">
                            @foreach($class->quizzes as $quiz)
                                @php
                                    $attempts = $userQuizAttempts->get($quiz->id, collect());
                                    $bestScore = $attempts->max('total_score');
                                    $hasPassed = $attempts->contains('is_passed', true);
                                @endphp
                                <div class="p-3.5 rounded-xl border border-slate-800 bg-slate-800/40 flex items-center justify-between text-xs">
                                    <div>
                                        <div class="font-bold text-white font-montserrat">{{ $quiz->title }}</div>
                                        <div class="text-[11px] text-slate-400 font-mono">
                                            KKM: {{ $quiz->passing_grade }}% • Total Percobaan: {{ $attempts->count() }} / {{ $quiz->max_attempts }}
                                        </div>
                                    </div>

                                    <div class="text-right">
                                        @if($attempts->isNotEmpty())
                                            <div class="font-bold font-mono text-sm {{ $hasPassed ? 'text-emerald-400' : 'text-rose-400' }}">
                                                Skor: {{ $bestScore }}
                                            </div>
                                            <span class="text-[10px] font-bold uppercase font-montserrat {{ $hasPassed ? 'text-emerald-400' : 'text-rose-400' }}">
                                                {{ $hasPassed ? 'Lulus KKM' : 'Belum Lulus KKM' }}
                                            </span>
                                        @else
                                            <span class="text-slate-500 font-montserrat text-[11px]">Belum Dikerjakan</span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <!-- Kolom Kanan: Keputusan Evaluasi Trainer (1 Kolom) -->
            <div class="space-y-6">
                <!-- Card Keputusan -->
                <div class="bg-gradient-to-br from-slate-900 via-slate-900 to-slate-950 border border-slate-800 rounded-3xl p-6 shadow-xl space-y-5">
                    <div>
                        <span class="text-xs font-bold uppercase tracking-wider text-amber-400 font-montserrat">Keputusan Kelulusan</span>
                        <h3 class="text-lg font-extrabold text-white font-montserrat mt-0.5">Tindakan Instruktur</h3>
                        <p class="text-xs text-slate-400 mt-1 font-quicksand">
                            Evaluasi kelayakan siswa berdasarkan pemenuhan 20 JP dan capaian nilai materi.
                        </p>
                    </div>

                    @if($submission->isApproved())
                        <!-- State: Sudah Lulus -->
                        <div class="p-4 rounded-2xl bg-emerald-500/10 border border-emerald-500/30 space-y-3">
                            <div class="flex items-center gap-2 text-emerald-400 font-bold text-xs font-montserrat">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                E-Sertifikat Telah Diterbitkan
                            </div>
                            <div class="text-xs space-y-1 font-mono text-slate-300">
                                <div>No. Registrasi: <strong class="text-white">{{ $submission->certificate_number }}</strong></div>
                                <div>Waktu Review: {{ $submission->reviewed_at?->translatedFormat('d M Y, H:i') }} WIB</div>
                                <div>Trainer Penilai: {{ $submission->trainer?->name ?? Auth::user()->name }}</div>
                            </div>
                            @if($submission->certificate_number)
                                <a href="{{ route('certificates.verify', $submission->certificate_number) }}" target="_blank" class="w-full py-2 text-xs font-semibold text-emerald-400 hover:text-emerald-300 bg-emerald-500/20 hover:bg-emerald-500/30 rounded-xl transition flex items-center justify-center gap-1.5 font-montserrat">
                                    <span>Buka Verifikasi Publik QR &rarr;</span>
                                </a>
                            @endif
                        </div>
                    @else
                        <!-- State: Pending atau Rejected (Bisa re-evaluasi) -->
                        <div class="space-y-3">
                            @if($submission->isRejected())
                                <div class="p-3.5 rounded-xl bg-rose-500/10 border border-rose-500/30 text-xs text-slate-300 space-y-1">
                                    <span class="font-bold text-rose-400 block font-montserrat">Catatan Remedial Sebelumnya:</span>
                                    <p class="text-slate-300 font-quicksand">{{ $submission->trainer_feedback }}</p>
                                </div>
                            @endif

                            <!-- Form Approve -->
                            <form method="POST" action="{{ route('trainer.graduations.approve', $submission) }}" id="approve-form">
                                @csrf
                                <button type="button"
                                        @click="confirmApprove()"
                                        class="w-full py-3 text-xs font-bold text-white bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700 rounded-xl shadow-lg shadow-emerald-500/20 transition flex items-center justify-center gap-2 font-montserrat">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    <span>Setujui & Terbitkan E-Sertifikat</span>
                                </button>
                            </form>

                            <!-- Tombol Trigger Modal Reject -->
                            <button type="button"
                                    @click="openRejectModal = true"
                                    class="w-full py-3 text-xs font-bold text-rose-400 hover:text-white bg-rose-500/10 hover:bg-rose-600 border border-rose-500/30 rounded-xl transition flex items-center justify-center gap-2 font-montserrat">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                <span>Tolak Kelulusan (Wajib Remedial)</span>
                            </button>
                        </div>
                    @endif
                </div>

                <!-- Info Profil Peserta -->
                <div class="bg-slate-900/40 border border-slate-800/80 rounded-2xl p-5 space-y-3 text-xs text-slate-400">
                    <span class="font-bold uppercase tracking-wider text-slate-300 font-montserrat block">Biodata Peserta</span>
                    <div class="space-y-1.5 font-quicksand">
                        <div class="text-white font-bold text-sm">{{ $student->name }}</div>
                        <div>Email: <strong class="text-slate-200">{{ $student->email }}</strong></div>
                        <div>Telepon: {{ $student->phone_number ?? '-' }}</div>
                        <div>Cabang: {{ $class->branch?->name }}</div>
                        <div>Terdaftar Sejak: {{ $enrollment->enrolled_at ? \Carbon\Carbon::parse($enrollment->enrolled_at)->translatedFormat('d M Y') : '-' }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Dialog Penolakan / Remedial (Wajib Feedback) -->
        <div x-show="openRejectModal"
             x-cloak
             class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/70 backdrop-blur-xs">
            <div class="bg-slate-900 border border-slate-800 rounded-2xl max-w-md w-full p-6 space-y-4 shadow-2xl"
                 @click.away="openRejectModal = false">
                <div>
                    <span class="text-xs font-bold uppercase tracking-wider text-rose-400 font-montserrat">Remedial Pelatihan</span>
                    <h3 class="text-lg font-bold text-white font-montserrat mt-0.5">Instruksi Perbaikan Peserta</h3>
                    <p class="text-xs text-slate-400 mt-1 font-quicksand">
                        Tuliskan alasan penolakan dan materi yang harus diperbaiki/diulang oleh peserta.
                    </p>
                </div>

                <form method="POST" action="{{ route('trainer.graduations.reject', $submission) }}" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-semibold text-slate-300 font-montserrat mb-1">Catatan & Arahan Trainer *</label>
                        <textarea name="trainer_feedback"
                                  rows="4"
                                  required
                                  placeholder="Contoh: Silakan ulangi kuis modul 2 untuk memenuhi passing grade minimal 75%..."
                                  class="w-full px-3.5 py-2.5 text-xs rounded-xl bg-slate-800/80 border border-slate-700 text-white placeholder-slate-500 focus:ring-2 focus:ring-rose-500 font-quicksand"></textarea>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-2">
                        <button type="button"
                                @click="openRejectModal = false"
                                class="px-4 py-2 text-xs font-semibold text-slate-400 hover:text-white bg-slate-800 rounded-xl transition">
                            Batal
                        </button>
                        <button type="submit"
                                class="px-5 py-2 text-xs font-bold text-white bg-rose-600 hover:bg-rose-500 rounded-xl shadow-md shadow-rose-600/20 transition font-montserrat">
                            Kirim Instruksi Remedial
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Script SweetAlert2 Konfirmasi Approve -->
    <script>
        function graduationReviewManager() {
            return {
                openRejectModal: false,
                confirmApprove() {
                    if (window.Swal) {
                        Swal.fire({
                            title: 'Setujui Kelulusan 20 JP?',
                            text: 'Sistem akan menerbitkan nomor registrasi E-Sertifikat resmi dan QR Code verifikasi untuk peserta ini.',
                            icon: 'question',
                            showCancelButton: true,
                            confirmButtonText: 'Ya, Setujui & Terbitkan',
                            cancelButtonText: 'Batal',
                            confirmButtonColor: '#10B981',
                            cancelButtonColor: '#322E2B',
                            background: '#1E1B18',
                            color: '#FFFFFF'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                document.getElementById('approve-form').submit();
                            }
                        });
                    } else {
                        if (confirm('Setujui kelulusan peserta ini dan terbitkan E-Sertifikat?')) {
                            document.getElementById('approve-form').submit();
                        }
                    }
                }
            }
        }
    </script>
</x-app-layout>
