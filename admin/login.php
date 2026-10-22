<?php
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../config/app.php';

if (!empty($_SESSION['admin_login'])) {
    redirectTo('admin/index.php');
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === 'admin' && $password === 'admin123') {
        session_regenerate_id(true);
        $_SESSION['admin_login'] = true;
        $_SESSION['admin_username'] = 'admin';
        redirectTo('admin/index.php');
    }

    $error = 'Username atau password admin salah.';
}
?>
<!DOCTYPE html>
<html lang="id"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Login Admin</title>
<style>
body{font-family:Arial;background:#f3e9ed;margin:0}.box{max-width:420px;margin:70px auto;background:#fff;padding:30px;border-radius:18px;box-shadow:0 10px 30px #0001}label{display:block;font-weight:bold;margin:14px 0 6px}input{width:100%;padding:12px;box-sizing:border-box;border:1px solid #ddd;border-radius:8px}button,.btn{display:inline-block;border:0;background:#7b2438;color:#fff;padding:12px 18px;border-radius:8px;text-decoration:none;cursor:pointer;margin-top:18px}.gray{background:#eee;color:#333}.error{background:#ffe5e5;color:#a00000;padding:12px;border-radius:8px}
</style></head>
<body><div class="box"><h1>Login Admin</h1>
<?php if($error): ?><div class="error"><?=htmlspecialchars($error)?></div><?php endif; ?>
<form method="post"><label>Username</label><input name="username" required><label>Password</label><input type="password" name="password" required><button>Login</button><a class="btn gray" href="<?=url('auth/login.php')?>">Login Karyawan</a></form>
<p>Demo: <b>admin</b> / <b>admin123</b></p>
</div></body></html>
