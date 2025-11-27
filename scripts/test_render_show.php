<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$nota = App\Models\Nota::with('items.item','payments.user','user')->first();
if (! $nota) { echo "No nota found\n"; exit(1); }
try {
    echo "Rendering nota show view for nota id={$nota->id}\n";
    $html = view('admin.nota.show', compact('nota'))->render();
    echo "Rendered length: " . strlen($html) . "\n";
} catch (Throwable $e) {
    echo "Exception: " . get_class($e) . " - " . $e->getMessage() . "\n";
    echo $e->getTraceAsString();
}
