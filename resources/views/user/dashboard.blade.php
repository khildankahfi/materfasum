@extends('layouts.user')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('breadcrumb')
    <li class="breadcrumb-item active">Dashboard</li>
@endsection

@section('content')

{{-- Greeting --}}
<div class="mb-4">
    <h5 class="fw-700 mb-1">Halo, {{ auth()->user()->name }}! 👋</h5>
    <p class="text-muted mb-0" style="font-size:.9rem;">Selamat datang di Materfasum. Berikut ringkasan laporan Anda.</p>
</div>

{{-- Stats --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
        <div class="stat-card" style="background:linear-gradient(135deg,#2563eb,#1d4ed8);">
            <div class="fw-700 fs-2">{{ $stats['total'] }}</div>
            <div style="font-size:.82rem;opacity:.85;">Total Laporan</div>
            <i class="bi bi-file-earmark-text icon"></i>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card" style="background:linear-gradient(135deg,#f59e0b,#d97706);">
            <div class="fw-700 fs-2">{{ $stats['menunggu'] }}</div>
            <div style="font-size:.82rem;opacity:.85;">Menunggu</div>
            <i class="bi bi-hourglass-split icon"></i>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card" style="background:linear-gradient(135deg,#06b6d4,#0891b2);">
            <div class="fw-700 fs-2">{{ $stats['diproses'] }}</div>
            <div style="font-size:.82rem;opacity:.85;">Diproses</div>
            <i class="bi bi-gear icon"></i>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card" style="background:linear-gradient(135deg,#10b981,#059669);">
            <div class="fw-700 fs-2">{{ $stats['selesai'] }}</div>
            <div style="font-size:.82rem;opacity:.85;">Selesai</div>
            <i class="bi bi-check-circle icon"></i>
        </div>
    </div>
</div>

<div class="row g-4">
    {{-- Laporan Terbaru --}}
    <div class="col-12 col-lg-8">
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <span><i class="bi bi-clock-history me-2 text-primary"></i>Laporan Terbaru Saya</span>
                <a href="{{ route('user.reports.index') }}" class="btn btn-sm btn-outline-primary" style="border-radius:8px;font-size:.8rem;">
                    Lihat Semua
                </a>
            </div>
            <div class="card-body p-0">
                @forelse($latestReports as $report)
                    <div class="d-flex align-items-start gap-3 p-3 border-bottom">
                        <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                             style="width:40px;height:40px;background:#eff6ff;">
                            <i class="bi bi-file-text text-primary"></i>
                        </div>
                        <div class="flex-grow-1 min-width-0">
                            <div class="d-flex align-items-start justify-content-between gap-2">
                                <a href="{{ route('user.reports.show', $report) }}"
                                   class="fw-600 text-dark text-decoration-none"
                                   style="font-size:.9rem;">
                                    {{ Str::limit($report->title, 50) }}
                                </a>
                                <span class="badge badge-{{ $report->status }} flex-shrink-0" style="font-size:.72rem;">
                                    {{ $report->status_label }}
                                </span>
                            </div>
                            <div class="text-muted" style="font-size:.78rem;">
                                <i class="bi bi-geo-alt me-1"></i>{{ Str::limit($report->location, 45) }}
                                &nbsp;·&nbsp;
                                <i class="bi bi-calendar3 me-1"></i>{{ $report->created_at->diffForHumans() }}
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-5 text-muted">
                        <i class="bi bi-inbox fs-1 d-block mb-2 opacity-50"></i>
                        <p class="mb-0">Belum ada laporan. <a href="{{ route('user.reports.create') }}">Buat laporan pertama</a></p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Panel Kanan --}}
    <div class="col-12 col-lg-4">
        {{-- Notifikasi Terbaru --}}
        <div class="card mb-4">
            <div class="card-header d-flex align-items-center justify-content-between">
                <span><i class="bi bi-bell me-2 text-primary"></i>Notifikasi</span>
                <a href="{{ route('user.notifications.index') }}" class="btn btn-sm btn-outline-primary" style="border-radius:8px;font-size:.8rem;">Semua</a>
            </div>
            <div class="card-body p-0">
                @forelse($unreadNotifications as $notif)
                    <div class="d-flex gap-2 p-3 border-bottom" style="background:#fffbeb;">
                        <i class="bi bi-bell-fill text-warning flex-shrink-0 mt-1" style="font-size:.9rem;"></i>
                        <div>
                            <div style="font-size:.82rem;">{{ $notif->data['message'] ?? '-' }}</div>
                            <div class="text-muted" style="font-size:.72rem;">{{ $notif->created_at->diffForHumans() }}</div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-4 text-muted">
                        <i class="bi bi-bell-slash fs-3 d-block mb-1 opacity-50"></i>
                        <small>Tidak ada notifikasi baru</small>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Aksi Cepat --}}
        <div class="card">
            <div class="card-header"><i class="bi bi-lightning me-2 text-primary"></i>Aksi Cepat</div>
            <div class="card-body d-grid gap-2">
                <a href="{{ route('user.reports.create') }}" class="btn btn-primary" style="border-radius:10px;">
                    <i class="bi bi-plus-circle me-2"></i> Buat Laporan Baru
                </a>
                <a href="{{ route('user.reports.index') }}" class="btn btn-outline-secondary" style="border-radius:10px;">
                    <i class="bi bi-list-ul me-2"></i> Lihat Semua Laporan
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
