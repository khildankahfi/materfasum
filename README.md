# MaterFasum - Sistem Informasi Pelaporan Fasilitas Umum

## Deskripsi
MaterFasum adalah aplikasi berbasis web yang digunakan untuk melaporkan dan memantau kondisi fasilitas umum yang rusak. Sistem ini memungkinkan pengguna untuk mengirim laporan dan admin untuk mengelola serta memantau laporan yang masuk.

## Fitur
- Input laporan fasilitas umum rusak
- Melihat daftar laporan
- Monitoring status laporan
- Manajemen data laporan (admin)
- Dashboard admin
- Login pengguna & admin

## Teknologi yang Digunakan
- Laravel (Backend Framework)
- MySQL (Database)
- Blade / Bootstrap (Frontend)

## Gambaran Sistem
Pengguna dapat mengirim laporan terkait fasilitas umum yang rusak, kemudian admin dapat melihat, mengelola, dan memantau status laporan melalui dashboard.

![Dashboard](dashboard.png)

## Cara Menjalankan Project
1. Clone repository
2. Jalankan `composer install`
3. Copy `.env.example` menjadi `.env`
4. Atur konfigurasi database
5. Jalankan `php artisan key:generate`
6. Jalankan `php artisan migrate`
7. Jalankan server:
   ```bash
   php artisan serve
