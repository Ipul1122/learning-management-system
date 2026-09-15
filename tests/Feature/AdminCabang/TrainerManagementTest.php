<?php

namespace Tests\Feature\AdminCabang;

use App\Models\Branch;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TrainerManagementTest extends TestCase
{
    use RefreshDatabase;

    protected Branch $branch;

    protected User $adminCabang;

    protected User $otherAdmin;

    protected Branch $otherBranch;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RolePermissionSeeder::class);

        $this->branch = Branch::create([
            'name' => 'Cabang Bandung',
            'code' => 'CBG-BDG',
            'address' => 'Jl. Dago No. 10',
            'city' => 'Bandung',
            'phone' => '022-1234567',
            'is_active' => true,
        ]);

        $this->adminCabang = User::factory()->create([
            'name' => 'Admin Bandung',
            'email' => 'admin.bandung@test.com',
            'branch_id' => $this->branch->id,
            'status' => 'active',
        ]);
        $this->adminCabang->assignRole('admin-cabang');

        $this->otherBranch = Branch::create([
            'name' => 'Cabang Surabaya',
            'code' => 'CBG-SBY',
            'address' => 'Jl. Pemuda No. 20',
            'city' => 'Surabaya',
            'phone' => '031-7654321',
            'is_active' => true,
        ]);

        $this->otherAdmin = User::factory()->create([
            'name' => 'Admin Surabaya',
            'email' => 'admin.sby@test.com',
            'branch_id' => $this->otherBranch->id,
            'status' => 'active',
        ]);
        $this->otherAdmin->assignRole('admin-cabang');
    }

    public function test_admin_cabang_can_access_trainer_index_page(): void
    {
        $trainer = User::factory()->create([
            'name' => 'Kang Asep Trainer',
            'email' => 'asep@test.com',
            'branch_id' => $this->branch->id,
            'status' => 'active',
        ]);
        $trainer->assignRole('trainer');

        $response = $this->actingAs($this->adminCabang)->get(route('cabang.trainers.index'));

        $response->assertOk();
        $response->assertSee('Manajemen Instruktur / Trainer');
        $response->assertSee('Kang Asep Trainer');
    }

    public function test_admin_cabang_can_create_a_trainer_with_automatic_role_and_branch(): void
    {
        $payload = [
            'name' => 'Budi Instruktur',
            'email' => 'budi.instruktur@test.com',
            'phone_number' => '08123456789',
            'status' => 'active',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
        ];

        $response = $this->actingAs($this->adminCabang)->post(route('cabang.trainers.store'), $payload);

        $response->assertRedirect(route('cabang.trainers.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('users', [
            'name' => 'Budi Instruktur',
            'email' => 'budi.instruktur@test.com',
            'branch_id' => $this->branch->id,
            'status' => 'active',
            'phone_number' => '08123456789',
        ]);

        $createdTrainer = User::where('email', 'budi.instruktur@test.com')->first();
        $this->assertTrue($createdTrainer->hasRole('trainer'));

        // Pastikan audit log tercatat
        $this->assertDatabaseHas('activity_logs', [
            'action' => 'CREATE',
            'user_id' => $this->adminCabang->id,
            'branch_id' => $this->branch->id,
        ]);
    }

    public function test_admin_cabang_can_update_trainer(): void
    {
        $trainer = User::factory()->create([
            'name' => 'Trainer Lama',
            'email' => 'lama@test.com',
            'branch_id' => $this->branch->id,
            'status' => 'active',
        ]);
        $trainer->assignRole('trainer');

        $response = $this->actingAs($this->adminCabang)->put(route('cabang.trainers.update', $trainer), [
            'name' => 'Trainer Baru Diperbarui',
            'email' => 'lama@test.com',
            'phone_number' => '0899999999',
            'status' => 'active',
        ]);

        $response->assertRedirect(route('cabang.trainers.index'));
        $this->assertDatabaseHas('users', [
            'id' => $trainer->id,
            'name' => 'Trainer Baru Diperbarui',
            'phone_number' => '0899999999',
        ]);
    }

    public function test_admin_cabang_can_toggle_trainer_status(): void
    {
        $trainer = User::factory()->create([
            'name' => 'Trainer Toggle',
            'email' => 'toggle@test.com',
            'branch_id' => $this->branch->id,
            'status' => 'active',
        ]);
        $trainer->assignRole('trainer');

        $response = $this->actingAs($this->adminCabang)->patch(route('cabang.trainers.toggle-status', $trainer));

        $response->assertRedirect();
        $this->assertEquals('inactive', $trainer->fresh()->status);
    }

    public function test_admin_cabang_can_delete_trainer(): void
    {
        $trainer = User::factory()->create([
            'name' => 'Trainer Hapus',
            'email' => 'hapus@test.com',
            'branch_id' => $this->branch->id,
            'status' => 'inactive',
        ]);
        $trainer->assignRole('trainer');

        $response = $this->actingAs($this->adminCabang)->delete(route('cabang.trainers.destroy', $trainer));

        $response->assertRedirect(route('cabang.trainers.index'));
        $this->assertDatabaseMissing('users', [
            'id' => $trainer->id,
        ]);
    }

    public function test_peserta_cannot_access_trainer_management(): void
    {
        $peserta = User::factory()->create([
            'name' => 'Peserta Siswa',
            'email' => 'peserta@test.com',
            'status' => 'active',
        ]);
        $peserta->assignRole('peserta');

        $response = $this->actingAs($peserta)->get(route('cabang.trainers.index'));
        $response->assertForbidden();
    }
}
