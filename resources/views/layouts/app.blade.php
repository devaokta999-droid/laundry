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
            top: 30px;
            left: 30px;
            width: 260px;
            height: calc(100vh - 60px);
            backdrop-filter: var(--mac-blur);
            background: var(--mac-glass-bg);
            border: 1px solid var(--mac-border);
            border-radius: var(--mac-radius);
            box-shadow: var(--mac-shadow);
            padding: 1.5rem 1rem;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            z-index: 1000;
        }

        .sidebar-header {
            text-align: center;
            margin-bottom: 1rem;
        }

        .sidebar-header img {
            filter: drop-shadow(0 3px 6px rgba(0,0,0,0.1));
        }

        .sidebar-header h4 {
            color: var(--mac-accent);
            font-weight: 700;
        }

        .nav-link {
            color: var(--mac-text);
            font-weight: 500;
            border-radius: 12px;
            margin-bottom: 6px;
            padding: 10px 15px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .nav-link:hover, .nav-link.active {
            background: rgba(0,122,255,0.15);
            color: var(--mac-accent);
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

        .sidebar-toggle {
            display: none;
            position: fixed;
            top: 20px;
            left: 20px;
            background: var(--mac-accent);
            color: white;
            border: none;
            border-radius: 10px;
            padding: 8px 12px;
            z-index: 1100;
            box-shadow: 0 3px 10px rgba(0,122,255,0.4);
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
                top: 20px;
                left: 20px;
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
            <img src="{{ asset('images/' . $appLogo) }}" alt="Logo" width="60" class="mb-2 rounded-3 shadow-sm">
            <h4>DEVA LAUNDRY</h4>
        </div>

        <nav class="nav flex-column">
            <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">Beranda</a>
            <a href="{{ route('about') }}" class="nav-link {{ request()->routeIs('about') ? 'active' : '' }}">Tentang</a>
            <a href="{{ route('contact') }}" class="nav-link {{ request()->routeIs('contact') ? 'active' : '' }}">Kontak</a>

            @auth
                @php $role = auth()->user()->role; @endphp
                <hr>

                {{-- Layanan hanya untuk Admin & Karyawan --}}
                @if(in_array($role, ['admin', 'karyawan']))
                    <a href="{{ route('layanan.index') }}"
                       class="nav-link {{ request()->routeIs('layanan.index') ? 'active' : '' }}">
                        Layanan
                    </a>
                @endif

                {{-- Kasir, Admin, Karyawan dapat mengakses NOTA --}}
                @if(in_array($role, ['kasir', 'admin', 'karyawan']))
                    <a href="{{ route('admin.nota.index') }}" 
                       class="nav-link {{ request()->routeIs('admin.nota.index') ? 'active' : '' }}">
                       Nota
                    </a>
                @endif

                {{-- Admin & Karyawan dapat mengakses LAPORAN --}}
                @if(in_array($role, ['admin', 'karyawan']))
                    <a href="{{ route('admin.laporan') }}" 
                       class="nav-link {{ request()->routeIs('admin.laporan') ? 'active' : '' }}">
                       Laporan
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
                <button class="btn btn-outline-primary dropdown-toggle w-100" data-bs-toggle="dropdown">
                    {{ auth()->user()->name }}
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
