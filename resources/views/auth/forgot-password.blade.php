<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password - MaterFasum</title>
    <!-- Bootstrap 5 -->
    <link href="{{ asset('vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="{{ asset('vendor/bootstrap-icons/bootstrap-icons.min.css') }}" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Tailwind CSS (Vite) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: radial-gradient(circle at 10% 20%, rgb(240, 245, 255) 0%, rgb(255, 255, 255) 90%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
            position: relative;
            overflow-x: hidden;
        }

        /* Abstract Background Elements */
        .bg-shape-1 {
            position: absolute; width: 450px; height: 450px; border-radius: 50%;
            background: linear-gradient(135deg, rgba(37, 99, 235, 0.08) 0%, rgba(99, 102, 241, 0.04) 100%);
            top: -120px; right: -120px; z-index: 0; filter: blur(60px);
        }
        .bg-shape-2 {
            position: absolute; width: 350px; height: 350px; border-radius: 50%;
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.06) 0%, rgba(20, 184, 166, 0.02) 100%);
            bottom: -100px; left: -100px; z-index: 0; filter: blur(50px);
        }

        .auth-container {
            width: 100%;
            max-width: 440px;
            z-index: 10;
        }

        .auth-card {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(226, 232, 240, 0.8);
            border-radius: 28px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.04), 0 10px 20px -10px rgba(0, 0, 0, 0.02);
            overflow: hidden;
        }

        .brand-section {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding-top: 2.75rem;
            padding-bottom: 0.75rem;
        }

        .brand-icon {
            background-color: #2563eb;
            color: #ffffff;
            border-radius: 18px;
            height: 52px;
            width: 52px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 10px 15px -3px rgba(37, 99, 235, 0.2);
            font-size: 1.45rem;
            margin-bottom: 0.85rem;
        }

        .brand-name {
            font-weight: 800;
            font-size: 1.35rem;
            color: #1e293b;
            letter-spacing: -0.5px;
            line-height: 1;
        }

        .brand-subtitle {
            font-size: 0.72rem;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: #2563eb;
            font-weight: 700;
            margin-top: 0.35rem;
        }

        .auth-body {
            padding: 1.5rem 2.25rem 2.75rem;
        }

        .input-group-custom {
            position: relative;
        }

        .input-group-custom input {
            width: 100%;
            padding: 0.75rem 1rem 0.75rem 2.85rem;
            font-size: 0.875rem;
            border-radius: 14px;
            border: 1px solid #cbd5e1;
            background-color: #ffffff;
            transition: all 0.2s ease;
            color: #1e293b;
            font-weight: 500;
        }

        .input-group-custom input:focus {
            outline: none;
            border-color: #2563eb;
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1);
        }

        .input-group-custom .input-icon {
            position: absolute;
            left: 1.1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 1.1rem;
        }

        .form-label-custom {
            font-size: 0.72rem;
            font-weight: 700;
            color: #475569;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 0.5rem;
            display: block;
        }

        .btn-submit {
            background-color: #2563eb;
            color: #ffffff;
            font-size: 0.875rem;
            font-weight: 700;
            padding: 0.85rem;
            border: none;
            border-radius: 14px;
            box-shadow: 0 10px 15px -3px rgba(37, 99, 235, 0.15);
            transition: all 0.2s ease;
        }

        .btn-submit:hover {
            background-color: #1d4ed8;
            transform: translateY(-1px);
            box-shadow: 0 12px 20px -8px rgba(37, 99, 235, 0.35);
        }
    </style>
</head>
<body>

    <div class="bg-shape-1"></div>
    <div class="bg-shape-2"></div>

    <div class="auth-container">
        <div class="auth-card">
            
            <!-- Brand Section -->
            <div class="brand-section">
                <div class="brand-icon">
                    <i class="bi bi-megaphone-fill"></i>
                </div>
                <span class="brand-name">MaterFasum</span>
                <span class="brand-subtitle">Citizens Gresik</span>
            </div>

            <!-- Auth Body -->
            <div class="auth-body">
                <div class="text-center mb-4">
                    <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-3"
                         style="width:56px;height:56px;background:#eff6ff;">
                        <i class="bi bi-key text-primary" style="font-size:1.5rem;"></i>
                    </div>
                    <h5 class="font-extrabold text-slate-800 mb-1">Lupa Password?</h5>
                    <p class="text-slate-400 font-semibold text-xs leading-normal">
                        Masukkan email Anda dan kami akan mengirimkan link untuk mereset password.
                    </p>
                </div>

                @if(session('success'))
                    <div class="alert alert-success border-0 shadow-sm rounded-xl d-flex align-items-start gap-2.5 py-3 px-3.5 mb-4 bg-emerald-50 text-emerald-800 text-xs font-semibold" role="alert">
                        <i class="bi bi-check-circle-fill mt-0.5 flex-shrink-0"></i>
                        <div>{{ session('success') }}</div>
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger border-0 shadow-sm rounded-xl d-flex align-items-center gap-2.5 py-3 px-3.5 mb-4 bg-rose-50 text-rose-800 text-xs font-semibold" role="alert">
                        <i class="bi bi-exclamation-circle-fill flex-shrink-0"></i>
                        <div>{{ $errors->first() }}</div>
                    </div>
                @endif

                <form action="{{ route('password.email') }}" method="POST" class="space-y-4">
                    @csrf

                    <div>
                        <label class="form-label-custom">Alamat Email <span class="text-rose-500">*</span></label>
                        <div class="input-group-custom">
                            <span class="input-icon"><i class="bi bi-envelope"></i></span>
                            <input type="email" name="email"
                                   class="form-control @error('email') is-invalid @enderror"
                                   placeholder="contoh@email.com"
                                   value="{{ old('email') }}" required>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary btn-submit w-full text-white mt-4">
                        <i class="bi bi-send me-1.5"></i>Kirim Link Reset Password
                    </button>
                </form>

                <div class="text-center mt-4">
                    <a href="{{ route('login') }}" class="text-decoration-none text-slate-400 hover:text-slate-600 text-xs font-semibold">
                        <i class="bi bi-arrow-left me-1"></i> Kembali ke halaman login
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap Bundle -->
    <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
</body>
</html>