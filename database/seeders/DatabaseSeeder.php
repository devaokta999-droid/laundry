<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // ✅ Jalankan seeder lain jika ada (misalnya layanan default, dll)
        $this->call([
            AdminAndServicesSeeder::class,
        ]);

        // ✅ Buat akun Admin
        if (!User::where('email', 'admin@example.com')->exists()) {
            User::create([
                'name' => 'Admin',
                'email' => 'admin@example.com',
                'password' => bcrypt('password'),
                'role' => 'admin',
            ]);
        }

        // ✅ Buat akun Kasir
        if (!User::where('email', 'kasir@example.com')->exists()) {
            User::create([
                'name' => 'Kasir',
                'email' => 'kasir@example.com',
                'password' => bcrypt('password'),
                'role' => 'kasir',
            ]);
        }

        // ✅ Buat akun Deva
        if (!User::where('email', 'deva@example.com')->exists()) {
            User::create([
                'name' => 'Deva',
                'email' => 'deva@example.com',
                'password' => bcrypt('123456'),
                'role' => 'deva',
            ]);
        }

        // ✅ (Opsional) Buat user biasa untuk testing
        if (!User::where('email', 'test@example.com')->exists()) {
            User::factory()->create([
                'name' => 'Test User',
                'email' => 'test@example.com',
            ]);
        }
    }
}
