<?php

use App\Models\User;
use Spatie\Permission\Models\Role;

beforeEach(function () {
    Role::firstOrCreate(['name' => 'peserta']);
    Role::firstOrCreate(['name' => 'super-admin']);
});

test('halaman 404 menampilkan template kustom not found', function () {
    $response = $this->get('/halaman-acak-pasti-tidak-ada-xyz-999');

    $response->assertStatus(404);
    $response->assertSee('HTTP 404 • HALAMAN TIDAK DITEMUKAN (NOT FOUND)');
    $response->assertSee('Oops! Halaman Ini');
    $response->assertSee('Tidak Ditemukan');
});

test('halaman 403 menampilkan template kustom forbidden', function () {
    $peserta = User::factory()->create();
    $peserta->assignRole('peserta');

    // Peserta mencoba mengakses area khusus super-admin
    $response = $this->actingAs($peserta)->get(route('admin.branches.index'));

    $response->assertStatus(403);
    $response->assertSee('HTTP 403 • AKSES DITOLAK (FORBIDDEN)');
    $response->assertSee('Area Terbatas');
    $response->assertSee('Izin Tidak Memadai');
    $response->assertSee($peserta->name);
});
