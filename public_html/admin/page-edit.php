<?php
require __DIR__ . '/inc/layout.php';
require __DIR__ . '/inc/helpers.php';

$id=(int)($_GET['id']??0);
$row=$id?q1('SELECT * FROM pages WHERE id=?',[$id]):null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  csrf_check();
  try {
    $title=trim($_POST['title']??''); if($title==='') throw new RuntimeException('Title required.');
    $d=[slugify($_POST['slug']?:$title),$title,$_POST['content']??'',(int)($_POST['status']??1),trim($_POST['seo_title']??''),trim($_POST['seo_desc']??''),trim($_POST['seo_keywords']??'')];
    $img=upload_file('hero_file','image');
    if($id){ $sql='UPDATE pages SET slug=?,title=?,content=?,status=?,seo_title=?,seo_desc=?,seo_keywords=?'.($img?',hero_image=?':'').' WHERE id=?'; if($img)$d[]=$img['url']; $d[]=$id; db()->prepare($sql)->execute($d); flash('Page updated.'); }
    else { $cols='slug,title,content,status,seo_title,seo_desc,seo_keywords'.($img?',hero_image':''); $ph='?,?,?,?,?,?,?'.($img?',?':''); if($img)$d[]=$img['url']; db()->prepare("INSERT INTO pages($cols) VALUES($ph)")->execute($d); flash('Page created.'); }
    redirect('/admin/pages.php');
  } catch(Throwable $ex){ flash('Error: '.$ex->getMessage()); redirect($_SERVER['REQUEST_URI']); }
}
$r=fn($k,$d='')=>e($row[$k]??$d);
admin_head($id?'Edit Page':'Add Page'); editor_assets();
?>
<form method="post" enctype="multipart/form-data"><?= csrf_field() ?>
  <div class="panel"><div class="panel__body"><div class="form-grid">
    <div class="fld"><label>Title</label><input id="title" name="title" data-slug-source="#slug" value="<?= $r('title') ?>" required></div>
    <div class="fld"><label>Slug (URL)</label><input id="slug" name="slug" value="<?= $r('slug') ?>" placeholder="auto from title"></div>
    <div class="fld fld--full"><label>Content</label><textarea id="content" name="content"><?= $r('content') ?></textarea><?php editor_init('content'); ?></div>
    <div class="fld fld--full"><label>Hero image</label><?php if($row['hero_image']??''):?><div style="margin-bottom:8px"><img src="<?= $r('hero_image')?>" style="height:80px;border-radius:8px"></div><?php endif;?><input type="file" name="hero_file" accept="image/*"></div>
    <div class="fld"><label>Status</label><select name="status"><option value="1" <?= ($row['status']??1)?'selected':'' ?>>Active</option><option value="0" <?= isset($row['status'])&&!$row['status']?'selected':'' ?>>Hidden</option></select></div>
  </div></div></div>
  <div class="panel"><div class="panel__head"><h2>SEO</h2></div><div class="panel__body">
    <div class="fld"><label>Meta title</label><input name="seo_title" value="<?= $r('seo_title') ?>"></div>
    <div class="fld"><label>Meta description</label><textarea name="seo_desc"><?= $r('seo_desc') ?></textarea></div>
    <div class="fld"><label>Keywords</label><input name="seo_keywords" value="<?= $r('seo_keywords') ?>"></div>
  </div></div>
  <div class="form-actions"><button class="btn">Save Page</button><a class="btn btn--ghost" href="/admin/pages.php">Cancel</a></div>
</form>
<?php admin_foot(); ?>
