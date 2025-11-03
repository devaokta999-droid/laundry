<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi: membuat tabel `services`
     */
    public function up(): void
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();

            // 🧺 Nama layanan (contoh: Cuci Kering, Setrika, Cuci + Lipat)
            $table->string('name', 150);

            // 📄 Deskripsi tambahan (opsional)
            $table->text('description')->nullable();

            // 💰 Harga dalam format desimal
            // Total maksimal 99999999.99 (cukup besar untuk kebutuhan laundry)
            $table->decimal('price', 10, 2)->default(0);

            // 🕓 Timestamps created_at & updated_at otomatis
            $table->timestamps();
        });
    }

    /**
     * Rollback migrasi: menghapus tabel `services`
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
