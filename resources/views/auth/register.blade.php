<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - Materfasum</title>
    <link href="{{ asset('vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/bootstrap-icons/bootstrap-icons.min.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('vendor/intl-tel-input/css/intlTelInput.css') }}">
    <style>
        * { font-family: 'Inter', sans-serif; }
        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #1e3a5f 0%, #2563eb 50%, #1d4ed8 100%);
            display: flex; align-items: center; justify-content: center;
            padding: 2rem 1rem;
        }
        .auth-card { background: #fff; border-radius: 20px; box-shadow: 0 25px 60px rgba(0,0,0,.25); overflow: hidden; width: 100%; max-width: 480px; }
        .auth-header { background: linear-gradient(135deg, #1e3a5f, #2563eb); padding: 2rem; text-align: center; color: #fff; }
        .auth-header .logo-icon { width: 55px; height: 55px; background: rgba(255,255,255,.15); border-radius: 14px; display: flex; align-items: center; justify-content: center; margin: 0 auto .75rem; font-size: 1.5rem; }
        .auth-body { padding: 2rem; }
        .form-control { border-radius: 10px; border-color: #e2e8f0; }
        .form-control:focus { border-color: #2563eb; box-shadow: 0 0 0 3px rgba(37,99,235,.15); }
        .btn-register { background: linear-gradient(135deg, #2563eb, #1d4ed8); border: none; border-radius: 10px; padding: .75rem; font-weight: 600; }
        .btn-register:hover { transform: translateY(-1px); box-shadow: 0 4px 15px rgba(37,99,235,.4); }

        /* intl-tel-input custom styling */
        .iti { width: 100%; }
        .iti__tel-input {
            border-radius: 10px !important;
            border-color: #e2e8f0 !important;
            padding-left: 90px !important;
            width: 100% !important;
            height: calc(1.5em + .75rem + 2px);
            padding-top: .375rem;
            padding-bottom: .375rem;
            font-size: 1rem;
            line-height: 1.5;
            transition: border-color .15s ease-in-out, box-shadow .15s ease-in-out;
        }
        .iti__tel-input:focus {
            border-color: #2563eb !important;
            box-shadow: 0 0 0 3px rgba(37,99,235,.15) !important;
            outline: none;
        }
        .iti__tel-input.is-invalid {
            border-color: #dc3545 !important;
        }
        .iti__flag-container {
            padding: 0 6px;
        }
        .iti__selected-dial-code {
            font-size: .85rem;
            color: #374151;
            font-weight: 500;
        }
        .iti__dropdown-content {
            border-radius: 10px;
            box-shadow: 0 8px 25px rgba(0,0,0,.12);
            border-color: #e2e8f0;
        }
        .iti__search-input {
            border-radius: 8px;
        }
        .phone-hint {
            font-size: .78rem;
            color: #64748b;
            margin-top: .3rem;
        }
    </style>
</head>
<body>
<div class="auth-card">
    <div class="auth-header">
        <div class="logo-icon"><i class="bi bi-buildings"></i></div>
        <h5 class="fw-700 mb-1">Buat Akun Baru</h5>
        <p class="mb-0 opacity-75" style="font-size:.85rem;">Bergabung dan laporkan fasilitas rusak di sekitar Anda</p>
    </div>

    <div class="auth-body">
        @if($errors->any())
            <div class="alert alert-danger" style="border-radius:10px;font-size:.88rem;">
                <i class="bi bi-exclamation-circle-fill me-2"></i>
                <strong>Terjadi kesalahan:</strong>
                <ul class="mb-0 mt-1 ps-3">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('register.post') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label class="form-label fw-500" style="font-size:.88rem;">Nama Lengkap <span class="text-danger">*</span></label>
                <div class="input-group">
                    <span class="input-group-text bg-light" style="border-radius:10px 0 0 10px;border-right:none;">
                        <i class="bi bi-person text-muted"></i>
                    </span>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                           placeholder="Masukkan nama lengkap" value="{{ old('name') }}" required
                           style="border-radius:0 10px 10px 0;">
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-500" style="font-size:.88rem;">Email <span class="text-danger">*</span></label>
                <div class="input-group">
                    <span class="input-group-text bg-light" style="border-radius:10px 0 0 10px;border-right:none;">
                        <i class="bi bi-envelope text-muted"></i>
                    </span>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                           placeholder="contoh@email.com" value="{{ old('email') }}" required
                           style="border-radius:0 10px 10px 0;">
                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-500" style="font-size:.88rem;">Nomor HP</label>

                {{-- Hidden inputs to store parsed phone data for server --}}
                <input type="hidden" name="phone" id="phone_full">
                <input type="hidden" name="phone_code" id="phone_code">

                <input type="tel" id="phone_input"
                       class="form-control @error('phone') is-invalid @enderror"
                       placeholder="812 3456 7890"
                       value="{{ old('phone') }}">

                @error('phone')
                    <div class="text-danger mt-1" style="font-size:.83rem;">
                        <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                    </div>
                @enderror

                <div class="phone-hint">
                    <i class="bi bi-info-circle me-1"></i>Masukkan nomor tanpa angka 0 di depan. Contoh: <strong>812 3456 7890</strong>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-500" style="font-size:.88rem;">Alamat</label>
                <textarea name="address" rows="2"
                    class="form-control @error('address') is-invalid @enderror"
                    placeholder="Masukkan alamat Anda">{{ old('address') }}</textarea>
            </div>

            <div class="row g-3 mb-3">
                <div class="col-12 col-sm-6">
                    <label class="form-label fw-500" style="font-size:.88rem;">Password <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text bg-light" style="border-radius:10px 0 0 10px;border-right:none;">
                            <i class="bi bi-lock text-muted"></i>
                        </span>
                        <input type="password" name="password" id="password"
                               class="form-control @error('password') is-invalid @enderror"
                               placeholder="Min. 8 karakter" required
                               style="border-radius:0 10px 10px 0;">
                        @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="col-12 col-sm-6">
                    <label class="form-label fw-500" style="font-size:.88rem;">Konfirmasi Password <span class="text-danger">*</span></label>
                    <input type="password" name="password_confirmation"
                           class="form-control" placeholder="Ulangi password" required style="border-radius:10px;">
                </div>
            </div>

            <button type="submit" class="btn btn-primary btn-register w-100 text-white mb-3">
                <i class="bi bi-person-check me-2"></i> Buat Akun
            </button>
        </form>

        <p class="text-center mb-0" style="font-size:.88rem;">
            Sudah punya akun?
            <a href="{{ route('login') }}" class="fw-600 text-decoration-none">Masuk di sini</a>
        </p>
    </div>
</div>

<script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('vendor/intl-tel-input/js/intlTelInput.min.js') }}"></script>
<script>
    const input = document.querySelector("#phone_input");

    const iti = window.intlTelInput(input, {
        initialCountry: "id",
        separateDialCode: true,
        countrySearch: true,
        loadUtilsOnInit: "{{ asset('vendor/intl-tel-input/js/utils.js') }}",
    });

    // Prevent leading zero
    input.addEventListener("input", function () {
        if (this.value.startsWith("0")) {
            this.value = this.value.replace(/^0+/, "");
        }
    });

    // Before form submit: extract dial code + number and fill hidden fields
    document.querySelector("form").addEventListener("submit", function () {
        const dialCode = "+" + iti.getSelectedCountryData().dialCode;
        const number   = input.value.replace(/[\s\-]/g, "");

        document.getElementById("phone_code").value = dialCode;
        document.getElementById("phone_full").value  = number ? number : "";
    });
</script>
</body>
</html>