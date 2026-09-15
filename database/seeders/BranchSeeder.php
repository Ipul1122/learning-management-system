<?php

namespace Database\Seeders;

use App\Models\Branch;
use Illuminate\Database\Seeder;

class BranchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Branch::firstOrCreate(
            ['code' => 'JKT-01'],
            [
                'name' => 'Cabang Jakarta Pusat',
                'address' => 'Jl. Salemba Raya No. 45, Senen, Jakarta Pusat',
                'city' => 'Jakarta Pusat',
                'phone' => '021-3901234',
                'is_active' => true,
            ]
        );

        Branch::firstOrCreate(
            ['code' => 'SBY-01'],
            [
                'name' => 'Cabang Surabaya',
                'address' => 'Jl. Pemuda No. 88, Genteng, Surabaya',
                'city' => 'Surabaya',
                'phone' => '031-5345678',
                'is_active' => true,
            ]
        );
    }
}
