<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('nota_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('nota_id')->constrained('notas')->onDelete('cascade');
            $table->foreignId('item_id')->constrained('items')->onDelete('cascade');
            $table->integer('quantity');
            $table->integer('price');
            $table->integer('subtotal');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('nota_items');
    }
};
