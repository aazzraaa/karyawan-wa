<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../api/fonnte.php';

if (!empty($_SESSION['login'])) {
    redirectTo('index.php');
}

$pdo = getDatabaseConnection();
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nik = trim($_POST['nik'] ?? '');
    $nama = trim($_POST['nama'] ?? '');
    $jabatan = trim($_POST['jabatan'] ?? '');
    $no_hp = trim($_POST['no_hp'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $konfirmasi = $_POST['konfirmasi'] ?? '';

    if ($nik === '' || $nama === '' || $jabatan === '' || $no_hp === '' || $email === '' || $password === '') {
        $error = 'Semua data wajib diisi.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Format email tidak valid.';
    } elseif ($password !== $konfirmasi) {
        $error = 'Konfirmasi password tidak sama.';
    } elseif (strlen($password) < 6) {
        $error = 'Password minimal 6 karakter.';
    } else {
        $stmt = $pdo->prepare('SELECT id FROM karyawan WHERE nik = ? OR email = ?');
        $stmt->execute([$nik, $email]);

        if ($stmt->fetch()) {
            $error = 'NIK atau email sudah terdaftar.';
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $pdo->prepare(
                'INSERT INTO karyawan (nik, nama, jabatan, no_hp, email, password)
                 VALUES (?, ?, ?, ?, ?, ?)'
            );
            $stmt->execute([$nik, $nama, $jabatan, $no_hp, $email, $hash]);

            $messageEmployee =
                "Halo {$nama},\n\n" .
                "Registrasi akun karyawan berhasil.\n\n" .
                "NIK: {$nik}\n" .
                "Jabatan: {$jabatan}\n" .
                "Email: {$email}\n\n" .
                "Silakan login ke sistem Karyawan.";

            $messageAdmin =
                "Registrasi karyawan baru berhasil.\n\n" .
                "Nama: {$nama}\n" .
                "NIK: {$nik}\n" .
                "Jabatan: {$jabatan}\n" .
                "No. HP: {$no_hp}\n" .
                "Email: {$email}";

            sendToEmployeeAndAdmin($no_hp, $messageEmployee, $messageAdmin);

            $_SESSION['success'] = 'Registrasi berhasil. Silakan login.';
            redirectTo('auth/login.php');
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Register Karyawan</title>
<style>
*{box-sizing:border-box}
body{margin:0;font-family:Arial,sans-serif;background:#f8f1f4;color:#3d2028}
.container{width:min(520px,92%);margin:40px auto}
.card{background:#fff;border-radius:18px;padding:30px;box-shadow:0 10px 30px rgba(0,0,0,.08)}
h1{margin-top:0;color:#7b2438}
label{display:block;margin:14px 0 6px;font-weight:bold}
input{width:100%;padding:12px;border:1px solid #ddd;border-radius:9px}
button,.btn{display:inline-block;border:0;background:#7b2438;color:#fff;padding:12px 18px;border-radius:9px;text-decoration:none;cursor:pointer;margin-top:18px}
.btn.secondary{background:#eee;color:#333}
.error{background:#ffe5e5;color:#a00000;padding:12px;border-radius:8px;margin-bottom:15px}
.actions{display:flex;gap:10px;flex-wrap:wrap}
</style>
</head>
<body>
<div class="container">
<div class="card">
<h1>Register Karyawan</h1>

<?php if ($error): ?>
<div class="error"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<form method="post">
<label>NIK</label>
<input type="text" name="nik" value="<?= htmlspecialchars($_POST['nik'] ?? '') ?>" required>

<label>Nama</label>
<input type="text" name="nama" value="<?= htmlspecialchars($_POST['nama'] ?? '') ?>" required>

<label>Jabatan</label>
<input type="text" name="jabatan" value="<?= htmlspecialchars($_POST['jabatan'] ?? '') ?>" required>

<label>No. HP WhatsApp</label>
<input type="text" name="no_hp" value="<?= htmlspecialchars($_POST['no_hp'] ?? '') ?>" placeholder="08xxxxxxxxxx" required>

<label>Email</label>
<input type="email" name="email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>

<label>Password</label>
<input type="password" name="password" required>

<label>Konfirmasi Password</label>
<input type="password" name="konfirmasi" required>

<div class="actions">
<button type="submit">Daftar</button>
<a class="btn secondary" href="<?= url('auth/login.php') ?>">Sudah punya akun</a>
</div>
</form>
</div>
</div>
</body>
</html>
