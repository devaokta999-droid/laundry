@extends('layouts.app')

@section('content')
<style>
    /* 🌈 Font & Layout */
    html, body {
        height: 100%;
        margin: 0;
        padding: 0;
        background: linear-gradient(135deg, #dfe4ea, #f5f6fa);
        font-family: -apple-system, BlinkMacSystemFont, "SF Pro Display", "Segoe UI", Roboto, sans-serif;
    }

    /* 🩵 Full center alignment */
    .register-page-wrapper {
        min-height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 20px;
    }

    /* 🌤️ Container */
    .register-container {
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

    .register-container:hover {
        box-shadow: 0 12px 50px rgba(0, 0, 0, 0.25);
        transform: translateY(-3px);
    }

    /* 🧍‍♀️ Left side */
    .register-illustration {
        flex: 1;
        background: linear-gradient(135deg, #e0e7ff, #f3f4f6);
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 0;
        overflow: hidden;
        border-right: 1px solid rgba(255, 255, 255, 0.3);
    }

    .register-illustration img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    /* 💻 Right form */
    .register-card {
        flex: 1;
        background: rgba(255, 255, 255, 0.55);
        padding: 60px 60px 50px;
        text-align: center;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    /* 🍎 Logo */
    .register-logo {
        display: flex;
        justify-content: center;
        align-items: center;
        margin-bottom: 30px;
    }

    .register-logo img {
        width: 100px;
        height: auto;
        max-height: 100px;
        border-radius: 20px;
        object-fit: contain;
        background: white;
        padding: 5px;
        box-shadow: 0 6px 30px rgba(0, 122, 255, 0.35);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .register-logo img:hover {
        transform: scale(1.05);
        box-shadow: 0 8px 40px rgba(0, 122, 255, 0.45);
    }

    /* ✨ Heading */
    .register-card h3 {
        font-weight: 600;
        color: #1a1a1a;
        margin-bottom: 15px;
        letter-spacing: 0.5px;
        font-size: 1.5rem;
    }

    .register-card p {
        color: #555;
        font-size: 0.9rem;
        margin-bottom: 25px;
    }

    /* 📧 Input fields */
    .form-control {
        border-radius: 14px;
        border: 1px solid rgba(0, 0, 0, 0.1);
        background: rgba(255, 255, 255, 0.6);
        padding: 12px 14px;
        font-size: 1rem;
        transition: all 0.2s ease;
    }

    .form-control:focus {
        border-color: #007aff;
        box-shadow: 0 0 0 3px rgba(0, 122, 255, 0.25);
        background: rgba(255, 255, 255, 0.9);
    }

    /* 🔒 Password field */
    .password-wrapper {
        position: relative;
    }

    .password-toggle {
        position: absolute;
        right: 14px;
        top: 50%;
        transform: translateY(-50%);
        cursor: pointer;
        font-size: 1.2rem;
        color: #555;
        transition: color 0.2s ease;
    }

    .password-toggle:hover {
        color: #007aff;
    }

    /* 🔘 Button */
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

    .btn-primary:hover {
        background: linear-gradient(135deg, #0066e0, #004ec2);
        transform: translateY(-1px);
    }

    /* ⚠️ Error alert */
    .alert {
        border-radius: 12px;
        font-size: 0.9rem;
    }

    /* 🩶 Login link */
    .login-link {
        margin-top: 18px;
        font-size: 0.88rem;
        color: #555;
    }

    .login-link a {
        color: #007aff;
        font-weight: 500;
        text-decoration: none;
        transition: color 0.2s ease;
    }

    .login-link a:hover {
        color: #004ec2;
        text-decoration: underline;
    }

    /* 🍎 Footer */
    .register-footer {
        margin-top: 25px;
        color: #555;
        font-size: 0.85rem;
    }

    /* ✨ Animation */
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* 📱 Responsive */
    @media (max-width: 768px) {
        .register-container {
            flex-direction: column;
            max-width: 90%;
        }

        .register-illustration {
            height: 240px;
            border-right: none;
        }

        .register-card {
            padding: 40px 30px;
        }

        .register-logo img {
            width: 80px;
            max-height: 80px;
        }
    }
</style>

{{-- Bootstrap Icons --}}
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

<div class="register-page-wrapper">
    <div class="register-container">

        {{-- 🧍‍♀️ Left Illustration --}}
        <div class="register-illustration">
            <img src="{{ asset('images/picture.jpg') }}" alt="Register Illustration">
        </div>

        {{-- 💻 Right Register Form --}}
        <div class="register-card">
            {{-- 🍎 Logo --}}
            <div class="register-logo">
                <img src="{{ asset('images/header.png') }}" alt="Deva Laundry Logo">
            </div>

            <h3>Create Your Deva Laundry Account</h3>
            <p>Join the family of freshness and simplicity.</p>

            @if ($errors->any())
                <div class="alert alert-danger">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ url('register') }}">
                @csrf

                <div class="mb-3 text-start">
                    <label class="form-label fw-semibold">Name</label>
                    <input type="text" class="form-control" name="name" value="{{ old('name') }}" required>
                </div>

                <div class="mb-3 text-start">
                    <label class="form-label fw-semibold">Email</label>
                    <input type="email" class="form-control" name="email" value="{{ old('email') }}" required>
                </div>

                <div class="mb-3 text-start">
                    <label class="form-label fw-semibold">Password</label>
                    <div class="password-wrapper">
                        <input type="password" class="form-control" id="password" name="password" required>
                        <span class="password-toggle" onclick="togglePassword()">
                            <i class="bi bi-eye"></i>
                        </span>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-100">Register</button>
            </form>

            {{-- 🔗 Login link --}}
            <div class="login-link">
                Already have an account?
                <a href="{{ route('login') }}">Sign in here</a>
            </div>

            <div class="register-footer">
                © {{ date('Y') }} Deva Laundry
            </div>
        </div>

    </div>
</div>

{{-- 👁️ Script for Show/Hide Password --}}
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
