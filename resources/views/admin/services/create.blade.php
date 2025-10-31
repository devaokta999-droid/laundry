@extends('layouts.app')
@section('content')
<h3>Tambah Layanan</h3>
<form method="POST" action="{{ route('services.store') }}">@csrf
<div class="mb-3"><label>Title</label><input class="form-control" name="title"></div>
<div class="mb-3"><label>Description</label><textarea class="form-control" name="description"></textarea></div>
<div class="mb-3"><label>Price</label><input class="form-control" name="price"></div>
<button class="btn btn-success">Simpan</button>
</form>
@endsection