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
    .login-page-wrapper {
        min-height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 20px;
    }

    /* 🌤️ Container with image and form side by side */
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

    /* 🧍‍♂️ Left side - illustration */
    .login-illustration {
        flex: 1;
        position: relative;
        overflow: hidden;
        display: flex;
        justify-content: center;
        align-items: center;
        border-right: 1px solid rgba(255, 255, 255, 0.3);
    }

    .login-illustration img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border: none;
        border-radius: 0;
        transition: transform 0.6s ease;
    }

    .login-illustration::after {
        content: "";
        position: absolute;
        inset: 0;
        background: linear-gradient(to top right, rgba(0, 0, 0, 0.05), rgba(255, 255, 255, 0.1));
        pointer-events: none;
    }

    .login-illustration img:hover {
        transform: scale(1.05);
    }

    /* 🌤️ Right side - form */
    .login-card {
        flex: 1;
        background: rgba(255, 255, 255, 0.55);
        padding: 60px 60px 50px;
        text-align: center;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    /* 🍎 Logo */
    .login-logo {
        display: flex;
        justify-content: center;
        align-items: center;
        margin-bottom: 30px;
    }

    .login-logo img {
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

    .login-logo img:hover {
        transform: scale(1.05);
        box-shadow: 0 8px 40px rgba(0, 122, 255, 0.45);
    }

    /* ✨ Heading */
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

    /* 📧 Input fields */
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

    /* 👁️ Password field wrapper */
    .password-wrapper {
        position: relative;
    }

    .password-toggle {
        position: absolute;
        top: 50%;
        right: 14px;
        transform: translateY(-50%);
        cursor: pointer;
        width: 22px;
        height: 22px;
        opacity: 0.75;
        transition: all 0.3s ease;
    }

    .password-toggle:hover {
        opacity: 1;
        transform: translateY(-50%) scale(1.1);
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

    /* 🍎 Footer */
    .login-footer {
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
        .login-container {
            flex-direction: column;
            max-width: 90%;
        }

        .login-illustration {
            height: 240px;
            border-right: none;
            border-bottom: 1px solid rgba(255, 255, 255, 0.3);
        }

        .login-illustration img {
            object-fit: cover;
        }

        .login-card {
            padding: 40px 30px;
        }

        .login-logo img {
            width: 80px;
            max-height: 80px;
        }
    }
</style>

<div class="login-page-wrapper">
    <div class="login-container">

        {{-- 🧍‍♂️ Left Illustration --}}
        <div class="login-illustration">
            <img src="{{ asset('images/lg.jpeg') }}" alt="Login Illustration">
        </div>

        {{-- 💻 Right Login Form --}}
        <div class="login-card">
            <div class="login-logo">
                <img src="{{ asset('images/header.png') }}" alt="Deva Laundry Logo">
            </div>

            <h3>Sign In</h3>
            <p>Unlock your world.</p>

            @if ($errors->any())
                <div class="alert alert-danger">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="mb-3 text-start">
                    <label class="form-label fw-semibold">Email</label>
                    <input type="email" class="form-control" name="email" value="{{ old('email') }}" required autofocus>
                </div>

                <div class="mb-3 text-start">
                    <label class="form-label fw-semibold">Password</label>
                    <div class="password-wrapper">
                        <input type="password" class="form-control" id="password" name="password" required>
                        <img src="{{ asset('images/eye-closed.png') }}" id="togglePassword" class="password-toggle" alt="Toggle Password">
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-100">Sign In</button>
            </form>

            <div class="login-footer">
                © {{ date('Y') }} Deva Laundry — macOS Premium UI
            </div>
        </div>
    </div>
</div>

{{-- 👁️ JavaScript for toggle --}}
<script>
    const togglePassword = document.getElementById('togglePassword');
    const passwordField = document.getElementById('password');

    togglePassword.addEventListener('click', function () {
        const isPassword = passwordField.type === 'password';
        passwordField.type = isPassword ? 'text' : 'password';
        togglePassword.src = isPassword 
            ? "{{ asset('images/eye-open.png') }}" 
            : "{{ asset('images/eye-close.png') }}";
    });
</script>
@endsection
