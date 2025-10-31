@extends('layouts.app')
@section('content')
<div class="row justify-content-center">
<div class="col-md-6">
<h3>Register</h3>
<form method="POST" action="{{ url('register') }}">@csrf
<div class="mb-3"><label>Name</label><input class="form-control" name="name" value="{{ old('name') }}"></div>
<div class="mb-3"><label>Email</label><input class="form-control" name="email" value="{{ old('email') }}"></div>
<div class="mb-3"><label>Password</label><input type="password" class="form-control" name="password"></div>
<div class="mb-3"><label>Phone</label><input class="form-control" name="phone"></div>
<div class="mb-3"><label>Address</label><textarea class="form-control" name="address"></textarea></div>
<button class="btn btn-primary">Register</button>
</form>
</div>
</div>
@endsection