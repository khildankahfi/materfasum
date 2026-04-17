@extends('layouts.user')

@section('title', 'Notifikasi')
@section('page-title', 'Notifikasi')
@section('breadcrumb')
    <li class="breadcrumb-item active">Notifikasi</li>
@endsection

@section('content')

<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h5 class="fw-700 mb-1">Notifikasi</h5>
        <p class="text-muted mb-0" style="font-size:.88rem;">Riwayat pembaruan status laporan Anda.</p>
    </div>
    @if(auth()->user()->unreadNotifications->count() > 0)
        <form action="{{ route('user.notifications.read-all') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-sm btn-outline-primary" style="border-radius:8px;">
                <i class="bi bi-check-all me-1"></i> Tandai Semua Dibaca
            </button>
        </form>
    @endif
</div>

<div class="card">
    <div class="card-body p-0">
        @if($notifications->isEmpty())
            <div class="text-center py-5 text-muted">
                <i class="bi bi-bell-slash fs-1 d-block mb-2 opacity-50"></i>
                <h6>Belum Ada Notifikasi</h6>
                <p class="mb-0 small">Anda akan mendapatkan notifikasi saat status laporan diperbarui.</p>
            </div>
        @else
            @foreach($notifications as $notif)
                @php
                    $isUnread = is_null($notif->read_at);
                    $statusColors = ['selesai'=>'#10b981','diproses'=>'#0ea5e9','ditolak'=>'#ef4444','menunggu'=>'#f59e0b'];
                    $status = $notif->data['status'] ?? 'menunggu';
                    $color  = $statusColors[$status] ?? '#94a3b8';
                @endphp
                <div class="d-flex gap-3 p-4 border-bottom {{ $isUnread ? '' : '' }}"
                     style="{{ $isUnread ? 'background:#fffbeb;' : '' }}">
                    <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 text-white"
                         style="width:42px;height:42px;background:{{ $color }};">
                        <i class="bi bi-bell-fill" style="font-size:.9rem;"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="d-flex align-items-start justify-content-between gap-2">
                            <div>
                                <div class="fw-600 mb-1" style="font-size:.9rem;">
                                    {{ $notif->data['message'] ?? 'Status laporan diperbarui' }}
                                    @if($isUnread)
                                        <span class="badge bg-danger ms-1" style="font-size:.65rem;">Baru</span>
                                    @endif
                                </div>
                                @if(isset($notif->data['note']) && $notif->data['note'])
                                    <div class="text-muted mb-1" style="font-size:.83rem;">
                                        <i class="bi bi-chat-left-text me-1"></i>{{ $notif->data['note'] }}
                                    </div>
                                @endif
                                <div class="text-muted" style="font-size:.78rem;">
                                    <i class="bi bi-clock me-1"></i>{{ $notif->created_at->diffForHumans() }}
                                    &nbsp;·&nbsp; {{ $notif->created_at->format('d M Y, H:i') }}
                                </div>
                            </div>
                            @if(isset($notif->data['report_id']))
                                <a href="{{ route('user.reports.show', $notif->data['report_id']) }}"
                                   class="btn btn-sm btn-outline-primary flex-shrink-0" style="border-radius:8px;font-size:.78rem;">
                                    <i class="bi bi-eye me-1"></i>Lihat
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach

            <div class="p-3 border-top" style="font-size:.82rem;">
                {{ $notifications->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>
</div>
@endsection
