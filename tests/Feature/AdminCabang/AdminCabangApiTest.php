<?php

namespace Tests\Feature\AdminCabang;

use App\Models\ActivityLog;
use App\Models\Branch;
use App\Models\TrainingClass;
use App\Models\User;
use Carbon\Carbon;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AdminCabangApiTest extends TestCase
{
    use RefreshDatabase;

    protected Branch $branch;

    protected User $adminCabang;

    protected User $trainer;

    protected Branch $otherBranch;

    protected User $otherAdmin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RolePermissionSeeder::class);

        $this->branch = Branch::create([
            'name' => 'Cabang Semarang',
            'code' => 'CBG-SMG',
            'address' => 'Jl. Pandanaran No. 10',
            'city' => 'Semarang',
            'is_active' => true,
        ]);

        $this->adminCabang = User::factory()->create([
            'name' => 'Admin Semarang',
            'email' => 'admin.smg@test.com',
            'branch_id' => $this->branch->id,
            'status' => 'active',
        ]);
        $this->adminCabang->assignRole('admin-cabang');

        $this->trainer = User::factory()->create([
            'name' => 'Trainer Semarang',
            'email' => 'trainer.smg@test.com',
            'branch_id' => $this->branch->id,
            'status' => 'active',
        ]);
        $this->trainer->assignRole('trainer');

        $this->otherBranch = Branch::create([
            'name' => 'Cabang Medan',
            'code' => 'CBG-MDN',
            'address' => 'Jl. Gatot Subroto No. 5',
            'city' => 'Medan',
            'is_active' => true,
        ]);

        $this->otherAdmin = User::factory()->create([
            'name' => 'Admin Medan',
            'email' => 'admin.mdn@test.com',
            'branch_id' => $this->otherBranch->id,
            'status' => 'active',
        ]);
        $this->otherAdmin->assignRole('admin-cabang');
    }

    public function test_api_admin_cabang_can_list_trainers(): void
    {
        Sanctum::actingAs($this->adminCabang);

        $response = $this->getJson('/api/v1/admin-cabang/trainers');

        $response->assertOk();
        $response->assertJsonFragment([
            'name' => 'Trainer Semarang',
            'email' => 'trainer.smg@test.com',
        ]);
    }

    public function test_api_admin_cabang_can_create_trainer(): void
    {
        Sanctum::actingAs($this->adminCabang);

        $response = $this->postJson('/api/v1/admin-cabang/trainers', [
            'name' => 'Trainer Baru API',
            'email' => 'trainer.api@test.com',
            'phone_number' => '0812345678',
            'status' => 'active',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
        ]);

        $response->assertCreated();
        $this->assertDatabaseHas('users', [
            'email' => 'trainer.api@test.com',
            'branch_id' => $this->branch->id,
            'phone_number' => '0812345678',
        ]);
    }

    public function test_api_admin_cabang_cannot_create_offline_class_exceeding_40_capacity(): void
    {
        Sanctum::actingAs($this->adminCabang);

        $response = $this->postJson('/api/v1/admin-cabang/classes', [
            'trainer_id' => $this->trainer->id,
            'title' => 'Offline Kelas Melebihi Kuota',
            'type' => 'offline',
            'offline_capacity' => 50, // Lebih dari 40
            'start_date' => Carbon::now()->addDays(5)->format('Y-m-d'),
            'end_date' => Carbon::now()->addDays(15)->format('Y-m-d'),
            'status' => 'open',
        ]);

        $response->assertUnprocessable();
        $response->assertJsonValidationErrors('offline_capacity');
    }

    public function test_api_admin_cabang_can_create_class_and_session_with_jp_duration(): void
    {
        Sanctum::actingAs($this->adminCabang);

        // 1. Buat Kelas
        $classResponse = $this->postJson('/api/v1/admin-cabang/classes', [
            'trainer_id' => $this->trainer->id,
            'title' => 'Golang Backend Mastery',
            'type' => 'online',
            'online_capacity' => 200,
            'start_date' => Carbon::now()->addDays(5)->format('Y-m-d'),
            'end_date' => Carbon::now()->addDays(25)->format('Y-m-d'),
            'status' => 'open',
        ]);

        $classResponse->assertCreated();
        $classId = $classResponse->json('data.id');

        // 2. Tambah Sesi Zoom dengan 2 JP (90 menit)
        $sessionResponse = $this->postJson("/api/v1/admin-cabang/classes/{$classId}/sessions", [
            'title' => 'Sesi 1: Goroutine & Concurrency',
            'session_order' => 1,
            'jp_duration' => 2,
            'session_date' => Carbon::now()->addDays(7)->format('Y-m-d\TH:i'),
            'zoom_url' => 'https://zoom.us/j/123456789',
        ]);

        $sessionResponse->assertCreated();
        $sessionResponse->assertJsonFragment([
            'jp_duration' => 2,
            'minute_duration' => 90,
            'zoom_url' => 'https://zoom.us/j/123456789',
        ]);
    }

    public function test_api_cross_branch_access_is_forbidden(): void
    {
        $trainerMedan = User::factory()->create([
            'name' => 'Trainer Medan',
            'email' => 'trainer.medan@test.com',
            'branch_id' => $this->otherBranch->id,
            'status' => 'active',
        ]);
        $trainerMedan->assignRole('trainer');

        $classMedan = TrainingClass::create([
            'branch_id' => $this->otherBranch->id,
            'trainer_id' => $trainerMedan->id,
            'title' => 'Kelas Medan Terisolasi',
            'slug' => 'kelas-medan-terisolasi',
            'type' => 'online',
            'online_capacity' => 100,
            'start_date' => Carbon::now()->addDays(5),
            'end_date' => Carbon::now()->addDays(20),
            'status' => 'open',
        ]);

        Sanctum::actingAs($this->adminCabang);

        // Admin Semarang mencoba akses kelas Medan via API
        $response = $this->getJson("/api/v1/admin-cabang/classes/{$classMedan->id}");
        $response->assertForbidden();
    }

    public function test_api_admin_cabang_can_view_branch_activity_logs(): void
    {
        ActivityLog::record(
            action: 'CREATE',
            description: 'Penambahan trainer cabang Semarang',
            target: $this->trainer,
            old: null,
            new: $this->trainer->toArray(),
            branchId: $this->branch->id
        );

        Sanctum::actingAs($this->adminCabang);

        $response = $this->getJson('/api/v1/admin-cabang/logs');

        $response->assertOk();
        $response->assertJsonFragment([
            'action' => 'CREATE',
            'description' => 'Penambahan trainer cabang Semarang',
        ]);
    }
}
