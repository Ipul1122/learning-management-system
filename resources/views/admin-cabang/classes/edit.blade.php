<x-app-layout>
    <div class="py-8" x-data="classEditForm()">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Breadcrumb & Title -->
            <div>
                <a href="{{ route('cabang.classes.show', $class) }}" class="inline-flex items-center gap-1.5 text-xs font-montserrat font-bold text-[#6E675F] hover:text-[#FF6B00] transition mb-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    <span>Kembali ke Detail Kelas</span>
                </a>
                <h1 class="font-montserrat font-extrabold text-2xl sm:text-3xl text-[#1E1B18] tracking-tight">
                    Edit Kelas: {{ $class->title }}
                </h1>
                <p class="font-quicksand text-sm text-[#6E675F] mt-1">
                    Perbarui informasi kelas, instruktur penanggung jawab, atau sesuaikan status operasional.
                </p>
            </div>

            <!-- Form Card -->
            <div class="bg-white rounded-2xl border border-[#EBE5DF] shadow-sm p-6 sm:p-8">
                <form method="POST" action="{{ route('cabang.classes.update', $class) }}" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- Judul Kelas -->
                    <div>
                        <label for="title" class="block font-montserrat font-bold text-xs uppercase tracking-wider text-[#1E1B18] mb-2">
                            Judul Kelas Pelatihan <span class="text-[#DC2626]">*</span>
                        </label>
                        <input type="text"
                               id="title"
                               name="title"
                               value="{{ old('title', $class->title) }}"
                               required
                               class="w-full px-4 py-2.5 rounded-xl border @error('title') border-red-500 @else border-[#EBE5DF] @enderror bg-[#FAF8F5] text-sm text-[#1E1B18] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#FF6B00] focus:border-transparent font-quicksand transition">
                        @error('title')
                            <p class="mt-1.5 text-xs text-[#DC2626] font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Row: Trainer & Status -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <label for="trainer_id" class="block font-montserrat font-bold text-xs uppercase tracking-wider text-[#1E1B18] mb-2">
                                Instruktur / Trainer Pengampu <span class="text-[#DC2626]">*</span>
                            </label>
                            <select id="trainer_id"
                                    name="trainer_id"
                                    required
                                    class="w-full px-4 py-2.5 rounded-xl border @error('trainer_id') border-red-500 @else border-[#EBE5DF] @enderror bg-[#FAF8F5] text-sm text-[#1E1B18] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#FF6B00] focus:border-transparent font-quicksand transition">
                                @foreach($trainers as $trainer)
                                    <option value="{{ $trainer->id }}" {{ old('trainer_id', $class->trainer_id) == $trainer->id ? 'selected' : '' }}>
                                        {{ $trainer->name }} ({{ $trainer->email }})
                                    </option>
                                @endforeach
                            </select>
                            @error('trainer_id')
                                <p class="mt-1.5 text-xs text-[#DC2626] font-semibold">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="status" class="block font-montserrat font-bold text-xs uppercase tracking-wider text-[#1E1B18] mb-2">
                                Status Operasional <span class="text-[#DC2626]">*</span>
                            </label>
                            <select id="status"
                                    name="status"
                                    required
                                    class="w-full px-4 py-2.5 rounded-xl border @error('status') border-red-500 @else border-[#EBE5DF] @enderror bg-[#FAF8F5] text-sm text-[#1E1B18] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#FF6B00] focus:border-transparent font-quicksand transition">
                                <option value="draft" {{ old('status', $class->status) === 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="open" {{ old('status', $class->status) === 'open' ? 'selected' : '' }}>Buka Pendaftaran</option>
                                <option value="ongoing" {{ old('status', $class->status) === 'ongoing' ? 'selected' : '' }}>Sedang Berjalan</option>
                                <option value="completed" {{ old('status', $class->status) === 'completed' ? 'selected' : '' }}>Selesai</option>
                                <option value="cancelled" {{ old('status', $class->status) === 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                            </select>
                        </div>
                    </div>

                    <!-- Pilihan Tipe / Format Kelas -->
                    <div>
                        <label class="block font-montserrat font-bold text-xs uppercase tracking-wider text-[#1E1B18] mb-2">
                            Format Pelaksanaan Kelas <span class="text-[#DC2626]">*</span>
                        </label>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                            <label class="p-4 rounded-2xl border cursor-pointer transition flex items-start gap-3"
                                   :class="type === 'offline' ? 'bg-[#FFF7ED] border-[#FF6B00] shadow-sm' : 'bg-[#FAF8F5] border-[#EBE5DF] hover:bg-white'">
                                <input type="radio"
                                       name="type"
                                       value="offline"
                                       x-model="type"
                                       class="mt-1 text-[#FF6B00] focus:ring-[#FF6B00]">
                                <div>
                                    <div class="font-montserrat font-bold text-sm text-[#1E1B18]">Offline Fisik</div>
                                    <div class="text-xs text-[#6E675F] mt-0.5">Maksimal 40 orang</div>
                                </div>
                            </label>

                            <label class="p-4 rounded-2xl border cursor-pointer transition flex items-start gap-3"
                                   :class="type === 'online' ? 'bg-[#FFF7ED] border-[#FF6B00] shadow-sm' : 'bg-[#FAF8F5] border-[#EBE5DF] hover:bg-white'">
                                <input type="radio"
                                       name="type"
                                       value="online"
                                       x-model="type"
                                       class="mt-1 text-[#FF6B00] focus:ring-[#FF6B00]">
                                <div>
                                    <div class="font-montserrat font-bold text-sm text-[#1E1B18]">Online Daring</div>
                                    <div class="text-xs text-[#6E675F] mt-0.5">Ratusan peserta via Zoom</div>
                                </div>
                            </label>

                            <label class="p-4 rounded-2xl border cursor-pointer transition flex items-start gap-3"
                                   :class="type === 'hybrid' ? 'bg-[#FFF7ED] border-[#FF6B00] shadow-sm' : 'bg-[#FAF8F5] border-[#EBE5DF] hover:bg-white'">
                                <input type="radio"
                                       name="type"
                                       value="hybrid"
                                       x-model="type"
                                       class="mt-1 text-[#FF6B00] focus:ring-[#FF6B00]">
                                <div>
                                    <div class="font-montserrat font-bold text-sm text-[#1E1B18]">Hybrid (Dual)</div>
                                    <div class="text-xs text-[#6E675F] mt-0.5">Fisik (maks 40) + Daring</div>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Dynamic Capacity Input Fields -->
                    <div class="p-4 rounded-2xl bg-[#FAF8F5] border border-[#EBE5DF] space-y-4">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div x-show="type === 'offline' || type === 'hybrid'" x-transition>
                                <label for="offline_capacity" class="block font-quicksand text-xs font-semibold text-[#1E1B18] mb-1">
                                    Kuota Kursi Fisik di Kelas (Maksimal 40) <span class="text-[#DC2626]">*</span>
                                </label>
                                <input type="number"
                                       id="offline_capacity"
                                       name="offline_capacity"
                                       min="1"
                                       max="40"
                                       x-model="offlineCapacity"
                                       required
                                       class="w-full px-4 py-2.5 rounded-xl border @error('offline_capacity') border-red-500 @else border-[#EBE5DF] @enderror bg-white text-sm text-[#1E1B18] focus:outline-none focus:ring-2 focus:ring-[#FF6B00] font-quicksand transition">
                            </div>

                            <div x-show="type === 'online' || type === 'hybrid'" x-transition>
                                <label for="online_capacity" class="block font-quicksand text-xs font-semibold text-[#1E1B18] mb-1">
                                    Kuota Peserta Daring Zoom (Ratusan) <span class="text-[#DC2626]">*</span>
                                </label>
                                <input type="number"
                                       id="online_capacity"
                                       name="online_capacity"
                                       min="1"
                                       max="5000"
                                       x-model="onlineCapacity"
                                       required
                                       class="w-full px-4 py-2.5 rounded-xl border @error('online_capacity') border-red-500 @else border-[#EBE5DF] @enderror bg-white text-sm text-[#1E1B18] focus:outline-none focus:ring-2 focus:ring-[#FF6B00] font-quicksand transition">
                            </div>
                        </div>
                    </div>

                    <!-- Row: Tanggal Mulai, Selesai, & Target 20 JP -->
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                        <div>
                            <label for="start_date" class="block font-montserrat font-bold text-xs uppercase tracking-wider text-[#1E1B18] mb-2">
                                Tanggal Mulai <span class="text-[#DC2626]">*</span>
                            </label>
                            <input type="date"
                                   id="start_date"
                                   name="start_date"
                                   value="{{ old('start_date', $class->start_date->format('Y-m-d')) }}"
                                   required
                                   class="w-full px-4 py-2.5 rounded-xl border border-[#EBE5DF] bg-[#FAF8F5] text-sm text-[#1E1B18] focus:bg-white font-quicksand transition">
                        </div>

                        <div>
                            <label for="end_date" class="block font-montserrat font-bold text-xs uppercase tracking-wider text-[#1E1B18] mb-2">
                                Tanggal Selesai <span class="text-[#DC2626]">*</span>
                            </label>
                            <input type="date"
                                   id="end_date"
                                   name="end_date"
                                   value="{{ old('end_date', $class->end_date->format('Y-m-d')) }}"
                                   required
                                   class="w-full px-4 py-2.5 rounded-xl border border-[#EBE5DF] bg-[#FAF8F5] text-sm text-[#1E1B18] focus:bg-white font-quicksand transition">
                        </div>

                        <div>
                            <label for="required_jp" class="block font-montserrat font-bold text-xs uppercase tracking-wider text-[#1E1B18] mb-2">
                                Target Kelulusan (JP) <span class="text-[#DC2626]">*</span>
                            </label>
                            <input type="number"
                                   id="required_jp"
                                   name="required_jp"
                                   value="{{ old('required_jp', $class->required_jp) }}"
                                   min="1"
                                   max="100"
                                   required
                                   class="w-full px-4 py-2.5 rounded-xl border border-[#EBE5DF] bg-[#FAF8F5] text-sm font-bold text-[#FF6B00] font-mono transition">
                        </div>
                    </div>

                    <!-- Deskripsi Kelas -->
                    <div>
                        <label for="description" class="block font-montserrat font-bold text-xs uppercase tracking-wider text-[#1E1B18] mb-2">
                            Silabus / Deskripsi Pembelajaran
                        </label>
                        <textarea id="description"
                                  name="description"
                                  rows="3"
                                  class="w-full px-4 py-2.5 rounded-xl border border-[#EBE5DF] bg-[#FAF8F5] text-sm text-[#1E1B18] focus:bg-white font-quicksand transition">{{ old('description', $class->description) }}</textarea>
                    </div>

                    <!-- Action Buttons -->
                    <div class="pt-4 flex items-center justify-end gap-3 border-t border-[#EBE5DF]">
                        <a href="{{ route('cabang.classes.show', $class) }}"
                           class="px-5 py-2.5 rounded-xl font-montserrat font-bold text-sm text-[#6E675F] hover:text-[#1E1B18] hover:bg-[#FAF8F5] transition">
                            Batal
                        </a>
                        <button type="submit"
                                class="px-6 py-2.5 rounded-xl font-montserrat font-bold text-sm text-white bg-gradient-to-r from-[#FF6B00] to-[#E11D48] hover:from-[#EA580C] hover:to-[#DC2626] shadow-md hover:shadow-lg transition-all duration-200 transform hover:-translate-y-0.5">
                            Perbarui Data Kelas
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>

    @push('scripts')
        <script>
            function classEditForm() {
                return {
                    type: '{{ old('type', $class->type) }}',
                    offlineCapacity: {{ old('offline_capacity', $class->offline_capacity) }},
                    onlineCapacity: {{ old('online_capacity', $class->online_capacity) }},
                }
            }
        </script>
    @endpush
</x-app-layout>
