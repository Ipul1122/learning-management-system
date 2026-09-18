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

    public function test_branch_code_is_automatically_generated_if_empty_in_store_request(): void
    {
        $payload = [
            'name' => 'Cabang Surabaya',
            'code' => '', // sengaja dikosongkan untuk uji auto-generate
            'address' => 'Jl. Pemuda No. 10',
            'city' => 'Surabaya',
            'phone' => '031-1234567',
            'is_active' => '1',
        ];

        $response = $this->actingAs($this->superAdmin)
            ->post(route('admin.branches.store'), $payload);

        $response->assertRedirect(route('admin.branches.index'));
        $this->assertDatabaseHas('branches', [
            'name' => 'Cabang Surabaya',
            'code' => 'CBG-SBY',
            'city' => 'Surabaya',
        ]);
    }

    public function test_branch_code_generator_handles_various_formats_and_collisions(): void
    {
        // 1. Kota umum (CBG-JKT sudah dibuat di setUp, sehingga tanpa ignoreId menghasilkan suffix duplikasi)
        $this->assertEquals('CBG-JKT', Branch::generateCode('Cabang Jakarta Pusat', $this->branch->id));
        $this->assertEquals('CBG-BDG', Branch::generateCode('Cabang Bandung Barat'));
        $this->assertEquals('CBG-SBY', Branch::generateCode('Cabang Surabaya'));
        $this->assertEquals('CBG-DPS', Branch::generateCode('Kantor Cabang Denpasar'));

        // 2. Custom name
        $this->assertEquals('CBG-NUSANTARA', Branch::generateCode('Cabang Nusantara'));

        // 3. Menangani duplikasi / collision dengan suffix
        Branch::create([
            'name' => 'Cabang Semarang',
            'code' => 'CBG-SMG',
            'address' => 'Jl. Pahlawan No. 1',
            'city' => 'Semarang',
            'is_active' => true,
        ]);

        $codeAfterCollision = Branch::generateCode('Cabang Semarang');
        $this->assertEquals('CBG-SMG-02', $codeAfterCollision);
    }

    public function test_super_admin_can_access_branch_create_page_with_auto_code_and_phone_features(): void
    {
        $response = $this->actingAs($this->superAdmin)
            ->get(route('admin.branches.create'));

        $response->assertOk();
        $response->assertSee('Tambah Cabang Baru');
        $response->assertSee('code-auto-badge', false);
        $response->assertSee('btn-regenerate-code', false);
        $response->assertSee('generateBranchCode', false);
        $response->assertSee('formatPhoneNumber', false);
        $response->assertSee('Contoh: 0812-3456-7890');
    }

    public function test_super_admin_can_access_branch_edit_page_with_phone_features(): void
    {
        $response = $this->actingAs($this->superAdmin)
            ->get(route('admin.branches.edit', $this->branch));

        $response->assertOk();
        $response->assertSee('formatPhoneNumber', false);
        $response->assertSee('Contoh: 0812-3456-7890');
    }

    public function test_super_admin_can_store_and_update_branch_with_formatted_phone(): void
    {
        // Test Store with formatted phone (0812-3456-7890)
        $storePayload = [
            'name' => 'Cabang Medan Kota',
            'code' => 'CBG-MDN',
            'address' => 'Jl. Merdeka No. 8',
            'city' => 'Medan',
            'phone' => '0812-3456-7890',
            'is_active' => '1',
        ];

        $storeResponse = $this->actingAs($this->superAdmin)
            ->post(route('admin.branches.store'), $storePayload);

        $storeResponse->assertRedirect(route('admin.branches.index'));
        $this->assertDatabaseHas('branches', [
            'name' => 'Cabang Medan Kota',
            'phone' => '0812-3456-7890',
        ]);

        $createdBranch = Branch::where('code', 'CBG-MDN')->first();

        // Test Update with formatted phone (0852-9876-5432)
        $updatePayload = [
            'name' => 'Cabang Medan Kota Updated',
            'code' => 'CBG-MDN',
            'address' => 'Jl. Merdeka No. 9',
            'city' => 'Medan',
            'phone' => '0852-9876-5432',
            'is_active' => '1',
        ];

        $updateResponse = $this->actingAs($this->superAdmin)
            ->put(route('admin.branches.update', $createdBranch), $updatePayload);

        $updateResponse->assertRedirect(route('admin.branches.index'));
        $this->assertDatabaseHas('branches', [
            'id' => $createdBranch->id,
            'phone' => '0852-9876-5432',
        ]);
    }

    public function test_cannot_create_duplicate_branch_name(): void
    {
        // $this->branch sudah bernama 'Cabang Jakarta Pusat'
        $payload = [
            'name' => 'Cabang Jakarta Pusat',
            'code' => 'CBG-JKT-NEW',
            'address' => 'Jl. Salemba No. 1',
            'city' => 'Jakarta Pusat',
            'phone' => '0812-1111-2222',
            'is_active' => '1',
        ];

        $response = $this->actingAs($this->superAdmin)
            ->post(route('admin.branches.store'), $payload);

        $response->assertSessionHasErrors(['name']);
        $this->assertEquals(1, Branch::where('name', 'Cabang Jakarta Pusat')->count());
    }

    public function test_cannot_create_duplicate_branch_with_prefix_variation(): void
    {
        // $this->branch sudah ada dengan 'Cabang Jakarta Pusat'
        // Mencoba input 'Jakarta Pusat' atau 'Kantor Cabang Jakarta Pusat' harus ditolak
        $payload = [
            'name' => 'Jakarta Pusat',
            'code' => 'CBG-JKP',
            'address' => 'Jl. Salemba No. 2',
            'city' => 'Jakarta Pusat',
            'phone' => '0812-3333-4444',
            'is_active' => '1',
        ];

        $response = $this->actingAs($this->superAdmin)
            ->post(route('admin.branches.store'), $payload);

        $response->assertSessionHasErrors(['name']);
    }

    public function test_branch_create_page_contains_integrated_admin_account_section(): void
    {
        $response = $this->actingAs($this->superAdmin)
            ->get(route('admin.branches.create'));

        $response->assertOk();
        $response->assertSee('Akun Administrator Cabang');
        $response->assertSee('create_admin_account');
        $response->assertSee('admin_name');
        $response->assertSee('admin_email');
        $response->assertSee('admin_phone_number');
        $response->assertSee('admin_password');
        $response->assertSee('admin_password_confirmation');
    }

    public function test_super_admin_can_create_branch_and_admin_simultaneously(): void
    {
        $payload = [
            'name' => 'Cabang Bali Denpasar',
            'code' => 'CBG-DPS',
            'address' => 'Jl. Bypass Ngurah Rai No. 88',
            'city' => 'Denpasar',
            'phone' => '0361-123-4567',
            'is_active' => '1',
            'create_admin_account' => '1',
            'admin_name' => 'Wayan Admin',
            'admin_email' => 'wayan.admin@lms.test',
            'admin_phone_number' => '0812-3456-7890',
            'admin_status' => 'active',
            'admin_password' => 'password123',
            'admin_password_confirmation' => 'password123',
        ];

        $response = $this->actingAs($this->superAdmin)
            ->post(route('admin.branches.store'), $payload);

        $response->assertRedirect(route('admin.branches.index'));
        $response->assertSessionHas('success');

        // Pastikan cabang tersimpan
        $this->assertDatabaseHas('branches', [
            'name' => 'Cabang Bali Denpasar',
            'code' => 'CBG-DPS',
            'city' => 'Denpasar',
        ]);

        $createdBranch = Branch::where('code', 'CBG-DPS')->first();
        $this->assertNotNull($createdBranch);

        // Pastikan user admin cabang tersimpan dan terhubung ke cabang tersebut
        $this->assertDatabaseHas('users', [
            'name' => 'Wayan Admin',
            'email' => 'wayan.admin@lms.test',
            'branch_id' => $createdBranch->id,
            'phone_number' => '0812-3456-7890',
            'status' => 'active',
        ]);

        $createdAdmin = User::where('email', 'wayan.admin@lms.test')->first();
        $this->assertTrue($createdAdmin->hasRole('admin-cabang'));

        // Pastikan activity log tercatat untuk branch dan admin
        $this->assertDatabaseHas('activity_logs', [
            'action' => 'CREATE',
            'target_entity' => Branch::class,
            'target_id' => $createdBranch->id,
        ]);

        $this->assertDatabaseHas('activity_logs', [
            'action' => 'CREATE',
            'target_entity' => User::class,
            'target_id' => $createdAdmin->id,
        ]);
    }

    public function test_branch_and_admin_creation_validates_admin_fields_when_toggle_enabled(): void
    {
        $payload = [
            'name' => 'Cabang Yogyakarta Baru',
            'code' => 'CBG-JOG',
            'address' => 'Jl. Malioboro No. 1',
            'city' => 'Yogyakarta',
            'phone' => '0274-123456',
            'is_active' => '1',
            'create_admin_account' => '1',
            // Data admin sengaja dikosongkan untuk uji validasi
            'admin_name' => '',
            'admin_email' => '',
            'admin_password' => '',
            'admin_password_confirmation' => '',
        ];

        $response = $this->actingAs($this->superAdmin)
            ->post(route('admin.branches.store'), $payload);

        $response->assertSessionHasErrors(['admin_name', 'admin_email', 'admin_password']);
        $this->assertDatabaseMissing('branches', [
            'name' => 'Cabang Yogyakarta Baru',
        ]);
    }

    public function test_branch_and_admin_creation_validates_unique_admin_email(): void
    {
        // $this->superAdmin sudah memiliki email 'superadmin@test.com'
        $payload = [
            'name' => 'Cabang Solo Baru',
            'code' => 'CBG-SLO-BARU',
            'address' => 'Jl. Slamet Riyadi No. 99',
            'city' => 'Surakarta',
            'phone' => '0271-999999',
            'is_active' => '1',
            'create_admin_account' => '1',
            'admin_name' => 'Admin Duplikat Email',
            'admin_email' => 'superadmin@test.com', // email sudah terdaftar
            'admin_password' => 'password123',
            'admin_password_confirmation' => 'password123',
        ];

        $response = $this->actingAs($this->superAdmin)
            ->post(route('admin.branches.store'), $payload);

        $response->assertSessionHasErrors(['admin_email']);
        $this->assertDatabaseMissing('branches', [
            'name' => 'Cabang Solo Baru',
        ]);
    }

    public function test_super_admin_can_update_branch_and_admin_simultaneously(): void
    {
        $admin = User::factory()->create([
            'name' => 'Admin Lama',
            'email' => 'admin.lama@lms.test',
            'branch_id' => $this->branch->id,
            'status' => 'active',
            'password' => bcrypt('password123'),
        ]);
        $admin->assignRole('admin-cabang');

        $payload = [
            'name' => 'Cabang Jakarta Pusat Updated',
            'code' => 'CBG-JKT',
            'address' => 'Jl. MH Thamrin No. 99',
            'city' => 'Jakarta Pusat',
            'phone' => '021-9998887',
            'is_active' => '1',
            'admin_name' => 'Admin Cabang Diperbarui',
            'admin_email' => 'admin.baru@lms.test',
            'admin_phone_number' => '0812-8888-7777',
            'admin_status' => 'active',
            'admin_password' => 'passwordBaru123',
            'admin_password_confirmation' => 'passwordBaru123',
        ];

        $response = $this->actingAs($this->superAdmin)
            ->put(route('admin.branches.update', $this->branch), $payload);

        $response->assertRedirect(route('admin.branches.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('branches', [
            'id' => $this->branch->id,
            'name' => 'Cabang Jakarta Pusat Updated',
        ]);

        $this->assertDatabaseHas('users', [
            'id' => $admin->id,
            'name' => 'Admin Cabang Diperbarui',
            'email' => 'admin.baru@lms.test',
            'phone_number' => '0812-8888-7777',
        ]);

        $this->assertTrue(\Illuminate\Support\Facades\Hash::check('passwordBaru123', $admin->fresh()->password));
    }

    public function test_super_admin_can_update_admin_without_changing_password(): void
    {
        $originalHash = bcrypt('passwordTetap123');
        $admin = User::factory()->create([
            'name' => 'Admin Nama Lama',
            'email' => 'admin.tetap@lms.test',
            'branch_id' => $this->branch->id,
            'status' => 'active',
            'password' => $originalHash,
        ]);
        $admin->assignRole('admin-cabang');

        $payload = [
            'name' => 'Cabang Jakarta Pusat',
            'code' => 'CBG-JKT',
            'address' => 'Jl. Thamrin No. 10',
            'city' => 'Jakarta Pusat',
            'phone' => '021-5551234',
            'is_active' => '1',
            'admin_name' => 'Admin Nama Baru',
            'admin_email' => 'admin.tetap@lms.test',
            'admin_phone_number' => '0812-1111-2222',
            'admin_status' => 'active',
            'admin_password' => '', // kosong, tidak ubah kata sandi
            'admin_password_confirmation' => '',
        ];

        $response = $this->actingAs($this->superAdmin)
            ->put(route('admin.branches.update', $this->branch), $payload);

        $response->assertRedirect(route('admin.branches.index'));

        $this->assertDatabaseHas('users', [
            'id' => $admin->id,
            'name' => 'Admin Nama Baru',
            'phone_number' => '0812-1111-2222',
        ]);

        $this->assertTrue(\Illuminate\Support\Facades\Hash::check('passwordTetap123', $admin->fresh()->password));
    }

    public function test_super_admin_can_create_admin_from_branch_edit_page_if_none_existed(): void
    {
        // Branch tanpa admin
        $emptyBranch = Branch::create([
            'name' => 'Cabang Pontianak',
            'code' => 'CBG-PTK',
            'address' => 'Jl. Gajah Mada No. 12',
            'city' => 'Pontianak',
            'is_active' => true,
        ]);

        $payload = [
            'name' => 'Cabang Pontianak Kota',
            'code' => 'CBG-PTK',
            'address' => 'Jl. Gajah Mada No. 12',
            'city' => 'Pontianak',
            'is_active' => '1',
            'create_admin_account' => '1',
            'admin_name' => 'Admin Pontianak Baru',
            'admin_email' => 'admin.ptk@lms.test',
            'admin_phone_number' => '0812-7777-6666',
            'admin_status' => 'active',
            'admin_password' => 'password123',
            'admin_password_confirmation' => 'password123',
        ];

        $response = $this->actingAs($this->superAdmin)
            ->put(route('admin.branches.update', $emptyBranch), $payload);

        $response->assertRedirect(route('admin.branches.index'));

        $this->assertDatabaseHas('users', [
            'name' => 'Admin Pontianak Baru',
            'email' => 'admin.ptk@lms.test',
            'branch_id' => $emptyBranch->id,
        ]);

        $createdAdmin = User::where('email', 'admin.ptk@lms.test')->first();
        $this->assertTrue($createdAdmin->hasRole('admin-cabang'));
    }

    public function test_super_admin_can_add_admin_to_existing_branch_using_reuse_feature(): void
    {
        $initialBranchCount = Branch::count();

        $payload = [
            'existing_branch_id' => $this->branch->id,
            'reuse_branch_choice' => 'reuse',
            'name' => $this->branch->name,
            'code' => $this->branch->code,
            'admin_name' => 'Admin Cabang Baru Dua',
            'admin_email' => 'admin.dua@lms.test',
            'admin_phone_number' => '0812-9999-8888',
            'admin_status' => 'active',
            'admin_password' => 'password123',
            'admin_password_confirmation' => 'password123',
        ];

        $response = $this->actingAs($this->superAdmin)
            ->post(route('admin.branches.store'), $payload);

        $response->assertRedirect(route('admin.branches.index'));
        $response->assertSessionHas('success');

        // Memastikan tidak membuat baris cabang baru di tabel branches
        $this->assertEquals($initialBranchCount, Branch::count());

        // Memastikan user admin baru terdaftar di bawah cabang yang sama
        $this->assertDatabaseHas('users', [
            'name' => 'Admin Cabang Baru Dua',
            'email' => 'admin.dua@lms.test',
            'branch_id' => $this->branch->id,
            'phone_number' => '0812-9999-8888',
        ]);

        $createdAdmin = User::where('email', 'admin.dua@lms.test')->first();
        $this->assertTrue($createdAdmin->hasRole('admin-cabang'));

        // Memastikan activity log tercatat
        $this->assertDatabaseHas('activity_logs', [
            'action' => 'CREATE',
            'target_entity' => User::class,
            'target_id' => $createdAdmin->id,
            'branch_id' => $this->branch->id,
        ]);
    }

    public function test_multiple_admins_can_be_assigned_to_same_branch_sequentially(): void
    {
        // 1 cabang yang sama bisa dibuat ramai-ramai admin
        // Daftarkan admin pertama
        $this->actingAs($this->superAdmin)
            ->post(route('admin.branches.store'), [
                'existing_branch_id' => $this->branch->id,
                'reuse_branch_choice' => 'reuse',
                'name' => $this->branch->name,
                'code' => $this->branch->code,
                'admin_name' => 'Admin Ramai 1',
                'admin_email' => 'ramai1@lms.test',
                'admin_password' => 'password123',
                'admin_password_confirmation' => 'password123',
            ])->assertRedirect(route('admin.branches.index'));

        // Daftarkan admin kedua untuk cabang yang sama
        $this->actingAs($this->superAdmin)
            ->post(route('admin.branches.store'), [
                'existing_branch_id' => $this->branch->id,
                'reuse_branch_choice' => 'reuse',
                'name' => $this->branch->name,
                'code' => $this->branch->code,
                'admin_name' => 'Admin Ramai 2',
                'admin_email' => 'ramai2@lms.test',
                'admin_password' => 'password123',
                'admin_password_confirmation' => 'password123',
            ])->assertRedirect(route('admin.branches.index'));

        // Daftarkan admin ketiga untuk cabang yang sama
        $this->actingAs($this->superAdmin)
            ->post(route('admin.branches.store'), [
                'existing_branch_id' => $this->branch->id,
                'reuse_branch_choice' => 'reuse',
                'name' => $this->branch->name,
                'code' => $this->branch->code,
                'admin_name' => 'Admin Ramai 3',
                'admin_email' => 'ramai3@lms.test',
                'admin_password' => 'password123',
                'admin_password_confirmation' => 'password123',
            ])->assertRedirect(route('admin.branches.index'));

        $this->assertEquals(3, $this->branch->users()->role('admin-cabang')->count());
    }

    public function test_super_admin_can_use_save_and_add_another_action_for_bulk_admin_creation(): void
    {
        $payload = [
            'existing_branch_id' => $this->branch->id,
            'action' => 'save_and_add_another',
            'reuse_branch_choice' => 'reuse',
            'name' => $this->branch->name,
            'code' => $this->branch->code,
            'admin_name' => 'Admin Bulk Next',
            'admin_email' => 'bulk.next@lms.test',
            'admin_password' => 'password123',
            'admin_password_confirmation' => 'password123',
        ];

        $response = $this->actingAs($this->superAdmin)
            ->post(route('admin.branches.store'), $payload);

        // Harus dialihkan kembali ke formulir create dengan parameter branch_id
        $response->assertRedirect(route('admin.branches.create', ['branch_id' => $this->branch->id]));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('users', [
            'name' => 'Admin Bulk Next',
            'email' => 'bulk.next@lms.test',
            'branch_id' => $this->branch->id,
        ]);
    }

    public function test_reusing_existing_branch_requires_admin_credentials(): void
    {
        $payload = [
            'existing_branch_id' => $this->branch->id,
            'reuse_branch_choice' => 'reuse',
            'name' => $this->branch->name,
            'code' => $this->branch->code,
            // Kosongkan kredensial admin
            'admin_name' => '',
            'admin_email' => '',
            'admin_password' => '',
            'admin_password_confirmation' => '',
        ];

        $response = $this->actingAs($this->superAdmin)
            ->post(route('admin.branches.store'), $payload);

        $response->assertSessionHasErrors(['admin_name', 'admin_email', 'admin_password']);
    }

    public function test_reusing_existing_branch_validates_unique_admin_email(): void
    {
        // $this->superAdmin sudah memiliki email 'superadmin@test.com'
        $payload = [
            'existing_branch_id' => $this->branch->id,
            'reuse_branch_choice' => 'reuse',
            'name' => $this->branch->name,
            'code' => $this->branch->code,
            'admin_name' => 'Admin Email Sama',
            'admin_email' => 'superadmin@test.com',
            'admin_password' => 'password123',
            'admin_password_confirmation' => 'password123',
        ];

        $response = $this->actingAs($this->superAdmin)
            ->post(route('admin.branches.store'), $payload);

        $response->assertSessionHasErrors(['admin_email']);
    }
}



