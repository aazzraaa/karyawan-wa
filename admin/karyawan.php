<?php
require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../config/database.php';
requireAdmin();

$pdo = getDatabaseConnection();
$data = $pdo->query('SELECT * FROM karyawan ORDER BY id DESC')->fetchAll();
?>
<!DOCTYPE html>
<html lang="id"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Data Karyawan</title>
<style>
body{font-family:Arial;background:#f6f3f4;margin:0;color:#332126}.nav{background:#641d30;padding:17px}.nav a{color:#fff;text-decoration:none;margin-right:20px}.container{width:96%;max-width:1200px;margin:30px auto}.card{background:#fff;padding:22px;border-radius:15px;overflow:auto}table{width:100%;border-collapse:collapse;min-width:850px}th,td{padding:11px;border-bottom:1px solid #eee;text-align:left}th{background:#f4e9ed}.btn{display:inline-block;background:#7b2438;color:white;padding:9px 13px;border-radius:7px;text-decoration:none}.btn.gray{background:#666}.btn.red{background:#a51d36}
</style></head>
<body><div class="nav">
<a href="<?=url('admin/index.php')?>">Dashboard</a><a href="<?=url('admin/karyawan.php')?>">Karyawan</a><a href="<?=url('admin/absensi.php')?>">Absensi</a><a href="<?=url('admin/laporan.php')?>">Laporan</a><a href="<?=url('admin/logout.php')?>">Logout</a>
</div><div class="container"><h1>Data Karyawan</h1>
<p><a class="btn" href="<?=url('karyawan/tambah.php')?>">+ Tambah Karyawan</a></p>
<div class="card"><table><tr><th>No</th><th>NIK</th><th>Nama</th><th>Jabatan</th><th>No HP</th><th>Email</th><th>Aksi</th></tr>
<?php foreach($data as $i=>$row): ?>
<tr><td><?=$i+1?></td><td><?=htmlspecialchars($row['nik'])?></td><td><?=htmlspecialchars($row['nama'])?></td><td><?=htmlspecialchars($row['jabatan'])?></td><td><?=htmlspecialchars($row['no_hp'])?></td><td><?=htmlspecialchars($row['email'])?></td><td>
<a class="btn" href="<?=url('karyawan/edit.php')?>?id=<?=$row['id']?>">Edit</a>
<a class="btn red" href="<?=url('karyawan/hapus.php')?>?id=<?=$row['id']?>" onclick="return confirm('Yakin ingin menghapus karyawan ini?')">Hapus</a>
</td></tr>
<?php endforeach; ?>
<?php if(!$data): ?><tr><td colspan="7">Belum ada data karyawan.</td></tr><?php endif; ?>
</table></div></div></body></html>
