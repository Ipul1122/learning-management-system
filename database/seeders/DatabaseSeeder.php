<?php

namespace Database\Seeders;

use Database\Seeders\AdminCabang\AdminCabangSeeder;
use Database\Seeders\Peserta\PesertaSeeder;
use Database\Seeders\SuperAdmin\SuperAdminSeeder;
use Database\Seeders\Trainer\TrainerSeeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RolePermissionSeeder::class,
            BranchSeeder::class,
            SuperAdminSeeder::class,
            AdminCabangSeeder::class,
            TrainerSeeder::class,
            PesertaSeeder::class,
        ]);
    }
}
