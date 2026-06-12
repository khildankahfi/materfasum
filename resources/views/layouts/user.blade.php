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
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Instrument+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Tailwind CSS (Vite) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --primary: #2563eb;
            --primary-dark: #1d4ed8;
            --sidebar-width: 280px;
            --sidebar-bg: #0f172a; /* Dark Navy Slate */
            --sidebar-text: #94a3b8;
            --sidebar-hover: #1e293b;
            --sidebar-active: #2563eb;
            --bg-base: #f8fafc;
        }

        body {
            font-family: 'Plus Jakarta Sans', 'Instrument Sans', -apple-system, sans-serif;
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
            border-right: 1px solid #1e293b;
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

        .sidebar-brand span { color: var(--primary); }

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
            box-shadow: 0 4px 12px rgba(37,99,235,0.25);
        }

        .sidebar .nav-link i { font-size: 1.15rem; width: 22px; text-align: center; }

        .sidebar-footer {
            padding: 1.25rem;
            border-top: 1px solid rgba(255,255,255,0.05);
            background: rgba(0,0,0,0.1);
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

        /* Glassmorphism & Premium Cards */
        .premium-card {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: 1px solid #e2e8f0;
        }
        .premium-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 20px -8px rgba(0, 0, 0, 0.05), 0 4px 12px -2px rgba(0, 0, 0, 0.03);
            border-color: #cbd5e1;
        }

        /* Hover Lift Utility */
        .hover-lift {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .hover-lift:hover {
            transform: translateY(-1px);
        }

        /* Responsive Layouts */
        .sidebar-toggle { display: none; }

        @media (max-width: 991.98px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.show { transform: translateX(0); }
            .main-wrapper { margin-left: 0; }
            .sidebar-toggle { display: block; }
        }

        /* Status colors */
        .status-badge {
            font-weight: 600;
            font-size: 0.72rem;
            padding: 0.3em 0.75em;
            border-radius: 9999px;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }
        .status-menunggu { background-color: #fef3c7; color: #d97706; }
        .status-diproses { background-color: #e0f2fe; color: #0284c7; }
        .status-selesai { background-color: #d1fae5; color: #059669; }
        .status-ditolak { background-color: #ffe4e6; color: #e11d48; }
    </style>
    @stack('styles')
</head>
<body class="bg-slate-50 text-slate-800 antialiased">

    <!-- Sidebar Menu -->
    <aside class="sidebar animate-fade-in" id="sidebar">
        <div class="sidebar-brand">
            <h5>
                <div class="bg-blue-600 text-white rounded-lg p-1.5 flex items-center justify-center shadow-md shadow-blue-500/20">
                    <i class="bi bi-megaphone-fill"></i>
                </div>
                <span>Mater</span>fasum
            </h5>
            <small class="text-slate-500 font-bold uppercase tracking-wider mt-1.5 block text-[9px]">Pelaporan Gresik</small>
        </div>

        <nav class="sidebar-nav">
            <div class="nav-label">Menu Utama</div>
            <a href="{{ route('user.dashboard') }}" class="nav-link {{ request()->routeIs('user.dashboard') ? 'active' : '' }}">
                <i class="bi bi-grid"></i> Dashboard
            </a>
            <a href="{{ route('user.reports.map') }}" class="nav-link {{ request()->routeIs('user.reports.map') ? 'active' : '' }}">
                <i class="bi bi-map"></i> Peta Keluhan
            </a>

            <div class="nav-label">Laporan Lacak</div>
            <a href="{{ route('user.reports.index') }}" class="nav-link {{ request()->routeIs('user.reports.index') || (request()->routeIs('user.reports.*') && !request()->routeIs('user.reports.create') && !request()->routeIs('user.reports.map')) ? 'active' : '' }}">
                <i class="bi bi-clock-history"></i> Riwayat Laporan
            </a>
            <a href="{{ route('user.reports.create') }}" class="nav-link {{ request()->routeIs('user.reports.create') ? 'active' : '' }}">
                <i class="bi bi-plus-circle"></i> Buat Laporan
            </a>

            <div class="nav-label">Pengaturan Akun</div>
            <a href="{{ route('user.notifications.index') }}" class="nav-link {{ request()->routeIs('user.notifications.*') ? 'active' : '' }}">
                <i class="bi bi-bell"></i> Notifikasi
                @if(auth()->user()->unreadNotifications->count() > 0)
                    <span class="badge bg-rose-500 text-white rounded-pill ms-auto text-[10px] font-bold py-0.5 px-2">
                        {{ auth()->user()->unreadNotifications->count() }}
                    </span>
                @endif
            </a>
            <a href="{{ route('user.profile.edit') }}" class="nav-link {{ request()->routeIs('user.profile.*') ? 'active' : '' }}">
                <i class="bi bi-person-gear"></i> Edit Profil
            </a>
        </nav>

        <div class="sidebar-footer">
            <div class="d-flex align-items-center gap-3 mb-3">
                <div class="rounded-lg bg-blue-600 text-white font-bold h-9 w-9 flex items-center justify-center shadow-md">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div style="overflow:hidden;" class="leading-none">
                    <div class="text-white font-bold text-xs truncate">{{ auth()->user()->name }}</div>
                    <span class="text-slate-500 font-semibold text-[10px] uppercase mt-0.5 block">Warga</span>
                </div>
            </div>
            <form action="{{ route('logout') }}" method="POST" class="m-0">
                @csrf
                <button type="submit" class="btn btn-sm btn-outline-danger border-0 hover:bg-rose-500/10 w-100 py-2 rounded-xl text-xs font-bold flex items-center justify-center gap-2">
                    <i class="bi bi-box-arrow-right"></i> Logout Warga
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Wrapper -->
    <div class="main-wrapper">
        
        <!-- Sticky Topbar -->
        <header class="topbar">
            <div class="d-flex align-items-center gap-3">
                <button class="sidebar-toggle btn btn-sm btn-light border border-slate-200 rounded-lg p-2" onclick="toggleSidebar()">
                    <i class="bi bi-list fs-5"></i>
                </button>
                <div>
                    <h6 class="mb-0 font-extrabold text-slate-800">@yield('title', 'Dashboard')</h6>
                    <nav aria-label="breadcrumb" class="d-none d-md-block">
                        <ol class="breadcrumb mb-0" style="font-size:.75rem;">
                            <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}" class="text-decoration-none text-slate-400">Home</a></li>
                            <li class="breadcrumb-item active text-slate-500 font-semibold" aria-current="page">@yield('title', 'Dashboard')</li>
                        </ol>
                    </nav>
                </div>
            </div>

            <div class="d-flex align-items-center gap-2.5">
                <!-- Notifications icon -->
                <a href="{{ route('user.notifications.index') }}" class="btn btn-sm btn-light border border-slate-200/60 rounded-xl p-2.5 position-relative hover-lift">
                    <i class="bi bi-bell text-slate-500"></i>
                    @if(auth()->user()->unreadNotifications->count() > 0)
                        <span class="position-absolute top-1 start-7 translate-middle badge rounded-pill bg-rose-500 text-[8px] font-bold p-1">
                            {{ auth()->user()->unreadNotifications->count() }}
                        </span>
                    @endif
                </a>

                <!-- Profile summary menu -->
                <div class="dropdown">
                    <button class="btn btn-sm btn-light border border-slate-200/60 rounded-xl d-flex align-items-center gap-2 p-1.5 pr-2.5 dropdown-toggle text-slate-700" type="button" data-bs-toggle="dropdown">
                        <div class="rounded-lg bg-blue-600 text-white font-bold h-7 w-7 flex items-center justify-center shadow-sm text-xs">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                        <span class="d-none d-sm-block text-xs font-bold text-slate-800">{{ auth()->user()->name }}</span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end border-slate-200 shadow-lg mt-2 rounded-xl p-1.5 text-xs">
                        <li>
                            <a class="dropdown-item rounded-lg py-2 px-3 text-slate-700 hover:bg-slate-50" href="{{ route('user.profile.edit') }}">
                                <i class="bi bi-person-gear me-2 text-slate-400"></i>Edit Profil
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item rounded-lg py-2 px-3 text-slate-700 hover:bg-slate-50" href="{{ route('user.notifications.index') }}">
                                <i class="bi bi-bell me-2 text-slate-400"></i>Notifikasi
                            </a>
                        </li>
                        <li><hr class="dropdown-divider border-slate-100 my-1"></li>
                        <li>
                            <form action="{{ route('logout') }}" method="POST" class="m-0">
                                @csrf
                                <button type="submit" class="dropdown-item rounded-lg py-2 px-3 text-rose-600 hover:bg-rose-50 font-bold">
                                    <i class="bi bi-box-arrow-right me-2"></i>Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </header>

        <!-- Dynamic Content Body -->
        <main class="main-content">
            
            {{-- Flash Messages --}}
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

        <!-- Footer -->
        <footer class="bg-white border-t border-slate-200/60 py-4 mt-auto">
            <div class="max-w-7xl mx-auto px-4 flex flex-col sm:flex-row justify-between items-center gap-2 text-[10px] font-semibold text-slate-400">
                <p class="mb-0">&copy; 2026 MaterFasum Citizens. Dibuat untuk melayani warga Gresik.</p>
                <div class="flex items-center gap-3">
                    <a href="#" class="text-slate-400 hover:text-blue-600 text-decoration-none">Panduan</a>
                    <span>|</span>
                    <a href="#" class="text-slate-400 hover:text-blue-600 text-decoration-none">Privasi</a>
                </div>
            </div>
        </footer>

    </div>

    <!-- Backdrop for Mobile Sidebar -->
    <div class="d-lg-none" id="backdrop" style="display:none!important;position:fixed;inset:0;background:rgba(15,23,42,0.4);z-index:999;backdrop-filter:blur(4px);-webkit-backdrop-filter:blur(4px);" onclick="toggleSidebar()"></div>

    <!-- Bootstrap Bundle -->
    <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- SweetAlert2 -->
    <script src="{{ asset('vendor/sweetalert2/sweetalert2.all.min.js') }}"></script>
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const backdrop = document.getElementById('backdrop');
            sidebar.classList.toggle('show');
            backdrop.style.display = sidebar.classList.contains('show') ? 'block' : 'none';
        }

        document.addEventListener('DOMContentLoaded', function () {
            // SweetAlert2 Form Confirmations
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
                        confirmButtonColor: '#2563eb', // Blue
                        cancelButtonColor: '#64748b',
                        confirmButtonText: confirmText,
                        cancelButtonText: cancelText,
                        reverseButtons: true,
                        customClass: {
                            popup: 'rounded-2xl border-0 shadow-xl'
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