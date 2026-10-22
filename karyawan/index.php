<?php
require_once __DIR__ . '/../config/auth.php';
requireLogin();
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Data Karyawan</title>
<style>
body{margin:0;font-family:Arial;background:#f6f3f4;color:#332126}
nav{background:#7b2438;padding:16px}
nav a{color:#fff;text-decoration:none;margin-right:18px}
.container{width:min(900px,92%);margin:35px auto}
.card{background:#fff;border-radius:16px;padding:25px;box-shadow:0 8px 25px rgba(0,0,0,.07)}
table{width:100%;border-collapse:collapse}
td{padding:12px;border-bottom:1px solid #eee}
.label{font-weight:bold;width:180px}
.btn{display:inline-block;background:#7b2438;color:#fff;padding:11px 16px;border-radius:8px;text-decoration:none;margin:5px 5px 0 0}
.btn.gray{background:#555}
</style>
</head>
<body>
<nav>
<a href="<?= url('index.php') ?>">Beranda</a>
<a href="<?= url('karyawan/index.php') ?>">Data Saya</a>
<a href="<?= url('absensi/index.php') ?>">Absensi</a>
<a href="<?= url('auth/logout.php') ?>">Logout</a>
</nav>

<div class="container">
<div class="card">
<h1>Data Karyawan</h1>
<table>
<tr><td class="label">NIK</td><td><?= htmlspecialchars($_SESSION['karyawan_nik']) ?></td></tr>
<tr><td class="label">Nama</td><td><?= htmlspecialchars($_SESSION['karyawan_nama']) ?></td></tr>
<tr><td class="label">Jabatan</td><td><?= htmlspecialchars($_SESSION['karyawan_jabatan']) ?></td></tr>
<tr><td class="label">No. HP</td><td><?= htmlspecialchars($_SESSION['karyawan_no_hp']) ?></td></tr>
<tr><td class="label">Email</td><td><?= htmlspecialchars($_SESSION['karyawan_email']) ?></td></tr>
</table>
<a class="btn" href="<?= url('absensi/index.php') ?>">Buka Absensi</a>
<a class="btn gray" href="<?= url('index.php') ?>">Kembali</a>
</div>
</div>
</body>
</html>
