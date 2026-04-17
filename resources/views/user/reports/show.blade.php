@extends('layouts.user')

@section('title', 'Detail Laporan')
@section('page-title', 'Detail Laporan')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('user.reports.index') }}">Laporan</a></li>
    <li class="breadcrumb-item active">Detail</li>
@endsection

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
<style>
    #map-detail { height: 220px; border-radius: 10px; z-index: 0; }

    /* Timeline */
    .timeline { position: relative; }
    .timeline-item { display: flex; gap: 1rem; padding-bottom: 1.5rem; position: relative; }
    .timeline-item:not(:last-child) .tl-dot::after {
        content: '';
        position: absolute;
        top: 36px; left: 17px;
        width: 2px; bottom: 0;
        background: #e2e8f0;
    }
    .tl-dot { flex-shrink: 0; position: relative; width: 36px; }
    .tl-dot .dot {
        width: 36px; height: 36px; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        color: #fff; font-size: .85rem; position: relative; z-index: 1;
    }
    .tl-body { flex-grow: 1; }

    /* Photos */
    .photo-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(110px, 1fr)); gap: .5rem; }
    .photo-grid img { width: 100%; height: 95px; object-fit: cover; border-radius: 8px; cursor: pointer; transition: opacity .2s; }
    .photo-grid img:hover { opacity: .85; }
</style>
@endpush

@section('content')

<div class="row g-4">
    {{-- Detail Laporan --}}
    <div class="col-12 col-lg-8">
        <div class="card mb-4">
            <div class="card-header d-flex align-items-center justify-content-between">
                <span><i class="bi bi-file-earmark-text me-2 text-primary"></i>Informasi Laporan</span>
                <span class="badge badge-{{ $report->status }} px-3 py-2" style="font-size:.78rem;">
                    {{ $report->status_label }}
                </span>
            </div>
            <div class="card-body">
                <h5 class="fw-700 mb-3">{{ $report->title }}</h5>

                <div class="row g-3 mb-4">
                    <div class="col-6">
                        <div class="text-muted mb-1" style="font-size:.78rem;text-transform:uppercase;letter-spacing:.5px;">Kategori</div>
                        <span class="badge bg-light text-dark px-2 py-1">{{ $report->category_label }}</span>
                    </div>
                    <div class="col-6">
                        <div class="text-muted mb-1" style="font-size:.78rem;text-transform:uppercase;letter-spacing:.5px;">Tanggal Lapor</div>
                        <div style="font-size:.9rem;">{{ $report->created_at->format('d F Y, H:i') }} WIB</div>
                    </div>
                    <div class="col-12">
                        <div class="text-muted mb-1" style="font-size:.78rem;text-transform:uppercase;letter-spacing:.5px;">Lokasi</div>
                        <div style="font-size:.9rem;"><i class="bi bi-geo-alt text-danger me-1"></i>{{ $report->location }}</div>
                    </div>
                </div>

                @if($report->latitude && $report->longitude)
                    <div class="mb-4">
                        <div class="text-muted mb-2" style="font-size:.78rem;text-transform:uppercase;letter-spacing:.5px;">Peta Lokasi</div>
                        <div id="map-detail"></div>
                    </div>
                @endif

                <div class="mb-4">
                    <div class="text-muted mb-2" style="font-size:.78rem;text-transform:uppercase;letter-spacing:.5px;">Deskripsi Kerusakan</div>
                    <p style="font-size:.9rem;line-height:1.7;color:#374151;">{{ $report->description }}</p>
                </div>

                {{-- Multiple Photos --}}
                @php $allPhotos = $report->all_photos; @endphp
                @if(count($allPhotos) > 0)
                    <div>
                        <div class="text-muted mb-2" style="font-size:.78rem;text-transform:uppercase;letter-spacing:.5px;">
                            Foto Kerusakan ({{ count($allPhotos) }} foto)
                        </div>
                        <div class="photo-grid">
                            @foreach($allPhotos as $url)
                                <img src="{{ $url }}" alt="Foto laporan"
                                     onclick="window.open(this.src,'_blank')" title="Klik untuk perbesar">
                            @endforeach
                        </div>
                        <small class="text-muted mt-1 d-block">Klik foto untuk memperbesar</small>
                    </div>
                @endif

                @if($report->status === 'ditolak' && $report->rejection_reason)
                    <div class="alert alert-danger mt-4" style="border-radius:10px;">
                        <div class="fw-600 mb-1"><i class="bi bi-x-circle-fill me-2"></i>Alasan Penolakan</div>
                        <div style="font-size:.88rem;">{{ $report->rejection_reason }}</div>
                    </div>
                @endif
            </div>
        </div>

        {{-- Timeline Riwayat Visual --}}
        <div class="card">
            <div class="card-header"><i class="bi bi-clock-history me-2 text-primary"></i>Riwayat Pembaruan</div>
            <div class="card-body">

                {{-- Progress bar status --}}
                <div class="mb-4 px-1">
                    @php
                        $steps = ['menunggu','diproses','selesai'];
                        $stepLabels = ['Menunggu','Diproses','Selesai'];
                        $currentIdx = array_search($report->status, $steps);
                        if ($report->status === 'ditolak') $currentIdx = 1;
                    @endphp
                    <div class="d-flex align-items-center justify-content-between position-relative">
                        {{-- Garis latar --}}
                        <div style="position:absolute;top:16px;left:10%;right:10%;height:3px;background:#e2e8f0;z-index:0;"></div>
                        {{-- Garis aktif --}}
                        <div style="position:absolute;top:16px;left:10%;
                            width:{{ $report->status === 'ditolak' ? '40%' : ($currentIdx >= 2 ? '80%' : ($currentIdx * 40)) . '%' }};
                            height:3px;background:#2563eb;z-index:1;transition:width .5s;"></div>

                        @foreach($steps as $idx => $step)
                            @php
                                $isDone    = $currentIdx !== false && $idx < $currentIdx;
                                $isCurrent = $idx === $currentIdx && $report->status !== 'ditolak';
                                $isRejected= $report->status === 'ditolak' && $idx === 1;
                            @endphp
                            <div class="text-center" style="position:relative;z-index:2;flex:1;">
                                <div class="rounded-circle mx-auto d-flex align-items-center justify-content-center text-white mb-1"
                                     style="width:32px;height:32px;font-size:.8rem;
                                     background:{{ $isDone ? '#10b981' : ($isCurrent ? '#2563eb' : ($isRejected && $idx===1 ? '#ef4444' : '#e2e8f0')) }};
                                     color:{{ ($isDone||$isCurrent||$isRejected) ? 'white' : '#94a3b8' }};">
                                    @if($isDone)
                                        <i class="bi bi-check-lg"></i>
                                    @elseif($isRejected && $idx===1)
                                        <i class="bi bi-x-lg"></i>
                                    @else
                                        {{ $idx + 1 }}
                                    @endif
                                </div>
                                <div style="font-size:.72rem;font-weight:600;
                                    color:{{ $isDone ? '#10b981' : ($isCurrent ? '#2563eb' : '#94a3b8') }};">
                                    {{ $stepLabels[$idx] }}
                                    @if($report->status === 'ditolak' && $idx === 1)
                                        <br><span style="color:#ef4444;">Ditolak</span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                @if($report->updates->isEmpty())
                    <div class="text-center py-3 text-muted">
                        <i class="bi bi-hourglass-split fs-2 d-block mb-2 opacity-50"></i>
                        <p class="mb-0 small">Belum ada pembaruan. Laporan sedang menunggu ditinjau oleh admin.</p>
                    </div>
                @else
                    <div class="timeline mt-3">
                        @foreach($report->updates as $update)
                            @php
                                $dotColor = match($update->status) {
                                    'diproses' => '#0ea5e9', 'selesai' => '#10b981',
                                    'ditolak'  => '#ef4444', default   => '#94a3b8'
                                };
                                $dotIcon = match($update->status) {
                                    'diproses' => 'gear-fill', 'selesai' => 'check-lg',
                                    'ditolak'  => 'x-lg',     default   => 'clock'
                                };
                            @endphp
                            <div class="timeline-item">
                                <div class="tl-dot">
                                    <div class="dot" style="background:{{ $dotColor }};">
                                        <i class="bi bi-{{ $dotIcon }}"></i>
                                    </div>
                                </div>
                                <div class="tl-body">
                                    <div class="d-flex align-items-start justify-content-between gap-2 mb-1">
                                        <span class="fw-600" style="font-size:.88rem;">
                                            Status diubah ke
                                            <span class="badge badge-{{ $update->status }} ms-1">{{ $update->status_label }}</span>
                                        </span>
                                        <small class="text-muted text-nowrap">{{ $update->created_at->format('d M Y, H:i') }}</small>
                                    </div>
                                    <small class="text-muted">oleh <strong>{{ $update->admin->name }}</strong></small>

                                    @if($update->note)
                                        <div class="mt-2 p-2 rounded" style="background:#f8fafc;font-size:.85rem;border-left:3px solid {{ $dotColor }};">
                                            <i class="bi bi-chat-left-text me-1 text-muted"></i>{{ $update->note }}
                                        </div>
                                    @endif
                                    @if($update->photo_after)
                                        <div class="mt-2">
                                            <img src="{{ Storage::url($update->photo_after) }}" class="rounded"
                                                 style="max-height:140px;cursor:pointer;border-radius:8px!important;"
                                                 onclick="window.open(this.src,'_blank')">
                                            <div class="text-muted mt-1" style="font-size:.72rem;">
                                                <i class="bi bi-image me-1"></i>Foto kondisi setelah diperbaiki
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Sidebar Kanan --}}
    <div class="col-12 col-lg-4">
        <div class="card mb-4">
            <div class="card-header"><i class="bi bi-info-circle me-2 text-primary"></i>Status Laporan</div>
            <div class="card-body text-center py-4">
                @php
                    $iconMap  = ['menunggu'=>'hourglass-split','diproses'=>'gear','selesai'=>'check-circle','ditolak'=>'x-circle'];
                    $colorMap = ['menunggu'=>'#f59e0b','diproses'=>'#0ea5e9','selesai'=>'#10b981','ditolak'=>'#ef4444'];
                @endphp
                <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3 text-white"
                     style="width:64px;height:64px;background:{{ $colorMap[$report->status] }};font-size:1.6rem;">
                    <i class="bi bi-{{ $iconMap[$report->status] }}"></i>
                </div>
                <h5 class="fw-700">{{ $report->status_label }}</h5>
                <p class="text-muted mb-0" style="font-size:.85rem;">
                    @if($report->status === 'menunggu') Laporan Anda sedang menunggu ditinjau.
                    @elseif($report->status === 'diproses') Laporan sedang dalam proses perbaikan.
                    @elseif($report->status === 'selesai') Laporan telah diselesaikan. Terima kasih!
                    @else Laporan ditolak. Lihat alasan di atas.
                    @endif
                </p>
            </div>
        </div>

        <div class="card">
            <div class="card-header"><i class="bi bi-gear me-2 text-primary"></i>Aksi</div>
            <div class="card-body d-grid gap-2">
                <a href="{{ route('user.reports.index') }}" class="btn btn-outline-secondary" style="border-radius:10px;">
                    <i class="bi bi-arrow-left me-2"></i>Kembali
                </a>
                @if($report->status === 'menunggu')
                    <a href="{{ route('user.reports.edit', $report) }}" class="btn btn-warning text-dark" style="border-radius:10px;font-weight:600;">
                        <i class="bi bi-pencil-square me-2"></i>Edit Laporan
                    </a>
                    <form action="{{ route('user.reports.destroy', $report) }}" method="POST"
                          onsubmit="return confirm('Yakin hapus laporan ini?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger w-100" style="border-radius:10px;">
                            <i class="bi bi-trash me-2"></i>Hapus Laporan
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
@if($report->latitude && $report->longitude)
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    const map = L.map('map-detail', { scrollWheelZoom: false })
                 .setView([{{ $report->latitude }}, {{ $report->longitude }}], 16);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);
    L.marker([{{ $report->latitude }}, {{ $report->longitude }}])
     .addTo(map)
     .bindPopup('<strong>{{ addslashes($report->title) }}</strong><br>{{ addslashes($report->location) }}')
     .openPopup();
</script>
@endif
@endpush