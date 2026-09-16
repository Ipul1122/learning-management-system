<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="text-2xl font-extrabold text-[#1E1B18] tracking-tight flex items-center gap-3 font-montserrat">
                    <span class="p-2.5 rounded-xl bg-[#FFF7ED] border border-[#FED7AA] text-[#EA580C] shadow-xs">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </span>
                    Verifikasi Kelulusan 20 JP & Sertifikat
                </h1>
                <p class="text-xs text-[#6E675F] mt-1 font-quicksand">
                    Tinjau pemenuhan 20 jam pelajaran (900 menit) dan hasil kuis peserta sebelum menerbitkan E-Sertifikat resmi.
                </p>
            </div>
            <div class="flex items-center gap-2">
                @if($pendingCount > 0)
                    <span class="px-3.5 py-1.5 rounded-xl text-xs font-bold font-montserrat bg-[#FFF7ED] text-[#EA580C] border border-[#FED7AA] flex items-center gap-2 animate-pulse shadow-sm">
                        <span class="w-2 h-2 rounded-full bg-[#EA580C]"></span>
                        {{ $pendingCount }} Pengajuan Menunggu Evaluasi
                    </span>
                @else
                    <span class="px-3.5 py-1.5 rounded-xl text-xs font-medium font-montserrat bg-white text-[#6E675F] border border-[#EBE5DF] shadow-sm">
                        Semua Pengajuan Telah Ditinjau
                    </span>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-6 space-y-6">
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

        <!-- Filter & Search Bar -->
        <div class="bg-white border border-[#EBE5DF] rounded-2xl p-4 shadow-sm">
            <form method="GET" action="{{ route('trainer.graduations.index') }}" class="flex flex-col sm:flex-row gap-3">
                <div class="flex-1 relative">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau email peserta..."
                           class="w-full pl-10 pr-4 py-2.5 text-xs rounded-xl bg-[#FAF8F5] border border-[#EBE5DF] text-[#1E1B18] placeholder-[#A8A29E] focus:ring-2 focus:ring-[#FF6B00] font-quicksand">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-[#A8A29E]">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                </div>

                <div class="sm:w-56">
                    <select name="class_id" onchange="this.form.submit()"
                            class="w-full py-2.5 px-3 text-xs rounded-xl bg-[#FAF8F5] border border-[#EBE5DF] text-[#1E1B18] focus:ring-2 focus:ring-[#FF6B00] font-quicksand">
                        <option value="">-- Semua Kelas Pelatihan --</option>
                        @foreach($trainerClasses as $c)
                            <option value="{{ $c->id }}" {{ request('class_id') == $c->id ? 'selected' : '' }}>
                                {{ $c->title }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="sm:w-44">
                    <select name="status" onchange="this.form.submit()"
                            class="w-full py-2.5 px-3 text-xs rounded-xl bg-[#FAF8F5] border border-[#EBE5DF] text-[#1E1B18] focus:ring-2 focus:ring-[#FF6B00] font-quicksand">
                        <option value="">-- Semua Status --</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Menunggu Review</option>
                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Disetujui (Lulus)</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Ditolak (Remedial)</option>
                    </select>
                </div>

                <button type="submit" class="px-5 py-2.5 text-xs font-bold text-white bg-gradient-to-r from-[#FF6B00] to-[#E11D48] hover:from-[#EA580C] hover:to-[#DC2626] rounded-xl shadow-md shadow-orange-500/10 transition font-montserrat">
                    Filter
                </button>
                @if(request()->hasAny(['search', 'class_id', 'status']))
                    <a href="{{ route('trainer.graduations.index') }}" class="px-3.5 py-2.5 text-xs font-bold text-rose-600 bg-rose-50 hover:bg-rose-100 border border-rose-200 rounded-xl transition text-center font-montserrat">
                        Reset
                    </a>
                @endif
            </form>
        </div>

        <!-- Tabel Antrean Pengajuan Kelulusan -->
        <div class="bg-white border border-[#EBE5DF] rounded-2xl overflow-hidden shadow-sm">
            @if($submissions->isEmpty())
                <div class="p-12 text-center text-[#6E675F] font-quicksand">
                    <div class="w-16 h-16 rounded-full bg-[#FFF7ED] text-[#EA580C] border border-[#FED7AA] flex items-center justify-center mx-auto mb-4 shadow-xs">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="text-base font-bold text-[#1E1B18] font-montserrat">Tidak Ada Antrean Pengajuan Kelulusan</h3>
                    <p class="text-xs text-[#6E675F] mt-1 max-w-sm mx-auto">
                        Peserta yang telah mencapai 20 JP (900 menit) belajar dan kuis akan muncul secara otomatis di daftar ini.
                    </p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs text-[#1E1B18]">
                        <thead class="bg-[#FAF8F5] text-[11px] uppercase tracking-wider text-[#A8A29E] font-montserrat border-b border-[#EBE5DF]">
                            <tr>
                                <th class="py-3.5 px-4">Peserta Pelatihan</th>
                                <th class="py-3.5 px-4">Kelas & Cabang</th>
                                <th class="py-3.5 px-4 text-center">Akumulasi JP</th>
                                <th class="py-3.5 px-4 text-center">Rata-rata Kuis</th>
                                <th class="py-3.5 px-4 text-center">Status Kelulusan</th>
                                <th class="py-3.5 px-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#EBE5DF]">
                            @foreach($submissions as $sub)
                                @php
                                    $enrollment = $sub->enrollment;
                                    $student = $enrollment?->user;
                                    $class = $enrollment?->trainingClass;
                                @endphp
                                <tr class="hover:bg-[#FAF8F5]/60 transition">
                                    <td class="py-3.5 px-4">
                                        <div class="font-bold text-[#1E1B18] font-montserrat text-sm">{{ $student?->name ?? 'Peserta' }}</div>
                                        <div class="text-[11px] text-[#6E675F] font-mono">{{ $student?->email }}</div>
                                    </td>
                                    <td class="py-3.5 px-4">
                                        <div class="font-semibold text-[#1E1B18] font-montserrat">{{ $class?->title }}</div>
                                        <div class="flex items-center gap-2 mt-0.5 text-[11px] text-[#6E675F]">
                                            <span class="text-[#EA580C] font-semibold">{{ $class?->branch?->name }}</span>
                                            <span>• Mode: <strong class="uppercase text-[#1E1B18] font-mono">{{ $enrollment?->attendance_mode }}</strong></span>
                                        </div>
                                    </td>
                                    <td class="py-3.5 px-4 text-center">
                                        <div class="font-bold text-emerald-600 font-mono text-sm">
                                            {{ $sub->total_jp_earned }} <span class="text-[10px] text-[#6E675F]">/ 20.0 JP</span>
                                        </div>
                                        <span class="text-[10px] text-[#6E675F] font-mono">
                                            {{ $enrollment?->accumulated_minutes }} Menit
                                        </span>
                                    </td>
                                    <td class="py-3.5 px-4 text-center font-mono">
                                        <span class="font-bold text-[#1E1B18] text-sm">{{ $sub->avg_quiz_score }}%</span>
                                    </td>
                                    <td class="py-3.5 px-4 text-center">
                                        @if($sub->isPending())
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold font-montserrat bg-[#FFF7ED] text-[#EA580C] border border-[#FED7AA] animate-pulse">
                                                <span class="w-1.5 h-1.5 rounded-full bg-[#EA580C]"></span>
                                                Menunggu Review
                                            </span>
                                        @elseif($sub->isApproved())
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold font-montserrat bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                <svg class="w-3 h-3 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                                Lulus (Disetujui)
                                            </span>
                                            @if($sub->certificate_number)
                                                <div class="text-[10px] text-[#6E675F] font-mono mt-0.5">{{ $sub->certificate_number }}</div>
                                            @endif
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold font-montserrat bg-rose-50 text-rose-700 border border-rose-200">
                                                <svg class="w-3 h-3 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                                Ditolak (Remedial)
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-3.5 px-4 text-right">
                                        <a href="{{ route('trainer.graduations.show', $sub) }}" class="px-3.5 py-1.5 rounded-xl text-xs font-bold text-[#1E1B18] bg-white hover:bg-[#FAF8F5] border border-[#EBE5DF] shadow-sm transition inline-flex items-center gap-1.5 font-montserrat">
                                            <span>Tinjau Performa</span>
                                            <svg class="w-3.5 h-3.5 text-[#6E675F]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="p-4 border-t border-[#EBE5DF]">
                    {{ $submissions->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
