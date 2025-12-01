@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h3 class="mb-4 text-primary fw-bold">Tambah User dengan Role</h3>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.roles.store') }}" enctype="multipart/form-data">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Nama</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <div class="input-group">
                        <input type="password" name="password" id="passwordField" class="form-control" required>
                        <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                            <span id="iconShow">Show</span>
                            <span id="iconHide" style="display:none;">Hide</span>
                        </button>
                    </div>
                    <div class="form-text">Minimal 6 karakter. Gunakan tombol Show/Hide untuk melihat password.</div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Foto Profil</label>
                    <input type="file" name="avatar" class="form-control" accept="image/*">
                    <div class="form-text">Pilih file gambar (opsional) untuk foto profil pada daftar role.</div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Role</label>
                    <select name="role" class="form-select" required>
                        @foreach($roles as $role)
                            <option value="{{ $role }}" {{ old('role') === $role ? 'selected' : '' }}>
                                {{ ucfirst($role) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('admin.roles.index') }}" class="btn btn-outline-secondary">Kembali</a>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const field = document.getElementById('passwordField');
        const toggle = document.getElementById('togglePassword');
        const iconShow = document.getElementById('iconShow');
        const iconHide = document.getElementById('iconHide');

        if (field && toggle) {
            toggle.addEventListener('click', function () {
                const isHidden = field.type === 'password';
                field.type = isHidden ? 'text' : 'password';
                iconShow.style.display = isHidden ? 'none' : 'inline';
                iconHide.style.display = isHidden ? 'inline' : 'none';
            });
        }
    });
</script>
@endpush
