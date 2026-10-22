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
    redirectTo('admin/karyawan.php');
}

sendWhatsApp(
    $data['no_hp'],
    "Halo {$data['nama']},\n\nAkun karyawan kamu telah dihapus dari sistem oleh admin.\nNIK: {$data['nik']}"
);

$delete = $pdo->prepare('DELETE FROM karyawan WHERE id = ?');
$delete->execute([$id]);

redirectTo('admin/karyawan.php');
