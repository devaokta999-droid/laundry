<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('notas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('customer_name');
            $table->string('customer_address')->nullable();
            $table->date('tgl_masuk')->default(now());
            $table->date('tgl_keluar')->nullable();
            $table->integer('total')->default(0);
            $table->integer('uang_muka')->default(0);
            $table->integer('sisa')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('notas');
    }
};
