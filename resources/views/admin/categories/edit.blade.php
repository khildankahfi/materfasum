@extends('layouts.admin')

@section('title', 'Edit Kategori')
@section('page-title', 'Edit Kategori')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.categories.index') }}" class="text-decoration-none text-slate-400">Kategori</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-lg-7">
        <div class="bg-white rounded-2xl border border-slate-200/60 shadow-sm p-5">
            <div class="mb-5">
                <h5 class="font-extrabold text-slate-800 mb-1"><i class="bi bi-pencil-square me-2 text-indigo-500"></i>Edit Kategori</h5>
                <p class="text-xs text-slate-400 font-semibold mb-0">Ubah detail kategori laporan aduan warga.</p>
            </div>

            @if($errors->any())
                <div class="alert border-0 shadow-sm rounded-xl d-flex align-items-start gap-2.5 py-3 px-3.5 bg-rose-50 text-rose-800 text-xs font-semibold mb-4" role="alert">
                    <i class="bi bi-exclamation-circle-fill flex-shrink-0 mt-0.5"></i>
                    <div>@foreach($errors->all() as $err)<div>{{ $err }}</div>@endforeach</div>
                </div>
            @endif

            <form action="{{ route('admin.categories.update', $category) }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5 d-block">
                        Nama Kategori <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="name" value="{{ old('name', $category->name) }}"
                           class="form-control border-slate-200/80 text-sm shadow-none" style="border-radius:12px; font-weight:600;"
                           placeholder="Contoh: Jalan Rusak, Drainase, Lampu Jalan...">
                    <p class="text-[10px] text-slate-400 font-semibold mt-1.5 mb-0">Slug: {{ $category->slug }}</p>
                </div>

                <div>
                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5 d-block">
                        Ikon Bootstrap Icons
                    </label>
                    <div class="input-group">
                        <span class="input-group-text border-slate-200/80 bg-slate-50 text-slate-600 text-sm" style="border-radius:12px 0 0 12px;">
                            <i id="iconPreview" class="bi {{ $category->icon ?? 'bi-tag' }} fs-5"></i>
                        </span>
                        <input type="text" name="icon" id="iconInput" value="{{ old('icon', $category->icon ?? 'bi-tag') }}"
                               class="form-control border-slate-200/80 text-sm shadow-none" style="border-radius:0 12px 12px 0; font-weight:600;"
                               placeholder="bi-tag, bi-road, bi-lightbulb-fill, dll.">
                    </div>
                    <p class="text-[10px] text-slate-400 font-semibold mt-1.5 mb-0">
                        Cari nama ikon di <a href="https://icons.getbootstrap.com/" target="_blank" class="text-indigo-500 font-bold">icons.getbootstrap.com</a>
                    </p>
                    <div class="mt-2 p-2.5 bg-slate-50 rounded-xl border border-slate-100 d-flex flex-wrap gap-2" id="iconSuggestions">
                        @foreach(['bi-road','bi-buildings','bi-lightbulb-fill','bi-tree','bi-droplet','bi-tools','bi-trash','bi-signpost-split','bi-sign-merge-right','bi-cone-striped'] as $ic)
                            <button type="button" onclick="setIcon('{{ $ic }}')"
                                    class="btn btn-light border border-slate-200 rounded-lg px-2.5 py-1 text-xs d-flex align-items-center gap-1.5 text-slate-600">
                                <i class="bi {{ $ic }}"></i><span class="d-none d-sm-inline text-[10px]">{{ $ic }}</span>
                            </button>
                        @endforeach
                    </div>
                </div>

                <div>
                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5 d-block">Deskripsi</label>
                    <textarea name="description" rows="3"
                              class="form-control border-slate-200/80 text-sm shadow-none"
                              style="border-radius:12px; font-weight:500; resize:none;"
                              placeholder="Penjelasan singkat tentang kategori ini (opsional)...">{{ old('description', $category->description) }}</textarea>
                </div>

                <div class="d-flex gap-3 pt-2">
                    <a href="{{ route('admin.categories.index') }}"
                       class="btn btn-light border border-slate-200 rounded-xl px-4 py-2.5 text-xs font-bold text-slate-600 flex-shrink-0">
                        <i class="bi bi-arrow-left me-1"></i> Batal
                    </a>
                    <button type="submit" class="btn btn-primary rounded-xl px-5 py-2.5 text-xs font-bold flex-grow-1 shadow-sm shadow-indigo-500/10">
                        <i class="bi bi-save me-1.5"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function setIcon(ic) {
    document.getElementById('iconInput').value = ic;
    document.getElementById('iconPreview').className = 'bi ' + ic + ' fs-5';
}
document.getElementById('iconInput').addEventListener('input', function() {
    document.getElementById('iconPreview').className = 'bi ' + this.value + ' fs-5';
});
</script>
@endpush
