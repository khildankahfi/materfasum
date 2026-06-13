@extends('layouts.admin')

@section('title', 'Dashboard Admin')
@section('page-title', 'Dashboard')
@section('breadcrumb')
    <li class="breadcrumb-item active">Dashboard</li>
@endsection

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
<script src="{{ asset('vendor/chartjs/chart.umd.min.js') }}"></script>
<style>
    #map-dashboard {
        height: 320px;
        border-radius: 12px;
        border: 1px solid #c7e2f5;
        z-index: 1;
    }
    .leaflet-popup-content-wrapper {
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        padding: 0;
        overflow: hidden;
    }
    .leaflet-popup-content {
        margin: 0;
    }
</style>
@endpush

@section('content')

{{-- Stats Row --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
        <div class="stat-card d-flex flex-column justify-content-between h-100">
            <div class="text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-2">TOTAL MASUK</div>
            <div class="font-extrabold text-2xl text-sky-700">{{ $stats['total_laporan'] }} Laporan</div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card d-flex flex-column justify-content-between h-100">
            <div class="text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-2">▲ BARU</div>
            <div class="font-extrabold text-2xl text-amber-600">{{ $stats['menunggu'] }} Antrean</div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card d-flex flex-column justify-content-between h-100">
            <div class="text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-2">⚡ PROSES</div>
            <div class="font-extrabold text-2xl text-sky-700">{{ $stats['diproses'] }} Lokasi</div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card d-flex flex-column justify-content-between h-100">
            <div class="text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-2">✓ SELESAI</div>
            <div class="font-extrabold text-2xl text-emerald-600">{{ $stats['selesai'] }} Laporan</div>
        </div>
    </div>
</div>

{{-- Antrean & GIS Map split --}}
<div class="row g-4 mb-4">
    {{-- Antrean Laporan Warga Terbaru --}}
    <div class="col-12 col-lg-6">
        <div class="card h-100">
            <div class="card-header border-bottom border-slate-100 d-flex align-items-center justify-content-between py-3">
                <span class="font-bold text-sm text-indigo-950 d-flex align-items-center gap-2">
                    <i class="bi bi-collection-play text-sky-600"></i> Antrean Laporan Warga Terbaru
                </span>
                <a href="{{ route('admin.reports.index') }}" class="btn btn-sm btn-light border text-xs font-bold px-2.5 py-1.5 rounded-lg hover-lift">
                    Lihat Semua
                </a>
            </div>
            <div class="card-body p-0 overflow-y-auto" style="max-height: 320px;">
                @forelse($laporanTerbaru->take(5) as $report)
                    <div class="p-3 border-bottom border-slate-100 last:border-0 hover:bg-slate-50/55 transition-colors">
                        <div class="d-flex align-items-start justify-content-between gap-3">
                            <div class="min-w-0">
                                <small class="text-sky-600 font-extrabold text-[10px] uppercase tracking-wider block mb-0.5">#TRX-{{ sprintf('%03d', $report->id) }}</small>
                                <a href="{{ route('admin.reports.show', $report) }}" class="text-indigo-950 font-bold text-sm text-decoration-none hover:text-sky-600 block mb-1 truncate">
                                    {{ $report->title }}
                                </a>
                                <span class="text-slate-400 font-semibold text-xs block truncate"><i class="bi bi-geo-alt me-1 text-slate-300"></i>{{ $report->location }}</span>
                            </div>
                            <div class="flex-shrink-0">
                                @if($report->status === 'menunggu')
                                    <span class="badge px-2.5 py-1 text-[9px] font-bold" style="background:#fff7ed; color:#c2410c; border: 1px solid #ffedd5;">BARU</span>
                                @elseif($report->status === 'diproses')
                                    <span class="badge px-2.5 py-1 text-[9px] font-bold" style="background:#f0f9ff; color:#0369a1; border: 1px solid #e0f2fe;">PROSES</span>
                                @elseif($report->status === 'selesai')
                                    <span class="badge px-2.5 py-1 text-[9px] font-bold" style="background:#f0fdf4; color:#15803d; border: 1px solid #d1fae5;">SELESAI</span>
                                @else
                                    <span class="badge px-2.5 py-1 text-[9px] font-bold" style="background:#fef2f2; color:#b91c1c; border: 1px solid #fee2e2;">DITOLAK</span>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-5 text-slate-400">
                        <i class="bi bi-check-circle fs-3 text-success opacity-75"></i>
                        <p class="mb-0 mt-2 text-xs font-bold">Belum ada aduan warga masuk</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- GIS Map --}}
    <div class="col-12 col-lg-6">
        <div class="card h-100">
            <div class="card-header border-bottom border-slate-100 py-3">
                <span class="font-bold text-sm text-indigo-950 d-flex align-items-center gap-2">
                    <i class="bi bi-geo-alt text-sky-600"></i> Pemetaan Lokasi Laporan (GIS)
                </span>
            </div>
            <div class="card-body p-2">
                <div id="map-dashboard"></div>
            </div>
        </div>
    </div>
</div>

{{-- Validation List & Chart --}}
<div class="row g-4 mb-4">
    {{-- Laporan Perlu Validasi --}}
    <div class="col-12 col-lg-7">
        <div class="card h-100">
            <div class="card-header d-flex align-items-center justify-content-between">
                <span class="font-bold text-sm text-indigo-950"><i class="bi bi-hourglass-split me-2 text-warning"></i>Menunggu Validasi</span>
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
                            <i class="bi bi-check-circle fs-3 text-success opacity-75 animate-pulse"></i>
                            <p class="mb-0 mt-2 text-xs font-bold">Semua laporan aduan warga sudah divalidasi!</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    {{-- Kategori Chart --}}
    <div class="col-12 col-lg-5">
        <div class="card h-100">
            <div class="card-header font-bold text-sm text-indigo-950"><i class="bi bi-pie-chart me-2 text-primary"></i>Laporan per Kategori</div>
            <div class="card-body d-flex align-items-center justify-content-center" style="min-height:240px;">
                @if($kategoryStat->isEmpty())
                    <div class="text-center text-slate-400">
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

{{-- Detail Table Laporan Terbaru --}}
<div class="card">
    <div class="card-header d-flex align-items-center justify-content-between">
        <span class="font-bold text-sm text-indigo-950"><i class="bi bi-clock-history me-2 text-primary"></i>Riwayat Laporan Terbaru</span>
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
                                <small class="text-slate-400 font-semibold"><i class="bi bi-geo-alt me-1 text-slate-300"></i>{{ Str::limit($report->location, 35) }}</small>
                            </td>
                            <td class="py-3 font-semibold text-slate-700">{{ $report->user->name }}</td>
                            <td class="py-3"><span class="badge bg-slate-100 text-slate-700 border border-slate-200/40">{{ $report->category_label }}</span></td>
                            <td class="py-3">
                                <span class="status-badge status-{{ $report->status }}">
                                    <span class="h-1.5 w-1.5 rounded-full bg-current"></span>
                                    {{ $report->status_label }}
                                </span>
                            </td>
                            <td class="py-3 text-slate-400 font-medium">{{ $report->created_at->format('d M Y') }}</td>
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
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
@php
    $allReportsForMap = \App\Models\Report::select('id','title','latitude','longitude','status','location','category')->get();
    foreach($allReportsForMap as $r) {
        $r->url = route('admin.reports.show', $r);
        $r->status_label = $r->status_label;
    }
@endphp
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Map
        const reports = @json($allReportsForMap);
        const map = L.map('map-dashboard', { scrollWheelZoom: false }).setView([-7.1539, 112.6561], 12);
        
        // CartoDB Positron - Light & minimal layout matching mockup style
        L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
            attribution: '© OpenStreetMap contributors © CartoDB'
        }).addTo(map);

        function getMarkerColor(status) {
            if (status === 'selesai') return '#10b981'; // Green
            if (status === 'diproses') return '#0284c7'; // Blue
            if (status === 'ditolak') return '#ef4444'; // Red
            return '#f59e0b'; // Amber (pending)
        }

        reports.forEach(report => {
            if (report.latitude && report.longitude) {
                const color = getMarkerColor(report.status);
                const svgIcon = L.divIcon({
                    html: `<svg width="22" height="30" viewBox="0 0 30 42" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M15 0C6.71573 0 0 6.71573 0 15C0 24.375 15 42 15 42C15 42 30 24.375 30 15C30 6.71573 23.2843 0 15 0ZM15 20.5C11.9624 20.5 9.5 18.0376 9.5 15C9.5 11.9624 11.9624 9.5 15 9.5C18.0376 9.5 20.5 11.9624 20.5 15C20.5 18.0376 18.0376 20.5 15 20.5Z" fill="${color}"/>
                           </svg>`,
                    className: "",
                    iconSize: [22, 30],
                    iconAnchor: [11, 30],
                    popupAnchor: [0, -30]
                });

                const popupContent = `
                    <div class="p-2.5 bg-white max-w-[240px]">
                        <span class="status-badge status-${report.status} mb-1.5 text-[9px] py-0.5 px-2">
                            ${report.status_label}
                        </span>
                        <h6 class="font-bold text-slate-800 text-xs mb-1 line-clamp-2">${report.title}</h6>
                        <div class="text-[10px] text-slate-500 font-semibold mb-2 truncate"><i class="bi bi-geo-alt me-1"></i>${report.location}</div>
                        <a href="${report.url}" class="btn btn-primary btn-sm w-100 rounded-lg text-[10px] font-bold py-1 text-decoration-none text-white text-center d-block">
                            Tinjau Laporan
                        </a>
                    </div>
                `;

                L.marker([report.latitude, report.longitude], { icon: svgIcon })
                    .bindPopup(popupContent)
                    .addTo(map);
            }
        });
    });
</script>

@if(!$kategoryStat->isEmpty())
<script>
    document.addEventListener('DOMContentLoaded', function() {
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
    });
</script>
@endif
@endpush
