# 🎨 DESIGN SYSTEM & UI/UX GUIDELINES (`desain.md`)
## Learning Management System (LMS) Terpadu Multi-Cabang

Dokumen ini merupakan panduan visual (*design tokens*), prinsip UI/UX, tipografi, palet warna, dan arsitektur tata letak (*layout*) untuk platform LMS. Seluruh antarmuka dirancang dengan pendekatan **Mobile-First**, adaptif (**Flexbox & CSS Grid**), serta berprinsip **Anti-AI Slop** (menghindari tampilan kaku, generik, atau dingin).

---

## 1. Filosofi Desain & Karakter Visual

### 1.1 Prinsip "Anti-AI Slop"
> **AI Slop** adalah desain antarmuka yang terlihat seperti template bawaan generik: kartu-kartu putih polos yang membosankan, gradien ungu acak tanpa konteks, ikon melayang yang tidak fungsional, dan hirarki teks yang datar.

Antarmuka LMS ini dibangun dengan identitas yang berbeda:
1. **Hangat & Penuh Energi (Warm & Energetic)**: Palet utama perpaduan **Orange hangat** dan **Merah dinamis** membangkitkan semangat belajar, akselerasi kompetensi, dan urgensi kelulusan 20 JP.
2. **Keterbacaan Tinggi & Bersahabat (Human & Friendly)**: Kombinasi ketegasan **Montserrat** pada judul dengan kehangatan lekukan **Quicksand** pada sub-judul dan body text.
3. **Sentuhan Taktil (Tactile Depth & Clarity)**: Memanfaatkan bayangan halus (*soft ambient shadows*), garis tepi (*subtle warm borders*), dan kontras yang tegas—bukan sekadar efek blur tak beraturan.
4. **Indikator Visual Bermakna**: Setiap badge, progress bar, dan status kuota dirancang untuk langsung menyampaikan urgensi (misal: kuota 40 offline yang menipis, live session Zoom yang sedang berlangsung).

---

## 2. Tipografi (Typography System)

Sistem menggunakan font Google standar yang dipasangkan secara harmonis:
* **Heading (H1 - H5, Banner Title, Nilai Angka Besar)**: `Montserrat` (Sans-Serif, Geometric, Tegas, Berkarakter).
* **Sub-Heading, Label Menu, Form Input & Body Text**: `Quicksand` (Sans-Serif, Rounded Terminals, Ramah, Nyaman Dibaca).

```html
<!-- Google Fonts Embed Code -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,500;0,600;0,700;0,800;1,700&family=Quicksand:wght@400;500;600;700&display=swap" rel="stylesheet">
```

### Skala Tipografi
| Level | Font Family | Weight | Ukuran (Mobile) | Ukuran (Desktop) | Kegunaan |
| :--- | :--- | :---: | :---: | :---: | :--- |
| **Hero / Stat** | Montserrat | 800 (ExtraBold) | 2rem (32px) | 3rem (48px) | Angka 20 JP, Skor Kuis, Judul Utama |
| **H1** | Montserrat | 700 (Bold) | 1.5rem (24px) | 2rem (32px) | Judul Halaman / Dashboard |
| **H2** | Montserrat | 700 (Bold) | 1.25rem (20px) | 1.5rem (24px) | Nama Kelas, Judul Section |
| **H3** | Montserrat | 600 (SemiBold) | 1.125rem (18px) | 1.25rem (20px) | Judul Kartu, Header Modal |
| **Sub-Heading** | Quicksand | 700 (Bold) | 1rem (16px) | 1.125rem (18px) | Sub-judul section, breadcrumb aktif |
| **Body Large** | Quicksand | 600 (SemiBold) | 0.95rem (15px) | 1rem (16px) | Paragraf pengantar, item navigasi |
| **Body Regular** | Quicksand | 500 (Medium) | 0.875rem (14px) | 0.9rem (14.5px) | Teks isi materi, pembahasan soal |
| **Caption / Chip**| Quicksand | 600 (SemiBold) | 0.75rem (12px) | 0.8rem (13px) | Badge kuota, durasi JP, timestamp log |

---

## 3. Palet Warna (Color Palette System)

* **Warna Utama (Main)**: **Orange** (Vibrant, Energetik, Antusiasme).
* **Warna Pendukung (Sub-Main)**: **Red** (Fokus, Aksen Kuat, Peringatan, CTA Tegas).
* **Warna Netral Hangat (Warm Neutrals)**: Menggantikan abu-abu dingin standar komputer dengan abu-abu ber-undertone hangat lembut (*warm slate / stone*).

```
Main Orange : #FF6B00  ──►  Vibrant Orange : #F97316
Sub Red     : #E11D48  ──►  Crimson Red   : #DC2626
```

### 3.1 Token Warna Utama
```css
:root {
  /* Brand Main - Orange */
  --color-orange-50:  #FFF7ED;
  --color-orange-100: #FFEDD5;
  --color-orange-200: #FED7AA;
  --color-orange-300: #FDBA74;
  --color-orange-400: #FB923C;
  --color-orange-500: #F97316; /* Standard Main */
  --color-orange-600: #EA580C; /* Interactive Hover */
  --color-orange-700: #C2410C;
  --color-orange-primary: #FF6B00; /* Signature Accent */

  /* Brand Sub-Main - Red */
  --color-red-50:  #FEF2F2;
  --color-red-100: #FEE2E2;
  --color-red-200: #FECACA;
  --color-red-400: #F87171;
  --color-red-500: #EF4444; /* Standard Red */
  --color-red-600: #DC2626; /* Crimson Accent */
  --color-red-700: #B91C1C;
  --color-red-primary: #E11D48; /* Sub-main Accent */

  /* Backgrounds & Neutrals (Warm Tone) */
  --bg-app:        #FAF8F5; /* Canvas dasar hangat (bukan putih mentah) */
  --bg-surface:    #FFFFFF; /* Warna kartu / panel */
  --bg-surface-alt:#F4EFEA; /* Panel sekunder / input background */
  --border-subtle: #EBE5DF; /* Border halus */
  --border-focus:  #FB923C;

  /* Text Colors */
  --text-main:     #1E1B18; /* Hampir hitam dengan nuansa hangat */
  --text-muted:    #6E675F; /* Teks sekunder */
  --text-inverted: #FFFFFF;

  /* Semantic Highlights */
  --badge-offline: #FFF1F2; /* Soft red tint */
  --badge-online:  #EFF6FF; /* Soft sky tint */
  --badge-hybrid:  #FFF7ED; /* Soft orange tint */
  --status-success:#10B981;
  --status-warning:#F59E0B;
}
```

### 3.2 Gradien Khas (Signature Gradients)
Digunakan secara selektif untuk tombol pendaftaran, banner pengumuman, dan kartu progres 20 JP:
* **Sunset Blaze (Primary CTA)**: `linear-gradient(135deg, #FF6B00 0%, #E11D48 100%)`
* **Warm Glow (Hero Card Accent)**: `linear-gradient(135deg, rgba(255, 107, 0, 0.12) 0%, rgba(225, 29, 72, 0.08) 100%)`
* **Live Pulse (Zoom Active Indicator)**: `linear-gradient(90deg, #DC2626 0%, #F97316 100%)`

---

## 4. Arsitektur Layout (Mobile-First, Flex & Grid)

### 4.1 Prinsip Mobile-First
1. **Navigasi Bawah / Bottom Bar di Mobile**: Peserta dapat mengakses Menu Kelas, Jadwal Zoom, Progres 20 JP, dan Profil dengan mudah menggunakan satu ibu jari (*thumb zone friendly*).
2. **Desktop Sidebar Kolapsibel**: Pada layar $\ge 1024px$ (desktop), navigasi bertransformasi menjadi sidebar kiri yang luas dengan profil pengguna di pojok bawah.
3. **Tabel Responsif**: Pada mobile, data tabel (seperti log aktivitas atau daftar peserta) otomatis beradaptasi menjadi kartu bertumpuk (*card list view*), bukan tabel lebar yang terpotong.

### 4.2 Pola Layout Grid & Flex

```
┌─────────────────────────────────────────────────────────┐
│                      HEADER / TOPBAR                    │
├─────────────────┬───────────────────────────────────────┤
│                 │               MAIN CONTENT            │
│                 │  ┌─────────────────────────────────┐  │
│     SIDEBAR     │  │  BANNER PROGRES 20 JP           │  │
│                 │  └─────────────────────────────────┘  │
│  (Desktop: Col) │  ┌───────────────┬─────────────────┐  │
│  (Mobile: Bar)  │  │ GRID CARD 1   │ GRID CARD 2     │  │
│                 │  │ (Kelas Aktif) │ (Link Zoom Live)│  │
│                 │  └───────────────┴─────────────────┘  │
└─────────────────┴───────────────────────────────────────┘
```

* **Container Utama**:
  ```html
  <div class="min-h-screen bg-[#FAF8F5] flex flex-col lg:flex-row font-quicksand text-[#1E1B18]">
  ```
* **Katalog Kelas (Responsive Grid)**:
  ```html
  <!-- 1 kolom di HP, 2 kolom di Tablet, 3 kolom di Desktop/Laptop -->
  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
  ```
* **Header Kartu & Badge (Flex Alignment)**:
  ```html
  <div class="flex items-center justify-between gap-3">
  ```

---

## 5. Spesifikasi Komponen Utama (UI Component Kit)

### 5.1 Kartu Kelas & Indikator Kapasitas (Fitur Kuota Offline 40 vs Online)
Kartu kelas tidak kaku; dilengkapi indikator kuota yang dinamis dan informatif:

```html
<!-- Kartu Kelas Offline -->
<div class="bg-white rounded-2xl p-5 border border-[#EBE5DF] shadow-sm hover:shadow-md transition-all duration-200 flex flex-col justify-between">
  <div>
    <!-- Badge Status Tipe Kelas & Cabang -->
    <div class="flex items-center justify-between gap-2 mb-3">
      <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold font-montserrat bg-[#FFF1F2] text-[#DC2626] border border-[#FECACA]">
        <span class="w-2 h-2 rounded-full bg-[#DC2626]"></span>
        Kelas Offline
      </span>
      <span class="text-xs text-[#6E675F] font-semibold">Cabang Jakarta Pusat</span>
    </div>

    <!-- Judul Kelas -->
    <h3 class="font-montserrat font-bold text-lg text-[#1E1B18] mb-1 leading-snug">
      Pelatihan Manajemen Operasional
    </h3>
    <p class="text-xs text-[#6E675F] mb-4">Oleh: Budi Santoso, M.Kom</p>

    <!-- Indikator Kuota Ketat 40 Orang -->
    <div class="bg-[#FFF7ED] rounded-xl p-3 border border-[#FED7AA] mb-4">
      <div class="flex justify-between items-center text-xs font-bold mb-1.5">
        <span class="text-[#EA580C]">Kapasitas Kursi Fisik</span>
        <span class="text-[#DC2626] font-montserrat">38 / 40 Terisi</span>
      </div>
      <!-- Meter Gauge -->
      <div class="w-full bg-[#FED7AA] h-2 rounded-full overflow-hidden">
        <div class="bg-gradient-to-r from-[#FF6B00] to-[#DC2626] h-full rounded-full transition-all duration-500" style="width: 95%"></div>
      </div>
      <p class="text-[11px] text-[#C2410C] mt-1 font-semibold">⚠️ Sisa 2 kursi lagi sebelum pendaftaran ditutup otomatis!</p>
    </div>
  </div>

  <!-- Aksi Tombol -->
  <button class="w-full py-2.5 px-4 rounded-xl bg-gradient-to-r from-[#FF6B00] to-[#E11D48] text-white font-montserrat font-bold text-sm shadow-sm hover:opacity-95 active:scale-[0.99] transition-all">
    Pilih Kelas Ini
  </button>
</div>
```

---

### 5.2 Widget Visual Pemenuhan 20 JP (Karakteristik Utama Peserta)
Elemen kebanggaan peserta untuk memantau progres kelulusan (1 JP = 45 menit, akumulasi 900 menit):

```html
<div class="bg-gradient-to-br from-[#1E1B18] to-[#2D2824] rounded-3xl p-6 text-white shadow-lg relative overflow-hidden">
  <!-- Aksen Cahaya Orange/Red di Latar Belakang -->
  <div class="absolute -right-10 -bottom-10 w-48 h-48 bg-[#FF6B00]/20 rounded-full blur-3xl pointer-events-none"></div>

  <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-4 relative z-10">
    <div>
      <span class="text-xs font-bold tracking-wider uppercase text-[#FB923C] font-montserrat">Syarat Kelulusan Pelatihan</span>
      <h2 class="text-xl sm:text-2xl font-montserrat font-extrabold text-white">Akumulasi Jam Pelajaran (JP)</h2>
    </div>
    <div class="text-right">
      <span class="text-3xl sm:text-4xl font-extrabold font-montserrat text-transparent bg-clip-text bg-gradient-to-r from-[#FB923C] to-[#F87171]">
        14.0 <span class="text-lg text-white/70">/ 20.0 JP</span>
      </span>
      <p class="text-xs text-white/60">630 dari 900 Menit Tuntas (70%)</p>
    </div>
  </div>

  <!-- Progress Track Bar -->
  <div class="w-full bg-white/10 h-3.5 rounded-full p-0.5 mb-3 relative z-10">
    <div class="bg-gradient-to-r from-[#FF6B00] via-[#FB923C] to-[#E11D48] h-full rounded-full transition-all duration-700 shadow-sm" style="width: 70%"></div>
  </div>

  <!-- Status Box -->
  <div class="flex items-center justify-between text-xs text-white/80 pt-2 border-t border-white/10 relative z-10">
    <span class="flex items-center gap-1.5 font-medium">
      <span class="w-2 h-2 rounded-full bg-[#10B981] animate-pulse"></span>
      6 JP lagi menuju verifikasi Trainer
    </span>
    <span class="text-[#FED7AA] font-semibold">1 JP = 45 Menit</span>
  </div>
</div>
```

---

### 5.3 Tombol Live "Masuk Zoom" (Fitur 1-Click Zoom)
Didesain mencolok dengan indikator aktif agar peserta tidak bingung saat sesi daring berlangsung:

```html
<!-- Tombol Zoom Aktif (Sesi Sedang Berlangsung) -->
<a href="#" class="inline-flex items-center justify-center gap-2.5 px-5 py-3 rounded-xl bg-gradient-to-r from-[#DC2626] to-[#FF6B00] text-white font-montserrat font-bold text-sm shadow-md hover:shadow-orange-500/20 active:scale-95 transition-all">
  <span class="relative flex h-3 w-3">
    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-white opacity-75"></span>
    <span class="relative inline-flex rounded-full h-3 w-3 bg-white"></span>
  </span>
  Masuk Ruang Zoom Sekarang
</a>
```

---

### 5.4 Tombol Keputusan Trainer (Approve / Reject Kelulusan 20 JP)
Kontras tinggi untuk membedakan aksi penerimaan sertifikasi atau penolakan dengan catatan revisi:

```html
<div class="flex items-center gap-3">
  <!-- Tombol Terima (Approve) -->
  <button class="flex-1 py-2.5 px-4 rounded-xl bg-[#10B981] hover:bg-[#059669] text-white font-montserrat font-bold text-sm shadow-sm transition-all flex items-center justify-center gap-2">
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
    Terima (Lulus 20 JP)
  </button>

  <!-- Tombol Tolak (Reject) -->
  <button class="flex-1 py-2.5 px-4 rounded-xl bg-[#FFF1F2] hover:bg-[#FEE2E2] text-[#DC2626] border border-[#FECACA] font-montserrat font-bold text-sm transition-all flex items-center justify-center gap-2">
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
    Tolak / Beri Revisi
  </button>
</div>
```

---

## 6. Konfigurasi Tailwind CSS (`tailwind.config.js`)

Agar seluruh tim developer dan agent AI menggunakan font dan warna yang konsisten, konfigurasi `tailwind.config.js` didefinisikan sebagai berikut:

```javascript
/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  theme: {
    extend: {
      fontFamily: {
        montserrat: ['"Montserrat"', 'sans-serif'],
        quicksand: ['"Quicksand"', 'sans-serif'],
        sans: ['"Quicksand"', 'sans-serif'], // Default fallback
      },
      colors: {
        brand: {
          orange: {
            50:  '#FFF7ED',
            100: '#FFEDD5',
            200: '#FED7AA',
            300: '#FDBA74',
            400: '#FB923C',
            500: '#F97316',
            600: '#EA580C',
            700: '#C2410C',
            primary: '#FF6B00',
          },
          red: {
            50:  '#FEF2F2',
            100: '#FEE2E2',
            200: '#FECACA',
            400: '#F87171',
            500: '#EF4444',
            600: '#DC2626',
            700: '#B91C1C',
            primary: '#E11D48',
          },
          canvas: '#FAF8F5',
          dark: '#1E1B18',
        }
      }
    },
  },
  plugins: [
    require('@tailwindcss/forms'),
  ],
}
```

---

## 7. Integrasi SweetAlert2 (Modal Dialog, Konfirmasi & Toast)

Sistem menggunakan **SweetAlert2** (`sweetalert2`) untuk seluruh feedback interaktif, dialog konfirmasi aksi kritis, dan notifikasi toast. Tampilan pop-up disesuaikan agar menyatu dengan sistem desain (font Montserrat & Quicksand, warna Orange & Red, sudut membulat `rounded-3xl`).

### 7.1 Status Instalasi & Setup Global
* Package telah terpasang: `npm install sweetalert2` (terdaftar di `package.json`).
* Terintegrasi global di `resources/js/app.js` sebagai `window.Swal`, `window.Toast`, dan helper `window.confirmAction`.

### 7.2 Spesifikasi Gaya Visual SweetAlert2
| Elemen Pop-up | Nilai Desain | Class / Properti CSS |
| :--- | :--- | :--- |
| **Font Judul** | Montserrat Bold | `font-montserrat font-bold text-xl text-[#1E1B18]` |
| **Font Isi / Pesan** | Quicksand Medium | `font-quicksand text-sm text-[#6E675F]` |
| **Sudut Modal** | Extra Rounded | `rounded-3xl border border-[#EBE5DF] shadow-2xl p-6` |
| **Tombol Konfirmasi**| Main Orange (`#FF6B00`) | `px-5 py-2.5 rounded-xl font-montserrat font-bold text-sm text-white bg-[#FF6B00] hover:bg-[#EA580C]` |
| **Tombol Batal / Tolak**| Sub-Main Red (`#DC2626`) | `px-5 py-2.5 rounded-xl font-montserrat font-bold text-sm text-white bg-[#DC2626] hover:bg-[#B91C1C]` |

---

### 7.3 Contoh Penggunaan Kasus Nyata

#### A. Toast Notifikasi Cepat (Top-End Toast)
Digunakan setelah aksi berhasil disimpan (misal: link Zoom diperbarui, profil tersimpan):
```javascript
// Memanggil Toast global
Toast.fire({
  icon: 'success',
  title: 'Link Zoom sesi berhasil diperbarui!'
});
```

#### B. Konfirmasi Penolakan Kelulusan 20 JP oleh Trainer (Dengan Input Revisi)
Dialog interaktif ketika Trainer menolak verifikasi kelulusan peserta:
```javascript
Swal.fire({
  title: 'Tolak Pengajuan 20 JP?',
  text: 'Peserta wajib memperbaiki materi sebelum dapat mengajukan verifikasi kembali.',
  input: 'textarea',
  inputPlaceholder: 'Tuliskan catatan revisi spesifik (misal: Kuis Bab 2 perlu remedial)...',
  icon: 'warning',
  showCancelButton: true,
  confirmButtonText: 'Kirim Catatan Revisi',
  cancelButtonText: 'Batal',
  confirmButtonColor: '#DC2626', // Merah tegas untuk aksi tolak
  cancelButtonColor: '#6E675F',
  customClass: {
    popup: 'font-quicksand rounded-3xl p-6 border border-[#EBE5DF]',
    title: 'font-montserrat font-bold text-xl text-[#1E1B18]',
    confirmButton: 'px-5 py-2.5 rounded-xl font-montserrat font-bold text-sm text-white shadow-sm',
    cancelButton: 'px-5 py-2.5 rounded-xl font-montserrat font-bold text-sm text-white shadow-sm',
    input: 'font-quicksand text-sm rounded-xl border-[#EBE5DF] focus:border-[#FF6B00] focus:ring-[#FF6B00]'
  },
  inputValidator: (value) => {
    if (!value) {
      return 'Catatan revisi wajib diisi agar peserta tahu apa yang harus diperbaiki!';
    }
  }
}).then((result) => {
  if (result.isConfirmed) {
    // Submit form penolakan via AJAX / Form POST
  }
});
```

#### C. Konfirmasi Pendaftaran Kelas Offline (Peringatan Kuota 40 Kursi)
```javascript
confirmAction({
  title: 'Konfirmasi Pilihan Kelas Offline',
  text: 'Kelas ini memiliki kuota terbatas 40 orang di ruangan fisik cabang. Pastikan jadwal Anda sesuai.',
  icon: 'question',
  confirmText: 'Daftar Kelas Sekarang',
  cancelText: 'Pikirkan Nanti'
}).then((result) => {
  if (result.isConfirmed) {
    // Lanjutkan proses pendaftaran
  }
});
```

---

## 8. Checklist Kualitas UI/UX Sebelum Rilis
- [ ] **Kesesuaian Font**: Seluruh tag Heading menggunakan class `font-montserrat font-bold`, seluruh teks isi dan sub-heading menggunakan class `font-quicksand`.
- [ ] **Hierarki Warna**: Tombol aksi utama (*primary*) menggunakan Orange (`#FF6B00`) atau Sunset Gradient; tombol penolakan/aksi kritis menggunakan Red (`#DC2626` / `#E11D48`).
- [ ] **Modal SweetAlert2**: Semua alert dan konfirmasi menggunakan SweetAlert2 dengan font Montserrat/Quicksand dan styling tema Orange-Red.
- [ ] **Responsivitas HP**: Uji tampilan pada viewport selebar 360px - 414px (tidak ada elemen yang meluap/overflow horizontal).
- [ ] **Feedback State**: Setiap tombol memiliki state `:hover`, `:active`, dan `:disabled` yang jelas.
- [ ] **Keterbacaan Kontras**: Teks putih pada tombol orange/merah telah memenuhi standar rasio kontras WCAG AA ($\ge 4.5:1$).

