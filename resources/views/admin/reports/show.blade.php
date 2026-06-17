@extends('layouts.admin')

@section('title', 'Detail Laporan')
@section('page-title', 'Detail Laporan')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.reports.index') }}" class="text-decoration-none text-slate-400">Laporan</a></li>
    <li class="breadcrumb-item active">Detail</li>
@endsection

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
<style>
    #map-admin { height: 240px; border-radius: 16px; z-index: 0; border: 1px solid #e2e8f0; }

    .timeline { position: relative; padding-left: 2.5rem; }
    .timeline::before {
        content: ''; position: absolute; left: 17px; top: 10px; bottom: 10px;
        width: 2px; background: #e2e8f0;
    }
    .timeline-item { position: relative; padding-bottom: 1.75rem; }
    .timeline-item:last-child { padding-bottom: 0; }
    .timeline-dot {
        position: absolute; left: -2.5rem; top: 2px;
        width: 36px; height: 36px; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        border: 3px solid #fff; box-shadow: 0 2px 6px rgba(0,0,0,.06);
        color: #fff; z-index: 2; font-size: .85rem;
    }
    .photo-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(110px, 1fr)); gap: .65rem; }
    .photo-grid img {
        width: 100%; aspect-ratio: 1; object-fit: cover; border-radius: 12px;
        border: 1px solid #e2e8f0; cursor: pointer; transition: all .2s;
    }
    .photo-grid img:hover { transform: scale(1.03); box-shadow: 0 4px 12px rgba(0,0,0,.06); }

    .status-badge {
        font-weight: 600; font-size: .72rem; padding: .3em .75em;
        border-radius: 9999px; display: inline-flex; align-items: center; gap: 4px;
    }
    .badge-menunggu { background: #fef3c7; color: #d97706; }
    .badge-diproses { background: #e0f2fe; color: #0284c7; }
    .badge-selesai  { background: #d1fae5; color: #059669; }
    .badge-ditolak  { background: #ffe4e6; color: #e11d48; }
</style>
@endpush

@section('content')

<div class="row g-4">

    {{-- Left Column: Detail Info --}}
    <div class="col-12 col-lg-8">

        {{-- Main Info Card --}}
        <div class="bg-white rounded-2xl border border-slate-200/60 p-4 sm:p-5 shadow-sm mb-4">
            <div class="d-flex align-items-center justify-between gap-3 mb-4">
                <div class="d-flex align-items-center gap-2">
                    <span class="bg-slate-100 text-slate-700 text-[10px] font-bold py-1 px-3 rounded-lg border border-slate-200/40">
                        {{ $report->category_label }}
                    </span>
                    <span class="bg-rose-50 text-rose-700 text-[10px] font-bold py-1 px-3 rounded-lg border border-rose-100">
                        <i class="bi bi-heart-fill me-1"></i>{{ $report->supports->count() }} Didukung Warga
                    </span>
                </div>
                <span class="status-badge badge-{{ $report->status }}">
                    <span class="h-1.5 w-1.5 rounded-full bg-current"></span>
                    {{ $report->status_label }}
                </span>
            </div>

            <h4 class="font-extrabold text-slate-800 mb-3 leading-snug">{{ $report->title }}</h4>

            <div class="row g-3 mb-4">
                <div class="col-sm-6">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Kategori</p>
                    <p class="text-sm font-semibold text-slate-700 mb-0">{{ $report->category_label }}</p>
                </div>
                <div class="col-sm-6">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Tanggal Lapor</p>
                    <p class="text-sm font-semibold text-slate-700 mb-0">{{ $report->created_at->format('d F Y, H:i') }} WIB</p>
                </div>
                <div class="col-12">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Lokasi Kerusakan</p>
                    <p class="text-sm font-semibold text-slate-700 mb-0">
                        <i class="bi bi-geo-alt-fill text-rose-500 me-1.5"></i>{{ $report->location }}
                    </p>
                </div>
                <div class="col-sm-6">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Pelapor</p>
                    <div class="d-flex align-items-center gap-2">
                        <div class="rounded-lg bg-indigo-600 text-white font-bold h-8 w-8 d-flex align-items-center justify-content-center shadow-sm flex-shrink-0" style="font-size:.75rem;">
                            {{ strtoupper(substr($report->user->name, 0, 1)) }}
                        </div>
                        <div>
                            <div class="font-bold text-sm text-slate-800">{{ $report->user->name }}</div>
                            <small class="text-slate-400 font-medium">{{ $report->user->email }}</small>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">No. Telepon</p>
                    <p class="text-sm font-semibold text-slate-700 mb-0">{{ $report->user->phone ?? '-' }}</p>
                </div>
            </div>

            <hr class="border-slate-100 my-4">

            {{-- Peta Lokasi --}}
            @if($report->latitude && $report->longitude)
                <div class="mb-4">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2">Peta Lokasi</p>
                    <div id="map-admin"></div>
                </div>
            @endif

            {{-- Deskripsi --}}
            <div class="mb-4">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2">Deskripsi Kerusakan</p>
                <p class="text-slate-700 text-sm leading-relaxed bg-slate-50 p-3.5 rounded-2xl border border-slate-100/50 mb-0"
                   style="white-space:pre-line;font-weight:500;">{{ $report->description }}</p>
            </div>

            {{-- Photos --}}
            @php $allPhotos = $report->all_photos; @endphp
            @if(count($allPhotos) > 0)
                <div class="mb-4">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2">
                        Foto Kerusakan ({{ count($allPhotos) }} foto)
                    </p>
                    <div class="photo-grid">
                        @foreach($allPhotos as $url)
                            <img src="{{ $url }}" alt="Foto laporan" onclick="window.open(this.src,'_blank')" title="Klik untuk perbesar">
                        @endforeach
                    </div>
                </div>
            @endif

            @if($report->status === 'ditolak' && $report->rejection_reason)
                <div class="bg-rose-50 border border-rose-200/50 rounded-2xl p-4">
                    <h6 class="font-bold text-rose-800 text-xs mb-1"><i class="bi bi-x-circle-fill me-2"></i>Alasan Penolakan</h6>
                    <p class="text-xs text-rose-700 font-semibold mb-0 leading-normal">{{ $report->rejection_reason }}</p>
                </div>
            @endif

            @if($report->rating !== null)
                <div class="bg-amber-50/50 border border-amber-200/50 rounded-2xl p-4 mt-4 space-y-2">
                    <div class="d-flex align-items-center gap-1.5">
                        <h6 class="font-bold text-slate-800 text-xs mb-0">⭐ Penilaian Kinerja dari Pelapor</h6>
                        <div class="text-amber-500 text-xs">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="bi bi-{{ $i <= $report->rating ? 'star-fill' : 'star' }}"></i>
                            @endfor
                        </div>
                    </div>
                    @if($report->rating_comment)
                        <p class="text-xs text-slate-600 font-semibold mb-0 leading-normal bg-white p-3 rounded-xl border border-slate-100">
                            <i class="bi bi-chat-left-quote me-1 text-slate-400"></i>{{ $report->rating_comment }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        {{-- Timeline Riwayat --}}
        <div class="bg-white rounded-2xl border border-slate-200/60 p-4 sm:p-5 shadow-sm">
            <div class="mb-4">
                <h5 class="font-bold text-slate-800 mb-1"><i class="bi bi-clock-history me-2 text-indigo-500"></i>Riwayat Pembaruan Status</h5>
                <p class="text-[11px] text-slate-400 font-semibold mb-0 uppercase tracking-wider">Log tindakan admin terhadap laporan ini</p>
            </div>

            @if($report->updates->isEmpty())
                <div class="text-center py-8 text-slate-400 bg-slate-50/50 rounded-xl border border-dashed border-slate-200">
                    <i class="bi bi-hourglass-split fs-2 d-block mb-2 text-slate-300"></i>
                    <p class="mb-0 text-xs font-semibold">Belum ada riwayat pembaruan. Laporan menunggu tindakan admin.</p>
                </div>
            @else
                <div class="timeline">
                    @foreach($report->updates as $update)
                        @php
                            $dotBg = match($update->status) {
                                'diproses' => 'bg-sky-500', 'selesai' => 'bg-emerald-500',
                                'ditolak'  => 'bg-rose-500', default => 'bg-slate-400'
                            };
                            $dotIcon = match($update->status) {
                                'diproses' => 'gear-wide-connected', 'selesai' => 'check-lg',
                                'ditolak'  => 'x-lg', default => 'clock'
                            };
                        @endphp
                        <div class="timeline-item">
                            <div class="timeline-dot {{ $dotBg }}">
                                <i class="bi bi-{{ $dotIcon }}"></i>
                            </div>
                            <div class="bg-slate-50/40 p-3.5 rounded-2xl border border-slate-100 space-y-2">
                                <div class="d-flex align-items-start justify-content-between gap-2 flex-wrap">
                                    <span class="text-xs font-bold text-slate-700">
                                        Status diubah ke
                                        <span class="status-badge badge-{{ $update->status }} ms-1 text-[10px]">
                                            <span class="h-1.5 w-1.5 rounded-full bg-current"></span>
                                            {{ $update->status_label }}
                                        </span>
                                    </span>
                                    <small class="text-slate-400 font-bold text-[10px] text-nowrap">
                                        {{ $update->created_at->format('d M Y, H:i') }} WIB
                                    </small>
                                </div>
                                <div class="text-[10px] text-slate-400 font-bold">
                                    <i class="bi bi-person-check me-1"></i>Oleh: {{ $update->admin->name }}
                                </div>
                                @if($update->note)
                                    <div class="p-3 bg-white rounded-xl border-l-4 {{ $update->status === 'selesai' ? 'border-emerald-400' : ($update->status === 'ditolak' ? 'border-rose-400' : 'border-sky-400') }} text-xs font-semibold text-slate-600 leading-normal">
                                        <i class="bi bi-chat-left-quote me-1 text-slate-400"></i>{{ $update->note }}
                                    </div>
                                @endif
                                @if($update->photo_after)
                                    <div>
                                        <img src="{{ Storage::url($update->photo_after) }}"
                                             class="rounded-xl border border-slate-200/60 cursor-pointer object-cover"
                                             style="max-height:150px;"
                                             onclick="window.open(this.src,'_blank')">
                                        <p class="text-[10px] text-slate-400 font-semibold mt-1.5 mb-0">
                                            <i class="bi bi-image me-1"></i>Foto kondisi setelah perbaikan
                                        </p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Discussion Thread Card -->
        <div class="bg-white rounded-2xl border border-slate-200/60 p-4 sm:p-5 shadow-sm mt-4">
            <div class="mb-4">
                <h5 class="font-bold text-slate-800 mb-1"><i class="bi bi-chat-dots me-2 text-blue-500"></i>Diskusi Laporan</h5>
                <p class="text-xs text-slate-400 font-semibold mb-0 uppercase tracking-wider">Tanya jawab dan koordinasi penyelesaian laporan</p>
            </div>

            @if($report->comments->isEmpty())
                <div class="text-center py-5 text-slate-400 bg-slate-50/50 rounded-xl border border-dashed border-slate-200 mb-4">
                    <i class="bi bi-chat-square-text fs-3 d-block mb-1.5 text-slate-350"></i>
                    <p class="mb-0 text-xs font-semibold">Belum ada diskusi di laporan ini.</p>
                </div>
            @else
                <div class="space-y-3 mb-4 max-h-[350px] overflow-y-auto pe-1">
                    @foreach($report->comments as $comment)
                        @php $isAdminComment = $comment->user->isAdmin(); @endphp
                        <div class="p-3 rounded-2xl border {{ $isAdminComment ? 'bg-blue-50/30 border-blue-100/60 ms-5' : 'bg-slate-50/40 border-slate-100 me-5' }}">
                            <div class="d-flex align-items-center justify-content-between mb-1.5">
                                <div class="d-flex align-items-center gap-1.5">
                                    <div class="rounded-full font-bold h-6 w-6 d-flex align-items-center justify-content-center text-[10px] text-white
                                         {{ $isAdminComment ? 'bg-blue-600' : 'bg-slate-500' }}">
                                        {{ strtoupper(substr($comment->user->name, 0, 1)) }}
                                    </div>
                                    <span class="text-xs font-bold text-slate-800">
                                        {{ $comment->user->name }}
                                        @if($isAdminComment)
                                            <span class="badge bg-blue-100 text-blue-700 text-[8px] py-0.5 px-1.5 ms-1">Admin</span>
                                        @endif
                                    </span>
                                </div>
                                <small class="text-slate-400 text-[9px] font-bold">{{ $comment->created_at->diffForHumans() }}</small>
                            </div>
                            <p class="text-xs text-slate-600 font-semibold mb-0 leading-normal" style="white-space: pre-line;">{{ $comment->body }}</p>
                        </div>
                    @endforeach
                </div>
            @endif

            <form action="{{ route('admin.reports.comments.store', $report) }}" method="POST" class="m-0">
                @csrf
                <div class="input-group">
                    <textarea name="body" rows="1" class="form-control border-slate-200/80 text-xs shadow-none py-2.5 px-3" placeholder="Tulis tanggapan atau komentar diskusi..." style="border-radius:12px 0 0 12px; resize:none;"></textarea>
                    <button type="submit" class="btn btn-primary px-4 rounded-xl font-bold text-xs" style="border-radius:0 12px 12px 0;">
                        <i class="bi bi-send-fill"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Right Column: Admin Action Panel --}}
    <div class="col-12 col-lg-4">

        @if(!in_array($report->status, ['selesai', 'ditolak']))
            {{-- Action Form --}}
            <div class="bg-white rounded-2xl border border-slate-200/60 p-4 sm:p-5 shadow-sm mb-4">
                <div class="mb-4">
                    <h5 class="font-bold text-slate-800 mb-1">
                        <i class="bi bi-shield-check me-2 text-indigo-500"></i>Validasi & Update Status
                    </h5>
                    <p class="text-[11px] text-slate-400 font-semibold mb-0 uppercase tracking-wider">Ubah status penanganan laporan</p>
                </div>

                <form action="{{ route('admin.reports.update-status', $report) }}" method="POST"
                      enctype="multipart/form-data" class="form-confirm space-y-4"
                      data-title="Update Status Laporan"
                      data-text="Apakah Anda yakin ingin menyimpan perubahan status laporan ini?"
                      data-icon="question"
                      data-confirm-text="Ya, Simpan!"
                      data-cancel-text="Batal">
                    @csrf

                    @if($errors->any())
                        <div class="alert border-0 shadow-sm rounded-xl d-flex align-items-start gap-2.5 py-3 px-3.5 bg-rose-50 text-rose-800 text-xs font-semibold" role="alert">
                            <i class="bi bi-exclamation-circle-fill flex-shrink-0 mt-0.5"></i>
                            <div>@foreach($errors->all() as $err)<div>{{ $err }}</div>@endforeach</div>
                        </div>
                    @endif

                    <div>
                        <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5 d-block">
                            Status Baru <span class="text-rose-500">*</span>
                        </label>
                        <select name="status" id="statusSelect"
                                class="form-select border-slate-200/80 text-xs shadow-none text-slate-700"
                                style="border-radius:12px; padding:.65rem .8rem; font-weight:600;"
                                onchange="toggleRejection(this.value)">
                            <option value="">— Pilih Status Baru —</option>
                            @if($report->status === 'menunggu')
                                <option value="diproses">▶ Setujui & Proses</option>
                                <option value="ditolak">✕ Tolak Laporan</option>
                            @elseif($report->status === 'diproses')
                                <option value="selesai">✓ Tandai Selesai</option>
                                <option value="ditolak">✕ Tolak Laporan</option>
                            @endif
                        </select>
                    </div>

                    <div>
                        <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5 d-block">Catatan Admin</label>
                        <textarea name="note" rows="3"
                                  class="form-control border-slate-200/80 text-xs shadow-none"
                                  style="border-radius:12px; font-weight:500; resize:none;"
                                  placeholder="Tambahkan catatan tindak lanjut (opsional)...">{{ old('note') }}</textarea>
                    </div>

                    <div id="rejectionReasonField" style="display:none;">
                        <label class="text-[10px] font-bold text-rose-500 uppercase tracking-wider mb-1.5 d-block">
                            Alasan Penolakan <span class="text-rose-500">*</span>
                        </label>
                        <textarea name="rejection_reason" rows="3"
                                  class="form-control text-xs shadow-none"
                                  style="border-radius:12px; font-weight:500; border-color:#fca5a5; resize:none;"
                                  placeholder="Jelaskan alasan penolakan laporan ini...">{{ old('rejection_reason') }}</textarea>
                    </div>

                    <div>
                        <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5 d-block">
                            Foto Sesudah Perbaikan
                        </label>
                        <input type="file" name="photo_after"
                               class="form-control border-slate-200/80 text-xs shadow-none"
                               style="border-radius:12px;" accept="image/*">
                        <p class="text-[10px] text-slate-400 font-semibold mt-1.5 mb-0">Upload foto kondisi setelah diperbaiki (opsional)</p>
                    </div>

                    <button type="submit"
                            class="btn btn-primary w-100 py-2.5 rounded-xl font-bold text-xs shadow-md shadow-indigo-500/10 hover-lift d-flex align-items-center justify-content-center gap-2">
                        <i class="bi bi-check-circle-fill"></i>Simpan Perubahan Status
                    </button>
                </form>
            </div>
        @else
            {{-- Closed Status Card --}}
            <div class="bg-white rounded-2xl border border-slate-200/60 p-5 shadow-sm mb-4 text-center">
                <div class="rounded-full h-14 w-14 flex items-center justify-center mx-auto mb-3 text-white"
                     style="background-color: {{ $report->status === 'selesai' ? '#10b981' : '#f43f5e' }};">
                    <i class="bi bi-{{ $report->status === 'selesai' ? 'check-circle-fill' : 'x-circle-fill' }} fs-4"></i>
                </div>
                <h5 class="font-extrabold text-slate-800 mb-1.5">Laporan {{ $report->status_label }}</h5>
                <p class="text-slate-400 font-semibold text-xs leading-normal mb-0">
                    Laporan ini sudah {{ $report->status === 'selesai' ? 'selesai ditangani' : 'ditolak' }} dan tidak dapat diubah statusnya lagi.
                </p>
            </div>
        @endif

        {{-- Other Actions Card --}}
        <div class="bg-white rounded-2xl border border-slate-200/60 p-4 shadow-sm">
            <h6 class="font-bold text-slate-800 mb-3"><i class="bi bi-gear me-2 text-slate-400"></i>Aksi Lainnya</h6>
            <div class="d-grid gap-2.5">
                <a href="{{ route('admin.reports.index') }}"
                   class="btn btn-light border border-slate-200 text-slate-600 rounded-xl py-2.5 px-3 text-xs font-bold text-decoration-none d-flex align-items-center justify-content-center gap-2 hover-lift">
                    <i class="bi bi-arrow-left"></i> Kembali ke Daftar Laporan
                </a>
                <form action="{{ route('admin.reports.destroy', $report) }}" method="POST"
                      class="form-confirm m-0"
                      data-title="Hapus Laporan Permanen"
                      data-text="Laporan ini akan dihapus secara permanen dan tidak dapat dikembalikan."
                      data-icon="warning"
                      data-confirm-text="Ya, Hapus Permanen!"
                      data-cancel-text="Batal">
                    @csrf @method('DELETE')
                    <button type="submit"
                            class="btn btn-light border border-slate-200 text-rose-500 rounded-xl w-100 py-2.5 px-3 text-xs font-bold d-flex align-items-center justify-content-center gap-2 hover-lift">
                        <i class="bi bi-trash"></i> Hapus Laporan Ini
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