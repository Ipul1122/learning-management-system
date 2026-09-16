<?php

use App\Models\NewsPost;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

beforeEach(function () {
    /** @var TestCase $this */
    $this->seed();
    Storage::fake('public');
});

test('super admin dapat melihat daftar semua berita termasuk filter cabang', function () {
    /** @var TestCase $this */
    $superAdmin = User::where('email', 'superadmin@lms.test')->first();

    $response = $this->actingAs($superAdmin)->get(route('admin.news.index'));

    $response->assertStatus(200);
    $response->assertSee('Kelola Berita');
    $response->assertSee('Tulis Berita Baru');
});

test('super admin dapat menerbitkan berita global tanpa cabang', function () {
    /** @var TestCase $this */
    $superAdmin = User::where('email', 'superadmin@lms.test')->first();

    $payload = [
        'title' => 'Pengumuman Libur Nasional Global 2026',
        'category' => 'pengumuman',
        'branch_id' => '',
        'content' => 'Seluruh kegiatan pembelajaran pada tanggal merah diliburkan.',
        'is_published' => '1',
    ];

    $response = $this->actingAs($superAdmin)->post(route('admin.news.store'), $payload);

    $response->assertRedirect(route('admin.news.index'));
    $this->assertDatabaseHas('news_posts', [
        'title' => 'Pengumuman Libur Nasional Global 2026',
        'category' => 'pengumuman',
        'branch_id' => null,
        'is_published' => true,
    ]);
});

test('admin cabang dapat membuat berita yang otomatis terikat ke cabangnya', function () {
    /** @var TestCase $this */
    $adminCabang = User::where('email', 'admin.jkt@lms.test')->first();

    $payload = [
        'title' => 'Jadwal Workshop Offline Jakarta',
        'category' => 'kegiatan',
        'content' => 'Workshop offline akan diadakan di aula cabang Jakarta.',
        'is_published' => '1',
    ];

    $response = $this->actingAs($adminCabang)->post(route('admin.news.store'), $payload);

    $response->assertRedirect(route('admin.news.index'));
    $this->assertDatabaseHas('news_posts', [
        'title' => 'Jadwal Workshop Offline Jakarta',
        'branch_id' => $adminCabang->branch_id,
        'author_id' => $adminCabang->id,
    ]);
});

test('admin cabang tidak dapat mengedit atau menghapus berita cabang lain', function () {
    /** @var TestCase $this */
    $adminJakarta = User::where('email', 'admin.jkt@lms.test')->first();
    $adminSurabaya = User::where('email', 'admin.sby@lms.test')->first();

    $newsSurabaya = NewsPost::create([
        'branch_id' => $adminSurabaya->branch_id,
        'author_id' => $adminSurabaya->id,
        'title' => 'Berita Khusus Surabaya',
        'slug' => 'berita-khusus-surabaya',
        'category' => 'informasi',
        'content' => 'Konten untuk cabang Surabaya saja.',
        'is_published' => true,
        'published_at' => now(),
    ]);

    // Admin Jakarta coba akses edit berita Surabaya
    $responseEdit = $this->actingAs($adminJakarta)->get(route('admin.news.edit', $newsSurabaya));
    $responseEdit->assertStatus(403);

    // Admin Jakarta coba hapus berita Surabaya
    $responseDelete = $this->actingAs($adminJakarta)->delete(route('admin.news.destroy', $newsSurabaya));
    $responseDelete->assertStatus(403);
});

test('peserta dapat melihat feed berita yang sudah terbit', function () {
    /** @var TestCase $this */
    $peserta = User::where('email', 'peserta1@lms.test')->first();

    $publishedNews = NewsPost::create([
        'author_id' => User::where('email', 'superadmin@lms.test')->first()->id,
        'title' => 'Panduan Belajar Mandiri LMS',
        'slug' => 'panduan-belajar-mandiri-lms',
        'category' => 'edukasi',
        'content' => 'Ini adalah panduan komprehensif bagi seluruh peserta.',
        'is_published' => true,
        'published_at' => now(),
    ]);

    $response = $this->actingAs($peserta)->get(route('news.index'));

    $response->assertStatus(200);
    $response->assertSee('Panduan Belajar Mandiri LMS');
    $response->assertSee('Portal Informasi');
});

test('peserta tidak dapat melihat draft berita yang belum terbit', function () {
    /** @var TestCase $this */
    $peserta = User::where('email', 'peserta1@lms.test')->first();

    $draftNews = NewsPost::create([
        'author_id' => User::where('email', 'superadmin@lms.test')->first()->id,
        'title' => 'Draft Rahasia Internal Admin',
        'slug' => 'draft-rahasia-internal-admin',
        'category' => 'informasi',
        'content' => 'Berita ini masih berupa draft.',
        'is_published' => false,
        'published_at' => null,
    ]);

    // Public feed tidak menampilkan draft
    $responseFeed = $this->actingAs($peserta)->get(route('news.index'));
    $responseFeed->assertDontSee('Draft Rahasia Internal Admin');

    // Mengakses langsung URL slug draft menghasilkan 404
    $responseDetail = $this->actingAs($peserta)->get(route('news.show', $draftNews->slug));
    $responseDetail->assertStatus(404);
});

test('pencarian dan filter kategori berita berfungsi dengan baik', function () {
    /** @var TestCase $this */
    $peserta = User::where('email', 'peserta1@lms.test')->first();
    $author = User::where('email', 'superadmin@lms.test')->first();

    NewsPost::create([
        'author_id' => $author->id,
        'title' => 'Tips Lolos Ujian 20 JP',
        'slug' => 'tips-lolos-ujian-20-jp',
        'category' => 'edukasi',
        'content' => 'Pelajari silabus dan kerjakan kuis tepat waktu.',
        'is_published' => true,
        'published_at' => now(),
    ]);

    NewsPost::create([
        'author_id' => $author->id,
        'title' => 'Maintenance Server Malam Ini',
        'slug' => 'maintenance-server-malam-ini',
        'category' => 'pengumuman',
        'content' => 'Server akan down selama 15 menit.',
        'is_published' => true,
        'published_at' => now(),
    ]);

    // Filter kategori edukasi
    $responseFilter = $this->actingAs($peserta)->get(route('news.index', ['category' => 'edukasi']));
    $responseFilter->assertStatus(200);
    $responseFilter->assertSee('Tips Lolos Ujian 20 JP');
    $responseFilter->assertDontSee('Maintenance Server Malam Ini');

    // Search query
    $responseSearch = $this->actingAs($peserta)->get(route('news.index', ['search' => 'Maintenance']));
    $responseSearch->assertStatus(200);
    $responseSearch->assertSee('Maintenance Server Malam Ini');
    $responseSearch->assertDontSee('Tips Lolos Ujian 20 JP');
});
