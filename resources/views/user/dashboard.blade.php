@extends('layouts.user')

@section('title', 'Dashboard Warga')

@section('content')

{{-- Greeting & Role Badge --}}
<div class="d-flex flex-column sm:flex-row align-items-start sm:align-items-center justify-content-between gap-3 mb-4">
    <div>
        <h4 class="font-extrabold text-slate-800 mb-1">Halo, {{ auth()->user()->name }}! 👋</h4>
        <p class="text-slate-500 font-semibold text-xs mb-0">Selamat datang kembali. Mari bersama-sama ikut memantau dan memelihara fasilitas umum di Gresik.</p>
    </div>
    <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-blue-50 border border-blue-100 rounded-full text-blue-600 text-[10px] font-bold uppercase tracking-wider">
        <span class="h-1.5 w-1.5 rounded-full bg-blue-600"></span> Warga Aktif
    </span>
</div>

{{-- Workflow Banner / Guide --}}
<div class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-3xl p-4 sm:p-4.5 mb-4 shadow-md shadow-blue-500/10 position-relative overflow-hidden">
    <div class="absolute -right-10 -bottom-10 text-white/5 font-bold text-9xl select-none pointer-events-none">
        <i class="bi bi-info-circle-fill"></i>
    </div>
    
    <div class="position-relative z-1">
        <h6 class="font-bold text-sm mb-2.5 flex items-center gap-2"><i class="bi bi-shield-shaded"></i> Alur Kerja Penanganan Aduan Anda:</h6>
        
        <div class="row g-3 text-center text-sm">
            <div class="col-6 col-md-3">
                <div class="p-2.5 bg-white/10 rounded-2xl h-100">
                    <div class="font-extrabold text-sm mb-0.5"><i class="bi bi-pencil-square me-1"></i> 1. Laporkan</div>
                    <span class="text-[10px] opacity-75 font-medium leading-tight block">Kirim aduan berupa foto, deskripsi, & titik peta GIS</span>
                </div>
            </div>
            
            <div class="col-6 col-md-3">
                <div class="p-2.5 bg-white/10 rounded-2xl h-100">
                    <div class="font-extrabold text-sm mb-0.5"><i class="bi bi-shield-check me-1"></i> 2. Validasi</div>
                    <span class="text-[10px] opacity-75 font-medium leading-tight block">Admin memvalidasi & mengarahkan ke dinas terkait</span>
                </div>
            </div>

            <div class="col-6 col-md-3">
                <div class="p-2.5 bg-white/10 rounded-2xl h-100">
                    <div class="font-extrabold text-sm mb-0.5"><i class="bi bi-tools me-1"></i> 3. Diperbaiki</div>
                    <span class="text-[10px] opacity-75 font-medium leading-tight block">Petugas dinas diterjunkan langsung ke lokasi keluhan</span>
                </div>
            </div>

            <div class="col-6 col-md-3">
                <div class="p-2.5 bg-white/10 rounded-2xl h-100">
                    <div class="font-extrabold text-sm mb-0.5"><i class="bi bi-check-circle me-1"></i> 4. Selesai</div>
                    <span class="text-[10px] opacity-75 font-medium leading-tight block">Aduan tuntas diperbaiki & Anda menerima notifikasi</span>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Stats Row --}}
<div class="row g-3 mb-4">
    <!-- Stat 1: Total -->
    <div class="col-6 col-lg-3 animate-fade-in">
        <div class="bg-gradient-to-br from-blue-600 to-blue-700 text-white rounded-2xl p-4 relative overflow-hidden shadow-sm shadow-blue-500/10 hover:scale-[1.02] transition-transform duration-300">
            <div class="font-extrabold text-3xl leading-none mb-1">{{ $stats['total'] }}</div>
            <div class="text-[10px] uppercase font-bold tracking-wider opacity-85">Total Laporan</div>
            <div class="absolute -right-3 -bottom-3 text-white/10 font-bold text-6xl leading-none select-none pointer-events-none">
                <i class="bi bi-file-earmark-text"></i>
            </div>
        </div>
    </div>
    
    <!-- Stat 2: Menunggu -->
    <div class="col-6 col-lg-3 animate-fade-in">
        <div class="bg-gradient-to-br from-amber-500 to-amber-600 text-white rounded-2xl p-4 relative overflow-hidden shadow-sm shadow-amber-500/10 hover:scale-[1.02] transition-transform duration-300">
            <div class="font-extrabold text-3xl leading-none mb-1">{{ $stats['menunggu'] }}</div>
            <div class="text-[10px] uppercase font-bold tracking-wider opacity-85">Menunggu Validasi</div>
            <div class="absolute -right-3 -bottom-3 text-white/10 font-bold text-6xl leading-none select-none pointer-events-none">
                <i class="bi bi-hourglass-split"></i>
            </div>
        </div>
    </div>

    <!-- Stat 3: Diproses -->
    <div class="col-6 col-lg-3 animate-fade-in">
        <div class="bg-gradient-to-br from-sky-500 to-sky-600 text-white rounded-2xl p-4 relative overflow-hidden shadow-sm shadow-sky-500/10 hover:scale-[1.02] transition-transform duration-300">
            <div class="font-extrabold text-3xl leading-none mb-1">{{ $stats['diproses'] }}</div>
            <div class="text-[10px] uppercase font-bold tracking-wider opacity-85">Sedang Diproses</div>
            <div class="absolute -right-3 -bottom-3 text-white/10 font-bold text-6xl leading-none select-none pointer-events-none">
                <i class="bi bi-gear-wide-connected"></i>
            </div>
        </div>
    </div>

    <!-- Stat 4: Selesai -->
    <div class="col-6 col-lg-3 animate-fade-in">
        <div class="bg-gradient-to-br from-emerald-500 to-emerald-600 text-white rounded-2xl p-4 relative overflow-hidden shadow-sm shadow-emerald-500/10 hover:scale-[1.02] transition-transform duration-300">
            <div class="font-extrabold text-3xl leading-none mb-1">{{ $stats['selesai'] }}</div>
            <div class="text-[10px] uppercase font-bold tracking-wider opacity-85">Selesai Diperbaiki</div>
            <div class="absolute -right-3 -bottom-3 text-white/10 font-bold text-6xl leading-none select-none pointer-events-none">
                <i class="bi bi-check-circle-fill"></i>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Left Column: Laporan Terbaru Saya -->
    <div class="col-12 col-lg-8">
        <div class="bg-white rounded-2xl border border-slate-200/60 p-4 sm:p-5 shadow-sm h-full">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <div>
                    <h5 class="font-bold text-slate-800 mb-1">Laporan Terbaru Saya</h5>
                    <p class="text-xs text-slate-400 font-semibold mb-0 uppercase tracking-wider">Status aduan terakhir yang Anda laporkan</p>
                </div>
                <a href="{{ route('user.reports.index') }}" class="px-3 py-1.5 border border-slate-200 text-slate-600 hover:bg-slate-50 hover:text-slate-900 rounded-lg text-xs font-bold text-decoration-none">
                    Lihat Semua
                </a>
            </div>

            <div class="divide-y divide-slate-100">
                @forelse($latestReports as $report)
                    <div class="flex items-start gap-4 py-3.5 first:pt-0 last:pb-0">
                        <div class="rounded-xl h-10 w-10 bg-blue-50 text-blue-600 flex items-center justify-center flex-shrink-0">
                            @if($report->category === 'jalan')
                                <i class="bi bi-cone-striped fs-5"></i>
                            @elseif($report->category === 'lampu')
                                <i class="bi bi-lightbulb fs-5"></i>
                            @elseif($report->category === 'taman')
                                <i class="bi bi-tree fs-5"></i>
                            @elseif($report->category === 'drainase')
                                <i class="bi bi-droplet fs-5"></i>
                            @else
                                <i class="bi bi-file-earmark-text fs-5"></i>
                            @endif
                        </div>
                        <div class="flex-grow min-w-0">
                            <div class="flex items-start justify-between gap-3 mb-1">
                                <a href="{{ route('user.reports.show', $report) }}" class="font-bold text-sm text-slate-800 text-decoration-none hover:text-blue-600 truncate">
                                    {{ $report->title }}
                                </a>
                                <span class="status-badge status-{{ $report->status }} flex-shrink-0">
                                    <span class="h-1.5 w-1.5 rounded-full bg-current"></span>
                                    {{ $report->status_label }}
                                </span>
                            </div>
                            <div class="flex flex-wrap items-center gap-x-3 gap-y-1 text-xs text-slate-400 font-medium">
                                <span class="flex items-center gap-1">
                                    <i class="bi bi-geo-alt text-slate-300"></i>{{ Str::limit($report->location, 45) }}
                                </span>
                                <span>•</span>
                                <span class="flex items-center gap-1">
                                    <i class="bi bi-calendar3 text-slate-300"></i>{{ $report->created_at->diffForHumans() }}
                                </span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-12 text-slate-400">
                        <i class="bi bi-inbox fs-1 d-block mb-2 text-slate-300"></i>
                        <p class="mb-3 text-sm font-semibold">Belum ada aduan laporan yang Anda kirimkan.</p>
                        <a href="{{ route('user.reports.create') }}" class="btn btn-sm btn-outline-primary rounded-lg text-xs font-bold px-3 py-2">
                            <i class="bi bi-plus-circle me-1"></i>Buat Laporan Pertama
                        </a>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Right Column: Notifikasi & Aksi Cepat -->
    <div class="col-12 col-lg-4 space-y-4">
        
        <!-- Notifikasi Terbaru Widget -->
        <div class="bg-white rounded-2xl border border-slate-200/60 p-4 sm:p-5 shadow-sm">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <div>
                    <h5 class="font-bold text-slate-800 mb-1">Notifikasi Terbaru</h5>
                    <p class="text-xs text-slate-400 font-semibold mb-0 uppercase tracking-wider">Pemberitahuan sistem aduan</p>
                </div>
                <a href="{{ route('user.notifications.index') }}" class="px-2.5 py-1.5 border border-slate-200 text-slate-600 hover:bg-slate-50 hover:text-slate-900 rounded-lg text-[10px] font-bold text-decoration-none uppercase tracking-wider">Semua</a>
            </div>

            <div class="space-y-3">
                @forelse($unreadNotifications as $notif)
                    <div class="p-3 bg-amber-50/45 border border-amber-100/40 rounded-xl flex items-start gap-2.5">
                        <i class="bi bi-bell-fill text-amber-500 flex-shrink-0 mt-0.5" style="font-size: 0.85rem;"></i>
                        <div class="min-w-0 flex-grow">
                            <p class="text-slate-700 text-xs font-semibold leading-normal mb-1">
                                {{ $notif->data['message'] ?? '-' }}
                            </p>
                            <span class="text-[9px] text-slate-400 font-bold uppercase tracking-wider">
                                {{ $notif->created_at->diffForHumans() }}
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-6 text-slate-400">
                        <i class="bi bi-bell-slash fs-3 d-block mb-1.5 text-slate-300"></i>
                        <p class="mb-0 text-xs font-semibold">Tidak ada pemberitahuan baru.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Aksi Cepat Widget -->
        <div class="bg-white rounded-2xl border border-slate-200/60 p-4 sm:p-5 shadow-sm">
            <div class="mb-4">
                <h5 class="font-bold text-slate-800 mb-1">Aksi Cepat</h5>
                <p class="text-xs text-slate-400 font-semibold mb-0 uppercase tracking-wider">Pintasan menu cepat aduan</p>
            </div>
            
            <div class="d-grid gap-2.5">
                <a href="{{ route('user.reports.create') }}" class="btn btn-primary py-2.5 px-4 rounded-xl text-xs font-bold shadow-md shadow-blue-500/10 text-decoration-none hover-lift flex items-center justify-center gap-2">
                    <i class="bi bi-plus-circle-fill"></i> Buat Laporan Baru
                </a>
                <a href="{{ route('user.reports.index') }}" class="btn btn-light border border-slate-200 text-slate-600 py-2.5 px-4 rounded-xl text-xs font-bold text-decoration-none hover-lift flex items-center justify-center gap-2">
                    <i class="bi bi-list-ul"></i> Lihat Semua Laporan
                </a>
            </div>
        </div>

    </div>
</div>
@endsection
