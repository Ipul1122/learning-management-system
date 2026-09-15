<?php

namespace Database\Seeders\AdminCabang;

use App\Models\Branch;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminCabangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $password = Hash::make('password');
        $jktBranch = Branch::where('code', 'JKT-01')->first();
        $sbyBranch = Branch::where('code', 'SBY-01')->first();

        // 1. Admin Cabang Jakarta Pusat
        $adminJkt = User::firstOrCreate(
            ['email' => 'admin.jkt@lms.test'],
            [
                'name' => 'Admin Cabang Jakarta',
                'password' => $password,
                'branch_id' => $jktBranch?->id,
                'phone_number' => '08120000002',
                'status' => 'active',
            ]
        );
        $adminJkt->syncRoles(['admin-cabang']);

        // 2. Admin Cabang Surabaya
        $adminSby = User::firstOrCreate(
            ['email' => 'admin.sby@lms.test'],
            [
                'name' => 'Admin Cabang Surabaya',
                'password' => $password,
                'branch_id' => $sbyBranch?->id,
                'phone_number' => '08130000003',
                'status' => 'active',
            ]
        );
        $adminSby->syncRoles(['admin-cabang']);
    }
}
