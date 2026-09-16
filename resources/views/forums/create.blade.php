<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <div>
                <a href="{{ route('classes.forum.index', $class) }}" class="inline-flex items-center gap-1.5 text-xs text-orange-400 hover:text-orange-300 font-mono font-bold mb-1">
                    &larr; Kembali ke Forum Diskusi Kelas
                </a>
                <h2 class="font-montserrat font-extrabold text-2xl text-white leading-tight">
                    Mulai Topik Diskusi Baru
                </h2>
                <p class="text-xs text-slate-400 font-quicksand mt-0.5">
                    Kelas: <strong class="text-slate-200">{{ $class->title }}</strong>
                </p>
            </div>
        </div>
    </x-slot>

    <div class="py-6 max-w-3xl space-y-6">
        @if ($errors->any())
            <div class="p-4 rounded-xl bg-rose-500/10 border border-rose-500/30 text-rose-400 text-xs space-y-1">
                <strong class="font-bold font-montserrat">Mohon perbaiki isian berikut:</strong>
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('classes.forum.store', $class) }}"
              class="bg-slate-900/60 border border-slate-800 rounded-3xl p-6 sm:p-8 space-y-5 shadow-xl">
            @csrf

            <!-- Judul Topik -->
            <div class="space-y-1.5">
                <label for="title" class="block text-xs font-bold text-slate-300 uppercase tracking-wider font-montserrat">
                    Judul Topik / Pertanyaan <span class="text-rose-500">*</span>
                </label>
                <input type="text" id="title" name="title" value="{{ old('title') }}" required
                       placeholder="Contoh: Kendala saat konfigurasi routing REST API di modul sesi 3"
                       class="w-full px-4 py-3 text-sm rounded-xl bg-slate-800/80 border border-slate-700 text-white placeholder-slate-500 focus:ring-2 focus:ring-orange-500 font-quicksand">
                <p class="text-[10px] text-slate-500 font-mono">Gunakan judul yang ringkas dan deskriptif (min. 5 karakter).</p>
            </div>

            <!-- Isi Pertanyaan / Bahasan -->
            <div class="space-y-1.5">
                <label for="content" class="block text-xs font-bold text-slate-300 uppercase tracking-wider font-montserrat">
                    Rincian Pertanyaan atau Penjelasan Materi <span class="text-rose-500">*</span>
                </label>
                <textarea id="content" name="content" rows="8" required
                          placeholder="Jelaskan secara detail permasalahan, pesan error, baris kode, atau topik yang ingin didiskusikan..."
                          class="w-full px-4 py-3 text-sm rounded-xl bg-slate-800/80 border border-slate-700 text-white placeholder-slate-500 focus:ring-2 focus:ring-orange-500 font-quicksand leading-relaxed">{{ old('content') }}</textarea>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-800">
                <a href="{{ route('classes.forum.index', $class) }}" class="px-5 py-2.5 text-xs font-bold text-slate-400 hover:text-white transition">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-orange-500 to-rose-600 hover:from-orange-600 hover:to-rose-700 text-white rounded-xl text-xs font-bold font-montserrat shadow-lg shadow-orange-500/20 transition">
                    Terbitkan Topik
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
