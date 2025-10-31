<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Service;

class AdminAndServicesSeeder extends Seeder
{
    /**
     * Jalankan seeder.
     */
    public function run(): void
    {
        // 🧍 Admin
        User::create([
            'name' => 'Admin Deva',
            'email' => 'admin@devalaundry.test',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'phone' => '081234567890',
        ]);

        // 👩‍💼 Kasir
        User::create([
            'name' => 'Kasir Deva',
            'email' => 'kasir@devalaundry.test',
            'password' => Hash::make('password'),
            'role' => 'kasir',
            'phone' => '081234567891',
        ]);

        // 🧺 Layanan Laundry (gunakan kolom `name`, bukan `title`)
        Service::create([
            'name' => 'Cuci Kering',
            'price' => 7000,
            'description' => 'Pakaian dicuci dan dikeringkan tanpa setrika.',
        ]);

        Service::create([
            'name' => 'Cuci Setrika',
            'price' => 10000,
            'description' => 'Pakaian dicuci, dikeringkan, dan disetrika rapi.',
        ]);

        Service::create([
            'name' => 'Setrika Saja',
            'price' => 5000,
            'description' => 'Hanya setrika pakaian pelanggan.',
        ]);
    }
}
