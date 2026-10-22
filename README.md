# Karyawan WA

Aplikasi PHP Native untuk:
- Register karyawan
- Login karyawan
- CRUD karyawan
- Absensi masuk
- Absensi pulang
- Dashboard admin
- Laporan absensi
- Integrasi WhatsApp Gateway Fonnte

## 1. Lokasi

Ekstrak project menjadi:

C:\xamppano\htdocs\karyawan-wa

Jangan sampai menjadi:

C:\xamppano\htdocs\karyawan-wa\karyawan-wa

## 2. Database

Buka phpMyAdmin lalu import:

database.sql

Database yang dibuat:

db_karyawan

## 3. Jalankan

Pastikan Apache dan MySQL aktif.

Buka:

http://localhost/karyawan-wa/

## 4. Login Admin

Username:
admin

Password:
admin123

## 5. Fonnte

Edit:

api/fonnte.php

Isi:

const FONNTE_TOKEN = 'TOKEN_FONNTE_DI_SINI';
const FONNTE_ADMIN = 'NOMOR_ADMIN_DI_SINI';

Jangan memasukkan token Fonnte ke GitHub.

## 6. Jika menu tidak bisa diklik

Pastikan alamat browser adalah:

http://localhost/karyawan-wa/

Bukan membuka file PHP langsung dari File Explorer.

Semua link project menggunakan BASE_URL:

/karyawan-wa

sehingga link tidak bergantung pada posisi folder file.

## 7. Fitur

Register:
- NIK unik
- Email unik
- Password di-hash
- Notifikasi WhatsApp karyawan
- Notifikasi WhatsApp admin

CRUD:
- Tambah
- Edit
- Hapus
- Konfirmasi hapus
- Notifikasi WhatsApp

Absensi:
- Satu kali masuk per hari
- Tidak bisa pulang sebelum masuk
- Tidak bisa pulang dua kali
- Notifikasi WhatsApp

Admin:
- Dashboard
- Data karyawan
- Data absensi
- Laporan
