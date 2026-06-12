@extends('layouts.user')

@section('title', 'Peta Keluhan')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
<style>
    #map-container {
        height: 600px;
        border-radius: 20px;
        box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.02), 0 2px 4px -2px rgb(0 0 0 / 0.02);
        border: 1px solid #e2e8f0;
        z-index: 1;
    }
    .leaflet-popup-content-wrapper {
        border-radius: 16px;
        box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.05);
        border: 0;
        padding: 0;
        overflow: hidden;
    }
    .leaflet-popup-content {
        margin: 0;
        font-family: inherit;
    }
    .leaflet-popup-tip {
        box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.05);
    }
</style>
@endpush

@section('content')
<div class="mb-5">
    <h4 class="font-extrabold text-2xl tracking-tight text-slate-800 mb-1">
        Peta Keluhan Warga Gresik 📍
    </h4>
    <p class="text-slate-500 font-medium text-xs">
        Pantau persebaran laporan kerusakan fasilitas umum secara langsung di seluruh penjuru kota Gresik.
    </p>
</div>

<div class="row g-4">
    <!-- Map Canvas (col-lg-9) -->
    <div class="col-12 col-lg-9">
        <div id="map-container"></div>
    </div>

    <!-- Filter & Legend Pane (col-lg-3) -->
    <div class="col-12 col-lg-3">
        <div class="bg-white rounded-2xl border border-slate-200/60 p-4 shadow-sm h-full flex flex-col justify-content-between">
            <div>
                <h6 class="font-bold text-slate-800 mb-3"><i class="bi bi-funnel me-2 text-blue-600"></i>Filter Kategori</h6>
                
                <div class="space-y-2 mb-4">
                    <button class="w-100 text-start px-3 py-2.5 rounded-xl text-xs font-bold bg-blue-50 text-blue-600 border border-blue-100 flex items-center justify-between transition-all duration-200" id="filter-all" onclick="filterMap('all')">
                        <span>Semua Laporan</span>
                        <span class="badge bg-blue-600 rounded-pill" id="count-all">0</span>
                    </button>
                    <button class="w-100 text-start px-3 py-2.5 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-50 hover:text-slate-900 border border-slate-100 flex items-center justify-between transition-all duration-200" id="filter-jalan" onclick="filterMap('Jalan')">
                        <span class="flex items-center gap-2">
                            <span class="h-2.5 w-2.5 rounded-full bg-blue-500"></span>Jalan & Jembatan
                        </span>
                        <span class="badge bg-slate-100 text-slate-600 rounded-pill" id="count-jalan">0</span>
                    </button>
                    <button class="w-100 text-start px-3 py-2.5 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-50 hover:text-slate-900 border border-slate-100 flex items-center justify-between transition-all duration-200" id="filter-lampu" onclick="filterMap('Lampu Jalan')">
                        <span class="flex items-center gap-2">
                            <span class="h-2.5 w-2.5 rounded-full bg-amber-500"></span>Lampu Jalan (PJU)
                        </span>
                        <span class="badge bg-slate-100 text-slate-600 rounded-pill" id="count-lampu">0</span>
                    </button>
                    <button class="w-100 text-start px-3 py-2.5 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-50 hover:text-slate-900 border border-slate-100 flex items-center justify-between transition-all duration-200" id="filter-taman" onclick="filterMap('Taman')">
                        <span class="flex items-center gap-2">
                            <span class="h-2.5 w-2.5 rounded-full bg-emerald-500"></span>Taman & Fasum
                        </span>
                        <span class="badge bg-slate-100 text-slate-600 rounded-pill" id="count-taman">0</span>
                    </button>
                    <button class="w-100 text-start px-3 py-2.5 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-50 hover:text-slate-900 border border-slate-100 flex items-center justify-between transition-all duration-200" id="filter-drainase" onclick="filterMap('Drainase')">
                        <span class="flex items-center gap-2">
                            <span class="h-2.5 w-2.5 rounded-full bg-teal-500"></span>Drainase / Air
                        </span>
                        <span class="badge bg-slate-100 text-slate-600 rounded-pill" id="count-drainase">0</span>
                    </button>
                </div>

                <hr class="border-slate-100 my-4">

                <h6 class="font-bold text-slate-800 mb-3"><i class="bi bi-info-circle me-2 text-slate-500"></i>Legenda Status Pin</h6>
                <div class="space-y-2.5">
                    <div class="flex items-center gap-3 text-xs font-bold text-slate-500 px-1">
                        <span class="bg-amber-100 text-amber-600 rounded-xl h-7 w-7 flex items-center justify-center border border-amber-200/50">
                            <i class="bi bi-hourglass-split"></i>
                        </span>
                        <span>Menunggu Validasi</span>
                    </div>
                    <div class="flex items-center gap-3 text-xs font-bold text-slate-500 px-1">
                        <span class="bg-blue-100 text-blue-600 rounded-xl h-7 w-7 flex items-center justify-center border border-blue-200/50">
                            <i class="bi bi-gear-wide-connected"></i>
                        </span>
                        <span>Sedang Diproses</span>
                    </div>
                    <div class="flex items-center gap-3 text-xs font-bold text-slate-500 px-1">
                        <span class="bg-emerald-100 text-emerald-600 rounded-xl h-7 w-7 flex items-center justify-center border border-emerald-200/50">
                            <i class="bi bi-check-lg"></i>
                        </span>
                        <span>Selesai Diperbaiki</span>
                    </div>
                </div>
            </div>

            <div class="bg-blue-50/50 rounded-xl p-3 border border-blue-100/50 mt-4">
                <small class="text-xs text-blue-800 font-bold block mb-1">Butuh Bantuan?</small>
                <p class="text-[10px] text-slate-500 font-semibold mb-0 leading-normal">
                    Klik pin pada peta untuk melihat detail singkat aduan, lalu klik "Lihat Selengkapnya" untuk melacak status laporan tersebut.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    // Load reports data from Laravel controller
    const reports = @json($reports);

    // Initial Map Setup
    const map = L.map('map-container').setView([-7.1539, 112.6561], 13); // Focus on Gresik Center
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap'
    }).addTo(map);

    let activeFilter = 'all';
    let markersLayer = L.layerGroup().addTo(map);

    // Icon Colors
    function getMarkerColor(status) {
        if (status === 'selesai') return '#10b981'; // Green
        if (status === 'diproses') return '#0284c7'; // Blue
        return '#f59e0b'; // Amber (pending/rejected)
    }

    function renderMapMarkers() {
        markersLayer.clearLayers();

        let counts = { all: 0, jalan: 0, lampu: 0, taman: 0, drainase: 0 };

        reports.forEach(report => {
            // Count categories
            counts.all++;
            if (report.category.includes('Jalan') || report.category.includes('Jembatan')) counts.jalan++;
            else if (report.category.includes('Lampu')) counts.lampu++;
            else if (report.category.includes('Taman')) counts.taman++;
            else if (report.category.includes('Drainase')) counts.drainase++;

            // Apply filter
            if (activeFilter !== 'all') {
                if (activeFilter === 'Jalan' && !(report.category.includes('Jalan') || report.category.includes('Jembatan'))) return;
                if (activeFilter === 'Lampu Jalan' && !report.category.includes('Lampu')) return;
                if (activeFilter === 'Taman' && !report.category.includes('Taman')) return;
                if (activeFilter === 'Drainase' && !report.category.includes('Drainase')) return;
            }

            const color = getMarkerColor(report.status);

            // Create customized SVG marker pin
            const svgIcon = L.divIcon({
                html: `<svg width="30" height="42" viewBox="0 0 30 42" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M15 0C6.71573 0 0 6.71573 0 15C0 24.375 15 42 15 42C15 42 30 24.375 30 15C30 6.71573 23.2843 0 15 0ZM15 20.5C11.9624 20.5 9.5 18.0376 9.5 15C9.5 11.9624 11.9624 9.5 15 9.5C18.0376 9.5 20.5 11.9624 20.5 15C20.5 18.0376 18.0376 20.5 15 20.5Z" fill="${color}"/>
                       </svg>`,
                className: "",
                iconSize: [30, 42],
                iconAnchor: [15, 42],
                popupAnchor: [0, -42]
            });

            const popupContent = `
                <div class="p-3 bg-white max-w-[280px]">
                    <span class="status-badge status-${report.status} mb-2 text-[10px] py-0.5 px-2">
                        ${report.status_label}
                    </span>
                    <h6 class="font-bold text-slate-800 text-xs mb-1.5 line-clamp-2">${report.title}</h6>
                    <div class="text-[10px] text-slate-400 font-bold mb-1 uppercase tracking-wider">${report.category}</div>
                    <div class="text-[11px] text-slate-500 font-semibold mb-3 truncate"><i class="bi bi-geo-alt me-1"></i>${report.location}</div>
                    <a href="${report.url}" class="btn btn-primary btn-sm w-100 rounded-lg text-xs font-bold py-1.5 text-decoration-none">
                        Lihat Selengkapnya <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                </div>
            `;

            L.marker([report.latitude, report.longitude], { icon: svgIcon })
                .bindPopup(popupContent)
                .addTo(markersLayer);
        });

        // Update counts badges in HTML
        document.getElementById('count-all').innerText = counts.all;
        document.getElementById('count-jalan').innerText = counts.jalan;
        document.getElementById('count-lampu').innerText = counts.lampu;
        document.getElementById('count-taman').innerText = counts.taman;
        document.getElementById('count-drainase').innerText = counts.drainase;
    }

    function filterMap(category) {
        activeFilter = category;
        
        // Toggle active style class on filter buttons
        const categoriesIds = ['all', 'jalan', 'lampu', 'taman', 'drainase'];
        categoriesIds.forEach(id => {
            const btn = document.getElementById(`filter-${id}`);
            if (id === category.toLowerCase() || (category === 'Lampu Jalan' && id === 'lampu') || (category === 'all' && id === 'all')) {
                btn.className = "w-100 text-start px-3 py-2.5 rounded-xl text-xs font-bold bg-blue-50 text-blue-600 border border-blue-100 flex items-center justify-between transition-all duration-200";
            } else {
                btn.className = "w-100 text-start px-3 py-2.5 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-50 hover:text-slate-900 border border-slate-100 flex items-center justify-between transition-all duration-200";
            }
        });

        renderMapMarkers();
    }

    // Initialize markers
    renderMapMarkers();
</script>
@endpush
