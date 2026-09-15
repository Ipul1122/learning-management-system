<?php

namespace Tests\Feature\AdminCabang;

use App\Models\Branch;
use App\Models\ClassSession;
use App\Models\TrainingClass;
use App\Models\User;
use Carbon\Carbon;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClassManagementTest extends TestCase
{
    use RefreshDatabase;

    protected Branch $branch;

    protected User $adminCabang;

    protected User $trainer;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RolePermissionSeeder::class);

        $this->branch = Branch::create([
            'name' => 'Cabang Yogyakarta',
            'code' => 'CBG-YOG',
            'address' => 'Jl. Malioboro No. 5',
            'city' => 'Yogyakarta',
            'phone' => '0274-123456',
            'is_active' => true,
        ]);

        $this->adminCabang = User::factory()->create([
            'name' => 'Admin Jogja',
            'email' => 'admin.jogja@test.com',
            'branch_id' => $this->branch->id,
            'status' => 'active',
        ]);
        $this->adminCabang->assignRole('admin-cabang');

        $this->trainer = User::factory()->create([
            'name' => 'Mas Eko Trainer',
            'email' => 'eko@test.com',
            'branch_id' => $this->branch->id,
            'status' => 'active',
        ]);
        $this->trainer->assignRole('trainer');
    }

    public function test_admin_cabang_can_view_class_index_page(): void
    {
        $class = TrainingClass::create([
            'branch_id' => $this->branch->id,
            'trainer_id' => $this->trainer->id,
            'title' => 'Web Development Laravel 13',
            'slug' => 'web-development-laravel-13',
            'type' => 'offline',
            'offline_capacity' => 30,
            'start_date' => Carbon::now()->addDays(5),
            'end_date' => Carbon::now()->addDays(20),
            'status' => 'open',
        ]);

        $response = $this->actingAs($this->adminCabang)->get(route('cabang.classes.index'));

        $response->assertOk();
        $response->assertSee('Manajemen Kelas Pelatihan');
        $response->assertSee('Web Development Laravel 13');
    }

    public function test_admin_cabang_can_create_offline_class_within_max_40_capacity(): void
    {
        $payload = [
            'trainer_id' => $this->trainer->id,
            'title' => 'Flutter Mobile Masterclass',
            'type' => 'offline',
            'offline_capacity' => 40, // Maksimal 40 orang
            'start_date' => Carbon::now()->addDays(7)->format('Y-m-d'),
            'end_date' => Carbon::now()->addDays(25)->format('Y-m-d'),
            'status' => 'open',
            'required_jp' => 20,
            'description' => 'Kelas offline tatap muka langsung di lab komputer.',
        ];

        $response = $this->actingAs($this->adminCabang)->post(route('cabang.classes.store'), $payload);

        $response->assertRedirect();
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('classes', [
            'branch_id' => $this->branch->id,
            'trainer_id' => $this->trainer->id,
            'title' => 'Flutter Mobile Masterclass',
            'type' => 'offline',
            'offline_capacity' => 40,
        ]);
    }

    public function test_offline_class_fails_validation_if_capacity_exceeds_40(): void
    {
        $payload = [
            'trainer_id' => $this->trainer->id,
            'title' => 'Kelas Offline Over Capacity',
            'type' => 'offline',
            'offline_capacity' => 45, // Melebihi batas 40 orang
            'start_date' => Carbon::now()->addDays(7)->format('Y-m-d'),
            'end_date' => Carbon::now()->addDays(25)->format('Y-m-d'),
            'status' => 'open',
        ];

        $response = $this->actingAs($this->adminCabang)->post(route('cabang.classes.store'), $payload);

        $response->assertSessionHasErrors('offline_capacity');
        $this->assertDatabaseMissing('classes', [
            'title' => 'Kelas Offline Over Capacity',
        ]);
    }

    public function test_admin_cabang_can_create_online_class_with_flexible_capacity(): void
    {
        $payload = [
            'trainer_id' => $this->trainer->id,
            'title' => 'Fullstack Web Online Intensive',
            'type' => 'online',
            'online_capacity' => 500, // Online bisa ratusan
            'start_date' => Carbon::now()->addDays(10)->format('Y-m-d'),
            'end_date' => Carbon::now()->addDays(30)->format('Y-m-d'),
            'status' => 'open',
        ];

        $response = $this->actingAs($this->adminCabang)->post(route('cabang.classes.store'), $payload);

        $response->assertRedirect();
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('classes', [
            'branch_id' => $this->branch->id,
            'title' => 'Fullstack Web Online Intensive',
            'type' => 'online',
            'online_capacity' => 500,
        ]);
    }

    public function test_admin_cabang_can_create_hybrid_class_with_both_capacities(): void
    {
        $payload = [
            'trainer_id' => $this->trainer->id,
            'title' => 'Data Science Hybrid Bootcamp',
            'type' => 'hybrid',
            'offline_capacity' => 35, // Maksimal 40 fisik
            'online_capacity' => 300, // Ratusan online
            'start_date' => Carbon::now()->addDays(14)->format('Y-m-d'),
            'end_date' => Carbon::now()->addDays(40)->format('Y-m-d'),
            'status' => 'open',
        ];

        $response = $this->actingAs($this->adminCabang)->post(route('cabang.classes.store'), $payload);

        $response->assertRedirect();
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('classes', [
            'title' => 'Data Science Hybrid Bootcamp',
            'type' => 'hybrid',
            'offline_capacity' => 35,
            'online_capacity' => 300,
        ]);
    }

    public function test_admin_cabang_can_update_class_and_status(): void
    {
        $class = TrainingClass::create([
            'branch_id' => $this->branch->id,
            'trainer_id' => $this->trainer->id,
            'title' => 'UI/UX Design',
            'slug' => 'ui-ux-design',
            'type' => 'offline',
            'offline_capacity' => 20,
            'start_date' => Carbon::now()->addDays(5),
            'end_date' => Carbon::now()->addDays(15),
            'status' => 'draft',
        ]);

        $response = $this->actingAs($this->adminCabang)->patch(route('cabang.classes.update-status', $class), [
            'status' => 'ongoing',
        ]);

        $response->assertRedirect();
        $this->assertEquals('ongoing', $class->fresh()->status);
    }

    public function test_admin_cabang_can_add_session_with_jp_and_zoom_link(): void
    {
        $class = TrainingClass::create([
            'branch_id' => $this->branch->id,
            'trainer_id' => $this->trainer->id,
            'title' => 'Python for AI',
            'slug' => 'python-for-ai',
            'type' => 'online',
            'online_capacity' => 100,
            'start_date' => Carbon::now()->addDays(5),
            'end_date' => Carbon::now()->addDays(15),
            'status' => 'open',
        ]);

        $sessionPayload = [
            'title' => 'Sesi 1: Dasar Python & NumPy',
            'session_order' => 1,
            'jp_duration' => 2, // 2 JP = 90 menit
            'session_date' => Carbon::now()->addDays(6)->format('Y-m-d\TH:i'),
            'zoom_url' => 'https://zoom.us/j/987654321',
            'zoom_meeting_id' => '987 654 321',
            'zoom_passcode' => 'ai2026',
            'description' => 'Materi pengantar environment Python.',
        ];

        $response = $this->actingAs($this->adminCabang)->post(route('cabang.sessions.store', $class), $sessionPayload);

        $response->assertRedirect(route('cabang.classes.show', $class));

        $this->assertDatabaseHas('class_sessions', [
            'class_id' => $class->id,
            'title' => 'Sesi 1: Dasar Python & NumPy',
            'jp_duration' => 2,
            'minute_duration' => 90, // 2 JP * 45 menit = 90 menit
            'zoom_meeting_id' => '987 654 321',
        ]);

        // Pastikan relasi akumulasi JP dan durasi pada TrainingClass bekerja
        $this->assertEquals(2, $class->fresh()->totalAccumulatedJp());
        $this->assertEquals(90, $class->fresh()->totalAccumulatedMinutes());
    }

    public function test_admin_cabang_can_update_and_delete_class_session(): void
    {
        $class = TrainingClass::create([
            'branch_id' => $this->branch->id,
            'trainer_id' => $this->trainer->id,
            'title' => 'DevOps Cloud',
            'slug' => 'devops-cloud',
            'type' => 'online',
            'online_capacity' => 100,
            'start_date' => Carbon::now()->addDays(5),
            'end_date' => Carbon::now()->addDays(15),
            'status' => 'open',
        ]);

        $session = ClassSession::create([
            'class_id' => $class->id,
            'created_by_user_id' => $this->adminCabang->id,
            'title' => 'Sesi 1 Awal',
            'session_order' => 1,
            'jp_duration' => 2,
            'minute_duration' => 90,
            'session_date' => Carbon::now()->addDays(6),
        ]);

        // Update
        $response = $this->actingAs($this->adminCabang)->put(route('cabang.sessions.update', [$class, $session]), [
            'title' => 'Sesi 1: Intro Docker & Container',
            'session_order' => 1,
            'jp_duration' => 3, // Diubah ke 3 JP
            'session_date' => Carbon::now()->addDays(6)->format('Y-m-d\TH:i'),
        ]);

        $response->assertRedirect(route('cabang.classes.show', $class));
        $this->assertEquals(3, $session->fresh()->jp_duration);
        $this->assertEquals(135, $session->fresh()->minute_duration); // 3 JP * 45 = 135

        // Delete
        $deleteResponse = $this->actingAs($this->adminCabang)->delete(route('cabang.sessions.destroy', [$class, $session]));
        $deleteResponse->assertRedirect(route('cabang.classes.show', $class));
        $this->assertDatabaseMissing('class_sessions', ['id' => $session->id]);
    }
}
