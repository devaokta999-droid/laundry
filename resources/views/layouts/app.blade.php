<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Deva Laundry')</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font SF Pro Display -->
    <link href="https://fonts.cdnfonts.com/css/sf-pro-display" rel="stylesheet">

    <!-- Icon -->
    @php
        $appLogo = \App\Models\SiteSetting::getValue('logo_path', 'header.png');
    @endphp
    <link rel="icon" type="image/png" href="{{ asset('images/' . $appLogo) }}">

    <style>
        :root {
            --mac-accent: #007aff;
            --mac-glass-bg: rgba(255, 255, 255, 0.35);
            --mac-border: rgba(255, 255, 255, 0.4);
            --mac-shadow: 0 20px 40px rgba(0,0,0,0.12);
            --mac-radius: 18px;
            --mac-text: #1c1c1e;
            --mac-blur: blur(30px) saturate(180%);
        }

        * {
            font-family: 'SF Pro Display', -apple-system, BlinkMacSystemFont, 'Helvetica Neue', sans-serif;
            transition: all 0.3s ease;
        }

        body {
            background: linear-gradient(135deg, #e8ecf7, #f9fbff);
            color: var(--mac-text);
            overflow-x: hidden;
            min-height: 100vh;
            display: flex;
            justify-content: flex-start;
            align-items: stretch;
        }

        /* Perbesar lebar semua container utama di layar besar */
        @media (min-width: 1200px) {
            .container {
                max-width: 1500px;
            }
        }

        .sidebar {
            position: fixed;
            top: 26px;
            left: 26px;
            width: 270px;
            height: calc(100vh - 52px);
            backdrop-filter: var(--mac-blur);
            background:
                radial-gradient(circle at 0% 0%, rgba(255,255,255,0.94), rgba(243,246,255,0.9)),
                linear-gradient(180deg, rgba(255,255,255,0.94), rgba(226,232,240,0.85));
            border: 1px solid rgba(255,255,255,0.85);
            border-radius: 24px;
            box-shadow:
                0 24px 60px rgba(15,23,42,0.22),
                0 0 0 1px rgba(148,163,184,0.18);
            padding: 1.6rem 1.1rem 1.3rem;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            z-index: 1000;
        }

        .sidebar-header {
            text-align: center;
            margin-bottom: 1.4rem;
            padding: 0 .35rem 0.6rem;
            border-bottom: 1px solid rgba(226,232,240,0.8);
        }

        .sidebar-logo-icon{
            width: 40px;
            height: 40px;
            border-radius: 999px;
            margin: 0 auto .55rem;
            overflow: hidden;
            box-shadow: 0 8px 22px rgba(15,23,42,0.22);
            border: 1px solid rgba(255,255,255,0.9);
            background: radial-gradient(circle at 0% 0%, #ffffff, #e5e7eb);
        }
        .sidebar-logo-icon img{
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .sidebar-header h4 {
            color: var(--mac-accent);
            font-weight: 800;
            letter-spacing: .14em;
            text-transform: uppercase;
            font-size: .78rem;
            margin-bottom: .1rem;
        }

        .sidebar-header span {
            font-size: .82rem;
            color: #6b7280;
        }

        .nav-section-label{
            font-size: .72rem;
            letter-spacing: .22em;
            text-transform: uppercase;
            color: #9ca3af;
            margin: .9rem .45rem .35rem;
        }

        .nav-link {
            color: var(--mac-text);
            font-weight: 500;
            border-radius: 999px;
            margin-bottom: 4px;
            padding: 8px 14px 8px 32px;
            display: flex;
            align-items: center;
            gap: 8px;
            position: relative;
            font-size: .88rem;
            text-decoration: none;
        }

        .nav-link::before {
            content: "";
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            width: 7px;
            height: 7px;
            border-radius: 999px;
            background: rgba(148,163,184,0.6);
            box-shadow: 0 0 0 3px rgba(226,232,240,0.9);
        }

        .nav-link:hover {
            background: rgba(15,23,42,0.04);
            color: var(--mac-accent);
        }

        .nav-link.active {
            background: linear-gradient(135deg, rgba(0,122,255,0.12), rgba(37,99,235,0.14));
            color: var(--mac-accent);
            box-shadow:
                0 10px 24px rgba(37,99,235,0.30),
                0 0 0 1px rgba(191,219,254,0.9);
        }

        .nav-link.active::before{
            background: var(--mac-accent);
            box-shadow: 0 0 0 4px rgba(191,219,254,0.9);
        }

        .btn-cashier {
            background: linear-gradient(180deg, #007aff, #0051cc);
            color: #fff;
            font-weight: 600;
            border: none;
            padding: 10px 15px;
            border-radius: 14px;
            box-shadow: 0 4px 15px rgba(0,122,255,0.3);
            transition: all 0.3s ease;
            width: 100%;
            text-align: center;
        }

        .btn-cashier:hover {
            transform: translateY(-2px);
            background: linear-gradient(180deg, #0a84ff, #0066ff);
        }

        .sidebar .btn {
            border-radius: 12px;
            width: 100%;
            font-weight: 600;
        }

        .btn-outline-primary {
            border: 1.8px solid var(--mac-accent);
            color: var(--mac-accent);
            background: rgba(255,255,255,0.25);
        }

        .btn-outline-primary:hover {
            background: var(--mac-accent);
            color: #fff;
        }
        .sidebar-user-btn{
            display:flex;
            align-items:center;
            justify-content:center;
            gap:8px;
        }
        .sidebar-user-avatar{
            width:22px;
            height:22px;
            border-radius:999px;
            overflow:hidden;
            background:linear-gradient(135deg,#4f46e5,#0ea5e9);
            color:#fff;
            display:inline-flex;
            align-items:center;
            justify-content:center;
            font-size:.7rem;
            font-weight:700;
        }
        .sidebar-user-avatar img{
            width:100%;
            height:100%;
            object-fit:cover;
            display:block;
        }

        .sidebar-toggle {
            display: none;
            position: fixed;
            top: 16px;
            left: 16px;
            background: rgba(15,23,42,0.92);
            color: white;
            border: none;
            border-radius: 999px;
            padding: 7px 12px;
            z-index: 1100;
            box-shadow: 0 6px 18px rgba(15,23,42,0.45);
        }

        .content-wrapper {
            flex: 1;
            padding: 3rem;
            margin-left: 320px;
            width: calc(100% - 320px);
            min-height: 100vh;
            animation: fadeIn 0.6s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: scale(0.98); }
            to { opacity: 1; transform: scale(1); }
        }

        @media (max-width: 992px) {
            .sidebar {
                transform: translateX(-120%);
                transition: all 0.4s ease;
                width: 230px;
                top: 16px;
                left: 16px;
                height: 90vh;
            }

            .sidebar.active {
                transform: translateX(0);
            }

            .sidebar-toggle {
                display: block;
            }

            .content-wrapper {
                margin-left: 0;
                width: 100%;
                padding: 2rem 1rem;
            }
        }

        .fade-out {
            animation: fadeOut 0.4s ease forwards;
        }

        @keyframes fadeOut {
            to { opacity: 0; transform: scale(0.98); }
        }
    </style>
</head>
<body>

<button class="sidebar-toggle" id="sidebarToggle"></button>

<div class="sidebar" id="macSidebar">
    <div>
        <div class="sidebar-header mb-4">
            <div class="sidebar-logo-icon">
                <img src="{{ asset('images/' . $appLogo) }}" alt="Logo">
            </div>
            <h4>DEVA LAUNDRY</h4>
            <span>Premium Laundry Workspace</span>
        </div>

        <nav class="nav flex-column">
            <div class="nav-section-label">Umum</div>
            <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">Beranda</a>
            <a href="{{ route('status.index') }}" class="nav-link {{ request()->routeIs('status.index') ? 'active' : '' }}">Status Laundry</a>
            <a href="{{ route('about') }}" class="nav-link {{ request()->routeIs('about') ? 'active' : '' }}">Tentang</a>
            <a href="{{ route('contact') }}" class="nav-link {{ request()->routeIs('contact') ? 'active' : '' }}">Kontak</a>
            <a href="{{ route('reviews.create') }}" class="nav-link {{ request()->routeIs('reviews.create') ? 'active' : '' }}">Rating & Ulasan</a>

            @auth
                @php $role = auth()->user()->role; @endphp
                <div class="nav-section-label mt-3">Dashboard</div>

                {{-- Admin & Kasir dapat mengakses Status Laundry Dashboard --}}
                @if(in_array($role, ['admin', 'kasir']))
                    <a href="{{ route('admin.orders.status') }}"
                       class="nav-link {{ request()->routeIs('admin.orders.status') ? 'active' : '' }}">
                        Status Laundry
                    </a>
                @endif

                {{-- Kasir & Admin dapat mengakses NOTA --}}
                @if(in_array($role, ['kasir', 'admin']))
                    <a href="{{ route('admin.nota.index') }}" 
                       class="nav-link {{ request()->routeIs('admin.nota.index') ? 'active' : '' }}">
                       Nota
                    </a>
                @endif

                {{-- Admin dapat mengakses LAPORAN & RATING/ULASAN --}}
                @if($role === 'admin')
                    <a href="{{ route('admin.laporan') }}" 
                       class="nav-link {{ request()->routeIs('admin.laporan') ? 'active' : '' }}">
                       Laporan
                    </a>
                    <a href="{{ route('admin.reviews.index') }}" 
                       class="nav-link {{ request()->routeIs('admin.reviews.*') ? 'active' : '' }}">
                        Rating & Ulasan
                    </a>
                @endif

                {{-- Layanan hanya untuk Admin --}}
                @if($role === 'admin')
                    <a href="{{ route('layanan.index') }}"
                       class="nav-link {{ request()->routeIs('layanan.index') ? 'active' : '' }}">
                        Layanan
                    </a>
                @endif

                {{-- Admin: Kelola Tim, Role & Profil --}}
                @if($role === 'admin')
                    <a href="{{ route('admin.team.index') }}" 
                       class="nav-link {{ request()->routeIs('admin.team.*') ? 'active' : '' }}">
                        Tim Profesional
                    </a>
                    <a href="{{ route('admin.roles.index') }}" 
                       class="nav-link {{ request()->routeIs('admin.roles.*') ? 'active' : '' }}">
                        Role & Permission
                    </a>
                    <a href="{{ route('admin.profile') }}" 
                       class="nav-link {{ request()->routeIs('admin.profile') ? 'active' : '' }}">
                        Profil
                    </a>
                @endif
            @endauth
        </nav>
    </div>

    <div class="mt-auto">
        @guest
            <a href="{{ route('login') }}" class="btn btn-primary w-100">Login</a>
        @else
            <div class="dropup w-100">
                <button class="btn btn-outline-primary dropdown-toggle w-100 sidebar-user-btn" data-bs-toggle="dropdown">
                    @php $u = auth()->user(); @endphp
                    <span class="sidebar-user-avatar">
                        @if(!empty($u->avatar))
                            <img src="{{ asset('storage/'.$u->avatar) }}" alt="{{ $u->name }}">
                        @else
                            {{ strtoupper(mb_substr($u->name,0,1,'UTF-8')) }}
                        @endif
                    </span>
                    <span>{{ $u->name }}</span>
                </button>
                <ul class="dropdown-menu dropdown-menu-end w-100">
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger">Logout</button>
                        </form>
                    </li>
                </ul>
            </div>
        @endguest
    </div>
</div>

<div class="content-wrapper" id="page-content">
    @yield('content')
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const sidebar = document.getElementById('macSidebar');
    const toggleBtn = document.getElementById('sidebarToggle');
    const pageContent = document.getElementById('page-content');

    toggleBtn.addEventListener('click', () => {
        sidebar.classList.toggle('active');
    });

    const links = document.querySelectorAll('.nav-link, .btn, .dropdown-item');
    links.forEach(link => {
        link.addEventListener('click', function(e) {
            const url = this.getAttribute('href');
            if (!url || url.startsWith('#') || this.classList.contains('dropdown-toggle')) return;
            e.preventDefault();
            pageContent.classList.add('fade-out');
            setTimeout(() => window.location.href = url, 400);
        });
    });
</script>

@stack('scripts')
</body>
</html>
