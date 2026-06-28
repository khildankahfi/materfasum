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
            --primary: #0284c7; /* Sky blue matching mockup */
            --primary-dark: #0369a1;
            --sidebar-width: 270px;
            --sidebar-bg: #0082c8; /* Mockup solid blue */
            --sidebar-text: #e0f2fe;
            --sidebar-hover: rgba(0, 0, 0, 0.08);
            --sidebar-active: rgba(0, 0, 0, 0.15);
            --bg-base: #f0f5fa; /* Mockup light gray-blue background */
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
        }
        
        .sidebar-brand {
            padding: 1.75rem 1.5rem;
            background: rgba(0, 0, 0, 0.08);
        }
        
        .sidebar-brand h4 {
            color: #fff;
            font-weight: 800;
            font-size: 1.35rem;
            margin: 0;
            letter-spacing: -.5px;
        }
        
        .sidebar-brand small {
            color: #e0f2fe;
            opacity: 0.75;
            font-size: 0.75rem;
        }
        
        .sidebar-nav {
            flex: 1;
            padding: 1.5rem 0;
            overflow-y: auto;
        }
        
        .sidebar .nav-link {
            color: var(--sidebar-text);
            padding: 0.85rem 1.5rem;
            display: flex;
            align-items: center;
            gap: .75rem;
            font-size: .88rem;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s ease;
            border-left: 4px solid transparent;
        }
        
        .sidebar .nav-link:hover {
            background: var(--sidebar-hover);
            color: #fff;
        }
        
        .sidebar .nav-link.active {
            background: var(--sidebar-active);
            color: #fff;
            border-left: 4px solid #fff;
        }
        
        .sidebar .nav-link i {
            font-size: 1.15rem;
            width: 22px;
            text-align: center;
        }
        
        .sidebar-footer {
            padding: 1.25rem 1.5rem;
            background: rgba(0,0,0,0.15);
            margin-top: auto;
            border-top: 1px solid rgba(255,255,255,0.08);
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
            background: #ffffff;
            border-bottom: 1px solid #e2e8f0;
            padding: 1rem 2rem;
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
            border: 1px solid #c7e2f5; /* Light blue border matching mockup */
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
            background: #fff;
            border: 1px solid #c7e2f5;
            border-radius: 16px;
            padding: 1.25rem 1.5rem;
            position: relative;
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px -5px rgba(0,0,0,0.05);
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
        
        /* Overrides to prevent Bootstrap's .badge from forcing text color to white */
        .badge.text-indigo-700 { color: #4338ca !important; }
        .badge.text-blue-700 { color: #1d4ed8 !important; }
        .badge.text-slate-700 { color: #334155 !important; }
        .badge.text-slate-600 { color: #475569 !important; }
        .badge.text-blue-600 { color: #2563eb !important; }
        
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
        <h4 class="mb-0">MaterFasum</h4>
        <small>Admin Panel v1.0</small>
    </div>

    <nav class="sidebar-nav">
        <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="bi bi-grid-1x2"></i> Dashboard Utama
        </a>
        <a href="{{ route('admin.reports.index') }}" class="nav-link {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
            <i class="bi bi-file-earmark-text"></i> Kelola Laporan
        </a>
        <a href="{{ route('admin.reports.map') }}" class="nav-link {{ request()->routeIs('admin.reports.map') ? 'active' : '' }}" style="padding-left:2.5rem; font-size:.82rem; opacity:.85;">
            <i class="bi bi-geo-alt"></i> Peta Wilayah GIS
        </a>
        <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
            <i class="bi bi-people"></i> Validasi Akun Warga
        </a>

        {{-- Divider --}}
        <div style="padding:.5rem 1.5rem .25rem; font-size:.65rem; font-weight:800; text-transform:uppercase; letter-spacing:.1em; color:rgba(255,255,255,.4);">
            Master Data
        </div>

        <a href="{{ route('admin.categories.index') }}" class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
            <i class="bi bi-tags"></i> Kategori Laporan
        </a>
        <a href="{{ route('admin.departments.index') }}" class="nav-link {{ request()->routeIs('admin.departments.*') ? 'active' : '' }}">
            <i class="bi bi-building"></i> Dinas Pelaksana
        </a>
    </nav>

    <div class="sidebar-footer">
        <div class="d-flex align-items-center gap-2.5 mb-3">
            <div class="rounded-lg bg-white/20 text-white font-bold h-8 w-8 d-flex align-items-center justify-content-center text-xs">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>
            <div class="leading-none min-w-0 flex-grow" style="overflow:hidden;">
                <div class="text-white font-bold text-xs truncate">{{ auth()->user()->name }}</div>
                <span class="text-sky-200/60 font-semibold text-[9px] uppercase mt-0.5 block">Administrator</span>
            </div>
        </div>
        <form action="{{ route('logout') }}" method="POST" class="m-0">
            @csrf
            <button type="submit" class="btn btn-sm text-white bg-white/10 hover:bg-rose-600 border-0 w-100 py-2 rounded-lg text-[11px] font-bold flex items-center justify-center gap-1.5 transition-all">
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
                <h5 class="mb-0 text-indigo-950 font-extrabold text-base">
                    @if(request()->routeIs('admin.dashboard'))
                        Pusat Kendali Operasional Kota Surabaya
                    @else
                        @yield('page-title', 'Dashboard')
                    @endif
                </h5>
                <small class="text-slate-400 font-bold text-[10px] uppercase tracking-wider mt-0.5 block">
                    @if(request()->routeIs('admin.dashboard'))
                        Status Koneksi: Aktif & Terintegrasi dengan Lapangan
                    @else
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0" style="font-size:.72rem;">
                                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none text-slate-400">Admin</a></li>
                                @yield('breadcrumb')
                            </ol>
                        </nav>
                    @endif
                </small>
            </div>
        </div>
        
        <div class="d-flex align-items-center gap-2">
            <span class="h-2.5 w-2.5 rounded-full bg-blue-600"></span>
            <span class="font-bold text-slate-800 text-sm">Admin Utama</span>
        </div>
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