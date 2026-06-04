<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Materfasum</title>
    <link href="{{ asset('vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/bootstrap-icons/bootstrap-icons.min.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Inter', sans-serif; }
        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #1e3a5f 0%, #2563eb 50%, #1d4ed8 100%);
            display: flex; align-items: center; justify-content: center;
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
            padding: 2.5rem 2rem;
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
        .form-floating label { font-size: .88rem; color: #64748b; }
        .form-control { border-radius: 10px; border-color: #e2e8f0; padding: .75rem 1rem; }
        .form-control:focus { border-color: #2563eb; box-shadow: 0 0 0 3px rgba(37,99,235,.15); }
        .btn-login {
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            border: none; border-radius: 10px;
            padding: .75rem; font-weight: 600;
            font-size: .95rem; transition: all .2s;
        }
        .btn-login:hover { transform: translateY(-1px); box-shadow: 0 4px 15px rgba(37,99,235,.4); }
        .divider { position: relative; text-align: center; margin: 1.25rem 0; }
        .divider::before {
            content: ''; position: absolute;
            top: 50%; left: 0; right: 0;
            height: 1px; background: #e2e8f0;
        }
        .divider span {
            background: #fff; padding: 0 .75rem;
            position: relative; color: #94a3b8; font-size: .82rem;
        }
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
        <h5 class="fw-700 mb-1">Selamat Datang 👋</h5>
        <p class="text-muted mb-4" style="font-size:.88rem;">Masuk ke akun Anda untuk melanjutkan</p>

        @if($errors->any())
            <div class="alert alert-danger d-flex align-items-center gap-2 mb-3" style="border-radius:10px;">
                <i class="bi bi-exclamation-circle-fill"></i>
                <div>{{ $errors->first() }}</div>
            </div>
        @endif

        @if(session('success'))
            <div class="alert alert-success d-flex align-items-center gap-2 mb-3" style="border-radius:10px;">
                <i class="bi bi-check-circle-fill"></i>
                <div>{{ session('success') }}</div>
            </div>
        @endif

        <form action="{{ route('login.post') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label fw-500" style="font-size:.88rem;">Email</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0" style="border-radius:10px 0 0 10px;">
                        <i class="bi bi-envelope text-muted"></i>
                    </span>
                    <input type="email" name="email" class="form-control border-start-0 @error('email') is-invalid @enderror"
                           placeholder="contoh@email.com" value="{{ old('email') }}" required
                           style="border-radius:0 10px 10px 0;">
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-500" style="font-size:.88rem;">Password</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0" style="border-radius:10px 0 0 10px;">
                        <i class="bi bi-lock text-muted"></i>
                    </span>
                    <input type="password" name="password" id="password"
                           class="form-control border-start-0 @error('password') is-invalid @enderror"
                           placeholder="••••••••" required style="border-radius:0 10px 10px 0; border-right:none;">
                    <button type="button" class="input-group-text bg-light border-start-0"
                            style="border-radius:0 10px 10px 0;cursor:pointer;" onclick="togglePassword()">
                        <i class="bi bi-eye text-muted" id="eyeIcon"></i>
                    </button>
                </div>
            </div>

            <div class="d-flex align-items-center justify-content-between mb-4">
                <div class="form-check">
                    <input type="checkbox" name="remember" class="form-check-input" id="remember">
                    <label class="form-check-label" for="remember" style="font-size:.85rem;">Ingat saya</label>
                </div>
                <a href="{{ route('password.request') }}" class="text-decoration-none"
                   style="font-size:.85rem;color:#2563eb;font-weight:500;">
                    Lupa password?
                </a>
            </div>

            <button type="submit" class="btn btn-primary btn-login w-100 text-white mb-3">
                <i class="bi bi-box-arrow-in-right me-2"></i> Masuk
            </button>
        </form>

        <div class="divider"><span>Belum punya akun?</span></div>

        <a href="{{ route('register') }}" class="btn btn-outline-primary w-100" style="border-radius:10px;font-weight:600;">
            <i class="bi bi-person-plus me-2"></i> Daftar Sekarang
        </a>
    </div>
</div>

<script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script>
    function togglePassword() {
        const pass = document.getElementById('password');
        const icon = document.getElementById('eyeIcon');
        if (pass.type === 'password') {
            pass.type = 'text';
            icon.className = 'bi bi-eye-slash text-muted';
        } else {
            pass.type = 'password';
            icon.className = 'bi bi-eye text-muted';
        }
    }
</script>
</body>
</html>