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
    }

    /* 🌤️ Register Card (macOS glass effect) */
    .register-card {
        background: rgba(255, 255, 255, 0.25);
        backdrop-filter: blur(16px) saturate(180%);
        -webkit-backdrop-filter: blur(16px) saturate(180%);
        border-radius: 24px;
        border: 1px solid rgba(255, 255, 255, 0.3);
        box-shadow: 0 10px 40px rgba(31, 38, 135, 0.25);
        padding: 60px 60px 50px;
        width: 100%;
        max-width: 460px;
        text-align: center;
        transition: all 0.3s ease;
        animation: fadeIn 0.8s ease;
    }

    .register-card:hover {
        box-shadow: 0 12px 50px rgba(0, 0, 0, 0.25);
        transform: translateY(-3px);
    }

    /* 🍎 Logo */
    .register-logo {
        display: flex;
        justify-content: center;
        align-items: center;
        margin-bottom: 30px;
    }

    .register-logo img {
        width: 120px;
        height: auto;
        max-height: 120px;
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
        margin-bottom: 30px;
        letter-spacing: 0.5px;
        font-size: 1.5rem;
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

    /* 🍎 Subtle footer text */
    .register-footer {
        margin-top: 25px;
        color: #555;
        font-size: 0.85rem;
    }

    /* ✨ Fade animation */
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Responsive */
    @media (max-width: 576px) {
        .register-card {
            max-width: 90%;
            padding: 40px 30px;
        }

        .register-logo img {
            width: 90px;
            max-height: 90px;
        }
    }
</style>

<div class="register-page-wrapper">
    <div class="register-card">
        {{-- 🍎 Logo Deva Laundry --}}
        <div class="register-logo">
            <img src="{{ asset('images/header.png') }}" alt="Deva Laundry Logo">
        </div>

        <h3>Create Your Deva Laundry Account</h3>

        {{-- 🔥 Pesan error --}}
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
                <input type="password" class="form-control" name="password" required>
            </div>

            <button type="submit" class="btn btn-primary w-100">Register</button>
        </form>

        <div class="register-footer">
            © {{ date('Y') }} Deva Laundry 
        </div>
    </div>
</div>
@endsection
