@extends('layouts.user')

@section('title', 'Riwayat Laporan')

@section('content')
<div class="d-flex flex-column sm:flex-row sm:items-center justify-content-between gap-3 mb-5">
    <div>
        <h4 class="font-extrabold text-2xl tracking-tight text-slate-800 mb-1">
            Riwayat Laporan Saya 📂
        </h4>
        <p class="text-slate-500 font-medium text-xs">
            Pantau status dan tanggapan atas semua aduan fasilitas umum yang Anda kirimkan.
        </p>
    </div>
    <div>
        <a href="{{ route('user.reports.create') }}" class="btn btn-primary shadow-lg shadow-blue-500/20 px-4 py-2.5 rounded-xl text-xs font-bold hover-lift text-decoration-none flex items-center justify-center gap-2">
            <i class="bi bi-plus-circle"></i> Buat Laporan Baru
        </a>
    </div>
</div>

<!-- Filters & Search Section -->
<div class="bg-white rounded-2xl border border-slate-200/60 p-4 shadow-sm mb-4">
    <form action="{{ route('user.reports.index') }}" method="GET" class="row g-3 items-center">
        <!-- Search input -->
        <div class="col-12 col-md-4">
            <label class="form-label text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Cari Laporan</label>
            <div class="input-group">
                <span class="input-group-text bg-slate-50 border-slate-200/80 text-slate-400" style="border-radius:12px 0 0 12px;"><i class="bi bi-search"></i></span>
                <input type="text" name="search" class="form-control border-slate-200/80 text-xs focus:border-blue-500 focus:ring-0 shadow-none" 
                       placeholder="Cari judul aduan atau lokasi..." 
                       value="{{ request('search') }}" style="border-radius:0 12px 12px 0; padding: 0.6rem 0.8rem; font-weight: 500;">
            </div>
        </div>

        <!-- Status Filter -->
        <div class="col-6 col-md-3">
            <label class="form-label text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Status Laporan</label>
            <select name="status" class="form-select border-slate-200/80 text-xs focus:border-blue-500 shadow-none text-slate-700" style="border-radius:12px; padding: 0.65rem 0.8rem; font-weight: 500;">
                <option value="">Semua Status</option>
                <option value="menunggu" {{ request('status') === 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                <option value="diproses" {{ request('status') === 'diproses' ? 'selected' : '' }}>Diproses</option>
                <option value="selesai" {{ request('status') === 'selesai' ? 'selected' : '' }}>Selesai</option>
                <option value="ditolak" {{ request('status') === 'ditolak' ? 'selected' : '' }}>Ditolak</option>
            </select>
        </div>

        <!-- Category Filter -->
        <div class="col-6 col-md-3">
            <label class="form-label text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Kategori Fasilitas</label>
            <select name="category" class="form-select border-slate-200/80 text-xs focus:border-blue-500 shadow-none text-slate-700" style="border-radius:12px; padding: 0.65rem 0.8rem; font-weight: 500;">
                <option value="">Semua Kategori</option>
                @foreach($categories as $key => $label)
                    <option value="{{ $key }}" {{ request('category') === $key ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
        </div>

        <!-- Form Buttons -->
        <div class="col-12 col-md-2 d-flex gap-2 align-self-end pt-2 pt-md-0">
            <button type="submit" class="btn btn-blue-light hover:bg-blue-600 hover:text-white px-4 py-2.5 rounded-xl text-xs font-bold hover-lift w-100 flex items-center justify-center gap-1.5">
                <i class="bi bi-funnel"></i> Filter
            </button>
            @if(request()->hasAny(['status', 'category', 'search']))
                <a href="{{ route('user.reports.index') }}" class="btn btn-light border border-slate-200/80 text-slate-500 px-3 py-2.5 rounded-xl text-xs font-bold hover-lift flex items-center justify-center">
                    <i class="bi bi-arrow-counterclockwise"></i>
                </a>
            @endif
        </div>
    </form>
</div>

<!-- Reports Table/List Grid -->
<div class="bg-white rounded-2xl border border-slate-200/60 shadow-sm overflow-hidden">
    @if($reports->isEmpty())
        <div class="text-center py-12 px-4 text-slate-400">
            <div class="bg-slate-50 text-slate-300 rounded-full h-16 w-16 flex items-center justify-center mx-auto mb-3 border border-slate-100">
                <i class="bi bi-inbox fs-2"></i>
            </div>
            @if(request()->hasAny(['search', 'status', 'category']))
                <h6 class="font-bold text-slate-700 mb-1">Hasil Pencarian Kosong</h6>
                <p class="text-xs text-slate-400 mb-4 max-w-xs mx-auto">Kami tidak dapat menemukan laporan yang cocok dengan kata kunci atau filter Anda.</p>
                <a href="{{ route('user.reports.index') }}" class="btn btn-sm btn-outline-secondary rounded-lg text-[10px] font-bold px-3 py-2">
                    Reset Filter Pencarian
                </a>
            @else
                <h6 class="font-bold text-slate-700 mb-1">Belum Ada Laporan</h6>
                <p class="text-xs text-slate-400 mb-4 max-w-xs mx-auto">Anda belum mengirimkan aduan apapun. Mari bantu jaga kota kita!</p>
                <a href="{{ route('user.reports.create') }}" class="btn btn-sm btn-primary rounded-lg text-[10px] font-bold px-4 py-2">
                    <i class="bi bi-plus-circle me-1.5"></i>Buat Laporan Pertama
                </a>
            @endif
        </div>
    @else
        <!-- Desktop Table Layout -->
        <div class="table-responsive d-none d-md-block">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-slate-50 border-bottom border-slate-200/80 text-slate-500 text-[10px] font-bold uppercase tracking-wider">
                    <tr>
                        <th class="ps-4 py-3.5 text-center" style="width: 60px;">#</th>
                        <th class="py-3.5">Aduan & Deskripsi</th>
                        <th class="py-3.5" style="width: 140px;">Kategori</th>
                        <th class="py-3.5">Lokasi Kerusakan</th>
                        <th class="py-3.5" style="width: 150px;">Status</th>
                        <th class="py-3.5" style="width: 130px;">Tanggal</th>
                        <th class="text-end pe-4 py-3.5" style="width: 160px;">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-xs">
                    @foreach($reports as $i => $report)
                        <tr class="border-bottom border-slate-100 hover:bg-slate-50/40">
                            <td class="ps-4 text-center font-bold text-slate-400">
                                {{ $reports->firstItem() + $i }}
                            </td>
                            <td>
                                <div class="font-bold text-slate-800 mb-0.5">{{ Str::limit($report->title, 45) }}</div>
                                <div class="text-[11px] text-slate-400 truncate max-w-[280px] font-medium">{{ $report->description }}</div>
                            </td>
                            <td>
                                <span class="bg-slate-100 text-slate-700 text-[10px] font-bold py-1 px-2.5 rounded-lg border border-slate-200/40">
                                    {{ $report->category_label }}
                                </span>
                            </td>
                            <td class="text-slate-500 font-semibold text-[11px]">
                                <div class="truncate max-w-[180px]"><i class="bi bi-geo-alt me-1 text-slate-400"></i>{{ $report->location }}</div>
                            </td>
                            <td>
                                <span class="status-badge status-{{ $report->status }}">
                                    <span class="h-1.5 w-1.5 rounded-full bg-current"></span>
                                    {{ $report->status_label }}
                                </span>
                            </td>
                            <td class="text-slate-400 font-medium">
                                {{ $report->created_at->format('d M Y') }}
                            </td>
                            <td class="text-end pe-4">
                                <div class="d-inline-flex gap-1.5">
                                    <a href="{{ route('user.reports.show', $report) }}" class="btn btn-sm btn-light border border-slate-200 text-slate-600 rounded-lg p-1.5 hover-lift" title="Lihat Detail">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    @if($report->status === 'menunggu')
                                        <a href="{{ route('user.reports.edit', $report) }}" class="btn btn-sm btn-light border border-slate-200 text-amber-600 rounded-lg p-1.5 hover-lift" title="Edit Laporan">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('user.reports.destroy', $report) }}" method="POST" class="d-inline form-confirm m-0"
                                              data-title="Hapus Laporan"
                                              data-text="Laporan Anda akan dihapus permanen dari sistem."
                                              data-icon="warning"
                                              data-confirm-text="Ya, Hapus!"
                                              data-cancel-text="Batal">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-light border border-slate-200 text-rose-600 rounded-lg p-1.5 hover-lift" title="Hapus Laporan">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Mobile List Layout -->
        <div class="d-block d-md-none p-3 divide-y divide-slate-100">
            @foreach($reports as $i => $report)
                <div class="py-3.5 first:pt-0 last:pb-0">
                    <div class="flex items-start justify-between gap-3 mb-2">
                        <span class="bg-slate-100 text-slate-700 text-[9px] font-bold py-0.5 px-2 rounded-md border border-slate-200/40">
                            {{ $report->category_label }}
                        </span>
                        <span class="status-badge status-{{ $report->status }} text-[9px] py-0.5 px-2">
                            <span class="h-1 w-1 rounded-full bg-current"></span>
                            {{ $report->status_label }}
                        </span>
                    </div>
                    <h6 class="font-bold text-xs text-slate-800 mb-1 leading-snug">{{ $report->title }}</h6>
                    <p class="text-[11px] text-slate-400 font-semibold mb-2 line-clamp-2">{{ $report->description }}</p>
                    <div class="flex items-center justify-between gap-3">
                        <div class="text-slate-400 text-[10px] font-semibold flex flex-col gap-0.5">
                            <span><i class="bi bi-geo-alt me-1"></i>{{ Str::limit($report->location, 35) }}</span>
                            <span><i class="bi bi-calendar3 me-1"></i>{{ $report->created_at->format('d M Y') }}</span>
                        </div>
                        <div class="d-flex gap-1 flex-shrink-0">
                            <a href="{{ route('user.reports.show', $report) }}" class="btn btn-sm btn-light border border-slate-200 text-slate-600 rounded-lg p-1.5">
                                <i class="bi bi-eye"></i>
                            </a>
                            @if($report->status === 'menunggu')
                                <a href="{{ route('user.reports.edit', $report) }}" class="btn btn-sm btn-light border border-slate-200 text-amber-600 rounded-lg p-1.5">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('user.reports.destroy', $report) }}" method="POST" class="d-inline form-confirm m-0"
                                      data-title="Hapus Laporan"
                                      data-text="Laporan Anda akan dihapus permanen dari sistem."
                                      data-icon="warning"
                                      data-confirm-text="Ya, Hapus!"
                                      data-cancel-text="Batal">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-light border border-slate-200 text-rose-600 rounded-lg p-1.5">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination Section -->
        <div class="p-4 border-top border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-3 text-[10px] font-bold text-slate-400 bg-slate-50/50">
            <span>
                Menampilkan {{ $reports->firstItem() }}–{{ $reports->lastItem() }} dari {{ $reports->total() }} aduan
            </span>
            <div>
                {{ $reports->withQueryString()->links('pagination::bootstrap-5') }}
            </div>
        </div>
    @endif
</div>
@endsection

@push('styles')
<style>
    /* Styling for lighter button style */
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