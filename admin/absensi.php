<?php
require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../config/database.php';
requireAdmin();

$pdo = getDatabaseConnection();

$data = $pdo->query(
    'SELECT a.*, k.nama, k.nik
     FROM absensi a
     INNER JOIN karyawan k ON k.id = a.id_karyawan
     ORDER BY a.tanggal DESC, a.jam_masuk DESC'
)->fetchAll();
?>
<!DOCTYPE html>
<html lang="id"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Data Absensi</title>
<style>
body{font-family:Arial;background:#f6f3f4;margin:0}.nav{background:#641d30;padding:17px}.nav a{color:#fff;text-decoration:none;margin-right:20px}.container{width:96%;max-width:1100px;margin:30px auto}.card{background:#fff;padding:20px;border-radius:15px;overflow:auto}table{width:100%;border-collapse:collapse;min-width:700px}th,td{padding:11px;border-bottom:1px solid #eee;text-align:left}th{background:#f4e9ed}.btn{display:inline-block;background:#7b2438;color:white;padding:10px 15px;border-radius:8px;text-decoration:none}
</style></head><body>
<div class="nav"><a href="<?=url('admin/index.php')?>">Dashboard</a><a href="<?=url('admin/karyawan.php')?>">Karyawan</a><a href="<?=url('admin/absensi.php')?>">Absensi</a><a href="<?=url('admin/laporan.php')?>">Laporan</a><a href="<?=url('admin/logout.php')?>">Logout</a></div>
<div class="container"><h1>Data Absensi</h1><div class="card"><table>
<tr><th>No</th><th>Nama</th><th>NIK</th><th>Tanggal</th><th>Masuk</th><th>Pulang</th><th>Status</th></tr>
<?php foreach($data as $i=>$row): ?><tr><td><?=$i+1?></td><td><?=htmlspecialchars($row['nama'])?></td><td><?=htmlspecialchars($row['nik'])?></td><td><?=htmlspecialchars($row['tanggal'])?></td><td><?=htmlspecialchars($row['jam_masuk']??'-')?></td><td><?=htmlspecialchars($row['jam_pulang']??'-')?></td><td><?=htmlspecialchars($row['status'])?></td></tr><?php endforeach; ?>
<?php if(!$data): ?><tr><td colspan="7">Belum ada data absensi.</td></tr><?php endif; ?>
</table></div></div></body></html>
