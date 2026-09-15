<x-app-layout>
    <div class="py-8" x-data="{ jp: {{ old('jp_duration', $session->jp_duration ?? 2) }} }">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Breadcrumb & Navigation -->
            <div class="flex items-center justify-between">
                <div>
                    <a href="{{ route('cabang.classes.show', $class) }}" class="inline-flex items-center gap-1.5 text-xs font-montserrat font-bold text-[#6E675F] hover:text-[#FF6B00] transition mb-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        <span>Kembali ke Detail Kelas</span>
                    </a>
                    <h1 class="font-montserrat font-extrabold text-2xl sm:text-3xl text-[#1E1B18] tracking-tight">
                        Edit Sesi Pertemuan
                    </h1>
                    <p class="font-quicksand text-xs text-[#6E675F] mt-1">
                        Perbarui rincian topik, durasi jam pelajaran (JP), dan tautan Zoom meeting untuk kelas <strong class="text-[#1E1B18]">{{ $class->title }}</strong>.
                    </p>
                </div>
            </div>

            <!-- Form Card -->
            <div class="bg-white rounded-3xl border border-[#EBE5DF] shadow-sm overflow-hidden">
                <div class="p-6 border-b border-[#EBE5DF] bg-[#FAF8F5]/60 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <span class="w-10 h-10 rounded-2xl bg-[#FFF7ED] text-[#FF6B00] flex items-center justify-center font-montserrat font-bold text-sm">
                            #{{ $session->session_order }}
                        </span>
                        <div>
                            <h2 class="font-montserrat font-bold text-base text-[#1E1B18]">{{ $session->title }}</h2>
                            <p class="text-xs text-[#6E675F]">Dibuat oleh {{ $session->creator?->name ?? 'System' }} • Terakhir diperbarui {{ $session->updated_at->diffForHumans() }}</p>
                        </div>
                    </div>
                </div>

                <form method="POST" action="{{ route('cabang.sessions.update', [$class, $session]) }}" class="p-6 sm:p-8 space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- Topik & Urutan Sesi -->
                    <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                        <div class="sm:col-span-3">
                            <label for="title" class="block font-montserrat font-bold text-xs uppercase tracking-wider text-[#1E1B18] mb-1.5">
                                Topik Materi Pertemuan <span class="text-[#DC2626]">*</span>
                            </label>
                            <input type="text"
                                   id="title"
                                   name="title"
                                   value="{{ old('title', $session->title) }}"
                                   required
                                   placeholder="Contoh: Modul 2 - Deep Dive State Management"
                                   class="w-full px-4 py-3 rounded-2xl border border-[#EBE5DF] bg-[#FAF8F5] text-sm text-[#1E1B18] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#FF6B00] font-quicksand transition">
                            @error('title')
                                <p class="text-xs text-[#DC2626] mt-1 font-semibold">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="session_order" class="block font-montserrat font-bold text-xs uppercase tracking-wider text-[#1E1B18] mb-1.5">
                                Sesi Ke <span class="text-[#DC2626]">*</span>
                            </label>
                            <input type="number"
                                   id="session_order"
                                   name="session_order"
                                   value="{{ old('session_order', $session->session_order) }}"
                                   min="1"
                                   required
                                   class="w-full px-4 py-3 rounded-2xl border border-[#EBE5DF] bg-[#FAF8F5] text-sm text-[#1E1B18] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#FF6B00] font-mono transition">
                            @error('session_order')
                                <p class="text-xs text-[#DC2626] mt-1 font-semibold">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Durasi JP & Tanggal Waktu -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="jp_duration" class="block font-montserrat font-bold text-xs uppercase tracking-wider text-[#1E1B18] mb-1.5">
                                Durasi JP (1 JP = 45 Menit) <span class="text-[#DC2626]">*</span>
                            </label>
                            <div class="relative">
                                <input type="number"
                                       id="jp_duration"
                                       name="jp_duration"
                                       x-model.number="jp"
                                       min="1"
                                       max="12"
                                       required
                                       class="w-full px-4 py-3 rounded-2xl border border-[#EBE5DF] bg-[#FAF8F5] text-sm text-[#1E1B18] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#FF6B00] font-mono transition">
                                <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none">
                                    <span class="text-xs font-bold font-montserrat text-[#6E675F]" x-text="(jp * 45) + ' Menit'"></span>
                                </div>
                            </div>
                            <p class="text-[11px] text-[#6E675F] mt-1">Standar kurikulum kelulusan membutuhkan total 20 JP (900 Menit).</p>
                            @error('jp_duration')
                                <p class="text-xs text-[#DC2626] mt-1 font-semibold">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="session_date" class="block font-montserrat font-bold text-xs uppercase tracking-wider text-[#1E1B18] mb-1.5">
                                Jadwal Tanggal & Waktu Pelaksanaan <span class="text-[#DC2626]">*</span>
                            </label>
                            <input type="datetime-local"
                                   id="session_date"
                                   name="session_date"
                                   value="{{ old('session_date', $session->session_date ? $session->session_date->format('Y-m-d\TH:i') : '') }}"
                                   required
                                   class="w-full px-4 py-3 rounded-2xl border border-[#EBE5DF] bg-[#FAF8F5] text-sm text-[#1E1B18] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#FF6B00] font-mono transition">
                            @error('session_date')
                                <p class="text-xs text-[#DC2626] mt-1 font-semibold">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Integrasi Zoom Meeting -->
                    <div class="p-5 rounded-2xl bg-[#F0F9FF] border border-[#BAE6FD] space-y-4">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded-xl bg-[#0284C7] text-white flex items-center justify-center">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-montserrat font-bold text-sm text-[#0369A1]">Integrasi Zoom Meeting</h3>
                                <p class="text-[11px] text-[#0369A1]/80">Tautan ini akan langsung dapat diakses peserta & trainer saat kelas berlangsung.</p>
                            </div>
                        </div>

                        <div>
                            <label for="zoom_url" class="block font-montserrat font-bold text-xs uppercase tracking-wider text-[#0369A1] mb-1.5">
                                URL Zoom Meeting (Join URL)
                            </label>
                            <input type="url"
                                   id="zoom_url"
                                   name="zoom_url"
                                   value="{{ old('zoom_url', $session->zoom_url) }}"
                                   placeholder="https://zoom.us/j/1234567890?pwd=..."
                                   class="w-full px-4 py-2.5 rounded-xl border border-[#BAE6FD] bg-white text-sm text-[#1E1B18] focus:outline-none focus:ring-2 focus:ring-[#0284C7] font-mono transition">
                            @error('zoom_url')
                                <p class="text-xs text-[#DC2626] mt-1 font-semibold">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="zoom_meeting_id" class="block font-montserrat font-bold text-xs uppercase tracking-wider text-[#0369A1] mb-1.5">
                                    Meeting ID (Opsional)
                                </label>
                                <input type="text"
                                       id="zoom_meeting_id"
                                       name="zoom_meeting_id"
                                       value="{{ old('zoom_meeting_id', $session->zoom_meeting_id) }}"
                                       placeholder="Contoh: 849 1234 5678"
                                       class="w-full px-4 py-2.5 rounded-xl border border-[#BAE6FD] bg-white text-sm text-[#1E1B18] focus:outline-none focus:ring-2 focus:ring-[#0284C7] font-mono transition">
                                @error('zoom_meeting_id')
                                    <p class="text-xs text-[#DC2626] mt-1 font-semibold">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="zoom_passcode" class="block font-montserrat font-bold text-xs uppercase tracking-wider text-[#0369A1] mb-1.5">
                                    Passcode (Opsional)
                                </label>
                                <input type="text"
                                       id="zoom_passcode"
                                       name="zoom_passcode"
                                       value="{{ old('zoom_passcode', $session->zoom_passcode) }}"
                                       placeholder="Contoh: 123456"
                                       class="w-full px-4 py-2.5 rounded-xl border border-[#BAE6FD] bg-white text-sm text-[#1E1B18] focus:outline-none focus:ring-2 focus:ring-[#0284C7] font-mono transition">
                                @error('zoom_passcode')
                                    <p class="text-xs text-[#DC2626] mt-1 font-semibold">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Catatan / Deskripsi Materi -->
                    <div>
                        <label for="description" class="block font-montserrat font-bold text-xs uppercase tracking-wider text-[#1E1B18] mb-1.5">
                            Catatan / Deskripsi Agenda (Opsional)
                        </label>
                        <textarea id="description"
                                  name="description"
                                  rows="3"
                                  placeholder="Tuliskan catatan persiapan materi atau instruksi khusus untuk sesi ini..."
                                  class="w-full px-4 py-3 rounded-2xl border border-[#EBE5DF] bg-[#FAF8F5] text-sm text-[#1E1B18] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#FF6B00] font-quicksand transition">{{ old('description', $session->description) }}</textarea>
                        @error('description')
                            <p class="text-xs text-[#DC2626] mt-1 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Action Buttons -->
                    <div class="pt-4 border-t border-[#EBE5DF] flex items-center justify-end gap-3">
                        <a href="{{ route('cabang.classes.show', $class) }}"
                           class="px-6 py-2.5 rounded-xl font-montserrat font-bold text-xs text-[#6E675F] hover:text-[#1E1B18] hover:bg-[#FAF8F5] border border-[#EBE5DF] transition">
                            Batal
                        </a>
                        <button type="submit"
                                class="px-6 py-2.5 rounded-xl font-montserrat font-bold text-xs text-white bg-gradient-to-r from-[#FF6B00] to-[#E11D48] hover:from-[#EA580C] hover:to-[#DC2626] shadow-md hover:shadow-lg transition transform hover:-translate-y-0.5">
                            Simpan Perubahan Sesi
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>
