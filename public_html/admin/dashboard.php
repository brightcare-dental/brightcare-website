<?php
require __DIR__ . '/inc/layout.php';
admin_head('Dashboard');

$counts = [
  ['Services',     (int)db()->query('SELECT COUNT(*) FROM services')->fetchColumn(),     'tooth', 'services.php'],
  ['Doctors',      (int)db()->query('SELECT COUNT(*) FROM doctors')->fetchColumn(),      'user',  'doctors.php'],
  ['Gallery Items',(int)db()->query('SELECT COUNT(*) FROM gallery')->fetchColumn(),      'image', 'gallery.php'],
  ['Blog Posts',   (int)db()->query('SELECT COUNT(*) FROM posts')->fetchColumn(),        'news',  'posts.php'],
  ['New Enquiries',(int)db()->query("SELECT COUNT(*) FROM enquiries WHERE status='new'")->fetchColumn(), 'inbox', 'enquiries.php'],
];
$recent = q('SELECT name,phone,service,created_at FROM enquiries ORDER BY id DESC LIMIT 6');
?>
<div class="cards-row">
  <?php foreach ($counts as $c): ?>
    <a class="stat-card" href="/admin/<?= $c[3] ?>">
      <div class="ic"><?= adm_icon($c[2]) ?></div>
      <div class="n"><?= $c[1] ?></div>
      <div class="l"><?= $c[0] ?></div>
    </a>
  <?php endforeach; ?>
</div>

<div class="panel">
  <div class="panel__head"><h2>Recent Enquiries</h2><a class="btn btn--ghost btn--sm" href="/admin/enquiries.php">View all</a></div>
  <div class="panel__body" style="padding:0">
    <?php if ($recent): ?>
    <table class="tbl">
      <tr><th>Name</th><th>Phone</th><th>Service</th><th>When</th></tr>
      <?php foreach ($recent as $r): ?>
        <tr><td><?= e($r['name']) ?></td><td><?= e($r['phone']) ?></td><td><?= e($r['service']) ?></td><td><?= e($r['created_at']) ?></td></tr>
      <?php endforeach; ?>
    </table>
    <?php else: ?>
      <p style="padding:20px;color:var(--mut)">No enquiries yet. Submissions from the website booking form will appear here.</p>
    <?php endif; ?>
  </div>
</div>

<div class="panel">
  <div class="panel__head"><h2>Quick Start</h2></div>
  <div class="panel__body">
    <p style="color:var(--mut);margin-bottom:14px">Welcome to your website control panel. From here you can manage every part of the site:</p>
    <div class="actions" style="flex-wrap:wrap;gap:10px">
      <a class="btn btn--ghost btn--sm" href="/admin/settings.php">Edit clinic info & logo</a>
      <a class="btn btn--ghost btn--sm" href="/admin/services.php">Manage services</a>
      <a class="btn btn--ghost btn--sm" href="/admin/gallery.php">Add photos & videos</a>
      <a class="btn btn--ghost btn--sm" href="/admin/seo.php">SEO settings</a>
    </div>
  </div>
</div>
<?php admin_foot(); ?>
