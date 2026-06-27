@extends('layouts.admin')

@section('title', 'Kelola Dinas Pelaksana')
@section('page-title', 'Kelola Dinas Pelaksana')
@section('breadcrumb')
    <li class="breadcrumb-item active">Dinas</li>
@endsection

@section('content')
<div class="bg-white rounded-2xl border border-slate-200/60 shadow-sm p-4 sm:p-5 mb-4 d-flex align-items-center justify-content-between flex-wrap gap-3">
    <div>
        <h5 class="font-extrabold text-slate-800 mb-1"><i class="bi bi-building me-2 text-indigo-500"></i>Daftar Dinas Pelaksana</h5>
        <p class="text-xs text-slate-400 font-semibold mb-0">Kelola instansi atau dinas yang bertugas menangani laporan.</p>
    </div>
    <a href="{{ route('admin.departments.create') }}" class="btn btn-primary rounded-xl px-4 py-2.5 text-xs font-bold shadow-sm shadow-indigo-500/10 hover-lift d-flex align-items-center gap-2">
        <i class="bi bi-plus-lg"></i> Tambah Dinas
    </a>
</div>

<div class="bg-white rounded-2xl border border-slate-200/60 shadow-sm overflow-hidden">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0 text-sm">
            <thead class="bg-slate-50 text-slate-500 text-[10px] uppercase tracking-wider font-bold">
                <tr>
                    <th class="py-3 px-4 border-0">Nama Dinas</th>
                    <th class="py-3 px-4 border-0">Kode</th>
                    <th class="py-3 px-4 border-0">Kontak</th>
                    <th class="py-3 px-4 border-0 text-center">Jumlah Laporan</th>
                    <th class="py-3 px-4 border-0 text-end">Aksi</th>
                </tr>
            </thead>
            <tbody class="border-top border-slate-100">
                @forelse($departments as $dept)
                    <tr>
                        <td class="py-3 px-4">
                            <div class="font-bold text-slate-800">{{ $dept->name }}</div>
                            <div class="text-[10px] text-slate-400 font-semibold">{{ Str::limit($dept->address, 50) }}</div>
                        </td>
                        <td class="py-3 px-4 font-semibold text-slate-600">
                            {{ $dept->code ?? '-' }}
                        </td>
                        <td class="py-3 px-4">
                            <div class="text-xs text-slate-700"><i class="bi bi-envelope me-1 text-slate-400"></i>{{ $dept->email ?? '-' }}</div>
                            <div class="text-xs text-slate-700"><i class="bi bi-telephone me-1 text-slate-400"></i>{{ $dept->phone ?? '-' }}</div>
                        </td>
                        <td class="py-3 px-4 text-center">
                            <span class="badge bg-indigo-50 text-indigo-700 rounded-lg px-2.5 py-1.5 border border-indigo-100 font-bold">
                                {{ $dept->reports_count ?? 0 }} Laporan
                            </span>
                        </td>
                        <td class="py-3 px-4 text-end">
                            <div class="d-flex align-items-center justify-content-end gap-2">
                                <a href="{{ route('admin.departments.edit', $dept) }}" class="btn btn-sm btn-light border border-slate-200 text-amber-600 rounded-lg py-1.5 px-2 hover-lift" title="Edit">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <form action="{{ route('admin.departments.destroy', $dept) }}" method="POST"
                                      class="form-confirm m-0"
                                      data-title="Hapus Dinas"
                                      data-text="Apakah Anda yakin ingin menghapus dinas ini?"
                                      data-icon="warning">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-light border border-slate-200 text-rose-500 rounded-lg py-1.5 px-2 hover-lift" title="Hapus">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-5">
                            <div class="text-slate-400 mb-2"><i class="bi bi-building fs-1"></i></div>
                            <div class="font-bold text-slate-600">Belum ada data dinas</div>
                            <p class="text-xs text-slate-400 mb-0">Silakan tambah dinas pelaksana baru.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($departments->hasPages())
        <div class="p-4 border-top border-slate-100 bg-slate-50/50">
            {{ $departments->links() }}
        </div>
    @endif
</div>
@endsection
