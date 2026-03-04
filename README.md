# Simple Attendance App (Tugas Luar)

Aplikasi presensi sederhana berbasis PHP Native untuk memantau kehadiran pegawai di lapangan menggunakan verifikasi wajah dan GPS.

## Fitur Utama

- **Face Verification**: Deteksi wajah real-time sebelum melakukan presensi.
- **Dual Capture**: Tombol terpisah untuk ambil foto wajah dan bukti kegiatan.
- **Geolocation**: Pencatatan koordinat GPS otomatis.
- **Simple Migration**: Eksekusi file SQL via Terminal (CLI) tanpa perlu buka DBMS.

## Cara Instalasi

1. Clone repository.
2. Buat database `coba_absensi` di MySQL.
3. Sesuaikan konfigurasi di `koneksi.php`.
4. Jalankan migrasi database via terminal:

   ```bash
   php migrate.php
   ```

## Teknologi

- PHP Native & MySQL
- Bootstrap 5 & Face-api.js

**Catatan:** Wajib menggunakan HTTPS jika di-deploy ke server agar fitur Kamera dan GPS dapat berfungsi.

---
