<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Perbarui enum kolom role agar mendukung nilai 'karyawan'.
     */
    public function up(): void
    {
        // Sesuaikan enum di kolom role pada tabel users.
        DB::statement("ALTER TABLE `users` MODIFY `role` ENUM('admin','kasir','user','karyawan') NOT NULL DEFAULT 'user'");
    }

    /**
     * Kembalikan enum ke bentuk sebelumnya (opsional).
     * Jika sebelumnya menggunakan 'deva', tambahkan kembali di sini.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE `users` MODIFY `role` ENUM('admin','kasir','user','deva') NOT NULL DEFAULT 'user'");
    }
};

