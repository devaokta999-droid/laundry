<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Nota Laundry</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .nota { max-width: 400px; margin: 0 auto; }
        .center { text-align: center; }
        table { width: 100%; border-collapse: collapse; }
        td, th { padding: 4px; }
        hr { border: 1px dashed #000; }
    </style>
</head>
<body onload="window.print()">
    <div class="nota">
        <h3 class="center">Deva Laundry</h3>
        <p class="center">Jl. Wisnu Marga No.Belayu, Peken, Kec. Marga, Kabupaten Tabanan, Bali 82181</p>
        <hr>

        <p><strong>Pelanggan:</strong> {{ $order->user->name }}</p>
        <p><strong>Layanan:</strong> {{ $order->service->nama_layanan }}</p>
        <p><strong>Berat:</strong> {{ $order->weight }} Kg</p>
        <p><strong>Total Bayar:</strong> Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>

        <hr>
        <p class="center">Terima kasih telah menggunakan layanan kami!</p>
    </div>
</body>
</html>
