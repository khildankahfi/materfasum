<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - MaterFasum Citizens</title>
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
            transition: all 0.3s ease;
        }

        .brand-section {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding-top: 3rem;
            padding-bottom: 1rem;
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
            padding: 1.5rem 2.25rem 3rem;
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

        .toggle-btn {
            position: absolute;
            right: 0.85rem;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #94a3b8;
            cursor: pointer;
            padding: 0.25rem;
            font-size: 1.1rem;
        }

        .toggle-btn:hover {
            color: #64748b;
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
        
        .btn-submit:active {
            transform: translateY(0);
        }

        .divider {
            position: relative;
            text-align: center;
            margin: 1.75rem 0;
        }

        .divider::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 1px;
            background-color: #e2e8f0;
            z-index: 0;
        }

        .divider span {
            position: relative;
            background-color: #fdfdfd;
            padding: 0 0.85rem;
            color: #94a3b8;
            font-size: 0.72rem;
            font-weight: 700;
            border-radius: 9999px;
            border: 1px solid #f1f5f9;
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
                <h5 class="font-extrabold text-slate-800 mb-1.5">Selamat Datang 👋</h5>
                <p class="text-slate-400 font-semibold text-xs mb-4">Silakan masuk ke akun warga Anda untuk melaporkan kerusakan.</p>

                <!-- Alerts -->
                @if($errors->any())
                    <div class="alert alert-danger border-0 shadow-sm rounded-xl d-flex align-items-center gap-2.5 py-3 px-3.5 mb-3 bg-rose-50 text-rose-800 text-xs font-semibold" role="alert">
                        <i class="bi bi-exclamation-circle-fill flex-shrink-0"></i>
                        <div>{{ $errors->first() }}</div>
                    </div>
                @endif

                @if(session('success'))
                    <div class="alert alert-success border-0 shadow-sm rounded-xl d-flex align-items-center gap-2.5 py-3 px-3.5 mb-3 bg-emerald-50 text-emerald-800 text-xs font-semibold" role="alert">
                        <i class="bi bi-check-circle-fill flex-shrink-0"></i>
                        <div>{{ session('success') }}</div>
                    </div>
                @endif

                <form action="{{ route('login.post') }}" method="POST" class="space-y-4">
                    @csrf
                    
                    <!-- Email field -->
                    <div>
                        <label class="form-label-custom">Email Warga</label>
                        <div class="input-group-custom">
                            <span class="input-icon"><i class="bi bi-envelope"></i></span>
                            <input type="email" name="email" value="{{ old('email') }}" required placeholder="contoh@email.com" class="@error('email') is-invalid @enderror">
                        </div>
                    </div>

                    <!-- Password field -->
                    <div>
                        <label class="form-label-custom">Kata Sandi</label>
                        <div class="input-group-custom">
                            <span class="input-icon"><i class="bi bi-lock"></i></span>
                            <input type="password" name="password" id="password" required placeholder="••••••••" class="@error('password') is-invalid @enderror" style="padding-right: 2.5rem;">
                            <button type="button" class="toggle-btn" onclick="togglePassword()">
                                <i class="bi bi-eye" id="eyeIcon"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Remember and Forgot actions -->
                    <div class="d-flex align-items-center justify-content-between text-xs font-bold pt-1">
                        <div class="form-check text-slate-500">
                            <input type="checkbox" name="remember" class="form-check-input border-slate-300" id="remember">
                            <label class="form-check-label cursor-pointer" for="remember">Ingat Saya</label>
                        </div>
                        <a href="{{ route('password.request') }}" class="text-decoration-none text-blue-600 hover:text-blue-700">Lupa Password?</a>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="btn btn-primary btn-submit w-full mt-4">
                        <i class="bi bi-box-arrow-in-right me-1.5"></i>Masuk Portal Warga
                    </button>
                </form>

                <div class="divider">
                    <span>Belum Punya Akun?</span>
                </div>

                <a href="{{ route('register') }}" class="btn btn-outline-secondary w-full border-slate-200 text-slate-600 rounded-xl text-xs font-bold hover:bg-slate-50 hover:text-slate-900 py-3 text-decoration-none hover:scale-[1.01] transition-transform">
                    <i class="bi bi-person-plus me-1.5"></i>Daftar Akun Baru
                </a>

                <!-- Back to welcome link -->
                <div class="text-center mt-4">
                    <a href="{{ url('/') }}" class="text-decoration-none text-slate-400 hover:text-slate-600 text-xs font-semibold">
                        <i class="bi bi-arrow-left me-1"></i> Kembali ke Beranda
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap Bundle -->
    <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script>
        function togglePassword() {
            const pass = document.getElementById('password');
            const icon = document.getElementById('eyeIcon');
            if (pass.type === 'password') {
                pass.type = 'text';
                icon.className = 'bi bi-eye-slash';
            } else {
                pass.type = 'password';
                icon.className = 'bi bi-eye';
            }
        }
    </script>
</body>
</html>