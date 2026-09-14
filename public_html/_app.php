<?php
/**
 * Single-lambda front controller for Vercel.
 *
 * Vercel's Hobby plan allows 12 serverless functions; building one per PHP file
 * produces ~48. Everything is therefore routed through this one file, which
 * resolves the request to the right page and includes it. The rewrite rules
 * below mirror .htaccess so URLs behave identically on Apache and on Vercel.
 *
 * Apache never uses this file — it serves the page scripts directly.
 */

$root = __DIR__;
$uri  = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
$path = trim($uri, '/');

/** Include a page script, or 404 if it is missing. */
$serve = function (string $script, array $query = []) use ($root) {
    $file = $root . '/' . ltrim($script, '/');
    $real = realpath($file);
    // Never escape the document root, and never execute anything but PHP.
    if ($real === false || strncmp($real, $root, strlen($root)) !== 0 || substr($real, -4) !== '.php') {
        http_response_code(404);
        return false;
    }
    foreach ($query as $k => $v) { $_GET[$k] = $v; $_REQUEST[$k] = $v; }
    $_SERVER['SCRIPT_NAME']     = '/' . ltrim($script, '/');
    $_SERVER['SCRIPT_FILENAME'] = $real;
    require $real;
    return true;
};

// Never serve internals or the one-time installers over HTTP.
if (preg_match('#^(includes|vendor)(/|$)#', $path) || preg_match('#^admin/_#', $path)) {
    http_response_code(404);
    exit;
}

// Static files that slipped past the CDN rules (images, css, js).
if ($path !== '' && preg_match('#\.(css|js|png|jpe?g|gif|svg|webp|ico|woff2?|ttf|map)$#i', $path)) {
    $file = realpath($root . '/' . $path);
    if ($file && strncmp($file, $root, strlen($root)) === 0 && is_file($file)) {
        $types = [
            'css' => 'text/css', 'js' => 'application/javascript', 'svg' => 'image/svg+xml',
            'png' => 'image/png', 'jpg' => 'image/jpeg', 'jpeg' => 'image/jpeg',
            'gif' => 'image/gif', 'webp' => 'image/webp', 'ico' => 'image/x-icon',
            'woff' => 'font/woff', 'woff2' => 'font/woff2', 'ttf' => 'font/ttf',
            'map' => 'application/json',
        ];
        $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
        header('Content-Type: ' . ($types[$ext] ?? 'application/octet-stream'));
        header('Cache-Control: public, max-age=2592000');
        readfile($file);
        exit;
    }
}

// ── Routes, mirroring .htaccess ───────────────────────────────
if ($path === '')                { $serve('index.php');   exit; }
if ($path === 'sitemap.xml')     { $serve('sitemap.php'); exit; }
if ($path === 'robots.txt')      { $serve('robots.php');  exit; }

if (preg_match('#^services/([a-z0-9-]+)$#', $path, $m)) { $serve('service.php', ['s' => $m[1]]); exit; }
if (preg_match('#^blog/([a-z0-9-]+)$#',     $path, $m)) { $serve('post.php',    ['s' => $m[1]]); exit; }
if (preg_match('#^case/([a-z0-9-]+)$#',     $path, $m)) { $serve('case.php',    ['s' => $m[1]]); exit; }

// Admin CMS.
if ($path === 'admin' || $path === 'admin/') { $serve('admin/index.php'); exit; }
if (preg_match('#^admin/([a-z0-9-]+)(\.php)?$#', $path, $m)) {
    if ($serve('admin/' . $m[1] . '.php')) exit;
}

// Top-level pages: /about → about.php
if (preg_match('#^([a-z0-9-]+)(\.php)?$#', $path, $m)) {
    if ($serve($m[1] . '.php')) exit;
}

// Nothing matched — render the styled 404 rather than a bare error.
http_response_code(404);
$page = ['active' => '', 'title' => 'Page not found'];
require $root . '/includes/header.php';
echo '<section class="section"><div class="container" style="text-align:center;padding:60px 0">'
   . '<h1>Page not found</h1>'
   . '<p style="margin:12px 0 24px">The page you are looking for does not exist or has moved.</p>'
   . '<a class="btn btn--primary" href="/">Back to Home</a>'
   . '</div></section>';
require $root . '/includes/footer.php';
