<?php
require_once __DIR__ . '/bootstrap.php';
$C = clinic();
$page  = $page ?? [];
$title = $page['title']       ?? s('seo_title', $C['name']);
$desc  = $page['description'] ?? s('seo_desc', $C['tagline']);
$kw    = $page['keywords']    ?? s('seo_keywords', '');
$active = $page['active'] ?? 'home';
$ogimg  = $page['image'] ?? (s('og_image') ?: '/assets/img/og-image.jpg');
if ($ogimg !== '' && $ogimg[0] === '/') $ogimg = 'https://' . ($_SERVER['HTTP_HOST'] ?? 'rosybrown-wolverine-261784.hostingersite.com') . $ogimg;
$canonical = 'https://' . ($_SERVER['HTTP_HOST'] ?? 'rosybrown-wolverine-261784.hostingersite.com') . ($_SERVER['REQUEST_URI'] ?? '/');

$nav = [
  'home'     => ['/', 'Home'],
  'about'    => ['/about', 'About'],
  'services' => ['/services', 'Services'],
  'smilegallery' => ['/before-after', 'Smile Gallery'],
  'blog'     => ['/blog', 'Blog'],
  'contact'  => ['/contact', 'Contact'],
];
$pc = s('color_primary', '#2e9c8a'); $sc = s('color_secondary', '#2b474d'); $ac = s('color_accent', '#e2a33c');
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= h($title) ?></title>
<meta name="description" content="<?= h($desc) ?>">
<?php if ($kw): ?><meta name="keywords" content="<?= h($kw) ?>"><?php endif; ?>
<meta name="robots" content="index, follow">
<meta name="theme-color" content="<?= h($pc) ?>">
<link rel="canonical" href="<?= h($canonical) ?>">
<meta property="og:type" content="website">
<meta property="og:title" content="<?= h($title) ?>">
<meta property="og:description" content="<?= h($desc) ?>">
<meta property="og:image" content="<?= h($ogimg) ?>">
<meta name="twitter:card" content="summary_large_image">

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,500;9..144,600;9..144,700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="preload" as="style" href="/assets/css/style.css?v=34">
<link rel="stylesheet" href="/assets/css/style.css?v=34">
<?php if (!empty($page['css'])): ?><link rel="stylesheet" href="<?= h($page['css']) ?>"><?php endif; ?>
<link rel="icon" href="/assets/img/favicon.png" type="image/png">
<link rel="apple-touch-icon" href="/assets/img/favicon.png">
<!-- dynamic brand theme from admin -->
<style>:root{--primary:<?= h($pc) ?>;--secondary:<?= h($sc) ?>;--accent:<?= h($ac) ?>}</style>

<script type="application/ld+json">
{"@context":"https://schema.org","@type":"Dentist","name":<?= json_encode($C['name']) ?>,
"image":<?= json_encode($ogimg) ?>,"telephone":<?= json_encode($C['phone']) ?>,"email":<?= json_encode($C['email']) ?>,
"url":<?= json_encode('https://' . ($_SERVER['HTTP_HOST'] ?? '')) ?>,
"address":{"@type":"PostalAddress","streetAddress":"Mangalapuram","addressLocality":"Trivandrum","addressRegion":"Kerala","postalCode":"695317","addressCountry":"IN"},
"openingHours":["Mo-Sa 09:00-20:00","Su 10:00-13:00"],"priceRange":"$$",
"aggregateRating":{"@type":"AggregateRating","ratingValue":"5.0","reviewCount":"300"}}
</script>
<?= s('head_scripts') ?>
</head>
<body>
<span class="scrollbar" id="scrollbar"></span>
<a class="skip-link" href="#main">Skip to content</a>

<div class="topbar">
  <div class="container topbar__in">
    <span class="topbar__item"><?= icon('pin') ?> Trivandrum, Kerala</span>
    <span class="topbar__sep"></span>
    <span class="topbar__item"><?= icon('clock') ?> Mon–Sat <?= h($C['hours_weekday']) ?></span>
    <span class="topbar__spacer"></span>
    <a class="topbar__item" href="tel:<?= h($C['phone_raw']) ?>"><?= icon('phone') ?> <?= h($C['phone']) ?></a>
    <?php if ($SOC = socials()): ?>
    <span class="topbar__socials">
      <?php foreach ($SOC as $k => $s): ?><a href="<?= h($s['url']) ?>" target="_blank" rel="noopener" aria-label="<?= h($s['label']) ?>"><?= icon($k) ?></a><?php endforeach; ?>
    </span>
    <?php endif; ?>
  </div>
</div>

<header class="header" id="header">
  <div class="container header__in">
    <a class="brand" href="/" aria-label="<?= h($C['name']) ?> home">
      <?php if ($C['logo']): ?>
        <img class="brand__img" src="<?= h($C['logo']) ?>" alt="<?= h($C['name']) ?>" height="46">
      <?php else: ?>
        <span class="brand__mark"><?= icon('tooth') ?></span>
        <span class="brand__text"><span class="brand__name"><?= h($C['short']) ?></span><span class="brand__sub">Dental Clinic</span></span>
      <?php endif; ?>
    </a>
    <nav class="nav" id="nav" aria-label="Primary">
      <button class="nav__close" id="navClose" aria-label="Close menu"><?= icon('close') ?></button>
      <div class="nav__links">
        <?php $ni=0; foreach ($nav as $k => $n): $ni++; ?>
          <?php if ($k === 'services'): ?>
            <div class="nav__item nav__item--mega" data-mega>
              <a href="/services" class="nav__link <?= in_array($active,['services','aligners'],true) ? 'is-active' : '' ?>" style="--i:<?= $ni ?>">Services
                <svg class="nav__chev" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M6 9l6 6 6-6"/></svg>
              </a>
            </div>
          <?php else: ?>
            <a href="<?= $n[0] ?>" class="nav__link <?= $active === $k ? 'is-active' : '' ?>" style="--i:<?= $ni ?>"><?= $n[1] ?></a>
          <?php endif; ?>
        <?php endforeach; ?>
      </div>
      <a href="/contact#book" class="btn btn--primary nav__cta" style="--i:<?= $ni+1 ?>">Book Appointment</a>
      <div class="nav__foot">
        <a class="nav__call" href="tel:<?= h($C['phone_raw']) ?>"><?= icon('phone') ?> <?= h($C['phone']) ?></a>
        <?php if ($SOC = socials()): ?>
        <div class="nav__social">
          <?php foreach ($SOC as $k => $s): ?><a href="<?= h($s['url']) ?>" target="_blank" rel="noopener" aria-label="<?= h($s['label']) ?>"><?= icon($k) ?></a><?php endforeach; ?>
        </div>
        <?php endif; ?>
      </div>
    </nav>
    <button class="nav__toggle" id="navToggle" aria-label="Open menu" aria-expanded="false"><?= icon('menu') ?></button>
  </div>
</header>

<!-- Services mega menu (desktop hover) -->
<div class="megamenu" id="megamenu" aria-hidden="true">
  <span class="megamenu__scrim" data-mega-close></span>
  <div class="megamenu__panel">
    <button class="megamenu__close" data-mega-close aria-label="Close menu"><?= icon('close') ?></button>
    <div class="container megamenu__in">
      <div class="megamenu__top">
        <span class="kicker">Our Treatments</span>
        <h2>Comprehensive Dental <span class="grad">Care</span></h2>
      </div>
      <div class="megamenu__grid">
        <a href="/aligners" class="megaitem megaitem--feature">
          <span class="megaitem__ic"><?= icon('sparkle') ?></span>
          <span class="megaitem__t"><strong>Clear Aligners</strong><small>Invisible teeth straightening — our signature treatment</small></span>
        </a>
        <?php foreach (get_services() as $msv): ?>
          <a href="/services/<?= h($msv['slug']) ?>" class="megaitem">
            <span class="megaitem__ic"><?= icon($msv['icon'] ?: 'tooth') ?></span>
            <span class="megaitem__t"><strong><?= h($msv['title']) ?></strong><small><?= h($msv['excerpt']) ?></small></span>
          </a>
        <?php endforeach; ?>
      </div>
      <div class="megamenu__foot">
        <a href="/services" class="btn btn--ghost">View All Services <?= icon('arrow') ?></a>
        <a href="/contact#book" class="btn btn--primary">Book Appointment <?= icon('arrow') ?></a>
      </div>
    </div>
  </div>
</div>
<main id="main">
