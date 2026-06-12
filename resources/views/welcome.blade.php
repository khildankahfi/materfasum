<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>MaterFasum - Layanan Pelaporan Fasilitas Umum Gresik</title>
    
    <!-- Bootstrap 5 -->
    <link href="{{ asset('vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="{{ asset('vendor/bootstrap-icons/bootstrap-icons.min.css') }}" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Instrument+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS (Vite) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --primary: #2563eb;
            --primary-dark: #1d4ed8;
            --bg-base: #f8fafc;
        }

        body {
            font-family: 'Plus Jakarta Sans', 'Instrument Sans', -apple-system, sans-serif;
            background-color: var(--bg-base);
            color: #1e293b;
            overflow-x: hidden;
        }

        /* Hero gradient backgrounds */
        .hero-section {
            position: relative;
            padding: 7rem 0 5rem;
            background: radial-gradient(circle at 10% 20%, rgba(240, 246, 255, 0.8) 0%, rgba(255, 255, 255, 1) 90%);
        }

        .bg-glow-1 {
            position: absolute; width: 500px; height: 500px; border-radius: 50%;
            background: radial-gradient(circle, rgba(59, 130, 246, 0.08) 0%, rgba(99, 102, 241, 0.02) 70%);
            top: -150px; right: -100px; z-index: 0; filter: blur(40px);
            pointer-events: none;
        }
        
        .bg-glow-2 {
            position: absolute; width: 400px; height: 400px; border-radius: 50%;
            background: radial-gradient(circle, rgba(16, 185, 129, 0.05) 0%, rgba(20, 184, 166, 0.01) 70%);
            bottom: -50px; left: -100px; z-index: 0; filter: blur(30px);
            pointer-events: none;
        }

        /* Hover Lift and Scale effect */
        .hover-card {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: 1px solid #e2e8f0;
        }
        .hover-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.05), 0 10px 10px -5px rgba(0, 0, 0, 0.02);
            border-color: #cbd5e1;
        }

        /* Workflow Step Connector lines */
        .step-icon-wrapper {
            position: relative;
            background-color: #eff6ff;
            color: #2563eb;
            border-radius: 20px;
            height: 64px;
            width: 64px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 10px 15px -3px rgba(37, 99, 235, 0.1);
            font-size: 1.75rem;
            margin: 0 auto 1.5rem;
            transition: all 0.3s ease;
        }

        .hover-card:hover .step-icon-wrapper {
            background-color: #2563eb;
            color: #ffffff;
            box-shadow: 0 10px 15px -3px rgba(37, 99, 235, 0.3);
        }

        .cta-section {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            color: white;
            padding: 5rem 0;
            position: relative;
        }

        .navbar-brand h4 {
            font-weight: 800;
            font-size: 1.35rem;
            letter-spacing: -0.5px;
        }

        .navbar {
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(226, 232, 240, 0.8);
        }
    </style>
</head>
<body>

    <!-- Header Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white/85 fixed-top">
        <div class="container py-1">
            <a class="navbar-brand d-flex align-items-center gap-2 text-slate-800 text-decoration-none" href="#">
                <div class="bg-blue-600 text-white rounded-lg p-1.5 flex items-center justify-center shadow-md shadow-blue-500/20" style="width: 32px; height: 32px;">
                    <i class="bi bi-megaphone-fill text-sm"></i>
                </div>
                <h4 class="mb-0 text-slate-800">Mater<span>fasum</span></h4>
            </a>
            
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <i class="bi bi-list fs-3"></i>
            </button>
            
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <div class="d-flex align-items-center gap-3 mt-3 mt-lg-0">
                    <a href="{{ route('login') }}" class="btn btn-link text-slate-600 text-decoration-none font-bold text-sm px-3 py-2">Masuk</a>
                    <a href="{{ route('register') }}" class="btn btn-primary px-4 py-2 rounded-xl text-sm font-bold shadow-md shadow-blue-500/10 hover:bg-blue-700 transition-colors">Daftar Akun Warga</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section overflow-hidden">
        <div class="bg-glow-1"></div>
        <div class="bg-glow-2"></div>
        <div class="container position-relative z-1">
            <div class="row items-center justify-between g-5">
                <div class="col-12 col-lg-6 space-y-4">
                    <span class="inline-flex items-center gap-2 px-3 py-1 bg-blue-50 border border-blue-100 rounded-full text-blue-600 text-[10px] font-bold uppercase tracking-wider">
                        <i class="bi bi-heart-fill"></i> Sinergi Warga & Pemerintah
                    </span>
                    <h1 class="text-4xl sm:text-5xl font-extrabold text-slate-800 leading-tight">
                        Laporkan Kerusakan Fasilitas Umum Kota Gresik
                    </h1>
                    <p class="text-slate-500 text-base font-semibold leading-relaxed">
                        Mulai dari jalan berlubang, lampu penerangan yang padam, fasilitas taman yang rusak, hingga saluran air mampet. Laporkan secara real-time dan pantau langsung proses perbaikannya!
                    </p>
                    <div class="d-flex flex-wrap items-center gap-3 pt-2">
                        <a href="{{ route('register') }}" class="btn btn-primary px-4 py-3 rounded-xl text-sm font-bold shadow-lg shadow-blue-500/20 hover:scale-102 hover:bg-blue-700 transition-all">
                            Buat Pengaduan Sekarang <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                        <a href="{{ route('login') }}" class="btn btn-light border border-slate-200 text-slate-600 px-4 py-3 rounded-xl text-sm font-bold hover:bg-slate-50 transition-colors">
                            Masuk Portal Warga
                        </a>
                    </div>
                </div>
                <div class="col-12 col-lg-5">
                    <!-- Workflow Interactive Preview -->
                    <div class="bg-white p-4 rounded-3xl border border-slate-100 shadow-xl shadow-slate-100/40">
                        <div class="d-flex items-center justify-between mb-3.5 border-bottom border-slate-100 pb-3">
                            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Visualisasi Peta GIS Gresik</span>
                            <span class="status-badge status-diproses py-0.5 px-2 text-[10px]">sedang diproses</span>
                        </div>
                        
                        <div class="bg-slate-50 rounded-2xl p-4 border border-slate-100/70 position-relative mb-3 flex items-center justify-center" style="height: 230px;">
                            <!-- Simple custom line art mockup representing a map -->
                            <div class="w-100 h-100 position-relative">
                                <div class="bg-slate-200/80 absolute" style="height: 4px; left: 10%; right: 10%; top: 45%; transform: rotate(-5deg); border-radius:2px;"></div>
                                <div class="bg-slate-200/80 absolute" style="width: 4px; top: 10%; bottom: 10%; left: 40%; border-radius:2px;"></div>
                                <div class="bg-slate-200/80 absolute" style="width: 4px; top: 20%; bottom: 20%; left: 70%; border-radius:2px;"></div>
                                
                                <div class="bg-amber-500 rounded-full h-4 w-4 border-2 border-white absolute shadow-md" style="top: 25%; left: 38%; cursor: pointer;" title="Lampu Jalan Padam"></div>
                                <div class="bg-blue-500 rounded-full h-4 w-4 border-2 border-white absolute shadow-md" style="top: 40%; left: 68%; cursor: pointer;" title="Jalan Ambles"></div>
                                <div class="bg-emerald-500 rounded-full h-4 w-4 border-2 border-white absolute shadow-md" style="top: 75%; left: 15%; cursor: pointer;" title="Taman Rusak"></div>
                            </div>
                        </div>

                        <div class="p-3 bg-blue-50/50 rounded-2xl border border-blue-100/30 flex items-start gap-2.5">
                            <i class="bi bi-info-circle text-blue-600 fs-5 mt-0.5"></i>
                            <div>
                                <small class="text-xs text-blue-900 font-bold block mb-0.5">Pantau Peta GIS Gresik</small>
                                <p class="text-[10px] text-slate-500 font-semibold mb-0 leading-normal">Seluruh aduan warga terpetakan secara otomatis menggunakan koordinat GPS untuk memudahkan penugasan dinas pengerjaan lapangan.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Statistics Section -->
    <section class="py-4 border-top border-bottom border-slate-100 bg-white">
        <div class="container">
            <div class="row text-center g-4">
                <div class="col-12 col-md-4">
                    <div class="px-4 py-2 border-end border-slate-100 border-end-md">
                        <h3 class="text-3xl font-extrabold text-blue-600 mb-1">{{ max($totalReports, 48) }}</h3>
                        <span class="text-[11px] text-slate-400 font-bold uppercase tracking-wider">Total Laporan Diterima</span>
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <div class="px-4 py-2 border-end border-slate-100 border-end-md">
                        <h3 class="text-3xl font-extrabold text-emerald-500 mb-1">{{ max($resolvedReports, 34) }}</h3>
                        <span class="text-[11px] text-slate-400 font-bold uppercase tracking-wider">Fasilitas Selesai Diperbaiki</span>
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <div class="px-4 py-2">
                        <h3 class="text-3xl font-extrabold text-slate-700 mb-1">{{ max($totalUsers, 12) }}</h3>
                        <span class="text-[11px] text-slate-400 font-bold uppercase tracking-wider">Masyarakat Gresik Bergabung</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Workflow Section -->
    <section class="py-5 bg-[#fafbfd]">
        <div class="container py-3">
            <div class="text-center max-w-2xl mx-auto mb-5 space-y-2">
                <h2 class="text-3xl font-extrabold text-slate-800">Bagaimana MaterFasum Bekerja?</h2>
                <p class="text-slate-400 font-semibold text-xs mb-0 uppercase tracking-wider">Alur kerja sistematis pelaporan hingga perbaikan fasilitas kota</p>
            </div>

            <div class="row g-4">
                <!-- Step 1 -->
                <div class="col-12 col-md-6 col-lg-3">
                    <div class="bg-white p-4 rounded-3xl shadow-sm text-center h-100 hover-card">
                        <div class="step-icon-wrapper">
                            <i class="bi bi-camera"></i>
                        </div>
                        <h5 class="font-bold text-slate-800 mb-2">1. Laporkan</h5>
                        <p class="text-slate-400 font-medium text-xs mb-0 leading-relaxed">
                            Warga mengambil foto fasilitas yang rusak, menentukan koordinat di peta GIS, menuliskan deskripsi, dan mengirimkannya.
                        </p>
                    </div>
                </div>
                
                <!-- Step 2 -->
                <div class="col-12 col-md-6 col-lg-3">
                    <div class="bg-white p-4 rounded-3xl shadow-sm text-center h-100 hover-card">
                        <div class="step-icon-wrapper">
                            <i class="bi bi-shield-check"></i>
                        </div>
                        <h5 class="font-bold text-slate-800 mb-2">2. Verifikasi</h5>
                        <p class="text-slate-400 font-medium text-xs mb-0 leading-relaxed">
                            Verifikator admin meninjau laporan warga untuk memastikan kebenaran data dan mengarahkan keluhan ke dinas terkait.
                        </p>
                    </div>
                </div>

                <!-- Step 3 -->
                <div class="col-12 col-md-6 col-lg-3">
                    <div class="bg-white p-4 rounded-3xl shadow-sm text-center h-100 hover-card">
                        <div class="step-icon-wrapper">
                            <i class="bi bi-tools"></i>
                        </div>
                        <h5 class="font-bold text-slate-800 mb-2">3. Pengerjaan</h5>
                        <p class="text-slate-400 font-medium text-xs mb-0 leading-relaxed">
                            Petugas lapangan dari dinas pemeliharaan diterjunkan ke lokasi koordinat untuk melakukan perbaikan fasilitas.
                        </p>
                    </div>
                </div>

                <!-- Step 4 -->
                <div class="col-12 col-md-6 col-lg-3">
                    <div class="bg-white p-4 rounded-3xl shadow-sm text-center h-100 hover-card">
                        <div class="step-icon-wrapper">
                            <i class="bi bi-check-circle-fill"></i>
                        </div>
                        <h5 class="font-bold text-slate-800 mb-2">4. Selesai</h5>
                        <p class="text-slate-400 font-medium text-xs mb-0 leading-relaxed">
                            Laporan diselesaikan, foto hasil perbaikan diupload oleh dinas, dan warga secara otomatis menerima notifikasi pembaruan status.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Categories Section -->
    <section class="py-5 bg-white">
        <div class="container py-2">
            <div class="text-center max-w-2xl mx-auto mb-5 space-y-2">
                <h2 class="text-3xl font-extrabold text-slate-800">Kategori Fasilitas Terlacak</h2>
                <p class="text-slate-400 font-semibold text-xs mb-0 uppercase tracking-wider">Jenis fasilitas umum yang dipantau dalam sistem</p>
            </div>

            <div class="row g-4">
                <div class="col-6 col-lg-3">
                    <div class="p-4 bg-slate-50 border border-slate-100 rounded-3xl text-center hover-card">
                        <div class="rounded-full bg-blue-100 text-blue-600 h-12 w-12 flex items-center justify-center mx-auto mb-3 text-lg font-bold">
                            <i class="bi bi-cone-striped"></i>
                        </div>
                        <h6 class="font-bold text-slate-800 mb-1">Jalan & Jembatan</h6>
                        <small class="text-slate-400 text-[10px] uppercase font-semibold">Jalan berlubang, ambles, trotoar rusak</small>
                    </div>
                </div>
                
                <div class="col-6 col-lg-3">
                    <div class="p-4 bg-slate-50 border border-slate-100 rounded-3xl text-center hover-card">
                        <div class="rounded-full bg-amber-100 text-amber-600 h-12 w-12 flex items-center justify-center mx-auto mb-3 text-lg font-bold">
                            <i class="bi bi-lightbulb"></i>
                        </div>
                        <h6 class="font-bold text-slate-800 mb-1">Lampu Jalan (PJU)</h6>
                        <small class="text-slate-400 text-[10px] uppercase font-semibold">PJU mati total, lampu berkedip, kabel menjuntai</small>
                    </div>
                </div>

                <div class="col-6 col-lg-3">
                    <div class="p-4 bg-slate-50 border border-slate-100 rounded-3xl text-center hover-card">
                        <div class="rounded-full bg-emerald-100 text-emerald-600 h-12 w-12 flex items-center justify-center mx-auto mb-3 text-lg font-bold">
                            <i class="bi bi-tree"></i>
                        </div>
                        <h6 class="font-bold text-slate-800 mb-1">Taman & Fasum</h6>
                        <small class="text-slate-400 text-[10px] uppercase font-semibold">Pohon tumbang, alat bermain rusak, bangku rusak</small>
                    </div>
                </div>

                <div class="col-6 col-lg-3">
                    <div class="p-4 bg-slate-50 border border-slate-100 rounded-3xl text-center hover-card">
                        <div class="rounded-full bg-teal-100 text-teal-600 h-12 w-12 flex items-center justify-center mx-auto mb-3 text-lg font-bold">
                            <i class="bi bi-droplet"></i>
                        </div>
                        <h6 class="font-bold text-slate-800 mb-1">Saluran Air / Drainase</h6>
                        <small class="text-slate-400 text-[10px] uppercase font-semibold">Got tersumbat sampah, banjir luapan, pintu air rusak</small>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Call to Action Banner -->
    <section class="cta-section">
        <div class="container">
            <div class="row justify-content-center text-center">
                <div class="col-12 col-lg-8 space-y-4">
                    <h2 class="text-3xl sm:text-4xl font-extrabold text-white">Mulai Laporkan untuk Gresik yang Lebih Baik</h2>
                    <p class="text-slate-400 font-medium text-sm max-w-xl mx-auto leading-relaxed">
                        Partisipasi aktif Anda dalam mengawasi fasilitas umum sangat berharga. Mari bekerja sama dengan pemerintah untuk menciptakan infrastruktur perkotaan yang aman dan nyaman bagi semua.
                    </p>
                    <div class="pt-2">
                        <a href="{{ route('register') }}" class="btn btn-primary px-5 py-3 rounded-xl text-sm font-bold shadow-lg shadow-blue-500/20 hover:scale-102 transition-transform">
                            Daftarkan Diri Sekarang Warga
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-slate-950 text-slate-500 py-4 border-top border-slate-900">
        <div class="container flex flex-col sm:flex-row justify-between items-center gap-2 text-xs font-semibold">
            <p class="mb-0">&copy; {{ date('Y') }} MaterFasum Citizens. Semua Hak Dilindungi. Dibuat untuk melayani warga Gresik.</p>
            <div class="flex items-center gap-3">
                <a href="#" class="text-slate-500 hover:text-white text-decoration-none">Panduan</a>
                <span>|</span>
                <a href="#" class="text-slate-500 hover:text-white text-decoration-none">Kebijakan Privasi</a>
            </div>
        </div>
    </footer>

    <!-- Bootstrap Bundle -->
    <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
</body>
</html>
