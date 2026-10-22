<?php
require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../api/fonnte.php';
requireAdmin();

$pdo = getDatabaseConnection();
$id = (int)($_GET['id'] ?? 0);

$stmt = $pdo->prepare('SELECT * FROM karyawan WHERE id = ?');
$stmt->execute([$id]);
$data = $stmt->fetch();

if (!$data) {
    die('Data karyawan tidak ditemukan.');
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nik = trim($_POST['nik'] ?? '');
    $nama = trim($_POST['nama'] ?? '');
    $jabatan = trim($_POST['jabatan'] ?? '');
    $no_hp = trim($_POST['no_hp'] ?? '');
    $email = trim($_POST['email'] ?? '');

    $check = $pdo->prepare('SELECT id FROM karyawan WHERE (nik = ? OR email = ?) AND id != ?');
    $check->execute([$nik, $email, $id]);

    if ($nik === '' || $nama === '' || $jabatan === '' || $no_hp === '' || $email === '') {
        $error = 'Semua data wajib diisi.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Email tidak valid.';
    } elseif ($check->fetch()) {
        $error = 'NIK atau email sudah digunakan karyawan lain.';
    } else {
        $stmt = $pdo->prepare(
            'UPDATE karyawan SET nik=?, nama=?, jabatan=?, no_hp=?, email=? WHERE id=?'
        );
        $stmt->execute([$nik,$nama,$jabatan,$no_hp,$email,$id]);

        sendWhatsApp(
            $no_hp,
            "Halo {$nama},\n\nData akun karyawan kamu telah diperbarui oleh admin.\nNIK: {$nik}\nJabatan: {$jabatan}\nEmail: {$email}"
        );

        redirectTo('admin/karyawan.php');
    }
}
?>
<!DOCTYPE html>
<html lang="id"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Edit Karyawan</title>
<style>
body{font-family:Arial;background:#f6f3f4;margin:0}.box{max-width:650px;margin:35px auto;background:#fff;padding:28px;border-radius:16px}.field{margin:12px 0}.field label{display:block;font-weight:bold;margin-bottom:5px}.field input{width:100%;padding:11px;box-sizing:border-box;border:1px solid #ddd;border-radius:8px}button,.btn{border:0;background:#7b2438;color:#fff;padding:11px 16px;border-radius:8px;text-decoration:none;cursor:pointer}.error{background:#ffe5e5;padding:10px}
</style></head>
<body><div class="box"><h1>Edit Karyawan</h1>
<?php if($error): ?><div class="error"><?=htmlspecialchars($error)?></div><?php endif; ?>
<form method="post">
<?php foreach(['nik'=>'NIK','nama'=>'Nama','jabatan'=>'Jabatan','no_hp'=>'No. HP','email'=>'Email'] as $name=>$label): ?>
<div class="field"><label><?= $label ?></label><input type="<?= $name==='email'?'email':'text' ?>" name="<?= $name ?>" value="<?=htmlspecialchars($_POST[$name]??$data[$name])?>" required></div>
<?php endforeach; ?>
<button type="submit">Simpan Perubahan</button>
<a class="btn" href="<?=url('admin/karyawan.php')?>">Kembali</a>
</form></div></body></html>
