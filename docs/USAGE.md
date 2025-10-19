# Usage & Integration

## 1) Login + protect pages (file‑based store)

**Protect a page:**
```php
<?php
require_once __DIR__ . '/../php/auth/auth.php';
auth_require_role('admin');           // only admins allowed
$csrf = auth_csrf_token();            // include in POST forms
?>
```

**Login flow:** visit `/php/auth/login.php`, authenticate, then you’ll be redirected to the `next` URL.

**Logout:** `/php/auth/logout.php`

**Add users:** generate password hashes via:
```bash
php php/auth/hash_pass.php "ChangeMe!123"
```
Paste the hash into `php/auth/users.json`.

---

## 2) Using the SQL schema (MySQL/MariaDB)

Fetch current user by token:
```php
$token = $_COOKIE['session_token'] ?? '';

$sql = "SELECT u.*
        FROM user_tokens t
        JOIN users u ON u.id = t.user_id
        WHERE t.token = ?
          AND (t.expires_at IS NULL OR t.expires_at > NOW())";
$st = $pdo->prepare($sql);
$st->execute([$token]);
$user = $st->fetch(PDO::FETCH_ASSOC);
```

Issue a new token:
```php
$token = bin2hex(random_bytes(32));
$st = $pdo->prepare("INSERT INTO user_tokens (user_id, token, type, expires_at)
                     VALUES (?, ?, 'session', DATE_ADD(NOW(), INTERVAL 1 DAY))");
$st->execute([$user_id, $token]);
setcookie('session_token', $token, time()+86400, '/', '', true, true);
```

Audit log:
```php
$st = $pdo->prepare("INSERT INTO user_log (user_id, event, ip_address, user_agent)
                     VALUES (?, 'login', ?, ?)");
$st->execute([$user_id, $_SERVER['REMOTE_ADDR'] ?? null, $_SERVER['HTTP_USER_AGENT'] ?? null]);
```

---

## 3) CSRF example

```php
// form:
<input type="hidden" name="csrf" value="<?=htmlspecialchars($csrf)?>">

// handler:
<?php
require_once __DIR__ . '/../php/auth/auth.php';
if (!auth_check_csrf($_POST['csrf'] ?? '')) {
  http_response_code(400);
  exit('Bad CSRF token');
}
?>
```

---

## 4) Tips

- Use HTTPS and set `secure` + `httponly` cookie flags
- Rotate tokens and invalidate on logout
- Consider rate limiting (fail2ban / reverse proxy) on `/auth/login.php`
