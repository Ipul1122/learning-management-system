<?php

namespace Tests\Feature\AdminCabang;

use App\Models\ActivityLog;
use App\Models\Branch;
use App\Models\ClassSession;
use App\Models\TrainingClass;
use App\Models\User;
use Carbon\Carbon;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BranchIsolationTest extends TestCase
{
    use RefreshDatabase;

    protected Branch $branchJakarta;

    protected Branch $branchSurabaya;

    protected User $adminJakarta;

    protected User $adminSurabaya;

    protected User $trainerJakarta;

    protected User $trainerSurabaya;

    protected TrainingClass $classSurabaya;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RolePermissionSeeder::class);

        // Branch 1: Jakarta
        $this->branchJakarta = Branch::create([
            'name' => 'Cabang Jakarta',
            'code' => 'CBG-JKT',
            'address' => 'Jl. MH Thamrin No. 1',
            'city' => 'Jakarta',
            'is_active' => true,
        ]);

        $this->adminJakarta = User::factory()->create([
            'name' => 'Admin Jakarta',
            'email' => 'admin.jkt@test.com',
            'branch_id' => $this->branchJakarta->id,
            'status' => 'active',
        ]);
        $this->adminJakarta->assignRole('admin-cabang');

        $this->trainerJakarta = User::factory()->create([
            'name' => 'Trainer Jakarta',
            'email' => 'trainer.jkt@test.com',
            'branch_id' => $this->branchJakarta->id,
            'status' => 'active',
        ]);
        $this->trainerJakarta->assignRole('trainer');

        // Branch 2: Surabaya
        $this->branchSurabaya = Branch::create([
            'name' => 'Cabang Surabaya',
            'code' => 'CBG-SBY',
            'address' => 'Jl. Tunjungan No. 20',
            'city' => 'Surabaya',
            'is_active' => true,
        ]);

        $this->adminSurabaya = User::factory()->create([
            'name' => 'Admin Surabaya',
            'email' => 'admin.sby@test.com',
            'branch_id' => $this->branchSurabaya->id,
            'status' => 'active',
        ]);
        $this->adminSurabaya->assignRole('admin-cabang');

        $this->trainerSurabaya = User::factory()->create([
            'name' => 'Trainer Surabaya',
            'email' => 'trainer.sby@test.com',
            'branch_id' => $this->branchSurabaya->id,
            'status' => 'active',
        ]);
        $this->trainerSurabaya->assignRole('trainer');

        $this->classSurabaya = TrainingClass::create([
            'branch_id' => $this->branchSurabaya->id,
            'trainer_id' => $this->trainerSurabaya->id,
            'title' => 'Kelas Rahasia Surabaya',
            'slug' => 'kelas-rahasia-surabaya',
            'type' => 'offline',
            'offline_capacity' => 25,
            'start_date' => Carbon::now()->addDays(5),
            'end_date' => Carbon::now()->addDays(20),
            'status' => 'open',
        ]);
    }

    public function test_admin_jakarta_cannot_edit_or_delete_trainer_from_surabaya(): void
    {
        // Edit attempt
        $editResponse = $this->actingAs($this->adminJakarta)->get(route('cabang.trainers.edit', $this->trainerSurabaya));
        $editResponse->assertForbidden();

        // Update attempt
        $updateResponse = $this->actingAs($this->adminJakarta)->put(route('cabang.trainers.update', $this->trainerSurabaya), [
            'name' => 'Hacked Trainer Name',
            'email' => $this->trainerSurabaya->email,
            'status' => 'active',
        ]);
        $updateResponse->assertForbidden();
        $this->assertNotEquals('Hacked Trainer Name', $this->trainerSurabaya->fresh()->name);

        // Delete attempt
        $deleteResponse = $this->actingAs($this->adminJakarta)->delete(route('cabang.trainers.destroy', $this->trainerSurabaya));
        $deleteResponse->assertForbidden();
        $this->assertDatabaseHas('users', ['id' => $this->trainerSurabaya->id]);
    }

    public function test_admin_jakarta_cannot_view_edit_or_delete_class_from_surabaya(): void
    {
        // Show attempt
        $showResponse = $this->actingAs($this->adminJakarta)->get(route('cabang.classes.show', $this->classSurabaya));
        $showResponse->assertForbidden();

        // Edit attempt
        $editResponse = $this->actingAs($this->adminJakarta)->get(route('cabang.classes.edit', $this->classSurabaya));
        $editResponse->assertForbidden();

        // Update attempt
        $updateResponse = $this->actingAs($this->adminJakarta)->put(route('cabang.classes.update', $this->classSurabaya), [
            'trainer_id' => $this->trainerJakarta->id,
            'title' => 'Bajak Kelas Surabaya',
            'type' => 'offline',
            'offline_capacity' => 30,
            'start_date' => Carbon::now()->addDays(1)->format('Y-m-d'),
            'end_date' => Carbon::now()->addDays(10)->format('Y-m-d'),
            'status' => 'open',
        ]);
        $updateResponse->assertForbidden();
        $this->assertNotEquals('Bajak Kelas Surabaya', $this->classSurabaya->fresh()->title);

        // Delete attempt
        $deleteResponse = $this->actingAs($this->adminJakarta)->delete(route('cabang.classes.destroy', $this->classSurabaya));
        $deleteResponse->assertForbidden();
        $this->assertDatabaseHas('classes', ['id' => $this->classSurabaya->id]);
    }

    public function test_admin_jakarta_cannot_manage_sessions_of_surabaya_class(): void
    {
        // Add session to Surabaya class attempt
        $storeResponse = $this->actingAs($this->adminJakarta)->post(route('cabang.sessions.store', $this->classSurabaya), [
            'title' => 'Sesi Palsu Jakarta',
            'session_order' => 1,
            'jp_duration' => 2,
            'session_date' => Carbon::now()->addDays(6)->format('Y-m-d\TH:i'),
        ]);
        $storeResponse->assertForbidden();

        $sessionSurabaya = ClassSession::create([
            'class_id' => $this->classSurabaya->id,
            'created_by_user_id' => $this->adminSurabaya->id,
            'title' => 'Sesi Asli Surabaya',
            'session_order' => 1,
            'jp_duration' => 2,
            'minute_duration' => 90,
            'session_date' => Carbon::now()->addDays(6),
        ]);

        // Edit session attempt
        $editResponse = $this->actingAs($this->adminJakarta)->get(route('cabang.sessions.edit', [$this->classSurabaya, $sessionSurabaya]));
        $editResponse->assertForbidden();

        // Delete session attempt
        $deleteResponse = $this->actingAs($this->adminJakarta)->delete(route('cabang.sessions.destroy', [$this->classSurabaya, $sessionSurabaya]));
        $deleteResponse->assertForbidden();
        $this->assertDatabaseHas('class_sessions', ['id' => $sessionSurabaya->id]);
    }

    public function test_admin_jakarta_cannot_view_activity_logs_of_surabaya(): void
    {
        $logSurabaya = ActivityLog::record(
            action: 'UPDATE',
            description: 'Surabaya internal operation',
            target: $this->classSurabaya,
            old: ['title' => 'Old Title'],
            new: ['title' => 'New Title'],
            branchId: $this->branchSurabaya->id
        );

        $response = $this->actingAs($this->adminJakarta)->get(route('cabang.logs.show', $logSurabaya));
        $response->assertForbidden();
    }
}
