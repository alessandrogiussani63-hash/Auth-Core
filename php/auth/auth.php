<?php
/**
 * Auth middleware (session-based)
 * - Role protection: auth_require_role('admin')
 * - CSRF tokens: auth_csrf_token(), auth_check_csrf($token)
 * - Helper: auth_user() returns current user array or null
 */
session_start();

/**
 * Require a logged-in user with the given role.
 * Redirects to /php/auth/login.php if not logged in.
 * Sends HTTP 403 if role does not match.
 */
function auth_require_role($role = 'admin') {
    if (!isset($_SESSION['user'])) {
        $next = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '/';
        header('Location: /php/auth/login.php?next=' . urlencode($next));
        exit;
    }
    if ($role && (!isset($_SESSION['user']['role']) || $_SESSION['user']['role'] !== $role)) {
        http_response_code(403);
        echo "Forbidden";
        exit;
    }
}

/** Get the current user info (array) or null. */
function auth_user() {
    return $_SESSION['user'] ?? null;
}

/** Return/create a per-session CSRF token string. */
function auth_csrf_token() {
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(16));
    }
    return $_SESSION['csrf'];
}

/** Returns true if the provided CSRF token matches the session token. */
function auth_check_csrf($token) {
    return isset($_SESSION['csrf']) && hash_equals($_SESSION['csrf'], $token);
}
