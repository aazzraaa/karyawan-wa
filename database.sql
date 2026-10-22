CREATE DATABASE IF NOT EXISTS db_karyawan
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE db_karyawan;

CREATE TABLE IF NOT EXISTS karyawan (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nik VARCHAR(50) NOT NULL UNIQUE,
    nama VARCHAR(100) NOT NULL,
    jabatan VARCHAR(100) NOT NULL,
    no_hp VARCHAR(20) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS absensi (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_karyawan INT NOT NULL,
    tanggal DATE NOT NULL,
    jam_masuk TIME NULL,
    jam_pulang TIME NULL,
    status VARCHAR(30) NOT NULL DEFAULT 'HADIR',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_absensi_karyawan
        FOREIGN KEY (id_karyawan) REFERENCES karyawan(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    UNIQUE KEY unik_absensi_harian (id_karyawan, tanggal)
);
