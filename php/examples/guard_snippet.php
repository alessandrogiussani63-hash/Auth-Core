<?php
/**
 * Example snippet to protect a page.
 * Place this at the top of any sensitive PHP file.
 */
require_once __DIR__ . '/../auth/auth.php';
auth_require_role('admin');
$csrf = auth_csrf_token(); // include this value as hidden input in POST forms
?>
<!-- Your protected content below -->
<h1>Protected Area</h1>
<p>Welcome, <?=htmlspecialchars(auth_user()['username'] ?? 'unknown')?>.</p>
<form method="post">
  <input type="hidden" name="csrf" value="<?=htmlspecialchars($csrf)?>">
  <button>Do sensitive action</button>
</form>
