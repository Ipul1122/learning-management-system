<?php

namespace Database\Seeders\Trainer;

use App\Models\Branch;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TrainerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $password = Hash::make('password');
        $jktBranch = Branch::where('code', 'JKT-01')->first();
        $sbyBranch = Branch::where('code', 'SBY-01')->first();

        // 1. Trainer 1 (Jakarta)
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

        // 2. Trainer 2 (Surabaya)
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
    }
}
