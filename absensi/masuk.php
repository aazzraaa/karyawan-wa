<?php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../api/fonnte.php';

requireLogin();

$pdo = getDatabaseConnection();

$id = (int) $_SESSION['karyawan_id'];
$nama = $_SESSION['karyawan_nama'];
$nik = $_SESSION['karyawan_nik'];
$phone = $_SESSION['karyawan_no_hp'];

$today = date('Y-m-d');
$time = date('H:i:s');


/*
|--------------------------------------------------------------------------
| CEK ABSENSI HARI INI
|--------------------------------------------------------------------------
*/

$check = $pdo->prepare(
    'SELECT id 
     FROM absensi 
     WHERE id_karyawan = ? 
     AND tanggal = ?'
);

$check->execute([
    $id,
    $today
]);

if ($check->fetch()) {
    $_SESSION['error'] = 'Kamu sudah melakukan absensi masuk hari ini.';
    redirectTo('absensi/index.php');
}


/*
|--------------------------------------------------------------------------
| SIMPAN ABSENSI
|--------------------------------------------------------------------------
*/

$stmt = $pdo->prepare(
    'INSERT INTO absensi 
    (id_karyawan, tanggal, jam_masuk, status) 
    VALUES (?, ?, ?, "HADIR")'
);

$stmt->execute([
    $id,
    $today,
    $time
]);


/*
|--------------------------------------------------------------------------
| KIRIM NOTIFIKASI WHATSAPP
|--------------------------------------------------------------------------
*/

$message = "Absensi masuk berhasil.\n\n"
    . "Nama: {$nama}\n"
    . "NIK: {$nik}\n"
    . "Tanggal: {$today}\n"
    . "Jam masuk: {$time}\n"
    . "Status: HADIR";


$waResult = sendWhatsApp(
    $phone,
    $message
);


/*
|--------------------------------------------------------------------------
| SIMPAN HASIL WHATSAPP KE SESSION
|--------------------------------------------------------------------------
*/

if ($waResult['success']) {

    $_SESSION['success'] =
        'Absensi masuk berhasil dan WhatsApp berhasil dikirim.';

} else {

    $_SESSION['error'] =
        'Absensi berhasil disimpan, tetapi WhatsApp gagal dikirim. '
        . 'Alasan: '
        . ($waResult['message'] ?? 'Tidak diketahui.');
}


/*
|--------------------------------------------------------------------------
| KEMBALI KE HALAMAN ABSENSI
|--------------------------------------------------------------------------
*/

redirectTo('absensi/index.php');