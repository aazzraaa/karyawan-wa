<?php
require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../config/database.php';
requireAdmin();

$pdo = getDatabaseConnection();
$today = date('Y-m-d');

$totalKaryawan = (int)$pdo->query('SELECT COUNT(*) FROM karyawan')->fetchColumn();

$stmt = $pdo->prepare('SELECT COUNT(*) FROM absensi WHERE tanggal = ?');
$stmt->execute([$today]);
$sudahAbsen = (int)$stmt->fetchColumn();

$belumAbsen = max(0, $totalKaryawan - $sudahAbsen);

$stmt = $pdo->prepare('SELECT COUNT(*) FROM absensi WHERE tanggal = ? AND jam_pulang IS NOT NULL');
$stmt->execute([$today]);
$sudahPulang = (int)$stmt->fetchColumn();
?>
<!DOCTYPE html>
<html lang="id"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Dashboard Admin</title>
<style>
*{box-sizing:border-box}body{margin:0;font-family:Arial;background:#f6f3f4;color:#332126}.nav{background:#641d30;padding:17px}.nav a{color:white;text-decoration:none;margin-right:20px}.container{width:min(1100px,94%);margin:30px auto}.cards{display:grid;grid-template-columns:repeat(4,1fr);gap:15px}.card{background:white;border-radius:15px;padding:22px;box-shadow:0 8px 22px #0000000d}.number{font-size:32px;font-weight:bold;color:#7b2438}.menu{margin-top:25px}.btn{display:inline-block;background:#7b2438;color:white;padding:12px 18px;border-radius:8px;text-decoration:none;margin:5px 5px 5px 0}
@media(max-width:800px){.cards{grid-template-columns:1fr 1fr}}@media(max-width:500px){.cards{grid-template-columns:1fr}}
</style></head>
<body>
<div class="nav">
<a href="<?=url('admin/index.php')?>">Dashboard</a>
<a href="<?=url('admin/karyawan.php')?>">Karyawan</a>
<a href="<?=url('admin/absensi.php')?>">Absensi</a>
<a href="<?=url('admin/laporan.php')?>">Laporan</a>
<a href="<?=url('admin/logout.php')?>">Logout</a>
</div>
<div class="container">
<h1>Dashboard Admin</h1><p>Selamat datang, <?=htmlspecialchars($_SESSION['admin_username'])?>.</p>
<div class="cards">
<div class="card"><div>Total Karyawan</div><div class="number"><?=$totalKaryawan?></div></div>
<div class="card"><div>Sudah Absen</div><div class="number"><?=$sudahAbsen?></div></div>
<div class="card"><div>Belum Absen</div><div class="number"><?=$belumAbsen?></div></div>
<div class="card"><div>Sudah Pulang</div><div class="number"><?=$sudahPulang?></div></div>
</div>
<div class="menu">
<a class="btn" href="<?=url('admin/karyawan.php')?>">Kelola Karyawan</a>
<a class="btn" href="<?=url('admin/absensi.php')?>">Lihat Absensi</a>
<a class="btn" href="<?=url('admin/laporan.php')?>">Lihat Laporan</a>
</div>
</div>
</body></html>
