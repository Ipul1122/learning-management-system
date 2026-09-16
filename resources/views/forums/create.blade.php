<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <div>
                <a href="{{ route('classes.forum.index', $class) }}" class="inline-flex items-center gap-1.5 text-xs text-[#EA580C] hover:text-[#C2410C] font-montserrat font-bold mb-1 transition">
                    &larr; Kembali ke Forum Diskusi Kelas
                </a>
                <h2 class="font-montserrat font-extrabold text-2xl text-[#1E1B18] leading-tight">
                    Mulai Topik Diskusi Baru
                </h2>
                <p class="text-xs text-[#6E675F] font-quicksand mt-0.5">
                    Kelas: <strong class="text-[#1E1B18]">{{ $class->title }}</strong>
                </p>
            </div>
        </div>
    </x-slot>

    <div class="py-6 max-w-3xl space-y-6">
        @if ($errors->any())
            <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs space-y-1 shadow-sm">
                <strong class="font-bold font-montserrat">Mohon perbaiki isian berikut:</strong>
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('classes.forum.store', $class) }}"
              class="bg-white border border-[#EBE5DF] rounded-2xl p-6 sm:p-8 space-y-5 shadow-sm">
            @csrf

            <!-- Judul Topik -->
            <div class="space-y-1.5">
                <label for="title" class="block text-xs font-bold text-[#1E1B18] uppercase tracking-wider font-montserrat">
                    Judul Topik / Pertanyaan <span class="text-rose-500">*</span>
                </label>
                <input type="text" id="title" name="title" value="{{ old('title') }}" required
                       placeholder="Contoh: Kendala saat konfigurasi routing REST API di modul sesi 3"
                       class="w-full px-4 py-3 text-sm rounded-xl bg-white border border-[#EBE5DF] text-[#1E1B18] placeholder-[#A8A29E] focus:ring-2 focus:ring-[#FF6B00] focus:border-transparent font-quicksand">
                <p class="text-[10px] text-[#A8A29E] font-mono">Gunakan judul yang ringkas dan deskriptif (min. 5 karakter).</p>
            </div>

            <!-- Isi Pertanyaan / Bahasan -->
            <div class="space-y-1.5">
                <label for="content" class="block text-xs font-bold text-[#1E1B18] uppercase tracking-wider font-montserrat">
                    Rincian Pertanyaan atau Penjelasan Materi <span class="text-rose-500">*</span>
                </label>
                <textarea id="content" name="content" rows="8" required
                          placeholder="Jelaskan secara detail permasalahan, pesan error, baris kode, atau topik yang ingin didiskusikan..."
                          class="w-full px-4 py-3 text-sm rounded-xl bg-white border border-[#EBE5DF] text-[#1E1B18] placeholder-[#A8A29E] focus:ring-2 focus:ring-[#FF6B00] focus:border-transparent font-quicksand leading-relaxed">{{ old('content') }}</textarea>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center justify-end gap-3 pt-3 border-t border-[#EBE5DF]">
                <a href="{{ route('classes.forum.index', $class) }}" class="px-5 py-2.5 text-xs font-bold text-[#6E675F] hover:text-[#1E1B18] transition">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-[#FF6B00] to-[#E11D48] hover:from-[#EA580C] hover:to-[#DC2626] text-white rounded-xl text-xs font-bold font-montserrat shadow-md shadow-orange-500/10 transition">
                    Terbitkan Topik
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
