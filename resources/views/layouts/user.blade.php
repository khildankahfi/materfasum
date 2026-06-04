<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Materfasum') - Pelaporan Fasilitas Umum</title>

    <!-- Bootstrap 5 -->
    <link href="{{ asset('vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="{{ asset('vendor/bootstrap-icons/bootstrap-icons.min.css') }}" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary: #2563eb;
            --primary-dark: #1d4ed8;
            --sidebar-width: 260px;
            --sidebar-bg: #1e293b;
            --sidebar-text: #cbd5e1;
            --sidebar-hover: #334155;
            --sidebar-active: #2563eb;
        }

        * { font-family: 'Inter', sans-serif; }

        body { background: #f1f5f9; min-height: 100vh; }

        /* ── Sidebar ── */
        .sidebar {
            width: var(--sidebar-width);
            background: var(--sidebar-bg);
            min-height: 100vh;
            position: fixed;
            top: 0; left: 0;
            z-index: 1000;
            transition: transform .3s ease;
            display: flex;
            flex-direction: column;
        }

        .sidebar-brand {
            padding: 1.5rem 1.25rem;
            border-bottom: 1px solid #334155;
        }

        .sidebar-brand h5 {
            color: #fff;
            font-weight: 700;
            font-size: 1.1rem;
            margin: 0;
            letter-spacing: .5px;
        }

        .sidebar-brand span { color: var(--primary); }

        .sidebar-nav { flex: 1; padding: 1rem 0; }

        .nav-label {
            font-size: .7rem;
            font-weight: 600;
            letter-spacing: 1.2px;
            text-transform: uppercase;
            color: #64748b;
            padding: .5rem 1.25rem;
            margin-top: .5rem;
        }

        .sidebar .nav-link {
            color: var(--sidebar-text);
            padding: .65rem 1.25rem;
            border-radius: 0;
            display: flex;
            align-items: center;
            gap: .75rem;
            font-size: .88rem;
            font-weight: 500;
            transition: all .2s;
        }

        .sidebar .nav-link:hover {
            background: var(--sidebar-hover);
            color: #fff;
        }

        .sidebar .nav-link.active {
            background: var(--sidebar-active);
            color: #fff;
        }

        .sidebar .nav-link i { font-size: 1.1rem; width: 20px; text-align: center; }

        .sidebar-footer {
            padding: 1rem 1.25rem;
            border-top: 1px solid #334155;
        }

        /* ── Main Content ── */
        .main-wrapper {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .topbar {
            background: #fff;
            border-bottom: 1px solid #e2e8f0;
            padding: .85rem 1.5rem;
            position: sticky;
            top: 0;
            z-index: 999;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .main-content { padding: 1.75rem; flex: 1; }

        /* ── Cards ── */
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0,0,0,.08);
        }

        .card-header {
            background: transparent;
            border-bottom: 1px solid #f1f5f9;
            padding: 1rem 1.25rem;
            font-weight: 600;
        }

        /* ── Stat Cards ── */
        .stat-card {
            border-radius: 12px;
            padding: 1.25rem;
            color: #fff;
            position: relative;
            overflow: hidden;
        }

        .stat-card .icon {
            font-size: 2.5rem;
            opacity: .25;
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
        }

        /* ── Badge Status ── */
        .badge-menunggu  { background: #fef3c7; color: #92400e; }
        .badge-diproses  { background: #dbeafe; color: #1e40af; }
        .badge-selesai   { background: #d1fae5; color: #065f46; }
        .badge-ditolak   { background: #fee2e2; color: #991b1b; }

        /* ── Notification dot ── */
        .notif-dot {
            width: 8px; height: 8px;
            border-radius: 50%;
            background: #ef4444;
            display: inline-block;
        }

        /* ── Responsive ── */
        .sidebar-toggle { display: none; }

        @media (max-width: 991.98px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.show { transform: translateX(0); }
            .main-wrapper { margin-left: 0; }
            .sidebar-toggle { display: block; }
        }
    </style>

    @stack('styles')
</head>
<body>

<!-- Sidebar -->
<aside class="sidebar" id="sidebar">
    <div class="sidebar-brand">
        <h5><span>Mater</span>fasum</h5>
        <small class="text-muted" style="font-size:.72rem;">Pelaporan Fasilitas Umum</small>
    </div>

    <nav class="sidebar-nav">
        <div class="nav-label">Menu Utama</div>
        <a href="{{ route('user.dashboard') }}" class="nav-link {{ request()->routeIs('user.dashboard') ? 'active' : '' }}">
            <i class="bi bi-speedometer2"></i> Dashboard
        </a>

        <div class="nav-label">Laporan</div>
        <a href="{{ route('user.reports.index') }}" class="nav-link {{ request()->routeIs('user.reports.index') ? 'active' : '' }}">
            <i class="bi bi-list-ul"></i> Laporan Saya
        </a>
        <a href="{{ route('user.reports.create') }}" class="nav-link {{ request()->routeIs('user.reports.create') ? 'active' : '' }}">
            <i class="bi bi-plus-circle"></i> Buat Laporan
        </a>

        <div class="nav-label">Akun</div>
        <a href="{{ route('user.notifications.index') }}" class="nav-link {{ request()->routeIs('user.notifications.*') ? 'active' : '' }}">
            <i class="bi bi-bell"></i> Notifikasi
            @if(auth()->user()->unreadNotifications->count() > 0)
                <span class="badge bg-danger ms-auto">{{ auth()->user()->unreadNotifications->count() }}</span>
            @endif
        </a>
        <a href="{{ route('user.profile.edit') }}" class="nav-link {{ request()->routeIs('user.profile.*') ? 'active' : '' }}">
            <i class="bi bi-person-gear"></i> Edit Profil
        </a>
    </nav>

    <div class="sidebar-footer">
        <div class="d-flex align-items-center gap-2 mb-2">
            <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center text-white"
                style="width:34px;height:34px;font-size:.85rem;font-weight:600;flex-shrink:0;">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>
            <div style="overflow:hidden;">
                <div class="text-white" style="font-size:.82rem;font-weight:600;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                    {{ auth()->user()->name }}
                </div>
                <div class="text-muted" style="font-size:.72rem;">Pengguna</div>
            </div>
        </div>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-sm btn-outline-danger w-100">
                <i class="bi bi-box-arrow-right"></i> Logout
            </button>
        </form>
    </div>
</aside>

<!-- Main Wrapper -->
<div class="main-wrapper">
    <!-- Topbar -->
    <header class="topbar">
        <div class="d-flex align-items-center gap-3">
            <button class="sidebar-toggle btn btn-sm btn-outline-secondary" onclick="toggleSidebar()">
                <i class="bi bi-list fs-5"></i>
            </button>
            <div>
                <h6 class="mb-0 fw-600">@yield('page-title', 'Dashboard')</h6>
                <nav aria-label="breadcrumb" class="d-none d-md-block">
                    <ol class="breadcrumb mb-0" style="font-size:.78rem;">
                        <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">Home</a></li>
                        @yield('breadcrumb')
                    </ol>
                </nav>
            </div>
        </div>

        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('user.notifications.index') }}" class="btn btn-sm btn-light position-relative">
                <i class="bi bi-bell fs-6"></i>
                @if(auth()->user()->unreadNotifications->count() > 0)
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                          style="font-size:.6rem;">
                        {{ auth()->user()->unreadNotifications->count() }}
                    </span>
                @endif
            </a>
            {{-- Dropdown profil di topbar --}}
            <div class="dropdown">
                <button class="btn btn-sm btn-light d-flex align-items-center gap-2 dropdown-toggle"
                        type="button" data-bs-toggle="dropdown">
                    <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center text-white"
                         style="width:26px;height:26px;font-size:.72rem;font-weight:700;">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <span class="d-none d-md-block" style="font-size:.83rem;">{{ auth()->user()->name }}</span>
                </button>
                <ul class="dropdown-menu dropdown-menu-end" style="border-radius:10px;font-size:.88rem;">
                    <li>
                        <a class="dropdown-item" href="{{ route('user.profile.edit') }}">
                            <i class="bi bi-person-gear me-2"></i>Edit Profil
                        </a>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger">
                                <i class="bi bi-box-arrow-right me-2"></i>Logout
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </header>

    <!-- Content -->
    <main class="main-content">
        {{-- Flash Messages --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2 mb-4" role="alert">
                <i class="bi bi-check-circle-fill"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center gap-2 mb-4" role="alert">
                <i class="bi bi-exclamation-circle-fill"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </main>
</div>

<!-- Backdrop (mobile) -->
<div class="d-lg-none" id="backdrop" style="display:none!important;position:fixed;inset:0;background:rgba(0,0,0,.4);z-index:999;"
    onclick="toggleSidebar()"></div>

<script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('vendor/sweetalert2/sweetalert2.all.min.js') }}"></script>
<script>
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        const backdrop = document.getElementById('backdrop');
        sidebar.classList.toggle('show');
        backdrop.style.display = sidebar.classList.contains('show') ? 'block' : 'none';
    }

    document.addEventListener('DOMContentLoaded', function () {
        // Intercept forms with .form-confirm
        document.querySelectorAll('.form-confirm').forEach(function (form) {
            form.addEventListener('submit', function (e) {
                e.preventDefault();
                
                const title = form.getAttribute('data-title') || 'Konfirmasi';
                const text = form.getAttribute('data-text') || 'Apakah Anda yakin?';
                const icon = form.getAttribute('data-icon') || 'question';
                const confirmText = form.getAttribute('data-confirm-text') || 'Ya';
                const cancelText = form.getAttribute('data-cancel-text') || 'Batal';
                
                Swal.fire({
                    title: title,
                    text: text,
                    icon: icon,
                    showCancelButton: true,
                    confirmButtonColor: '#2563eb', // Blue for User Panel
                    cancelButtonColor: '#64748b',
                    confirmButtonText: confirmText,
                    cancelButtonText: cancelText,
                    reverseButtons: true,
                    customClass: {
                        popup: 'rounded-4'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    });
</script>
@stack('scripts')
</body>
</html>