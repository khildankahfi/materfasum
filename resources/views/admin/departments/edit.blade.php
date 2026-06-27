@extends('layouts.admin')

@section('title', 'Edit Dinas Pelaksana')
@section('page-title', 'Edit Dinas')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.departments.index') }}" class="text-decoration-none text-slate-400">Dinas</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-lg-8">
        <div class="bg-white rounded-2xl border border-slate-200/60 shadow-sm p-5">
            <div class="mb-5">
                <h5 class="font-extrabold text-slate-800 mb-1"><i class="bi bi-pencil-square me-2 text-indigo-500"></i>Edit Dinas Pelaksana</h5>
                <p class="text-xs text-slate-400 font-semibold mb-0">Ubah data instansi pemerintah yang bertugas menangani laporan.</p>
            </div>

            @if($errors->any())
                <div class="alert border-0 shadow-sm rounded-xl d-flex align-items-start gap-2.5 py-3 px-3.5 bg-rose-50 text-rose-800 text-xs font-semibold mb-4" role="alert">
                    <i class="bi bi-exclamation-circle-fill flex-shrink-0 mt-0.5"></i>
                    <div>@foreach($errors->all() as $err)<div>{{ $err }}</div>@endforeach</div>
                </div>
            @endif

            <form action="{{ route('admin.departments.update', $department) }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                <div class="row g-4">
                    <div class="col-12 col-md-8">
                        <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5 d-block">
                            Nama Dinas <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="name" value="{{ old('name', $department->name) }}"
                               class="form-control border-slate-200/80 text-sm shadow-none" style="border-radius:12px; font-weight:600;"
                               placeholder="Contoh: Dinas Pekerjaan Umum dan Penataan Ruang">
                    </div>

                    <div class="col-12 col-md-4">
                        <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5 d-block">Kode Dinas</label>
                        <input type="text" name="code" value="{{ old('code', $department->code) }}"
                               class="form-control border-slate-200/80 text-sm shadow-none" style="border-radius:12px; font-weight:600;"
                               placeholder="Contoh: DPUPR">
                    </div>

                    <div class="col-12 col-md-6">
                        <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5 d-block">Nomor Telepon</label>
                        <input type="text" name="phone" value="{{ old('phone', $department->phone) }}"
                               class="form-control border-slate-200/80 text-sm shadow-none" style="border-radius:12px; font-weight:600;"
                               placeholder="Contoh: 031-1234567">
                    </div>

                    <div class="col-12 col-md-6">
                        <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5 d-block">Alamat Email</label>
                        <input type="email" name="email" value="{{ old('email', $department->email) }}"
                               class="form-control border-slate-200/80 text-sm shadow-none" style="border-radius:12px; font-weight:600;"
                               placeholder="Contoh: dpupr@surabaya.go.id">
                    </div>

                    <div class="col-12">
                        <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5 d-block">Alamat Kantor</label>
                        <textarea name="address" rows="3"
                                  class="form-control border-slate-200/80 text-sm shadow-none"
                                  style="border-radius:12px; font-weight:500; resize:none;"
                                  placeholder="Alamat lengkap instansi/dinas...">{{ old('address', $department->address) }}</textarea>
                    </div>
                </div>

                <div class="d-flex gap-3 pt-4 border-top border-slate-100 mt-4">
                    <a href="{{ route('admin.departments.index') }}"
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
