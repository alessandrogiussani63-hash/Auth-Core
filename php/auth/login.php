<?php
/**
 * Minimal login form. Validates credentials against users.json
 * and stores a session user object: ['username'=>..., 'role'=>...]
 */
session_start();
$next = $_GET['next'] ?? '/';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $u = trim($_POST['username'] ?? '');
    $p = (string)($_POST['password'] ?? '');
    $users = json_decode(@file_get_contents(__DIR__ . '/users.json'), true);
    foreach ((array)($users['users'] ?? []) as $user) {
        if ($user['username'] === $u && password_verify($p, $user['password_hash'])) {
            $_SESSION['user'] = [
                'username' => $u,
                'role'     => $user['role'] ?? 'admin'
            ];
            header('Location: ' . ($next ?: '/'));
            exit;
        }
    }
    $error = 'Invalid credentials';
}
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Login</title>
  <style>
    body{font-family:sans-serif;max-width:420px;margin:6rem auto}
    label{display:block;margin:.5rem 0}
    input{width:100%;padding:.6rem}
    button{padding:.6rem 1rem}
    .error{color:#b00;margin:.5rem 0}
  </style>
</head>
<body>
<h1>Login</h1>
<?php if ($error): ?><div class="error"><?=htmlspecialchars($error)?></div><?php endif; ?>
<form method="post" action="?<?=http_build_query(['next'=>$next])?>">
  <label>Username<input name="username" required></label>
  <label>Password<input type="password" name="password" required></label>
  <button type="submit">Sign in</button>
</form>
</body>
</html>
