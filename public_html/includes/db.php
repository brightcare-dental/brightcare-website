<?php
/**
 * Database connection (PDO) for Bright Care Dental Clinic.
 *
 * Credentials come from, in order of precedence:
 *   1. Environment variables (DB_HOST / DB_NAME / DB_USER / DB_PASS)
 *   2. includes/db.config.php — never committed; see db.config.example.php
 *
 * On a plain shared host, copy db.config.example.php to db.config.php and fill it in.
 */
// A visitor must never see a PHP error. Set APP_DEBUG=1 to show them while developing.
if (!getenv('APP_DEBUG')) {
    ini_set('display_errors', '0');
    ini_set('display_startup_errors', '0');
}
ini_set('log_errors', '1');

$__cfg = is_file(__DIR__ . '/db.config.php') ? (require __DIR__ . '/db.config.php') : [];

define('DB_HOST', getenv('DB_HOST') ?: ($__cfg['host'] ?? 'localhost'));
define('DB_NAME', getenv('DB_NAME') ?: ($__cfg['name'] ?? ''));
define('DB_USER', getenv('DB_USER') ?: ($__cfg['user'] ?? ''));
define('DB_PASS', getenv('DB_PASS') ?: ($__cfg['pass'] ?? ''));
unset($__cfg);

function db(): PDO {
    static $pdo = null;
    if ($pdo === null) {
        $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4';
        $pdo = new PDO($dsn, DB_USER, DB_PASS, [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]);
    }
    return $pdo;
}

/** Fetch a single setting value with a fallback. */
function setting(string $key, $default = '') {
    static $cache = null;
    if ($cache === null) {
        $cache = [];
        foreach (db()->query('SELECT skey, svalue FROM settings') as $r) {
            $cache[$r['skey']] = $r['svalue'];
        }
    }
    return $cache[$key] ?? $default;
}

/** Convenience: prepared SELECT returning all rows. */
function q(string $sql, array $params = []): array {
    $st = db()->prepare($sql);
    $st->execute($params);
    return $st->fetchAll();
}

/** Convenience: prepared SELECT returning one row. */
function q1(string $sql, array $params = []): ?array {
    $st = db()->prepare($sql);
    $st->execute($params);
    $row = $st->fetch();
    return $row ?: null;
}
