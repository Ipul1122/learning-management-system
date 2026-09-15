<?php

namespace Database\Seeders\Peserta;

use App\Models\Branch;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class PesertaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $password = Hash::make('password');
        $jktBranch = Branch::where('code', 'JKT-01')->first();

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
