<?php

namespace Tests\Feature\SuperAdmin;

use App\Models\ActivityLog;
use App\Models\Branch;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ActivityLogManagementTest extends TestCase
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
            'name' => 'Cabang Medan',
            'code' => 'CBG-MDN',
            'address' => 'Jl. Merdeka No. 3',
            'city' => 'Medan',
            'is_active' => true,
        ]);

        $this->superAdmin = User::factory()->create([
            'name' => 'Super Admin',
            'email' => 'superadmin@test.com',
            'status' => 'active',
        ]);
        $this->superAdmin->assignRole('super-admin');

        $this->peserta = User::factory()->create([
            'name' => 'Peserta Test',
            'email' => 'peserta@test.com',
            'status' => 'active',
        ]);
        $this->peserta->assignRole('peserta');

        ActivityLog::record(
            action: 'CREATE',
            description: 'Log Percobaan Pertama',
            target: $this->branch,
            old: null,
            new: $this->branch->toArray(),
            userId: $this->superAdmin->id,
            branchId: $this->branch->id
        );
    }

    public function test_super_admin_can_view_activity_logs(): void
    {
        $response = $this->actingAs($this->superAdmin)
            ->get(route('admin.logs.index'));

        $response->assertOk();
        $response->assertSee('Audit Trail Log Aktivitas');
        $response->assertSee('Log Percobaan Pertama');
    }

    public function test_peserta_cannot_view_activity_logs(): void
    {
        $response = $this->actingAs($this->peserta)
            ->get(route('admin.logs.index'));

        $response->assertForbidden();
    }

    public function test_super_admin_can_filter_logs_by_action(): void
    {
        $response = $this->actingAs($this->superAdmin)
            ->get(route('admin.logs.index', ['action' => 'CREATE']));

        $response->assertOk();
        $response->assertSee('Log Percobaan Pertama');

        $responseEmpty = $this->actingAs($this->superAdmin)
            ->get(route('admin.logs.index', ['action' => 'DELETE']));

        $responseEmpty->assertOk();
        $responseEmpty->assertDontSee('Log Percobaan Pertama');
    }

    public function test_super_admin_can_inspect_log_details_via_json(): void
    {
        $log = ActivityLog::first();

        $response = $this->actingAs($this->superAdmin)
            ->getJson(route('admin.logs.show', $log));

        $response->assertOk();
        $response->assertJsonStructure([
            'log' => ['id', 'action', 'description', 'ip_address'],
            'user_name',
            'branch_name',
            'formatted_time',
        ]);
    }
}
