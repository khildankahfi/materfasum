@extends('layouts.user')

@section('title', 'Beranda')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
<style>
    #dashboard-map {
        height: 340px;
        border-radius: 16px;
        z-index: 1;
    }
    .leaflet-popup-content-wrapper {
        border-radius: 14px;
        box-shadow: 0 8px 24px rgba(0,0,0,.09);
        border: 0;
        padding: 0;
        overflow: hidden;
    }
    .leaflet-popup-content { margin: 0; font-family: inherit; }
    .map-stat-pill {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: .3rem .75rem;
        border-radius: 99px;
        font-size: .72rem;
        font-weight: 700;
    }
</style>
@endpush

@section('content')

{{-- Welcome Header --}}
<div class="mb-5">
    <h4 style="font-size:1.5rem;font-weight:700;color:#111827;margin-bottom:.35rem;">
        Selamat Datang Kembali, {{ explode(' ', auth()->user()->name)[0] }}! 👋
    </h4>
    <p style="color:#6b7280;font-size:.9rem;margin:0;">
        Pilih kategori fasilitas umum di bawah ini untuk memulai pengaduan cepat.
    </p>
</div>

<div class="row g-4">

    {{-- Left Column: Category Cards --}}
    <div class="col-12 col-lg-7">

        <h6 style="font-weight:600;color:#374151;margin-bottom:1rem;font-size:.95rem;">Pilih Kategori Fasilitas</h6>

        <div class="row g-3">

            {{-- Jalan & Jembatan --}}
            <div class="col-6">
                <div class="card p-4 h-100" style="transition:border-color .15s,box-shadow .15s;cursor:pointer;"
                     onmouseenter="this.style.borderColor='#93c5fd';this.style.boxShadow='0 2px 8px rgba(37,99,235,.06)'"
                     onmouseleave="this.style.borderColor='#e5e7eb';this.style.boxShadow='none'">
                    <div style="font-size:1.3rem;margin-bottom:.6rem;">🛣️ Jalan &amp; Jembatan</div>
                    <div style="font-size:.8rem;color:#6b7280;margin-bottom:1rem;line-height:1.6;">
                        Aspal ambles<br>lubang jalan
                    </div>
                    <a href="{{ route('user.reports.create', ['category' => 'jalan_jembatan']) }}"
                       style="color:#2563eb;font-size:.83rem;font-weight:500;text-decoration:none;">
                        Laporkan →
                    </a>
                </div>
            </div>

            {{-- Penerangan Jalan --}}
            <div class="col-6">
                <div class="card p-4 h-100" style="transition:border-color .15s,box-shadow .15s;cursor:pointer;"
                     onmouseenter="this.style.borderColor='#93c5fd';this.style.boxShadow='0 2px 8px rgba(37,99,235,.06)'"
                     onmouseleave="this.style.borderColor='#e5e7eb';this.style.boxShadow='none'">
                    <div style="font-size:1.3rem;margin-bottom:.6rem;">💡 Penerangan Jalan (PJU)</div>
                    <div style="font-size:.8rem;color:#6b7280;margin-bottom:1rem;line-height:1.6;">
                        Lampu jalan mati<br>kabel menjuntai
                    </div>
                    <a href="{{ route('user.reports.create', ['category' => 'penerangan_jalan']) }}"
                       style="color:#2563eb;font-size:.83rem;font-weight:500;text-decoration:none;">
                        Laporkan →
                    </a>
                </div>
            </div>

            {{-- Taman & Fasum --}}
            <div class="col-6">
                <div class="card p-4 h-100" style="transition:border-color .15s,box-shadow .15s;cursor:pointer;"
                     onmouseenter="this.style.borderColor='#93c5fd';this.style.boxShadow='0 2px 8px rgba(37,99,235,.06)'"
                     onmouseleave="this.style.borderColor='#e5e7eb';this.style.boxShadow='none'">
                    <div style="font-size:1.3rem;margin-bottom:.6rem;">🌳 Taman &amp; Fasum</div>
                    <div style="font-size:.8rem;color:#6b7280;margin-bottom:1rem;line-height:1.6;">
                        Fasilitas taman rusak<br>pohon rawan tumbang
                    </div>
                    <a href="{{ route('user.reports.create', ['category' => 'taman']) }}"
                       style="color:#2563eb;font-size:.83rem;font-weight:500;text-decoration:none;">
                        Laporkan →
                    </a>
                </div>
            </div>

            {{-- Saluran Air --}}
            <div class="col-6">
                <div class="card p-4 h-100" style="transition:border-color .15s,box-shadow .15s;cursor:pointer;"
                     onmouseenter="this.style.borderColor='#93c5fd';this.style.boxShadow='0 2px 8px rgba(37,99,235,.06)'"
                     onmouseleave="this.style.borderColor='#e5e7eb';this.style.boxShadow='none'">
                    <div style="font-size:1.3rem;margin-bottom:.6rem;">💧 Saluran Air / Drainase</div>
                    <div style="font-size:.8rem;color:#6b7280;margin-bottom:1rem;line-height:1.6;">
                        Got tersumbat<br>banjir
                    </div>
                    <a href="{{ route('user.reports.create', ['category' => 'drainase']) }}"
                       style="color:#2563eb;font-size:.83rem;font-weight:500;text-decoration:none;">
                        Laporkan →
                    </a>
                </div>
            </div>

        </div>
    </div>

    {{-- Right Column: Stats + Recent --}}
    <div class="col-12 col-lg-5">

        {{-- Ringkasan Laporan --}}
        <div class="card p-4 mb-3">
            <h6 style="font-weight:600;color:#111827;margin-bottom:1rem;font-size:.95rem;">Ringkasan Laporan Saya</h6>
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <span style="color:#374151;font-size:.875rem;">
                    <strong style="font-size:1rem;">{{ $totalReports }}</strong> Total Aduan
                </span>
                <div class="d-flex flex-column align-items-end gap-1" style="font-size:.82rem;">
                    @if($inProgressReports > 0)
                        <a href="{{ route('user.reports.index', ['status' => 'diproses']) }}"
                           style="color:#2563eb;text-decoration:none;font-weight:500;">
                            {{ $inProgressReports }} Sedang Diproses
                        </a>
                    @endif
                    @if($completedReports > 0)
                        <a href="{{ route('user.reports.index', ['status' => 'selesai']) }}"
                           style="color:#2563eb;text-decoration:none;font-weight:500;">
                            {{ $completedReports }} Selesai Diperbaiki
                        </a>
                    @endif
                    @if($pendingReports > 0)
                        <a href="{{ route('user.reports.index', ['status' => 'menunggu']) }}"
                           style="color:#d97706;text-decoration:none;font-weight:500;">
                            {{ $pendingReports }} Menunggu
                        </a>
                    @endif
                    @if($totalReports === 0)
                        <span style="color:#9ca3af;">Belum ada laporan</span>
                    @endif
                </div>
            </div>
        </div>

        {{-- Perbaikan Terbaru --}}
        <div class="card p-4">
            <h6 style="font-weight:600;color:#111827;margin-bottom:1rem;font-size:.95rem;">Perbaikan Terbaru di Sekitarmu</h6>

            @if($recentActivity->isEmpty())
                <div style="text-align:center;padding:1.5rem 0;color:#9ca3af;font-size:.85rem;">
                    <i class="bi bi-inbox d-block mb-2" style="font-size:1.5rem;"></i>
                    Belum ada aktivitas terbaru
                </div>
            @else
                <div style="display:flex;flex-direction:column;gap:1rem;">
                    @foreach($recentActivity as $report)
                        <a href="{{ route('user.reports.show', $report) }}" style="text-decoration:none;color:inherit;">
                            <div style="display:flex;flex-direction:column;gap:.3rem;padding:.5rem;border-radius:8px;transition:background .15s;"
                                 onmouseenter="this.style.background='#f9fafb'"
                                 onmouseleave="this.style.background='transparent'">
                                <div style="display:flex;align-items:center;gap:.6rem;flex-wrap:wrap;">
                                    {{-- Status badge --}}
                                    @if($report->status === 'selesai')
                                        <span style="display:inline-flex;align-items:center;gap:4px;padding:.2em .6em;border-radius:4px;background:#d1fae5;color:#059669;font-size:.72rem;font-weight:600;">
                                            ✓ SELESAI
                                        </span>
                                    @elseif($report->status === 'diproses')
                                        <span style="display:inline-flex;align-items:center;gap:4px;padding:.2em .6em;border-radius:4px;background:#dbeafe;color:#2563eb;font-size:.72rem;font-weight:600;">
                                            ⟳ PROSES
                                        </span>
                                    @elseif($report->status === 'menunggu')
                                        <span style="display:inline-flex;align-items:center;gap:4px;padding:.2em .6em;border-radius:4px;background:#fef3c7;color:#d97706;font-size:.72rem;font-weight:600;">
                                            ◷ MENUNGGU
                                        </span>
                                    @else
                                        <span style="display:inline-flex;align-items:center;gap:4px;padding:.2em .6em;border-radius:4px;background:#fee2e2;color:#dc2626;font-size:.72rem;font-weight:600;">
                                            ✕ DITOLAK
                                        </span>
                                    @endif
                                    <span style="color:#9ca3af;font-size:.78rem;">{{ $report->updated_at->diffForHumans() }}</span>
                                </div>
                                <div style="font-size:.85rem;color:#374151;font-weight:500;">
                                    {{ $report->title }}
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>

                @if($totalReports > 3)
                    <div style="margin-top:1rem;padding-top:1rem;border-top:1px solid #f3f4f6;">
                        <a href="{{ route('user.reports.index') }}"
                           style="color:#2563eb;font-size:.83rem;font-weight:500;text-decoration:none;">
                            Lihat semua laporan →
                        </a>
                    </div>
                @endif
            @endif
        </div>

    </div>
</div>

{{-- ── GIS Map Section ── --}}
<div class="mt-5">
    <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
        <div>
            <h5 style="font-size:1.1rem;font-weight:700;color:#111827;margin-bottom:.2rem;">
                🗺️ Visualisasi Peta GIS Gresik
            </h5>
            <p style="color:#6b7280;font-size:.82rem;margin:0;">
                Sebaran laporan kerusakan fasilitas umum secara real-time di seluruh Kabupaten Gresik.
            </p>
        </div>
        <a href="{{ route('user.reports.map') }}"
           class="btn btn-sm"
           style="background:#eff6ff;color:#2563eb;border:1px solid #bfdbfe;font-size:.8rem;font-weight:600;border-radius:10px;padding:.4rem .9rem;">
            <i class="bi bi-arrows-fullscreen me-1"></i> Buka Peta Penuh
        </a>
    </div>

    <div class="card" style="border-radius:18px;overflow:hidden;border:1px solid #e5e7eb;">

        {{-- Map Stats Bar --}}
        <div style="background:#f8fafc;border-bottom:1px solid #e5e7eb;padding:.75rem 1.25rem;display:flex;align-items:center;gap:.6rem;flex-wrap:wrap;">
            <span class="map-stat-pill" style="background:#eff6ff;color:#2563eb;">
                <i class="bi bi-pin-map-fill"></i>
                <span id="stat-total">0</span> Laporan
            </span>
            <span class="map-stat-pill" style="background:#fef3c7;color:#d97706;">
                <i class="bi bi-hourglass-split"></i>
                <span id="stat-menunggu">0</span> Menunggu
            </span>
            <span class="map-stat-pill" style="background:#dbeafe;color:#2563eb;">
                <i class="bi bi-gear-wide-connected"></i>
                <span id="stat-diproses">0</span> Diproses
            </span>
            <span class="map-stat-pill" style="background:#d1fae5;color:#059669;">
                <i class="bi bi-check-circle-fill"></i>
                <span id="stat-selesai">0</span> Selesai
            </span>
            <span class="ms-auto" style="font-size:.72rem;color:#9ca3af;font-weight:500;">
                <i class="bi bi-clock me-1"></i>Diperbarui otomatis
            </span>
        </div>

        {{-- Map Canvas --}}
        <div id="dashboard-map"></div>

        {{-- Legend --}}
        <div style="background:#f8fafc;border-top:1px solid #e5e7eb;padding:.6rem 1.25rem;display:flex;align-items:center;gap:1.25rem;flex-wrap:wrap;">
            <span style="font-size:.72rem;font-weight:700;color:#6b7280;text-transform:uppercase;letter-spacing:.05em;">Legend:</span>
            <span style="display:inline-flex;align-items:center;gap:5px;font-size:.75rem;color:#374151;font-weight:600;">
                <span style="width:10px;height:10px;border-radius:50%;background:#f59e0b;display:inline-block;"></span> Menunggu
            </span>
            <span style="display:inline-flex;align-items:center;gap:5px;font-size:.75rem;color:#374151;font-weight:600;">
                <span style="width:10px;height:10px;border-radius:50%;background:#3b82f6;display:inline-block;"></span> Diproses
            </span>
            <span style="display:inline-flex;align-items:center;gap:5px;font-size:.75rem;color:#374151;font-weight:600;">
                <span style="width:10px;height:10px;border-radius:50%;background:#10b981;display:inline-block;"></span> Selesai
            </span>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    const mapReports = @json($mapReports);

    // Init map — center on Gresik
    const dashMap = L.map('dashboard-map', { zoomControl: true, scrollWheelZoom: false })
        .setView([-7.1539, 112.6561], 12);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(dashMap);

    // Color per status
    function pinColor(status) {
        if (status === 'selesai')  return '#10b981';
        if (status === 'diproses') return '#3b82f6';
        return '#f59e0b';
    }

    function makePin(color) {
        return L.divIcon({
            html: `<svg width="26" height="36" viewBox="0 0 30 42" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M15 0C6.71573 0 0 6.71573 0 15C0 24.375 15 42 15 42C15 42 30 24.375 30 15C30 6.71573 23.2843 0 15 0ZM15 20.5C11.9624 20.5 9.5 18.0376 9.5 15C9.5 11.9624 11.9624 9.5 15 9.5C18.0376 9.5 20.5 11.9624 20.5 15C20.5 18.0376 18.0376 20.5 15 20.5Z" fill="${color}"/>
                   </svg>`,
            className: '',
            iconSize: [26, 36],
            iconAnchor: [13, 36],
            popupAnchor: [0, -38]
        });
    }

    // Stats counters
    let counts = { total: 0, menunggu: 0, diproses: 0, selesai: 0 };

    const bounds = [];

    mapReports.forEach(r => {
        counts.total++;
        if (r.status === 'menunggu') counts.menunggu++;
        else if (r.status === 'diproses') counts.diproses++;
        else if (r.status === 'selesai') counts.selesai++;

        const color = pinColor(r.status);
        const popup = `
            <div style="padding:12px 14px;min-width:220px;font-family:inherit;">
                <span style="display:inline-flex;align-items:center;gap:4px;padding:.2em .65em;border-radius:99px;
                      background:${r.status==='selesai'?'#d1fae5':r.status==='diproses'?'#dbeafe':'#fef3c7'};
                      color:${r.status==='selesai'?'#059669':r.status==='diproses'?'#2563eb':'#d97706'};
                      font-size:.68rem;font-weight:700;margin-bottom:8px;">
                    ${r.status_label}
                </span>
                <div style="font-weight:700;font-size:.82rem;color:#111827;margin-bottom:4px;line-height:1.4;">${r.title}</div>
                <div style="font-size:.72rem;color:#6b7280;margin-bottom:4px;font-weight:600;">${r.category}</div>
                <div style="font-size:.72rem;color:#9ca3af;margin-bottom:10px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:200px;">
                    <i class="bi bi-geo-alt"></i> ${r.location}
                </div>
                <a href="${r.url}"
                   style="display:block;text-align:center;background:#2563eb;color:#fff;font-size:.75rem;font-weight:700;
                          border-radius:8px;padding:.4rem .75rem;text-decoration:none;">
                    Lihat Detail <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>`;

        L.marker([r.latitude, r.longitude], { icon: makePin(color) })
            .bindPopup(popup)
            .addTo(dashMap);

        bounds.push([r.latitude, r.longitude]);
    });

    // Update stat pills
    document.getElementById('stat-total').textContent    = counts.total;
    document.getElementById('stat-menunggu').textContent = counts.menunggu;
    document.getElementById('stat-diproses').textContent = counts.diproses;
    document.getElementById('stat-selesai').textContent  = counts.selesai;

    // Fit map to markers if any, else default Gresik view
    if (bounds.length > 0) {
        dashMap.fitBounds(bounds, { padding: [30, 30], maxZoom: 14 });
    }
</script>
@endpush
