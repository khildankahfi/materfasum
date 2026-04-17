@extends('layouts.admin')

@section('title', 'Manajemen User')
@section('page-title', 'Manajemen User')
@section('breadcrumb')
    <li class="breadcrumb-item active">Manajemen User</li>
@endsection

@section('content')

<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h5 class="fw-700 mb-1">Daftar Pengguna</h5>
        <p class="text-muted mb-0" style="font-size:.88rem;">Kelola akun pengguna yang terdaftar di sistem.</p>
    </div>
    <div class="d-flex gap-2">
        <span class="badge bg-primary px-3 py-2" style="font-size:.82rem;">
            {{ $users->total() }} Total User
        </span>
    </div>
</div>

{{-- Filter & Search --}}
<div class="card mb-4">
    <div class="card-body py-3">
        <form action="{{ route('admin.users.index') }}" method="GET">
            <div class="row g-2 align-items-center">
                <div class="col-12 col-md-5">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-light"><i class="bi bi-search text-muted"></i></span>
                        <input type="text" name="search" class="form-control"
                               placeholder="Cari nama atau email..."
                               value="{{ request('search') }}" style="border-radius:0 8px 8px 0;">
                    </div>
                </div>
                <div class="col-6 col-md-2">
                    <select name="status" class="form-select form-select-sm" style="border-radius:8px;">
                        <option value="">Semua Status</option>
                        <option value="aktif"   {{ request('status') === 'aktif'   ? 'selected' : '' }}>Aktif</option>
                        <option value="nonaktif"{{ request('status') === 'nonaktif'? 'selected' : '' }}>Nonaktif</option>
                    </select>
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-sm btn-primary" style="border-radius:8px;">
                        <i class="bi bi-funnel me-1"></i>Filter
                    </button>
                    @if(request()->hasAny(['search','status']))
                        <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-outline-secondary ms-1" style="border-radius:8px;">Reset</a>
                    @endif
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Table --}}
<div class="card">
    <div class="card-body p-0">
        @if($users->isEmpty())
            <div class="text-center py-5 text-muted">
                <i class="bi bi-people fs-1 d-block mb-2 opacity-50"></i>
                <h6>Tidak Ada User Ditemukan</h6>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover mb-0" style="font-size:.85rem;">
                    <thead style="background:#f8fafc;color:#64748b;">
                        <tr>
                            <th class="ps-4">#</th>
                            <th>Pengguna</th>
                            <th>Nomor HP</th>
                            <th>Laporan</th>
                            <th>Status</th>
                            <th>Bergabung</th>
                            <th class="text-end pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $i => $user)
                            <tr>
                                <td class="ps-4 text-muted">{{ $users->firstItem() + $i }}</td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center text-white flex-shrink-0"
                                             style="width:34px;height:34px;font-size:.82rem;font-weight:700;">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="fw-600">{{ $user->name }}</div>
                                            <small class="text-muted">{{ $user->email }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-muted">{{ $user->phone ?? '-' }}</td>
                                <td>
                                    <span class="badge bg-light text-dark px-2">
                                        {{ $user->reports_count }} laporan
                                    </span>
                                </td>
                                <td>
                                    @if($user->is_active)
                                        <span class="badge" style="background:#d1fae5;color:#065f46;">
                                            <i class="bi bi-circle-fill me-1" style="font-size:.5rem;"></i>Aktif
                                        </span>
                                    @else
                                        <span class="badge" style="background:#fee2e2;color:#991b1b;">
                                            <i class="bi bi-circle-fill me-1" style="font-size:.5rem;"></i>Nonaktif
                                        </span>
                                    @endif
                                </td>
                                <td class="text-muted">{{ $user->created_at->format('d M Y') }}</td>
                                <td class="text-end pe-4">
                                    {{-- Toggle aktif/nonaktif --}}
                                    <form action="{{ route('admin.users.toggle', $user) }}" method="POST" class="d-inline">
                                        @csrf @method('PATCH')
                                        <button type="submit"
                                                class="btn btn-sm {{ $user->is_active ? 'btn-outline-warning' : 'btn-outline-success' }}"
                                                style="border-radius:7px;"
                                                title="{{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }}"
                                                onclick="return confirm('{{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }} akun {{ $user->name }}?')">
                                            <i class="bi bi-{{ $user->is_active ? 'slash-circle' : 'check-circle' }}"></i>
                                        </button>
                                    </form>
                                    {{-- Hapus --}}
                                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="d-inline"
                                          onsubmit="return confirm('Hapus akun {{ $user->name }} secara permanen? Semua laporannya juga akan dihapus.')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger"
                                                style="border-radius:7px;" title="Hapus">
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
                <span class="text-muted">{{ $users->firstItem() }}–{{ $users->lastItem() }} dari {{ $users->total() }} user</span>
                {{ $users->withQueryString()->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>
</div>

@endsection