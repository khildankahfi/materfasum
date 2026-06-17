@extends('layouts.user')

@section('title', 'Edit Laporan')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
<style>
    #map {
        height: 380px;
        border-radius: 16px;
        z-index: 1;
        border: 1px solid #e2e8f0;
    }
    .photo-drop-area {
        border: 2px dashed #cbd5e1;
        border-radius: 16px;
        padding: 2rem 1.5rem;
        text-align: center;
        cursor: pointer;
        background-color: #f8fafc;
        transition: all 0.2s ease;
    }
    .photo-drop-area:hover, .photo-drop-area.dragover {
        border-color: #2563eb;
        background-color: #eff6ff;
    }
    .photo-preview-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(80px, 1fr));
        gap: 0.75rem;
        margin-top: 1rem;
    }
    .photo-thumb-wrap {
        position: relative;
        aspect-ratio: 1;
        border-radius: 12px;
        overflow: hidden;
        border: 1px solid #e2e8f0;
        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
    }
    .photo-thumb-wrap img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .photo-thumb-wrap .remove-btn {
        position: absolute;
        top: 4px;
        right: 4px;
        background: rgba(244, 63, 94, 0.9);
        border: none;
        border-radius: 50%;
        width: 22px;
        height: 22px;
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        font-size: 0.75rem;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        transition: transform 0.2s;
    }
    .photo-thumb-wrap .remove-btn:hover {
        transform: scale(1.1);
    }
    .current-photo-card {
        transition: all 0.2s;
    }
</style>
@endpush

@section('content')
<div class="mb-5">
    <h4 class="font-extrabold text-2xl tracking-tight text-slate-800 mb-1">
        Edit Pengaduan ✏️
    </h4>
    <p class="text-slate-500 font-medium text-sm">
        Ubah rincian informasi laporan kerusakan fasilitas umum Anda.
    </p>
</div>

@if($errors->any())
    <div class="alert alert-danger border-0 shadow-sm rounded-xl d-flex align-items-start gap-3 py-3 px-4 mb-4 bg-rose-50 text-rose-800" role="alert">
        <div class="bg-rose-500 text-white rounded-full p-1 h-7 w-7 flex items-center justify-center mt-0.5 flex-shrink-0">
            <i class="bi bi-exclamation-triangle"></i>
        </div>
        <div class="flex-grow-1 text-sm font-medium">
            <strong class="block mb-1">Terjadi kesalahan input data:</strong>
            <ul class="mb-0 ps-3 list-disc">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<form action="{{ route('user.reports.update', $report) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="row g-4">
        <!-- Left Panel: Form fields -->
        <div class="col-12 col-lg-7">
            <div class="bg-white rounded-2xl border border-slate-200/60 p-4 sm:p-5 shadow-sm space-y-4">
                
                <!-- Judul -->
                <div>
                    <label class="form-label text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Judul Laporan <span class="text-rose-500">*</span></label>
                    <input type="text" name="title" class="form-control border-slate-200/80 text-sm focus:border-blue-500 focus:ring-0"
                           placeholder="Contoh: Jalan berlubang lebar di simpang Gubeng"
                           value="{{ old('title', $report->title) }}" style="border-radius:12px; padding: 0.7rem 0.9rem;">
                </div>

                <!-- Kategori & Lokasi -->
                <div class="row g-3">
                    <div class="col-12 col-sm-6">
                        <label class="form-label text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Kategori <span class="text-rose-500">*</span></label>
                        <select name="category" class="form-select border-slate-200/80 text-sm focus:border-blue-500" style="border-radius:12px; padding: 0.7rem 0.9rem;">
                            <option value="">Pilih Kategori</option>
                            @foreach($categories as $key => $label)
                                <option value="{{ $key }}" {{ old('category', $report->category) === $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12 col-sm-6">
                        <label class="form-label text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Alamat Lokasi <span class="text-rose-500">*</span></label>
                        <input type="text" name="location" id="location"
                               class="form-control border-slate-200/80 text-sm focus:border-blue-500"
                               placeholder="Contoh: Jl. Raya Gubeng No. 12"
                               value="{{ old('location', $report->location) }}" style="border-radius:12px; padding: 0.7rem 0.9rem;">
                    </div>
                </div>

                <!-- Deskripsi -->
                <div>
                    <label class="form-label text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Deskripsi Kerusakan <span class="text-rose-500">*</span></label>
                    <textarea name="description" rows="5"
                              class="form-control border-slate-200/80 text-sm focus:border-blue-500"
                              placeholder="Jelaskan kondisi kerusakan secara lengkap dan dampaknya bagi warga sekitar (minimal 20 karakter)..."
                              style="border-radius:12px; padding: 0.7rem 0.9rem;">{{ old('description', $report->description) }}</textarea>
                    <div class="d-flex justify-content-end mt-1.5">
                        <small class="text-slate-400 font-bold text-[10px]" id="charCount">0 karakter</small>
                    </div>
                </div>

                <!-- Existing Photos (Deletion selector) -->
                @if($report->photos->isNotEmpty())
                    <div>
                        <label class="form-label text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Foto Saat Ini <small class="text-rose-500 lowercase font-medium">(centang foto untuk menghapusnya)</small></label>
                        <div class="flex flex-wrap gap-3">
                            @foreach($report->photos as $photo)
                                <div class="position-relative current-photo-card rounded-xl overflow-hidden border border-slate-200 bg-slate-50 flex items-center justify-center" style="width: 80px; height: 80px;">
                                    <img src="{{ Storage::url($photo->path) }}" class="w-full h-full object-cover">
                                    <div class="position-absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 hover:opacity-100 transition-opacity duration-200">
                                        <div class="form-check m-0">
                                            <input class="form-check-input remove-photo-cb cursor-pointer" type="checkbox" name="remove_photos[]" value="{{ $photo->id }}" id="photo-{{ $photo->id }}" style="width: 1.15rem; height: 1.15rem;">
                                        </div>
                                    </div>
                                    <!-- Delete label indicator -->
                                    <div class="position-absolute top-1 right-1 bg-rose-500 text-white rounded-full h-5.5 w-5.5 flex items-center justify-center text-[9px] font-bold remove-badge hidden" id="badge-{{ $photo->id }}">
                                        <i class="bi bi-trash-fill"></i>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Upload Photo dropzone -->
                <div>
                    <label class="form-label text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">
                        Tambah Foto Baru <small class="text-slate-400 lowercase font-medium">(maksimal 5 foto secara keseluruhan)</small>
                    </label>
                    
                    <div class="photo-drop-area" id="dropArea" onclick="document.getElementById('photoInput').click()">
                        <div class="bg-slate-200/60 text-slate-500 rounded-full h-12 w-12 flex items-center justify-center mx-auto mb-2.5">
                            <i class="bi bi-plus-lg fs-5"></i>
                        </div>
                        <div class="font-bold text-slate-700 text-sm mb-1">Klik atau seret foto tambahan ke sini</div>
                        <small class="text-slate-400 text-xs font-semibold">Mendukung format JPG, PNG, WEBP</small>
                        <input type="file" id="photoInput" name="photos[]" accept="image/*"
                               multiple style="display:none;">
                    </div>
                    
                    <div class="photo-preview-grid" id="previewGrid"></div>
                    
                    <div class="d-flex justify-content-end mt-2">
                        <small class="text-slate-400 font-bold text-[10px]" id="photoCount">0 foto baru dipilih</small>
                    </div>
                </div>

                <!-- Actions buttons -->
                <div class="d-flex gap-3 pt-3">
                    <button type="submit" class="btn btn-primary shadow-lg shadow-blue-500/20 px-4 py-2.5 rounded-xl text-sm font-semibold hover-lift">
                        <i class="bi bi-check-lg me-1.5"></i>Simpan Perubahan
                    </button>
                    <a href="{{ route('user.reports.show', $report) }}" class="btn btn-light border border-slate-200 text-slate-600 px-4 py-2.5 rounded-xl text-sm font-semibold hover-lift">
                        Batal
                    </a>
                </div>

            </div>
        </div>

        <!-- Right Panel: Map Selection & Status Warnings -->
        <div class="col-12 col-lg-5">
            <div class="bg-white rounded-2xl border border-slate-200/60 p-4 sm:p-5 shadow-sm mb-4 space-y-4">
                <div>
                    <h6 class="font-bold text-slate-800 mb-1">Sesuaikan Lokasi di Peta</h6>
                    <p class="text-[11px] text-slate-400 font-semibold mb-0 uppercase tracking-wider">Geser atau klik peta untuk memperbarui titik koordinat penanganan</p>
                </div>

                <div id="map" class="mb-2"></div>

                <div class="d-flex align-items-center justify-between gap-3 p-3 bg-slate-50 rounded-xl border border-slate-100">
                    <small class="text-slate-600 font-semibold text-xs leading-none" id="coordsLabel">
                        @if($report->latitude && $report->longitude)
                            <i class="bi bi-geo-alt-fill text-rose-500 me-1"></i>Lat: {{ number_format($report->latitude, 5) }}, Lng: {{ number_format($report->longitude, 5) }}
                        @else
                            <i class="bi bi-geo-alt me-1 text-slate-400"></i>Belum ada titik dipilih
                        @endif
                    </small>
                    <div class="d-flex gap-1.5">
                        <button type="button" class="btn btn-sm btn-primary px-2.5 py-1.5 rounded-lg text-[10px] font-bold" onclick="getLocationGPS()">
                            <i class="bi bi-cursor-fill me-1"></i>Gunakan GPS
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-secondary px-2.5 py-1.5 rounded-lg text-[10px] font-bold" onclick="resetMap()">
                            <i class="bi bi-trash me-1"></i>Hapus Pin
                        </button>
                    </div>
                </div>
                
                <input type="hidden" name="latitude" id="latitude" value="{{ old('latitude', $report->latitude) }}">
                <input type="hidden" name="longitude" id="longitude" value="{{ old('longitude', $report->longitude) }}">
            </div>

            <!-- Caution Panel -->
            <div class="bg-rose-50/50 rounded-2xl p-4 border border-rose-200/50">
                <h6 class="font-bold text-rose-800 text-sm mb-2.5"><i class="bi bi-exclamation-octagon me-2"></i>Aturan Perubahan Aduan</h6>
                <ul class="list-unstyled mb-0 text-xs text-slate-600 space-y-2.5">
                    <li class="flex items-start gap-2.5">
                        <i class="bi bi-check-circle-fill text-rose-500 flex-shrink-0 mt-0.5"></i>
                        <span>Pengaduan hanya bisa diubah selama berstatus <strong>Menunggu Validasi</strong>.</span>
                    </li>
                    <li class="flex items-start gap-2.5">
                        <i class="bi bi-check-circle-fill text-rose-500 flex-shrink-0 mt-0.5"></i>
                        <span>Jika petugas atau admin sudah memvalidasi/memproses aduan ini, tombol simpan akan dikunci otomatis.</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    // Map Configuration
    const initLat = {{ old('latitude', $report->latitude ?? -7.1539) }};
    const initLng = {{ old('longitude', $report->longitude ?? 112.6561) }};
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
        document.getElementById('latitude').value = lat.toFixed(7);
        document.getElementById('longitude').value = lng.toFixed(7);
        updateCoordsLabel(lat, lng);
        reverseGeocode(lat, lng);
    });

    function updateCoordsLabel(lat, lng) {
        document.getElementById('coordsLabel').innerHTML =
            `<i class="bi bi-geo-alt-fill text-rose-500 me-1"></i>Lat: ${parseFloat(lat).toFixed(5)}, Lng: ${parseFloat(lng).toFixed(5)}`;
    }

    function resetMap() {
        if (marker) {
            map.removeLayer(marker);
            marker = null;
        }
        document.getElementById('latitude').value = '';
        document.getElementById('longitude').value = '';
        document.getElementById('coordsLabel').innerHTML = '<i class="bi bi-geo-alt me-1"></i>Belum ada titik dipilih';
    }

    function reverseGeocode(lat, lng) {
        const locationInput = document.getElementById('location');
        if (!locationInput) return;
        
        const originalPlaceholder = locationInput.placeholder;
        locationInput.placeholder = "Mencari alamat otomatis...";
        locationInput.disabled = true;

        fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&zoom=18&addressdetails=1`, {
            headers: {
                'User-Agent': 'MaterFasumApp/1.0 (khildankahfi/materfasum)'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data && data.display_name) {
                locationInput.value = data.display_name;
            }
        })
        .catch(error => {
            console.error('Error reverse geocoding:', error);
        })
        .finally(() => {
            locationInput.placeholder = originalPlaceholder;
            locationInput.disabled = false;
        });
    }

    function getLocationGPS() {
        if (!navigator.geolocation) {
            Swal.fire({
                title: 'Tidak Didukung',
                text: 'Browser Anda tidak mendukung fitur deteksi lokasi GPS.',
                icon: 'error',
                customClass: { popup: 'rounded-2xl border-0 shadow-xl' }
            });
            return;
        }

        Swal.fire({
            title: 'Mencari Lokasi...',
            text: 'Harap izinkan akses GPS pada perangkat Anda.',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            },
            customClass: { popup: 'rounded-2xl border-0 shadow-xl' }
        });

        navigator.geolocation.getCurrentPosition(
            function(position) {
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;

                if (marker) map.removeLayer(marker);
                marker = L.marker([lat, lng]).addTo(map);
                map.setView([lat, lng], 16);

                document.getElementById('latitude').value = lat.toFixed(7);
                document.getElementById('longitude').value = lng.toFixed(7);
                updateCoordsLabel(lat, lng);
                reverseGeocode(lat, lng);

                Swal.close();
            },
            function(error) {
                Swal.close();
                let errMsg = 'Gagal mendeteksi lokasi GPS.';
                if (error.code === error.PERMISSION_DENIED) {
                    errMsg = 'Akses lokasi ditolak. Harap aktifkan izin lokasi di browser Anda.';
                }
                Swal.fire({
                    title: 'Deteksi GPS Gagal',
                    text: errMsg,
                    icon: 'error',
                    customClass: { popup: 'rounded-2xl border-0 shadow-xl' }
                });
            },
            {
                enableHighAccuracy: true,
                timeout: 10000,
                maximumAge: 0
            }
        );
    }

    // Existing Photos Deletion visual toggle
    document.querySelectorAll('.remove-photo-cb').forEach(cb => {
        cb.addEventListener('change', function() {
            const id = this.value;
            const badge = document.getElementById(`badge-${id}`);
            const card = this.closest('.current-photo-card');
            if (this.checked) {
                badge.classList.remove('hidden');
                card.style.borderColor = '#f43f5e';
                card.style.opacity = '0.5';
            } else {
                badge.classList.add('hidden');
                card.style.borderColor = '#e2e8f0';
                card.style.opacity = '1';
            }
        });
    });

    // File Dropzone for New Photos
    const MAX_PHOTOS = 5;
    const existingPhotosCount = {{ $report->photos->count() }};
    let selectedFiles = [];

    const photoInput = document.getElementById('photoInput');
    const dropArea = document.getElementById('dropArea');
    const previewGrid = document.getElementById('previewGrid');
    const photoCount = document.getElementById('photoCount');

    photoInput.addEventListener('change', () => addFiles(photoInput.files));
    dropArea.addEventListener('dragover', e => { e.preventDefault(); dropArea.classList.add('dragover'); });
    dropArea.addEventListener('dragleave', () => dropArea.classList.remove('dragover'));
    dropArea.addEventListener('drop', e => {
        e.preventDefault();
        dropArea.classList.remove('dragover');
        addFiles(e.dataTransfer.files);
    });

    function addFiles(fileList) {
        const allowedNewCount = MAX_PHOTOS - existingPhotosCount;
        Array.from(fileList).forEach(f => {
            if (selectedFiles.length >= allowedNewCount) return;
            if (!f.type.startsWith('image/')) return;
            selectedFiles.push(f);
        });
        syncInput();
        renderPreviews();
    }

    function removeFile(idx) {
        selectedFiles.splice(idx, 1);
        syncInput();
        renderPreviews();
    }

    function syncInput() {
        const dt = new DataTransfer();
        selectedFiles.forEach(f => dt.items.add(f));
        photoInput.files = dt.files;
        photoCount.textContent = `${selectedFiles.length} foto baru dipilih`;
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

    // Char count for description
    const desc = document.querySelector('textarea[name="description"]');
    const counter = document.getElementById('charCount');
    function updateCharCount() {
        const len = desc.value.length;
        counter.textContent = len + ' karakter';
        counter.style.color = len < 20 ? '#ef4444' : '#64748b';
    }
    desc.addEventListener('input', updateCharCount);
    updateCharCount();
</script>
@endpush