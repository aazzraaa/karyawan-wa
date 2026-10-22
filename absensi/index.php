<?php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/auth.php';

requireLogin();

$pdo = getDatabaseConnection();

$id = (int) $_SESSION['karyawan_id'];
$today = date('Y-m-d');


/*
|--------------------------------------------------------------------------
| AMBIL PESAN SESSION
|--------------------------------------------------------------------------
*/

$success = $_SESSION['success'] ?? null;
$error = $_SESSION['error'] ?? null;


/*
|--------------------------------------------------------------------------
| HAPUS PESAN DARI SESSION
|--------------------------------------------------------------------------
*/

unset($_SESSION['success']);
unset($_SESSION['error']);


/*
|--------------------------------------------------------------------------
| AMBIL DATA ABSENSI HARI INI
|--------------------------------------------------------------------------
*/

$stmt = $pdo->prepare(
    'SELECT * 
     FROM absensi 
     WHERE id_karyawan = ? 
     AND tanggal = ?'
);

$stmt->execute([
    $id,
    $today
]);

$absen = $stmt->fetch();

?>
<!DOCTYPE html>
<html lang="id">

<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">

<title>Absensi</title>

<style>

* {
    box-sizing: border-box;
}

body {
    font-family: Arial, sans-serif;
    background: #f6f3f4;
    margin: 0;
    color: #302128;
}

nav {
    background: #7b2438;
    padding: 16px;
}

nav a {
    color: white;
    text-decoration: none;
    margin-right: 18px;
}

.box {
    max-width: 700px;
    margin: 35px auto;
    background: #fff;
    padding: 28px;
    border-radius: 16px;
    box-shadow: 0 8px 25px rgba(0,0,0,.07);
}


/*
|--------------------------------------------------------------------------
| PESAN SUKSES
|--------------------------------------------------------------------------
*/

.alert-success {
    background: #e8f7ee;
    color: #176b3a;
    border: 1px solid #b8e5c8;
    padding: 14px 16px;
    border-radius: 10px;
    margin-bottom: 20px;
}


/*
|--------------------------------------------------------------------------
| PESAN ERROR
|--------------------------------------------------------------------------
*/

.alert-error {
    background: #fdecec;
    color: #a12626;
    border: 1px solid #f1bcbc;
    padding: 14px 16px;
    border-radius: 10px;
    margin-bottom: 20px;
}


.info {
    background: #f7edf0;
    padding: 15px;
    border-radius: 10px;
    margin: 15px 0;
}

.btn {
    display: inline-block;
    background: #7b2438;
    color: #fff;
    padding: 12px 18px;
    border-radius: 8px;
    text-decoration: none;
    margin: 5px 5px 0 0;
}

.btn:hover {
    background: #641d2e;
}

.btn.disabled {
    background: #aaa;
    pointer-events: none;
}

</style>

</head>

<body>


<nav>

<a href="<?= url('index.php') ?>">
    Beranda
</a>

<a href="<?= url('karyawan/index.php') ?>">
    Data Saya
</a>

<a href="<?= url('absensi/index.php') ?>">
    Absensi
</a>

<a href="<?= url('auth/logout.php') ?>">
    Logout
</a>

</nav>


<div class="box">

<h1>Absensi Hari Ini</h1>


<?php if ($success): ?>

<div class="alert-success">
    <?= htmlspecialchars($success) ?>
</div>

<?php endif; ?>


<?php if ($error): ?>

<div class="alert-error">
    <?= htmlspecialchars($error) ?>
</div>

<?php endif; ?>


<div class="info">

<b>Nama:</b>
<?= htmlspecialchars($_SESSION['karyawan_nama']) ?>

<br>

<b>NIK:</b>
<?= htmlspecialchars($_SESSION['karyawan_nik']) ?>

<br>

<b>Tanggal:</b>
<?= htmlspecialchars($today) ?>

</div>


<?php if (!$absen): ?>

<p>
    Belum melakukan absensi masuk hari ini.
</p>

<a
    class="btn"
    href="<?= url('absensi/masuk.php') ?>"
>
    Absen Masuk
</a>


<?php else: ?>


<p>
    <b>Status:</b>
    <?= htmlspecialchars($absen['status']) ?>
</p>


<p>
    <b>Jam Masuk:</b>
    <?= htmlspecialchars($absen['jam_masuk'] ?? '-') ?>
</p>


<p>
    <b>Jam Pulang:</b>
    <?= htmlspecialchars($absen['jam_pulang'] ?? '-') ?>
</p>


<?php if (empty($absen['jam_pulang'])): ?>

<a
    class="btn"
    href="<?= url('absensi/pulang.php') ?>"
>
    Absen Pulang
</a>


<?php else: ?>

<p>
    Absensi hari ini sudah lengkap.
</p>

<?php endif; ?>


<?php endif; ?>


<a
    class="btn"
    href="<?= url('karyawan/index.php') ?>"
>
    Kembali
</a>


</div>

</body>

</html>