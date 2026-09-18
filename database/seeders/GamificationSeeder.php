<?php

namespace Database\Seeders;

use App\Models\Badge;
use App\Services\GamificationService;
use Illuminate\Database\Seeder;

class GamificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $badges = [
            [
                'slug' => 'langkah-awal',
                'name' => 'Langkah Awal',
                'description' => 'Mendaftar dan memulai langkah pembelajaran pada kelas pelatihan pertama.',
                'icon' => 'rocket',
                'category' => 'enrollment',
                'xp_reward' => 50,
            ],
            [
                'slug' => 'pejuang-waktu',
                'name' => 'Pejuang 10 JP',
                'description' => 'Menuntaskan akumulasi 10 Jam Pelajaran (450 menit) pembelajaran aktif.',
                'icon' => 'clock',
                'category' => 'study',
                'xp_reward' => 100,
            ],
            [
                'slug' => 'juara-20-jp',
                'name' => 'Master 20 JP',
                'description' => 'Tuntas menyelesaikan syarat kumulatif 20 Jam Pelajaran (900 menit) pelatihan.',
                'icon' => 'trophy',
                'category' => 'study',
                'xp_reward' => 250,
            ],
            [
                'slug' => 'kuis-sempurna',
                'name' => 'Akurasi Kuis',
                'description' => 'Meraih nilai kuis evaluasi pemahaman materi dengan skor 90 atau lebih.',
                'icon' => 'target',
                'category' => 'quiz',
                'xp_reward' => 150,
            ],
            [
                'slug' => 'lulusan-kompeten',
                'name' => 'Lulusan Kompeten',
                'description' => 'Lulus secara sah dan memperoleh E-Sertifikat resmi terverifikasi QR.',
                'icon' => 'medal',
                'category' => 'graduation',
                'xp_reward' => 500,
            ],
            [
                'slug' => 'kontributor-forum',
                'name' => 'Aktivis Diskusi',
                'description' => 'Aktif berdiskusi, bertanya, atau membagikan wawasan di forum kelas.',
                'icon' => 'chat',
                'category' => 'forum',
                'xp_reward' => 50,
            ],
        ];

        foreach ($badges as $badge) {
            Badge::updateOrCreate(['slug' => $badge['slug']], $badge);
        }

        // Sinkronisasi data gamifikasi peserta yang sudah ada di database
        $service = app(GamificationService::class);
        $service->syncAllExistingUsers();
    }
}
