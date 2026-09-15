<?php

use App\Models\ActivityLog;
use App\Models\Branch;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

beforeEach(function () {
    /** @var TestCase $this */
    $this->seed();
});

test('semua 4 peran utama LMS terdefinisi dalam database', function () {
    $expectedRoles = ['super-admin', 'admin-cabang', 'trainer', 'peserta'];

    foreach ($expectedRoles as $roleName) {
        expect(Role::where('name', $roleName)->exists())->toBeTrue();
    }
});

test('cabang contoh berhasil di-seed dengan benar', function () {
    $jkt = Branch::where('code', 'JKT-01')->first();
    $sby = Branch::where('code', 'SBY-01')->first();

    expect($jkt)->not->toBeNull()
        ->and($jkt->name)->toBe('Cabang Jakarta Pusat')
        ->and($jkt->is_active)->toBeTrue();

    expect($sby)->not->toBeNull()
        ->and($sby->name)->toBe('Cabang Surabaya');
});

test('akun uji untuk masing-masing peran memiliki role yang sesuai', function () {
    $superAdmin = User::where('email', 'superadmin@lms.test')->first();
    $adminJkt = User::where('email', 'admin.jkt@lms.test')->first();
    $trainer1 = User::where('email', 'trainer1@lms.test')->first();
    $peserta1 = User::where('email', 'peserta1@lms.test')->first();

    expect($superAdmin->hasRole('super-admin'))->toBeTrue();
    expect($adminJkt->hasRole('admin-cabang'))->toBeTrue();
    expect($trainer1->hasRole('trainer'))->toBeTrue();
    expect($peserta1->hasRole('peserta'))->toBeTrue();

    // Pastikan relasi cabang tersambung
    expect($adminJkt->branch)->not->toBeNull();
    expect($trainer1->branch)->not->toBeNull();
});

test('super admin login dan mengakses dashboard admin', function () {
    /** @var TestCase $this */
    $superAdmin = User::where('email', 'superadmin@lms.test')->first();

    $response = $this->actingAs($superAdmin)->get(route('dashboard'));
    $response->assertRedirect(route('admin.dashboard'));

    $dashboardResponse = $this->actingAs($superAdmin)->get(route('admin.dashboard'));
    $dashboardResponse->assertStatus(200);
    $dashboardResponse->assertSee('Dashboard Super Admin');
});

test('admin cabang login dan mengakses dashboard cabang', function () {
    /** @var TestCase $this */
    $adminJkt = User::where('email', 'admin.jkt@lms.test')->first();

    $response = $this->actingAs($adminJkt)->get(route('dashboard'));
    $response->assertRedirect(route('cabang.dashboard'));

    $dashboardResponse = $this->actingAs($adminJkt)->get(route('cabang.dashboard'));
    $dashboardResponse->assertStatus(200);
    $dashboardResponse->assertSee('Dashboard Operasional Cabang');
});

test('trainer login dan mengakses dashboard trainer', function () {
    /** @var TestCase $this */
    $trainer1 = User::where('email', 'trainer1@lms.test')->first();

    $response = $this->actingAs($trainer1)->get(route('dashboard'));
    $response->assertRedirect(route('trainer.dashboard'));

    $dashboardResponse = $this->actingAs($trainer1)->get(route('trainer.dashboard'));
    $dashboardResponse->assertStatus(200);
    $dashboardResponse->assertSee('Dashboard Trainer');
});

test('peserta login dan mengakses dashboard peserta', function () {
    /** @var TestCase $this */
    $peserta1 = User::where('email', 'peserta1@lms.test')->first();

    $response = $this->actingAs($peserta1)->get(route('dashboard'));
    $response->assertStatus(200);
    $response->assertSee('Dashboard Belajar Saya');
    $response->assertSee('20.0 JP');
});

test('peserta dilarang mengakses dashboard super admin (403 forbidden)', function () {
    /** @var TestCase $this */
    $peserta1 = User::where('email', 'peserta1@lms.test')->first();

    $response = $this->actingAs($peserta1)->get(route('admin.dashboard'));
    $response->assertStatus(403);
});

test('activity log dapat mencatat audit trail dengan benar', function () {
    /** @var TestCase $this */
    $superAdmin = User::where('email', 'superadmin@lms.test')->first();
    $this->actingAs($superAdmin);

    $log = ActivityLog::record(
        action: 'TEST_AUDIT',
        description: 'Uji coba pencatatan audit trail',
        target: $superAdmin,
        old: ['status' => 'inactive'],
        new: ['status' => 'active']
    );

    expect($log)->not->toBeNull()
        ->and($log->action)->toBe('TEST_AUDIT')
        ->and($log->user_id)->toBe($superAdmin->id)
        ->and($log->properties_new['status'])->toBe('active');

    expect(ActivityLog::where('action', 'TEST_AUDIT')->exists())->toBeTrue();
});
