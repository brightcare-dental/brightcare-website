<?php
require __DIR__ . '/inc/layout.php';
require __DIR__ . '/inc/helpers.php';

$fields = ['seo_title','seo_desc','seo_keywords','og_image','robots_txt'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  csrf_check();
  try {
    $up = db()->prepare('INSERT INTO settings(skey,svalue) VALUES(?,?) ON DUPLICATE KEY UPDATE svalue=VALUES(svalue)');
    foreach ($fields as $f) $up->execute([$f, trim($_POST[$f] ?? '')]);
    if ($og = upload_file('og_file','image')) $up->execute(['og_image', $og['url']]);
    flash('SEO settings saved.');
  } catch (Throwable $ex) { flash('Error: ' . $ex->getMessage()); }
  redirect('/admin/seo.php');
}

$S=[]; foreach (db()->query('SELECT skey,svalue FROM settings') as $r) $S[$r['skey']]=$r['svalue'];
$v = fn($k)=>e($S[$k]??'');
$svcMissing = (int)db()->query("SELECT COUNT(*) FROM services WHERE seo_title IS NULL OR seo_title=''")->fetchColumn();

admin_head('SEO');
?>
<form method="post" enctype="multipart/form-data">
  <?= csrf_field() ?>
  <div class="panel"><div class="panel__head"><h2>Global SEO Defaults</h2></div>
    <div class="panel__body">
      <div class="fld"><label>Default Meta Title</label><input name="seo_title" value="<?= $v('seo_title') ?>"><div class="hint">Used on the homepage and as a fallback. ~60 characters.</div></div>
      <div class="fld"><label>Default Meta Description</label><textarea name="seo_desc"><?= $v('seo_desc') ?></textarea><div class="hint">~155 characters.</div></div>
      <div class="fld"><label>Default Keywords</label><input name="seo_keywords" value="<?= $v('seo_keywords') ?>"></div>
      <div class="fld"><label>Default Social Share Image (OG)</label>
        <?php if($og=($S['og_image']??'')):?><div style="margin-bottom:8px"><img src="<?= e($og)?>" style="height:70px;border-radius:8px"></div><?php endif;?>
        <input type="file" name="og_file" accept="image/*"><div class="hint">1200×630px recommended.</div>
      </div>
    </div>
  </div>

  <div class="panel"><div class="panel__head"><h2>robots.txt</h2></div>
    <div class="panel__body">
      <div class="fld"><textarea name="robots_txt" style="min-height:120px;font-family:monospace"><?= $v('robots_txt') ?: "User-agent: *\nAllow: /\nSitemap: https://rosybrown-wolverine-261784.hostingersite.com/sitemap.xml" ?></textarea></div>
    </div>
  </div>

  <div class="panel"><div class="panel__head"><h2>Sitemap</h2></div>
    <div class="panel__body">
      <p style="color:var(--mut);margin-bottom:12px"><code>/sitemap.xml</code> is generated automatically from your published pages, services and posts.</p>
      <a class="btn btn--ghost btn--sm" href="/sitemap.xml" target="_blank">View sitemap.xml</a>
      <?php if($svcMissing):?><p style="color:#c98415;margin-top:12px">⚠ <?= $svcMissing ?> service(s) have no custom SEO title — they’ll fall back to the service name.</p><?php endif;?>
    </div>
  </div>

  <div class="form-actions"><button class="btn">Save SEO Settings</button></div>
</form>
<?php admin_foot(); ?>
