<?php
require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../api/fonnte.php';
requireAdmin();

$pdo = getDatabaseConnection();
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nik = trim($_POST['nik'] ?? '');
    $nama = trim($_POST['nama'] ?? '');
    $jabatan = trim($_POST['jabatan'] ?? '');
    $no_hp = trim($_POST['no_hp'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($nik === '' || $nama === '' || $jabatan === '' || $no_hp === '' || $email === '' || $password === '') {
        $error = 'Semua data wajib diisi.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Email tidak valid.';
    } else {
        $check = $pdo->prepare('SELECT id FROM karyawan WHERE nik = ? OR email = ?');
        $check->execute([$nik, $email]);

        if ($check->fetch()) {
            $error = 'NIK atau email sudah digunakan.';
        } else {
            $stmt = $pdo->prepare(
                'INSERT INTO karyawan (nik,nama,jabatan,no_hp,email,password) VALUES (?,?,?,?,?,?)'
            );
            $stmt->execute([
                $nik, $nama, $jabatan, $no_hp, $email,
                password_hash($password, PASSWORD_DEFAULT)
            ]);

            sendToEmployeeAndAdmin(
                $no_hp,
                "Halo {$nama}, akun karyawan kamu berhasil ditambahkan oleh admin.\nNIK: {$nik}\nJabatan: {$jabatan}",
                "Admin menambahkan karyawan baru.\nNama: {$nama}\nNIK: {$nik}\nJabatan: {$jabatan}"
            );

            redirectTo('admin/karyawan.php');
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Tambah Karyawan</title>
<style>
body{font-family:Arial;background:#f6f3f4;margin:0}.box{max-width:650px;margin:35px auto;background:#fff;padding:28px;border-radius:16px}.field{margin:12px 0}.field label{display:block;font-weight:bold;margin-bottom:5px}.field input{width:100%;padding:11px;box-sizing:border-box;border:1px solid #ddd;border-radius:8px}button,.btn{border:0;background:#7b2438;color:#fff;padding:11px 16px;border-radius:8px;text-decoration:none;cursor:pointer}.error{background:#ffe5e5;padding:10px;margin-bottom:15px}
</style></head>
<body><div class="box"><h1>Tambah Karyawan</h1>
<?php if($error): ?><div class="error"><?=htmlspecialchars($error)?></div><?php endif; ?>
<form method="post">
<?php foreach(['nik'=>'NIK','nama'=>'Nama','jabatan'=>'Jabatan','no_hp'=>'No. HP','email'=>'Email'] as $name=>$label): ?>
<div class="field"><label><?= $label ?></label><input type="<?= $name==='email'?'email':'text' ?>" name="<?= $name ?>" value="<?=htmlspecialchars($_POST[$name]??'')?>" required></div>
<?php endforeach; ?>
<div class="field"><label>Password</label><input type="password" name="password" required></div>
<button type="submit">Simpan</button>
<a class="btn" href="<?=url('admin/karyawan.php')?>">Kembali</a>
</form></div></body></html>
