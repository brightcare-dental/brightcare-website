<?php
/**
 * Public-site bootstrap. Loads everything the front-end needs from the DB
 * with a graceful fallback so the site never hard-fails on a DB hiccup.
 */
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/icons.php';

/** All settings as an array (cached). */
function settings_all(): array {
    static $all = null;
    if ($all === null) {
        $all = [];
        try { foreach (db()->query('SELECT skey,svalue FROM settings') as $r) $all[$r['skey']] = $r['svalue']; }
        catch (Throwable $e) { $all = []; }
    }
    return $all;
}
function s(string $key, $default = ''): string {
    $a = settings_all();
    return ($a[$key] ?? '') !== '' ? $a[$key] : $default;
}

/** Published services / doctors / testimonials. */
function get_services(int $limit = 0): array {
    try { return q('SELECT * FROM services WHERE status=1 ORDER BY sort,id' . ($limit ? " LIMIT $limit" : '')); }
    catch (Throwable $e) { return []; }
}
function get_doctors(int $limit = 0): array {
    try { return q('SELECT * FROM doctors WHERE status=1 ORDER BY sort,id' . ($limit ? " LIMIT $limit" : '')); }
    catch (Throwable $e) { return []; }
}
function doctors_by(string $category): array {
    try {
        $st = db()->prepare('SELECT * FROM doctors WHERE status=1 AND category=? ORDER BY sort,id');
        $st->execute([$category]);
        return $st->fetchAll();
    } catch (Throwable $e) { return []; }
}
function get_testimonials(int $limit = 0): array {
    try { return q('SELECT * FROM testimonials WHERE status=1 ORDER BY sort,id' . ($limit ? " LIMIT $limit" : '')); }
    catch (Throwable $e) { return []; }
}
function get_posts(int $limit = 0): array {
    try { return q('SELECT * FROM posts WHERE status=1 AND (published_at IS NULL OR published_at<=NOW()) ORDER BY COALESCE(published_at,created_at) DESC' . ($limit ? " LIMIT $limit" : '')); }
    catch (Throwable $e) { return []; }
}
function get_gallery(int $limit = 0): array {
    try { return q('SELECT * FROM gallery WHERE status=1 ORDER BY sort,id' . ($limit ? " LIMIT $limit" : '')); }
    catch (Throwable $e) { return []; }
}
/** Smile-gallery cases by type ('before-after' | 'makeover' | 'case-study'). */
function get_cases(string $type, int $limit = 0): array {
    try {
        $st = db()->prepare('SELECT * FROM smile_cases WHERE status=1 AND type=? ORDER BY sort,id' . ($limit ? " LIMIT $limit" : ''));
        $st->execute([$type]);
        return $st->fetchAll();
    } catch (Throwable $e) { return []; }
}
function get_case(string $slug): ?array {
    try { return q1('SELECT * FROM smile_cases WHERE slug=? AND status=1', [$slug]); }
    catch (Throwable $e) { return null; }
}
function get_branches(int $limit = 0): array {
    try { $rows = q('SELECT * FROM branches WHERE status=1 ORDER BY is_main DESC, sort, id' . ($limit ? " LIMIT $limit" : '')); }
    catch (Throwable $e) { $rows = []; }
    if (!$rows) $rows = default_branches();
    return $limit ? array_slice($rows, 0, $limit) : $rows;
}

/** Placeholder branches shown until real rows exist in the `branches` table — edit via Admin → Branches. */
function default_branches(): array {
    return [
        ['id'=>0,'name'=>'Mangalapuram','area'=>'','city'=>'Trivandrum','address'=>'','phone'=>'+91 94475 60532','map_url'=>'https://maps.google.com/?q=Mangalapuram+Trivandrum','map_embed'=>'','hours'=>'Mon–Sat 9:00 AM – 8:00 PM','is_main'=>1,'sort'=>0,'status'=>1],
        ['id'=>0,'name'=>'Vengode','area'=>'','city'=>'Trivandrum','address'=>'','phone'=>'+91 94475 60532','map_url'=>'','map_embed'=>'','hours'=>'Mon–Sat 9:00 AM – 8:00 PM','is_main'=>0,'sort'=>1,'status'=>1],
        ['id'=>0,'name'=>'Mananakku','area'=>'','city'=>'Trivandrum','address'=>'','phone'=>'+91 94475 60532','map_url'=>'','map_embed'=>'','hours'=>'Mon–Sat 9:00 AM – 8:00 PM','is_main'=>0,'sort'=>2,'status'=>1],
    ];
}

/** q1() that returns null instead of throwing when the database is unreachable. */
function q1_safe(string $sql, array $params = []): ?array {
    try { return q1($sql, $params); }
    catch (Throwable $e) { return null; }
}

function h($s): string { return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }

/** Social links that are actually configured — a "#" placeholder renders nothing rather than a dead icon. */
function socials(): array {
    $C = clinic();
    $out = [];
    foreach (['fb' => 'Facebook', 'ig' => 'Instagram', 'yt' => 'YouTube'] as $k => $label) {
        $url = trim($C[$k] ?? '');
        if ($url !== '' && $url !== '#') $out[$k] = ['url' => $url, 'label' => $label];
    }
    return $out;
}

/** Patient-facing placeholder for a section with no content yet. Never mention the admin panel here. */
function empty_state(string $icon, string $title, string $text): void { ?>
<div class="estate">
  <span class="estate__ic"><?= icon($icon) ?></span>
  <h3><?= h($title) ?></h3>
  <p><?= h($text) ?></p>
  <div class="estate__cta">
    <a href="/contact#book" data-book class="btn btn--primary btn--sm">Book an Appointment <?= icon('arrow') ?></a>
    <a href="tel:<?= h(s('phone_raw', '919447560532')) ?>" class="btn btn--ghost btn--sm"><?= icon('phone') ?> <?= h(s('phone', '+91 94475 60532')) ?></a>
  </div>
</div>
<?php }

/** Doctor initials for avatar fallback (e.g. "Dr. Aravind Menon" → "AM"). */
function docInitials(string $name): string {
    $name = preg_replace('/^Dr\.?\s+/i', '', trim($name));
    $parts = preg_split('/\s+/', $name);
    $a = $parts[0][0] ?? 'D';
    $b = isset($parts[1]) ? $parts[1][0] : '';
    return strtoupper($a . $b);
}

/** Google reviews trust badge. */
function google_badge(string $rating = '5.0', string $count = '300+'): string {
    $stars = str_repeat(icon('star', 'gstar'), 5);
    return '<a class="gbadge" href="' . h(s('maps_url', '#')) . '" target="_blank" rel="noopener" aria-label="' . h($rating) . ' star rating on Google, ' . h($count) . ' reviews">'
        . '<span class="gbadge__g">' . icon('google') . '</span>'
        . '<span class="gbadge__body">'
        . '<span class="gbadge__top"><b>' . h($rating) . '</b><span class="gbadge__stars">' . $stars . '</span></span>'
        . '<span class="gbadge__sub">' . h($count) . ' Google reviews</span>'
        . '</span></a>';
}

/** Smile-gallery tab bar (links between the 3 gallery pages). */
function gallery_tabs(string $current): void {
    $tabs = ['before-after'=>'Before & After','smile-makeovers'=>'Smile Makeovers','case-studies'=>'Case Studies'];
    echo '<div class="gtabs-wrap"><div class="container gtabs">';
    foreach ($tabs as $slug => $label) {
        echo '<a href="/'.$slug.'" class="gtab'.($current===$slug?' on':'').'">'.h($label).'</a>';
    }
    echo '</div></div>';
}

/** Inner-page hero banner with breadcrumb. */
function page_hero(string $title, string $sub = '', array $crumbs = []): void { ?>
<section class="phero">
  <div class="phero__bg" aria-hidden="true"><span class="mesh mesh--1"></span><span class="grid-dots"></span></div>
  <div class="container phero__in">
    <?php if (count($crumbs) >= 2): ?>
    <nav class="crumbs"><a href="/">Home</a><?php foreach ($crumbs as $label => $url): ?> <span>/</span> <?php echo $url ? '<a href="'.h($url).'">'.h($label).'</a>' : '<b>'.h($label).'</b>'; endforeach; ?></nav>
    <?php endif; ?>
    <h1 class="reveal"><?= h($title) ?></h1>
    <?php if ($sub): ?><p class="reveal"><?= h($sub) ?></p><?php endif; ?>
  </div>
</section>
<?php }

/** WhatsApp / tel convenience. */
function clinic(): array {
    return [
        'name'    => s('clinic_name', 'Bright Care Dental Clinic'),
        'short'   => s('clinic_short', 'Bright Care'),
        'tagline' => s('tagline', 'Caring for Healthy, Confident Smiles'),
        'phone'   => s('phone', '+91 94475 60532'),
        'phone_raw' => s('phone_raw', '919447560532'),
        'whatsapp'  => s('whatsapp', '919447560532'),
        'email'   => s('email', 'info@brightcaredentalclinic.com'),
        'address' => s('address', 'Mangalapuram, Trivandrum, Kerala 695317'),
        'maps'    => s('maps_url', '#'),
        'logo'    => s('logo', '/assets/img/logo.png'),
        'hours_weekday' => s('hours_weekday', '9:00 AM – 8:00 PM'),
        'hours_sunday'  => s('hours_sunday', '10:00 AM – 1:00 PM'),
        'fb' => s('social_facebook', '#'), 'ig' => s('social_instagram', '#'), 'yt' => s('social_youtube', '#'),
    ];
}
