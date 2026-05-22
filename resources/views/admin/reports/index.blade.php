@extends('layouts.admin')

@section('title', 'Kelola Laporan')
@section('page-title', 'Kelola Laporan')
@section('breadcrumb')
    <li class="breadcrumb-item active">Laporan</li>
@endsection

@section('content')

<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h5 class="fw-700 mb-1">Semua Laporan</h5>
        <p class="text-muted mb-0" style="font-size:.88rem;">Kelola dan validasi laporan kerusakan fasilitas.</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.reports.export-csv', request()->query()) }}"
           class="btn btn-sm btn-success" style="border-radius:8px;">
            <i class="bi bi-file-earmark-excel me-1"></i>Export Excel
        </a>
        <a href="{{ route('admin.reports.export-pdf', request()->query()) }}"
           target="_blank" class="btn btn-sm btn-danger" style="border-radius:8px;">
            <i class="bi bi-file-earmark-pdf me-1"></i>Export PDF
        </a>
    </div>
</div>

{{-- Filter & Search --}}
<div class="card mb-4">
    <div class="card-body py-3">
        <form action="{{ route('admin.reports.index') }}" method="GET">
            <div class="row g-2 align-items-center">
                <div class="col-12 col-md-4">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-light"><i class="bi bi-search text-muted"></i></span>
                        <input type="text" name="search" class="form-control" placeholder="Cari judul, lokasi, atau pelapor..."
                               value="{{ request('search') }}" style="border-radius:0 8px 8px 0;">
                    </div>
                </div>
                <div class="col-6 col-md-2">
                    <select name="status" class="form-select form-select-sm" style="border-radius:8px;">
                        <option value="">Semua Status</option>
                        <option value="menunggu"  {{ request('status') === 'menunggu'  ? 'selected' : '' }}>Menunggu</option>
                        <option value="diproses"  {{ request('status') === 'diproses'  ? 'selected' : '' }}>Diproses</option>
                        <option value="selesai"   {{ request('status') === 'selesai'   ? 'selected' : '' }}>Selesai</option>
                        <option value="ditolak"   {{ request('status') === 'ditolak'   ? 'selected' : '' }}>Ditolak</option>
                    </select>
                </div>
                <div class="col-6 col-md-2">
                    <select name="category" class="form-select form-select-sm" style="border-radius:8px;">
                        <option value="">Semua Kategori</option>
                        @foreach($categories as $key => $label)
                            <option value="{{ $key }}" {{ request('category') === $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-sm btn-primary" style="border-radius:8px;">
                        <i class="bi bi-funnel me-1"></i> Filter
                    </button>
                    @if(request()->hasAny(['status','category','search']))
                        <a href="{{ route('admin.reports.index') }}" class="btn btn-sm btn-outline-secondary ms-1" style="border-radius:8px;">Reset</a>
                    @endif
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Table --}}
<div class="card">
    <div class="card-body p-0">
        @if($reports->isEmpty())
            <div class="text-center py-5 text-muted">
                <i class="bi bi-inbox fs-1 d-block mb-2 opacity-50"></i>
                <h6>Tidak Ada Laporan Ditemukan</h6>
                <p class="mb-0 small">Coba ubah filter pencarian Anda.</p>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover mb-0" style="font-size:.85rem;">
                    <thead style="background:#f8fafc;color:#64748b;">
                        <tr>
                            <th class="ps-4">#</th>
                            <th>Laporan</th>
                            <th>Pelapor</th>
                            <th>Kategori</th>
                            <th>Status</th>
                            <th>Tanggal</th>
                            <th class="text-end pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($reports as $i => $report)
                            <tr>
                                <td class="ps-4 text-muted">{{ $reports->firstItem() + $i }}</td>
                                <td>
                                    <div class="fw-600">{{ Str::limit($report->title, 38) }}</div>
                                    <small class="text-muted">
                                        <i class="bi bi-geo-alt me-1"></i>{{ Str::limit($report->location, 35) }}
                                    </small>
                                </td>
                                <td>
                                    <div class="fw-500">{{ $report->user->name }}</div>
                                    <small class="text-muted">{{ $report->user->email }}</small>
                                </td>
                                <td><span class="badge bg-light text-dark">{{ $report->category_label }}</span></td>
                                <td><span class="badge badge-{{ $report->status }}">{{ $report->status_label }}</span></td>
                                <td class="text-muted">{{ $report->created_at->format('d M Y') }}</td>
                                <td class="text-end pe-4">
                                    <a href="{{ route('admin.reports.show', $report) }}"
                                       class="btn btn-sm btn-outline-primary" style="border-radius:7px;" title="Lihat Detail">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <form action="{{ route('admin.reports.destroy', $report) }}" method="POST"
                                          class="d-inline form-confirm"
                                          data-title="Hapus Laporan Permanen"
                                          data-text="Laporan ini akan dihapus secara permanen dan tidak dapat dikembalikan."
                                          data-icon="warning"
                                          data-confirm-text="Ya, Hapus!"
                                          data-cancel-text="Batal">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" style="border-radius:7px;" title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="p-3 d-flex justify-content-between align-items-center border-top" style="font-size:.82rem;">
                <span class="text-muted">{{ $reports->firstItem() }}–{{ $reports->lastItem() }} dari {{ $reports->total() }} laporan</span>
                {{ $reports->withQueryString()->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>
</div>
@endsection