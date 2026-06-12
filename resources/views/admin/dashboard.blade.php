@extends('layouts.admin')

@section('title', 'Dashboard Admin')
@section('page-title', 'Dashboard')
@section('breadcrumb')
    <li class="breadcrumb-item active">Dashboard</li>
@endsection

@push('styles')
<script src="{{ asset('vendor/chartjs/chart.umd.min.js') }}"></script>
@endpush

@section('content')

<div class="mb-4">
    <h5 class="font-extrabold text-slate-800 mb-1">Selamat datang, {{ auth()->user()->name }}! 👋</h5>
    <p class="text-slate-500 font-semibold text-xs mb-0">Berikut ringkasan laporan fasilitas kota Gresik yang masuk hari ini.</p>
</div>

{{-- Stats --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-lg-2">
        <div class="stat-card" style="background: linear-gradient(135deg, #6366f1, #4f46e5);">
            <div class="font-extrabold text-3xl leading-none mb-1">{{ $stats['total_laporan'] }}</div>
            <div class="text-[10px] uppercase font-bold tracking-wider opacity-85">Total Laporan</div>
            <i class="bi bi-file-earmark-text icon"></i>
        </div>
    </div>
    <div class="col-6 col-lg-2">
        <div class="stat-card" style="background: linear-gradient(135deg, #f59e0b, #d97706);">
            <div class="font-extrabold text-3xl leading-none mb-1">{{ $stats['menunggu'] }}</div>
            <div class="text-[10px] uppercase font-bold tracking-wider opacity-85">Menunggu</div>
            <i class="bi bi-hourglass-split icon"></i>
        </div>
    </div>
    <div class="col-6 col-lg-2">
        <div class="stat-card" style="background: linear-gradient(135deg, #0ea5e9, #0284c7);">
            <div class="font-extrabold text-3xl leading-none mb-1">{{ $stats['diproses'] }}</div>
            <div class="text-[10px] uppercase font-bold tracking-wider opacity-85">Diproses</div>
            <i class="bi bi-gear icon"></i>
        </div>
    </div>
    <div class="col-6 col-lg-2">
        <div class="stat-card" style="background: linear-gradient(135deg, #10b981, #059669);">
            <div class="font-extrabold text-3xl leading-none mb-1">{{ $stats['selesai'] }}</div>
            <div class="text-[10px] uppercase font-bold tracking-wider opacity-85">Selesai</div>
            <i class="bi bi-check-circle icon"></i>
        </div>
    </div>
    <div class="col-6 col-lg-2">
        <div class="stat-card" style="background: linear-gradient(135deg, #ef4444, #dc2626);">
            <div class="font-extrabold text-3xl leading-none mb-1">{{ $stats['ditolak'] }}</div>
            <div class="text-[10px] uppercase font-bold tracking-wider opacity-85">Ditolak</div>
            <i class="bi bi-x-circle icon"></i>
        </div>
    </div>
    <div class="col-6 col-lg-2">
        <div class="stat-card" style="background: linear-gradient(135deg, #64748b, #475569);">
            <div class="font-extrabold text-3xl leading-none mb-1">{{ $stats['total_user'] }}</div>
            <div class="text-[10px] uppercase font-bold tracking-wider opacity-85">Total User</div>
            <i class="bi bi-people icon"></i>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    {{-- Laporan Perlu Validasi --}}
    <div class="col-12 col-lg-7">
        <div class="card h-100">
            <div class="card-header d-flex align-items-center justify-content-between">
                <span class="font-bold text-sm text-slate-800"><i class="bi bi-hourglass-split me-2 text-warning"></i>Menunggu Validasi</span>
                <a href="{{ route('admin.reports.index') }}?status=menunggu" class="btn btn-sm btn-outline-warning border-0 bg-amber-50 text-amber-700 hover:bg-amber-500 hover:text-white px-3 py-1.5 rounded-lg text-xs font-bold">
                    Lihat Semua
                </a>
            </div>
            <div class="card-body p-0">
                <div class="divide-y divide-slate-100">
                    @forelse($laporanBaru as $report)
                        <div class="d-flex align-items-center gap-3 p-3 first:pt-3 last:pb-3">
                            <div class="rounded-xl d-flex align-items-center justify-content-center flex-shrink-0"
                                 style="width:38px;height:38px;background:#fef3c7;">
                                <i class="bi bi-exclamation-triangle-fill text-warning" style="font-size:0.95rem;"></i>
                            </div>
                            <div class="flex-grow-1 min-w-0">
                                <div class="font-bold text-slate-800 text-xs text-truncate">{{ $report->title }}</div>
                                <small class="text-slate-400 font-bold text-[10px] uppercase tracking-wider block mt-0.5">
                                    <i class="bi bi-person me-1 text-slate-300"></i>{{ $report->user->name }}
                                    &nbsp;·&nbsp;
                                    <i class="bi bi-clock me-1 text-slate-300"></i>{{ $report->created_at->diffForHumans() }}
                                </small>
                            </div>
                            <a href="{{ route('admin.reports.show', $report) }}"
                               class="btn btn-sm btn-warning text-xs font-bold px-3 py-2 rounded-lg flex-shrink-0 hover-lift">
                                Tinjau
                            </a>
                        </div>
                    @empty
                        <div class="text-center py-5 text-slate-400">
                            <i class="bi bi-check-circle fs-2 d-block mb-2 text-success opacity-75 animate-pulse"></i>
                            <small class="font-bold">Semua laporan aduan warga sudah divalidasi!</small>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    {{-- Grafik Kategori --}}
    <div class="col-12 col-lg-5">
        <div class="card h-100">
            <div class="card-header font-bold text-sm text-slate-800"><i class="bi bi-pie-chart me-2 text-primary"></i>Laporan per Kategori</div>
            <div class="card-body d-flex align-items-center justify-content-center" style="min-height:240px;">
                @if($kategoryStat->isEmpty())
                    <div class="text-center text-slate-450">
                        <i class="bi bi-bar-chart fs-2 d-block mb-1.5 opacity-50"></i>
                        <small class="font-bold">Belum ada data aduan warga</small>
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
        <span class="font-bold text-sm text-slate-800"><i class="bi bi-clock-history me-2 text-primary"></i>Laporan Terbaru</span>
        <a href="{{ route('admin.reports.index') }}" class="btn btn-sm btn-outline-primary border-0 bg-blue-50 text-blue-700 hover:bg-blue-600 hover:text-white px-3 py-1.5 rounded-lg text-xs font-bold">Lihat Semua</a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0" style="font-size:.8rem;">
                <thead class="bg-slate-50 border-bottom border-slate-200/80 text-slate-500 text-[10px] font-bold uppercase tracking-wider">
                    <tr>
                        <th class="ps-4 py-3.5">Judul</th>
                        <th class="py-3.5">Pelapor</th>
                        <th class="py-3.5">Kategori</th>
                        <th class="py-3.5" style="width: 140px;">Status</th>
                        <th class="py-3.5">Tanggal</th>
                        <th class="pe-4 py-3.5"></th>
                    </tr>
                </thead>
                <tbody class="text-xs">
                    @foreach($laporanTerbaru as $report)
                        <tr>
                            <td class="ps-4 py-3">
                                <div class="font-bold text-slate-800 mb-0.5">{{ Str::limit($report->title, 45) }}</div>
                                <small class="text-slate-400 font-semibold"><i class="bi bi-geo-alt me-1 text-slate-350"></i>{{ Str::limit($report->location, 35) }}</small>
                            </td>
                            <td class="py-3 font-semibold text-slate-700">{{ $report->user->name }}</td>
                            <td class="py-3"><span class="badge bg-slate-100 text-slate-700 border border-slate-200/40">{{ $report->category_label }}</span></td>
                            <td class="py-3">
                                <span class="status-badge status-{{ $report->status }}">
                                    <span class="h-1.5 w-1.5 rounded-full bg-current"></span>
                                    {{ $report->status_label }}
                                </span>
                            </td>
                            <td class="py-3 text-slate-455 font-medium">{{ $report->created_at->format('d M Y') }}</td>
                            <td class="pe-4 py-3 text-end">
                                <a href="{{ route('admin.reports.show', $report) }}" class="btn btn-sm btn-light border border-slate-200 text-slate-500 rounded-lg p-1.5 hover-lift">
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
                backgroundColor: ['#6366f1','#0ea5e9','#10b981','#f59e0b','#ef4444','#64748b','#ec4899'],
                borderWidth: 2,
                borderColor: '#fff',
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'bottom', labels: { font: { size: 10, family: 'Plus Jakarta Sans', weight: 'bold' }, padding: 8 } }
            }
        }
    });
</script>
@endif
@endpush
