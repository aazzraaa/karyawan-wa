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
    'SELECT *
     FROM absensi
     WHERE id_karyawan = ?
     AND tanggal = ?'
);

$check->execute([
    $id,
    $today
]);

$absen = $check->fetch();


/*
|--------------------------------------------------------------------------
| VALIDASI ABSENSI
|--------------------------------------------------------------------------
| Tidak boleh pulang jika:
| 1. Belum ada absensi
| 2. Belum absen masuk
| 3. Sudah absen pulang
|--------------------------------------------------------------------------
*/

if (
    !$absen ||
    empty($absen['jam_masuk']) ||
    !empty($absen['jam_pulang'])
) {
    $_SESSION['error'] = 'Absensi pulang tidak dapat dilakukan.';
    redirectTo('absensi/index.php');
}


/*
|--------------------------------------------------------------------------
| UPDATE JAM PULANG
|--------------------------------------------------------------------------
*/

$stmt = $pdo->prepare(
    'UPDATE absensi
     SET jam_pulang = ?
     WHERE id = ?'
);

$stmt->execute([
    $time,
    $absen['id']
]);


/*
|--------------------------------------------------------------------------
| PESAN WHATSAPP
|--------------------------------------------------------------------------
*/

$message =
    "Absensi pulang berhasil.\n\n" .
    "Nama: {$nama}\n" .
    "NIK: {$nik}\n" .
    "Tanggal: {$today}\n" .
    "Jam pulang: {$time}";


/*
|--------------------------------------------------------------------------
| KIRIM WHATSAPP
|--------------------------------------------------------------------------
*/

$waResult = sendWhatsApp(
    $phone,
    $message
);


/*
|--------------------------------------------------------------------------
| SIMPAN HASIL WHATSAPP
|--------------------------------------------------------------------------
*/

if ($waResult['success']) {

    $_SESSION['success'] =
        'Absensi pulang berhasil dan WhatsApp berhasil dikirim.';

} else {

    $_SESSION['error'] =
        'Absensi pulang berhasil disimpan, tetapi WhatsApp gagal dikirim. ' .
        'Alasan: ' .
        ($waResult['message'] ?? 'Tidak diketahui.');
}


/*
|--------------------------------------------------------------------------
| KEMBALI KE HALAMAN ABSENSI
|--------------------------------------------------------------------------
*/

redirectTo('absensi/index.php');