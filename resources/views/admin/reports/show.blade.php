@extends('layouts.admin')

@section('title', 'Detail Laporan')
@section('page-title', 'Detail Laporan')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.reports.index') }}">Laporan</a></li>
    <li class="breadcrumb-item active">Detail</li>
@endsection

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
<style>
    #map-admin { height: 220px; border-radius:10px; z-index:0; }

    /* Timeline */
    .timeline { position: relative; padding-left: 0; }
    .timeline-item { display: flex; gap: 1rem; margin-bottom: 0; padding-bottom: 1.5rem; position: relative; }
    .timeline-item:not(:last-child) .timeline-dot::after {
        content: '';
        position: absolute;
        top: 36px;
        left: 17px;
        width: 2px;
        bottom: 0;
        background: #e2e8f0;
    }
    .timeline-dot {
        flex-shrink: 0;
        position: relative;
        width: 36px;
    }
    .timeline-dot .dot {
        width: 36px; height: 36px;
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        color: #fff;
        font-size: .85rem;
        position: relative;
        z-index: 1;
    }
    .timeline-body { flex-grow: 1; padding-bottom: 1rem; }

    /* Photo gallery */
    .photo-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(120px, 1fr)); gap: .5rem; }
    .photo-grid img { width: 100%; height: 100px; object-fit: cover; border-radius: 8px; cursor: pointer; transition: opacity .2s; }
    .photo-grid img:hover { opacity: .85; }
</style>
@endpush

@section('content')

<div class="row g-4">
    {{-- Info Laporan --}}
    <div class="col-12 col-lg-8">
        <div class="card mb-4">
            <div class="card-header d-flex align-items-center justify-content-between">
                <span><i class="bi bi-file-earmark-text me-2" style="color:var(--primary);"></i>Informasi Laporan</span>
                <span class="badge badge-{{ $report->status }} px-3 py-2" style="font-size:.78rem;">{{ $report->status_label }}</span>
            </div>
            <div class="card-body">
                <h5 class="fw-700 mb-4">{{ $report->title }}</h5>

                <div class="row g-3 mb-4">
                    <div class="col-sm-6">
                        <div class="text-muted mb-1" style="font-size:.75rem;text-transform:uppercase;letter-spacing:.5px;">Kategori</div>
                        <span class="badge bg-light text-dark px-2 py-1">{{ $report->category_label }}</span>
                    </div>
                    <div class="col-sm-6">
                        <div class="text-muted mb-1" style="font-size:.75rem;text-transform:uppercase;letter-spacing:.5px;">Tanggal Lapor</div>
                        <div style="font-size:.9rem;">{{ $report->created_at->format('d F Y, H:i') }} WIB</div>
                    </div>
                    <div class="col-12">
                        <div class="text-muted mb-1" style="font-size:.75rem;text-transform:uppercase;letter-spacing:.5px;">Lokasi</div>
                        <div style="font-size:.9rem;"><i class="bi bi-geo-alt text-danger me-1"></i>{{ $report->location }}</div>
                    </div>
                    <div class="col-sm-6">
                        <div class="text-muted mb-1" style="font-size:.75rem;text-transform:uppercase;letter-spacing:.5px;">Pelapor</div>
                        <div class="fw-600" style="font-size:.9rem;">{{ $report->user->name }}</div>
                        <small class="text-muted">{{ $report->user->email }}</small>
                    </div>
                    <div class="col-sm-6">
                        <div class="text-muted mb-1" style="font-size:.75rem;text-transform:uppercase;letter-spacing:.5px;">No. Telepon</div>
                        <div style="font-size:.9rem;">{{ $report->user->phone ?? '-' }}</div>
                    </div>
                </div>

                {{-- Peta --}}
                @if($report->latitude && $report->longitude)
                    <div class="mb-4">
                        <div class="text-muted mb-2" style="font-size:.75rem;text-transform:uppercase;letter-spacing:.5px;">Peta Lokasi</div>
                        <div id="map-admin"></div>
                    </div>
                @endif

                <div class="mb-4">
                    <div class="text-muted mb-2" style="font-size:.75rem;text-transform:uppercase;letter-spacing:.5px;">Deskripsi Kerusakan</div>
                    <p style="font-size:.9rem;line-height:1.7;color:#374151;">{{ $report->description }}</p>
                </div>

                {{-- Multiple Photos --}}
                @php $allPhotos = $report->all_photos; @endphp
                @if(count($allPhotos) > 0)
                    <div>
                        <div class="text-muted mb-2" style="font-size:.75rem;text-transform:uppercase;letter-spacing:.5px;">
                            Foto Kerusakan ({{ count($allPhotos) }} foto)
                        </div>
                        <div class="photo-grid">
                            @foreach($allPhotos as $url)
                                <img src="{{ $url }}" alt="Foto laporan" onclick="window.open(this.src,'_blank')"
                                     title="Klik untuk perbesar">
                            @endforeach
                        </div>
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

        {{-- Timeline Riwayat --}}
        <div class="card">
            <div class="card-header"><i class="bi bi-clock-history me-2" style="color:var(--primary);"></i>Riwayat Pembaruan</div>
            <div class="card-body">
                @if($report->updates->isEmpty())
                    <div class="text-center py-3 text-muted">
                        <i class="bi bi-hourglass-split fs-3 d-block mb-2 opacity-50"></i>
                        <small>Belum ada riwayat pembaruan.</small>
                    </div>
                @else
                    <div class="timeline">
                        @foreach($report->updates as $update)
                            @php
                                $dotColor = match($update->status) {
                                    'diproses' => '#0ea5e9',
                                    'selesai'  => '#10b981',
                                    'ditolak'  => '#ef4444',
                                    default    => '#94a3b8'
                                };
                                $dotIcon = match($update->status) {
                                    'diproses' => 'gear-fill',
                                    'selesai'  => 'check-lg',
                                    'ditolak'  => 'x-lg',
                                    default    => 'clock'
                                };
                            @endphp
                            <div class="timeline-item">
                                <div class="timeline-dot">
                                    <div class="dot" style="background:{{ $dotColor }};">
                                        <i class="bi bi-{{ $dotIcon }}"></i>
                                    </div>
                                </div>
                                <div class="timeline-body">
                                    <div class="d-flex align-items-start justify-content-between gap-2 mb-1">
                                        <div>
                                            <span class="fw-600" style="font-size:.88rem;">
                                                Status diubah ke
                                            </span>
                                            <span class="badge badge-{{ $update->status }} ms-1">{{ $update->status_label }}</span>
                                        </div>
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

    {{-- Panel Aksi Admin --}}
    <div class="col-12 col-lg-4">
        @if(!in_array($report->status, ['selesai', 'ditolak']))
            <div class="card mb-4">
                <div class="card-header fw-700">
                    <i class="bi bi-shield-check me-2" style="color:var(--primary);"></i>Validasi & Update Status
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.reports.update-status', $report) }}" method="POST" enctype="multipart/form-data"
                          class="form-confirm"
                          data-title="Update Status Laporan"
                          data-text="Apakah Anda yakin ingin menyimpan perubahan status laporan ini?"
                          data-icon="question"
                          data-confirm-text="Ya, Simpan!"
                          data-cancel-text="Batal">
                        @csrf

                        @if($errors->any())
                            <div class="alert alert-danger mb-3" style="border-radius:8px;font-size:.83rem;">
                                @foreach($errors->all() as $err) <div>{{ $err }}</div> @endforeach
                            </div>
                        @endif

                        <div class="mb-3">
                            <label class="form-label fw-600" style="font-size:.85rem;">Status Baru <span class="text-danger">*</span></label>
                            <select name="status" id="statusSelect" class="form-select" style="border-radius:8px;" onchange="toggleRejection(this.value)">
                                <option value="">Pilih Status</option>
                                @if($report->status === 'menunggu')
                                    <option value="diproses">▶ Diproses</option>
                                    <option value="ditolak">✕ Ditolak</option>
                                @elseif($report->status === 'diproses')
                                    <option value="selesai">✓ Selesai</option>
                                    <option value="ditolak">✕ Ditolak</option>
                                @endif
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-600" style="font-size:.85rem;">Catatan Admin</label>
                            <textarea name="note" rows="3" class="form-control"
                                      placeholder="Tambahkan catatan tindak lanjut (opsional)..."
                                      style="border-radius:8px;font-size:.88rem;">{{ old('note') }}</textarea>
                        </div>

                        <div class="mb-3" id="rejectionReasonField" style="display:none;">
                            <label class="form-label fw-600 text-danger" style="font-size:.85rem;">
                                Alasan Penolakan <span class="text-danger">*</span>
                            </label>
                            <textarea name="rejection_reason" rows="3" class="form-control"
                                      placeholder="Jelaskan alasan penolakan..."
                                      style="border-radius:8px;font-size:.88rem;border-color:#ef4444;">{{ old('rejection_reason') }}</textarea>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-600" style="font-size:.85rem;">Foto Sesudah Perbaikan</label>
                            <input type="file" name="photo_after" class="form-control form-control-sm"
                                   accept="image/*" style="border-radius:8px;">
                            <small class="text-muted">Upload foto kondisi setelah diperbaiki (opsional)</small>
                        </div>

                        <button type="submit" class="btn btn-primary w-100" style="border-radius:10px;font-weight:600;">
                            <i class="bi bi-check-circle me-2"></i>Simpan Perubahan
                        </button>
                    </form>
                </div>
            </div>
        @else
            <div class="card mb-4">
                <div class="card-body text-center py-4">
                    <i class="bi bi-{{ $report->status === 'selesai' ? 'check-circle-fill text-success' : 'x-circle-fill text-danger' }} fs-2 d-block mb-2"></i>
                    <h6 class="fw-700">Laporan {{ $report->status_label }}</h6>
                    <p class="text-muted mb-0" style="font-size:.85rem;">Laporan ini sudah tidak dapat diubah statusnya.</p>
                </div>
            </div>
        @endif

        <div class="card">
            <div class="card-header"><i class="bi bi-gear me-2" style="color:var(--primary);"></i>Aksi Lain</div>
            <div class="card-body d-grid gap-2">
                <a href="{{ route('admin.reports.index') }}" class="btn btn-outline-secondary" style="border-radius:10px;">
                    <i class="bi bi-arrow-left me-2"></i>Kembali ke Daftar
                </a>
                <form action="{{ route('admin.reports.destroy', $report) }}" method="POST"
                      class="form-confirm"
                      data-title="Hapus Laporan Permanen"
                      data-text="Laporan ini akan dihapus secara permanen dan tidak dapat dikembalikan."
                      data-icon="warning"
                      data-confirm-text="Ya, Hapus!"
                      data-cancel-text="Batal">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger w-100" style="border-radius:10px;">
                        <i class="bi bi-trash me-2"></i>Hapus Laporan
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
@if($report->latitude && $report->longitude)
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    const map = L.map('map-admin', { scrollWheelZoom: false })
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
<script>
    function toggleRejection(val) {
        document.getElementById('rejectionReasonField').style.display = val === 'ditolak' ? 'block' : 'none';
    }
</script>
@endpush