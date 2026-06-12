<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun - MaterFasum Citizens</title>
    <!-- Bootstrap 5 -->
    <link href="{{ asset('vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="{{ asset('vendor/bootstrap-icons/bootstrap-icons.min.css') }}" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- intlTelInput -->
    <link rel="stylesheet" href="{{ asset('vendor/intl-tel-input/css/intlTelInput.css') }}">
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
            max-width: 500px;
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
            padding-top: 2.5rem;
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
            padding: 1.5rem 2.25rem 2.5rem;
        }

        .input-group-custom {
            position: relative;
        }

        .input-group-custom input, .input-group-custom textarea {
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

        .input-group-custom input:focus, .input-group-custom textarea:focus {
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

        .input-group-custom .textarea-icon {
            position: absolute;
            left: 1.1rem;
            top: 1rem;
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

        .btn-submit:active {
            transform: translateY(0);
        }

        /* intl-tel-input modifications */
        .iti {
            width: 100%;
        }
        .iti__tel-input {
            border-radius: 14px !important;
            border: 1px solid #cbd5e1 !important;
            padding-left: 90px !important;
            width: 100% !important;
            height: 46px !important;
            font-size: 0.875rem !important;
            transition: all 0.2s ease !important;
            font-weight: 500 !important;
            color: #1e293b !important;
        }
        .iti__tel-input:focus {
            border-color: #2563eb !important;
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1) !important;
            outline: none;
        }
        .iti__selected-dial-code {
            font-size: 0.825rem;
            font-weight: 700;
            color: #475569;
        }
        .phone-hint {
            font-size: 0.7rem;
            color: #64748b;
            margin-top: 0.4rem;
            font-weight: 600;
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
                <h5 class="font-extrabold text-slate-800 mb-1.5">Registrasi Warga</h5>
                <p class="text-slate-400 font-semibold text-xs mb-4">Daftarkan akun warga untuk mulai menggunakan layanan kami.</p>

                <!-- Alerts -->
                @if($errors->any())
                    <div class="alert alert-danger border-0 shadow-sm rounded-xl d-flex align-items-start gap-2.5 py-3 px-3.5 mb-3 bg-rose-50 text-rose-800 text-xs font-semibold" role="alert">
                        <i class="bi bi-exclamation-circle-fill flex-shrink-0 mt-0.5"></i>
                        <div>
                            <strong class="block mb-0.5">Gagal membuat akun:</strong>
                            <ul class="mb-0 ps-3 list-disc text-[11px]">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                </div>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                <form action="{{ route('register.post') }}" method="POST" class="space-y-3.5">
                    @csrf
                    
                    <!-- Name field -->
                    <div>
                        <label class="form-label-custom">Nama Lengkap <span class="text-rose-500">*</span></label>
                        <div class="input-group-custom">
                            <span class="input-icon"><i class="bi bi-person"></i></span>
                            <input type="text" name="name" value="{{ old('name') }}" required placeholder="Masukkan nama lengkap" class="@error('name') is-invalid @enderror">
                        </div>
                    </div>

                    <!-- Email field -->
                    <div>
                        <label class="form-label-custom">Alamat Email <span class="text-rose-500">*</span></label>
                        <div class="input-group-custom">
                            <span class="input-icon"><i class="bi bi-envelope"></i></span>
                            <input type="email" name="email" value="{{ old('email') }}" required placeholder="contoh@email.com" class="@error('email') is-invalid @enderror">
                        </div>
                    </div>

                    <!-- Phone Input -->
                    <div>
                        <label class="form-label-custom">Nomor Telepon (HP)</label>
                        <input type="hidden" name="phone" id="phone_full">
                        <input type="hidden" name="phone_code" id="phone_code">
                        
                        <input type="tel" id="phone_input" class="form-control @error('phone') is-invalid @enderror" placeholder="812 3456 7890" value="{{ old('phone') }}">
                        
                        @error('phone')
                            <div class="text-danger text-[11px] font-semibold mt-1">
                                <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                            </div>
                        @enderror

                        <div class="phone-hint">
                            <i class="bi bi-info-circle me-1 text-slate-400"></i>Tuliskan tanpa angka 0 di depan. Contoh: <strong>812 3456 7890</strong>
                        </div>
                    </div>

                    <!-- Address field -->
                    <div>
                        <label class="form-label-custom">Alamat Domisili</label>
                        <div class="input-group-custom">
                            <span class="textarea-icon"><i class="bi bi-geo-alt"></i></span>
                            <textarea name="address" rows="2" placeholder="Masukkan alamat lengkap Anda di Gresik..." style="padding-left: 2.85rem;" class="@error('address') is-invalid @enderror">{{ old('address') }}</textarea>
                        </div>
                    </div>

                    <!-- Password fields (Split on columns) -->
                    <div class="row g-2">
                        <div class="col-12 col-sm-6">
                            <label class="form-label-custom">Sandi Baru <span class="text-rose-500">*</span></label>
                            <div class="input-group-custom">
                                <span class="input-icon"><i class="bi bi-lock"></i></span>
                                <input type="password" name="password" id="password" required placeholder="Min. 8 karakter" class="@error('password') is-invalid @enderror">
                            </div>
                        </div>
                        <div class="col-12 col-sm-6">
                            <label class="form-label-custom">Ulangi Sandi <span class="text-rose-500">*</span></label>
                            <input type="password" name="password_confirmation" required placeholder="Konfirmasi sandi" style="border-radius: 14px; padding: 0.75rem 1rem;" class="form-control border-slate-200/80 text-sm focus:border-blue-500">
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="btn btn-primary btn-submit w-full mt-4">
                        <i class="bi bi-person-check-fill me-1.5"></i>Registrasi Sekarang Warga
                    </button>
                </form>

                <p class="text-center text-xs font-bold text-slate-400 mt-4 mb-0">
                    Sudah Punya Akun Warga?
                    <a href="{{ route('login') }}" class="text-decoration-none text-blue-600 hover:text-blue-700 ms-1">Masuk di Sini</a>
                </p>

                <div class="text-center mt-3.5">
                    <a href="{{ url('/') }}" class="text-decoration-none text-slate-400 hover:text-slate-600 text-xs font-semibold">
                        <i class="bi bi-arrow-left me-1"></i> Kembali ke Beranda
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap Bundle -->
    <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- intlTelInput scripts -->
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
            const number = input.value.replace(/[\s\-]/g, "");

            document.getElementById("phone_code").value = dialCode;
            document.getElementById("phone_full").value = number ? number : "";
        });
    </script>
</body>
</html>