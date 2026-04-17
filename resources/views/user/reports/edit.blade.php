@extends('layouts.user')

@section('title', 'Edit Laporan')
@section('page-title', 'Edit Laporan')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('user.reports.index') }}">Laporan</a></li>
    <li class="breadcrumb-item"><a href="{{ route('user.reports.show', $report) }}">Detail</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
<style>
    #map {
        height: 280px;
        border-radius: 10px;
        z-index: 0;
        border: 1px solid #e2e8f0;
    }
    .photo-drop-area {
        border: 2px dashed #e2e8f0;
        border-radius: 10px;
        padding: 1.5rem;
        text-align: center;
        cursor: pointer;
        transition: border-color .2s, background .2s;
    }
    .photo-drop-area:hover, .photo-drop-area.dragover {
        border-color: #2563eb;
        background: #eff6ff;
    }
    #photoPreview {
        max-height: 200px;
        border-radius: 8px;
        object-fit: cover;
    }
    .remove-photo-btn {
        position: absolute;
        top: 6px; right: 6px;
        background: rgba(239,68,68,.9);
        border: none;
        border-radius: 50%;
        width: 28px; height: 28px;
        color: #fff;
        display: flex; align-items: center; justify-content: center;
        cursor: pointer;
        font-size: .8rem;
    }
    .current-photo-wrap { position: relative; display: inline-block; }
</style>
@endpush

@section('content')

<div class="row justify-content-center">
    <div class="col-12 col-lg-8">
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <span><i class="bi bi-pencil-square me-2 text-primary"></i>Edit Laporan</span>
                <span class="badge badge-menunggu px-2 py-1" style="font-size:.78rem;">Menunggu</span>
            </div>
            <div class="card-body">

                @if($errors->any())
                    <div class="alert alert-danger mb-4" style="border-radius:10px;">
                        <i class="bi bi-exclamation-circle-fill me-2"></i><strong>Terjadi kesalahan:</strong>
                        <ul class="mb-0 mt-1 ps-3">
                            @foreach($errors->all() as $error)
                                <li style="font-size:.88rem;">{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('user.reports.update', $report) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    {{-- Judul --}}
                    <div class="mb-4">
                        <label class="form-label fw-600" style="font-size:.88rem;">
                            Judul Laporan <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="title"
                               class="form-control @error('title') is-invalid @enderror"
                               placeholder="Contoh: Lampu Jalan Padam di Jl. Sudirman"
                               value="{{ old('title', $report->title) }}" style="border-radius:10px;">
                        @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    {{-- Kategori & Lokasi --}}
                    <div class="row g-3 mb-4">
                        <div class="col-12 col-sm-6">
                            <label class="form-label fw-600" style="font-size:.88rem;">
                                Kategori <span class="text-danger">*</span>
                            </label>
                            <select name="category" class="form-select @error('category') is-invalid @enderror" style="border-radius:10px;">
                                <option value="">Pilih Kategori</option>
                                @foreach($categories as $key => $label)
                                    <option value="{{ $key }}" {{ old('category', $report->category) === $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('category')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12 col-sm-6">
                            <label class="form-label fw-600" style="font-size:.88rem;">
                                Lokasi <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="location" id="location"
                                   class="form-control @error('location') is-invalid @enderror"
                                   placeholder="Masukkan alamat lokasi"
                                   value="{{ old('location', $report->location) }}" style="border-radius:10px;">
                            @error('location')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    {{-- Peta --}}
                    <div class="mb-4">
                        <label class="form-label fw-600" style="font-size:.88rem;">
                            Tandai Lokasi di Peta
                            <span class="text-muted fw-normal">(opsional, klik peta untuk mengubah)</span>
                        </label>
                        <div id="map" class="mb-2"></div>
                        <div class="d-flex align-items-center gap-3">
                            <small class="text-muted" id="coordsLabel">
                                @if($report->latitude && $report->longitude)
                                    <i class="bi bi-geo-alt-fill text-danger me-1"></i>
                                    Lat: {{ number_format($report->latitude, 5) }}, Lng: {{ number_format($report->longitude, 5) }}
                                @else
                                    <i class="bi bi-geo-alt me-1"></i>Belum ada titik dipilih
                                @endif
                            </small>
                            <button type="button" class="btn btn-sm btn-outline-secondary ms-auto"
                                    style="border-radius:8px;font-size:.78rem;" onclick="resetMap()">
                                <i class="bi bi-x-circle me-1"></i>Reset Pin
                            </button>
                        </div>
                        <input type="hidden" name="latitude"  id="latitude"  value="{{ old('latitude', $report->latitude) }}">
                        <input type="hidden" name="longitude" id="longitude" value="{{ old('longitude', $report->longitude) }}">
                    </div>

                    {{-- Deskripsi --}}
                    <div class="mb-4">
                        <label class="form-label fw-600" style="font-size:.88rem;">
                            Deskripsi Kerusakan <span class="text-danger">*</span>
                        </label>
                        <textarea name="description" rows="5"
                                  class="form-control @error('description') is-invalid @enderror"
                                  placeholder="Jelaskan kerusakan secara detail (minimal 20 karakter)"
                                  style="border-radius:10px;">{{ old('description', $report->description) }}</textarea>
                        <div class="d-flex justify-content-end mt-1">
                            <small class="text-muted" id="charCount">0 karakter</small>
                        </div>
                        @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    {{-- Foto --}}
                    <div class="mb-4">
                        <label class="form-label fw-600" style="font-size:.88rem;">
                            Foto Kerusakan <small class="text-muted fw-normal">(opsional, maks. 5MB)</small>
                        </label>

                        @if($report->photo && !old('remove_photo'))
                            {{-- Foto saat ini --}}
                            <div class="mb-3 p-3 rounded" style="background:#f8fafc;border:1px solid #e2e8f0;">
                                <div class="text-muted mb-2" style="font-size:.78rem;text-transform:uppercase;letter-spacing:.5px;">Foto Saat Ini</div>
                                <div class="current-photo-wrap">
                                    <img src="{{ Storage::url($report->photo) }}" alt="Foto saat ini"
                                         class="rounded" style="max-height:160px;cursor:pointer;border-radius:8px!important;"
                                         onclick="window.open(this.src,'_blank')">
                                </div>
                                <div class="mt-2">
                                    <div class="form-check">
                                        <input type="checkbox" name="remove_photo" value="1" id="removePhoto"
                                               class="form-check-input" onchange="toggleRemovePhoto(this)">
                                        <label class="form-check-label text-danger" for="removePhoto" style="font-size:.85rem;">
                                            Hapus foto ini
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="text-muted mb-2" style="font-size:.83rem;">
                                <i class="bi bi-arrow-repeat me-1"></i>Upload foto baru untuk mengganti (opsional):
                            </div>
                        @endif

                        <div class="photo-drop-area" id="dropArea" onclick="document.getElementById('photoInput').click()">
                            <div id="previewWrapper" class="position-relative d-inline-block" style="display:none;">
                                <img id="photoPreview" src="" alt="Preview" class="d-block mx-auto">
                                <button type="button" class="remove-photo-btn" onclick="removeNewPhoto(event)">
                                    <i class="bi bi-x"></i>
                                </button>
                            </div>
                            <div id="uploadPlaceholder">
                                <i class="bi bi-cloud-upload fs-2 text-muted d-block mb-1"></i>
                                <div class="fw-500" style="font-size:.88rem;">Klik atau seret foto ke sini</div>
                                <small class="text-muted">JPG, PNG, WEBP — maks. 5MB</small>
                            </div>
                            <input type="file" id="photoInput" name="photo" accept="image/*"
                                   class="@error('photo') is-invalid @enderror" style="display:none;">
                        </div>
                        @error('photo')<div class="text-danger mt-1" style="font-size:.83rem;">{{ $message }}</div>@enderror
                    </div>

                    <div class="d-flex gap-3 pt-2">
                        <button type="submit" class="btn btn-primary px-4" style="border-radius:10px;font-weight:600;">
                            <i class="bi bi-check-lg me-2"></i>Simpan Perubahan
                        </button>
                        <a href="{{ route('user.reports.show', $report) }}" class="btn btn-outline-secondary" style="border-radius:10px;">
                            Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Info --}}
    <div class="col-12 col-lg-4">
        <div class="card border-warning">
            <div class="card-header text-warning" style="background:#fffbeb;">
                <i class="bi bi-exclamation-triangle me-2"></i>Perhatian
            </div>
            <div class="card-body" style="font-size:.85rem;">
                <ul class="list-unstyled mb-0">
                    <li class="d-flex gap-2 mb-2">
                        <i class="bi bi-check-circle-fill text-success mt-1 flex-shrink-0"></i>
                        <span class="text-muted">Laporan hanya bisa diedit selama berstatus <strong>Menunggu</strong>.</span>
                    </li>
                    <li class="d-flex gap-2 mb-2">
                        <i class="bi bi-check-circle-fill text-success mt-1 flex-shrink-0"></i>
                        <span class="text-muted">Setelah admin mulai memproses, laporan tidak bisa diubah lagi.</span>
                    </li>
                    <li class="d-flex gap-2">
                        <i class="bi bi-check-circle-fill text-success mt-1 flex-shrink-0"></i>
                        <span class="text-muted">Pastikan semua informasi sudah benar sebelum menyimpan.</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    // ── Leaflet Map ──
    const initLat = {{ old('latitude', $report->latitude ?? -7.2575) }};
    const initLng = {{ old('longitude', $report->longitude ?? 112.7521) }};
    const hasPin  = {{ ($report->latitude && $report->longitude) ? 'true' : 'false' }};

    const map = L.map('map').setView([initLat, initLng], hasPin ? 15 : 13);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    let marker = null;

    if (hasPin) {
        marker = L.marker([initLat, initLng]).addTo(map);
    }

    map.on('click', function(e) {
        const { lat, lng } = e.latlng;
        if (marker) map.removeLayer(marker);
        marker = L.marker([lat, lng]).addTo(map);
        document.getElementById('latitude').value  = lat.toFixed(7);
        document.getElementById('longitude').value = lng.toFixed(7);
        updateCoordsLabel(lat, lng);
    });

    function updateCoordsLabel(lat, lng) {
        document.getElementById('coordsLabel').innerHTML =
            `<i class="bi bi-geo-alt-fill text-danger me-1"></i>Lat: ${parseFloat(lat).toFixed(5)}, Lng: ${parseFloat(lng).toFixed(5)}`;
    }

    function resetMap() {
        if (marker) { map.removeLayer(marker); marker = null; }
        document.getElementById('latitude').value  = '';
        document.getElementById('longitude').value = '';
        document.getElementById('coordsLabel').innerHTML =
            '<i class="bi bi-geo-alt me-1"></i>Belum ada titik dipilih';
    }

    // ── Photo Preview ──
    const photoInput  = document.getElementById('photoInput');
    const previewWrap = document.getElementById('previewWrapper');
    const placeholder = document.getElementById('uploadPlaceholder');
    const preview     = document.getElementById('photoPreview');
    const dropArea    = document.getElementById('dropArea');

    photoInput.addEventListener('change', function() { showPreview(this.files[0]); });

    dropArea.addEventListener('dragover', e => { e.preventDefault(); dropArea.classList.add('dragover'); });
    dropArea.addEventListener('dragleave', () => dropArea.classList.remove('dragover'));
    dropArea.addEventListener('drop', e => {
        e.preventDefault();
        dropArea.classList.remove('dragover');
        const file = e.dataTransfer.files[0];
        if (file && file.type.startsWith('image/')) {
            const dt = new DataTransfer();
            dt.items.add(file);
            photoInput.files = dt.files;
            showPreview(file);
        }
    });

    function showPreview(file) {
        if (!file) return;
        const reader = new FileReader();
        reader.onload = e => {
            preview.src = e.target.result;
            previewWrap.style.display = 'inline-block';
            placeholder.style.display = 'none';
        };
        reader.readAsDataURL(file);
    }

    function removeNewPhoto(e) {
        e.stopPropagation();
        photoInput.value = '';
        previewWrap.style.display = 'none';
        placeholder.style.display = 'block';
    }

    function toggleRemovePhoto(cb) {
        dropArea.style.opacity = cb.checked ? '0.4' : '1';
        dropArea.style.pointerEvents = cb.checked ? 'none' : 'auto';
    }

    // ── Char counter ──
    const desc    = document.querySelector('textarea[name="description"]');
    const counter = document.getElementById('charCount');
    function updateCounter() {
        const len = desc.value.length;
        counter.textContent = len + ' karakter';
        counter.style.color = len < 20 ? '#ef4444' : '#64748b';
    }
    desc.addEventListener('input', updateCounter);
    updateCounter();
</script>
@endpush