<?php
require __DIR__ . '/includes/bootstrap.php';
header('Content-Type: text/plain; charset=utf-8');
$base = 'https://' . ($_SERVER['HTTP_HOST'] ?? 'rosybrown-wolverine-261784.hostingersite.com');
$custom = trim(s('robots_txt'));
echo $custom !== '' ? $custom : "User-agent: *\nAllow: /\nDisallow: /admin/\nSitemap: $base/sitemap.xml";
