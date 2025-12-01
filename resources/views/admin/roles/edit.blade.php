@extends('layouts.app')

@section('content')
<style>
    .password-wrapper {
        position: relative;
    }
    .password-toggle-edit {
        position: absolute;
        top: 50%;
        right: 14px;
        transform: translateY(-50%);
        cursor: pointer;
        font-size: 1.1rem;
        color: #555;
    }
</style>
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

<div class="container mt-4">
    <h3 class="mb-4 text-primary fw-bold">Edit User & Role</h3>

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
            <form method="POST" action="{{ route('admin.roles.update', $user) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label">Nama</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Password Baru (opsional)</label>
                    <div class="password-wrapper">
                        <input type="password" name="password" id="password-edit" class="form-control">
                        <span class="password-toggle-edit" id="password-edit-toggle" onclick="togglePasswordEdit()">
                            <i class="bi bi-eye-slash"></i>
                        </span>
                    </div>
                    <div class="form-text">Kosongkan jika tidak ingin mengubah password.</div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Foto Profil</label>
                    @if($user->avatar)
                        <div class="mb-2">
                            <img src="{{ asset('storage/'.$user->avatar) }}" alt="{{ $user->name }}" style="width:48px;height:48px;border-radius:50%;object-fit:cover;">
                        </div>
                    @endif
                    <input type="file" name="avatar" class="form-control" accept="image/*">
                    <div class="form-text">Upload gambar baru jika ingin mengganti foto profil. Biarkan kosong jika tidak ingin mengubah.</div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Role</label>
                    <select name="role" class="form-select" required>
                        @foreach($roles as $role)
                            <option value="{{ $role }}" {{ (old('role', $user->role) === $role) ? 'selected' : '' }}>
                                {{ ucfirst($role) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('admin.roles.index') }}" class="btn btn-outline-secondary">Kembali</a>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function togglePasswordEdit() {
        const field = document.getElementById('password-edit');
        const icon = document.querySelector('#password-edit-toggle i');
        if (!field || !icon) return;

        if (field.type === 'password') {
            field.type = 'text';
            icon.classList.remove('bi-eye-slash');
            icon.classList.add('bi-eye');
        } else {
            field.type = 'password';
            icon.classList.remove('bi-eye');
            icon.classList.add('bi-eye-slash');
        }
    }
</script>
@endpush
