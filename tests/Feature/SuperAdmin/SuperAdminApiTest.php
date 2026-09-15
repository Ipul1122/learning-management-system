<?php

namespace Tests\Feature\SuperAdmin;

use App\Models\Branch;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class SuperAdminApiTest extends TestCase
{
    use RefreshDatabase;

    protected User $superAdmin;

    protected User $peserta;

    protected Branch $branch;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RolePermissionSeeder::class);

        $this->branch = Branch::create([
            'name' => 'Cabang Semarang',
            'code' => 'CBG-SMG',
            'address' => 'Jl. Pandanaran No. 1',
            'city' => 'Semarang',
            'is_active' => true,
        ]);

        $this->superAdmin = User::factory()->create([
            'name' => 'Super Admin API',
            'email' => 'superadmin.api@test.com',
            'password' => 'secret123',
            'status' => 'active',
        ]);
        $this->superAdmin->assignRole('super-admin');

        $this->peserta = User::factory()->create([
            'name' => 'Peserta API',
            'email' => 'peserta.api@test.com',
            'status' => 'active',
        ]);
        $this->peserta->assignRole('peserta');
    }

    public function test_can_obtain_sanctum_token(): void
    {
        $response = $this->postJson('/api/v1/auth/token', [
            'email' => 'superadmin.api@test.com',
            'password' => 'secret123',
        ]);

        $response->assertOk();
        $response->assertJsonStructure([
            'token',
            'user' => ['id', 'name', 'email', 'roles'],
        ]);
    }

    public function test_super_admin_can_list_branches_via_api(): void
    {
        Sanctum::actingAs($this->superAdmin);

        $response = $this->getJson(route('api.v1.super-admin.branches.index'));

        $response->assertOk();
        $response->assertJsonStructure([
            'data' => [
                '*' => ['id', 'name', 'code', 'city', 'is_active', 'users_count'],
            ],
            'links',
            'meta',
        ]);
    }

    public function test_peserta_is_forbidden_from_super_admin_branches_api(): void
    {
        Sanctum::actingAs($this->peserta);

        $response = $this->getJson(route('api.v1.super-admin.branches.index'));

        $response->assertForbidden();
    }

    public function test_super_admin_can_create_branch_via_api(): void
    {
        Sanctum::actingAs($this->superAdmin);

        $payload = [
            'name' => 'Cabang Denpasar',
            'code' => 'CBG-DPS',
            'address' => 'Jl. Teuku Umar No. 88',
            'city' => 'Denpasar',
            'phone' => '0361-123456',
            'is_active' => true,
        ];

        $response = $this->postJson(route('api.v1.super-admin.branches.store'), $payload);

        $response->assertCreated();
        $response->assertJsonPath('data.name', 'Cabang Denpasar');
        $response->assertJsonPath('data.code', 'CBG-DPS');

        $this->assertDatabaseHas('branches', [
            'code' => 'CBG-DPS',
        ]);
    }

    public function test_super_admin_can_toggle_branch_status_via_api(): void
    {
        Sanctum::actingAs($this->superAdmin);

        $response = $this->patchJson(route('api.v1.super-admin.branches.toggle-status', $this->branch));

        $response->assertOk();
        $this->assertFalse($this->branch->fresh()->is_active);
    }

    public function test_super_admin_can_create_and_list_admin_cabang_via_api(): void
    {
        Sanctum::actingAs($this->superAdmin);

        $payload = [
            'name' => 'Admin Cabang API',
            'email' => 'admin.api@test.com',
            'branch_id' => $this->branch->id,
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'status' => 'active',
        ];

        $storeResponse = $this->postJson(route('api.v1.super-admin.admins.store'), $payload);
        $storeResponse->assertCreated();
        $storeResponse->assertJsonPath('data.email', 'admin.api@test.com');

        $listResponse = $this->getJson(route('api.v1.super-admin.admins.index'));
        $listResponse->assertOk();
        $listResponse->assertJsonPath('data.0.email', 'admin.api@test.com');
    }

    public function test_super_admin_can_list_activity_logs_via_api(): void
    {
        Sanctum::actingAs($this->superAdmin);

        $response = $this->getJson(route('api.v1.super-admin.logs.index'));

        $response->assertOk();
        $response->assertJsonStructure([
            'data',
            'links',
            'meta',
        ]);
    }
}
