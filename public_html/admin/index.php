<?php
require __DIR__ . '/inc/auth.php';
if (current_user()) redirect('/admin/dashboard.php');

$err = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    // simple throttle
    $_SESSION['tries'] = ($_SESSION['tries'] ?? 0);
    if ($_SESSION['tries'] >= 6 && (time() - ($_SESSION['lock'] ?? 0)) < 300) {
        $err = 'Too many attempts. Please wait 5 minutes.';
    } else {
        $u = trim($_POST['username'] ?? '');
        $p = $_POST['password'] ?? '';
        if (attempt_login($u, $p)) {
            $_SESSION['tries'] = 0;
            redirect('/admin/dashboard.php');
        }
        $_SESSION['tries']++; $_SESSION['lock'] = time();
        $err = 'Invalid username or password.';
    }
}
?><!DOCTYPE html><html lang="en"><head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Login · Bright Care Admin</title>
<link rel="icon" href="/assets/img/favicon.png" type="image/png">
<link rel="stylesheet" href="/admin/assets/admin.css?v=1">
</head><body>
<div class="login">
  <form class="login__box" method="post" autocomplete="off">
    <div class="login__logo">B</div>
    <h1>Bright Care Admin</h1>
    <p class="sub">Sign in to manage your website</p>
    <?php if ($err): ?><div class="alert alert--err"><?= e($err) ?></div><?php endif; ?>
    <?= csrf_field() ?>
    <div class="fld"><label>Username or Email</label><input type="text" name="username" required autofocus></div>
    <div class="fld"><label>Password</label><input type="password" name="password" required></div>
    <button class="btn" style="width:100%;justify-content:center;padding:.8em">Sign In</button>
  </form>
</div>
</body></html>
