<x-app-layout>
    <div class="py-8">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Breadcrumb & Header Title -->
            <div>
                <a href="{{ route('admin.news.index') }}" class="inline-flex items-center gap-1.5 text-xs font-montserrat font-bold text-[#6E675F] hover:text-[#FF6B00] transition mb-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    <span>Kembali ke Daftar Berita</span>
                </a>
                <h1 class="font-montserrat font-extrabold text-2xl sm:text-3xl text-[#1E1B18] tracking-tight">
                    {{ isset($post) ? 'Edit Berita & Pengumuman' : 'Tulis Berita & Pengumuman Baru' }}
                </h1>
                <p class="font-quicksand text-sm text-[#6E675F] mt-1">
                    {{ isset($post) ? 'Perbarui isi pengumuman, materi publikasi, atau cakupan wilayah penerima.' : 'Tulis dan publikasikan informasi resmi, berita kegiatan, atau pengumuman penting untuk peserta dan cabang.' }}
                </p>
            </div>
        @if ($errors->any())
            <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs space-y-1 shadow-sm">
                <strong class="font-bold font-montserrat">Terdapat kesalahan pengisian data:</strong>
                <ul class="list-disc list-inside font-quicksand">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST"
              action="{{ isset($post) ? route('admin.news.update', $post) : route('admin.news.store') }}"
              enctype="multipart/form-data"
              class="bg-white border border-[#EBE5DF] rounded-2xl p-6 sm:p-8 space-y-6 shadow-sm">
            @csrf
            @if(isset($post))
                @method('PUT')
            @endif

            <!-- Judul Berita -->
            <div class="space-y-1.5">
                <label for="title" class="block text-xs font-bold text-[#1E1B18] uppercase tracking-wider font-montserrat">
                    Judul Berita / Pengumuman <span class="text-rose-500">*</span>
                </label>
                <input type="text" id="title" name="title"
                       value="{{ old('title', $post->title ?? '') }}" required
                       placeholder="Contoh: Jadwal Ujian Sertifikasi Gelombang 3 Resmi Dibuka"
                       class="w-full px-4 py-3 text-sm rounded-xl bg-white border border-[#EBE5DF] text-[#1E1B18] placeholder-[#A8A29E] focus:ring-2 focus:ring-[#FF6B00] font-quicksand">
            </div>

            <!-- Baris 2: Kategori & Cakupan Wilayah Cabang -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div class="space-y-1.5">
                    <label for="category" class="block text-xs font-bold text-[#1E1B18] uppercase tracking-wider font-montserrat">
                        Kategori <span class="text-rose-500">*</span>
                    </label>
                    <select id="category" name="category" required
                            class="w-full px-4 py-2.5 text-xs rounded-xl bg-white border border-[#EBE5DF] text-[#1E1B18] focus:ring-2 focus:ring-[#FF6B00] font-quicksand">
                        @foreach($categories as $cat)
                            <option value="{{ $cat }}" {{ old('category', $post->category ?? '') === $cat ? 'selected' : '' }}>
                                {{ $cat }}
                            </option>
                        @endforeach
                    </select>
                </div>

                @if(auth()->user()->hasRole('super-admin'))
                    <div class="space-y-1.5">
                        <label for="branch_id" class="block text-xs font-bold text-[#1E1B18] uppercase tracking-wider font-montserrat">
                            Cakupan Wilayah / Cabang
                        </label>
                        <select id="branch_id" name="branch_id"
                                class="w-full px-4 py-2.5 text-xs rounded-xl bg-white border border-[#EBE5DF] text-[#1E1B18] focus:ring-2 focus:ring-[#FF6B00] font-quicksand">
                            <option value="">Nasional (Semua Cabang)</option>
                            @foreach($branches as $b)
                                <option value="{{ $b->id }}" {{ old('branch_id', $post->branch_id ?? '') == $b->id ? 'selected' : '' }}>
                                    {{ $b->name }}
                                </option>
                            @endforeach
                        </select>
                        <p class="text-[10px] text-[#A8A29E] font-quicksand">Biarkan kosong jika berita ditujukan untuk skala nasional.</p>
                    </div>
                @else
                    <div class="space-y-1.5">
                        <label class="block text-xs font-bold text-[#1E1B18] uppercase tracking-wider font-montserrat">
                            Cabang Penerbit
                        </label>
                        <div class="px-4 py-2.5 rounded-xl bg-[#FAF8F5] border border-[#EBE5DF] text-xs text-[#1E1B18] font-mono">
                            {{ auth()->user()->branch->name ?? 'Cabang Anda' }}
                        </div>
                    </div>
                @endif
            </div>

            <!-- Upload Thumbnail Gambar Sampul -->
            <div class="space-y-2">
                <label for="thumbnail" class="block text-xs font-bold text-[#1E1B18] uppercase tracking-wider font-montserrat">
                    Gambar Sampul / Thumbnail
                </label>
                @if(isset($post) && $post->thumbnail)
                    <div class="flex items-center gap-4 mb-2">
                        <img src="{{ $post->getThumbnailUrl() }}" alt="Current thumbnail" class="w-24 h-16 object-cover rounded-xl border border-[#EBE5DF] shadow-xs">
                        <span class="text-xs text-[#6E675F] font-quicksand">Pilih file baru untuk mengganti gambar sampul ini.</span>
                    </div>
                @endif
                <input type="file" id="thumbnail" name="thumbnail" accept="image/*"
                       class="w-full px-4 py-2 text-xs rounded-xl bg-[#FAF8F5] border border-[#EBE5DF] text-[#1E1B18] file:mr-4 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:font-montserrat file:bg-[#FF6B00] file:text-white hover:file:bg-[#EA580C] cursor-pointer">
                <p class="text-[10px] text-[#A8A29E] font-mono">Format: JPG, PNG, WEBP (Maksimal 2 MB).</p>
            </div>

            <!-- Isi Konten Artikel (WordPress-style Rich Editor) -->
            <div class="space-y-1.5">
                <div class="flex items-center justify-between">
                    <label for="content" class="block text-xs font-bold text-[#1E1B18] uppercase tracking-wider font-montserrat">
                        Konten Artikel Berita / Pengumuman <span class="text-rose-500">*</span>
                    </label>
                    <span class="text-[11px] text-[#6E675F] font-quicksand hidden sm:inline">Mendukung format tebal, miring, judul, daftar, tautan & kode HTML</span>
                </div>
                <x-rich-editor id="content"
                               name="content"
                               :value="old('content', $post->content ?? '')"
                               placeholder="Tuliskan isi pengumuman secara rinci, instruksi kegiatan, atau materi edukatif di sini..." />
                @error('content')
                    <p class="text-xs text-rose-600 font-montserrat font-bold mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Status Publikasi -->
            <div class="flex items-center gap-3 pt-2">
                <input type="hidden" name="is_published" value="0">
                <input type="checkbox" id="is_published" name="is_published" value="1"
                       {{ old('is_published', $post->is_published ?? true) ? 'checked' : '' }}
                       class="w-4 h-4 rounded text-[#FF6B00] bg-white border-[#EBE5DF] focus:ring-[#FF6B00]">
                <label for="is_published" class="text-xs font-bold text-[#1E1B18] font-montserrat">
                    Publikasikan Langsung ke Feed Peserta
                </label>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-[#EBE5DF]">
                <a href="{{ route('admin.news.index') }}" class="px-5 py-2.5 text-xs font-bold text-[#6E675F] hover:text-[#1E1B18] transition font-montserrat">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-[#FF6B00] to-[#E11D48] hover:from-[#EA580C] hover:to-[#DC2626] text-white rounded-xl text-xs font-bold font-montserrat shadow-md shadow-orange-500/10 transition">
                    {{ isset($post) ? 'Simpan Perubahan' : 'Terbitkan Berita' }}
                </button>
            </div>
        </form>
    </div>
</div>
</x-app-layout>
