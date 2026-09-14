<?php
/** Admin session, authentication and CSRF helpers. */
require_once __DIR__ . '/../../includes/db.php';

if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params([
        'lifetime' => 0, 'path' => '/admin', 'httponly' => true,
        'samesite' => 'Lax',
        'secure' => (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off'),
    ]);
    session_name('BCADMIN');
    session_start();
}

// ── CSRF ─────────────────────────────────────────────────────
function csrf_token(): string {
    if (empty($_SESSION['csrf'])) $_SESSION['csrf'] = bin2hex(random_bytes(32));
    return $_SESSION['csrf'];
}
function csrf_field(): string {
    return '<input type="hidden" name="csrf" value="' . csrf_token() . '">';
}
function csrf_check(): void {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (!hash_equals($_SESSION['csrf'] ?? '', $_POST['csrf'] ?? '')) {
            http_response_code(419);
            exit('Session expired. Please go back and try again.');
        }
    }
}

// ── Auth ─────────────────────────────────────────────────────
function current_user(): ?array {
    return $_SESSION['admin'] ?? null;
}
function require_login(): void {
    if (!current_user()) {
        header('Location: /admin/index.php');
        exit;
    }
}
function attempt_login(string $user, string $pass): bool {
    $row = q1('SELECT * FROM admins WHERE username = ? OR email = ? LIMIT 1', [$user, $user]);
    if ($row && password_verify($pass, $row['password'])) {
        session_regenerate_id(true);
        unset($row['password']);
        $_SESSION['admin'] = $row;
        db()->prepare('UPDATE admins SET last_login = NOW() WHERE id = ?')->execute([$row['id']]);
        return true;
    }
    return false;
}
function logout(): void {
    $_SESSION = [];
    session_destroy();
}

// ── Tiny helpers ─────────────────────────────────────────────
function e($s): string { return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }
function redirect(string $to): void { header("Location: $to"); exit; }
function flash(?string $msg = null): ?string {
    if ($msg !== null) { $_SESSION['flash'] = $msg; return null; }
    $m = $_SESSION['flash'] ?? null; unset($_SESSION['flash']); return $m;
}
function slugify(string $s): string {
    $s = strtolower(trim(preg_replace('/[^a-z0-9]+/i', '-', $s), '-'));
    return $s ?: 'item-' . time();
}
