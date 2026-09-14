<?php
require __DIR__ . '/includes/bootstrap.php';
header('Content-Type: application/xml; charset=utf-8');
$base = 'https://' . ($_SERVER['HTTP_HOST'] ?? 'rosybrown-wolverine-261784.hostingersite.com');
$urls = ['/','/about','/services','/doctors','/gallery','/blog','/contact'];
foreach (get_services() as $s) $urls[] = '/services/' . $s['slug'];
foreach (get_posts() as $p)   $urls[] = '/blog/' . $p['slug'];
echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
foreach ($urls as $u) {
    echo "  <url><loc>" . h($base . $u) . "</loc><changefreq>weekly</changefreq></url>\n";
}
echo '</urlset>';
