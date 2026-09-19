<?php

namespace Tests\Feature\SuperAdmin;

use App\Models\Branch;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminCabangManagementTest extends TestCase
{
    use RefreshDatabase;

    protected User $superAdmin;

    protected Branch $branch;

    protected User $adminCabang;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RolePermissionSeeder::class);

        $this->branch = Branch::create([
            'name' => 'Cabang Surabaya',
            'code' => 'CBG-SBY',
            'address' => 'Jl. Pemuda No. 1',
            'city' => 'Surabaya',
            'is_active' => true,
        ]);

        $this->superAdmin = User::factory()->create([
            'name' => 'Super Admin',
            'email' => 'superadmin@test.com',
            'status' => 'active',
        ]);
        $this->superAdmin->assignRole('super-admin');

        $this->adminCabang = User::factory()->create([
            'name' => 'Admin Surabaya',
            'email' => 'admin.sby@test.com',
            'branch_id' => $this->branch->id,
            'status' => 'active',
        ]);
        $this->adminCabang->assignRole('admin-cabang');
    }

    public function test_super_admin_can_view_admins_list(): void
    {
        $response = $this->actingAs($this->superAdmin)
            ->get(route('admin.admins.index'));

        $response->assertOk();
        $response->assertSee('Manajemen Admin Cabang');
        $response->assertSee('Admin Surabaya');
    }

    public function test_super_admin_can_create_new_admin_cabang(): void
    {
        $payload = [
            'name' => 'Admin Baru',
            'email' => 'admin.baru@test.com',
            'branch_id' => $this->branch->id,
            'phone_number' => '08123456789',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'status' => 'active',
        ];

        $response = $this->actingAs($this->superAdmin)
            ->post(route('admin.admins.store'), $payload);

        $response->assertRedirect(route('admin.admins.index'));
        $this->assertDatabaseHas('users', [
            'name' => 'Admin Baru',
            'email' => 'admin.baru@test.com',
            'branch_id' => $this->branch->id,
        ]);

        $newUser = User::where('email', 'admin.baru@test.com')->first();
        $this->assertTrue($newUser->hasRole('admin-cabang'));

        $this->assertDatabaseHas('activity_logs', [
            'action' => 'CREATE',
            'target_entity' => User::class,
        ]);
    }

    public function test_admin_cabang_creation_validates_required_fields(): void
    {
        $response = $this->actingAs($this->superAdmin)
            ->post(route('admin.admins.store'), []);

        $response->assertSessionHasErrors(['name', 'email', 'password', 'branch_id', 'status']);
    }

    public function test_super_admin_can_update_admin_cabang(): void
    {
        $payload = [
            'name' => 'Admin Surabaya Updated',
            'email' => 'admin.sby@test.com',
            'branch_id' => $this->branch->id,
            'phone_number' => '08999999999',
            'status' => 'active',
        ];

        $response = $this->actingAs($this->superAdmin)
            ->put(route('admin.admins.update', $this->adminCabang), $payload);

        $response->assertRedirect(route('admin.admins.index'));
        $this->assertDatabaseHas('users', [
            'id' => $this->adminCabang->id,
            'name' => 'Admin Surabaya Updated',
            'phone_number' => '08999999999',
        ]);

        $this->assertDatabaseHas('activity_logs', [
            'action' => 'UPDATE',
            'target_id' => $this->adminCabang->id,
        ]);
    }

    public function test_super_admin_can_toggle_admin_status(): void
    {
        $this->assertEquals('active', $this->adminCabang->status);

        $response = $this->actingAs($this->superAdmin)
            ->patch(route('admin.admins.toggle-status', $this->adminCabang));

        $response->assertRedirect();
        $this->assertEquals('inactive', $this->adminCabang->fresh()->status);
    }

    public function test_super_admin_can_delete_admin_cabang(): void
    {
        $response = $this->actingAs($this->superAdmin)
            ->delete(route('admin.admins.destroy', $this->adminCabang));

        $response->assertRedirect(route('admin.admins.index'));
        $this->assertDatabaseMissing('users', [
            'id' => $this->adminCabang->id,
        ]);

        $this->assertDatabaseHas('activity_logs', [
            'action' => 'DELETE',
        ]);
    }

    public function test_super_admin_accessing_admin_create_is_redirected_to_branch_create(): void
    {
        $response = $this->actingAs($this->superAdmin)
            ->get(route('admin.admins.create'));

        $response->assertRedirect(route('admin.branches.create'));
        $response->assertSessionHas('info');
    }

    public function test_super_admin_can_view_city_and_phone_fields_in_admin_list(): void
    {
        $this->adminCabang->update([
            'phone_number' => '0812-9999-8888',
        ]);

        $response = $this->actingAs($this->superAdmin)
            ->get(route('admin.admins.index'));

        $response->assertOk();
        $response->assertSee('Kota');
        $response->assertSee('Nomor Telepon');
        $response->assertSee('Surabaya');
        $response->assertSee('0812-9999-8888');
        $response->assertSee('tel:081299998888');
    }

    public function test_super_admin_can_filter_admins_by_city(): void
    {
        $branchBandung = Branch::create([
            'name' => 'Cabang Bandung',
            'code' => 'CBG-BDG',
            'address' => 'Jl. Asia Afrika No. 10',
            'city' => 'Bandung',
            'is_active' => true,
        ]);

        $adminBandung = User::factory()->create([
            'name' => 'Admin Bandung',
            'email' => 'admin.bdg@test.com',
            'branch_id' => $branchBandung->id,
            'status' => 'active',
        ]);
        $adminBandung->assignRole('admin-cabang');

        // Filter Kota Bandung
        $response = $this->actingAs($this->superAdmin)
            ->get(route('admin.admins.index', ['city' => 'Bandung']));

        $response->assertOk();
        $response->assertSee('Admin Bandung');
        $response->assertDontSee('Admin Surabaya');

        // Filter Kota Surabaya
        $responseSurabaya = $this->actingAs($this->superAdmin)
            ->get(route('admin.admins.index', ['city' => 'Surabaya']));

        $responseSurabaya->assertOk();
        $responseSurabaya->assertSee('Admin Surabaya');
        $responseSurabaya->assertDontSee('Admin Bandung');
    }

    public function test_super_admin_can_filter_admins_by_phone(): void
    {
        $this->adminCabang->update([
            'phone_number' => '0811-2233-4455',
        ]);

        $otherAdmin = User::factory()->create([
            'name' => 'Admin Lain',
            'email' => 'admin.lain@test.com',
            'branch_id' => $this->branch->id,
            'phone_number' => '0899-7777-6666',
            'status' => 'active',
        ]);
        $otherAdmin->assignRole('admin-cabang');

        // Filter nomor telepon spesifik
        $response = $this->actingAs($this->superAdmin)
            ->get(route('admin.admins.index', ['phone' => '0811-2233']));

        $response->assertOk();
        $response->assertSee('Admin Surabaya');
        $response->assertDontSee('Admin Lain');
    }
}

