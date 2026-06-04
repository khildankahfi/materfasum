<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password - Materfasum</title>
    <link href="{{ asset('vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/bootstrap-icons/bootstrap-icons.min.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Inter', sans-serif; }
        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #1e3a5f 0%, #2563eb 50%, #1d4ed8 100%);
            display: flex; align-items: center; justify-content: center;
            padding: 2rem 1rem;
        }
        .auth-card {
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 25px 60px rgba(0,0,0,.25);
            overflow: hidden;
            width: 100%;
            max-width: 440px;
        }
        .auth-header {
            background: linear-gradient(135deg, #1e3a5f, #2563eb);
            padding: 2rem;
            text-align: center;
            color: #fff;
        }
        .auth-header .logo-icon {
            width: 60px; height: 60px;
            background: rgba(255,255,255,.15);
            border-radius: 16px;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 1rem;
            font-size: 1.75rem;
        }
        .auth-body { padding: 2rem; }
        .form-control { border-radius: 10px; border-color: #e2e8f0; }
        .form-control:focus { border-color: #2563eb; box-shadow: 0 0 0 3px rgba(37,99,235,.15); }
        .btn-submit {
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            border: none; border-radius: 10px;
            padding: .75rem; font-weight: 600;
            transition: all .2s;
        }
        .btn-submit:hover { transform: translateY(-1px); box-shadow: 0 4px 15px rgba(37,99,235,.4); }
    </style>
</head>
<body>
<div class="auth-card">
    <div class="auth-header">
        <div class="logo-icon"><i class="bi bi-buildings"></i></div>
        <h4 class="fw-700 mb-1">Materfasum</h4>
        <p class="mb-0 opacity-75" style="font-size:.88rem;">Pelaporan Fasilitas Umum</p>
    </div>

    <div class="auth-body">
        <div class="text-center mb-4">
            <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-3"
                 style="width:56px;height:56px;background:#eff6ff;">
                <i class="bi bi-key text-primary" style="font-size:1.5rem;"></i>
            </div>
            <h5 class="fw-700 mb-1">Lupa Password?</h5>
            <p class="text-muted" style="font-size:.88rem;">
                Masukkan email Anda dan kami akan mengirimkan link untuk mereset password.
            </p>
        </div>

        @if(session('success'))
            <div class="alert alert-success d-flex align-items-start gap-2 mb-4" style="border-radius:10px;">
                <i class="bi bi-check-circle-fill mt-1 flex-shrink-0"></i>
                <div>{{ session('success') }}</div>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger d-flex align-items-center gap-2 mb-4" style="border-radius:10px;">
                <i class="bi bi-exclamation-circle-fill flex-shrink-0"></i>
                <div>{{ $errors->first() }}</div>
            </div>
        @endif

        <form action="{{ route('password.email') }}" method="POST">
            @csrf

            <div class="mb-4">
                <label class="form-label fw-500" style="font-size:.88rem;">
                    Alamat Email <span class="text-danger">*</span>
                </label>
                <div class="input-group">
                    <span class="input-group-text bg-light" style="border-radius:10px 0 0 10px;border-right:none;">
                        <i class="bi bi-envelope text-muted"></i>
                    </span>
                    <input type="email" name="email"
                           class="form-control @error('email') is-invalid @enderror"
                           placeholder="contoh@email.com"
                           value="{{ old('email') }}" required
                           style="border-radius:0 10px 10px 0;">
                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            <button type="submit" class="btn btn-primary btn-submit w-100 text-white mb-3">
                <i class="bi bi-send me-2"></i>Kirim Link Reset Password
            </button>
        </form>

        <div class="text-center">
            <a href="{{ route('login') }}" class="text-decoration-none" style="font-size:.88rem;color:#64748b;">
                <i class="bi bi-arrow-left me-1"></i>Kembali ke halaman login
            </a>
        </div>
    </div>
</div>

<script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
</body>
</html>