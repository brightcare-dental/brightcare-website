<?php
/** Admin chrome: header (sidebar + topbar) and footer. */
require_once __DIR__ . '/auth.php';

function admin_head(string $title = 'Dashboard'): void {
    require_login();
    $u = current_user();
    $cur = basename($_SERVER['PHP_SELF']);
    $nav = [
        ['dashboard.php','Dashboard','grid'],
        ['services.php','Services','tooth'],
        ['doctors.php','Doctors','user'],
        ['branches.php','Branches','pin'],
        ['testimonials.php','Testimonials','star'],
        ['gallery.php','Gallery','image'],
        ['smile-cases.php','Smile Gallery','sparkle'],
        ['pages.php','Pages','file'],
        ['posts.php','Blog / News','news'],
        ['enquiries.php','Enquiries','inbox'],
        ['media.php','Media Library','folder'],
        ['seo.php','SEO','search'],
        ['settings.php','Settings','cog'],
    ];
    ?><!DOCTYPE html><html lang="en"><head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title><?= e($title) ?> · Bright Care Admin</title>
<link rel="icon" href="/assets/img/favicon.png" type="image/png">
<link rel="stylesheet" href="/admin/assets/admin.css?v=1">
</head><body>
<div class="adm">
  <aside class="side" id="side">
    <div class="side__brand"><span class="side__logo">B</span> Bright Care <small>Admin</small></div>
    <nav class="side__nav">
      <?php foreach ($nav as $n): ?>
        <a href="/admin/<?= $n[0] ?>" class="<?= $cur === $n[0] ? 'on' : '' ?>"><?= adm_icon($n[2]) ?><span><?= $n[1] ?></span></a>
      <?php endforeach; ?>
    </nav>
    <a href="/" target="_blank" class="side__view"><?= adm_icon('eye') ?><span>View Site</span></a>
  </aside>
  <div class="main">
    <header class="top">
      <button class="top__burger" id="burger"><?= adm_icon('menu') ?></button>
      <h1 class="top__title"><?= e($title) ?></h1>
      <div class="top__user">
        <span><?= e($u['name'] ?: $u['username']) ?></span>
        <a href="/admin/logout.php" class="top__out"><?= adm_icon('out') ?> Logout</a>
      </div>
    </header>
    <div class="content">
    <?php if ($f = flash()): ?><div class="alert"><?= e($f) ?></div><?php endif; ?>
<?php }

function admin_foot(): void { ?>
    </div>
  </div>
</div>
<script src="/admin/assets/admin.js?v=1"></script>
</body></html>
<?php }

function adm_icon(string $n): string {
    $p = [
        'grid'=>'M3 3h8v8H3zM13 3h8v8h-8zM3 13h8v8H3zM13 13h8v8h-8z',
        'tooth'=>'M12 2C8 2 7 5 5 5 3.5 5 3 6.5 3 9c0 4 1.5 11 3 11 1.3 0 1.3-4 3-4s1.7 4 3 4c1.5 0 3-7 3-11 0-2.5-.5-4-2-4-2 0-3-3-7-3z',
        'user'=>'M12 12a5 5 0 100-10 5 5 0 000 10zm0 2c-5 0-9 2.5-9 6v2h18v-2c0-3.5-4-6-9-6z',
        'star'=>'M12 2l3 6.5 7 .8-5.2 4.8 1.4 6.9L12 17.8 5.8 21l1.4-6.9L2 9.3l7-.8z',
        'image'=>'M3 3h18v18H3zm3 12l4-4 3 3 4-5 3 4',
        'file'=>'M6 2h8l4 4v16H6zM14 2v4h4',
        'news'=>'M3 4h14v16H3zM7 8h6M7 12h6M7 16h4M17 8h4v12a2 2 0 01-4 0z',
        'inbox'=>'M3 13l3-9h12l3 9v7H3zM3 13h5l2 3h4l2-3h5',
        'folder'=>'M3 5h7l2 3h9v11H3z',
        'search'=>'M11 2a9 9 0 105.6 16.1l4.6 4.6 1.4-1.4-4.6-4.6A9 9 0 0011 2zm0 2a7 7 0 110 14 7 7 0 010-14z',
        'cog'=>'M12 8a4 4 0 100 8 4 4 0 000-8zm9 4l-2-1.5.3-2.5-2.4-.8-1.2-2.2-2.4.6L11 2 9 3.4 6.6 2.8 5.4 5 3 5.8l.3 2.5L1 12l2.3 1.5L3 16l2.4.8L6.6 19l2.4-.6L11 22l2-1.4 2.4.6 1.2-2.2 2.4-.8-.3-2.5z',
        'eye'=>'M12 5C5 5 1 12 1 12s4 7 11 7 11-7 11-7-4-7-11-7zm0 11a4 4 0 110-8 4 4 0 010 8z',
        'pin'=>'M12 2a7 7 0 017 7c0 5-7 13-7 13S5 14 5 9a7 7 0 017-7z M12 6a3 3 0 100 6 3 3 0 000-6z',
        'sparkle'=>'M12 3l1.9 5.1L19 10l-5.1 1.9L12 17l-1.9-5.1L6 10l5.1-1.9z',
        'menu'=>'M3 6h18v2H3zM3 11h18v2H3zM3 16h18v2H3z',
        'out'=>'M10 3H5v18h5v-2H7V5h3zM16 7l-1.4 1.4L17.2 11H10v2h7.2l-2.6 2.6L16 17l5-5z',
    ];
    return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" class="i"><path d="'.($p[$n]??'').'"/></svg>';
}
