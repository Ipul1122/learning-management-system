<x-app-layout>
    <div class="py-8">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Breadcrumb & Title -->
            <div>
                <a href="{{ route('admin.branches.index') }}" class="inline-flex items-center gap-1.5 text-xs font-montserrat font-bold text-[#6E675F] hover:text-[#FF6B00] transition mb-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    <span>Kembali ke Master Cabang</span>
                </a>
                <h1 class="font-montserrat font-extrabold text-2xl sm:text-3xl text-[#1E1B18] tracking-tight">
                    Tambah Cabang Baru
                </h1>
                <p class="font-quicksand text-sm text-[#6E675F] mt-1">
                    Daftarkan kantor cabang operasional baru sekaligus akun administrator pengelola dalam satu tampilan terpadu.
                </p>
            </div>

            <!-- Form Multi-Column -->
            <form method="POST" action="{{ route('admin.branches.store') }}" class="space-y-6">
                @csrf

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 items-start">
                    <!-- KOLOM 1: Informasi Kantor Cabang -->
                    <div class="bg-white rounded-2xl border border-[#EBE5DF] shadow-sm p-6 sm:p-7 space-y-5">
                        <div class="flex items-center gap-3 pb-4 border-b border-[#EBE5DF]">
                            <div class="w-10 h-10 rounded-xl bg-[#FFF5ED] text-[#FF6B00] flex items-center justify-center shrink-0 border border-[#FF6B00]/20 shadow-xs">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                            </div>
                            <div>
                                <h2 class="font-montserrat font-bold text-base text-[#1E1B18]">Informasi Kantor Cabang</h2>
                                <p class="font-quicksand text-xs text-[#6E675F]">Identitas, kode cabang, dan alamat operasional.</p>
                            </div>
                        </div>

                        <!-- Nama Kantor Cabang -->
                        <div>
                            <label for="name" class="block font-montserrat font-bold text-xs uppercase tracking-wider text-[#1E1B18] mb-2">
                                Nama Kantor Cabang <span class="text-[#DC2626]">*</span>
                            </label>
                            <input type="text"
                                   id="name"
                                   name="name"
                                   list="existing-branches-datalist"
                                   value="{{ old('name') }}"
                                   placeholder="Contoh: Cabang Jakarta Pusat"
                                   required
                                   autocomplete="off"
                                   class="w-full px-4 py-2.5 rounded-xl border @error('name') border-red-500 @else border-[#EBE5DF] @enderror bg-[#FAF8F5] text-sm text-[#1E1B18] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#FF6B00] focus:border-transparent font-quicksand transition">

                            <!-- Datalist Saran Cabang yang Sudah Ada -->
                            <datalist id="existing-branches-datalist">
                                @foreach($existingBranches as $b)
                                    <option value="{{ is_array($b) ? $b['name'] : $b->name }}">{{ is_array($b) ? $b['code'] : $b->code }}</option>
                                @endforeach
                            </datalist>

                            <!-- Hidden ID Cabang Terpilih (Untuk mode reuse) -->
                            <input type="hidden" id="existing_branch_id" name="existing_branch_id" value="{{ old('existing_branch_id', request('branch_id')) }}">

                            <!-- Banner Cabang Terdaftar Sedang Digunakan -->
                            <div id="reused-branch-banner" class="hidden mt-2.5 p-3 rounded-xl bg-[#ECFDF5] border border-[#A7F3D0] text-xs text-[#065F46] font-semibold flex items-center justify-between gap-2">
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-[#10B981] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span>Menggunakan cabang terdaftar: <strong id="reused-branch-title" class="font-bold text-[#047857]"></strong></span>
                                </div>
                                <button type="button" id="btn-cancel-reuse" class="text-xs text-[#DC2626] hover:underline font-montserrat font-bold">
                                    Batal & Ganti
                                </button>
                            </div>

                            <!-- Opsi Radio Button: Gunakan Kembali Cabang yang Telah Diinput -->
                            <div id="branch-reuse-container" class="hidden mt-3 p-4 rounded-xl bg-[#FFF5ED] border border-[#FF6B00]/40 shadow-xs space-y-3 transition-all duration-200">
                                <div class="flex items-start gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-[#FF6B00]/10 text-[#FF6B00] flex items-center justify-center shrink-0 mt-0.5">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <h4 class="font-montserrat font-bold text-xs sm:text-sm text-[#1E1B18]">
                                            <span id="reuse-branch-name" class="text-[#FF6B00]">[Nama Cabang]</span> telah diinput, mau digunakan lagi?
                                        </h4>
                                        <p id="reuse-branch-admin-info" class="font-quicksand text-xs text-[#6E675F] mt-0.5">
                                            Cabang ini sudah terdaftar. Anda dapat menggunakan cabang ini kembali untuk menambahkan akun Admin baru (1 cabang bisa dibuat ramai-ramai admin).
                                        </p>
                                    </div>
                                </div>

                                <!-- Radio Options -->
                                <div class="space-y-2 pt-1 font-quicksand">
                                    <label class="flex items-start gap-3 p-3 rounded-xl border border-[#EBE5DF] bg-white hover:border-[#FF6B00] cursor-pointer transition shadow-2xs">
                                        <input type="radio" id="radio-reuse-yes" name="reuse_branch_choice" value="reuse" {{ old('reuse_branch_choice') === 'reuse' || old('existing_branch_id') || request('branch_id') ? 'checked' : '' }} class="mt-0.5 text-[#FF6B00] focus:ring-[#FF6B00]">
                                        <div class="text-xs">
                                            <span class="font-montserrat font-bold text-[#1E1B18]">Ya, gunakan cabang ini untuk menambah Admin baru</span>
                                            <p class="text-[#6E675F] mt-0.5">Data kantor cabang akan otomatis tersinkronkan, dan Anda dapat mendaftarkan akun Admin baru di kolom sebelah kanan (1 cabang bisa dibuat ramai-ramai admin).</p>
                                        </div>
                                    </label>
                                    <label class="flex items-start gap-3 p-3 rounded-xl border border-[#EBE5DF] bg-white hover:border-[#DC2626] cursor-pointer transition shadow-2xs">
                                        <input type="radio" id="radio-reuse-no" name="reuse_branch_choice" value="new" {{ old('reuse_branch_choice') === 'new' ? 'checked' : '' }} class="mt-0.5 text-[#DC2626] focus:ring-[#DC2626]">
                                        <div class="text-xs">
                                            <span class="font-montserrat font-bold text-[#1E1B18]">Tidak, saya ingin mendaftarkan nama cabang baru yang berbeda</span>
                                            <p class="text-[#6E675F] mt-0.5">Ganti nama kantor cabang agar tidak terjadi duplikasi pendaftaran cabang baru.</p>
                                        </div>
                                    </label>
                                </div>
                            </div>

                            <!-- Alert Duplikasi Biasa (ketika radio Tidak dipilih) -->
                            <div id="name-duplicate-alert" class="hidden mt-2 p-2.5 rounded-xl bg-red-50 border border-red-200 text-xs text-[#DC2626] font-semibold flex items-center gap-2">
                                <svg class="w-4 h-4 shrink-0 text-[#DC2626]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                                <span id="name-duplicate-msg">Cabang ini sudah pernah diinput sebelumnya dan tidak dapat didaftarkan kembali.</span>
                            </div>
                            @error('name')
                                <p class="mt-1.5 text-xs text-[#DC2626] font-semibold">{{ $message }}</p>
                            @enderror
                            @error('existing_branch_id')
                                <p class="mt-1.5 text-xs text-[#DC2626] font-semibold">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Kode Cabang -->
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <label for="code" class="block font-montserrat font-bold text-xs uppercase tracking-wider text-[#1E1B18]">
                                    Kode Cabang <span class="text-[#DC2626]">*</span>
                                </label>
                                <span id="code-auto-badge" class="inline-flex items-center gap-1 text-[11px] font-medium text-[#FF6B00] bg-[#FFF5ED] px-2 py-0.5 rounded-md border border-[#FF6B00]/20 transition-all">
                                    <span id="code-status-text">Otomatis</span>
                                </span>
                            </div>
                            <div class="relative">
                                <input type="text"
                                       id="code"
                                       name="code"
                                       value="{{ old('code') }}"
                                       placeholder="Contoh: CBG-JKT"
                                       required
                                       class="w-full px-4 py-2.5 rounded-xl border @error('code') border-red-500 @else border-[#EBE5DF] @enderror bg-[#FAF8F5] text-sm font-mono uppercase text-[#1E1B18] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#FF6B00] focus:border-transparent transition pr-9">
                                <button type="button"
                                        id="btn-regenerate-code"
                                        title="Sinkronkan kembali dengan nama cabang"
                                        class="hidden absolute right-2.5 top-1/2 -translate-y-1/2 p-1 text-[#6E675F] hover:text-[#FF6B00] rounded-lg hover:bg-white transition"
                                        aria-label="Sinkronkan ulang kode cabang">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                    </svg>
                                </button>
                            </div>
                            <div id="code-duplicate-alert" class="hidden mt-2 p-2.5 rounded-xl bg-red-50 border border-red-200 text-xs text-[#DC2626] font-semibold flex items-center gap-2">
                                <svg class="w-4 h-4 shrink-0 text-[#DC2626]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                                <span id="code-duplicate-msg">Kode cabang ini sudah terdaftar pada cabang lain.</span>
                            </div>
                            <p class="mt-1.5 text-xs text-[#6E675F] font-quicksand flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 text-[#FF6B00] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span>Terisi otomatis dari nama cabang, dapat disesuaikan.</span>
                            </p>
                            @error('code')
                                <p class="mt-1.5 text-xs text-[#DC2626] font-semibold">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Kota & Nomor Telepon Kantor -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="city" class="block font-montserrat font-bold text-xs uppercase tracking-wider text-[#1E1B18] mb-2">
                                    Kota / Wilayah <span class="text-[#DC2626]">*</span>
                                </label>
                                <input type="text"
                                       id="city"
                                       name="city"
                                       value="{{ old('city') }}"
                                       placeholder="Contoh: Jakarta Pusat"
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
                                       value="{{ old('phone') }}"
                                       placeholder="Contoh: 0812-3456-7890"
                                       maxlength="19"
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
                                      placeholder="Tuliskan nama jalan, gedung, nomor kavling, dan kode pos..."
                                      class="w-full px-4 py-2.5 rounded-xl border @error('address') border-red-500 @else border-[#EBE5DF] @enderror bg-[#FAF8F5] text-sm text-[#1E1B18] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#FF6B00] focus:border-transparent font-quicksand transition">{{ old('address') }}</textarea>
                            @error('address')
                                <p class="mt-1.5 text-xs text-[#DC2626] font-semibold">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Status Keaktifan Switch -->
                        <div class="pt-3 border-t border-[#EBE5DF]">
                            <label class="flex items-center gap-3 cursor-pointer p-3 rounded-xl bg-[#FAF8F5] border border-[#EBE5DF] hover:border-[#FF6B00]/40 transition">
                                <input type="checkbox"
                                       id="is_active"
                                       name="is_active"
                                       value="1"
                                       {{ old('is_active', true) ? 'checked' : '' }}
                                       class="w-5 h-5 text-[#FF6B00] rounded border-[#EBE5DF] focus:ring-[#FF6B00]">
                                <div>
                                    <span class="font-montserrat font-bold text-sm text-[#1E1B18]">Aktifkan Cabang Sekarang</span>
                                    <p class="font-quicksand text-xs text-[#6E675F]">Cabang aktif dapat langsung digunakan untuk kelas & jadwal.</p>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- KOLOM 2: Akun Administrator Cabang -->
                    <div class="bg-white rounded-2xl border border-[#EBE5DF] shadow-sm p-6 sm:p-7 space-y-5">
                        <div class="flex items-center justify-between gap-3 pb-4 border-b border-[#EBE5DF]">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-[#FF6B00] to-[#E11D48] text-white flex items-center justify-center shrink-0 shadow-sm">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                                <div>
                                    <h2 class="font-montserrat font-bold text-base text-[#1E1B18] flex items-center gap-2">
                                        <span>Akun Administrator Cabang</span>
                                        <span class="text-[10px] uppercase tracking-wider font-semibold text-[#FF6B00] bg-[#FFF0E6] px-2 py-0.5 rounded-full border border-[#FF6B00]/30">Pengelola</span>
                                    </h2>
                                    <p class="font-quicksand text-xs text-[#6E675F]">Akun login pengelola cabang bersangkutan.</p>
                                </div>
                            </div>
                            <label class="inline-flex items-center gap-2 cursor-pointer shrink-0 bg-[#FAF8F5] px-3 py-1.5 rounded-xl border border-[#EBE5DF] hover:border-[#FF6B00]/40 transition">
                                <input type="checkbox"
                                       id="create_admin_account"
                                       name="create_admin_account"
                                       value="1"
                                       {{ old('create_admin_account', true) ? 'checked' : '' }}
                                       class="w-4 h-4 text-[#FF6B00] rounded border-[#EBE5DF] focus:ring-[#FF6B00]">
                                <span class="font-montserrat font-bold text-xs text-[#1E1B18]">Buat Akun</span>
                            </label>
                        </div>

                        <!-- Form Fields Admin Cabang -->
                        <div id="admin-fields-container" class="space-y-4 pt-1 transition-all duration-300">
                            <!-- Nama Lengkap Admin -->
                            <div>
                                <label for="admin_name" class="block font-montserrat font-bold text-xs uppercase tracking-wider text-[#1E1B18] mb-2">
                                    Nama Lengkap Admin <span class="text-[#DC2626]">*</span>
                                </label>
                                <input type="text"
                                       id="admin_name"
                                       name="admin_name"
                                       value="{{ old('admin_name') }}"
                                       placeholder="Contoh: Budi Santoso"
                                       required
                                       class="w-full px-4 py-2.5 rounded-xl border @error('admin_name') border-red-500 @else border-[#EBE5DF] @enderror bg-[#FAF8F5] text-sm text-[#1E1B18] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#FF6B00] focus:border-transparent font-quicksand transition">
                                @error('admin_name')
                                    <p class="mt-1.5 text-xs text-[#DC2626] font-semibold">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Alamat Email Login -->
                            <div>
                                <label for="admin_email" class="block font-montserrat font-bold text-xs uppercase tracking-wider text-[#1E1B18] mb-2">
                                    Alamat Email Login <span class="text-[#DC2626]">*</span>
                                </label>
                                <input type="email"
                                       id="admin_email"
                                       name="admin_email"
                                       value="{{ old('admin_email') }}"
                                       placeholder="Contoh: budi.admin@lms.test"
                                       required
                                       class="w-full px-4 py-2.5 rounded-xl border @error('admin_email') border-red-500 @else border-[#EBE5DF] @enderror bg-[#FAF8F5] text-sm text-[#1E1B18] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#FF6B00] focus:border-transparent font-quicksand transition">
                                @error('admin_email')
                                    <p class="mt-1.5 text-xs text-[#DC2626] font-semibold">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Nomor Telepon / WA & Status Admin -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label for="admin_phone_number" class="block font-montserrat font-bold text-xs uppercase tracking-wider text-[#1E1B18] mb-2">
                                        Nomor HP / WhatsApp Admin
                                    </label>
                                    <input type="text"
                                           id="admin_phone_number"
                                           name="admin_phone_number"
                                           value="{{ old('admin_phone_number') }}"
                                           placeholder="Contoh: 0812-3456-7890"
                                           maxlength="19"
                                           class="w-full px-4 py-2.5 rounded-xl border @error('admin_phone_number') border-red-500 @else border-[#EBE5DF] @enderror bg-[#FAF8F5] text-sm text-[#1E1B18] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#FF6B00] focus:border-transparent font-quicksand transition">
                                    @error('admin_phone_number')
                                        <p class="mt-1.5 text-xs text-[#DC2626] font-semibold">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block font-montserrat font-bold text-xs uppercase tracking-wider text-[#1E1B18] mb-2">
                                        Status Akun <span class="text-[#DC2626]">*</span>
                                    </label>
                                    <div class="flex gap-4 pt-2">
                                        <label class="flex items-center gap-2 cursor-pointer">
                                            <input type="radio" name="admin_status" value="active" {{ old('admin_status', 'active') === 'active' ? 'checked' : '' }} class="text-[#FF6B00] focus:ring-[#FF6B00]">
                                            <span class="text-sm font-quicksand text-[#1E1B18]">Aktif</span>
                                        </label>
                                        <label class="flex items-center gap-2 cursor-pointer">
                                            <input type="radio" name="admin_status" value="inactive" {{ old('admin_status') === 'inactive' ? 'checked' : '' }} class="text-[#FF6B00] focus:ring-[#FF6B00]">
                                            <span class="text-sm font-quicksand text-[#1E1B18]">Nonaktif</span>
                                        </label>
                                    </div>
                                    @error('admin_status')
                                        <p class="mt-1.5 text-xs text-[#DC2626] font-semibold">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Kata Sandi & Konfirmasi Password -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label for="admin_password" class="block font-montserrat font-bold text-xs uppercase tracking-wider text-[#1E1B18] mb-2">
                                        Kata Sandi Login <span class="text-[#DC2626]">*</span>
                                    </label>
                                    <input type="password"
                                           id="admin_password"
                                           name="admin_password"
                                           required
                                           placeholder="Min. 8 karakter"
                                           class="w-full px-4 py-2.5 rounded-xl border @error('admin_password') border-red-500 @else border-[#EBE5DF] @enderror bg-[#FAF8F5] text-sm text-[#1E1B18] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#FF6B00] focus:border-transparent font-quicksand transition">
                                    @error('admin_password')
                                        <p class="mt-1.5 text-xs text-[#DC2626] font-semibold">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="admin_password_confirmation" class="block font-montserrat font-bold text-xs uppercase tracking-wider text-[#1E1B18] mb-2">
                                        Konfirmasi Kata Sandi <span class="text-[#DC2626]">*</span>
                                    </label>
                                    <input type="password"
                                           id="admin_password_confirmation"
                                           name="admin_password_confirmation"
                                           required
                                           placeholder="Ulangi kata sandi"
                                           class="w-full px-4 py-2.5 rounded-xl border border-[#EBE5DF] bg-[#FAF8F5] text-sm text-[#1E1B18] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#FF6B00] focus:border-transparent font-quicksand transition">
                                </div>
                            </div>
                        </div>

                        <!-- Notice ketika pembuatan admin dinonaktifkan -->
                        <div id="admin-disabled-notice" class="hidden p-8 rounded-2xl border-2 border-dashed border-[#EBE5DF] bg-[#FAF8F5] text-center space-y-3">
                            <div class="w-12 h-12 rounded-full bg-[#EBE5DF]/60 text-[#6E675F] flex items-center justify-center mx-auto">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-montserrat font-bold text-sm text-[#1E1B18]">Pembuatan Akun Admin Dilewati</h3>
                                <p class="font-quicksand text-xs text-[#6E675F] mt-1 max-w-sm mx-auto">
                                    Cabang akan didaftarkan tanpa akun admin baru. Anda tetap dapat menugaskan admin di lain waktu melalui menu Master Cabang.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Bar Bawah Terpadu -->
                <div class="bg-white rounded-2xl border border-[#EBE5DF] shadow-sm p-4 sm:px-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div class="flex items-center gap-2 text-xs text-[#6E675F] font-quicksand">
                        <svg class="w-4 h-4 text-[#FF6B00] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>Data kantor cabang dan akun administrator akan disimpan secara terpadu.</span>
                    </div>
                    <div class="flex flex-wrap items-center justify-end gap-3 shrink-0">
                        <a href="{{ route('admin.branches.index') }}"
                           class="px-5 py-2.5 rounded-xl font-montserrat font-bold text-sm text-[#6E675F] hover:text-[#1E1B18] hover:bg-[#FAF8F5] transition">
                            Batal
                        </a>
                        <button type="submit"
                                name="action"
                                value="save_and_add_another"
                                id="btn-submit-add-another"
                                class="px-4 sm:px-5 py-2.5 rounded-xl font-montserrat font-bold text-xs sm:text-sm text-[#FF6B00] bg-[#FFF5ED] hover:bg-[#FFEADA] border border-[#FF6B00]/30 transition shadow-xs flex items-center gap-1.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            <span>Simpan & Tambah Admin Lain</span>
                        </button>
                        <button type="submit"
                                name="action"
                                value="save"
                                id="btn-submit-branch"
                                class="px-6 py-2.5 rounded-xl font-montserrat font-bold text-sm text-white bg-gradient-to-r from-[#FF6B00] to-[#E11D48] hover:from-[#EA580C] hover:to-[#DC2626] shadow-md hover:shadow-lg transition-all duration-200 transform hover:-translate-y-0.5">
                            Simpan Cabang & Akun Admin
                        </button>
                    </div>
                </div>
            </form>

        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const nameInput = document.getElementById('name');
            const codeInput = document.getElementById('code');
            const codeBadge = document.getElementById('code-auto-badge');
            const codeStatusText = document.getElementById('code-status-text');
            const btnRegenerate = document.getElementById('btn-regenerate-code');

            if (!nameInput || !codeInput) return;

            const cityMap = @json(\App\Models\Branch::CITY_CODE_MAP);
            const existingBranches = @json($existingBranches ?? \App\Models\Branch::select('id', 'name', 'code')->get());

            const nameDuplicateAlert = document.getElementById('name-duplicate-alert');
            const nameDuplicateMsg = document.getElementById('name-duplicate-msg');
            const codeDuplicateAlert = document.getElementById('code-duplicate-alert');
            const codeDuplicateMsg = document.getElementById('code-duplicate-msg');
            const btnSubmit = document.getElementById('btn-submit-branch');
            const btnSubmitAddAnother = document.getElementById('btn-submit-add-another');

            // Akun Admin Cabang Elements
            const createAdminCheckbox = document.getElementById('create_admin_account');
            const adminFieldsContainer = document.getElementById('admin-fields-container');
            const adminDisabledNotice = document.getElementById('admin-disabled-notice');
            const adminRequiredInputs = [
                document.getElementById('admin_name'),
                document.getElementById('admin_email'),
                document.getElementById('admin_password'),
                document.getElementById('admin_password_confirmation')
            ].filter(Boolean);

            function updateAdminSectionState() {
                if (!createAdminCheckbox || !adminFieldsContainer) return;
                const isEnabled = createAdminCheckbox.checked;

                if (isEnabled) {
                    adminFieldsContainer.classList.remove('hidden');
                    if (adminDisabledNotice) adminDisabledNotice.classList.add('hidden');
                    adminRequiredInputs.forEach(input => input.setAttribute('required', 'required'));
                } else {
                    adminFieldsContainer.classList.add('hidden');
                    if (adminDisabledNotice) adminDisabledNotice.classList.remove('hidden');
                    adminRequiredInputs.forEach(input => input.removeAttribute('required'));
                }
            }

            if (createAdminCheckbox) {
                createAdminCheckbox.addEventListener('change', updateAdminSectionState);
                updateAdminSectionState();
            }

            function setSubmitButtonsState(enabled, primaryText) {
                if (btnSubmit) {
                    btnSubmit.disabled = !enabled;
                    if (!enabled) {
                        btnSubmit.classList.add('opacity-50', 'cursor-not-allowed', 'pointer-events-none');
                    } else {
                        btnSubmit.classList.remove('opacity-50', 'cursor-not-allowed', 'pointer-events-none');
                    }
                    if (primaryText) btnSubmit.textContent = primaryText;
                }
                if (btnSubmitAddAnother) {
                    btnSubmitAddAnother.disabled = !enabled;
                    if (!enabled) {
                        btnSubmitAddAnother.classList.add('opacity-50', 'cursor-not-allowed', 'pointer-events-none');
                    } else {
                        btnSubmitAddAnother.classList.remove('opacity-50', 'cursor-not-allowed', 'pointer-events-none');
                    }
                }
            }

            function normalizeName(str) {
                return (str || '').toLowerCase().trim().replace(/\s+/g, ' ');
            }

            function stripPrefix(str) {
                let s = normalizeName(str);
                return s.replace(/^(kantor\s+cabang|kantor\s+operasional|kantor|cabang|branch|kcp|kc|pusat\s+pelatihan)\s+/i, '').trim();
            }

            const branchReuseContainer = document.getElementById('branch-reuse-container');
            const reuseBranchName = document.getElementById('reuse-branch-name');
            const reuseBranchAdminInfo = document.getElementById('reuse-branch-admin-info');
            const radioReuseYes = document.getElementById('radio-reuse-yes');
            const radioReuseNo = document.getElementById('radio-reuse-no');
            const existingBranchIdInput = document.getElementById('existing_branch_id');
            const reusedBranchBanner = document.getElementById('reused-branch-banner');
            const reusedBranchTitle = document.getElementById('reused-branch-title');
            const btnCancelReuse = document.getElementById('btn-cancel-reuse');

            const cityInput = document.getElementById('city');
            const phoneInput = document.getElementById('phone');
            const addressInput = document.getElementById('address');
            const isActiveInput = document.getElementById('is_active');

            function checkBranchDuplicates() {
                const rawName = nameInput.value.trim();
                const rawCode = codeInput.value.trim().toUpperCase();

                let matchedBranch = null;
                if (rawName) {
                    const normName = normalizeName(rawName);
                    const coreName = stripPrefix(rawName);

                    matchedBranch = existingBranches.find(b => {
                        const bNorm = normalizeName(b.name);
                        const bCore = stripPrefix(b.name);
                        return (normName === bNorm) || (coreName !== '' && bCore !== '' && coreName === bCore);
                    });
                }

                let codeMatch = null;
                if (rawCode) {
                    codeMatch = existingBranches.find(b => (b.code || '').trim().toUpperCase() === rawCode);
                }

                // Jika cabang ditemukan (duplikat nama terdeteksi)
                if (matchedBranch) {
                    nameInput.classList.add('border-[#FF6B00]');
                    if (reuseBranchName) reuseBranchName.textContent = matchedBranch.name;

                    if (reuseBranchAdminInfo) {
                        const adminCount = matchedBranch.admin_count || 0;
                        if (adminCount > 0) {
                            reuseBranchAdminInfo.textContent = `Cabang ini sudah terdaftar dan saat ini memiliki ${adminCount} admin (${matchedBranch.admin_names}). Anda dapat menggunakan cabang ini kembali untuk menambahkan akun admin baru (1 cabang bisa dibuat ramai-ramai admin).`;
                        } else {
                            reuseBranchAdminInfo.textContent = `Cabang ini sudah terdaftar. Anda dapat menggunakan cabang ini kembali untuk menambahkan akun Admin penanggung jawab baru.`;
                        }
                    }

                    if (branchReuseContainer) branchReuseContainer.classList.remove('hidden');

                    const isReuse = radioReuseYes && radioReuseYes.checked;
                    const isNo = radioReuseNo && radioReuseNo.checked;

                    if (isReuse) {
                        if (existingBranchIdInput) existingBranchIdInput.value = matchedBranch.id;
                        if (nameDuplicateAlert) nameDuplicateAlert.classList.add('hidden');
                        if (codeDuplicateAlert) codeDuplicateAlert.classList.add('hidden');

                        // Sinkronkan data kantor cabang yang ada
                        codeInput.value = matchedBranch.code;
                        if (cityInput && matchedBranch.city) cityInput.value = matchedBranch.city;
                        if (phoneInput && matchedBranch.phone) phoneInput.value = matchedBranch.phone;
                        if (addressInput && matchedBranch.address) addressInput.value = matchedBranch.address;
                        if (isActiveInput) isActiveInput.checked = Boolean(matchedBranch.is_active);

                        if (reusedBranchTitle) reusedBranchTitle.textContent = `${matchedBranch.name} (${matchedBranch.code})`;
                        if (reusedBranchBanner) reusedBranchBanner.classList.remove('hidden');

                        // Pastikan akun admin aktif di kolom kanan
                        if (createAdminCheckbox && !createAdminCheckbox.checked) {
                            createAdminCheckbox.checked = true;
                            updateAdminSectionState();
                        }

                        setSubmitButtonsState(true, `Tambah Admin untuk ${matchedBranch.name}`);
                        return;
                    } else if (isNo) {
                        // Radio 'Tidak' dipilih
                        if (existingBranchIdInput) existingBranchIdInput.value = '';
                        if (reusedBranchBanner) reusedBranchBanner.classList.add('hidden');
                        if (nameDuplicateMsg) nameDuplicateMsg.textContent = `Cabang '${matchedBranch.name}' sudah pernah diinput sebelumnya dan tidak dapat didaftarkan kembali. Silakan ganti nama kantor cabang.`;
                        if (nameDuplicateAlert) nameDuplicateAlert.classList.remove('hidden');

                        setSubmitButtonsState(false, 'Simpan Cabang & Akun Admin');
                        return;
                    } else {
                        // Belum memilih Ya atau Tidak: pertanyaan tampil, submit ditahan sementara
                        if (existingBranchIdInput) existingBranchIdInput.value = '';
                        if (reusedBranchBanner) reusedBranchBanner.classList.add('hidden');
                        if (nameDuplicateAlert) nameDuplicateAlert.classList.add('hidden');

                        setSubmitButtonsState(false, 'Pilih Opsi Cabang Terdaftar');
                        return;
                    }
                } else {
                    // Tidak ada duplikasi nama cabang
                    if (existingBranchIdInput) existingBranchIdInput.value = '';
                    if (branchReuseContainer) branchReuseContainer.classList.add('hidden');
                    if (reusedBranchBanner) reusedBranchBanner.classList.add('hidden');
                    if (nameDuplicateAlert) nameDuplicateAlert.classList.add('hidden');
                    nameInput.classList.remove('border-red-500', 'bg-red-50/20', 'border-[#FF6B00]');

                    // Cek duplikasi kode cabang
                    if (codeMatch) {
                        if (codeDuplicateMsg) codeDuplicateMsg.textContent = `Kode cabang '${codeMatch.code}' sudah digunakan oleh '${codeMatch.name}'.`;
                        if (codeDuplicateAlert) codeDuplicateAlert.classList.remove('hidden');
                        codeInput.classList.add('border-red-500', 'bg-red-50/20');
                        setSubmitButtonsState(false);
                    } else {
                        if (codeDuplicateAlert) codeDuplicateAlert.classList.add('hidden');
                        codeInput.classList.remove('border-red-500', 'bg-red-50/20');
                        const defaultText = (createAdminCheckbox && createAdminCheckbox.checked) ? 'Simpan Cabang & Akun Admin' : 'Simpan Kantor Cabang';
                        setSubmitButtonsState(true, defaultText);
                    }
                }
            }

            function generateBranchCode(name) {
                if (!name || !name.trim()) return '';

                let clean = name.toLowerCase().trim();
                // Hapus awalan umum kantor cabang
                clean = clean.replace(/^(kantor\s+cabang|kantor\s+operasional|kantor|cabang|branch|kcp|kc|pusat\s+pelatihan)\s+/i, '').trim();

                if (!clean) return '';

                // Cek kesesuaian kamus singkatan kota
                for (const [city, code] of Object.entries(cityMap)) {
                    if (clean === city || clean.startsWith(city + ' ')) {
                        return 'CBG-' + code;
                    }
                }

                // Jika tidak ada di kamus, buat dari kata yang ada
                const words = clean.replace(/[^a-z0-9\s]/gi, ' ').split(/\s+/).filter(Boolean);
                if (words.length === 0) return '';

                if (words.length === 1) {
                    return ('CBG-' + words[0].toUpperCase()).substring(0, 20);
                }

                const combined = 'CBG-' + words.join('-').toUpperCase();
                if (combined.length <= 20) {
                    return combined;
                }

                // Jika melebihi 20 karakter, gunakan inisial
                const initials = words.map(w => w[0]).join('').toUpperCase();
                return ('CBG-' + initials).substring(0, 20);
            }

            const initialCode = codeInput.value.trim();
            const initialName = nameInput.value.trim();
            let isManual = Boolean(initialCode && (!initialName || initialCode !== generateBranchCode(initialName)));

            function updateUIState() {
                if (!codeBadge || !codeStatusText || !btnRegenerate) return;

                if (isManual) {
                    codeBadge.className = 'inline-flex items-center gap-1 text-[11px] font-medium text-[#6E675F] bg-[#FAF8F5] px-2 py-0.5 rounded-md border border-[#EBE5DF] transition-all';
                    codeStatusText.textContent = 'Manual';
                    btnRegenerate.classList.remove('hidden');
                } else {
                    codeBadge.className = 'inline-flex items-center gap-1 text-[11px] font-medium text-[#FF6B00] bg-[#FFF5ED] px-2 py-0.5 rounded-md border border-[#FF6B00]/20 transition-all';
                    codeStatusText.textContent = 'Otomatis';
                    btnRegenerate.classList.add('hidden');
                }
            }

            nameInput.addEventListener('input', () => {
                if (!isManual) {
                    codeInput.value = generateBranchCode(nameInput.value);
                }
                checkBranchDuplicates();
            });

            codeInput.addEventListener('input', () => {
                const cursorStart = codeInput.selectionStart;
                const cursorEnd = codeInput.selectionEnd;
                codeInput.value = codeInput.value.toUpperCase();
                codeInput.setSelectionRange(cursorStart, cursorEnd);

                if (!codeInput.value.trim()) {
                    isManual = false;
                    codeInput.value = generateBranchCode(nameInput.value);
                } else {
                    isManual = true;
                }
                updateUIState();
                checkBranchDuplicates();
            });

            if (btnRegenerate) {
                btnRegenerate.addEventListener('click', () => {
                    isManual = false;
                    codeInput.value = generateBranchCode(nameInput.value);
                    codeInput.focus();
                    updateUIState();
                    checkBranchDuplicates();
                });
            }

            if (!isManual && nameInput.value && !codeInput.value) {
                codeInput.value = generateBranchCode(nameInput.value);
            }

            updateUIState();
            checkBranchDuplicates();

            if (radioReuseYes) {
                radioReuseYes.addEventListener('change', () => {
                    checkBranchDuplicates();
                });
            }

            if (radioReuseNo) {
                radioReuseNo.addEventListener('change', () => {
                    checkBranchDuplicates();
                });
            }

            if (btnCancelReuse) {
                btnCancelReuse.addEventListener('click', () => {
                    if (radioReuseNo) radioReuseNo.checked = true;
                    checkBranchDuplicates();
                    nameInput.focus();
                });
            }

            // Inisialisasi jika ada old existing_branch_id atau query string branch_id
            if (existingBranchIdInput && existingBranchIdInput.value) {
                const preselected = existingBranches.find(b => b.id == existingBranchIdInput.value);
                if (preselected) {
                    nameInput.value = preselected.name;
                    if (radioReuseYes) radioReuseYes.checked = true;
                    checkBranchDuplicates();
                }
            }

            const branchForm = nameInput.closest('form');
            if (branchForm) {
                branchForm.addEventListener('submit', (e) => {
                    checkBranchDuplicates();
                    if (btnSubmit && btnSubmit.disabled) {
                        e.preventDefault();
                        if (window.Swal) {
                            window.Swal.fire({
                                icon: 'error',
                                title: 'Cabang Sudah Terdaftar',
                                text: nameDuplicateMsg && !nameDuplicateAlert.classList.contains('hidden')
                                    ? nameDuplicateMsg.textContent
                                    : 'Cabang ini sudah pernah diinput sebelumnya dan tidak dapat didaftarkan kembali.',
                                confirmButtonColor: '#FF6B00'
                            });
                        }
                    }
                });
            }

            // Auto-hyphen setiap 4 digit untuk Nomor Telepon (Contoh: 0812-3456-7890)
            function attachPhoneFormatter(elementId) {
                const el = document.getElementById(elementId);
                if (!el) return;

                function formatPhoneNumber(val) {
                    if (!val) return '';
                    const digits = val.replace(/\D/g, '').slice(0, 16);
                    if (!digits) return '';
                    const chunks = digits.match(/.{1,4}/g);
                    return chunks ? chunks.join('-') : '';
                }

                function handlePhoneInput() {
                    const rawValue = el.value;
                    const oldSelectionStart = el.selectionStart;

                    const digitsBeforeCursor = rawValue.slice(0, oldSelectionStart).replace(/\D/g, '').length;
                    const formatted = formatPhoneNumber(rawValue);
                    el.value = formatted;

                    let newCursorPos = 0;
                    let countedDigits = 0;
                    for (let i = 0; i < formatted.length; i++) {
                        if (/\d/.test(formatted[i])) {
                            countedDigits++;
                        }
                        if (countedDigits === digitsBeforeCursor) {
                            newCursorPos = i + 1;
                            break;
                        }
                    }
                    if (digitsBeforeCursor === 0) {
                        newCursorPos = 0;
                    }
                    el.setSelectionRange(newCursorPos, newCursorPos);
                }

                el.addEventListener('input', handlePhoneInput);

                el.addEventListener('keydown', (e) => {
                    if (e.key === 'Backspace') {
                        const start = el.selectionStart;
                        const end = el.selectionEnd;
                        if (start === end && start > 0 && el.value[start - 1] === '-') {
                            e.preventDefault();
                            const val = el.value;
                            el.value = val.slice(0, start - 2) + val.slice(start - 1);
                            el.setSelectionRange(start - 2, start - 2);
                            handlePhoneInput();
                        }
                    }
                });

                if (el.value) {
                    el.value = formatPhoneNumber(el.value);
                }
            }

            attachPhoneFormatter('phone');
            attachPhoneFormatter('admin_phone_number');
        });
    </script>
    @endpush
</x-app-layout>
