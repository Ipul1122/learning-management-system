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
                <div class="flex flex-wrap items-center gap-3">
                    <h1 class="font-montserrat font-extrabold text-2xl sm:text-3xl text-[#1E1B18] tracking-tight">
                        Edit Cabang: {{ $branch->name }}
                    </h1>
                    <span class="px-2.5 py-1 rounded-lg text-xs font-mono font-bold bg-[#FAF8F5] border border-[#EBE5DF] text-[#1E1B18]">
                        {{ $branch->code }}
                    </span>
                </div>
                <p class="font-quicksand text-sm text-[#6E675F] mt-1">
                    Perbarui profil kantor cabang, alamat operasional, dan akun administrator pengelola cabang.
                </p>
            </div>

            <!-- Form Multi-Column -->
            <form method="POST" action="{{ route('admin.branches.update', $branch) }}" class="space-y-6">
                @csrf
                @method('PUT')

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
                                   value="{{ old('name', $branch->name) }}"
                                   placeholder="Contoh: Cabang Jakarta Pusat"
                                   required
                                   class="w-full px-4 py-2.5 rounded-xl border @error('name') border-red-500 @else border-[#EBE5DF] @enderror bg-[#FAF8F5] text-sm text-[#1E1B18] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#FF6B00] focus:border-transparent font-quicksand transition">
                            <div id="name-duplicate-alert" class="hidden mt-2 p-2.5 rounded-xl bg-red-50 border border-red-200 text-xs text-[#DC2626] font-semibold flex items-center gap-2">
                                <svg class="w-4 h-4 shrink-0 text-[#DC2626]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                                <span id="name-duplicate-msg">Cabang ini sudah pernah diinput sebelumnya dan tidak dapat didaftarkan kembali.</span>
                            </div>
                            @error('name')
                                <p class="mt-1.5 text-xs text-[#DC2626] font-semibold">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Kode Cabang -->
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <label for="code" class="block font-montserrat font-bold text-xs uppercase tracking-wider text-[#1E1B18]">
                                    Kode Cabang <span class="text-[#DC2626]">*</span>
                                </label>
                                <div class="flex items-center gap-2">
                                    <span id="code-auto-badge" class="inline-flex items-center gap-1 text-[11px] font-medium text-[#6E675F] bg-[#FAF8F5] px-2 py-0.5 rounded-md border border-[#EBE5DF] transition-all">
                                        <span id="code-status-text">Manual</span>
                                    </span>
                                </div>
                            </div>
                            <div class="relative">
                                <input type="text"
                                       id="code"
                                       name="code"
                                       value="{{ old('code', $branch->code) }}"
                                       placeholder="Contoh: CBG-JKT"
                                       required
                                       class="w-full px-4 py-2.5 rounded-xl border @error('code') border-red-500 @else border-[#EBE5DF] @enderror bg-[#FAF8F5] text-sm font-mono uppercase text-[#1E1B18] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#FF6B00] focus:border-transparent transition pr-9">
                                <button type="button"
                                        id="btn-regenerate-code"
                                        title="Sinkronkan kembali dengan nama cabang"
                                        class="absolute right-2.5 top-1/2 -translate-y-1/2 p-1 text-[#6E675F] hover:text-[#FF6B00] rounded-lg hover:bg-white transition"
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
                                <span>Gunakan tombol putar untuk menyesuaikan kode otomatis dengan nama cabang.</span>
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
                                       value="{{ old('city', $branch->city) }}"
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
                                       value="{{ old('phone', $branch->phone) }}"
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
                                      class="w-full px-4 py-2.5 rounded-xl border @error('address') border-red-500 @else border-[#EBE5DF] @enderror bg-[#FAF8F5] text-sm text-[#1E1B18] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#FF6B00] focus:border-transparent font-quicksand transition">{{ old('address', $branch->address) }}</textarea>
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
                                       {{ old('is_active', $branch->is_active) ? 'checked' : '' }}
                                       class="w-5 h-5 text-[#FF6B00] rounded border-[#EBE5DF] focus:ring-[#FF6B00]">
                                <div>
                                    <span class="font-montserrat font-bold text-sm text-[#1E1B18]">Status Cabang Aktif</span>
                                    <p class="font-quicksand text-xs text-[#6E675F]">Jika dinonaktifkan, cabang ini tidak dapat dipilih saat membuat kelas baru.</p>
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
                                        @if($admin)
                                            <span class="text-[10px] uppercase tracking-wider font-semibold text-[#16A34A] bg-[#DCFCE7] px-2 py-0.5 rounded-full border border-[#16A34A]/30">Pengelola Aktif</span>
                                        @else
                                            <span class="text-[10px] uppercase tracking-wider font-semibold text-[#FF6B00] bg-[#FFF0E6] px-2 py-0.5 rounded-full border border-[#FF6B00]/30">Belum Ada Admin</span>
                                        @endif
                                    </h2>
                                    <p class="font-quicksand text-xs text-[#6E675F]">
                                        @if($admin)
                                            Kelola akun login admin penanggung jawab cabang ini.
                                        @else
                                            Cabang ini belum memiliki akun admin penanggung jawab.
                                        @endif
                                    </p>
                                </div>
                            </div>
                            @if(!$admin)
                                <label class="inline-flex items-center gap-2 cursor-pointer shrink-0 bg-[#FAF8F5] px-3 py-1.5 rounded-xl border border-[#EBE5DF] hover:border-[#FF6B00]/40 transition">
                                    <input type="checkbox"
                                           id="create_admin_account"
                                           name="create_admin_account"
                                           value="1"
                                           {{ old('create_admin_account', false) ? 'checked' : '' }}
                                           class="w-4 h-4 text-[#FF6B00] rounded border-[#EBE5DF] focus:ring-[#FF6B00]">
                                    <span class="font-montserrat font-bold text-xs text-[#1E1B18]">Buat Akun</span>
                                </label>
                            @endif
                        </div>

                        <!-- Form Fields Admin Cabang -->
                        <div id="admin-fields-container" class="space-y-4 pt-1 transition-all duration-300 {{ !$admin && !old('create_admin_account') ? 'hidden' : '' }}">
                            <!-- Nama Lengkap Admin -->
                            <div>
                                <label for="admin_name" class="block font-montserrat font-bold text-xs uppercase tracking-wider text-[#1E1B18] mb-2">
                                    Nama Lengkap Admin <span class="text-[#DC2626]">*</span>
                                </label>
                                <input type="text"
                                       id="admin_name"
                                       name="admin_name"
                                       value="{{ old('admin_name', $admin?->name) }}"
                                       placeholder="Contoh: Budi Santoso"
                                       {{ $admin ? 'required' : '' }}
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
                                       value="{{ old('admin_email', $admin?->email) }}"
                                       placeholder="Contoh: budi.admin@lms.test"
                                       {{ $admin ? 'required' : '' }}
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
                                           value="{{ old('admin_phone_number', $admin?->phone_number) }}"
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
                                            <input type="radio" name="admin_status" value="active" {{ old('admin_status', $admin?->status ?? 'active') === 'active' ? 'checked' : '' }} class="text-[#FF6B00] focus:ring-[#FF6B00]">
                                            <span class="text-sm font-quicksand text-[#1E1B18]">Aktif</span>
                                        </label>
                                        <label class="flex items-center gap-2 cursor-pointer">
                                            <input type="radio" name="admin_status" value="inactive" {{ old('admin_status', $admin?->status ?? 'active') === 'inactive' ? 'checked' : '' }} class="text-[#FF6B00] focus:ring-[#FF6B00]">
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
                                        Kata Sandi @if(!$admin) <span class="text-[#DC2626]">*</span> @endif
                                    </label>
                                    <input type="password"
                                           id="admin_password"
                                           name="admin_password"
                                           placeholder="{{ $admin ? 'Kosongkan jika tidak diubah' : 'Min. 8 karakter' }}"
                                           class="w-full px-4 py-2.5 rounded-xl border @error('admin_password') border-red-500 @else border-[#EBE5DF] @enderror bg-[#FAF8F5] text-sm text-[#1E1B18] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#FF6B00] focus:border-transparent font-quicksand transition">
                                    @error('admin_password')
                                        <p class="mt-1.5 text-xs text-[#DC2626] font-semibold">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="admin_password_confirmation" class="block font-montserrat font-bold text-xs uppercase tracking-wider text-[#1E1B18] mb-2">
                                        Konfirmasi Kata Sandi
                                    </label>
                                    <input type="password"
                                           id="admin_password_confirmation"
                                           name="admin_password_confirmation"
                                           placeholder="{{ $admin ? 'Ulangi jika ubah kata sandi' : 'Ulangi kata sandi' }}"
                                           class="w-full px-4 py-2.5 rounded-xl border border-[#EBE5DF] bg-[#FAF8F5] text-sm text-[#1E1B18] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#FF6B00] focus:border-transparent font-quicksand transition">
                                </div>
                            </div>
                            @if($admin)
                                <p class="text-xs text-[#6E675F] font-quicksand flex items-center gap-1 mt-1">
                                    <svg class="w-3.5 h-3.5 text-[#FF6B00] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span>Biarkan kolom kata sandi kosong jika tidak ingin memperbarui kata sandi akun admin.</span>
                                </p>
                            @endif
                        </div>

                        <!-- Notice jika belum ada admin dan pembuatan belum diaktifkan -->
                        @if(!$admin)
                            <div id="admin-disabled-notice" class="{{ old('create_admin_account') ? 'hidden' : '' }} p-8 rounded-2xl border-2 border-dashed border-[#EBE5DF] bg-[#FAF8F5] text-center space-y-3">
                                <div class="w-12 h-12 rounded-full bg-[#EBE5DF]/60 text-[#6E675F] flex items-center justify-center mx-auto">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="font-montserrat font-bold text-sm text-[#1E1B18]">Belum Ada Akun Admin Cabang</h3>
                                    <p class="font-quicksand text-xs text-[#6E675F] mt-1 max-w-sm mx-auto">
                                        Cabang ini belum memiliki akun admin penanggung jawab. Centang opsi <strong>"Buat Akun"</strong> di atas untuk langsung mendaftarkan akun pengelola sekarang.
                                    </p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Action Bar Bawah Terpadu -->
                <div class="bg-white rounded-2xl border border-[#EBE5DF] shadow-sm p-4 sm:px-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div class="flex items-center gap-2 text-xs text-[#6E675F] font-quicksand">
                        <svg class="w-4 h-4 text-[#FF6B00] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>Data kantor cabang dan akun administrator akan diperbarui secara terpadu.</span>
                    </div>
                    <div class="flex items-center justify-end gap-3 shrink-0">
                        <a href="{{ route('admin.branches.index') }}"
                           class="px-5 py-2.5 rounded-xl font-montserrat font-bold text-sm text-[#6E675F] hover:text-[#1E1B18] hover:bg-[#FAF8F5] transition">
                            Batal
                        </a>
                        <button type="submit"
                                id="btn-submit-branch"
                                class="px-6 py-2.5 rounded-xl font-montserrat font-bold text-sm text-white bg-gradient-to-r from-[#FF6B00] to-[#E11D48] hover:from-[#EA580C] hover:to-[#DC2626] shadow-md hover:shadow-lg transition-all duration-200 transform hover:-translate-y-0.5">
                            Simpan Perubahan Data
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
            const existingBranches = @json($existingBranches ?? []);

            const nameDuplicateAlert = document.getElementById('name-duplicate-alert');
            const nameDuplicateMsg = document.getElementById('name-duplicate-msg');
            const codeDuplicateAlert = document.getElementById('code-duplicate-alert');
            const codeDuplicateMsg = document.getElementById('code-duplicate-msg');
            const btnSubmit = document.getElementById('btn-submit-branch');

            function normalizeName(str) {
                return (str || '').toLowerCase().trim().replace(/\s+/g, ' ');
            }

            function stripPrefix(str) {
                let s = normalizeName(str);
                return s.replace(/^(kantor\s+cabang|kantor\s+operasional|kantor|cabang|branch|kcp|kc|pusat\s+pelatihan)\s+/i, '').trim();
            }

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
                    codeMatch = existingBranches.find(b => b.code.trim().toUpperCase() === rawCode);
                }

                if (matchedBranch) {
                    nameDuplicateMsg.textContent = `Cabang '${matchedBranch.name}' sudah pernah didaftarkan pada cabang lain.`;
                    nameDuplicateAlert.classList.remove('hidden');
                    nameInput.classList.add('border-red-500', 'bg-red-50/20');
                } else {
                    nameDuplicateAlert.classList.add('hidden');
                    nameInput.classList.remove('border-red-500', 'bg-red-50/20');
                }

                if (codeMatch && !matchedBranch) {
                    codeDuplicateMsg.textContent = `Kode cabang '${codeMatch.code}' sudah digunakan oleh '${codeMatch.name}'.`;
                    codeDuplicateAlert.classList.remove('hidden');
                    codeInput.classList.add('border-red-500', 'bg-red-50/20');
                } else {
                    codeDuplicateAlert.classList.add('hidden');
                    codeInput.classList.remove('border-red-500', 'bg-red-50/20');
                }

                if (btnSubmit) {
                    if (matchedBranch || (codeMatch && !matchedBranch)) {
                        btnSubmit.disabled = true;
                        btnSubmit.classList.add('opacity-50', 'cursor-not-allowed', 'pointer-events-none');
                    } else {
                        btnSubmit.disabled = false;
                        btnSubmit.classList.remove('opacity-50', 'cursor-not-allowed', 'pointer-events-none');
                    }
                }
            }

            function generateBranchCode(name) {
                if (!name || !name.trim()) return '';

                let clean = name.toLowerCase().trim();
                clean = clean.replace(/^(kantor\s+cabang|kantor\s+operasional|kantor|cabang|branch|kcp|kc|pusat\s+pelatihan)\s+/i, '').trim();

                if (!clean) return '';

                for (const [city, code] of Object.entries(cityMap)) {
                    if (clean === city || clean.startsWith(city + ' ')) {
                        return 'CBG-' + code;
                    }
                }

                const words = clean.replace(/[^a-z0-9\s]/gi, ' ').split(/\s+/).filter(Boolean);
                if (words.length === 0) return '';

                if (words.length === 1) {
                    return ('CBG-' + words[0].toUpperCase()).substring(0, 20);
                }

                const combined = 'CBG-' + words.join('-').toUpperCase();
                if (combined.length <= 20) {
                    return combined;
                }

                const initials = words.map(w => w[0]).join('').toUpperCase();
                return ('CBG-' + initials).substring(0, 20);
            }

            let isManual = true;

            function updateUIState() {
                if (!codeBadge || !codeStatusText) return;

                if (isManual) {
                    codeBadge.className = 'inline-flex items-center gap-1 text-[11px] font-medium text-[#6E675F] bg-[#FAF8F5] px-2 py-0.5 rounded-md border border-[#EBE5DF] transition-all';
                    codeStatusText.textContent = 'Manual';
                } else {
                    codeBadge.className = 'inline-flex items-center gap-1 text-[11px] font-medium text-[#FF6B00] bg-[#FFF5ED] px-2 py-0.5 rounded-md border border-[#FF6B00]/20 transition-all';
                    codeStatusText.textContent = 'Otomatis';
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

                isManual = true;
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

            updateUIState();
            checkBranchDuplicates();

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
                                    : 'Cabang ini sudah terdaftar pada kantor cabang lain.',
                                confirmButtonColor: '#FF6B00'
                            });
                        }
                    }
                });
            }

            // Toggle Akun Admin jika belum ada akun
            const createAdminCheckbox = document.getElementById('create_admin_account');
            const adminFieldsContainer = document.getElementById('admin-fields-container');
            const adminDisabledNotice = document.getElementById('admin-disabled-notice');
            const adminRequiredInputs = [
                document.getElementById('admin_name'),
                document.getElementById('admin_email'),
                document.getElementById('admin_password'),
                document.getElementById('admin_password_confirmation')
            ].filter(Boolean);

            if (createAdminCheckbox && adminFieldsContainer) {
                function updateAdminSectionState() {
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

                createAdminCheckbox.addEventListener('change', updateAdminSectionState);
                updateAdminSectionState();
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
