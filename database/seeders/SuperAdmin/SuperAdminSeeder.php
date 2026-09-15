<?php

namespace Database\Seeders\SuperAdmin;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $superAdmin = User::firstOrCreate(
            ['email' => 'superadmin@lms.test'],
            [
                'name' => 'Super Administrator',
                'password' => Hash::make('password'),
                'branch_id' => null,
                'phone_number' => '08110000001',
                'status' => 'active',
            ]
        );

        $superAdmin->syncRoles(['super-admin']);
    }
}
