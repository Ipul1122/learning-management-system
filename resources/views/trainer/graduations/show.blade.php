<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div class="flex items-center gap-3">
                <a href="{{ route('trainer.graduations.index') }}" class="p-2.5 rounded-xl bg-white hover:bg-[#FAF8F5] border border-[#EBE5DF] text-[#1E1B18] shadow-sm transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>
                <div>
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-bold font-montserrat bg-[#FFF7ED] text-[#EA580C] border border-[#FED7AA] mb-1">
                        {{ $class->title }} • {{ $class->branch?->name }}
                    </span>
                    <h1 class="text-2xl font-extrabold text-[#1E1B18] tracking-tight font-montserrat">
                        Audit Kelulusan: {{ $student->name }}
                    </h1>
                </div>
            </div>
            <div class="flex items-center gap-2">
                @if($submission->isApproved())
                    <span class="px-3.5 py-1.5 rounded-xl text-xs font-bold font-montserrat bg-emerald-50 text-emerald-700 border border-emerald-200 flex items-center gap-1.5 shadow-sm">
                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Telah Disetujui (Lulus)
                    </span>
                @elseif($submission->isRejected())
                    <span class="px-3.5 py-1.5 rounded-xl text-xs font-bold font-montserrat bg-rose-50 text-rose-700 border border-rose-200 flex items-center gap-1.5 shadow-sm">
                        <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        Status: Remedial / Ditolak
                    </span>
                @else
                    <span class="px-3.5 py-1.5 rounded-xl text-xs font-bold font-montserrat bg-[#FFF7ED] text-[#EA580C] border border-[#FED7AA] flex items-center gap-1.5 animate-pulse shadow-sm">
                        <span class="w-2 h-2 rounded-full bg-[#EA580C]"></span>
                        Menunggu Keputusan Trainer
                    </span>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-6 space-y-6" x-data="graduationReviewManager()">
        <!-- Notifikasi Session Flash -->
        @if(session('success'))
            <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-medium flex items-center gap-3 shadow-sm">
                <svg class="w-5 h-5 flex-shrink-0 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif
        @if(session('warning'))
            <div class="p-4 rounded-2xl bg-amber-50 border border-amber-200 text-amber-800 text-xs font-medium flex items-center gap-3 shadow-sm">
                <svg class="w-5 h-5 flex-shrink-0 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                <span>{{ session('warning') }}</span>
            </div>
        @endif
        @if($errors->any())
            <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-medium space-y-1 shadow-sm">
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
                    <div class="p-5 rounded-2xl bg-white border border-[#EBE5DF] shadow-sm">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-[#A8A29E] font-montserrat block">Akumulasi Jam Pelajaran</span>
                        <div class="text-2xl font-extrabold text-emerald-600 font-mono mt-1">
                            {{ $submission->total_jp_earned }} <span class="text-xs text-[#6E675F]">/ 20.0 JP</span>
                        </div>
                        <span class="text-xs text-[#6E675F] mt-0.5 block font-mono">
                            {{ $enrollment->accumulated_minutes }} dari 900 Menit ({{ $enrollment->progressPercentage() }}%)
                        </span>
                    </div>

                    <div class="p-5 rounded-2xl bg-white border border-[#EBE5DF] shadow-sm">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-[#A8A29E] font-montserrat block">Rata-rata Nilai Kuis</span>
                        <div class="text-2xl font-extrabold text-[#EA580C] font-mono mt-1">
                            {{ $submission->avg_quiz_score }}%
                        </div>
                        <span class="text-xs text-[#6E675F] mt-0.5 block">
                            {{ $class->quizzes->count() }} Paket Kuis Tersedia
                        </span>
                    </div>

                    <div class="p-5 rounded-2xl bg-white border border-[#EBE5DF] shadow-sm">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-[#A8A29E] font-montserrat block">Presensi Sesi Pertemuan</span>
                        <div class="text-2xl font-extrabold text-[#1E1B18] font-mono mt-1">
                            {{ $attendancesBySessionId->count() }} <span class="text-xs text-[#6E675F]">/ {{ $class->sessions->count() }} Sesi</span>
                        </div>
                        <span class="text-xs text-[#6E675F] mt-0.5 block uppercase font-mono">
                            Mode: {{ $enrollment->attendance_mode }}
                        </span>
                    </div>
                </div>

                <!-- Rekapitulasi Presensi Sesi Zoom -->
                <div class="bg-white border border-[#EBE5DF] rounded-2xl p-6 space-y-4 shadow-sm">
                    <h3 class="text-base font-extrabold text-[#1E1B18] font-montserrat flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                        </svg>
                        Riwayat Presensi Sesi Tatap Muka & Zoom
                    </h3>

                    @if($class->sessions->isEmpty())
                        <div class="p-6 rounded-xl bg-[#FAF8F5] border border-[#EBE5DF] text-center text-xs text-[#6E675F]">
                            Tidak ada sesi pertemuan dalam kurikulum kelas ini.
                        </div>
                    @else
                        <div class="space-y-2.5">
                            @foreach($class->sessions->sortBy('session_order') as $session)
                                @php
                                    $att = $attendancesBySessionId->get($session->id);
                                    $isAttended = (bool) $att;
                                @endphp
                                <div class="p-3.5 rounded-xl border {{ $isAttended ? 'border-emerald-200 bg-emerald-50/50' : 'border-[#EBE5DF] bg-[#FAF8F5]' }} flex items-center justify-between text-xs font-quicksand">
                                    <div class="flex items-center gap-3">
                                        <span class="w-7 h-7 rounded-lg {{ $isAttended ? 'bg-emerald-100 text-emerald-800' : 'bg-white border border-[#EBE5DF] text-[#6E675F]' }} flex items-center justify-center font-bold text-xs font-mono shadow-xs">
                                            #{{ $session->session_order }}
                                        </span>
                                        <div>
                                            <div class="font-bold text-[#1E1B18] font-montserrat">{{ $session->title }}</div>
                                            <div class="text-[11px] text-[#6E675F] font-mono">
                                                Durasi: {{ $session->minute_duration }} Menit ({{ round($session->minute_duration / 45, 1) }} JP)
                                            </div>
                                        </div>
                                    </div>

                                    <div>
                                        @if($isAttended)
                                            <span class="inline-flex items-center gap-1 text-[11px] font-bold text-emerald-700 font-montserrat">
                                                <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                                Hadir (+{{ $att->minutes_earned }} Menit)
                                            </span>
                                        @else
                                            <span class="text-[11px] text-[#A8A29E] font-montserrat font-medium">
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
                <div class="bg-white border border-[#EBE5DF] rounded-2xl p-6 space-y-4 shadow-sm">
                    <h3 class="text-base font-extrabold text-[#1E1B18] font-montserrat flex items-center gap-2">
                        <svg class="w-5 h-5 text-[#EA580C]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                        </svg>
                        Hasil Evaluasi Paket Kuis
                    </h3>

                    @if($class->quizzes->isEmpty())
                        <div class="p-6 rounded-xl bg-[#FAF8F5] border border-[#EBE5DF] text-center text-xs text-[#6E675F]">
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
                                <div class="p-3.5 rounded-xl border border-[#EBE5DF] bg-[#FAF8F5] flex items-center justify-between text-xs font-quicksand">
                                    <div>
                                        <div class="font-bold text-[#1E1B18] font-montserrat">{{ $quiz->title }}</div>
                                        <div class="text-[11px] text-[#6E675F] font-mono">
                                            KKM: {{ $quiz->passing_grade }}% • Total Percobaan: {{ $attempts->count() }} / {{ $quiz->max_attempts }}
                                        </div>
                                    </div>

                                    <div class="text-right">
                                        @if($attempts->isNotEmpty())
                                            <div class="font-bold font-mono text-sm {{ $hasPassed ? 'text-emerald-600' : 'text-rose-600' }}">
                                                Skor: {{ $bestScore }}
                                            </div>
                                            <span class="text-[10px] font-bold uppercase font-montserrat {{ $hasPassed ? 'text-emerald-700' : 'text-rose-700' }}">
                                                {{ $hasPassed ? 'Lulus KKM' : 'Belum Lulus KKM' }}
                                            </span>
                                        @else
                                            <span class="text-[#A8A29E] font-montserrat text-[11px]">Belum Dikerjakan</span>
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
                <div class="bg-white border border-[#EBE5DF] rounded-2xl p-6 shadow-sm space-y-5">
                    <div>
                        <span class="text-xs font-bold uppercase tracking-wider text-[#EA580C] font-montserrat">Keputusan Kelulusan</span>
                        <h3 class="text-lg font-extrabold text-[#1E1B18] font-montserrat mt-0.5">Tindakan Instruktur</h3>
                        <p class="text-xs text-[#6E675F] mt-1 font-quicksand">
                            Evaluasi kelayakan siswa berdasarkan pemenuhan 20 JP dan capaian nilai materi.
                        </p>
                    </div>

                    @if($submission->isApproved())
                        <!-- State: Sudah Lulus -->
                        <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 space-y-3">
                            <div class="flex items-center gap-2 text-emerald-800 font-bold text-xs font-montserrat">
                                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                E-Sertifikat Telah Diterbitkan
                            </div>
                            <div class="text-xs space-y-1 font-mono text-[#6E675F]">
                                <div>No. Registrasi: <strong class="text-[#1E1B18]">{{ $submission->certificate_number }}</strong></div>
                                <div>Waktu Review: {{ $submission->reviewed_at?->translatedFormat('d M Y, H:i') }} WIB</div>
                                <div>Trainer Penilai: {{ $submission->trainer?->name ?? Auth::user()->name }}</div>
                            </div>
                            @if($submission->certificate_number)
                                <a href="{{ route('certificates.verify', $submission->certificate_number) }}" target="_blank" class="w-full py-2.5 text-xs font-bold text-emerald-700 hover:text-emerald-800 bg-emerald-100/60 hover:bg-emerald-100 rounded-xl transition flex items-center justify-center gap-1.5 font-montserrat shadow-xs">
                                    <span>Buka Verifikasi Publik QR &rarr;</span>
                                </a>
                            @endif
                        </div>
                    @else
                        <!-- State: Pending atau Rejected (Bisa re-evaluasi) -->
                        <div class="space-y-3">
                            @if($submission->isRejected())
                                <div class="p-3.5 rounded-xl bg-rose-50 border border-rose-200 text-xs text-[#6E675F] space-y-1">
                                    <span class="font-bold text-rose-700 block font-montserrat">Catatan Remedial Sebelumnya:</span>
                                    <p class="text-[#6E675F] font-quicksand">{{ $submission->trainer_feedback }}</p>
                                </div>
                            @endif

                            <!-- Form Approve -->
                            <form method="POST" action="{{ route('trainer.graduations.approve', $submission) }}" id="approve-form">
                                @csrf
                                <button type="button"
                                        @click="confirmApprove()"
                                        class="w-full py-3 text-xs font-bold text-white bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 rounded-xl shadow-md shadow-emerald-600/10 transition flex items-center justify-center gap-2 font-montserrat">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    <span>Setujui & Terbitkan E-Sertifikat</span>
                                </button>
                            </form>

                            <!-- Tombol Trigger Modal Reject -->
                            <button type="button"
                                    @click="openRejectModal = true"
                                    class="w-full py-3 text-xs font-bold text-rose-600 hover:bg-rose-50 border border-rose-200 rounded-xl transition flex items-center justify-center gap-2 font-montserrat">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                <span>Tolak Kelulusan (Wajib Remedial)</span>
                            </button>
                        </div>
                    @endif
                </div>

                <!-- Info Profil Peserta -->
                <div class="bg-white border border-[#EBE5DF] rounded-2xl p-5 space-y-3 text-xs text-[#6E675F] shadow-sm">
                    <span class="font-bold uppercase tracking-wider text-[#1E1B18] font-montserrat block">Biodata Peserta</span>
                    <div class="space-y-1.5 font-quicksand">
                        <div class="text-[#1E1B18] font-bold text-sm">{{ $student->name }}</div>
                        <div>Email: <strong class="text-[#1E1B18]">{{ $student->email }}</strong></div>
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
             class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/40 backdrop-blur-xs">
            <div class="bg-white border border-[#EBE5DF] rounded-2xl max-w-md w-full p-6 space-y-4 shadow-2xl"
                 @click.away="openRejectModal = false">
                <div>
                    <span class="text-xs font-bold uppercase tracking-wider text-rose-600 font-montserrat">Remedial Pelatihan</span>
                    <h3 class="text-lg font-bold text-[#1E1B18] font-montserrat mt-0.5">Instruksi Perbaikan Peserta</h3>
                    <p class="text-xs text-[#6E675F] mt-1 font-quicksand">
                        Tuliskan alasan penolakan dan materi yang harus diperbaiki/diulang oleh peserta.
                    </p>
                </div>

                <form method="POST" action="{{ route('trainer.graduations.reject', $submission) }}" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-[#1E1B18] font-montserrat mb-1">Catatan & Arahan Trainer *</label>
                        <textarea name="trainer_feedback"
                                  rows="4"
                                  required
                                  placeholder="Contoh: Silakan ulangi kuis modul 2 untuk memenuhi passing grade minimal 75%..."
                                  class="w-full px-3.5 py-2.5 text-xs rounded-xl bg-white border border-[#EBE5DF] text-[#1E1B18] placeholder-[#A8A29E] focus:ring-2 focus:ring-rose-500 font-quicksand shadow-inner"></textarea>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-2">
                        <button type="button"
                                @click="openRejectModal = false"
                                class="px-4 py-2 text-xs font-semibold text-[#6E675F] hover:text-[#1E1B18] bg-[#FAF8F5] border border-[#EBE5DF] rounded-xl transition">
                            Batal
                        </button>
                        <button type="submit"
                                class="px-5 py-2 text-xs font-bold text-white bg-rose-600 hover:bg-rose-700 rounded-xl shadow-md shadow-rose-600/20 transition font-montserrat">
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
                            cancelButtonColor: '#A8A29E',
                            background: '#FFFFFF',
                            color: '#1E1B18'
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
