@extends('layouts.app')
@section('content')
<div class="text-center py-5">
<h1 class="display-4">Deva Laundry</h1>
<p class="lead">Cuci Bersih Wangi dan Cepat Selesai solusi cepat untuk pakaian kamu</p>
<div class="mt-4">
<a href="{{ route('order.create') }}" class="btn btn-primary btn-lg">Pesan Sekarang</a>
<a target="_blank" href="https://wa.me/{{ config('app.admin_whatsapp') }}?text=Halo%20Deva%20Laundry" class="btn btn-success btn-lg">Hubungi Kami</a>
</div>
</div>


<div class="row">
<h3>Layanan Kami</h3>
@foreach($services as $s)
<div class="col-md-4">
<div class="card mb-3">
<div class="card-body">
<h5>{{ $s->title }}</h5>
<p>{{ $s->description }}</p>
<p><strong>Rp {{ number_format($s->price,0,',','.') }}</strong></p>
</div>
</div>
</div>
@endforeach
</div>


<div class="mt-4">
<h3>Promo</h3>
<div class="row">
@foreach($promos as $p)
<div class="col-md-6"><div class="card p-3 mb-2"><h5>{{ $p['title'] }}</h5><p>{{ $p['desc'] }}</p></div></div>
@endforeach
</div>
</div>


<div class="mt-4">
<a href="{{ route('about') }}">Tentang Kami</a> | <a href="{{ route('contact') }}">Kontak</a>
</div>
@endsection