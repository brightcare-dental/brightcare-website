<?php
require __DIR__ . '/inc/layout.php';
require __DIR__ . '/inc/helpers.php';

$id  = (int)($_GET['id'] ?? 0);
$row = $id ? q1('SELECT * FROM services WHERE id=?', [$id]) : null;
$icons = ['tooth','root','implant','braces','sparkle','child','shield','heart'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  csrf_check();
  try {
    $title = trim($_POST['title'] ?? '');
    if ($title === '') throw new RuntimeException('Title is required.');
    $slug  = slugify($_POST['slug'] ?: $title);
    $data = [
      $slug, $_POST['icon'] ?? 'tooth', $title, trim($_POST['excerpt'] ?? ''),
      $_POST['content'] ?? '', (int)($_POST['sort'] ?? 0), (int)($_POST['status'] ?? 1),
      trim($_POST['seo_title'] ?? ''), trim($_POST['seo_desc'] ?? ''), trim($_POST['seo_keywords'] ?? ''),
    ];
    $img = upload_file('image_file', 'image');
    if ($id) {
      $sql = 'UPDATE services SET slug=?,icon=?,title=?,excerpt=?,content=?,sort=?,status=?,seo_title=?,seo_desc=?,seo_keywords=?'
           . ($img ? ',image=?' : '') . ' WHERE id=?';
      if ($img) $data[] = $img['url'];
      $data[] = $id;
      db()->prepare($sql)->execute($data);
      flash('Service updated.');
    } else {
      $cols='slug,icon,title,excerpt,content,sort,status,seo_title,seo_desc,seo_keywords'.($img?',image':'');
      $ph='?,?,?,?,?,?,?,?,?,?'.($img?',?':'');
      if ($img) $data[]=$img['url'];
      db()->prepare("INSERT INTO services($cols) VALUES($ph)")->execute($data);
      flash('Service created.');
    }
    redirect('/admin/services.php');
  } catch (Throwable $ex) { flash('Error: ' . $ex->getMessage()); redirect($_SERVER['REQUEST_URI']); }
}

$r = fn($k,$d='')=>e($row[$k] ?? $d);
admin_head($id ? 'Edit Service' : 'Add Service');
editor_assets();
?>
<form method="post" enctype="multipart/form-data">
  <?= csrf_field() ?>
  <div class="panel"><div class="panel__body"><div class="form-grid">
    <div class="fld"><label>Title</label><input id="title" name="title" data-slug-source="#slug" value="<?= $r('title') ?>" required></div>
    <div class="fld"><label>Slug (URL)</label><input id="slug" name="slug" value="<?= $r('slug') ?>" placeholder="auto from title"></div>
    <div class="fld"><label>Icon</label><select name="icon"><?php foreach($icons as $ic):?><option <?= ($row['icon']??'')===$ic?'selected':'' ?>><?= $ic ?></option><?php endforeach;?></select></div>
    <div class="fld"><label>Sort order</label><input type="number" name="sort" value="<?= $r('sort','0') ?>"></div>
    <div class="fld fld--full"><label>Short excerpt (card text)</label><textarea name="excerpt"><?= $r('excerpt') ?></textarea></div>
    <div class="fld fld--full"><label>Full content</label><textarea id="content" name="content"><?= $r('content') ?></textarea><?php editor_init('content'); ?></div>
    <div class="fld fld--full"><label>Featured image</label>
      <?php if($row['image']??''):?><div style="margin-bottom:8px"><img src="<?= $r('image')?>" style="height:80px;border-radius:8px"></div><?php endif;?>
      <input type="file" name="image_file" accept="image/*">
    </div>
    <div class="fld"><label>Status</label><select name="status"><option value="1" <?= ($row['status']??1)?'selected':'' ?>>Active</option><option value="0" <?= isset($row['status'])&&!$row['status']?'selected':'' ?>>Hidden</option></select></div>
  </div></div></div>

  <div class="panel"><div class="panel__head"><h2>SEO</h2></div><div class="panel__body">
    <div class="fld"><label>Meta title</label><input name="seo_title" value="<?= $r('seo_title') ?>"></div>
    <div class="fld"><label>Meta description</label><textarea name="seo_desc"><?= $r('seo_desc') ?></textarea></div>
    <div class="fld"><label>Keywords</label><input name="seo_keywords" value="<?= $r('seo_keywords') ?>"></div>
  </div></div>

  <div class="form-actions"><button class="btn">Save Service</button><a class="btn btn--ghost" href="/admin/services.php">Cancel</a></div>
</form>
<?php admin_foot(); ?>
