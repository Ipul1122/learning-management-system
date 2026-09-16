# 📋 MASTER IMPLEMENTATION FLOW & TRACKER
## Learning Management System (LMS) Terpadu Multi-Cabang

Dokumen ini berfungsi sebagai panduan kerja langkah demi langkah (*step-by-step roadmap*) untuk memantau progress implementasi sistem secara terstruktur dan terukur.

---

### 📊 Ringkasan Status Progres

| Modul / Fase | Target Deliverable | Status |
| :--- | :--- | :---: |
| **Fase 0** | Fondasi Database, Multi-Role (RBAC) & Seeder Awal | `[x] Selesai` |
| **Fase 1** | Modul Super Admin (Cabang, Admin Cabang, Log Global) | `[x] Selesai` |
| **Fase 2** | Modul Admin Cabang (Trainer, Kelas 40/Ratusan, Link Zoom) | `[ ] Belum Mulai` |
| **Fase 3** | Modul Trainer (Bank Soal, Kuis, Sesi Zoom) | `[ ] Belum Mulai` |
| **Fase 4** | Modul Peserta (Pilih Kelas, 1-Click Zoom, Tracking 20 JP) | `[ ] Belum Mulai` |
| **Fase 5** | Verifikasi Kelulusan 20 JP & Penerbitan Sertifikat | `[ ] Belum Mulai` |
| **Fase 6** | Fitur Komunitas (Forum Diskusi) & Berita Pengumuman | `[ ] Belum Mulai` |
| **Fase 7** | Uji Coba (Testing Pest), Security Hardening & Polish UI | `[ ] Belum Mulai` |

---

## 🚀 Rincian Pekerjaan Step-by-Step

### 🔹 FASE 0: Fondasi Database, RBAC & Otentikasi
> **Tujuan**: Menyiapkan struktur database utama, role permission, middleware akses, dan akun testing awal.

- [x] **Step 0.1: Konfigurasi Role & Permission (Spatie)**
  - [x] Migrasi tabel roles & permissions Spatie.
  - [x] Definisi 4 peran utama: `super-admin`, `admin-cabang`, `trainer`, `peserta`.
- [x] **Step 0.2: Migrasi Database Fondasi**
  - [x] Tabel `branches` (id, name, code, address, phone, is_active).
  - [x] Update tabel `users` (tambahkan relasi `branch_id` nullable, `phone_number`, `status`).
  - [x] Tabel `activity_logs` (user_id, branch_id, action, target_entity, target_id, description, properties_old, properties_new, ip_address, user_agent).
- [x] **Step 0.3: Database Seeders Awal**
  - [x] Seeder Role & Permission.
  - [x] Seeder Cabang Contoh (misal: "Cabang Jakarta Pusat", "Cabang Surabaya").
  - [x] Seeder Akun Default untuk Pengujian:
    - 1 Super Admin (`superadmin@lms.test`)
    - 2 Admin Cabang (`admin.jkt@lms.test`, `admin.sby@lms.test`)
    - 2 Trainer (`trainer1@lms.test`, `trainer2@lms.test`)
    - 3 Peserta (`peserta1@lms.test`, dll)
- [x] **Step 0.4: Multi-Role Dashboard Redirection**
  - [x] Middleware pengecekan peran (Role-based access protection).
  - [x] Pengalihan (*redirect*) dinamis setelah login berdasarkan peran user.
  - [x] Komponen Layout Sidebar navigasi dinamis sesuai role yang aktif.

---

### 🔹 FASE 1: Modul Pengguna Super Admin (Root Hierarki)
> **Tujuan**: Super Admin dapat mengelola master cabang, membuat akun admin cabang, dan melihat audit trail.

- [x] **Step 1.1: CRUD Master Cabang (Branches)**
  - [x] List cabang dengan status keaktifan & statistik jumlah pengguna/kelas.
  - [x] Form Tambah, Edit, dan Non-aktifkan (toggle) cabang.
  - [x] Proteksi penghapusan cabang yang memiliki relasi pengguna.
  - [x] RESTful API v1 Cabang (`/api/v1/super-admin/branches`) dengan `BranchResource`.
- [x] **Step 1.2: CRUD Akun Admin Cabang (Fitur PRD 1.1)**
  - [x] Tambah akun Admin Cabang baru + pilih cabang penugasan.
  - [x] Daftar Admin Cabang dengan filter cabang dan status.
  - [x] Edit profil, ubah penugasan cabang, atau reset password admin cabang.
  - [x] Hapus & toggle status keaktifan akun admin cabang.
  - [x] RESTful API v1 Admin Cabang (`/api/v1/super-admin/admins`) dengan `AdminCabangResource`.
- [x] **Step 1.3: Log Aktivitas Admin (Fitur PRD 1.2)**
  - [x] Halaman pemantauan log aktivitas global bagi Super Admin.
  - [x] Filter berdasarkan rentang tanggal, admin, cabang, dan tipe aksi (CREATE, UPDATE, DELETE).
  - [x] Modal detail inspeksi perubahan data (*old value vs new value*).
  - [x] RESTful API v1 Activity Log (`/api/v1/super-admin/logs`) dengan `ActivityLogResource`.
- [x] **Step 1.4: Service / Helper Auto-Logging**
  - [x] Pencatatan audit trail otomatis via `ActivityLog::record(...)` pada setiap mutasi Web & REST API.

---

### 🔹 FASE 2: Modul Pengguna Admin Cabang (Operasional Kelas & Trainer)
> **Tujuan**: Admin Cabang menyiapkan infrastruktur pelatihan (trainer, kelas, aturan kapasitas, dan link zoom).

- [x] **Step 2.1: CRUD Trainer Cabang (Fitur PRD 2.1)**
  - [x] Form pembuatan akun Trainer di cabangnya.
  - [x] Daftar Trainer cabang beserta status aktif dan kelas yang diampu.
  - [x] Edit data profil & penonaktifan akun trainer.
- [x] **Step 2.2: Migrasi Tabel Kelas & Sesi**
  - [x] Tabel `classes`:
    - `type`: `offline`, `online`, `hybrid`.
    - `offline_capacity`: default/maksimal 40 orang.
    - `online_capacity`: kapasitas fleksibel (ratusan).
    - `required_jp`: default 20 JP.
  - [x] Tabel `class_schedules` / `sessions`:
    - Relasi ke kelas, judul sesi, `jp_duration`, tanggal/jam sesi, `zoom_meeting_url`.
- [x] **Step 2.3: CRUD Kelas (Fitur PRD 2.6)**
  - [x] Form pembuatan kelas baru:
    - Input tipe kelas (Offline / Online / Hybrid).
    *Validasi UI: Jika Offline, kapasitas terkunci maksimal 40 orang.*
    *Jika Hybrid: Input kuota fisik (maks 40) + kuota online (ratusan).*
  - [x] Penugasan Trainer penanggung jawab kelas.
  - [x] Pengaturan status kelas (*Draft*, *Pendaftaran Buka*, *Berjalan*, *Selesai*).
- [x] **Step 2.4: Input Jadwal Sesi & Link Zoom (Fitur PRD 2.3)**
  - [x] Input sesi pembelajaran per kelas (misal: Sesi 1: 2 JP, Sesi 2: 3 JP).
  - [x] Input URL Meeting Zoom, Meeting ID, dan Passcode pada masing-masing sesi.
- [x] **Step 2.5: Log Aktivitas Internal Cabang (Fitur PRD 2.4)**
  - [x] Halaman log aktivitas khusus lingkup cabang terkait bagi Admin Cabang.

---

### 🔹 FASE 3: Modul Pengguna Trainer (Konten Pembelajaran & Evaluasi)
> **Tujuan**: Trainer menyiapkan materi bank soal, merancang kuis evaluasi, dan mengelola link zoom sesi langsung.

- [x] **Step 3.1: Migrasi Bank Soal & Kuis**
  - [x] Tabel `questions` & `question_options` (pilihan ganda, true/false, esai singkat).
  - [x] Tabel `quizzes` (judul kuis, durasi waktu, passing grade, relasi ke kelas).
  - [x] Tabel `quiz_attempts` & `quiz_attempt_answers` (hasil pengerjaan peserta).
- [x] **Step 3.2: CRUD Bank Soal (Fitur PRD 2.2 & 3.1)**
  - [x] Manajemen soal oleh Admin Cabang & Trainer dengan isolasi multi-cabang.
  - [x] *Catatan Aturan Bisnis*: Seluruh soal berbobot setara/sama rata (1 poin, tanpa pembagian mudah/sedang/sulit).
  - [x] Opsi penentuan kunci jawaban dan penjelasan pembahasan.
- [x] **Step 3.3: CRUD Kuis (Fitur PRD 2.5 & 3.2)**
  - [x] Konfigurasi paket kuis untuk kelas yang diampu trainer.
  - [x] Pengaturan waktu pengerjaan (menit), passing grade (default: 75), batas percobaan (max attempts), dan pengacakan soal (is_randomized).
  - [x] Penilaian kuis manual untuk soal esai (disiapkan pada schema attempts & attempt answers).
- [x] **Step 3.4: Membuat & Memperbarui Link Zoom Sesi Live (Fitur PRD 3.6)**
  - [x] Akses cepat Trainer untuk mengubah atau membuat tautan Zoom instan pada sesi kelas yang diampunya.

---

### 🔹 FASE 4: Modul Pengguna Peserta (Katalog Kelas, Belajar & Tracking 20 JP)
> **Tujuan**: Peserta memilih kelas, masuk ruang zoom, belajar dan melacak pemenuhan 20 JP secara transparan.

- [ ] **Step 4.1: Migrasi Pendaftaran & Tracking JP**
  - [ ] Tabel `class_enrollments` (user_id, class_id, attendance_mode, completed_minutes, completed_jp, status).
  - [ ] Tabel `learning_progress` (pencatatan menit tiap modul/sesi yang telah diselesaikan).
- [ ] **Step 4.2: Katalog & Pemilihan Kelas (Fitur PRD 4.2)**
  - [ ] Halaman katalog kelas dengan filter Cabang, Tipe (Offline/Online/Hybrid), dan Jadwal.
  - [ ] Indikator sisa kuota realtime:
    - Kelas Offline: *"Tersisa X dari 40 Kursi"*. Jika 40 penuh $\rightarrow$ tombol daftar terkunci.
    - Kelas Hybrid: Peserta memilih opsi kehadiran (*"Hadir Fisik di Kelas - Maks 40"* atau *"Hadir Daring Zoom"*).
  - [ ] **Proteksi Concurrency**: Implementasi `lockForUpdate()` di database saat transaksi pendaftaran agar tidak terjadi *overbooking* kuota offline 40 orang.
- [ ] **Step 4.3: Masuk ke Zoom (Fitur PRD 4.6)**
  - [ ] Halaman ruang kelas dengan tombol *"Masuk Zoom Sekarang"*.
  - [ ] Validasi tombol aktif hanya pada jadwal sesi yang sedang berlangsung.
  - [ ] Pencatatan log kehadiran peserta saat klik masuk Zoom.
- [ ] **Step 4.4: Engine Perhitungan & Tracking 20 JP (Fitur PRD 4.1)**
  - [ ] **Logika Konversi**: `1 JP = 45 Menit` $\rightarrow$ Target Lulus = `20 JP = 900 Menit`.
  - [ ] Widget Progress Bar visual interaktif (*"15 / 20 JP (75%) Terpenuhi"*).
  - [ ] Checklist pemenuhan sesi modul materi dan kehadiran tatap muka.
- [ ] **Step 4.5: Pengerjaan Kuis & Cek Nilai (Fitur PRD 4.5)**
  - [ ] Antarmuka pengerjaan kuis interaktif dengan timer countdown mundur.
  - [ ] Tampilan hasil skor kuis dan status lulus kuis berdasarkan *passing grade*.

---

### 🔹 FASE 5: Verifikasi Kelulusan 20 JP & Penerbitan Sertifikat
> **Tujuan**: Siklus penutupan kelulusan pelatihan melalui persetujuan Trainer dan penyerahan sertifikat.

- [ ] **Step 5.1: Pengajuan Kelulusan Otomatis**
  - [ ] Jika peserta telah genap menyelesaikan akumulasi 20 JP (900 menit) dan seluruh kuis tuntas, status pendaftaran otomatis berubah menjadi `review_pending` (*Menunggu Verifikasi Trainer*).
- [ ] **Step 5.2: Dashboard Review & Approval Trainer (Fitur PRD 3.5)**
  - [ ] Trainer membuka daftar antrean peserta yang mengajukan kelulusan.
  - [ ] Trainer memeriksa rekapitulasi: Total JP, kehadiran sesi, dan nilai kuis.
  - [ ] **Aksi Trainer**:
    - **Terima (Approve)** $\rightarrow$ Peserta resmi berstatus `graduated` (Lulus).
    - **Tolak (Reject)** $\rightarrow$ Peserta berstatus `rejected` / wajib remedial. Trainer wajib menginput form alasan penolakan & instruksi perbaikan materi.
- [ ] **Step 5.3: Generator E-Sertifikat Digital (PDF + QR Code)**
  - [ ] Template sertifikat profesional dengan `barryvdh/laravel-dompdf`.
  - [ ] Penyematan QR Code verifikasi keaslian sertifikat dengan `simplesoftwareio/simple-qrcode`.
  - [ ] Download E-Sertifikat di dashboard peserta yang telah disetujui (Lulus).

---

### 🔹 FASE 6: Modul Pendukung (Forum Komunitas & Berita Informasi)
> **Tujuan**: Memfasilitasi interaksi sosial, tanya jawab, dan penyebaran pengumuman penting.

- [ ] **Step 6.1: Modul Berita & Informasi (Fitur PRD 3.4 & 4.3)**
  - [ ] Tabel `news_posts` (title, slug, content, thumbnail, branch_id/global, author_id).
  - [ ] Form publikasi berita oleh Super Admin / Admin Cabang / Trainer.
  - [ ] Halaman feed berita & detail pengumuman yang dapat dibaca oleh Peserta.
- [ ] **Step 6.2: Modul Forum / Komunitas Diskusi (Fitur PRD 3.3 & 4.4)**
  - [ ] Tabel `forum_threads` dan `forum_replies`.
  - [ ] Peserta dan Trainer dapat membuat topik diskusi per kelas.
  - [ ] Balasan berbalas (*nested/threaded reply*) antar peserta dan instruktur.
  - [ ] Fitur pin diskusi dan moderasi thread oleh Trainer.

---

### 🔹 FASE 7: Uji Coba, Optimasi & Penyempurnaan Akhir
> **Tujuan**: Menjamin stabilitas sistem, bebas bug, aman dari eksploitasi kuota, dan performa optimal.

- [ ] **Step 7.1: Automated Testing dengan Pest**
  - [ ] Feature test: Pendaftaran kelas offline menolak pendaftar ke-41 (kuota ketat 40).
  - [ ] Feature test: Perhitungan 20 JP (1 JP = 45 menit) akurat.
  - [ ] Feature test: Pengujian approval & reject oleh Trainer.
  - [ ] Feature test: Role-based authorization & branch data isolation.
- [ ] **Step 7.2: Code Style & Quality**
  - [ ] Eksekusi `vendor/bin/pint --format agent` untuk menjaga standar kode PSR-12 Laravel.
- [ ] **Step 7.3: Verifikasi Tampilan & Responsivitas Frontend**
  - [ ] Pengecekan responsif mobile pada dashboard semua role.
  - [ ] Build asset produksi via `npm run build`.

---

## 📌 Aturan Pengerjaan bagi Developer / AI Agent
1. **Pengerjaan Berurutan**: Selesaikan fase per fase. Jangan melompat ke Fase Peserta jika Fase Kelas/Trainer belum selesai.
2. **Update Checklist**: Setiap kali sebuah sub-task selesai diimplementasikan dan diverifikasi, ubah tanda `[ ]` menjadi `[x]`.
3. **Commit / Checkpoint Bersih**: Lakukan pengetesan fungsional dan format kode dengan Laravel Pint sebelum melanjutkan ke fase berikutnya.
