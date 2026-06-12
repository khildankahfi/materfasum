@extends('layouts.user')

@section('title', 'Edit Profil')

@section('content')
<div class="mb-5">
    <h4 class="font-extrabold text-2xl tracking-tight text-slate-800 mb-1">
        Pengaturan Profil Akun ⚙️
    </h4>
    <p class="text-slate-500 font-medium text-xs">
        Perbarui data diri lengkap dan atur tingkat keamanan kata sandi akun Anda.
    </p>
</div>

<div class="row g-4">
    <!-- Left Column: Form Info Profil & Form Ganti Password -->
    <div class="col-12 col-lg-8 space-y-4">
        
        <!-- Informasi Profil Card -->
        <div class="bg-white rounded-2xl border border-slate-200/60 p-4 sm:p-5 shadow-sm">
            <div class="mb-4">
                <h5 class="font-bold text-slate-800 mb-1">Detail Informasi Profil</h5>
                <p class="text-xs text-slate-400 font-semibold mb-0 uppercase tracking-wider">Perbarui nama, email, dan alamat Anda</p>
            </div>

            <form action="{{ route('user.profile.update') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-2">Nama Lengkap <span class="text-rose-500">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-slate-50 border-slate-200/80 text-slate-400" style="border-radius:12px 0 0 12px; border-right:none;"><i class="bi bi-person"></i></span>
                            <input type="text" name="name" class="form-control border-slate-200/80 text-xs focus:border-blue-500 focus:ring-0 shadow-none @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required style="border-radius:0 12px 12px 0; padding: 0.65rem 0.8rem; font-weight: 500;">
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="col-12">
                        <label class="form-label text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-2">Alamat Email <span class="text-rose-500">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-slate-50 border-slate-200/80 text-slate-400" style="border-radius:12px 0 0 12px; border-right:none;"><i class="bi bi-envelope"></i></span>
                            <input type="email" name="email" class="form-control border-slate-200/80 text-xs focus:border-blue-500 focus:ring-0 shadow-none @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required style="border-radius:0 12px 12px 0; padding: 0.65rem 0.8rem; font-weight: 500;">
                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="col-12 col-sm-6">
                        <label class="form-label text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-2">Nomor HP</label>
                        <div class="input-group">
                            <span class="input-group-text bg-slate-50 border-slate-200/80 text-slate-400" style="border-radius:12px 0 0 12px; border-right:none;"><i class="bi bi-telephone"></i></span>
                            <input type="text" name="phone" class="form-control border-slate-200/80 text-xs focus:border-blue-500 focus:ring-0 shadow-none @error('phone') is-invalid @enderror" value="{{ old('phone', $user->phone) }}" placeholder="Contoh: +628123456789" style="border-radius:0 12px 12px 0; padding: 0.65rem 0.8rem; font-weight: 500;">
                            @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="col-12">
                        <label class="form-label text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-2">Alamat Lengkap</label>
                        <textarea name="address" rows="3" class="form-control border-slate-200/80 text-xs focus:border-blue-500 shadow-none @error('address') is-invalid @enderror" placeholder="Tulis alamat rumah lengkap Anda..." style="border-radius:12px; padding: 0.65rem 0.8rem; font-weight: 500;">{{ old('address', $user->address) }}</textarea>
                        @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="mt-4 d-flex gap-2">
                    <button type="submit" class="btn btn-primary shadow-lg shadow-blue-500/10 px-4 py-2.5 rounded-xl text-xs font-bold hover-lift flex items-center gap-1.5">
                        <i class="bi bi-check-lg"></i> Simpan Perubahan
                    </button>
                    <a href="{{ route('user.dashboard') }}" class="btn btn-light border border-slate-200 text-slate-600 px-4 py-2.5 rounded-xl text-xs font-bold hover-lift">
                        Batal
                    </a>
                </div>
            </form>
        </div>

        <!-- Ganti Password Card -->
        <div class="bg-white rounded-2xl border border-slate-200/60 p-4 sm:p-5 shadow-sm">
            <div class="mb-4">
                <h5 class="font-bold text-slate-800 mb-1">Ganti Password Akun</h5>
                <p class="text-xs text-slate-400 font-semibold mb-0 uppercase tracking-wider">Perbarui sandi login demi keamanan data Anda</p>
            </div>

            @if(session('success_password'))
                <div class="alert alert-success border-0 shadow-sm rounded-xl d-flex align-items-center gap-3 py-2 px-3 mb-4 bg-emerald-50 text-emerald-800" role="alert">
                    <div class="bg-emerald-500 text-white rounded-full p-0.5 h-6 w-6 flex items-center justify-center">
                        <i class="bi bi-check-lg text-xs"></i>
                    </div>
                    <div class="text-xs font-semibold">
                        {{ session('success_password') }}
                    </div>
                </div>
            @endif

            <form action="{{ route('user.profile.password') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-2">Password Saat Ini <span class="text-rose-500">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-slate-50 border-slate-200/80 text-slate-400" style="border-radius:12px 0 0 12px; border-right:none;"><i class="bi bi-lock"></i></span>
                            <input type="password" name="current_password" id="current_password" class="form-control border-slate-200/80 text-xs focus:border-blue-500 focus:ring-0 shadow-none @error('current_password') is-invalid @enderror" placeholder="Masukkan sandi saat ini" style="border-radius:0; border-right:none; padding: 0.65rem 0.8rem; font-weight: 500;">
                            <button class="btn btn-outline-secondary toggle-pass border-slate-200/80 text-slate-400 shadow-none" type="button" data-target="current_password" style="border-radius:0 12px 12px 0; border-left:none;">
                                <i class="bi bi-eye"></i>
                            </button>
                            @error('current_password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="col-12 col-sm-6">
                        <label class="form-label text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-2">Password Baru <span class="text-rose-500">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-slate-50 border-slate-200/80 text-slate-400" style="border-radius:12px 0 0 12px; border-right:none;"><i class="bi bi-lock-fill"></i></span>
                            <input type="password" name="password" id="new_password" class="form-control border-slate-200/80 text-xs focus:border-blue-500 focus:ring-0 shadow-none @error('password') is-invalid @enderror" placeholder="Minimal 8 karakter" style="border-radius:0; border-right:none; padding: 0.65rem 0.8rem; font-weight: 500;">
                            <button class="btn btn-outline-secondary toggle-pass border-slate-200/80 text-slate-400 shadow-none" type="button" data-target="new_password" style="border-radius:0 12px 12px 0; border-left:none;">
                                <i class="bi bi-eye"></i>
                            </button>
                            @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="col-12 col-sm-6">
                        <label class="form-label text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-2">Ulangi Password Baru <span class="text-rose-500">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-slate-50 border-slate-200/80 text-slate-400" style="border-radius:12px 0 0 12px; border-right:none;"><i class="bi bi-lock-fill"></i></span>
                            <input type="password" name="password_confirmation" id="confirm_password" class="form-control border-slate-200/80 text-xs focus:border-blue-500 focus:ring-0 shadow-none" placeholder="Ketik ulang sandi baru" style="border-radius:0; border-right:none; padding: 0.65rem 0.8rem; font-weight: 500;">
                            <button class="btn btn-outline-secondary toggle-pass border-slate-200/80 text-slate-400 shadow-none" type="button" data-target="confirm_password" style="border-radius:0 12px 12px 0; border-left:none;">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-warning text-dark px-4 py-2.5 rounded-xl text-xs font-bold hover-lift flex items-center gap-1.5">
                        <i class="bi bi-shield-check"></i> Simpan Password Baru
                    </button>
                </div>
            </form>
        </div>

    </div>

    <!-- Right Column: Profil Summary widget -->
    <div class="col-12 col-lg-4">
        <div class="bg-white rounded-2xl border border-slate-200/60 p-4 shadow-sm text-center">
            
            <!-- Avatar -->
            <div class="rounded-full bg-blue-600 text-white font-bold h-16 w-16 flex items-center justify-center mx-auto mb-3 text-xl shadow-md border-4 border-white shadow-blue-500/10">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>
            <h5 class="font-extrabold text-slate-800 mb-1 leading-none">{{ $user->name }}</h5>
            <span class="text-xs text-slate-400 font-semibold block mb-3">{{ $user->email }}</span>
            <span class="bg-blue-50 text-blue-600 text-[10px] font-bold py-1 px-2.5 rounded-full border border-blue-100/50 uppercase tracking-wider">
                Pengguna (Warga)
            </span>

            <hr class="border-slate-100 my-4">

            <ul class="list-unstyled mb-0 text-xs text-slate-500 font-semibold space-y-3">
                <li class="d-flex justify-content-between items-center py-1">
                    <span><i class="bi bi-calendar3 me-2 text-slate-400"></i>Bergabung</span>
                    <span class="text-slate-800 font-bold">{{ $user->created_at->format('d M Y') }}</span>
                </li>
                <li class="d-flex justify-content-between items-center py-1">
                    <span><i class="bi bi-telephone me-2 text-slate-400"></i>Telepon</span>
                    <span class="text-slate-800 font-bold">{{ $user->phone ?: '-' }}</span>
                </li>
                <li class="d-flex justify-content-between items-start py-1">
                    <span><i class="bi bi-geo-alt me-2 text-slate-400"></i>Domisili</span>
                    <span class="text-slate-800 font-bold text-end" style="max-width: 60%;">
                        {{ $user->address ?: '-' }}
                    </span>
                </li>
            </ul>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Toggle Password View helper
    document.querySelectorAll('.toggle-pass').forEach(btn => {
        btn.addEventListener('click', function () {
            const targetId = this.dataset.target;
            const input = document.getElementById(targetId);
            const icon = this.querySelector('i');

            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.replace('bi-eye', 'bi-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.replace('bi-eye-slash', 'bi-eye');
            }
        });
    });
</script>
@endpush