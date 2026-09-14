<?php
require __DIR__ . '/inc/layout.php';
require __DIR__ . '/inc/helpers.php';

$fields = [
  'clinic_name','clinic_short','tagline','phone','phone_raw','whatsapp','email',
  'address','maps_url','map_embed','hours_weekday','hours_sunday',
  'social_facebook','social_instagram','social_youtube',
  'color_primary','color_secondary','color_accent','head_scripts','body_scripts',
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  csrf_check();
  try {
    $up = db()->prepare('INSERT INTO settings(skey,svalue) VALUES(?,?) ON DUPLICATE KEY UPDATE svalue=VALUES(svalue)');
    foreach ($fields as $f) $up->execute([$f, trim($_POST[$f] ?? '')]);
    if ($logo = upload_file('logo_file', 'image')) $up->execute(['logo', $logo['url']]);
    flash('Settings saved successfully.');
  } catch (Throwable $ex) { flash('Error: ' . $ex->getMessage()); }
  redirect('/admin/settings.php');
}

$S = [];
foreach (db()->query('SELECT skey,svalue FROM settings') as $r) $S[$r['skey']] = $r['svalue'];
$v = fn($k) => e($S[$k] ?? '');

admin_head('Settings');
?>
<form method="post" enctype="multipart/form-data">
  <?= csrf_field() ?>

  <div class="panel"><div class="panel__head"><h2>Clinic Information</h2></div>
    <div class="panel__body"><div class="form-grid">
      <div class="fld"><label>Clinic Name</label><input name="clinic_name" value="<?= $v('clinic_name') ?>"></div>
      <div class="fld"><label>Short Name</label><input name="clinic_short" value="<?= $v('clinic_short') ?>"></div>
      <div class="fld fld--full"><label>Tagline</label><input name="tagline" value="<?= $v('tagline') ?>"></div>
      <div class="fld fld--full"><label>Logo</label>
        <?php if ($lg = ($S['logo'] ?? '')): ?><div style="margin-bottom:8px"><img src="<?= e($lg) ?>" style="height:46px;background:#eee;padding:5px;border-radius:8px"></div><?php endif; ?>
        <input type="file" name="logo_file" accept="image/*"><div class="hint">PNG with transparent background recommended.</div>
      </div>
    </div></div>
  </div>

  <div class="panel"><div class="panel__head"><h2>Contact &amp; Hours</h2></div>
    <div class="panel__body"><div class="form-grid">
      <div class="fld"><label>Phone (display)</label><input name="phone" value="<?= $v('phone') ?>"></div>
      <div class="fld"><label>Phone (digits for tel:)</label><input name="phone_raw" value="<?= $v('phone_raw') ?>"></div>
      <div class="fld"><label>WhatsApp number</label><input name="whatsapp" value="<?= $v('whatsapp') ?>"></div>
      <div class="fld"><label>Email</label><input name="email" value="<?= $v('email') ?>"></div>
      <div class="fld fld--full"><label>Address</label><input name="address" value="<?= $v('address') ?>"></div>
      <div class="fld"><label>Hours — Mon to Sat</label><input name="hours_weekday" value="<?= $v('hours_weekday') ?>"></div>
      <div class="fld"><label>Hours — Sunday</label><input name="hours_sunday" value="<?= $v('hours_sunday') ?>"></div>
      <div class="fld"><label>Google Maps link</label><input name="maps_url" value="<?= $v('maps_url') ?>"></div>
      <div class="fld fld--full"><label>Google Maps embed code (iframe)</label><textarea name="map_embed"><?= $v('map_embed') ?></textarea><div class="hint">Paste the &lt;iframe&gt; embed from Google Maps → Share → Embed.</div></div>
    </div></div>
  </div>

  <div class="panel"><div class="panel__head"><h2>Social Links</h2></div>
    <div class="panel__body"><div class="form-grid">
      <div class="fld"><label>Facebook URL</label><input name="social_facebook" value="<?= $v('social_facebook') ?>"></div>
      <div class="fld"><label>Instagram URL</label><input name="social_instagram" value="<?= $v('social_instagram') ?>"></div>
      <div class="fld"><label>YouTube URL</label><input name="social_youtube" value="<?= $v('social_youtube') ?>"></div>
    </div></div>
  </div>

  <div class="panel"><div class="panel__head"><h2>Brand Colours</h2></div>
    <div class="panel__body"><div class="form-grid">
      <div class="fld"><label>Primary</label><input type="color" name="color_primary" value="<?= $v('color_primary') ?: '#2e9c8a' ?>"></div>
      <div class="fld"><label>Secondary</label><input type="color" name="color_secondary" value="<?= $v('color_secondary') ?: '#2b474d' ?>"></div>
      <div class="fld"><label>Accent</label><input type="color" name="color_accent" value="<?= $v('color_accent') ?: '#e2a33c' ?>"></div>
    </div></div>
  </div>

  <div class="panel"><div class="panel__head"><h2>Tracking / Custom Scripts</h2></div>
    <div class="panel__body"><div class="form-grid">
      <div class="fld fld--full"><label>Head scripts (Google Analytics, verification…)</label><textarea name="head_scripts"><?= $v('head_scripts') ?></textarea></div>
      <div class="fld fld--full"><label>Body-end scripts (chat widgets, pixels…)</label><textarea name="body_scripts"><?= $v('body_scripts') ?></textarea></div>
    </div></div>
  </div>

  <div class="form-actions"><button class="btn">Save Settings</button></div>
</form>
<?php admin_foot(); ?>
