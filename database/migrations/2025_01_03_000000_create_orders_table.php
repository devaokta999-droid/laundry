<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


class CreateOrdersTable extends Migration
{
public function up()
{
Schema::create('orders', function (Blueprint $table) {
$table->id();
$table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
$table->string('customer_name');
$table->string('customer_email')->nullable();
$table->string('customer_phone')->nullable();
$table->text('customer_address');
$table->text('notes')->nullable();
$table->json('items')->nullable(); // array of {service_id, qty, price}
$table->decimal('total_price', 12, 2)->default(0);
$table->date('pickup_date')->nullable();
$table->time('pickup_time')->nullable();
$table->string('status')->default('pending');
$table->timestamps();
});
}


public function down()
{
Schema::dropIfExists('orders');
}
}