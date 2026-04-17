@extends('layouts.user')

@section('title', 'Edit Profil')
@section('page-title', 'Edit Profil')
@section('breadcrumb')
    <li class="breadcrumb-item active">Edit Profil</li>
@endsection

@section('content')

<div class="mb-4">
    <h5 class="fw-700 mb-1">Edit Profil</h5>
    <p class="text-muted mb-0" style="font-size:.9rem;">Perbarui informasi akun dan keamanan Anda.</p>
</div>

<div class="row g-4">

    {{-- Kolom Kiri: Info Profil --}}
    <div class="col-12 col-lg-8">

        {{-- ── Informasi Profil ── --}}
        <div class="card mb-4">
            <div class="card-header">
                <i class="bi bi-person-circle me-2 text-primary"></i>Informasi Profil
            </div>
            <div class="card-body">

                @if(session('success'))
                    <div class="alert alert-success d-flex align-items-center gap-2 mb-4" style="border-radius:10px;">
                        <i class="bi bi-check-circle-fill"></i>
                        {{ session('success') }}
                    </div>
                @endif

                <form action="{{ route('user.profile.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label fw-500" style="font-size:.88rem;">
                                Nama Lengkap <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light" style="border-radius:10px 0 0 10px;border-right:none;">
                                    <i class="bi bi-person text-muted"></i>
                                </span>
                                <input type="text" name="name"
                                       class="form-control @error('name') is-invalid @enderror"
                                       value="{{ old('name', $user->name) }}" required
                                       style="border-radius:0 10px 10px 0;">
                                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-500" style="font-size:.88rem;">
                                Email <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light" style="border-radius:10px 0 0 10px;border-right:none;">
                                    <i class="bi bi-envelope text-muted"></i>
                                </span>
                                <input type="email" name="email"
                                       class="form-control @error('email') is-invalid @enderror"
                                       value="{{ old('email', $user->email) }}" required
                                       style="border-radius:0 10px 10px 0;">
                                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="col-12 col-sm-6">
                            <label class="form-label fw-500" style="font-size:.88rem;">Nomor HP</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light" style="border-radius:10px 0 0 10px;border-right:none;">
                                    <i class="bi bi-telephone text-muted"></i>
                                </span>
                                <input type="text" name="phone"
                                       class="form-control @error('phone') is-invalid @enderror"
                                       value="{{ old('phone', $user->phone) }}"
                                       placeholder="cth: +6281234567890"
                                       style="border-radius:0 10px 10px 0;">
                                @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-500" style="font-size:.88rem;">Alamat</label>
                            <textarea name="address" rows="3"
                                class="form-control @error('address') is-invalid @enderror"
                                placeholder="Masukkan alamat lengkap Anda"
                                style="border-radius:10px;">{{ old('address', $user->address) }}</textarea>
                            @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="mt-4 d-flex gap-2">
                        <button type="submit" class="btn btn-primary" style="border-radius:10px;padding:.6rem 1.5rem;">
                            <i class="bi bi-check-lg me-2"></i>Simpan Perubahan
                        </button>
                        <a href="{{ route('user.dashboard') }}" class="btn btn-outline-secondary" style="border-radius:10px;padding:.6rem 1.5rem;">
                            Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>

        {{-- ── Ganti Password ── --}}
        <div class="card">
            <div class="card-header">
                <i class="bi bi-shield-lock me-2 text-warning"></i>Ganti Password
            </div>
            <div class="card-body">

                @if(session('success_password'))
                    <div class="alert alert-success d-flex align-items-center gap-2 mb-4" style="border-radius:10px;">
                        <i class="bi bi-check-circle-fill"></i>
                        {{ session('success_password') }}
                    </div>
                @endif

                <form action="{{ route('user.profile.password') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label fw-500" style="font-size:.88rem;">
                                Password Saat Ini <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light" style="border-radius:10px 0 0 10px;border-right:none;">
                                    <i class="bi bi-lock text-muted"></i>
                                </span>
                                <input type="password" name="current_password" id="current_password"
                                       class="form-control @error('current_password') is-invalid @enderror"
                                       placeholder="Masukkan password saat ini"
                                       style="border-radius:0 10px 10px 0;">
                                <button class="btn btn-outline-secondary toggle-pass" type="button"
                                        data-target="current_password" style="border-radius:0 10px 10px 0;border-left:none;">
                                    <i class="bi bi-eye"></i>
                                </button>
                                @error('current_password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-12 col-sm-6">
                            <label class="form-label fw-500" style="font-size:.88rem;">
                                Password Baru <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light" style="border-radius:10px 0 0 10px;border-right:none;">
                                    <i class="bi bi-lock-fill text-muted"></i>
                                </span>
                                <input type="password" name="password" id="new_password"
                                       class="form-control @error('password') is-invalid @enderror"
                                       placeholder="Min. 8 karakter"
                                       style="border-radius:0 10px 10px 0;">
                                <button class="btn btn-outline-secondary toggle-pass" type="button"
                                        data-target="new_password" style="border-radius:0 10px 10px 0;border-left:none;">
                                    <i class="bi bi-eye"></i>
                                </button>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-12 col-sm-6">
                            <label class="form-label fw-500" style="font-size:.88rem;">
                                Konfirmasi Password Baru <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light" style="border-radius:10px 0 0 10px;border-right:none;">
                                    <i class="bi bi-lock-fill text-muted"></i>
                                </span>
                                <input type="password" name="password_confirmation" id="confirm_password"
                                       class="form-control"
                                       placeholder="Ulangi password baru"
                                       style="border-radius:0 10px 10px 0;">
                                <button class="btn btn-outline-secondary toggle-pass" type="button"
                                        data-target="confirm_password" style="border-radius:0 10px 10px 0;border-left:none;">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-warning text-dark" style="border-radius:10px;padding:.6rem 1.5rem;font-weight:600;">
                            <i class="bi bi-shield-check me-2"></i>Ubah Password
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>

    {{-- Kolom Kanan: Info Akun --}}
    <div class="col-12 col-lg-4">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-info-circle me-2 text-primary"></i>Info Akun
            </div>
            <div class="card-body">

                {{-- Avatar --}}
                <div class="text-center mb-4">
                    <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center text-white mx-auto mb-3"
                         style="width:72px;height:72px;font-size:1.8rem;font-weight:700;">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                    <div class="fw-600">{{ $user->name }}</div>
                    <div class="text-muted" style="font-size:.82rem;">{{ $user->email }}</div>
                    <span class="badge bg-primary mt-1">Pengguna</span>
                </div>

                <hr>

                <ul class="list-unstyled mb-0" style="font-size:.85rem;">
                    <li class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted"><i class="bi bi-calendar3 me-2"></i>Bergabung</span>
                        <span class="fw-500">{{ $user->created_at->format('d M Y') }}</span>
                    </li>
                    <li class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted"><i class="bi bi-telephone me-2"></i>HP</span>
                        <span class="fw-500">{{ $user->phone ?: '-' }}</span>
                    </li>
                    <li class="d-flex justify-content-between py-2">
                        <span class="text-muted"><i class="bi bi-geo-alt me-2"></i>Alamat</span>
                        <span class="fw-500 text-end" style="max-width:55%;">
                            {{ $user->address ? \Illuminate\Support\Str::limit($user->address, 30) : '-' }}
                        </span>
                    </li>
                </ul>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
    // Toggle show/hide password
    document.querySelectorAll('.toggle-pass').forEach(btn => {
        btn.addEventListener('click', function () {
            const targetId = this.dataset.target;
            const input    = document.getElementById(targetId);
            const icon     = this.querySelector('i');

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