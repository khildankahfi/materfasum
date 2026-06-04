<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - Materfasum</title>
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

        .password-strength { height: 4px; border-radius: 2px; transition: all .3s; margin-top: .4rem; }
        .strength-label { font-size: .75rem; margin-top: .25rem; }
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
                 style="width:56px;height:56px;background:#f0fdf4;">
                <i class="bi bi-shield-lock text-success" style="font-size:1.5rem;"></i>
            </div>
            <h5 class="fw-700 mb-1">Buat Password Baru</h5>
            <p class="text-muted" style="font-size:.88rem;">
                Pastikan password baru Anda kuat dan mudah diingat.
            </p>
        </div>

        @if($errors->any())
            <div class="alert alert-danger d-flex align-items-center gap-2 mb-4" style="border-radius:10px;">
                <i class="bi bi-exclamation-circle-fill flex-shrink-0"></i>
                <div>{{ $errors->first() }}</div>
            </div>
        @endif

        <form action="{{ route('password.update') }}" method="POST">
            @csrf

            <input type="hidden" name="token" value="{{ $token }}">

            <div class="mb-3">
                <label class="form-label fw-500" style="font-size:.88rem;">
                    Email <span class="text-danger">*</span>
                </label>
                <div class="input-group">
                    <span class="input-group-text bg-light" style="border-radius:10px 0 0 10px;border-right:none;">
                        <i class="bi bi-envelope text-muted"></i>
                    </span>
                    <input type="email" name="email"
                           class="form-control @error('email') is-invalid @enderror"
                           value="{{ old('email', $email) }}" required readonly
                           style="border-radius:0 10px 10px 0;background:#f8fafc;">
                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-500" style="font-size:.88rem;">
                    Password Baru <span class="text-danger">*</span>
                </label>
                <div class="input-group">
                    <span class="input-group-text bg-light" style="border-radius:10px 0 0 10px;border-right:none;">
                        <i class="bi bi-lock text-muted"></i>
                    </span>
                    <input type="password" name="password" id="new_password"
                        class="form-control @error('password') is-invalid @enderror"
                        placeholder="Min. 8 karakter" required
                        style="border-radius:0 10px 10px 0;border-right:none;">
                    <button type="button" class="input-group-text bg-light toggle-btn"
                            data-target="new_password" style="border-radius:0 10px 10px 0;cursor:pointer;">
                        <i class="bi bi-eye text-muted"></i>
                    </button>
                    @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                {{-- Password strength bar --}}
                <div class="password-strength bg-secondary opacity-25" id="strength-bar"></div>
                <div class="strength-label text-muted" id="strength-label"></div>
            </div>

            <div class="mb-4">
                <label class="form-label fw-500" style="font-size:.88rem;">
                    Konfirmasi Password Baru <span class="text-danger">*</span>
                </label>
                <div class="input-group">
                    <span class="input-group-text bg-light" style="border-radius:10px 0 0 10px;border-right:none;">
                        <i class="bi bi-lock-fill text-muted"></i>
                    </span>
                    <input type="password" name="password_confirmation" id="confirm_password"
                        class="form-control" placeholder="Ulangi password baru" required
                        style="border-radius:0 10px 10px 0;border-right:none;">
                    <button type="button" class="input-group-text bg-light toggle-btn"
                            data-target="confirm_password" style="border-radius:0 10px 10px 0;cursor:pointer;">
                        <i class="bi bi-eye text-muted"></i>
                    </button>
                </div>
            </div>

            <button type="submit" class="btn btn-primary btn-submit w-100 text-white mb-3">
                <i class="bi bi-check-circle me-2"></i>Reset Password
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
<script>
    // Toggle show/hide password
    document.querySelectorAll('.toggle-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            const input = document.getElementById(this.dataset.target);
            const icon  = this.querySelector('i');
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.replace('bi-eye', 'bi-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.replace('bi-eye-slash', 'bi-eye');
            }
        });
    });

    // Password strength indicator
    document.getElementById('new_password').addEventListener('input', function () {
        const val   = this.value;
        const bar   = document.getElementById('strength-bar');
        const label = document.getElementById('strength-label');

        let strength = 0;
        if (val.length >= 8)                          strength++;
        if (/[A-Z]/.test(val))                        strength++;
        if (/[0-9]/.test(val))                        strength++;
        if (/[^A-Za-z0-9]/.test(val))                 strength++;

        const config = {
            0: { width: '0%',   color: 'transparent', text: '' },
            1: { width: '25%',  color: '#ef4444',      text: 'Lemah' },
            2: { width: '50%',  color: '#f59e0b',      text: 'Sedang' },
            3: { width: '75%',  color: '#3b82f6',      text: 'Kuat' },
            4: { width: '100%', color: '#10b981',      text: 'Sangat Kuat' },
        };

        const cfg = config[strength];
        bar.style.width    = cfg.width;
        bar.style.background = cfg.color;
        bar.style.opacity  = val.length ? '1' : '0.25';
        label.textContent  = cfg.text;
        label.style.color  = cfg.color;
    });
</script>
</body>
</html>