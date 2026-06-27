@extends('layouts.user')

@section('title', 'Detail Laporan')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
<style>
    #map-detail {
        height: 280px;
        border-radius: 16px;
        z-index: 1;
        border: 1px solid #e2e8f0;
    }
    .photo-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
        gap: 0.75rem;
    }
    .photo-grid img {
        width: 100%;
        aspect-ratio: 1;
        object-fit: cover;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        cursor: pointer;
        transition: all 0.2s;
    }
    .photo-grid img:hover {
        transform: scale(1.03);
        box-shadow: 0 4px 10px rgba(0,0,0,0.05);
    }
    
    /* Timeline styles */
    .timeline {
        position: relative;
        padding-left: 2.2rem;
    }
    .timeline::before {
        content: '';
        position: absolute;
        left: 17px;
        top: 10px;
        bottom: 10px;
        width: 2px;
        background: #e2e8f0;
    }
    .timeline-item {
        position: relative;
        padding-bottom: 2rem;
    }
    .timeline-item:last-child {
        padding-bottom: 0;
    }
    .timeline-dot {
        position: absolute;
        left: -2.2rem;
        top: 2px;
        width: 36px;
        height: 36px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 4px solid #fff;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        color: #fff;
        z-index: 2;
    }
</style>
@endpush

@section('content')
<div class="mb-5 flex flex-column md:flex-row md:items-center justify-content-between gap-3">
    <div>
        <h4 class="font-extrabold text-2xl tracking-tight text-slate-800 mb-1">
            Detail Laporan Aduan 🔍
        </h4>
        <p class="text-slate-500 font-medium text-xs">
            Pantau detail laporan Anda beserta status penanganan terbarunya.
        </p>
    </div>
    <div>
        <a href="{{ route('user.reports.index') }}" class="btn btn-light border border-slate-200 text-slate-600 px-4 py-2.5 rounded-xl text-xs font-bold hover-lift text-decoration-none flex items-center justify-center gap-1.5">
            <i class="bi bi-arrow-left"></i> Kembali ke Riwayat
        </a>
    </div>
</div>

<div class="row g-4">
    <!-- Left Column: Details & Updates -->
    <div class="col-12 col-lg-8">
        
        <!-- Main Info Card -->
        <div class="bg-white rounded-2xl border border-slate-200/60 p-4 sm:p-5 shadow-sm mb-4">
            <div class="d-flex align-items-center justify-content-between gap-3 mb-4">
                <span class="bg-slate-100 text-slate-755 text-xs font-bold py-1 px-3 rounded-lg border border-slate-200/40">
                    {{ $report->category_label }}
                </span>
                <span class="status-badge status-{{ $report->status }} py-1 px-3">
                    <span class="h-1.5 w-1.5 rounded-full bg-current"></span>
                    {{ $report->status_label }}
                </span>
            </div>

            <h4 class="font-bold text-slate-800 mb-3 leading-snug">{{ $report->title }}</h4>
            
            <div class="flex items-center gap-2 text-slate-400 font-bold text-xs mb-4">
                <span><i class="bi bi-calendar3 me-1"></i>{{ $report->created_at->format('d M Y, H:i') }} WIB</span>
                <span>•</span>
                <span><i class="bi bi-person me-1"></i>Pelapor: Anda</span>
            </div>

            <hr class="border-slate-100 my-4">

            <div class="space-y-4">
                <!-- Location -->
                <div>
                    <label class="text-[10px] font-bold text-slate-450 uppercase tracking-wider mb-1.5 block">Lokasi Kerusakan</label>
                    <p class="text-sm font-bold text-slate-700 mb-0">
                        <i class="bi bi-geo-alt-fill text-rose-500 me-1.5"></i>{{ $report->location }}
                    </p>
                </div>

                @if($report->department_id || $report->target_completion_date)
                    <div class="row g-3">
                        @if($report->department_id)
                            <div class="col-sm-6">
                                <label class="text-[10px] font-bold text-slate-450 uppercase tracking-wider mb-1.5 block">Dinas Pelaksana</label>
                                <p class="text-sm font-bold text-slate-700 mb-0">
                                    <i class="bi bi-building me-1.5 text-slate-400"></i>{{ $report->department->name }}
                                </p>
                            </div>
                        @endif
                        @if($report->target_completion_date)
                            <div class="col-sm-6">
                                <label class="text-[10px] font-bold text-slate-450 uppercase tracking-wider mb-1.5 block">Target Selesai (SLA)</label>
                                <p class="text-sm font-bold text-slate-700 mb-0">
                                    <i class="bi bi-calendar-event me-1.5 text-slate-400"></i>{{ $report->target_completion_date->format('d M Y') }}
                                    <span class="badge bg-blue-50 text-blue-600 border border-blue-100 ms-1">{{ $report->sla_remaining_days }}</span>
                                </p>
                            </div>
                        @endif
                    </div>
                @endif

                <!-- Description -->
                <div>
                    <label class="text-[10px] font-bold text-slate-450 uppercase tracking-wider mb-1.5 block">Deskripsi Kerusakan</label>
                    <p class="text-slate-650 text-xs leading-relaxed mb-0 bg-slate-50 p-3.5 rounded-2xl border border-slate-100/50" style="white-space: pre-line; font-weight: 500;">{{ $report->description }}</p>
                </div>

                <!-- Photos -->
                @php $allPhotos = $report->all_photos; @endphp
                @if(count($allPhotos) > 0)
                    <div>
                        <label class="text-[10px] font-bold text-slate-450 uppercase tracking-wider mb-2.5 block">Foto Kerusakan ({{ count($allPhotos) }})</label>
                        <div class="photo-grid">
                            @foreach($allPhotos as $url)
                                <img src="{{ $url }}" alt="Foto laporan" onclick="window.open(this.src,'_blank')" title="Klik untuk memperbesar">
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Rejection Reason -->
                @if($report->status === 'ditolak' && $report->rejection_reason)
                    <div class="bg-rose-50 border border-rose-200/50 rounded-2xl p-4 mt-4">
                        <h6 class="font-bold text-rose-800 text-xs mb-1"><i class="bi bi-x-circle-fill me-2"></i>Alasan Penolakan</h6>
                        <p class="text-xs text-rose-700 font-semibold mb-0 leading-normal">{{ $report->rejection_reason }}</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Location Map Card -->
        @if($report->latitude && $report->longitude)
            <div class="bg-white rounded-2xl border border-slate-200/60 p-4 sm:p-5 shadow-sm mb-4">
                <div class="mb-3">
                    <h6 class="font-bold text-slate-800 mb-1">Koordinat Lokasi Peta</h6>
                    <p class="text-[11px] text-slate-400 font-semibold mb-0 uppercase tracking-wider">Lokasi persis pengaduan ditandai pada peta</p>
                </div>
                <div id="map-detail"></div>
            </div>
        @endif

        <!-- Progress Updates Timeline -->
        <div class="bg-white rounded-2xl border border-slate-200/60 p-4 sm:p-5 shadow-sm">
            <div class="mb-4">
                <h5 class="font-bold text-slate-800 mb-1">Status Penanganan Laporan</h5>
                <p class="text-xs text-slate-400 font-semibold mb-0 uppercase tracking-wider">Riwayat pembaruan pengerjaan oleh petugas lapangan</p>
            </div>

            <!-- Horizontal Progress Indicator -->
            <div class="mb-5 px-2">
                @php
                    $steps = ['menunggu','diproses','selesai'];
                    $stepLabels = ['Menunggu','Diproses','Selesai'];
                    $currentIdx = array_search($report->status, $steps);
                    if ($report->status === 'ditolak') $currentIdx = 1;
                @endphp
                <div class="d-flex align-items-center justify-content-between position-relative">
                    <div class="position-absolute top-[16px] start-[10%] end-[10%] h-[2px] bg-slate-100 z-0"></div>
                    <div class="position-absolute top-[16px] start-[10%] h-[2px] bg-blue-600 z-1 transition-all duration-500"
                         style="width: {{ $report->status === 'ditolak' ? '40%' : ($currentIdx >= 2 ? '80%' : ($currentIdx * 40)) . '%' }}"></div>

                    @foreach($steps as $idx => $step)
                        @php
                            $isDone = $currentIdx !== false && $idx < $currentIdx;
                            $isCurrent = $idx === $currentIdx && $report->status !== 'ditolak';
                            $isRejected = $report->status === 'ditolak' && $idx === 1;
                        @endphp
                        <div class="text-center z-10 flex-grow-1">
                            <div class="rounded-full mx-auto h-8 w-8 flex items-center justify-center text-xs font-bold border-4 border-white shadow-sm
                                 {{ $isDone ? 'bg-emerald-500 text-white' : ($isCurrent ? 'bg-blue-600 text-white' : ($isRejected ? 'bg-rose-500 text-white' : 'bg-slate-100 text-slate-400')) }}">
                                @if($isDone)
                                    <i class="bi bi-check-lg"></i>
                                @elseif($isRejected)
                                    <i class="bi bi-x-lg"></i>
                                @else
                                    {{ $idx + 1 }}
                                @endif
                            </div>
                            <span class="text-[10px] font-bold block mt-1.5 uppercase tracking-wider
                                  {{ $isDone ? 'text-emerald-600' : ($isCurrent ? 'text-blue-600' : ($isRejected ? 'text-rose-600' : 'text-slate-400')) }}">
                                {{ $stepLabels[$idx] }}
                                @if($isRejected)
                                    <br><span class="text-[9px] lowercase font-semibold text-rose-500">(ditolak)</span>
                                @endif
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Vertical Detailed Timeline -->
            @if($report->updates->isEmpty())
                <div class="text-center py-6 text-slate-400 bg-slate-50/50 rounded-xl border border-dashed border-slate-200">
                    <i class="bi bi-hourglass-split fs-2 d-block mb-1.5 text-slate-300"></i>
                    <p class="mb-0 text-xs font-semibold">Laporan baru dikirimkan. Menunggu tinjauan dinas verifikator.</p>
                </div>
            @else
                <div class="timeline">
                    @foreach($report->updates as $update)
                        @php
                            $dotColor = match($update->status) {
                                'diproses' => 'bg-blue-500', 'selesai' => 'bg-emerald-500',
                                'ditolak' => 'bg-rose-500', default => 'bg-slate-400'
                            };
                            $dotIcon = match($update->status) {
                                'diproses' => 'bi-gear-wide-connected', 'selesai' => 'bi-check-lg',
                                'ditolak' => 'bi-x-lg', default => 'bi-clock'
                            };
                            $borderCol = match($update->status) {
                                'diproses' => 'border-blue-200/60', 'selesai' => 'border-emerald-200/60',
                                'ditolak' => 'border-rose-200/60', default => 'border-slate-200/60'
                            };
                        @endphp
                        <div class="timeline-item">
                            <div class="timeline-dot {{ $dotColor }}">
                                <i class="bi {{ $dotIcon }}"></i>
                            </div>
                            <div class="bg-slate-50/40 p-3.5 rounded-2xl border border-slate-100 space-y-2">
                                <div class="flex items-center justify-between gap-3 flex-wrap">
                                    <span class="text-xs font-bold text-slate-700">
                                        Petugas mengubah status menjadi: 
                                        <span class="status-badge status-{{ $update->status }} ms-1 text-[10px] py-0.5 px-2">
                                            {{ $update->status_label }}
                                        </span>
                                    </span>
                                    <small class="text-slate-400 text-[10px] font-bold">{{ $update->created_at->format('d M Y, H:i') }} WIB</small>
                                </div>
                                <div class="text-slate-400 font-bold text-[10px]"><i class="bi bi-person-check me-1"></i>Verifikator: {{ $update->admin->name }}</div>

                                @if($update->note)
                                    <div class="p-3 bg-white rounded-xl border-l-4 {{ $update->status === 'diproses' ? 'border-blue-500' : ($update->status === 'selesai' ? 'border-emerald-500' : 'border-rose-500') }} text-xs font-semibold text-slate-600 leading-normal">
                                        <i class="bi bi-chat-left-quote me-1 text-slate-400"></i>{{ $update->note }}
                                    </div>
                                @endif

                                @if($update->photo_after)
                                    <div class="mt-2.5">
                                        <img src="{{ Storage::url($update->photo_after) }}" class="rounded-xl border border-slate-200/60 cursor-pointer max-h-36 object-cover" onclick="window.open(this.src,'_blank')">
                                        <div class="text-[10px] text-slate-400 font-semibold mt-1"><i class="bi bi-image me-1"></i>Kondisi penanganan setelah pengerjaan selesai</div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        @if($report->status === 'selesai')
            <div class="bg-white rounded-2xl border border-slate-200/60 p-4 sm:p-5 shadow-sm mb-4">
                <div class="mb-3">
                    <h6 class="font-bold text-slate-800 mb-1">⭐ Penilaian Kinerja Perbaikan</h6>
                    <p class="text-[11px] text-slate-400 font-semibold mb-0 uppercase tracking-wider">Berikan ulasan Anda terkait penanganan aduan ini</p>
                </div>
                
                @if($report->rating === null)
                    @if(auth()->id() === $report->user_id)
                        <form action="{{ route('user.reports.rate', $report) }}" method="POST" class="space-y-4">
                            @csrf
                            <div class="d-flex align-items-center gap-2">
                                <span class="text-xs font-semibold text-slate-500">Nilai:</span>
                                <div class="star-rating d-flex gap-1.5 fs-4">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="bi bi-star text-slate-300 cursor-pointer star-btn" data-value="{{ $i }}" onclick="setRating({{ $i }})"></i>
                                    @endfor
                                </div>
                                <input type="hidden" name="rating" id="ratingValue" value="">
                            </div>
                            
                            <div>
                                <textarea name="rating_comment" rows="3" class="form-control border-slate-200/80 text-xs shadow-none" placeholder="Tuliskan ulasan atau terima kasih untuk petugas lapangan (opsional)..." style="border-radius: 12px; resize: none;"></textarea>
                            </div>
                            
                            <button type="submit" class="btn btn-sm btn-primary px-3 py-2 rounded-lg text-xs font-bold hover-lift">
                                Kirim Penilaian
                            </button>
                        </form>
                    @else
                        <div class="p-3 bg-slate-50 rounded-xl text-xs text-slate-400 font-medium text-center">
                            <i class="bi bi-info-circle me-1"></i> Menunggu ulasan pengerjaan dari pelapor aduan.
                        </div>
                    @endif
                @else
                    <div class="p-3 bg-slate-50/50 rounded-xl border border-slate-100 space-y-2">
                        <div class="d-flex align-items-center gap-1.5">
                            <span class="text-xs font-semibold text-slate-500">Penilaian:</span>
                            <div class="text-amber-500 fs-5">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="bi bi-{{ $i <= $report->rating ? 'star-fill' : 'star' }}"></i>
                                @endfor
                            </div>
                        </div>
                        @if($report->rating_comment)
                            <div class="p-3 bg-white rounded-lg border border-slate-100 text-xs font-semibold text-slate-600 leading-normal">
                                <i class="bi bi-chat-left-quote me-1 text-slate-400"></i>{{ $report->rating_comment }}
                            </div>
                        @endif
                    </div>
                @endif
            </div>
        @endif

        <!-- Discussion Thread Card -->
        <div class="bg-white rounded-2xl border border-slate-200/60 p-4 sm:p-5 shadow-sm mt-4">
            <div class="mb-4">
                <h5 class="font-bold text-slate-800 mb-1"><i class="bi bi-chat-dots me-2 text-blue-500"></i>Diskusi Laporan</h5>
                <p class="text-xs text-slate-400 font-semibold mb-0 uppercase tracking-wider">Tanya jawab dan koordinasi penyelesaian laporan</p>
            </div>

            @if($report->comments->isEmpty())
                <div class="text-center py-5 text-slate-400 bg-slate-50/50 rounded-xl border border-dashed border-slate-200 mb-4">
                    <i class="bi bi-chat-square-text fs-3 d-block mb-1.5 text-slate-300"></i>
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

            @if(auth()->id() === $report->user_id || auth()->user()->isAdmin())
                <form action="{{ route('user.reports.comments.store', $report) }}" method="POST" class="m-0">
                    @csrf
                    <div class="input-group">
                        <textarea name="body" rows="1" class="form-control border-slate-200/80 text-xs shadow-none py-2.5 px-3" placeholder="Tulis tanggapan atau komentar diskusi..." style="border-radius:12px 0 0 12px; resize:none;"></textarea>
                        <button type="submit" class="btn btn-primary px-4 rounded-xl font-bold text-xs" style="border-radius:0 12px 12px 0;">
                            <i class="bi bi-send-fill"></i>
                        </button>
                    </div>
                </form>
            @else
                <div class="p-3 bg-slate-50 rounded-xl text-xs text-slate-400 font-medium text-center">
                    <i class="bi bi-lock-fill me-1"></i> Diskusi ini hanya terbuka untuk pelapor dan pihak administrator.
                </div>
            @endif
        </div>

    </div>

    <!-- Right Column: Actions & Quick Status -->
    <div class="col-12 col-lg-4">
        
        <!-- Status Box -->
        <div class="bg-white rounded-2xl border border-slate-200/60 p-4 sm:p-5 shadow-sm mb-4 text-center">
            @php
                $statusColors = ['menunggu'=>'#f59e0b','diproses'=>'#0284c7','selesai'=>'#10b981','ditolak'=>'#f43f5e'];
                $statusIcons = ['menunggu'=>'bi-hourglass-split','diproses'=>'bi-gear-wide-connected','selesai'=>'bi-check-circle-fill','ditolak'=>'bi-x-circle-fill'];
            @endphp
            <div class="rounded-full h-14 w-14 flex items-center justify-center mx-auto mb-3 shadow-md border border-white text-white animate-pulse"
                 style="background-color: {{ $statusColors[$report->status] }};">
                <i class="bi {{ $statusIcons[$report->status] }} fs-4"></i>
            </div>
            <h5 class="font-extrabold text-slate-800 mb-1.5">{{ $report->status_label }}</h5>
            <p class="text-slate-400 font-semibold text-xs leading-normal mb-0 max-w-xs mx-auto">
                @if($report->status === 'menunggu')
                    Laporan aduan telah terdaftar di database kami dan sedang menunggu verifikasi oleh verifikator kota.
                @elseif($report->status === 'diproses')
                    Laporan telah divalidasi dan saat ini sedang dalam pengerjaan pemeliharaan oleh dinas terkait.
                @elseif($report->status === 'selesai')
                    Fasilitas umum yang dilaporkan telah selesai diperbaiki. Terima kasih telah ikut berpartisipasi menjaga kota!
                @else
                    Maaf, laporan Anda ditolak karena belum memenuhi kriteria kelayakan fasilitas umum atau data kurang valid.
                @endif
            </p>
        </div>

        <!-- Action Card -->
        <div class="bg-white rounded-2xl border border-slate-200/60 p-4 shadow-sm space-y-2.5">
            <h6 class="font-bold text-slate-800 mb-3"><i class="bi bi-shield-lock me-2 text-slate-450"></i>Panel Kontrol Aduan</h6>
            
            <a href="{{ route('user.reports.index') }}" class="btn btn-light border border-slate-200 text-slate-600 rounded-xl py-2 px-3 text-xs font-bold text-decoration-none w-100 flex items-center justify-center gap-1.5 hover-lift">
                <i class="bi bi-arrow-left"></i> Kembali ke Riwayat
            </a>

            <!-- Upvote / Dukungan Button -->
            @if(auth()->id() !== $report->user_id)
                <form action="{{ route('user.reports.support', $report) }}" method="POST" class="m-0">
                    @csrf
                    @php $isSupported = $report->isSupportedBy(auth()->id()); @endphp
                    <button type="submit" class="btn {{ $isSupported ? 'btn-danger shadow-danger/10' : 'btn-outline-primary' }} rounded-xl py-2.5 px-3 text-xs font-bold w-100 flex items-center justify-center gap-1.5 hover-lift">
                        <i class="bi {{ $isSupported ? 'bi-heart-fill' : 'bi-heart' }}"></i>
                        {{ $isSupported ? 'Batal Dukung Aduan' : 'Dukung Aduan Ini' }}
                        <span class="badge {{ $isSupported ? 'bg-white text-danger' : 'bg-primary text-white' }} rounded-pill ms-1">
                            {{ $report->supports()->count() }}
                        </span>
                    </button>
                </form>
            @else
                <div class="btn btn-light border border-slate-200 text-slate-500 rounded-xl py-2.5 px-3 text-xs font-bold w-100 flex items-center justify-center gap-1.5 cursor-default">
                    <i class="bi bi-heart-fill text-rose-500"></i>
                    Dukungan Warga: 
                    <span class="badge bg-slate-200 text-slate-700 rounded-pill ms-1">
                        {{ $report->supports()->count() }}
                    </span>
                </div>
            @endif

            @if($report->user_id === auth()->id() && $report->status === 'menunggu')
                <a href="{{ route('user.reports.edit', $report) }}" class="btn btn-light border border-slate-200 text-amber-600 rounded-xl py-2 px-3 text-xs font-bold text-decoration-none w-100 flex items-center justify-center gap-1.5 hover-lift">
                    <i class="bi bi-pencil-square"></i> Edit Data Laporan
                </a>
                
                <form action="{{ route('user.reports.destroy', $report) }}" method="POST" class="form-confirm m-0">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-light border border-slate-200 text-rose-600 rounded-xl py-2 px-3 text-xs font-bold w-100 flex items-center justify-center gap-1.5 hover-lift">
                        <i class="bi bi-trash"></i> Hapus Laporan
                    </button>
                </form>
            @else
                @if($report->user_id === auth()->id())
                    <div class="bg-slate-50 text-slate-400 rounded-xl p-3 border border-slate-100 text-[10px] font-semibold leading-normal text-start">
                        <i class="bi bi-lock-fill me-1"></i> Kontrol ubah laporan telah dikunci secara otomatis karena aduan ini sudah mulai ditinjau/diproses oleh petugas.
                    </div>
                @endif
            @endif
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
    
    // Custom pin color based on status
    const color = "{{ $report->status === 'selesai' ? '#10b981' : ($report->status === 'diproses' ? '#0284c7' : '#f59e0b') }}";
    const svgIcon = L.divIcon({
        html: `<svg width="30" height="42" viewBox="0 0 30 42" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M15 0C6.71573 0 0 6.71573 0 15C0 24.375 15 42 15 42C15 42 30 24.375 30 15C30 6.71573 23.2843 0 15 0ZM15 20.5C11.9624 20.5 9.5 18.0376 9.5 15C9.5 11.9624 11.9624 9.5 15 9.5C18.0376 9.5 20.5 11.9624 20.5 15C20.5 18.0376 18.0376 20.5 15 20.5Z" fill="${color}"/>
               </svg>`,
        className: "",
        iconSize: [30, 42],
        iconAnchor: [15, 42],
        popupAnchor: [0, -42]
    });

    L.marker([{{ $report->latitude }}, {{ $report->longitude }}], { icon: svgIcon })
     .addTo(map)
     .bindPopup('<strong>{{ addslashes($report->title) }}</strong><br>{{ addslashes($report->location) }}')
     .openPopup();
</script>
@endif

<script>
    function setRating(val) {
        document.getElementById('ratingValue').value = val;
        const stars = document.querySelectorAll('.star-btn');
        stars.forEach((star, index) => {
            if (index < val) {
                star.classList.remove('bi-star');
                star.classList.add('bi-star-fill', 'text-amber-500');
                star.classList.remove('text-slate-300');
            } else {
                star.classList.remove('bi-star-fill', 'text-amber-500');
                star.classList.add('bi-star', 'text-slate-300');
            }
        });
    }
</script>
@endpush