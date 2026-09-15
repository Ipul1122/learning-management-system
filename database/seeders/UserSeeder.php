<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $password = Hash::make('password');

        $jktBranch = Branch::where('code', 'JKT-01')->first();
        $sbyBranch = Branch::where('code', 'SBY-01')->first();

        // 1. Super Admin (Global, tanpa cabang)
        $superAdmin = User::firstOrCreate(
            ['email' => 'superadmin@lms.test'],
            [
                'name' => 'Super Administrator',
                'password' => $password,
                'branch_id' => null,
                'phone_number' => '08110000001',
                'status' => 'active',
            ]
        );
        $superAdmin->syncRoles(['super-admin']);

        // 2. Admin Cabang Jakarta
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

        // 3. Admin Cabang Surabaya
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

        // 4. Trainer 1 (Jakarta)
        $trainer1 = User::firstOrCreate(
            ['email' => 'trainer1@lms.test'],
            [
                'name' => 'Ahmad Fauzi, M.Kom',
                'password' => $password,
                'branch_id' => $jktBranch?->id,
                'phone_number' => '08140000004',
                'status' => 'active',
            ]
        );
        $trainer1->syncRoles(['trainer']);

        // 5. Trainer 2 (Surabaya)
        $trainer2 = User::firstOrCreate(
            ['email' => 'trainer2@lms.test'],
            [
                'name' => 'Siti Nurhaliza, S.T',
                'password' => $password,
                'branch_id' => $sbyBranch?->id,
                'phone_number' => '08150000005',
                'status' => 'active',
            ]
        );
        $trainer2->syncRoles(['trainer']);

        // 6. Peserta (3 Akun Demo)
        $students = [
            ['name' => 'Dimas Pratama', 'email' => 'peserta1@lms.test', 'phone' => '08160000006'],
            ['name' => 'Rina Anggraini', 'email' => 'peserta2@lms.test', 'phone' => '08170000007'],
            ['name' => 'Bayu Wicaksono', 'email' => 'peserta3@lms.test', 'phone' => '08180000008'],
        ];

        foreach ($students as $studentData) {
            $student = User::firstOrCreate(
                ['email' => $studentData['email']],
                [
                    'name' => $studentData['name'],
                    'password' => $password,
                    'branch_id' => $jktBranch?->id,
                    'phone_number' => $studentData['phone'],
                    'status' => 'active',
                ]
            );
            $student->syncRoles(['peserta']);
        }
    }
}
