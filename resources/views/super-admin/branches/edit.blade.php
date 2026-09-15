<x-app-layout>
    <div class="py-8">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Breadcrumb & Title -->
            <div>
                <a href="{{ route('admin.branches.index') }}" class="inline-flex items-center gap-1.5 text-xs font-montserrat font-bold text-[#6E675F] hover:text-[#FF6B00] transition mb-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    <span>Kembali ke Master Cabang</span>
                </a>
                <div class="flex items-center gap-3">
                    <h1 class="font-montserrat font-extrabold text-2xl sm:text-3xl text-[#1E1B18] tracking-tight">
                        Edit Cabang: {{ $branch->name }}
                    </h1>
                    <span class="px-2.5 py-1 rounded-lg text-xs font-mono font-bold bg-[#FAF8F5] border border-[#EBE5DF]">
                        {{ $branch->code }}
                    </span>
                </div>
                <p class="font-quicksand text-sm text-[#6E675F] mt-1">
                    Perbarui profil kantor cabang, alamat, telepon, atau sesuaikan status operasional.
                </p>
            </div>

            <!-- Form Card -->
            <div class="bg-white rounded-2xl border border-[#EBE5DF] shadow-sm p-6 sm:p-8">
                <form method="POST" action="{{ route('admin.branches.update', $branch) }}" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- Row: Nama & Kode -->
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                        <div class="sm:col-span-2">
                            <label for="name" class="block font-montserrat font-bold text-xs uppercase tracking-wider text-[#1E1B18] mb-2">
                                Nama Kantor Cabang <span class="text-[#DC2626]">*</span>
                            </label>
                            <input type="text"
                                   id="name"
                                   name="name"
                                   value="{{ old('name', $branch->name) }}"
                                   required
                                   class="w-full px-4 py-2.5 rounded-xl border @error('name') border-red-500 @else border-[#EBE5DF] @enderror bg-[#FAF8F5] text-sm text-[#1E1B18] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#FF6B00] focus:border-transparent font-quicksand transition">
                            @error('name')
                                <p class="mt-1.5 text-xs text-[#DC2626] font-semibold">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="code" class="block font-montserrat font-bold text-xs uppercase tracking-wider text-[#1E1B18] mb-2">
                                Kode Cabang <span class="text-[#DC2626]">*</span>
                            </label>
                            <input type="text"
                                   id="code"
                                   name="code"
                                   value="{{ old('code', $branch->code) }}"
                                   required
                                   class="w-full px-4 py-2.5 rounded-xl border @error('code') border-red-500 @else border-[#EBE5DF] @enderror bg-[#FAF8F5] text-sm font-mono uppercase text-[#1E1B18] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#FF6B00] focus:border-transparent transition">
                            @error('code')
                                <p class="mt-1.5 text-xs text-[#DC2626] font-semibold">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Row: Kota & Telepon -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <label for="city" class="block font-montserrat font-bold text-xs uppercase tracking-wider text-[#1E1B18] mb-2">
                                Kota / Wilayah <span class="text-[#DC2626]">*</span>
                            </label>
                            <input type="text"
                                   id="city"
                                   name="city"
                                   value="{{ old('city', $branch->city) }}"
                                   required
                                   class="w-full px-4 py-2.5 rounded-xl border @error('city') border-red-500 @else border-[#EBE5DF] @enderror bg-[#FAF8F5] text-sm text-[#1E1B18] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#FF6B00] focus:border-transparent font-quicksand transition">
                            @error('city')
                                <p class="mt-1.5 text-xs text-[#DC2626] font-semibold">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="phone" class="block font-montserrat font-bold text-xs uppercase tracking-wider text-[#1E1B18] mb-2">
                                Nomor Telepon Kantor
                            </label>
                            <input type="text"
                                   id="phone"
                                   name="phone"
                                   value="{{ old('phone', $branch->phone) }}"
                                   placeholder="Contoh: 021-5551234"
                                   class="w-full px-4 py-2.5 rounded-xl border @error('phone') border-red-500 @else border-[#EBE5DF] @enderror bg-[#FAF8F5] text-sm text-[#1E1B18] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#FF6B00] focus:border-transparent font-quicksand transition">
                            @error('phone')
                                <p class="mt-1.5 text-xs text-[#DC2626] font-semibold">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Alamat Lengkap -->
                    <div>
                        <label for="address" class="block font-montserrat font-bold text-xs uppercase tracking-wider text-[#1E1B18] mb-2">
                            Alamat Lengkap Kantor <span class="text-[#DC2626]">*</span>
                        </label>
                        <textarea id="address"
                                  name="address"
                                  rows="3"
                                  required
                                  class="w-full px-4 py-2.5 rounded-xl border @error('address') border-red-500 @else border-[#EBE5DF] @enderror bg-[#FAF8F5] text-sm text-[#1E1B18] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#FF6B00] focus:border-transparent font-quicksand transition">{{ old('address', $branch->address) }}</textarea>
                        @error('address')
                            <p class="mt-1.5 text-xs text-[#DC2626] font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Status Keaktifan Switch -->
                    <div class="pt-2 border-t border-[#FAF8F5]">
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="checkbox"
                                   name="is_active"
                                   value="1"
                                   {{ old('is_active', $branch->is_active) ? 'checked' : '' }}
                                   class="w-5 h-5 text-[#FF6B00] rounded border-[#EBE5DF] focus:ring-[#FF6B00]">
                            <div>
                                <span class="font-montserrat font-bold text-sm text-[#1E1B18]">Status Cabang Aktif</span>
                                <p class="font-quicksand text-xs text-[#6E675F]">Jika dinonaktifkan, cabang ini tidak dapat dipilih saat membuat kelas baru.</p>
                            </div>
                        </label>
                    </div>

                    <!-- Action Buttons -->
                    <div class="pt-4 flex items-center justify-end gap-3 border-t border-[#EBE5DF]">
                        <a href="{{ route('admin.branches.index') }}"
                           class="px-5 py-2.5 rounded-xl font-montserrat font-bold text-sm text-[#6E675F] hover:text-[#1E1B18] hover:bg-[#FAF8F5] transition">
                            Batal
                        </a>
                        <button type="submit"
                                class="px-6 py-2.5 rounded-xl font-montserrat font-bold text-sm text-white bg-gradient-to-r from-[#FF6B00] to-[#E11D48] hover:from-[#EA580C] hover:to-[#DC2626] shadow-md hover:shadow-lg transition-all duration-200 transform hover:-translate-y-0.5">
                            Perbarui Data Cabang
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>
