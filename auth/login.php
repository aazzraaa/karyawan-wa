<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../config/app.php';

if (!empty($_SESSION['login'])) {
    redirectTo('index.php');
}

$pdo = getDatabaseConnection();
$error = '';
$success = $_SESSION['success'] ?? '';
unset($_SESSION['success']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = $pdo->prepare('SELECT * FROM karyawan WHERE email = ? LIMIT 1');
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        session_regenerate_id(true);

        $_SESSION['login'] = true;
        $_SESSION['karyawan_id'] = $user['id'];
        $_SESSION['karyawan_nik'] = $user['nik'];
        $_SESSION['karyawan_nama'] = $user['nama'];
        $_SESSION['karyawan_jabatan'] = $user['jabatan'];
        $_SESSION['karyawan_no_hp'] = $user['no_hp'];
        $_SESSION['karyawan_email'] = $user['email'];

        redirectTo('index.php');
    }

    $error = 'Email atau password salah.';
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login Karyawan</title>
<style>
*{box-sizing:border-box}
body{margin:0;font-family:Arial;background:#f8f1f4;color:#3d2028}
.container{width:min(440px,92%);margin:70px auto}
.card{background:#fff;padding:30px;border-radius:18px;box-shadow:0 10px 30px rgba(0,0,0,.08)}
h1{color:#7b2438}
label{display:block;margin:14px 0 6px;font-weight:bold}
input{width:100%;padding:12px;border:1px solid #ddd;border-radius:9px}
button,.btn{display:inline-block;border:0;background:#7b2438;color:white;padding:12px 18px;border-radius:9px;text-decoration:none;cursor:pointer;margin-top:18px}
.btn.secondary{background:#eee;color:#333}
.error{background:#ffe5e5;color:#a00000;padding:12px;border-radius:8px;margin-bottom:15px}
.success{background:#e4f8e8;color:#166534;padding:12px;border-radius:8px;margin-bottom:15px}
</style>
</head>
<body>
<div class="container">
<div class="card">
<h1>Login Karyawan</h1>

<?php if ($error): ?><div class="error"><?= htmlspecialchars($error) ?></div><?php endif; ?>
<?php if ($success): ?><div class="success"><?= htmlspecialchars($success) ?></div><?php endif; ?>

<form method="post">
<label>Email</label>
<input type="email" name="email" required>

<label>Password</label>
<input type="password" name="password" required>

<button type="submit">Login</button>
<a class="btn secondary" href="<?= url('auth/register.php') ?>">Register</a>
<a class="btn secondary" href="<?= url('admin/login.php') ?>">Login Admin</a>
</form>
</div>
</div>
</body>
</html>
