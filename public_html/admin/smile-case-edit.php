<?php
require __DIR__ . '/inc/layout.php';
require __DIR__ . '/inc/helpers.php';

$id  = (int)($_GET['id'] ?? 0);
$row = $id ? q1('SELECT * FROM smile_cases WHERE id=?', [$id]) : null;
$types = ['before-after'=>'Before & After','makeover'=>'Smile Makeover','case-study'=>'Case Study'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  csrf_check();
  try {
    $title = trim($_POST['title'] ?? '');
    if ($title === '') throw new RuntimeException('Title is required.');
    $type = in_array($_POST['type']??'',array_keys($types),true) ? $_POST['type'] : 'before-after';
    $slug = slugify($_POST['slug'] ?: $title);
    $data = [$type,$slug,$title,trim($_POST['subtitle']??''),trim($_POST['service']??''),$_POST['content']??'',trim($_POST['seo_title']??''),trim($_POST['seo_desc']??''),(int)($_POST['sort']??0),(int)($_POST['status']??1)];
    // optional image uploads
    $imgCols=''; $imgVals=[];
    foreach (['before_img'=>'before_file','after_img'=>'after_file','image'=>'image_file'] as $col=>$field) {
      $up = upload_file($field,'image');
      if ($up){ $imgCols .= ",$col=?"; $imgVals[]=$up['url']; }
    }
    if ($id) {
      $sql='UPDATE smile_cases SET type=?,slug=?,title=?,subtitle=?,service=?,content=?,seo_title=?,seo_desc=?,sort=?,status=?'.$imgCols.' WHERE id=?';
      db()->prepare($sql)->execute(array_merge($data,$imgVals,[$id]));
      flash('Item updated.');
    } else {
      $cols='type,slug,title,subtitle,service,content,seo_title,seo_desc,sort,status'; $ph='?,?,?,?,?,?,?,?,?,?';
      // include any uploaded images as extra columns
      $extraCols=str_replace('=?','',$imgCols); // ",before_img,after_img"
      $sql="INSERT INTO smile_cases($cols$extraCols) VALUES($ph".str_repeat(',?',count($imgVals)).")";
      db()->prepare($sql)->execute(array_merge($data,$imgVals));
      flash('Item created.');
    }
    redirect('/admin/smile-cases.php');
  } catch (Throwable $ex){ flash('Error: '.$ex->getMessage()); redirect($_SERVER['REQUEST_URI']); }
}
$r = fn($k,$d='')=>e($row[$k] ?? $d);
$cur = $row['type'] ?? 'before-after';
admin_head($id ? 'Edit Smile Gallery Item' : 'Add Smile Gallery Item'); editor_assets();
?>
<form method="post" enctype="multipart/form-data">
  <?= csrf_field() ?>
  <div class="panel"><div class="panel__body"><div class="form-grid">
    <div class="fld"><label>Type</label><select name="type" id="caseType">
      <?php foreach($types as $tv=>$tl): ?><option value="<?= $tv ?>" <?= $cur===$tv?'selected':'' ?>><?= $tl ?></option><?php endforeach; ?>
    </select></div>
    <div class="fld"><label>Service / Tag</label><input name="service" value="<?= $r('service') ?>" placeholder="e.g. Clear Aligners"></div>
    <div class="fld"><label>Title</label><input id="title" name="title" data-slug-source="#slug" value="<?= $r('title') ?>" required></div>
    <div class="fld"><label>Slug (URL)</label><input id="slug" name="slug" value="<?= $r('slug') ?>" placeholder="auto from title"></div>
    <div class="fld fld--full"><label>Short caption (optional)</label><input name="subtitle" value="<?= $r('subtitle') ?>"></div>

    <div class="fld js-ba" data-types="before-after"><label>Before image</label>
      <?php if($row['before_img']??''):?><div style="margin-bottom:8px"><img src="<?= $r('before_img')?>" class="thumb"></div><?php endif;?>
      <input type="file" name="before_file" accept="image/*"></div>
    <div class="fld js-ba" data-types="before-after"><label>After image</label>
      <?php if($row['after_img']??''):?><div style="margin-bottom:8px"><img src="<?= $r('after_img')?>" class="thumb"></div><?php endif;?>
      <input type="file" name="after_file" accept="image/*"></div>

    <div class="fld fld--full js-single" data-types="makeover case-study"><label>Main image</label>
      <?php if($row['image']??''):?><div style="margin-bottom:8px"><img src="<?= $r('image')?>" style="height:80px;border-radius:8px"></div><?php endif;?>
      <input type="file" name="image_file" accept="image/*"></div>

    <div class="fld fld--full"><label>Detailed content (shown on the detail page)</label><textarea id="content" name="content"><?= $r('content') ?></textarea><?php editor_init('content'); ?></div>
    <div class="fld"><label>Sort order</label><input type="number" name="sort" value="<?= $r('sort','0') ?>"></div>
    <div class="fld"><label>Status</label><select name="status"><option value="1" <?= ($row['status']??1)?'selected':'' ?>>Active</option><option value="0" <?= isset($row['status'])&&!$row['status']?'selected':'' ?>>Hidden</option></select></div>
  </div></div></div>

  <div class="panel"><div class="panel__head"><h2>SEO</h2></div><div class="panel__body">
    <div class="fld"><label>Meta title</label><input name="seo_title" value="<?= $r('seo_title') ?>"></div>
    <div class="fld"><label>Meta description</label><textarea name="seo_desc"><?= $r('seo_desc') ?></textarea></div>
  </div></div>

  <div class="form-actions"><button class="btn">Save Item</button><a class="btn btn--ghost" href="/admin/smile-cases.php">Cancel</a></div>
</form>
<script>
(function(){
  var sel=document.getElementById('caseType');
  function sync(){
    var t=sel.value;
    document.querySelectorAll('[data-types]').forEach(function(el){
      el.style.display = el.getAttribute('data-types').split(' ').indexOf(t)>-1 ? '' : 'none';
    });
  }
  sel.addEventListener('change',sync); sync();
})();
</script>
<?php admin_foot(); ?>
