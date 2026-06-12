<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin - @yield('title', 'Materfasum')</title>
    <!-- Bootstrap 5 -->
    <link href="{{ asset('vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="{{ asset('vendor/bootstrap-icons/bootstrap-icons.min.css') }}" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Instrument+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Tailwind CSS (Vite) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --primary: #6366f1; /* Premium Indigo */
            --primary-dark: #4f46e5;
            --sidebar-width: 270px;
            --sidebar-bg: #0b0f19; /* Sleek Deep Dark */
            --sidebar-text: #94a3b8;
            --sidebar-hover: #1e293b;
            --sidebar-active: #6366f1;
            --bg-base: #f8fafc;
        }
        
        * {
            font-family: 'Plus Jakarta Sans', 'Instrument Sans', -apple-system, sans-serif;
        }
        
        body {
            background-color: var(--bg-base);
            color: #1e293b;
            min-height: 100vh;
        }

        /* Modern Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f5f9;
        }
        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 3px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        /* ── Sidebar ── */
        .sidebar {
            width: var(--sidebar-width);
            background: var(--sidebar-bg);
            min-height: 100vh;
            position: fixed;
            top: 0; left: 0;
            z-index: 1000;
            transition: transform .3s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            flex-direction: column;
            border-right: 1px solid rgba(255,255,255,0.05);
        }
        
        .sidebar-brand {
            padding: 2rem 1.5rem;
            border-bottom: 1px solid rgba(255,255,255,0.05);
        }
        
        .sidebar-brand h5 {
            color: #fff;
            font-weight: 800;
            font-size: 1.25rem;
            margin: 0;
            letter-spacing: -.5px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .sidebar-brand span {
            color: var(--primary);
        }

        .sidebar-brand .badge-admin {
            background: linear-gradient(135deg, #ec4899, #d946ef); /* Bright Pink-Purple */
            color: #fff;
            font-size: .6rem;
            padding: .25rem .5rem;
            border-radius: 6px;
            font-weight: 700;
            letter-spacing: .5px;
            box-shadow: 0 4px 10px rgba(236,72,153,0.2);
        }
        
        .sidebar-nav {
            flex: 1;
            padding: 1.5rem 1rem;
            overflow-y: auto;
        }
        
        .nav-label {
            font-size: .65rem;
            font-weight: 700;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            color: #475569;
            padding: 0.5rem 0.75rem;
            margin-top: 1rem;
            margin-bottom: 0.25rem;
        }
        
        .sidebar .nav-link {
            color: var(--sidebar-text);
            padding: 0.75rem 1rem;
            border-radius: 12px;
            display: flex;
            align-items: center;
            gap: .75rem;
            font-size: .85rem;
            font-weight: 600;
            text-decoration: none;
            transition: all .2s ease;
            margin-bottom: 0.25rem;
        }
        
        .sidebar .nav-link:hover {
            background: var(--sidebar-hover);
            color: #fff;
        }
        
        .sidebar .nav-link.active {
            background: var(--sidebar-active);
            color: #fff;
            box-shadow: 0 4px 12px rgba(99,102,241,0.25);
        }
        
        .sidebar .nav-link i {
            font-size: 1.15rem;
            width: 22px;
            text-align: center;
        }
        
        .sidebar-footer {
            padding: 1.25rem;
            border-top: 1px solid rgba(255,255,255,0.05);
            background: rgba(0,0,0,0.15);
        }
        
        /* ── Main Content Wrapper ── */
        .main-wrapper {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            transition: margin-left .3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .topbar {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid #e2e8f0;
            padding: .85rem 1.75rem;
            position: sticky;
            top: 0;
            z-index: 999;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        .main-content {
            padding: 2rem;
            flex: 1;
        }
        
        /* Modern Cards */
        .card {
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            box-shadow: 0 1px 3px rgba(0,0,0,.02);
            background: #fff;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .card-header {
            background: transparent;
            border-bottom: 1px solid #f1f5f9;
            padding: 1.25rem 1.5rem;
            font-weight: 700;
            color: #1e293b;
        }
        
        .stat-card {
            border-radius: 16px;
            padding: 1.5rem;
            color: #fff;
            position: relative;
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border: none;
        }
        
        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px -5px rgba(0,0,0,0.1);
        }
        
        .stat-card .icon {
            font-size: 3rem;
            opacity: .15;
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            transition: transform 0.3s ease;
        }
        
        .stat-card:hover .icon {
            transform: translateY(-50%) scale(1.1);
        }
        
        /* Badge Status Colors */
        .badge-menunggu { background-color: #fef3c7; color: #d97706; }
        .badge-diproses { background-color: #e0f2fe; color: #0284c7; }
        .badge-selesai  { background-color: #d1fae5; color: #059669; }
        .badge-ditolak  { background-color: #ffe4e6; color: #e11d48; }
        
        .badge {
            font-weight: 600;
            padding: 0.35em 0.8em;
            border-radius: 9999px;
            font-size: 0.72rem;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }
        
        .table > :not(caption) > * > * {
            padding: 1rem 1.25rem;
            vertical-align: middle;
        }
        
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

<aside class="sidebar" id="sidebar">
    <div class="sidebar-brand">
        <div class="d-flex align-items-center justify-content-between">
            <h5 class="mb-0">
                <div class="bg-indigo-600 text-white rounded-lg p-1.5 flex items-center justify-center shadow-md shadow-indigo-500/20" style="width:34px; height:34px;">
                    <i class="bi bi-shield-fill-check"></i>
                </div>
                <span class="ms-2">Mater</span><span class="text-slate-400">fasum</span>
            </h5>
            <span class="badge-admin">ADMIN</span>
        </div>
        <small class="text-slate-500 font-bold uppercase tracking-wider mt-2.5 block text-[9px]">Panel Administrator</small>
    </div>

    <nav class="sidebar-nav">
        <div class="nav-label">Utama</div>
        <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="bi bi-grid-1x2"></i> Dashboard
        </a>

        <div class="nav-label">Manajemen</div>
        <a href="{{ route('admin.reports.index') }}" class="nav-link {{ request()->routeIs('admin.reports.*') && !request()->has('status') ? 'active' : '' }}">
            <i class="bi bi-file-earmark-text"></i> Semua Laporan
        </a>
        <a href="{{ route('admin.reports.index') }}?status=menunggu" class="nav-link {{ request()->routeIs('admin.reports.*') && request('status') === 'menunggu' ? 'active' : '' }}">
            <i class="bi bi-hourglass-split"></i> Menunggu Validasi
            @php $pending = \App\Models\Report::where('status','menunggu')->count(); @endphp
            @if($pending > 0)
                <span class="badge bg-amber-500 text-white rounded-pill ms-auto text-[10px] font-bold py-0.5 px-2">{{ $pending }}</span>
            @endif
        </a>
        <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
            <i class="bi bi-people"></i> Manajemen User
        </a>
    </nav>

    <div class="sidebar-footer">
        <div class="d-flex align-items-center gap-3 mb-3">
            <div class="rounded-lg bg-indigo-600 text-white font-bold h-9 w-9 flex items-center justify-center shadow-md text-sm">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>
            <div style="overflow:hidden;" class="leading-none">
                <div class="text-white font-bold text-xs truncate">{{ auth()->user()->name }}</div>
                <span class="text-slate-500 font-semibold text-[10px] uppercase mt-0.5 block">Administrator</span>
            </div>
        </div>
        <form action="{{ route('logout') }}" method="POST" class="m-0">
            @csrf
            <button type="submit" class="btn btn-sm btn-outline-danger border-0 hover:bg-rose-500/10 w-100 py-2 rounded-xl text-xs font-bold flex items-center justify-center gap-2">
                <i class="bi bi-box-arrow-right"></i> Logout Admin
            </button>
        </form>
    </div>
</aside>

<div class="main-wrapper">
    <header class="topbar">
        <div class="d-flex align-items-center gap-3">
            <button class="sidebar-toggle btn btn-sm btn-light border border-slate-200 rounded-lg p-2" onclick="toggleSidebar()">
                <i class="bi bi-list fs-5"></i>
            </button>
            <div>
                <h6 class="mb-0 font-extrabold text-slate-800">@yield('page-title', 'Dashboard')</h6>
                <nav aria-label="breadcrumb" class="d-none d-md-block">
                    <ol class="breadcrumb mb-0" style="font-size:.75rem;">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none text-slate-400">Admin</a></li>
                        @yield('breadcrumb')
                    </ol>
                </nav>
            </div>
        </div>
        <span class="badge rounded-pill text-white" style="background:var(--primary);font-size:.75rem;box-shadow: 0 4px 10px rgba(99,102,241,0.2);">
            <i class="bi bi-shield-check me-1"></i> Administrator
        </span>
    </header>

    <main class="main-content">
        @if(session('success'))
            <div class="alert alert-success border-0 shadow-sm rounded-xl d-flex align-items-center gap-3 py-3 px-4 mb-4 bg-emerald-50 text-emerald-800" role="alert">
                <div class="bg-emerald-500 text-white rounded-full p-1 h-7 w-7 flex items-center justify-center flex-shrink-0">
                    <i class="bi bi-check-lg fs-5"></i>
                </div>
                <div class="flex-grow-1 text-xs font-semibold">
                    {{ session('success') }}
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger border-0 shadow-sm rounded-xl d-flex align-items-center gap-3 py-3 px-4 mb-4 bg-rose-50 text-rose-800" role="alert">
                <div class="bg-rose-500 text-white rounded-full p-1 h-7 w-7 flex items-center justify-center flex-shrink-0">
                    <i class="bi bi-exclamation-triangle fs-5"></i>
                </div>
                <div class="flex-grow-1 text-xs font-semibold">
                    {{ session('error') }}
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @yield('content')
    </main>
</div>

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
                    confirmButtonColor: '#7c3aed', // Purple for Admin Panel
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