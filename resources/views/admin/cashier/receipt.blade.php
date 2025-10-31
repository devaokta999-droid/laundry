<!doctype html>
<html>
<head><meta charset="utf-8"><title>Nota</title></head>
<body>
<h3>Deva Laundry</h3>
<p>Nota: {{ $data['nota_no'] ?? '' }}</p>
<table width="100%">
<thead><tr><th>Item</th><th>Qty</th><th>Harga</th><th>Subtotal</th></tr></thead>
<tbody>
@php $total=0; @endphp
@for($i=0;$i<count($data['item_title'] ?? []);$i++)
@php
$title = $data['item_title'][$i];
$qty = intval($data['item_qty'][$i] ?? 0);
$price = floatval($data['item_price'][$i] ?? 0);
$sub = $qty*$price;
$total += $sub;
@endphp
<tr><td>{{ $title }}</td><td>{{ $qty }}</td><td>Rp {{ number_format($price,0,',','.') }}</td><td>Rp {{ number_format($sub,0,',','.') }}</td></tr>
@endfor
</tbody>
</table>
<h4>Total: Rp {{ number_format($total,0,',','.') }}</h4>
</body>
</html>