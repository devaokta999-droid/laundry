<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';

// bootstrap kernel
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\ItemLaundry;
use App\Models\Nota;
use App\Models\NotaItem;
use App\Models\Payment;

// find user
$u = User::first();
if (! $u) {
    echo "No user found\n";
    exit(1);
}
echo "Using user: {$u->id} - {$u->name}\n";

$item = ItemLaundry::first() ?: ItemLaundry::create(['name' => 'Test Item', 'price' => 50000]);

$nota = Nota::create([
    'user_id' => $u->id,
    'customer_name' => 'Test Customer',
    'customer_address' => 'Jl Test',
    'tgl_masuk' => now(),
    'tgl_keluar' => null,
    'total' => 100000,
    'uang_muka' => 0,
    'sisa' => 100000,
]);

NotaItem::create([
    'nota_id' => $nota->id,
    'item_id' => $item->id,
    'quantity' => 2,
    'price' => 50000,
    'subtotal' => 100000,
]);

echo "Created nota id={$nota->id} total={$nota->total} sisa={$nota->sisa}\n";

$controller = app(App\Http\Controllers\NotaController::class);
$req = new Illuminate\Http\Request(['amount' => 50000, 'type' => 'cash']);
$req->headers->set('X-Requested-With', 'XMLHttpRequest');
$res = $controller->pay($req, $nota->id);

if (method_exists($res, 'getContent')) {
    echo "Response: \n";
    print_r(json_decode($res->getContent(), true));
} else {
    echo "Response class: " . get_class($res) . "\n";
}

$payments = Payment::where('nota_id', $nota->id)->get();
echo "Payments for nota {$nota->id}: count=" . count($payments) . "\n";
foreach ($payments as $p) {
    echo "- id={$p->id} amount={$p->amount} type={$p->type} method={$p->method} user_id={$p->user_id} created_at={$p->created_at}\n";
}

$notaRef = Nota::find($nota->id);
echo "Nota after payment: uang_muka={$notaRef->uang_muka} sisa={$notaRef->sisa}\n";

return 0;
