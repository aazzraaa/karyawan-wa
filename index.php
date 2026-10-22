<?php
require_once __DIR__ . '/config/session.php';
require_once __DIR__ . '/config/app.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Sistem Karyawan</title>
<style>
*{box-sizing:border-box}
body{margin:0;font-family:Arial,sans-serif;background:#f7f1f3;color:#332126}
nav{background:#641d30;padding:18px}
nav a{color:#fff;text-decoration:none;margin-right:20px;font-weight:bold}
.container{width:min(1000px,92%);margin:55px auto}
.hero{background:#fff;padding:45px;border-radius:20px;box-shadow:0 10px 35px rgba(0,0,0,.08)}
h1{font-size:38px;color:#7b2438;margin-top:0}
.buttons{display:flex;gap:12px;flex-wrap:wrap;margin-top:25px}
.btn{display:inline-block;background:#7b2438;color:#fff;text-decoration:none;padding:13px 20px;border-radius:9px}
.btn.secondary{background:#eee;color:#333}
</style>
</head>
<body>
<nav>
<a href="<?=url('index.php')?>">Beranda</a>
<?php if (!empty($_SESSION['login'])): ?>
<a href="<?=url('karyawan/index.php')?>">Data Saya</a>
<a href="<?=url('absensi/index.php')?>">Absensi</a>
<a href="<?=url('auth/logout.php')?>">Logout</a>
<?php else: ?>
<a href="<?=url('auth/login.php')?>">Login Karyawan</a>
<a href="<?=url('auth/register.php')?>">Register</a>
<a href="<?=url('admin/login.php')?>">Login Admin</a>
<?php endif; ?>
</nav>

<div class="container">
<div class="hero">
<h1>Sistem Manajemen Karyawan</h1>
<p>Aplikasi register karyawan, CRUD data karyawan, absensi masuk dan pulang, serta notifikasi WhatsApp melalui Fonnte.</p>

<div class="buttons">
<?php if (!empty($_SESSION['login'])): ?>
<a class="btn" href="<?=url('karyawan/index.php')?>">Data Saya</a>
<a class="btn" href="<?=url('absensi/index.php')?>">Absensi</a>
<?php else: ?>
<a class="btn" href="<?=url('auth/register.php')?>">Register Karyawan</a>
<a class="btn" href="<?=url('auth/login.php')?>">Login Karyawan</a>
<a class="btn secondary" href="<?=url('admin/login.php')?>">Login Admin</a>
<?php endif; ?>
</div>
</div>
</div>
</body>
</html>
