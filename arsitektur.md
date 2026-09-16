# 🏛️ ARSITEKTUR SISTEM & STRUKTUR FOLDER (`arsitektur.md`)
## Learning Management System (LMS) Terpadu Multi-Cabang

Dokumen ini mendefinisikan standar arsitektur sistem, struktur direktori/folder, konvensi penamaan, dan pola aliran data (*data flow*) untuk memastikan kode tetap modular, rapi, dan mudah dikelola seiring bertambahnya fitur.

---

## 1. Ikhtisar Arsitektur (Architecture Overview)

Sistem ini mengadopsi pola **Modular Role-Based Monolith with Dual-Layer Delivery**:

```
                              ┌─────────────────────────────────────────┐
                              │           PENGGUNA / CLIENT             │
                              │ (Desktop Browser, Mobile Browser, App)  │
                              └────────────────────┬────────────────────┘
                                                   │
                         ┌─────────────────────────┴─────────────────────────┐
                         │                                                   │
                         ▼                                                   ▼
             [ WEB LAYER (BLADE) ]                               [ REST API V1 LAYER ]
             - Tailwind CSS + Alpine.js                          - Laravel Sanctum Auth
             - SweetAlert2 Dialog/Toast                          - Eloquent API Resources
             - Mobile-First Responsive                           - JSON Response Standard
                         │                                                   │
                         └─────────────────────────┬─────────────────────────┘
                                                   │
                                                   ▼
                                      [ ROLE-BASED MIDDLEWARE ]
                                      - auth / auth:sanctum
                                      - role:super-admin | admin-cabang | trainer | peserta
                                      - branch data isolation check
                                                   │
                                                   ▼
                                      [ FORM REQUEST VALIDATION ]
                                      - SuperAdmin/ | AdminCabang/ | Trainer/ | Peserta/
                                                   │
                                                   ▼
                                     [ CONTROLLERS & SERVICES ]
                                      - SuperAdmin/ | AdminCabang/ | Trainer/ | Peserta/
                                                   │
                                                   ▼
                                        [ ELOQUENT ORM MODELS ]
                                      - Scopes, Mutators, Relationships
                                      - Auto Audit Trail (ActivityLog)
                                                   │
                                     ┌─────────────┴─────────────┐
                                     ▼                           ▼
                           [ BASIS DATA (MySQL) ]       [ CACHE STORE (Redis) ]
                           - Foreign Key Constraints    - Predis Driver (Port 6379)
                           - Pessimistic Locks (40 JP)  - Session & Cache Accelerator
```

---

## 2. Struktur Pohon Direktori (Directory Tree)

Semua komponen logika (Controller, Request, Resource, View, dan Seeder) dipisahkan secara tegas ke dalam folder peran masing-masing:

### 2.1 Direktori Aplikasi (`app/Http/`)

```text
app/Http/
├── Controllers/
│   ├── Auth/                               # Modul Otentikasi Bawaan (Breeze)
│   ├── DashboardController.php             # Dispatcher Dashboard Utama
│   │
│   ├── SuperAdmin/                         # [PERAN 1] Web Controller Super Admin
│   │   ├── DashboardController.php
│   │   ├── BranchController.php            # CRUD Master Cabang
│   │   ├── AdminCabangController.php       # CRUD Akun Admin Cabang
│   │   └── ActivityLogController.php       # Viewer Audit Trail Global
│   │
│   ├── AdminCabang/                        # [PERAN 2] Web Controller Admin Cabang
│   │   ├── DashboardController.php
│   │   ├── TrainerController.php           # CRUD Trainer Cabang
│   │   ├── ClassController.php             # CRUD Kelas (Offline 40, Online, Hybrid)
│   │   ├── ClassScheduleController.php     # Jadwal Sesi & Link Zoom
│   │   └── ActivityLogController.php       # Viewer Log Internal Cabang
│   │
│   ├── Trainer/                            # [PERAN 3] Web Controller Trainer
│   │   ├── DashboardController.php
│   │   ├── QuestionBankController.php      # CRUD Bank Soal
│   │   ├── QuizController.php              # CRUD Kuis
│   │   ├── ZoomSessionController.php       # Generate/Update Link Zoom
│   │   └── GraduationReviewController.php  # Verifikasi 20 JP (Approve / Reject)
│   │
│   ├── Peserta/                            # [PERAN 4] Web Controller Peserta
│   │   ├── DashboardController.php
│   │   ├── ClassCatalogController.php      # Pilih Kelas (Proteksi Kuota 40)
│   │   ├── StudyRoomController.php         # Ruang Belajar & 1-Click Zoom
│   │   ├── QuizAttemptController.php       # Pengerjaan Kuis
│   │   └── GraduationController.php        # Cek Hasil & E-Sertifikat
│   │
│   └── Api/
│       └── V1/                             # RESTful API Endpoints (Sanctum)
│           ├── SuperAdmin/
│           │   ├── BranchApiController.php
│           │   ├── AdminCabangApiController.php
│           │   └── ActivityLogApiController.php
│           ├── AdminCabang/
│           │   ├── TrainerApiController.php
│           │   ├── ClassApiController.php
│           │   └── ScheduleApiController.php
│           ├── Trainer/
│           │   ├── QuestionBankApiController.php   # API Bank Soal
│           │   ├── QuizApiController.php           # API Paket Kuis
│           │   ├── ClassScheduleApiController.php  # API Kelas & Update Zoom Sesi
│           │   └── GraduationReviewApiController.php
│           └── Peserta/
│               ├── ClassCatalogApiController.php
│               ├── AttendanceApiController.php
│               └── QuizApiController.php
│
├── Requests/                               # Form Request Validasi Input
│   ├── SuperAdmin/
│   │   ├── StoreBranchRequest.php
│   │   ├── UpdateBranchRequest.php
│   │   ├── StoreAdminCabangRequest.php
│   │   └── UpdateAdminCabangRequest.php
│   ├── AdminCabang/
│   ├── Trainer/
│   │   ├── StoreQuestionRequest.php
│   │   ├── UpdateQuestionRequest.php
│   │   ├── StoreQuizRequest.php
│   │   ├── UpdateQuizRequest.php
│   │   └── UpdateZoomSessionRequest.php
│   └── Peserta/
│
└── Resources/                              # Eloquent API Resources (JSON Transformers)
    ├── SuperAdmin/
    │   ├── BranchResource.php
    │   ├── AdminCabangResource.php
    │   └── ActivityLogResource.php
    ├── AdminCabang/
    ├── Trainer/
    │   ├── QuestionResource.php
    │   └── QuizResource.php
    └── Peserta/
```

---

### 2.2 Direktori Model Data (`app/Models/`)

Entitas data berada di `app/Models/` dengan nama tunggal (*singular*). Model `User` bertindak sebagai model multi-peran dengan bantuan Query Scope:

```text
app/Models/
├── User.php                    # Multi-Role User (scopes: superAdmins, adminCabangs, trainers, pesertas)
├── Branch.php                  # Kantor Cabang Pelatihan
├── ActivityLog.php             # Audit Trail Record (helper ActivityLog::record)
├── Classes.php                 # Kelas Pelatihan (Offline, Online, Hybrid)
├── ClassSession.php            # Sesi Pertemuan (1 JP = 45 mnt, URL Zoom)
├── ClassEnrollment.php         # Pendaftaran Peserta & Akumulasi JP
├── SessionAttendance.php       # Presensi Sesi & Menit Kehadiran
├── Question.php                # Bank Soal (Bobot Setara)
├── QuestionOption.php          # Pilihan Jawaban Soal
├── Quiz.php                    # Paket Kuis Evaluasi
├── QuizAttempt.php             # Hasil Pengerjaan Kuis
├── QuizAttemptAnswer.php       # Butir Jawaban Peserta
├── GraduationSubmission.php    # Verifikasi Kelulusan 20 JP & Sertifikat
├── NewsPost.php                # Artikel & Berita Pengumuman
├── ForumThread.php             # Topik Diskusi Komunitas
└── ForumReply.php              # Balasan Diskusi
```

---

### 2.3 Direktori Tampilan (`resources/views/`)

Folder views dipetakan sesuai pembagian peran pengguna dengan konsistensi penamaan:

```text
resources/views/
├── layouts/
│   ├── app.blade.php           # Layout Utama (delegasi otomatis ke sidebar & navbar peran pengguna)
│   ├── navigation.blade.php    # Navigasi Topbar Standar (Guest/Fallback)
│   ├── guest.blade.php         # Layout Login/Register
│   │
│   ├── superAdmin/             # [Layout Khusus Super Admin]
│   │   ├── app.blade.php       # Master Layout Super Admin (<x-super-admin-layout>)
│   │   ├── sidebar.blade.php   # Sidebar Desktop & Mobile Drawer Super Admin
│   │   └── navbar.blade.php    # Top Navbar & Breadcrumb Super Admin
│   │
│   ├── admin/                  # [Layout Khusus Admin Cabang]
│   │   ├── app.blade.php       # Master Layout Admin Cabang (<x-admin-layout>)
│   │   ├── sidebar.blade.php   # Sidebar Desktop & Mobile Drawer Admin Cabang
│   │   └── navbar.blade.php    # Top Navbar & Branch Badge Admin Cabang
│   │
│   ├── trainer/                # [Layout Khusus Trainer]
│   │   ├── app.blade.php       # Master Layout Trainer (<x-trainer-layout>)
│   │   ├── sidebar.blade.php   # Sidebar Trainer
│   │   └── navbar.blade.php    # Navbar Trainer
│   │
│   └── peserta/                # [Layout Khusus Peserta]
│       ├── app.blade.php       # Master Layout Peserta (<x-peserta-layout>)
│       ├── sidebar.blade.php   # Sidebar Ruang Belajar Peserta
│       └── navbar.blade.php    # Navbar Peserta
├── components/                 # Blade UI Components (Modal, Button, Input, Card)
│
├── super-admin/                # Views Peran Super Admin
│   ├── dashboard.blade.php
│   ├── branches/
│   │   ├── index.blade.php     # Grid & list card cabang
│   │   ├── create.blade.php
│   │   └── edit.blade.php
│   ├── admins/
│   │   ├── index.blade.php     # Daftar Admin Cabang
│   │   ├── create.blade.php
│   │   └── edit.blade.php
│   └── logs/
│       ├── index.blade.php     # Timeline Log Aktivitas
│       └── show-modal.blade.php# Modal Inspeksi JSON
│
├── admin-cabang/               # Views Peran Admin Cabang
│   ├── dashboard.blade.php
│   ├── trainers/
│   ├── classes/
│   └── schedules/
│
├── trainer/                    # Views Peran Trainer
│   ├── dashboard.blade.php
│   ├── questions/
│   ├── quizzes/
│   └── graduations/            # Review & Approval Kelulusan 20 JP
│
└── peserta/                    # Views Peran Peserta
    ├── dashboard.blade.php     # Dashboard dengan Widget Visual 20 JP
    ├── catalog/                # Katalog Kelas & Filter Kuota 40
    ├── room/                   # Ruang Belajar & Tombol 1-Click Zoom
    ├── quizzes/                # Pengerjaan Kuis
    └── results/                # Hasil Kelulusan & Unduh E-Sertifikat
```

---

### 2.4 Direktori Database Seeders (`database/seeders/`)

Seeder dibagi per modul dan peran agar memudahkan reset data per bagian:

```text
database/seeders/
├── DatabaseSeeder.php          # Seeder Induk (memanggil seluruh seeder)
├── RolePermissionSeeder.php    # Inisialisasi 4 Role Spatie
├── BranchSeeder.php            # Seed Kantor Cabang
├── SuperAdmin/
│   └── SuperAdminSeeder.php    # Akun Super Administrator
├── AdminCabang/
│   └── AdminCabangSeeder.php   # Akun Admin Cabang Jakarta & Surabaya
├── Trainer/
│   └── TrainerSeeder.php       # Akun Trainer Pengajar
└── Peserta/
    └── PesertaSeeder.php       # Akun Siswa / Demo Peserta
```

---

## 3. Konvensi Penamaan & Standar Kode (Conventions)

| Elemen | Format Konvensi | Contoh |
| :--- | :--- | :--- |
| **Model** | PascalCase, Singular | `Branch.php`, `ClassSession.php` |
| **Tabel Database** | snake_case, Plural | `branches`, `class_sessions` |
| **Controller Web** | PascalCase + Controller | `BranchController.php` |
| **Controller API** | PascalCase + ApiController | `BranchApiController.php` |
| **Form Request** | Action + Entity + Request | `StoreBranchRequest.php`, `UpdateBranchRequest.php` |
| **API Resource** | Entity + Resource | `BranchResource.php` |
| **Nama Route Web** | dot.notation | `admin.branches.index`, `admin.branches.create` |
| **URL Route Web** | kebab-case | `/admin/branches`, `/admin/admin-cabang` |
| **URL Route API** | kebab-case ber-prefix versi | `/api/v1/super-admin/branches`, `/api/v1/super-admin/admins` |
| **Folder View** | kebab-case | `resources/views/super-admin/branches/` |

---

## 4. Aliran Permintaan Data (Request Flow)

### 4.1 Alur Web (Blade Monolith)
1. **HTTP Request** $\rightarrow$ Rute di `routes/web.php`.
2. **Middleware**: Mengecek sesi login (`auth`) dan peran pengguna (`role:super-admin`).
3. **Form Request**: Memvalidasi input pengguna (contoh: `StoreBranchRequest`).
4. **Web Controller**: Menjalankan logika bisnis, mencatat log (`ActivityLog::record`), dan mengambil data via Eloquent.
5. **Blade View**: Merender HTML responsif dengan Tailwind CSS, font Montserrat & Quicksand, serta dialog SweetAlert2.

### 4.2 Alur REST API v1
1. **HTTP Request** $\rightarrow$ Rute di `routes/api.php` (`/api/v1/...`).
2. **Middleware**: Mengecek token (`auth:sanctum`) dan peran (`role:super-admin`).
3. **Form Request**: Memvalidasi payload JSON request.
4. **API Controller**: Menjalankan logika bisnis, mencatat log (`ActivityLog::record`).
5. **Eloquent Resource**: Mentransformasikan data menjadi format JSON standar yang konsisten:
   ```json
   {
     "success": true,
     "message": "Data cabang berhasil diambil",
     "data": [ ... ]
   }
   ```

---

## 5. Keamanan & Isolasi Data Cabang (Multi-Tenant Isolation)

* **Super Admin**: Memiliki visibilitas data **global** (semua cabang).
* **Admin Cabang & Trainer**: Diisolasi secara otomatis berdasarkan kolom `branch_id`.
* **Proteksi Mutasi**: Setiap controller cabang selalu memvalidasi:
  ```php
  if ($model->branch_id !== auth()->user()->branch_id && !auth()->user()->hasRole('super-admin')) {
      abort(403, 'Akses ditolak: Data ini berada di luar wewenang cabang Anda.');
  }
  ```
