<?php

namespace Tests\Feature\SuperAdmin;

use App\Models\Branch;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BranchManagementTest extends TestCase
{
    use RefreshDatabase;

    protected User $superAdmin;

    protected User $trainer;

    protected Branch $branch;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RolePermissionSeeder::class);

        $this->branch = Branch::create([
            'name' => 'Cabang Jakarta Pusat',
            'code' => 'CBG-JKT',
            'address' => 'Jl. Thamrin No. 10',
            'city' => 'Jakarta Pusat',
            'phone' => '021-5551234',
            'is_active' => true,
        ]);

        $this->superAdmin = User::factory()->create([
            'name' => 'Super Admin Test',
            'email' => 'superadmin@test.com',
            'status' => 'active',
        ]);
        $this->superAdmin->assignRole('super-admin');

        $this->trainer = User::factory()->create([
            'name' => 'Trainer Test',
            'email' => 'trainer@test.com',
            'branch_id' => $this->branch->id,
            'status' => 'active',
        ]);
        $this->trainer->assignRole('trainer');
    }

    public function test_super_admin_can_access_branch_index_page(): void
    {
        $response = $this->actingAs($this->superAdmin)->get(route('admin.branches.index'));

        $response->assertOk();
        $response->assertSee('Master Kantor Cabang');
        $response->assertSee($this->branch->name);
    }

    public function test_non_super_admin_cannot_access_branch_index_page(): void
    {
        $response = $this->actingAs($this->trainer)->get(route('admin.branches.index'));

        $response->assertForbidden();
    }

    public function test_super_admin_can_create_a_new_branch(): void
    {
        $payload = [
            'name' => 'Cabang Bandung Barat',
            'code' => 'CBG-BDG',
            'address' => 'Jl. Asia Afrika No. 5',
            'city' => 'Bandung',
            'phone' => '022-7778899',
            'is_active' => '1',
        ];

        $response = $this->actingAs($this->superAdmin)
            ->post(route('admin.branches.store'), $payload);

        $response->assertRedirect(route('admin.branches.index'));
        $this->assertDatabaseHas('branches', [
            'name' => 'Cabang Bandung Barat',
            'code' => 'CBG-BDG',
            'city' => 'Bandung',
        ]);

        // Verify audit log recorded
        $this->assertDatabaseHas('activity_logs', [
            'action' => 'CREATE',
            'target_entity' => Branch::class,
        ]);
    }

    public function test_branch_creation_validates_unique_code(): void
    {
        $payload = [
            'name' => 'Cabang Duplikat',
            'code' => 'CBG-JKT', // existing code
            'address' => 'Jl. Duplikat No. 1',
            'city' => 'Jakarta',
            'is_active' => '1',
        ];

        $response = $this->actingAs($this->superAdmin)
            ->post(route('admin.branches.store'), $payload);

        $response->assertSessionHasErrors(['code']);
    }

    public function test_super_admin_can_update_branch_data(): void
    {
        $payload = [
            'name' => 'Cabang Jakarta Pusat Updated',
            'code' => 'CBG-JKT',
            'address' => 'Jl. MH Thamrin No. 99',
            'city' => 'Jakarta Pusat',
            'phone' => '021-9998887',
            'is_active' => '1',
        ];

        $response = $this->actingAs($this->superAdmin)
            ->put(route('admin.branches.update', $this->branch), $payload);

        $response->assertRedirect(route('admin.branches.index'));
        $this->assertDatabaseHas('branches', [
            'id' => $this->branch->id,
            'name' => 'Cabang Jakarta Pusat Updated',
        ]);

        $this->assertDatabaseHas('activity_logs', [
            'action' => 'UPDATE',
            'target_id' => $this->branch->id,
        ]);
    }

    public function test_super_admin_can_toggle_branch_status(): void
    {
        $this->assertTrue($this->branch->is_active);

        $response = $this->actingAs($this->superAdmin)
            ->patch(route('admin.branches.toggle-status', $this->branch));

        $response->assertRedirect();
        $this->assertFalse($this->branch->fresh()->is_active);

        // Toggle back to active
        $this->actingAs($this->superAdmin)
            ->patch(route('admin.branches.toggle-status', $this->branch));

        $this->assertTrue($this->branch->fresh()->is_active);
    }

    public function test_super_admin_cannot_delete_branch_with_existing_users(): void
    {
        // $this->trainer is assigned to $this->branch
        $response = $this->actingAs($this->superAdmin)
            ->delete(route('admin.branches.destroy', $this->branch));

        $response->assertRedirect();
        $response->assertSessionHas('error');
        $this->assertDatabaseHas('branches', [
            'id' => $this->branch->id,
        ]);
    }

    public function test_super_admin_can_delete_empty_branch(): void
    {
        $emptyBranch = Branch::create([
            'name' => 'Cabang Solo',
            'code' => 'CBG-SLO',
            'address' => 'Jl. Slamet Riyadi No. 1',
            'city' => 'Surakarta',
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->superAdmin)
            ->delete(route('admin.branches.destroy', $emptyBranch));

        $response->assertRedirect(route('admin.branches.index'));
        $this->assertDatabaseMissing('branches', [
            'id' => $emptyBranch->id,
        ]);

        $this->assertDatabaseHas('activity_logs', [
            'action' => 'DELETE',
        ]);
    }
}
