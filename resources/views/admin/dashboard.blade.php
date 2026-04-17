@extends('layouts.admin')

@section('title', 'Dashboard Admin')
@section('page-title', 'Dashboard')
@section('breadcrumb')
    <li class="breadcrumb-item active">Dashboard</li>
@endsection

@push('styles')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
@endpush

@section('content')

<div class="mb-4">
    <h5 class="fw-700 mb-1">Selamat datang, {{ auth()->user()->name }}!</h5>
    <p class="text-muted mb-0" style="font-size:.9rem;">Berikut ringkasan laporan fasilitas yang masuk hari ini.</p>
</div>

{{-- Stats --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-lg-2">
        <div class="stat-card" style="background:linear-gradient(135deg,#7c3aed,#6d28d9);">
            <div class="fw-700 fs-2">{{ $stats['total_laporan'] }}</div>
            <div style="font-size:.78rem;opacity:.85;">Total Laporan</div>
            <i class="bi bi-file-earmark-text icon"></i>
        </div>
    </div>
    <div class="col-6 col-lg-2">
        <div class="stat-card" style="background:linear-gradient(135deg,#f59e0b,#d97706);">
            <div class="fw-700 fs-2">{{ $stats['menunggu'] }}</div>
            <div style="font-size:.78rem;opacity:.85;">Menunggu</div>
            <i class="bi bi-hourglass-split icon"></i>
        </div>
    </div>
    <div class="col-6 col-lg-2">
        <div class="stat-card" style="background:linear-gradient(135deg,#0ea5e9,#0284c7);">
            <div class="fw-700 fs-2">{{ $stats['diproses'] }}</div>
            <div style="font-size:.78rem;opacity:.85;">Diproses</div>
            <i class="bi bi-gear icon"></i>
        </div>
    </div>
    <div class="col-6 col-lg-2">
        <div class="stat-card" style="background:linear-gradient(135deg,#10b981,#059669);">
            <div class="fw-700 fs-2">{{ $stats['selesai'] }}</div>
            <div style="font-size:.78rem;opacity:.85;">Selesai</div>
            <i class="bi bi-check-circle icon"></i>
        </div>
    </div>
    <div class="col-6 col-lg-2">
        <div class="stat-card" style="background:linear-gradient(135deg,#ef4444,#dc2626);">
            <div class="fw-700 fs-2">{{ $stats['ditolak'] }}</div>
            <div style="font-size:.78rem;opacity:.85;">Ditolak</div>
            <i class="bi bi-x-circle icon"></i>
        </div>
    </div>
    <div class="col-6 col-lg-2">
        <div class="stat-card" style="background:linear-gradient(135deg,#64748b,#475569);">
            <div class="fw-700 fs-2">{{ $stats['total_user'] }}</div>
            <div style="font-size:.78rem;opacity:.85;">Total User</div>
            <i class="bi bi-people icon"></i>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    {{-- Laporan Perlu Validasi --}}
    <div class="col-12 col-lg-7">
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <span><i class="bi bi-hourglass-split me-2 text-warning"></i>Menunggu Validasi</span>
                <a href="{{ route('admin.reports.index') }}?status=menunggu" class="btn btn-sm btn-outline-warning" style="border-radius:8px;font-size:.78rem;">
                    Lihat Semua
                </a>
            </div>
            <div class="card-body p-0">
                @forelse($laporanBaru as $report)
                    <div class="d-flex align-items-center gap-3 p-3 border-bottom">
                        <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                             style="width:38px;height:38px;background:#fef3c7;">
                            <i class="bi bi-exclamation text-warning" style="font-size:1rem;"></i>
                        </div>
                        <div class="flex-grow-1 min-width-0">
                            <div class="fw-600 text-truncate" style="font-size:.88rem;">{{ $report->title }}</div>
                            <small class="text-muted">
                                <i class="bi bi-person me-1"></i>{{ $report->user->name }}
                                &nbsp;·&nbsp;
                                {{ $report->created_at->diffForHumans() }}
                            </small>
                        </div>
                        <a href="{{ route('admin.reports.show', $report) }}"
                           class="btn btn-sm btn-warning flex-shrink-0" style="border-radius:8px;font-size:.78rem;">
                            Tinjau
                        </a>
                    </div>
                @empty
                    <div class="text-center py-4 text-muted">
                        <i class="bi bi-check-circle fs-2 d-block mb-1 text-success opacity-75"></i>
                        <small>Semua laporan sudah diproses!</small>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Grafik Kategori --}}
    <div class="col-12 col-lg-5">
        <div class="card">
            <div class="card-header"><i class="bi bi-pie-chart me-2 text-primary"></i>Laporan per Kategori</div>
            <div class="card-body d-flex align-items-center justify-content-center" style="min-height:240px;">
                @if($kategoryStat->isEmpty())
                    <div class="text-center text-muted">
                        <i class="bi bi-bar-chart fs-2 d-block mb-1 opacity-50"></i>
                        <small>Belum ada data</small>
                    </div>
                @else
                    <canvas id="categoryChart" style="max-height:220px;"></canvas>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- Laporan Terbaru --}}
<div class="card">
    <div class="card-header d-flex align-items-center justify-content-between">
        <span><i class="bi bi-clock-history me-2 text-primary"></i>Laporan Terbaru</span>
        <a href="{{ route('admin.reports.index') }}" class="btn btn-sm btn-outline-primary" style="border-radius:8px;font-size:.78rem;">Lihat Semua</a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0" style="font-size:.85rem;">
                <thead style="background:#f8fafc;color:#64748b;">
                    <tr>
                        <th class="ps-4">Judul</th>
                        <th>Pelapor</th>
                        <th>Kategori</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                        <th class="pe-4"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($laporanTerbaru as $report)
                        <tr>
                            <td class="ps-4">
                                <div class="fw-600">{{ Str::limit($report->title, 35) }}</div>
                                <small class="text-muted">{{ Str::limit($report->location, 30) }}</small>
                            </td>
                            <td>{{ $report->user->name }}</td>
                            <td><span class="badge bg-light text-dark">{{ $report->category_label }}</span></td>
                            <td><span class="badge badge-{{ $report->status }}">{{ $report->status_label }}</span></td>
                            <td class="text-muted">{{ $report->created_at->format('d M Y') }}</td>
                            <td class="pe-4">
                                <a href="{{ route('admin.reports.show', $report) }}" class="btn btn-sm btn-outline-primary" style="border-radius:7px;">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
@if(!$kategoryStat->isEmpty())
<script>
    const rawLabels = @json($kategoryStat->keys());
    const labelMap = {
        'jalan': 'Jalan',
        'jembatan': 'Jembatan',
        'lampu': 'Lampu Jalan',
        'taman': 'Taman',
        'drainase': 'Drainase',
        'fasilitas_umum': 'Fasilitas Umum',
        'lainnya': 'Lainnya'
    };
    const labels = rawLabels.map(k => labelMap[k] || k);
    const data   = @json($kategoryStat->values());

    new Chart(document.getElementById('categoryChart'), {
        type: 'doughnut',
        data: {
            labels,
            datasets: [{
                data,
                backgroundColor: ['#7c3aed','#0ea5e9','#10b981','#f59e0b','#ef4444','#64748b','#ec4899'],
                borderWidth: 2,
                borderColor: '#fff',
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'bottom', labels: { font: { size: 11 }, padding: 10 } }
            }
        }
    });
</script>
@endif
@endpush
