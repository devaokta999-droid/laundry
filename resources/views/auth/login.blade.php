@extends('layouts.app')

@section('content')
<style>
    html, body {
        height: 100%;
        margin: 0;
        padding: 0;
        background: linear-gradient(135deg, #dfe4ea, #f5f6fa);
        font-family: -apple-system, BlinkMacSystemFont, "SF Pro Display", "Segoe UI", Roboto, sans-serif;
    }

    .login-page-wrapper {
        min-height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 20px;
    }

    .login-container {
        display: flex;
        background: rgba(255, 255, 255, 0.25);
        backdrop-filter: blur(16px) saturate(180%);
        -webkit-backdrop-filter: blur(16px) saturate(180%);
        border-radius: 24px;
        border: 1px solid rgba(255, 255, 255, 0.3);
        box-shadow: 0 10px 40px rgba(31, 38, 135, 0.25);
        overflow: hidden;
        max-width: 900px;
        width: 100%;
        transition: all 0.3s ease;
        animation: fadeIn 0.8s ease;
    }

    .login-container:hover {
        box-shadow: 0 12px 50px rgba(0, 0, 0, 0.25);
        transform: translateY(-3px);
    }

    .login-illustration {
        flex: 1;
        background: linear-gradient(135deg, #e0e7ff, #f3f4f6);
        display: flex;
        justify-content: center;
        align-items: center;
        overflow: hidden;
        border-right: 1px solid rgba(255, 255, 255, 0.3);
    }

    .login-illustration img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .login-card {
        flex: 1;
        background: rgba(255, 255, 255, 0.55);
        padding: 60px 60px 50px;
        text-align: center;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .login-logo {
        display: flex;
        justify-content: center;
        align-items: center;
        margin-bottom: 30px;
    }

    .login-logo img {
        width: 100px;
        height: auto;
        border-radius: 20px;
        object-fit: contain;
        background: white;
        padding: 5px;
        box-shadow: 0 6px 30px rgba(0, 122, 255, 0.35);
    }

    .login-card h3 {
        font-weight: 600;
        color: #1a1a1a;
        margin-bottom: 15px;
        letter-spacing: 0.5px;
        font-size: 1.5rem;
    }

    .login-card p {
        color: #555;
        font-size: 0.9rem;
        margin-bottom: 25px;
    }

    .form-control {
        border-radius: 14px;
        border: 1px solid rgba(0, 0, 0, 0.1);
        background: rgba(255, 255, 255, 0.6);
        padding: 12px 14px;
        font-size: 1rem;
        transition: all 0.2s ease;
        width: 100%;
    }

    .form-control:focus {
        border-color: #007aff;
        box-shadow: 0 0 0 3px rgba(0, 122, 255, 0.25);
        background: rgba(255, 255, 255, 0.9);
    }

    .password-wrapper {
        position: relative;
    }

    .password-toggle {
        position: absolute;
        top: 50%;
        right: 14px;
        transform: translateY(-50%);
        cursor: pointer;
        font-size: 1.2rem;
        color: #555;
        transition: color 0.2s ease;
    }

    .btn-primary {
        border-radius: 14px;
        background: linear-gradient(135deg, #007aff, #0056d8);
        border: none;
        transition: all 0.3s ease;
        font-weight: 500;
        font-size: 1rem;
        letter-spacing: 0.3px;
        padding: 12px 0;
    }

    .login-footer {
        margin-top: 25px;
        color: #555;
        font-size: 0.85rem;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    @media (max-width: 768px) {
        .login-container {
            flex-direction: column;
            max-width: 90%;
        }

        .login-illustration {
            height: 240px;
            border-right: none;
            border-bottom: 1px solid rgba(255, 255, 255, 0.3);
        }

        .login-card {
            padding: 40px 30px;
        }

        .login-logo img {
            width: 80px;
        }
    }
</style>

<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

<div class="login-page-wrapper">
    <div class="login-container">

        <div class="login-illustration">
            <img src="{{ asset('images/picture.jpg') }}" alt="Login Illustration">
        </div>

        <div class="login-card">
            <div class="login-logo">
                <img src="{{ asset('images/header.png') }}" alt="Deva Laundry Logo">
            </div>

            <h3>Welcome Back</h3>
            <p>Login to continue your clean journey.</p>

            @if ($errors->any())
                <div class="alert alert-danger">
                    {{ $errors->first() }}
                </div>
            @endif

            @if(session('new_password'))
                <div class="alert alert-success">
                    Password baru untuk <strong>{{ session('email') }}</strong>:<br>
                    <code>{{ session('new_password') }}</code><br>
                    Silakan gunakan password ini untuk login, lalu ganti di menu yang sesuai.
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="mb-3 text-start">
                    <label class="form-label fw-semibold">Email</label>
                    <input type="email" class="form-control" id="login-email" name="email" value="{{ old('email') ?? session('email') }}" required autofocus>
                </div>

                <div class="mb-3 text-start">
                    <label class="form-label fw-semibold">Password</label>
                    <div class="password-wrapper">
                        <input type="password" class="form-control" id="password" name="password" required>
                        <span class="password-toggle" onclick="togglePassword()">
                            <i class="bi bi-eye-slash"></i>
                        </span>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-100 mb-2">Sign In</button>
            </form>

            <div class="login-footer">
                © {{ date('Y') }} Deva Laundry
            </div>
        </div>
    </div>
</div>

<script>
    function togglePassword() {
        const passwordField = document.getElementById('password');
        const toggleIcon = document.querySelector('.password-toggle i');

        if (passwordField.type === 'password') {
            passwordField.type = 'text';
            toggleIcon.classList.remove('bi-eye-slash');
            toggleIcon.classList.add('bi-eye');
        } else {
            passwordField.type = 'password';
            toggleIcon.classList.remove('bi-eye');
            toggleIcon.classList.add('bi-eye-slash');
        }
    }

</script>
@endsection
