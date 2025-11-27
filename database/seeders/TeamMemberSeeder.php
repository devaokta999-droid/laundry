<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TeamMember;

class TeamMemberSeeder extends Seeder
{
    public function run(): void
    {
        if (TeamMember::count() > 0) {
            return;
        }

        $defaults = [
            [
                'name' => 'Deva Okta',
                'position' => 'Owner & Founder',
                'photo' => 'owner.jpg',
                'description' => 'Membangun Deva Laundry dengan visi pelayanan terbaik dan inovasi berkelanjutan.',
            ],
            [
                'name' => 'Rano Utama',
                'position' => 'Customer Service',
                'photo' => 'team2.jpg',
                'description' => 'Siap melayani pelanggan dengan ramah dan cepat setiap hari.',
            ],
            [
                'name' => 'Bayu Pratama',
                'position' => 'Supervisor Laundry',
                'photo' => 'team 3.jpg',
                'description' => 'Mengawasi setiap proses pencucian agar hasil selalu maksimal dan tepat waktu.',
            ],
        ];

        foreach ($defaults as $data) {
            TeamMember::create($data);
        }
    }
}

