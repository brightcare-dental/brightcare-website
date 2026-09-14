<?php
require __DIR__ . '/inc/layout.php';
require __DIR__ . '/inc/helpers.php';

$id=(int)($_GET['id']??0);
$row=$id?q1('SELECT * FROM posts WHERE id=?',[$id]):null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  csrf_check();
  try {
    $title=trim($_POST['title']??''); if($title==='') throw new RuntimeException('Title required.');
    $pub = trim($_POST['published_at']??''); $pub = $pub!==''? $pub : date('Y-m-d H:i:s');
    $d=[slugify($_POST['slug']?:$title),$title,trim($_POST['excerpt']??''),$_POST['content']??'',trim($_POST['category']?:'General'),trim($_POST['seo_title']??''),trim($_POST['seo_desc']??''),(int)($_POST['status']??1),$pub];
    $img=upload_file('cover_file','image');
    if($id){ $sql='UPDATE posts SET slug=?,title=?,excerpt=?,content=?,category=?,seo_title=?,seo_desc=?,status=?,published_at=?'.($img?',cover=?':'').' WHERE id=?'; if($img)$d[]=$img['url']; $d[]=$id; db()->prepare($sql)->execute($d); flash('Post updated.'); }
    else { $cols='slug,title,excerpt,content,category,seo_title,seo_desc,status,published_at'.($img?',cover':''); $ph='?,?,?,?,?,?,?,?,?'.($img?',?':''); if($img)$d[]=$img['url']; db()->prepare("INSERT INTO posts($cols) VALUES($ph)")->execute($d); flash('Post created.'); }
    redirect('/admin/posts.php');
  } catch(Throwable $ex){ flash('Error: '.$ex->getMessage()); redirect($_SERVER['REQUEST_URI']); }
}
$r=fn($k,$d='')=>e($row[$k]??$d);
admin_head($id?'Edit Post':'Add Post'); editor_assets();
?>
<form method="post" enctype="multipart/form-data"><?= csrf_field() ?>
  <div class="panel"><div class="panel__body"><div class="form-grid">
    <div class="fld"><label>Title</label><input id="title" name="title" data-slug-source="#slug" value="<?= $r('title') ?>" required></div>
    <div class="fld"><label>Slug</label><input id="slug" name="slug" value="<?= $r('slug') ?>" placeholder="auto from title"></div>
    <div class="fld"><label>Category</label><input name="category" value="<?= $r('category','General') ?>"></div>
    <div class="fld"><label>Publish date</label><input type="datetime-local" name="published_at" value="<?= $row['published_at']? e(date('Y-m-d\TH:i',strtotime($row['published_at']))):'' ?>"></div>
    <div class="fld fld--full"><label>Excerpt</label><textarea name="excerpt"><?= $r('excerpt') ?></textarea></div>
    <div class="fld fld--full"><label>Content</label><textarea id="content" name="content"><?= $r('content') ?></textarea><?php editor_init('content'); ?></div>
    <div class="fld fld--full"><label>Cover image</label><?php if($row['cover']??''):?><div style="margin-bottom:8px"><img src="<?= $r('cover')?>" style="height:80px;border-radius:8px"></div><?php endif;?><input type="file" name="cover_file" accept="image/*"></div>
    <div class="fld"><label>Status</label><select name="status"><option value="1" <?= ($row['status']??1)?'selected':'' ?>>Published</option><option value="0" <?= isset($row['status'])&&!$row['status']?'selected':'' ?>>Draft</option></select></div>
  </div></div></div>
  <div class="panel"><div class="panel__head"><h2>SEO</h2></div><div class="panel__body">
    <div class="fld"><label>Meta title</label><input name="seo_title" value="<?= $r('seo_title') ?>"></div>
    <div class="fld"><label>Meta description</label><textarea name="seo_desc"><?= $r('seo_desc') ?></textarea></div>
  </div></div>
  <div class="form-actions"><button class="btn">Save Post</button><a class="btn btn--ghost" href="/admin/posts.php">Cancel</a></div>
</form>
<?php admin_foot(); ?>
