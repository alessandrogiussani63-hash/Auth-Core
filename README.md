# 🔐 Auth Core (File‑based middleware + SQL schema)

**Auth Core** is a tiny, dependency‑free authentication starter kit for PHP projects.
It ships with:

- A **session‑based PHP middleware** (login/logout, roles, CSRF helpers)
- A **clean SQL schema** for users + tokens + audit log (MySQL/MariaDB)
- Minimal **examples** to protect pages and validate tokens
- MIT License

This repo is ideal when you want something **portable** (shared hosting, Hestia, Apache/Nginx) that you can drop into your existing apps without frameworks.

---

## 🧩 What’s inside

```
sql/
  auth_core_schema.sql      # users, user_tokens, user_log
php/
  auth/
    auth.php                # middleware (session, role, CSRF helpers)
    login.php               # login form
    logout.php              # logout endpoint
    users.json              # file‑based user store (for quick start)
    hash_pass.php           # CLI utility to generate password hashes
  examples/
    guard_snippet.php       # copy/paste protection snippet for pages
docs/
  USAGE.md                  # quick start & integration notes
LICENSE
.gitignore
README.md
```

---

## 🚀 Quick start

**Option A — File‑based only (no DB)**
1. Place `php/auth/` in your web root.
2. Generate a password hash and put it in `users.json`:
   ```bash
   php php/auth/hash_pass.php "ChangeMe!123"
   ```
3. Protect a page (e.g., `console.php`):
   ```php
   <?php require __DIR__.'/php/auth/auth.php'; auth_require_role('admin'); $csrf = auth_csrf_token(); ?>
   ```
4. Go to `/php/auth/login.php`, sign in, and you’re done.

**Option B — Use the SQL schema**
1. Import `sql/auth_core_schema.sql` in MySQL/MariaDB.
2. In your app, select users/tokens from DB (see examples in `docs/USAGE.md`).

---

## 🔒 Security checklist

- Protect sensitive pages (`/admin/*`, `write_*.php`) with `auth_require_role('admin')`.
- Use the CSRF helper for all POST forms:
  ```php
  <input type="hidden" name="csrf" value="<?=htmlspecialchars($csrf)?>">
  ```
- Validate tokens on each request and set **expiration** for session/API tokens.
- If possible, move `users.json` **outside** the web root and adjust the path.
- Add server‑level protection (Basic Auth, IP allow‑list) for extra safety.

---

## 🧾 License

MIT — free to use and modify with attribution.
