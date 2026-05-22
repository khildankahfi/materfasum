@extends('layouts.user')

@section('title', 'Laporan Saya')
@section('page-title', 'Laporan Saya')
@section('breadcrumb')
    <li class="breadcrumb-item active">Laporan</li>
@endsection

@section('content')

<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h5 class="fw-700 mb-1">Laporan Saya</h5>
        <p class="text-muted mb-0" style="font-size:.88rem;">Pantau status semua laporan yang telah Anda kirimkan.</p>
    </div>
    <a href="{{ route('user.reports.create') }}" class="btn btn-primary" style="border-radius:10px;">
        <i class="bi bi-plus-circle me-2"></i> Buat Laporan
    </a>
</div>

{{-- Filter & Search --}}
<div class="card mb-4">
    <div class="card-body py-3">
        <form action="{{ route('user.reports.index') }}" method="GET" class="row g-2 align-items-center">

            {{-- Search --}}
            <div class="col-12 col-sm-4">
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-light"><i class="bi bi-search text-muted"></i></span>
                    <input type="text" name="search" class="form-control"
                           placeholder="Cari judul atau lokasi..."
                           value="{{ request('search') }}" style="border-radius:0 8px 8px 0;">
                </div>
            </div>

            {{-- Status --}}
            <div class="col-6 col-sm-3">
                <select name="status" class="form-select form-select-sm" style="border-radius:8px;">
                    <option value="">Semua Status</option>
                    <option value="menunggu"  {{ request('status') === 'menunggu'  ? 'selected' : '' }}>Menunggu</option>
                    <option value="diproses"  {{ request('status') === 'diproses'  ? 'selected' : '' }}>Diproses</option>
                    <option value="selesai"   {{ request('status') === 'selesai'   ? 'selected' : '' }}>Selesai</option>
                    <option value="ditolak"   {{ request('status') === 'ditolak'   ? 'selected' : '' }}>Ditolak</option>
                </select>
            </div>

            {{-- Kategori --}}
            <div class="col-6 col-sm-3">
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
                    <a href="{{ route('user.reports.index') }}" class="btn btn-sm btn-outline-secondary ms-1" style="border-radius:8px;">
                        <i class="bi bi-x me-1"></i>Reset
                    </a>
                @endif
            </div>
        </form>
    </div>
</div>

@if(request()->hasAny(['search','status','category']))
    <div class="mb-3" style="font-size:.85rem;color:#64748b;">
        <i class="bi bi-info-circle me-1"></i>
        Menampilkan hasil untuk:
        @if(request('search')) kata kunci "<strong>{{ request('search') }}</strong>" @endif
        @if(request('status')) status <strong>{{ ucfirst(request('status')) }}</strong> @endif
        @if(request('category')) kategori <strong>{{ $categories[request('category')] ?? '' }}</strong> @endif
    </div>
@endif

{{-- Table --}}
<div class="card">
    <div class="card-body p-0">
        @if($reports->isEmpty())
            <div class="text-center py-5 text-muted">
                <i class="bi bi-inbox fs-1 d-block mb-2 opacity-50"></i>
                @if(request()->hasAny(['search','status','category']))
                    <h6>Tidak Ada Hasil</h6>
                    <p class="mb-3 small">Coba ubah kata kunci atau filter pencarian.</p>
                    <a href="{{ route('user.reports.index') }}" class="btn btn-outline-secondary btn-sm" style="border-radius:8px;">
                        Reset Filter
                    </a>
                @else
                    <h6>Belum Ada Laporan</h6>
                    <p class="mb-3 small">Anda belum mengirimkan laporan apapun.</p>
                    <a href="{{ route('user.reports.create') }}" class="btn btn-primary btn-sm" style="border-radius:8px;">
                        <i class="bi bi-plus-circle me-1"></i> Buat Laporan Pertama
                    </a>
                @endif
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead style="background:#f8fafc;font-size:.82rem;color:#64748b;">
                        <tr>
                            <th class="ps-4">#</th>
                            <th>Judul Laporan</th>
                            <th>Kategori</th>
                            <th>Lokasi</th>
                            <th>Status</th>
                            <th>Tanggal</th>
                            <th class="text-end pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody style="font-size:.88rem;">
                        @foreach($reports as $i => $report)
                            <tr>
                                <td class="ps-4 text-muted">{{ $reports->firstItem() + $i }}</td>
                                <td>
                                    <div class="fw-600">{{ Str::limit($report->title, 40) }}</div>
                                    <small class="text-muted">{{ Str::limit($report->description, 50) }}</small>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark">{{ $report->category_label }}</span>
                                </td>
                                <td class="text-muted">
                                    <i class="bi bi-geo-alt me-1"></i>{{ Str::limit($report->location, 30) }}
                                </td>
                                <td>
                                    <span class="badge badge-{{ $report->status }}">{{ $report->status_label }}</span>
                                </td>
                                <td class="text-muted">{{ $report->created_at->format('d M Y') }}</td>
                                <td class="text-end pe-4">
                                    <a href="{{ route('user.reports.show', $report) }}"
                                       class="btn btn-sm btn-outline-primary" style="border-radius:7px;">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    @if($report->status === 'menunggu')
                                        <a href="{{ route('user.reports.edit', $report) }}"
                                           class="btn btn-sm btn-outline-warning" style="border-radius:7px;" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('user.reports.destroy', $report) }}" method="POST" class="d-inline form-confirm"
                                              data-title="Hapus Laporan"
                                              data-text="Laporan ini akan dihapus dan tidak dapat dikembalikan."
                                              data-icon="warning"
                                              data-confirm-text="Ya, Hapus!"
                                              data-cancel-text="Batal">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" style="border-radius:7px;">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="p-3 d-flex justify-content-between align-items-center border-top" style="font-size:.82rem;">
                <span class="text-muted">
                    Menampilkan {{ $reports->firstItem() }}–{{ $reports->lastItem() }} dari {{ $reports->total() }} laporan
                </span>
                {{ $reports->withQueryString()->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>
</div>
@endsection