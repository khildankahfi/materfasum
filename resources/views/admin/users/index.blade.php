@extends('layouts.admin')

@section('title', 'Manajemen User')
@section('page-title', 'Manajemen User')
@section('breadcrumb')
    <li class="breadcrumb-item active">Manajemen User</li>
@endsection

@push('styles')
<style>
    .user-avatar {
        width: 36px; height: 36px; border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        font-size: .78rem; font-weight: 700; color: #fff; flex-shrink: 0;
    }
    .pagination { margin-bottom: 0; gap: 4px; }
    .page-item .page-link {
        border-radius: 8px !important; border: 1px solid #e2e8f0;
        padding: .4rem .8rem; color: #475569; font-size: .75rem; font-weight: 700; transition: all .2s;
    }
    .page-item.active .page-link { background-color: #6366f1; border-color: #6366f1; color: #fff; }
    .page-item.disabled .page-link { color: #94a3b8; background-color: #f8fafc; }
    .page-link:hover { background-color: #f1f5f9; color: #1e293b; }

    /* Avatar colors by initial letter */
    .av-a, .av-b, .av-c { background: linear-gradient(135deg, #6366f1, #4f46e5); }
    .av-d, .av-e, .av-f { background: linear-gradient(135deg, #0ea5e9, #0284c7); }
    .av-g, .av-h, .av-i { background: linear-gradient(135deg, #10b981, #059669); }
    .av-j, .av-k, .av-l { background: linear-gradient(135deg, #f59e0b, #d97706); }
    .av-m, .av-n, .av-o { background: linear-gradient(135deg, #ec4899, #db2777); }
    .av-p, .av-q, .av-r { background: linear-gradient(135deg, #8b5cf6, #7c3aed); }
    .av-s, .av-t, .av-u { background: linear-gradient(135deg, #14b8a6, #0d9488); }
    .av-v, .av-w, .av-x { background: linear-gradient(135deg, #f97316, #ea580c); }
    .av-y, .av-z         { background: linear-gradient(135deg, #64748b, #475569); }
</style>
@endpush

@section('content')

{{-- Page Header --}}
<div class="d-flex flex-column sm:flex-row sm:items-center justify-content-between gap-3 mb-5">
    <div>
        <h4 class="font-extrabold text-2xl tracking-tight text-slate-800 mb-1">Manajemen Pengguna 👤</h4>
        <p class="text-slate-500 font-medium text-xs">Kelola akun warga yang terdaftar dan aktif di sistem MaterFasum.</p>
    </div>
    <span class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-50 border border-indigo-100 rounded-full text-indigo-700 text-xs font-bold">
        <i class="bi bi-people-fill"></i> {{ $users->total() }} Total Pengguna
    </span>
</div>

{{-- Filter & Search --}}
<div class="bg-white rounded-2xl border border-slate-200/60 p-4 shadow-sm mb-4">
    <form action="{{ route('admin.users.index') }}" method="GET" class="row g-3 align-items-end">
        <div class="col-12 col-md-5">
            <label class="form-label text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Cari Pengguna</label>
            <div class="input-group">
                <span class="input-group-text bg-slate-50 border-slate-200/80 text-slate-400" style="border-radius:12px 0 0 12px;">
                    <i class="bi bi-search"></i>
                </span>
                <input type="text" name="search"
                       class="form-control border-slate-200/80 text-xs shadow-none"
                       placeholder="Cari nama atau email pengguna..."
                       value="{{ request('search') }}"
                       style="border-radius:0 12px 12px 0; padding:.6rem .8rem; font-weight:500;">
            </div>
        </div>
        <div class="col-6 col-md-3">
            <label class="form-label text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Status Akun</label>
            <select name="status" class="form-select border-slate-200/80 text-xs shadow-none text-slate-700"
                    style="border-radius:12px; padding:.65rem .8rem; font-weight:500;">
                <option value="">Semua Status</option>
                <option value="aktif"    {{ request('status') === 'aktif'    ? 'selected' : '' }}>Aktif</option>
                <option value="nonaktif" {{ request('status') === 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
            </select>
        </div>
        <div class="col-6 col-md-4 d-flex gap-2 align-self-end">
            <button type="submit"
                    class="btn flex-grow-1 text-xs font-bold py-2.5 px-4 rounded-xl d-flex align-items-center justify-content-center gap-1.5 hover-lift"
                    style="background:#eff6ff;color:#4338ca;border:1px solid #c7d2fe;">
                <i class="bi bi-funnel"></i> Filter
            </button>
            @if(request()->hasAny(['search','status']))
                <a href="{{ route('admin.users.index') }}"
                   class="btn btn-light border border-slate-200/80 text-slate-500 px-3 py-2.5 rounded-xl text-xs font-bold d-flex align-items-center justify-content-center">
                    <i class="bi bi-arrow-counterclockwise"></i>
                </a>
            @endif
        </div>
    </form>
</div>

{{-- Users Table --}}
<div class="bg-white rounded-2xl border border-slate-200/60 shadow-sm overflow-hidden">
    @if($users->isEmpty())
        <div class="text-center py-12 px-4 text-slate-400">
            <div class="bg-slate-50 text-slate-300 rounded-full h-16 w-16 flex items-center justify-center mx-auto mb-3 border border-slate-100">
                <i class="bi bi-people fs-2"></i>
            </div>
            <h6 class="font-bold text-slate-700 mb-1">Tidak Ada Pengguna Ditemukan</h6>
            <p class="text-xs text-slate-400 mb-0">Coba ubah kata kunci atau filter pencarian Anda.</p>
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-slate-50 border-bottom border-slate-200/80 text-slate-500 text-[10px] font-bold uppercase tracking-wider">
                    <tr>
                        <th class="ps-4 py-3.5 text-center" style="width:54px;">#</th>
                        <th class="py-3.5">Pengguna</th>
                        <th class="py-3.5" style="width:150px;">No. Telepon</th>
                        <th class="py-3.5 text-center" style="width:100px;">Laporan</th>
                        <th class="py-3.5" style="width:130px;">Status</th>
                        <th class="py-3.5" style="width:130px;">Bergabung</th>
                        <th class="text-end pe-4 py-3.5" style="width:120px;">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-xs">
                    @foreach($users as $i => $user)
                        @php $initial = strtolower(substr($user->name, 0, 1)); @endphp
                        <tr class="border-bottom border-slate-100 hover:bg-slate-50/40 transition-colors">
                            <td class="ps-4 text-center font-bold text-slate-400">{{ $users->firstItem() + $i }}</td>
                            <td>
                                <div class="d-flex align-items-center gap-2.5">
                                    <div class="user-avatar av-{{ $initial }}">
                                        {{ strtoupper($initial) }}
                                    </div>
                                    <div>
                                        <div class="font-bold text-slate-800">{{ $user->name }}</div>
                                        <small class="text-slate-400 font-medium">{{ $user->email }}</small>
                                    </div>
                                </div>
                            </td>
                            <td class="text-slate-500 font-semibold">{{ $user->phone ?? '—' }}</td>
                            <td class="text-center">
                                <span class="bg-slate-100 text-slate-700 text-[10px] font-bold py-1 px-2.5 rounded-lg border border-slate-200/40">
                                    {{ $user->reports_count }} laporan
                                </span>
                            </td>
                            <td>
                                @if($user->is_active)
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold"
                                          style="background:#d1fae5;color:#065f46;">
                                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>Aktif
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold"
                                          style="background:#fee2e2;color:#991b1b;">
                                        <span class="h-1.5 w-1.5 rounded-full bg-rose-500"></span>Nonaktif
                                    </span>
                                @endif
                            </td>
                            <td class="text-slate-400 font-medium">{{ $user->created_at->format('d M Y') }}</td>
                            <td class="text-end pe-4">
                                <div class="d-inline-flex gap-1.5">
                                    {{-- Toggle Aktif/Nonaktif --}}
                                    <form action="{{ route('admin.users.toggle', $user) }}" method="POST"
                                          class="d-inline form-confirm m-0"
                                          data-title="{{ $user->is_active ? 'Nonaktifkan Akun' : 'Aktifkan Akun' }}"
                                          data-text="{{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }} akun {{ $user->name }}?"
                                          data-icon="{{ $user->is_active ? 'warning' : 'question' }}"
                                          data-confirm-text="{{ $user->is_active ? 'Ya, Nonaktifkan' : 'Ya, Aktifkan' }}"
                                          data-cancel-text="Batal">
                                        @csrf @method('PATCH')
                                        <button type="submit"
                                                class="btn btn-sm btn-light border border-slate-200 rounded-lg p-1.5 hover-lift
                                                       {{ $user->is_active ? 'text-amber-600' : 'text-emerald-600' }}"
                                                title="{{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                            <i class="bi bi-{{ $user->is_active ? 'slash-circle' : 'check-circle' }}"></i>
                                        </button>
                                    </form>

                                    {{-- Hapus Akun --}}
                                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST"
                                          class="d-inline form-confirm m-0"
                                          data-title="Hapus Akun Pengguna"
                                          data-text="Hapus akun {{ $user->name }} secara permanen? Semua laporannya juga akan ikut dihapus."
                                          data-icon="warning"
                                          data-confirm-text="Ya, Hapus Permanen!"
                                          data-cancel-text="Batal">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                                class="btn btn-sm btn-light border border-slate-200 text-rose-500 rounded-lg p-1.5 hover-lift"
                                                title="Hapus">
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
            <span>Menampilkan {{ $users->firstItem() }}–{{ $users->lastItem() }} dari {{ $users->total() }} pengguna</span>
            <div>{{ $users->withQueryString()->links('pagination::bootstrap-5') }}</div>
        </div>
    @endif
</div>

@endsection