# MaterFasum

> **Sistem Informasi Pelaporan Fasilitas Umum**  
> Aplikasi web berbasis Laravel untuk melaporkan, memantau, dan mengelola kondisi fasilitas umum.

---

## 📋 Daftar Isi

- [Tentang Proyek](#tentang-proyek)
- [Fitur](#fitur)
- [Teknologi](#teknologi)
- [Prasyarat](#prasyarat)
- [Instalasi](#instalasi)
- [Konfigurasi Environment](#konfigurasi-environment)
- [Menjalankan Aplikasi](#menjalankan-aplikasi)
- [Scripts yang Tersedia](#scripts-yang-tersedia)

---

## Tentang Proyek

MaterFasum memungkinkan masyarakat untuk melaporkan fasilitas umum yang rusak secara langsung melalui web. Admin dapat memantau, mengelola, dan memperbarui status setiap laporan melalui dashboard yang terintegrasi.

---

## ✨ Fitur

- 📝 Input laporan kerusakan fasilitas umum
- 📋 Daftar & pencarian laporan
- 🔄 Monitoring status laporan secara real-time (Laravel Reverb)
- 🛠️ Manajemen laporan oleh admin
- 📊 Dashboard statistik admin
- 🔐 Autentikasi pengguna & admin

---

## 🛠️ Teknologi

| Layer      | Teknologi                              |
|------------|----------------------------------------|
| Backend    | Laravel 12, PHP 8.2+                   |
| Frontend   | Blade, Tailwind CSS 4, Vite            |
| Database   | MySQL                                  |
| Real-time  | Laravel Reverb + Laravel Echo + Pusher |
| Queue      | Laravel Queue (database driver)        |
| Testing    | PestPHP                                |

---

## ⚙️ Prasyarat

Pastikan sistem kamu sudah menginstal:

- **PHP** >= 8.2
- **Composer**
- **Node.js** >= 18 & **npm**
- **MySQL** (atau database kompatibel)

---

## 🚀 Instalasi

### Cara Cepat (menggunakan Composer script)

```bash
git clone <url-repository> materfasum
cd materfasum
composer run setup
```

Script `setup` akan otomatis:
1. Menginstal dependensi PHP (`composer install`)
2. Menyalin `.env.example` ke `.env` (jika belum ada)
3. Generate `APP_KEY`
4. Menjalankan migrasi database
5. Menginstal dependensi Node.js (`npm install`)
6. Build aset frontend (`npm run build`)

---

### Cara Manual

```bash
# 1. Clone repository
git clone <url-repository> materfasum
cd materfasum

# 2. Install dependensi PHP
composer install

# 3. Salin file environment
cp .env.example .env          # Linux/macOS
copy .env.example .env        # Windows

# 4. Generate application key
php artisan key:generate

# 5. Konfigurasi database di .env, lalu jalankan migrasi
php artisan migrate

# 6. Install dependensi Node.js & build aset
npm install
npm run build
```

---

## 🔧 Konfigurasi Environment

Sesuaikan file `.env` setelah menyalinnya dari `.env.example`:

```dotenv
APP_NAME=MaterFasum
APP_ENV=local
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=materfasum
DB_USERNAME=root
DB_PASSWORD=

BROADCAST_CONNECTION=reverb   # Aktifkan untuk fitur real-time
QUEUE_CONNECTION=database
```

> **Catatan:** Untuk fitur real-time (Laravel Reverb), pastikan konfigurasi `REVERB_*` juga diisi dengan benar.

---

## ▶️ Menjalankan Aplikasi

### Development (semua service sekaligus)

```bash
composer run dev
```

Perintah ini menjalankan secara bersamaan:
- `php artisan serve` — web server
- `php artisan queue:listen` — queue worker
- `npm run dev` — Vite dev server (HMR)

### Atau jalankan secara terpisah

```bash
php artisan serve          # Web server
php artisan queue:listen   # Queue worker
npm run dev                # Vite HMR
```

Akses aplikasi di: **http://localhost:8000**

---

## 📜 Scripts yang Tersedia

| Perintah              | Deskripsi                                         |
|-----------------------|---------------------------------------------------|
| `composer run setup`  | Instalasi lengkap pertama kali                    |
| `composer run dev`    | Jalankan semua service development sekaligus      |
| `composer run test`   | Jalankan test suite (PestPHP)                     |
| `npm run dev`         | Vite dev server dengan Hot Module Replacement     |
| `npm run build`       | Build aset untuk production                       |

---

## 📄 Lisensi

Proyek ini dilisensikan di bawah [MIT License](https://opensource.org/licenses/MIT).
