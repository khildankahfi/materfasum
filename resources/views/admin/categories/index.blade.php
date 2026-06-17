@extends('layouts.admin')

@section('title', 'Kelola Kategori')
@section('page-title', 'Kelola Kategori')
@section('breadcrumb')
    <li class="breadcrumb-item active">Kategori</li>
@endsection

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h5 class="font-extrabold text-slate-800 mb-0">Kategori Laporan</h5>
        <p class="text-xs text-slate-400 font-semibold mb-0 mt-0.5">Kelola kategori jenis kerusakan fasilitas umum</p>
    </div>
    <a href="{{ route('admin.categories.create') }}"
       class="btn btn-primary rounded-xl px-4 py-2.5 text-xs font-bold d-flex align-items-center gap-2 shadow-sm shadow-indigo-500/10">
        <i class="bi bi-plus-lg"></i> Tambah Kategori
    </a>
</div>

<div class="bg-white rounded-2xl border border-slate-200/60 shadow-sm overflow-hidden">
    @if($categories->isEmpty())
        <div class="text-center py-16 text-slate-400">
            <i class="bi bi-tags fs-1 d-block mb-3 text-slate-300"></i>
            <p class="font-semibold text-sm mb-1">Belum ada kategori</p>
            <p class="text-xs mb-4">Tambahkan kategori pertama untuk mulai mengelola laporan.</p>
            <a href="{{ route('admin.categories.create') }}" class="btn btn-primary btn-sm rounded-xl px-4">
                <i class="bi bi-plus-lg me-1"></i> Tambah Sekarang
            </a>
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead style="background:#f8fafc; border-bottom:2px solid #e2e8f0;">
                    <tr>
                        <th class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">#</th>
                        <th class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Kategori</th>
                        <th class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Slug</th>
                        <th class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Ikon</th>
                        <th class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Jumlah Laporan</th>
                        <th class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($categories as $i => $cat)
                    <tr>
                        <td class="text-xs text-slate-400 font-bold">{{ $categories->firstItem() + $i }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-2.5">
                                <div class="h-9 w-9 rounded-xl bg-indigo-50 border border-indigo-100 d-flex align-items-center justify-content-center flex-shrink-0">
                                    <i class="bi {{ $cat->icon ?? 'bi-tag' }} text-indigo-600"></i>
                                </div>
                                <div>
                                    <div class="font-bold text-sm text-slate-800">{{ $cat->name }}</div>
                                    @if($cat->description)
                                        <div class="text-[10px] text-slate-400 font-semibold">{{ Str::limit($cat->description, 50) }}</div>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td>
                            <code class="bg-slate-100 text-slate-600 px-2 py-1 rounded-lg text-[11px] font-bold">{{ $cat->slug }}</code>
                        </td>
                        <td>
                            <code class="bg-slate-100 text-slate-500 px-2 py-1 rounded-lg text-[11px]">{{ $cat->icon ?? 'bi-tag' }}</code>
                        </td>
                        <td>
                            <span class="badge bg-blue-50 text-blue-700 border border-blue-100 text-[11px] font-bold px-3 py-1.5 rounded-full">
                                {{ $cat->reports_count }} laporan
                            </span>
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <a href="{{ route('admin.categories.edit', $cat) }}"
                                   class="btn btn-light border border-slate-200 rounded-xl text-xs font-bold px-3 py-1.5 text-slate-600 d-flex align-items-center gap-1">
                                    <i class="bi bi-pencil-square"></i> Edit
                                </a>
                                <form action="{{ route('admin.categories.destroy', $cat) }}" method="POST"
                                      class="form-confirm m-0"
                                      data-title="Hapus Kategori?"
                                      data-text="Kategori '{{ $cat->name }}' akan dihapus permanen."
                                      data-icon="warning"
                                      data-confirm-text="Ya, Hapus!"
                                      data-cancel-text="Batal">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                            class="btn btn-light border border-rose-200 rounded-xl text-xs font-bold px-3 py-1.5 text-rose-500 d-flex align-items-center gap-1">
                                        <i class="bi bi-trash3"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @if($categories->hasPages())
            <div class="px-4 py-3 border-top border-slate-100">
                {{ $categories->links() }}
            </div>
        @endif
    @endif
</div>
@endsection
