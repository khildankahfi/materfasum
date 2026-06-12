@extends('layouts.user')

@section('title', 'Notifikasi')

@section('content')
<div class="d-flex flex-column sm:flex-row sm:items-center justify-content-between gap-3 mb-5">
    <div>
        <h4 class="font-extrabold text-2xl tracking-tight text-slate-800 mb-1">
            Pemberitahuan & Update Laporan 🔔
        </h4>
        <p class="text-slate-500 font-medium text-xs">
            Pantau tanggapan dan perkembangan status pengerjaan pengaduan Anda secara berkala.
        </p>
    </div>
    @if(auth()->user()->unreadNotifications->count() > 0)
        <div>
            <form action="{{ route('user.notifications.read-all') }}" method="POST" class="m-0">
                @csrf
                <button type="submit" class="btn btn-blue-light hover:bg-blue-600 hover:text-white px-4 py-2.5 rounded-xl text-xs font-bold hover-lift w-100 flex items-center justify-center gap-1.5">
                    <i class="bi bi-check-all"></i> Tandai Semua Dibaca
                </button>
            </form>
        </div>
    @endif
</div>

<!-- Notification List Card -->
<div class="bg-white rounded-2xl border border-slate-200/60 shadow-sm overflow-hidden">
    @if($notifications->isEmpty())
        <div class="text-center py-12 px-4 text-slate-400">
            <div class="bg-slate-50 text-slate-300 rounded-full h-16 w-16 flex items-center justify-center mx-auto mb-3 border border-slate-100">
                <i class="bi bi-bell-slash fs-2"></i>
            </div>
            <h6 class="font-bold text-slate-700 mb-1">Tidak Ada Notifikasi Baru</h6>
            <p class="text-xs text-slate-400 mb-0 max-w-xs mx-auto">Kami akan memberitahu Anda lewat halaman ini begitu ada pembaruan status laporan dari petugas.</p>
        </div>
    @else
        <div class="divide-y divide-slate-100">
            @foreach($notifications as $notif)
                @php
                    $isUnread = is_null($notif->read_at);
                    
                    // Colors and icons depending on report status
                    $status = $notif->data['status'] ?? 'menunggu';
                    $bgColors = ['selesai'=>'bg-emerald-50 text-emerald-600 border-emerald-100','diproses'=>'bg-blue-50 text-blue-600 border-blue-100','ditolak'=>'bg-rose-50 text-rose-600 border-rose-100','menunggu'=>'bg-amber-50 text-amber-600 border-amber-100'];
                    $icons = ['selesai'=>'bi-check-lg','diproses'=>'bi-gear-wide-connected','ditolak'=>'bi-x-lg','menunggu'=>'bi-hourglass-split'];
                    
                    $badgeStyle = $bgColors[$status] ?? 'bg-slate-50 text-slate-600 border-slate-100';
                    $iconStyle = $icons[$status] ?? 'bi-bell';
                @endphp
                <div class="p-4 flex gap-4 transition-colors duration-150 {{ $isUnread ? 'bg-amber-50/10' : 'hover:bg-slate-50/40' }}">
                    
                    <!-- Notification Badge Icon -->
                    <div class="rounded-xl h-11 w-11 flex items-center justify-center flex-shrink-0 border {{ $badgeStyle }} shadow-sm">
                        <i class="bi {{ $iconStyle }} fs-5"></i>
                    </div>

                    <!-- Notification Contents -->
                    <div class="flex-grow min-w-0">
                        <div class="flex items-start justify-between gap-3 flex-wrap sm:flex-nowrap">
                            <div class="space-y-1">
                                <div class="font-bold text-sm text-slate-800 leading-snug">
                                    {{ $notif->data['message'] ?? 'Pembalasan status laporan.' }}
                                    @if($isUnread)
                                        <span class="bg-rose-500 text-white text-[8px] font-extrabold uppercase px-2 py-0.5 rounded-full ms-1.5 align-middle tracking-wider shadow-sm shadow-rose-500/10">Baru</span>
                                    @endif
                                </div>
                                
                                @if(isset($notif->data['note']) && $notif->data['note'])
                                    <div class="bg-slate-50 p-2.5 rounded-xl border border-slate-100 text-xs text-slate-650 font-medium leading-normal flex items-start gap-1.5 max-w-2xl">
                                        <i class="bi bi-chat-left-quote text-slate-400 mt-0.5 flex-shrink-0"></i>
                                        <span>Catatan petugas: "{{ $notif->data['note'] }}"</span>
                                    </div>
                                @endif
                                
                                <div class="text-slate-400 text-[10px] font-bold uppercase tracking-wider flex items-center gap-1.5 pt-0.5">
                                    <span><i class="bi bi-clock me-1"></i>{{ $notif->created_at->diffForHumans() }}</span>
                                    <span>•</span>
                                    <span>{{ $notif->created_at->format('d M Y, H:i') }} WIB</span>
                                </div>
                            </div>

                            <!-- Mark as Read / View link -->
                            <div class="flex items-center gap-2 flex-shrink-0">
                                @if(isset($notif->data['report_id']))
                                    <a href="{{ route('user.reports.show', $notif->data['report_id']) }}" class="btn btn-sm btn-light border border-slate-200 text-slate-600 rounded-lg text-[10px] font-bold px-3 py-2 flex items-center gap-1 hover-lift text-decoration-none">
                                        <i class="bi bi-eye"></i> Detail
                                    </a>
                                @endif

                                @if($isUnread)
                                    <form action="{{ route('user.notifications.read', $notif->id) }}" method="POST" class="m-0">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-light border border-slate-200 text-slate-450 hover:text-blue-600 rounded-lg text-xs font-bold p-2 flex items-center justify-center hover-lift" title="Tandai sudah dibaca">
                                            <i class="bi bi-check-lg fs-6"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>

                </div>
            @endforeach
        </div>

        <!-- Pagination Section -->
        <div class="p-4 border-top border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-3 text-[10px] font-bold text-slate-400 bg-slate-50/50">
            <span>
                Menampilkan halaman {{ $notifications->currentPage() }} dari {{ $notifications->lastPage() }}
            </span>
            <div>
                {{ $notifications->links('pagination::bootstrap-5') }}
            </div>
        </div>
    @endif
</div>
@endsection

@push('styles')
<style>
    .btn-blue-light {
        background-color: #eff6ff;
        color: #2563eb;
        border: 1px solid #dbeafe;
    }
    .btn-blue-light:hover {
        background-color: #2563eb;
        color: #ffffff;
    }
    .pagination {
        margin-bottom: 0;
        gap: 4px;
    }
    .page-item .page-link {
        border-radius: 8px !important;
        border: 1px solid #e2e8f0;
        padding: 0.4rem 0.8rem;
        color: #475569;
        font-size: 0.75rem;
        font-weight: 700;
        transition: all 0.2s;
    }
    .page-item.active .page-link {
        background-color: #2563eb;
        border-color: #2563eb;
        color: #ffffff;
    }
    .page-item.disabled .page-link {
        color: #94a3b8;
        background-color: #f8fafc;
    }
    .page-link:hover {
        background-color: #f1f5f9;
        color: #1e293b;
    }
</style>
@endpush
