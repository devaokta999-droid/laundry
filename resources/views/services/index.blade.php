@extends('layouts.app')
@section('content')
<h3>Layanan</h3>
@auth
@if(auth()->user()->is_admin)
<a class="btn btn-primary mb-3" href="{{ route('services.create') }}">Tambah Layanan</a>
@endif
@endauth
<div class="row">
@foreach($services as $s)
<div class="col-md-4"><div class="card p-3 mb-2"><h5>{{ $s->title }}</h5><p>{{ $s->description }}</p><p>Rp {{ number_format($s->price,0,',','.') }}</p></div></div>
@endforeach
</div>
@endsection