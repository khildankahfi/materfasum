@extends('layouts.user')

@section('title', 'Buat Laporan')
@section('page-title', 'Buat Laporan Baru')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('user.reports.index') }}">Laporan</a></li>
    <li class="breadcrumb-item active">Buat Laporan</li>
@endsection

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
<style>
    #map { height: 280px; border-radius: 10px; z-index: 0; border: 1px solid #e2e8f0; }
    .photo-drop-area {
        border: 2px dashed #e2e8f0; border-radius: 10px;
        padding: 1.5rem; text-align: center; cursor: pointer;
        transition: border-color .2s, background .2s;
    }
    .photo-drop-area:hover, .photo-drop-area.dragover { border-color: #2563eb; background: #eff6ff; }
    .photo-preview-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(90px, 1fr)); gap: .5rem; margin-top: .75rem; }
    .photo-thumb-wrap { position: relative; }
    .photo-thumb-wrap img { width: 100%; height: 80px; object-fit: cover; border-radius: 8px; display: block; }
    .photo-thumb-wrap .remove-btn {
        position: absolute; top: 3px; right: 3px;
        background: rgba(239,68,68,.9); border: none; border-radius: 50%;
        width: 22px; height: 22px; color: #fff;
        display: flex; align-items: center; justify-content: center;
        cursor: pointer; font-size: .7rem;
    }
</style>
@endpush

@section('content')

<div class="row justify-content-center">
    <div class="col-12 col-lg-8">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-plus-circle me-2 text-primary"></i>Form Laporan Kerusakan Fasilitas
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

                <form action="{{ route('user.reports.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-4">
                        <label class="form-label fw-600" style="font-size:.88rem;">Judul Laporan <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                               placeholder="Contoh: Lampu Jalan Padam di Jl. Sudirman"
                               value="{{ old('title') }}" style="border-radius:10px;">
                        @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-12 col-sm-6">
                            <label class="form-label fw-600" style="font-size:.88rem;">Kategori <span class="text-danger">*</span></label>
                            <select name="category" class="form-select @error('category') is-invalid @enderror" style="border-radius:10px;">
                                <option value="">Pilih Kategori</option>
                                @foreach($categories as $key => $label)
                                    <option value="{{ $key }}" {{ old('category') === $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('category')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12 col-sm-6">
                            <label class="form-label fw-600" style="font-size:.88rem;">Lokasi <span class="text-danger">*</span></label>
                            <input type="text" name="location" id="location"
                                   class="form-control @error('location') is-invalid @enderror"
                                   placeholder="Masukkan alamat lokasi"
                                   value="{{ old('location') }}" style="border-radius:10px;">
                            @error('location')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    {{-- Peta --}}
                    <div class="mb-4">
                        <label class="form-label fw-600" style="font-size:.88rem;">
                            Tandai Lokasi di Peta <span class="text-muted fw-normal">(opsional, klik peta untuk menandai)</span>
                        </label>
                        <div id="map" class="mb-2"></div>
                        <div class="d-flex align-items-center gap-3">
                            <small class="text-muted" id="coordsLabel"><i class="bi bi-geo-alt me-1"></i>Belum ada titik dipilih</small>
                            <button type="button" class="btn btn-sm btn-outline-secondary ms-auto" style="border-radius:8px;font-size:.78rem;" onclick="resetMap()">
                                <i class="bi bi-x-circle me-1"></i>Reset Pin
                            </button>
                        </div>
                        <input type="hidden" name="latitude"  id="latitude"  value="{{ old('latitude') }}">
                        <input type="hidden" name="longitude" id="longitude" value="{{ old('longitude') }}">
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-600" style="font-size:.88rem;">Deskripsi Kerusakan <span class="text-danger">*</span></label>
                        <textarea name="description" rows="5"
                                  class="form-control @error('description') is-invalid @enderror"
                                  placeholder="Jelaskan kerusakan secara detail (minimal 20 karakter)"
                                  style="border-radius:10px;">{{ old('description') }}</textarea>
                        <div class="d-flex justify-content-end mt-1">
                            <small class="text-muted" id="charCount">0 karakter</small>
                        </div>
                        @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    {{-- Multiple Photos --}}
                    <div class="mb-4">
                        <label class="form-label fw-600" style="font-size:.88rem;">
                            Foto Kerusakan <small class="text-muted fw-normal">(opsional, maks. 5 foto, masing-masing maks. 5MB)</small>
                        </label>
                        <div class="photo-drop-area" id="dropArea" onclick="document.getElementById('photoInput').click()">
                            <i class="bi bi-cloud-upload fs-2 text-muted d-block mb-1"></i>
                            <div class="fw-500" style="font-size:.88rem;">Klik atau seret foto ke sini</div>
                            <small class="text-muted">JPG, PNG, WEBP — maks. 5 foto</small>
                            <input type="file" id="photoInput" name="photos[]" accept="image/*"
                                   multiple style="display:none;"
                                   class="@error('photos') is-invalid @enderror @error('photos.*') is-invalid @enderror">
                        </div>
                        <div class="photo-preview-grid" id="previewGrid"></div>
                        <div class="d-flex justify-content-between mt-1">
                            @error('photos')<div class="text-danger" style="font-size:.83rem;">{{ $message }}</div>@enderror
                            @error('photos.*')<div class="text-danger" style="font-size:.83rem;">{{ $message }}</div>@enderror
                            <small class="text-muted ms-auto" id="photoCount">0 / 5 foto dipilih</small>
                        </div>
                    </div>

                    <div class="d-flex gap-3 pt-2">
                        <button type="submit" class="btn btn-primary px-4" style="border-radius:10px;font-weight:600;">
                            <i class="bi bi-send me-2"></i>Kirim Laporan
                        </button>
                        <a href="{{ route('user.reports.index') }}" class="btn btn-outline-secondary" style="border-radius:10px;">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-4">
        <div class="card">
            <div class="card-header"><i class="bi bi-lightbulb me-2 text-warning"></i>Tips Laporan Baik</div>
            <div class="card-body">
                <ul class="list-unstyled mb-0" style="font-size:.85rem;">
                    <li class="d-flex gap-2 mb-3">
                        <span class="badge bg-primary rounded-circle flex-shrink-0 d-flex align-items-center justify-content-center" style="width:22px;height:22px;font-size:.7rem;">1</span>
                        <span class="text-muted">Berikan <strong>judul yang jelas</strong> dan spesifik tentang kerusakan.</span>
                    </li>
                    <li class="d-flex gap-2 mb-3">
                        <span class="badge bg-primary rounded-circle flex-shrink-0 d-flex align-items-center justify-content-center" style="width:22px;height:22px;font-size:.7rem;">2</span>
                        <span class="text-muted">Tulis <strong>alamat lengkap</strong> dan tandai di peta.</span>
                    </li>
                    <li class="d-flex gap-2 mb-3">
                        <span class="badge bg-primary rounded-circle flex-shrink-0 d-flex align-items-center justify-content-center" style="width:22px;height:22px;font-size:.7rem;">3</span>
                        <span class="text-muted">Deskripsikan <strong>kondisi kerusakan</strong> dan dampaknya.</span>
                    </li>
                    <li class="d-flex gap-2 mb-0">
                        <span class="badge bg-primary rounded-circle flex-shrink-0 d-flex align-items-center justify-content-center" style="width:22px;height:22px;font-size:.7rem;">4</span>
                        <span class="text-muted">Upload <strong>hingga 5 foto</strong> untuk mempercepat validasi.</span>
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
    // ── Peta ──
    const map = L.map('map').setView([{{ old('latitude', -7.2575) }}, {{ old('longitude', 112.7521) }}], 13);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution: '© OpenStreetMap' }).addTo(map);
    let marker = null;
    @if(old('latitude') && old('longitude'))
        marker = L.marker([{{ old('latitude') }}, {{ old('longitude') }}]).addTo(map);
        updateCoords({{ old('latitude') }}, {{ old('longitude') }});
    @endif
    map.on('click', function(e) {
        if (marker) map.removeLayer(marker);
        marker = L.marker([e.latlng.lat, e.latlng.lng]).addTo(map);
        document.getElementById('latitude').value  = e.latlng.lat.toFixed(7);
        document.getElementById('longitude').value = e.latlng.lng.toFixed(7);
        updateCoords(e.latlng.lat, e.latlng.lng);
    });
    function updateCoords(lat, lng) {
        document.getElementById('coordsLabel').innerHTML =
            `<i class="bi bi-geo-alt-fill text-danger me-1"></i>Lat: ${parseFloat(lat).toFixed(5)}, Lng: ${parseFloat(lng).toFixed(5)}`;
    }
    function resetMap() {
        if (marker) { map.removeLayer(marker); marker = null; }
        document.getElementById('latitude').value = document.getElementById('longitude').value = '';
        document.getElementById('coordsLabel').innerHTML = '<i class="bi bi-geo-alt me-1"></i>Belum ada titik dipilih';
    }

    // ── Multiple Photo Preview ──
    const MAX_PHOTOS = 5;
    let selectedFiles = [];

    const photoInput = document.getElementById('photoInput');
    const dropArea   = document.getElementById('dropArea');
    const previewGrid= document.getElementById('previewGrid');
    const photoCount = document.getElementById('photoCount');

    photoInput.addEventListener('change', () => addFiles(photoInput.files));
    dropArea.addEventListener('dragover', e => { e.preventDefault(); dropArea.classList.add('dragover'); });
    dropArea.addEventListener('dragleave', () => dropArea.classList.remove('dragover'));
    dropArea.addEventListener('drop', e => {
        e.preventDefault(); dropArea.classList.remove('dragover');
        addFiles(e.dataTransfer.files);
    });

    function addFiles(fileList) {
        Array.from(fileList).forEach(f => {
            if (selectedFiles.length >= MAX_PHOTOS) return;
            if (!f.type.startsWith('image/')) return;
            selectedFiles.push(f);
        });
        syncInput();
        renderPreviews();
    }

    function removeFile(idx) {
        selectedFiles.splice(idx, 1);
        syncInput(); renderPreviews();
    }

    function syncInput() {
        const dt = new DataTransfer();
        selectedFiles.forEach(f => dt.items.add(f));
        photoInput.files = dt.files;
        photoCount.textContent = `${selectedFiles.length} / ${MAX_PHOTOS} foto dipilih`;
    }

    function renderPreviews() {
        previewGrid.innerHTML = '';
        selectedFiles.forEach((f, i) => {
            const reader = new FileReader();
            reader.onload = e => {
                const wrap = document.createElement('div');
                wrap.className = 'photo-thumb-wrap';
                wrap.innerHTML = `
                    <img src="${e.target.result}" alt="preview">
                    <button type="button" class="remove-btn" onclick="removeFile(${i})">
                        <i class="bi bi-x"></i>
                    </button>`;
                previewGrid.appendChild(wrap);
            };
            reader.readAsDataURL(f);
        });
    }

    // ── Char counter ──
    const desc = document.querySelector('textarea[name="description"]');
    const counter = document.getElementById('charCount');
    desc.addEventListener('input', () => {
        counter.textContent = desc.value.length + ' karakter';
        counter.style.color = desc.value.length < 20 ? '#ef4444' : '#64748b';
    });
    counter.textContent = desc.value.length + ' karakter';
</script>
@endpush