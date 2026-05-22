<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin - @yield('title', 'Materfasum')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #7c3aed; --primary-dark: #6d28d9;
            --sidebar-width: 260px; --sidebar-bg: #0f172a;
            --sidebar-text: #94a3b8; --sidebar-hover: #1e293b; --sidebar-active: #7c3aed;
        }
        * { font-family: 'Inter', sans-serif; }
        body { background: #f8fafc; min-height: 100vh; }
        .sidebar { width: var(--sidebar-width); background: var(--sidebar-bg); min-height: 100vh; position: fixed; top: 0; left: 0; z-index: 1000; transition: transform .3s ease; display: flex; flex-direction: column; }
        .sidebar-brand { padding: 1.5rem 1.25rem; border-bottom: 1px solid #1e293b; }
        .sidebar-brand h5 { color: #fff; font-weight: 700; font-size: 1.1rem; margin: 0; }
        .sidebar-brand .badge-admin { background: var(--primary); color: #fff; font-size: .65rem; padding: .2rem .5rem; border-radius: 4px; font-weight: 600; letter-spacing: .5px; }
        .sidebar-nav { flex: 1; padding: 1rem 0; }
        .nav-label { font-size: .7rem; font-weight: 600; letter-spacing: 1.2px; text-transform: uppercase; color: #475569; padding: .5rem 1.25rem; margin-top: .5rem; }
        .sidebar .nav-link { color: var(--sidebar-text); padding: .65rem 1.25rem; display: flex; align-items: center; gap: .75rem; font-size: .88rem; font-weight: 500; transition: all .2s; }
        .sidebar .nav-link:hover { background: var(--sidebar-hover); color: #fff; }
        .sidebar .nav-link.active { background: var(--sidebar-active); color: #fff; }
        .sidebar .nav-link i { font-size: 1.1rem; width: 20px; text-align: center; }
        .sidebar-footer { padding: 1rem 1.25rem; border-top: 1px solid #1e293b; }
        .main-wrapper { margin-left: var(--sidebar-width); min-height: 100vh; display: flex; flex-direction: column; }
        .topbar { background: #fff; border-bottom: 1px solid #e2e8f0; padding: .85rem 1.5rem; position: sticky; top: 0; z-index: 999; display: flex; align-items: center; justify-content: space-between; }
        .main-content { padding: 1.75rem; flex: 1; }
        .card { border: none; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,.08); }
        .card-header { background: transparent; border-bottom: 1px solid #f1f5f9; padding: 1rem 1.25rem; font-weight: 600; }
        .stat-card { border-radius: 12px; padding: 1.25rem; color: #fff; position: relative; overflow: hidden; }
        .stat-card .icon { font-size: 2.5rem; opacity: .2; position: absolute; right: 1rem; top: 50%; transform: translateY(-50%); }
        .badge-menunggu { background: #fef3c7; color: #92400e; }
        .badge-diproses { background: #dbeafe; color: #1e40af; }
        .badge-selesai  { background: #d1fae5; color: #065f46; }
        .badge-ditolak  { background: #fee2e2; color: #991b1b; }
        .table > :not(caption) > * > * { padding: .75rem 1rem; vertical-align: middle; }
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
            <h5 class="mb-0">Materfasum</h5>
            <span class="badge-admin">ADMIN</span>
        </div>
        <small class="text-muted" style="font-size:.72rem;">Panel Administrator</small>
    </div>

    <nav class="sidebar-nav">
        <div class="nav-label">Utama</div>
        <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="bi bi-grid-1x2"></i> Dashboard
        </a>

        <div class="nav-label">Manajemen</div>
        <a href="{{ route('admin.reports.index') }}" class="nav-link {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
            <i class="bi bi-file-earmark-text"></i> Semua Laporan
        </a>
        <a href="{{ route('admin.reports.index') }}?status=menunggu" class="nav-link">
            <i class="bi bi-hourglass-split"></i> Menunggu Validasi
            @php $pending = \App\Models\Report::where('status','menunggu')->count(); @endphp
            @if($pending > 0)
                <span class="badge bg-warning text-dark ms-auto">{{ $pending }}</span>
            @endif
        </a>
        <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
            <i class="bi bi-people"></i> Manajemen User
        </a>
    </nav>

    <div class="sidebar-footer">
        <div class="d-flex align-items-center gap-2 mb-2">
            <div class="rounded-circle d-flex align-items-center justify-content-center text-white"
                 style="width:34px;height:34px;font-size:.85rem;font-weight:600;flex-shrink:0;background:var(--primary);">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>
            <div style="overflow:hidden;">
                <div class="text-white" style="font-size:.82rem;font-weight:600;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                    {{ auth()->user()->name }}
                </div>
                <div class="text-muted" style="font-size:.72rem;">Administrator</div>
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

<div class="main-wrapper">
    <header class="topbar">
        <div class="d-flex align-items-center gap-3">
            <button class="sidebar-toggle btn btn-sm btn-outline-secondary" onclick="toggleSidebar()">
                <i class="bi bi-list fs-5"></i>
            </button>
            <div>
                <h6 class="mb-0 fw-semibold">@yield('page-title', 'Dashboard')</h6>
                <nav aria-label="breadcrumb" class="d-none d-md-block">
                    <ol class="breadcrumb mb-0" style="font-size:.78rem;">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Admin</a></li>
                        @yield('breadcrumb')
                    </ol>
                </nav>
            </div>
        </div>
        <span class="badge rounded-pill text-white" style="background:var(--primary);font-size:.75rem;">
            <i class="bi bi-shield-check me-1"></i> Administrator
        </span>
    </header>

    <main class="main-content">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2 mb-4">
                <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center gap-2 mb-4">
                <i class="bi bi-exclamation-circle-fill"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @yield('content')
    </main>
</div>

<div class="d-lg-none" id="backdrop" style="display:none!important;position:fixed;inset:0;background:rgba(0,0,0,.4);z-index:999;"
     onclick="toggleSidebar()"></div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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