@extends('layouts.user')

@section('title', 'Buat Laporan')

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
        padding: 2.25rem 1.5rem;
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
</style>
@endpush

@section('content')
<div class="mb-5">
    <h4 class="font-extrabold text-2xl tracking-tight text-slate-800 mb-1">
        Buat Pengaduan Baru 📝
    </h4>
    <p class="text-slate-500 font-medium text-xs">
        Laporkan kerusakan fasilitas umum untuk segera ditindaklanjuti oleh dinas terkait Gresik.
    </p>
</div>

@if($errors->any())
    <div class="alert alert-danger border-0 shadow-sm rounded-xl d-flex align-items-start gap-3 py-3 px-4 mb-4 bg-rose-50 text-rose-800" role="alert">
        <div class="bg-rose-500 text-white rounded-full p-1 h-7 w-7 flex items-center justify-center mt-0.5 flex-shrink-0">
            <i class="bi bi-exclamation-triangle"></i>
        </div>
        <div class="flex-grow-1 text-xs font-semibold">
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

<form action="{{ route('user.reports.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <div class="row g-4">
        <!-- Left Panel: Form fields -->
        <div class="col-12 col-lg-7">
            <div class="bg-white rounded-2xl border border-slate-200/60 p-4 sm:p-5 shadow-sm space-y-4">
                
                <!-- Judul -->
                <div>
                    <label class="form-label text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-2">Judul Laporan <span class="text-rose-500">*</span></label>
                    <input type="text" name="title" class="form-control border-slate-200/80 text-xs focus:border-blue-500 focus:ring-0 shadow-none text-slate-800"
                           placeholder="Contoh: Jalan berlubang lebar di simpang Gubeng"
                           value="{{ old('title') }}" style="border-radius:12px; padding: 0.7rem 0.9rem; font-weight:500;">
                </div>

                <!-- Kategori & Lokasi -->
                <div class="row g-3">
                    <div class="col-12 col-sm-6">
                        <label class="form-label text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-2">Kategori <span class="text-rose-500">*</span></label>
                        <select name="category" class="form-select border-slate-200/80 text-xs focus:border-blue-500 shadow-none text-slate-700" style="border-radius:12px; padding: 0.7rem 0.9rem; font-weight:500;">
                            <option value="">Pilih Kategori</option>
                            @foreach($categories as $key => $label)
                                <option value="{{ $key }}" {{ (request('category') === $key || old('category') === $key) ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12 col-sm-6">
                        <label class="form-label text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-2">Alamat Lokasi <span class="text-rose-500">*</span></label>
                        <input type="text" name="location" id="location"
                               class="form-control border-slate-200/80 text-xs focus:border-blue-500 shadow-none text-slate-850"
                               placeholder="Contoh: Jl. Raya Gubeng No. 12"
                               value="{{ old('location') }}" style="border-radius:12px; padding: 0.7rem 0.9rem; font-weight:500;">
                    </div>
                </div>

                <!-- Deskripsi -->
                <div>
                    <label class="form-label text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-2">Deskripsi Kerusakan <span class="text-rose-500">*</span></label>
                    <textarea name="description" rows="5"
                              class="form-control border-slate-200/80 text-xs focus:border-blue-500 shadow-none text-slate-800"
                              placeholder="Jelaskan kondisi kerusakan secara lengkap dan dampaknya bagi warga sekitar (minimal 20 karakter)..."
                              style="border-radius:12px; padding: 0.7rem 0.9rem; font-weight:500;">{{ old('description') }}</textarea>
                    <div class="d-flex justify-content-end mt-1.5">
                        <small class="text-slate-450 font-bold text-[10px]" id="charCount">0 karakter</small>
                    </div>
                </div>

                <!-- Upload Photo dropzone -->
                <div>
                    <label class="form-label text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-2">
                        Upload Foto Kerusakan <small class="text-slate-400 lowercase font-bold">(maksimal 5 foto, masing-masing maks 5MB)</small>
                    </label>
                    
                    <div class="photo-drop-area" id="dropArea" onclick="document.getElementById('photoInput').click()">
                        <div class="bg-slate-200/60 text-slate-500 rounded-full h-12 w-12 flex items-center justify-center mx-auto mb-2.5">
                            <i class="bi bi-cloud-arrow-up-fill fs-4"></i>
                        </div>
                        <div class="font-bold text-slate-700 text-sm mb-1">Klik atau seret foto kerusakan ke sini</div>
                        <small class="text-slate-400 text-xs font-semibold">Mendukung format JPG, PNG, WEBP</small>
                        <input type="file" id="photoInput" name="photos[]" accept="image/*"
                               multiple style="display:none;">
                    </div>
                    
                    <div class="photo-preview-grid" id="previewGrid"></div>
                    
                    <div class="d-flex justify-content-end mt-2">
                        <small class="text-slate-400 font-bold text-[10px]" id="photoCount">0 / 5 foto dipilih</small>
                    </div>
                </div>

                <!-- Actions buttons -->
                <div class="d-flex gap-3 pt-3">
                    <button type="submit" class="btn btn-primary shadow-lg shadow-blue-500/20 px-4 py-2.5 rounded-xl text-xs font-bold hover-lift flex items-center gap-1.5">
                        <i class="bi bi-send-fill text-[11px]"></i> Kirim Pengaduan Warga
                    </button>
                    <a href="{{ route('user.reports.index') }}" class="btn btn-light border border-slate-200 text-slate-650 px-4 py-2.5 rounded-xl text-xs font-bold hover-lift">
                        Batal
                    </a>
                </div>

            </div>
        </div>

        <!-- Right Panel: Map Selection & Tips -->
        <div class="col-12 col-lg-5">
            <div class="bg-white rounded-2xl border border-slate-200/60 p-4 sm:p-5 shadow-sm mb-4 space-y-4">
                <div>
                    <h6 class="font-bold text-slate-800 mb-1">Tandai Titik di Peta</h6>
                    <p class="text-[11px] text-slate-400 font-semibold mb-0 uppercase tracking-wider">Klik peta untuk menaruh pin penanda lokasi persis aduan Anda</p>
                </div>

                <div id="map" class="mb-2"></div>

                <div class="d-flex align-items-center justify-between gap-3 p-3 bg-slate-50 rounded-xl border border-slate-100">
                    <small class="text-slate-600 font-bold text-xs leading-none" id="coordsLabel">
                        <i class="bi bi-geo-alt me-1 text-slate-400"></i>Belum ada titik dipilih
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
                
                <input type="hidden" name="latitude" id="latitude" value="{{ old('latitude') }}">
                <input type="hidden" name="longitude" id="longitude" value="{{ old('longitude') }}">
            </div>

            <!-- Tips Panel -->
            <div class="bg-amber-50/50 rounded-2xl p-4 border border-amber-200/50">
                <h6 class="font-bold text-amber-800 text-sm mb-2.5 flex items-center gap-1.5"><i class="bi bi-lightbulb-fill"></i> Panduan Menulis Pengaduan:</h6>
                <ul class="list-unstyled mb-0 text-xs text-slate-600 space-y-2.5">
                    <li class="flex items-start gap-2.5">
                        <span class="bg-amber-100 text-amber-800 rounded-full h-5 w-5 flex items-center justify-center font-extrabold text-[10px] flex-shrink-0 mt-0.5">1</span>
                        <span>Berikan <strong>judul yang deskriptif</strong>, seperti menyertakan jenis kerusakan dan nama jalan.</span>
                    </li>
                    <li class="flex items-start gap-2.5">
                        <span class="bg-amber-100 text-amber-800 rounded-full h-5 w-5 flex items-center justify-center font-extrabold text-[10px] flex-shrink-0 mt-0.5">2</span>
                        <span>Sesuaikan kategori agar dinas terkait dapat memproses laporan dengan lebih terarah.</span>
                    </li>
                    <li class="flex items-start gap-2.5">
                        <span class="bg-amber-100 text-amber-800 rounded-full h-5 w-5 flex items-center justify-center font-extrabold text-[10px] flex-shrink-0 mt-0.5">3</span>
                        <span>Klik peta di lokasi kerusakan secara akurat agar petugas mudah mendatangi lokasi.</span>
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
    // Leaflet map setup
    const map = L.map('map').setView([{{ old('latitude', -7.1539) }}, {{ old('longitude', 112.6561) }}], 13);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    let marker = null;

    @if(old('latitude') && old('longitude'))
        marker = L.marker([{{ old('latitude') }}, {{ old('longitude') }}]).addTo(map);
        updateCoordsLabel({{ old('latitude') }}, {{ old('longitude') }});
    @endif

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

    // Multiple File Selection & Previews
    const MAX_PHOTOS = 5;
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
        syncInput();
        renderPreviews();
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

    // Character counter for description text
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