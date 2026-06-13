@extends('layouts.admin')

@section('title', 'Kelola Laporan')
@section('page-title', 'Kelola Laporan')
@section('breadcrumb')
    <li class="breadcrumb-item active">Laporan</li>
@endsection

@push('styles')
<style>
    .btn-blue-light { background-color: #eff6ff; color: #2563eb; border: 1px solid #dbeafe; }
    .btn-blue-light:hover { background-color: #2563eb; color: #fff; }
    .pagination { margin-bottom: 0; gap: 4px; }
    .page-item .page-link {
        border-radius: 8px !important; border: 1px solid #e2e8f0;
        padding: .4rem .8rem; color: #475569; font-size: .75rem; font-weight: 700; transition: all .2s;
    }
    .page-item.active .page-link { background-color: #6366f1; border-color: #6366f1; color: #fff; }
    .page-item.disabled .page-link { color: #94a3b8; background-color: #f8fafc; }
    .page-link:hover { background-color: #f1f5f9; color: #1e293b; }
    .status-badge {
        font-weight: 600; font-size: 0.72rem; padding: .3em .75em;
        border-radius: 9999px; display: inline-flex; align-items: center; gap: 4px;
    }
    .badge-menunggu { background-color: #fef3c7; color: #d97706; }
    .badge-diproses { background-color: #e0f2fe; color: #0284c7; }
    .badge-selesai  { background-color: #d1fae5; color: #059669; }
    .badge-ditolak  { background-color: #ffe4e6; color: #e11d48; }
</style>
@endpush

@section('content')

{{-- Page Header --}}
<div class="d-flex flex-column sm:flex-row sm:items-center justify-content-between gap-3 mb-5">
    <div>
        <h4 class="font-extrabold text-2xl tracking-tight text-slate-800 mb-1">Kelola Semua Laporan 📋</h4>
        <p class="text-slate-500 font-medium text-xs">Tinjau, validasi, dan kelola seluruh aduan fasilitas umum warga.</p>
    </div>
    <div class="d-flex gap-2 flex-shrink-0">
        <a href="{{ route('admin.reports.export-csv', request()->query()) }}"
           class="btn btn-sm d-flex align-items-center gap-2 font-bold text-xs px-3 py-2 rounded-xl"
           style="background:#d1fae5;color:#065f46;border:1px solid #a7f3d0;">
            <i class="bi bi-file-earmark-excel"></i> CSV
        </a>
        <a href="{{ route('admin.reports.export-pdf', request()->query()) }}" target="_blank"
           class="btn btn-sm d-flex align-items-center gap-2 font-bold text-xs px-3 py-2 rounded-xl"
           style="background:#ffe4e6;color:#9f1239;border:1px solid #fecdd3;">
            <i class="bi bi-file-earmark-pdf"></i> PDF
        </a>
    </div>
</div>

{{-- Filter & Search --}}
<div class="bg-white rounded-2xl border border-slate-200/60 p-4 shadow-sm mb-4">
    <form action="{{ route('admin.reports.index') }}" method="GET" class="row g-3 align-items-end">
        <div class="col-12 col-md-4">
            <label class="form-label text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Cari Laporan</label>
            <div class="input-group">
                <span class="input-group-text bg-slate-50 border-slate-200/80 text-slate-400" style="border-radius:12px 0 0 12px;">
                    <i class="bi bi-search"></i>
                </span>
                <input type="text" name="search" class="form-control border-slate-200/80 text-xs shadow-none"
                       placeholder="Cari judul, lokasi, atau pelapor..."
                       value="{{ request('search') }}" style="border-radius:0 12px 12px 0; padding:.6rem .8rem; font-weight:500;">
            </div>
        </div>
        <div class="col-6 col-md-2">
            <label class="form-label text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Status</label>
            <select name="status" class="form-select border-slate-200/80 text-xs shadow-none text-slate-700"
                    style="border-radius:12px; padding:.65rem .8rem; font-weight:500;">
                <option value="">Semua Status</option>
                <option value="menunggu"  {{ request('status') === 'menunggu'  ? 'selected' : '' }}>Menunggu</option>
                <option value="diproses"  {{ request('status') === 'diproses'  ? 'selected' : '' }}>Diproses</option>
                <option value="selesai"   {{ request('status') === 'selesai'   ? 'selected' : '' }}>Selesai</option>
                <option value="ditolak"   {{ request('status') === 'ditolak'   ? 'selected' : '' }}>Ditolak</option>
            </select>
        </div>
        <div class="col-6 col-md-2">
            <label class="form-label text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Kategori</label>
            <select name="category" class="form-select border-slate-200/80 text-xs shadow-none text-slate-700"
                    style="border-radius:12px; padding:.65rem .8rem; font-weight:500;">
                <option value="">Semua Kategori</option>
                @foreach($categories as $key => $label)
                    <option value="{{ $key }}" {{ request('category') === $key ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-12 col-md-4 d-flex gap-2 align-self-end">
            <button type="submit"
                    class="btn btn-blue-light hover:bg-indigo-600 hover:text-white px-4 py-2.5 rounded-xl text-xs font-bold flex items-center justify-center gap-1.5 flex-grow-1">
                <i class="bi bi-funnel"></i> Filter
            </button>
            @if(request()->hasAny(['status','category','search']))
                <a href="{{ route('admin.reports.index') }}"
                   class="btn btn-light border border-slate-200/80 text-slate-500 px-3 py-2.5 rounded-xl text-xs font-bold flex items-center justify-center">
                    <i class="bi bi-arrow-counterclockwise"></i>
                </a>
            @endif
        </div>
    </form>
</div>

{{-- Reports Table --}}
<div class="bg-white rounded-2xl border border-slate-200/60 shadow-sm overflow-hidden">
    @if($reports->isEmpty())
        <div class="text-center py-12 px-4 text-slate-400">
            <div class="bg-slate-50 text-slate-300 rounded-full h-16 w-16 flex items-center justify-center mx-auto mb-3 border border-slate-100">
                <i class="bi bi-inbox fs-2"></i>
            </div>
            <h6 class="font-bold text-slate-700 mb-1">Tidak Ada Laporan Ditemukan</h6>
            <p class="text-xs text-slate-400 mb-0">Coba ubah filter atau kata kunci pencarian Anda.</p>
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-slate-50 border-bottom border-slate-200/80 text-slate-500 text-[10px] font-bold uppercase tracking-wider">
                    <tr>
                        <th class="ps-4 py-3.5 text-center" style="width:54px;">#</th>
                        <th class="py-3.5">Laporan & Lokasi</th>
                        <th class="py-3.5">Pelapor</th>
                        <th class="py-3.5" style="width:130px;">Kategori</th>
                        <th class="py-3.5" style="width:150px;">Status</th>
                        <th class="py-3.5" style="width:120px;">Tanggal</th>
                        <th class="text-end pe-4 py-3.5" style="width:120px;">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-xs">
                    @foreach($reports as $i => $report)
                        <tr class="border-bottom border-slate-100 hover:bg-slate-50/40 transition-colors">
                            <td class="ps-4 text-center font-bold text-slate-400">{{ $reports->firstItem() + $i }}</td>
                            <td>
                                <div class="font-bold text-slate-800 mb-0.5">{{ Str::limit($report->title, 42) }}</div>
                                <small class="text-slate-400 font-semibold">
                                    <i class="bi bi-geo-alt me-1 text-slate-300"></i>{{ Str::limit($report->location, 40) }}
                                </small>
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="rounded-lg bg-indigo-600 text-white font-bold h-7 w-7 d-flex align-items-center justify-content-center shadow-sm flex-shrink-0"
                                         style="font-size:.72rem;">
                                        {{ strtoupper(substr($report->user->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="font-bold text-slate-700">{{ $report->user->name }}</div>
                                        <small class="text-slate-400 font-medium">{{ $report->user->email }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="bg-slate-100 text-slate-700 text-[10px] font-bold py-1 px-2.5 rounded-lg border border-slate-200/40">
                                    {{ $report->category_label }}
                                </span>
                            </td>
                            <td>
                                <span class="status-badge badge-{{ $report->status }}">
                                    <span class="h-1.5 w-1.5 rounded-full bg-current"></span>
                                    {{ $report->status_label }}
                                </span>
                            </td>
                            <td class="text-slate-400 font-medium">{{ $report->created_at->format('d M Y') }}</td>
                            <td class="text-end pe-4">
                                <div class="d-inline-flex gap-1.5">
                                    <a href="{{ route('admin.reports.show', $report) }}"
                                       class="btn btn-sm btn-light border border-slate-200 text-indigo-600 rounded-lg p-1.5 hover-lift" title="Lihat Detail">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <form action="{{ route('admin.reports.destroy', $report) }}" method="POST"
                                          class="d-inline form-confirm m-0"
                                          data-title="Hapus Laporan Permanen"
                                          data-text="Laporan ini akan dihapus secara permanen dan tidak dapat dikembalikan."
                                          data-icon="warning"
                                          data-confirm-text="Ya, Hapus!"
                                          data-cancel-text="Batal">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                                class="btn btn-sm btn-light border border-slate-200 text-rose-500 rounded-lg p-1.5 hover-lift" title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="p-4 border-top border-slate-100 d-flex flex-column flex-sm-row align-items-center justify-content-between gap-3 text-[10px] font-bold text-slate-400 bg-slate-50/50">
            <span>Menampilkan {{ $reports->firstItem() }}–{{ $reports->lastItem() }} dari {{ $reports->total() }} laporan</span>
            <div>{{ $reports->withQueryString()->links('pagination::bootstrap-5') }}</div>
        </div>
    @endif
</div>

@endsection