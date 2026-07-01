<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Materfasum') - MaterFasum Citizens</title>

    <!-- Bootstrap 5 -->
    <link href="{{ asset('vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="{{ asset('vendor/bootstrap-icons/bootstrap-icons.min.css') }}" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Tailwind CSS (Vite) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --primary: #2563eb;
            --nav-height: 64px;
        }

        * { box-sizing: border-box; }

        body {
            font-family: 'Inter', -apple-system, sans-serif;
            background-color: #f9fafb;
            color: #1e293b;
            min-height: 100vh;
        }

        /* ── Top Navbar ── */
        .top-navbar {
            height: var(--nav-height);
            background: #ffffff;
            border-bottom: 1px solid #e5e7eb;
            position: sticky;
            top: 0;
            z-index: 1000;
            display: flex;
            align-items: center;
        }

        .navbar-inner {
            max-width: 1200px;
            margin: 0 auto;
            width: 100%;
            padding: 0 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1.5rem;
        }

        /* Brand */
        .nav-brand {
            font-size: 1.1rem;
            font-weight: 700;
            color: #2563eb;
            text-decoration: none;
            white-space: nowrap;
            letter-spacing: -.3px;
        }

        /* Nav links */
        .nav-links {
            display: flex;
            align-items: center;
            gap: 0.25rem;
            list-style: none;
            margin: 0;
            padding: 0;
        }

        .nav-links a {
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 0.45rem 0.9rem;
            border-radius: 8px;
            font-size: 0.875rem;
            font-weight: 500;
            color: #6b7280;
            text-decoration: none;
            transition: all 0.15s ease;
            white-space: nowrap;
        }

        .nav-links a:hover {
            color: #1f2937;
            background: #f3f4f6;
        }

        .nav-links a.active {
            color: #2563eb;
            font-weight: 600;
            background: transparent;
        }

        /* User section */
        .nav-user {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            flex-shrink: 0;
        }

        .nav-avatar {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: #d1d5db;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.8rem;
            font-weight: 700;
            color: #374151;
            cursor: pointer;
        }

        .nav-user-label {
            font-size: 0.875rem;
            font-weight: 500;
            color: #374151;
        }

        /* Main content area */
        .page-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem 1.5rem;
            min-height: calc(100vh - var(--nav-height));
        }

        /* Status badges */
        .status-badge {
            font-weight: 600;
            font-size: 0.72rem;
            padding: 0.25em 0.65em;
            border-radius: 9999px;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }
        .status-menunggu { background-color: #fef3c7; color: #d97706; }
        .status-diproses  { background-color: #dbeafe; color: #2563eb; }
        .status-selesai   { background-color: #d1fae5; color: #059669; }
        .status-ditolak   { background-color: #fee2e2; color: #dc2626; }

        /* Hover lift */
        .hover-lift {
            transition: transform 0.15s ease, box-shadow 0.15s ease;
        }
        .hover-lift:hover {
            transform: translateY(-1px);
        }

        /* Premium card */
        .card {
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            background: #fff;
        }
        .card:hover {
            border-color: #d1d5db;
        }

        /* Mobile hamburger */
        .nav-hamburger {
            display: none;
            background: none;
            border: none;
            padding: 6px;
            cursor: pointer;
            color: #6b7280;
        }

        @media (max-width: 768px) {
            .nav-links {
                display: none;
                flex-direction: column;
                position: absolute;
                top: var(--nav-height);
                left: 0; right: 0;
                background: #fff;
                border-bottom: 1px solid #e5e7eb;
                padding: 0.75rem 1.5rem 1rem;
                gap: 0.25rem;
            }
            .nav-links.open { display: flex; }
            .nav-hamburger { display: flex; }
            .nav-user-label { display: none; }
        }
    </style>
    @stack('styles')
</head>
<body>

    <!-- ── Top Navbar ── -->
    <nav class="top-navbar">
        <div class="navbar-inner">

            <!-- Brand -->
            <a href="{{ route('user.dashboard') }}" class="nav-brand">MaterFasum Citizens</a>

            <!-- Nav Links -->
            <ul class="nav-links" id="navLinks">
                <li>
                    <a href="{{ route('user.dashboard') }}" class="{{ request()->routeIs('user.dashboard') ? 'active' : '' }}">
                        Beranda
                    </a>
                </li>
                <li>
                    <a href="{{ route('user.reports.create') }}" class="{{ request()->routeIs('user.reports.create') ? 'active' : '' }}">
                        Buat Laporan
                    </a>
                </li>
                <li>
                    <a href="{{ route('user.reports.map') }}" class="{{ request()->routeIs('user.reports.map') ? 'active' : '' }}">
                        Peta Keluhan
                    </a>
                </li>
                <li>
                    <a href="{{ route('user.reports.index') }}" class="{{ (request()->routeIs('user.reports.index') || (request()->routeIs('user.reports.*') && !request()->routeIs('user.reports.create') && !request()->routeIs('user.reports.map'))) ? 'active' : '' }}">
                        Riwayat
                    </a>
                </li>
            </ul>

            <!-- Right side: notifications + user -->
            <div class="nav-user">
                <!-- Bell notif -->
                <a href="{{ route('user.notifications.index') }}" class="position-relative" style="color:#6b7280;text-decoration:none;">
                    <i class="bi bi-bell" style="font-size:1.1rem;"></i>
                    @if(auth()->user()->unreadNotifications->count() > 0)
                        <span class="position-absolute" style="top:-4px;right:-4px;width:8px;height:8px;background:#ef4444;border-radius:50%;border:2px solid #fff;"></span>
                    @endif
                </a>

                <!-- User dropdown -->
                <div class="dropdown">
                    <button class="d-flex align-items-center gap-2 bg-transparent border-0 p-0 dropdown-toggle"
                            data-bs-toggle="dropdown" style="cursor:pointer;">
                        <div class="nav-avatar">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                        <span class="nav-user-label">{{ auth()->user()->name }}</span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end border shadow-sm mt-2 rounded-xl p-1" style="min-width:180px;font-size:.85rem;">
                        <li>
                            <a class="dropdown-item rounded-lg py-2 px-3 text-slate-700" href="{{ route('user.profile.edit') }}">
                                <i class="bi bi-person-gear me-2 text-slate-400"></i>Edit Profil
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item rounded-lg py-2 px-3 text-slate-700" href="{{ route('user.notifications.index') }}">
                                <i class="bi bi-bell me-2 text-slate-400"></i>Notifikasi
                                @if(auth()->user()->unreadNotifications->count() > 0)
                                    <span class="badge bg-danger ms-1 rounded-pill" style="font-size:.65rem;">
                                        {{ auth()->user()->unreadNotifications->count() }}
                                    </span>
                                @endif
                            </a>
                        </li>
                        <li><hr class="dropdown-divider my-1"></li>
                        <li>
                            <form action="{{ route('logout') }}" method="POST" class="m-0">
                                @csrf
                                <button type="submit" class="dropdown-item rounded-lg py-2 px-3 text-danger fw-semibold">
                                    <i class="bi bi-box-arrow-right me-2"></i>Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>

                <!-- Mobile hamburger -->
                <button class="nav-hamburger" onclick="document.getElementById('navLinks').classList.toggle('open')">
                    <i class="bi bi-list" style="font-size:1.3rem;"></i>
                </button>
            </div>
        </div>
    </nav>

    <!-- ── Page Content ── -->
    <main class="page-content">

        {{-- Flash Messages --}}
        @if(session('success'))
            <div class="alert d-flex align-items-center gap-3 py-3 px-4 mb-4 rounded-xl border-0"
                 style="background:#f0fdf4;color:#15803d;" role="alert">
                <i class="bi bi-check-circle-fill"></i>
                <div class="flex-grow-1 fw-semibold" style="font-size:.875rem;">{{ session('success') }}</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert d-flex align-items-center gap-3 py-3 px-4 mb-4 rounded-xl border-0"
                 style="background:#fef2f2;color:#dc2626;" role="alert">
                <i class="bi bi-exclamation-circle-fill"></i>
                <div class="flex-grow-1 fw-semibold" style="font-size:.875rem;">{{ session('error') }}</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Bootstrap Bundle -->
    <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- SweetAlert2 -->
    <script src="{{ asset('vendor/sweetalert2/sweetalert2.all.min.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.form-confirm').forEach(function (form) {
                form.addEventListener('submit', function (e) {
                    e.preventDefault();
                    const title = form.getAttribute('data-title') || 'Konfirmasi';
                    const text = form.getAttribute('data-text') || 'Apakah Anda yakin?';
                    const icon = form.getAttribute('data-icon') || 'question';
                    const confirmText = form.getAttribute('data-confirm-text') || 'Ya';
                    const cancelText = form.getAttribute('data-cancel-text') || 'Batal';
                    Swal.fire({
                        title, text, icon,
                        showCancelButton: true,
                        confirmButtonColor: '#2563eb',
                        cancelButtonColor: '#6b7280',
                        confirmButtonText: confirmText,
                        cancelButtonText: cancelText,
                        reverseButtons: true,
                        customClass: { popup: 'rounded-2xl border-0 shadow-xl' }
                    }).then((result) => {
                        if (result.isConfirmed) form.submit();
                    });
                });
            });
        });
    </script>
    @auth
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (window.Echo) {
                const userId = "{{ auth()->id() }}";
                
                window.Echo.private(`App.Models.User.${userId}`)
                    .notification((notification) => {
                        console.log('Real-time notification received:', notification);
                        
                        // Show sweetalert2 toast
                        Swal.fire({
                            title: '🔔 Update Laporan',
                            text: notification.message,
                            icon: 'info',
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 5000,
                            timerProgressBar: true,
                            didOpen: (toast) => {
                                toast.addEventListener('mouseenter', Swal.stopTimer)
                                toast.addEventListener('mouseleave', Swal.resumeTimer)
                            }
                        });

                        // Update navbar bell icon (add red dot)
                        const bellLink = document.querySelector('a.position-relative[href*="notifications"]');
                        if (bellLink) {
                            let badge = bellLink.querySelector('span.position-absolute');
                            if (!badge) {
                                badge = document.createElement('span');
                                badge.className = 'position-absolute';
                                badge.style.cssText = 'top:-4px;right:-4px;width:8px;height:8px;background:#ef4444;border-radius:50%;border:2px solid #fff;';
                                bellLink.appendChild(badge);
                            }
                        }

                        // Update dropdown menu unread notifications badge
                        const dropdownBadge = document.querySelector('.dropdown-menu .badge');
                        if (dropdownBadge) {
                            const count = parseInt(dropdownBadge.textContent.trim()) || 0;
                            dropdownBadge.textContent = count + 1;
                        } else {
                            const dropdownLink = document.querySelector('.dropdown-menu a[href*="notifications"]');
                            if (dropdownLink) {
                                const badgeSpan = document.createElement('span');
                                badgeSpan.className = 'badge bg-danger ms-1 rounded-pill';
                                badgeSpan.style.fontSize = '.65rem';
                                badgeSpan.textContent = '1';
                                dropdownLink.appendChild(badgeSpan);
                            }
                        }

                        // Prepend to notifications list if on the page
                        const container = document.querySelector('.divide-y');
                        if (container) {
                            // Remove empty placeholder if any
                            const emptyState = document.querySelector('.text-center.py-12');
                            if (emptyState) {
                                emptyState.remove();
                                // Create divide-y structure if deleted
                                const listContainer = document.createElement('div');
                                listContainer.className = 'divide-y divide-slate-100';
                                document.querySelector('.bg-white.rounded-2xl').appendChild(listContainer);
                            }

                            // Define status colors & icons
                            const status = notification.status || 'menunggu';
                            const colors = {
                                selesai: 'bg-emerald-50 text-emerald-600 border-emerald-100',
                                diproses: 'bg-blue-50 text-blue-600 border-blue-100',
                                ditolak: 'bg-rose-50 text-rose-600 border-rose-100',
                                menunggu: 'bg-amber-50 text-amber-600 border-amber-100'
                            };
                            const icons = {
                                selesai: 'bi-check-lg',
                                diproses: 'bi-gear-wide-connected',
                                ditolak: 'bi-x-lg',
                                menunggu: 'bi-hourglass-split'
                            };

                            const badgeStyle = colors[status] || 'bg-slate-50 text-slate-600 border-slate-100';
                            const iconStyle = icons[status] || 'bi-bell';

                            const itemHtml = `
                                <div class="p-4 flex gap-4 transition-colors duration-150 bg-amber-50/10">
                                    <div class="rounded-xl h-11 w-11 flex items-center justify-center flex-shrink-0 border ${badgeStyle} shadow-sm">
                                        <i class="bi ${iconStyle} fs-5"></i>
                                    </div>
                                    <div class="flex-grow min-w-0">
                                        <div class="flex items-start justify-between gap-3 flex-wrap sm:flex-nowrap">
                                            <div class="space-y-1">
                                                <div class="font-bold text-sm text-slate-800 leading-snug">
                                                    \${notification.message}
                                                    <span class="bg-rose-500 text-white text-[8px] font-extrabold uppercase px-2 py-0.5 rounded-full ms-1.5 align-middle tracking-wider shadow-sm shadow-rose-500/10">Baru</span>
                                                </div>
                                                \${notification.note ? `
                                                    <div class="bg-slate-50 p-2.5 rounded-xl border border-slate-100 text-xs text-slate-650 font-medium leading-normal flex items-start gap-1.5 max-w-2xl">
                                                        <i class="bi bi-chat-left-quote text-slate-400 mt-0.5 flex-shrink-0"></i>
                                                        <span>Catatan petugas: "\${notification.note}"</span>
                                                    </div>
                                                ` : ''}
                                                <div class="text-slate-400 text-[10px] font-bold uppercase tracking-wider flex items-center gap-1.5 pt-0.5">
                                                    <span><i class="bi bi-clock me-1"></i>baru saja</span>
                                                </div>
                                            </div>
                                            <div class="flex items-center gap-2 flex-shrink-0">
                                                \${notification.report_id ? `
                                                    <a href="/user/reports/\${notification.report_id}" class="btn btn-sm btn-light border border-slate-200 text-slate-600 rounded-lg text-[10px] font-bold px-3 py-2 flex items-center gap-1 hover-lift text-decoration-none">
                                                        <i class="bi bi-eye"></i> Detail
                                                    </a>
                                                ` : ''}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            `;

                            const currentList = document.querySelector('.divide-y');
                            if (currentList) {
                                currentList.insertAdjacentHTML('afterbegin', itemHtml);
                            }
                        }
                    });
            }
        });
    </script>
    @endauth

    @stack('scripts')
</body>
</html>