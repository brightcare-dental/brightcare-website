<?php
require __DIR__ . '/inc/layout.php';
require __DIR__ . '/inc/helpers.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  csrf_check();
  $do=$_POST['do']??'save'; $id=(int)($_POST['id']??0);
  try {
    if ($do==='delete')     { db()->prepare('DELETE FROM gallery WHERE id=?')->execute([$id]); flash('Item deleted.'); }
    elseif ($do==='toggle') { db()->prepare('UPDATE gallery SET status=1-status WHERE id=?')->execute([$id]); flash('Status updated.'); }
    else {
      $type = ($_POST['type']??'image')==='video'?'video':'image';
      $src  = trim($_POST['src'] ?? '');           // embed/external URL
      $up   = upload_file('file', $type);          // optional uploaded file
      if ($up) $src = $up['url'];
      if ($src==='') throw new RuntimeException('Upload a file or paste a URL.');
      $d=[$type, trim($_POST['category']??'Clinic'), trim($_POST['title']??''), $src, trim($_POST['thumb']??''), (int)($_POST['sort']??0), (int)($_POST['status']??1)];
      if($id){ array_push($d,$id); db()->prepare('UPDATE gallery SET type=?,category=?,title=?,src=?,thumb=?,sort=?,status=? WHERE id=?')->execute($d); flash('Item updated.'); }
      else   { db()->prepare('INSERT INTO gallery(type,category,title,src,thumb,sort,status) VALUES(?,?,?,?,?,?,?)')->execute($d); flash('Item added.'); }
    }
  } catch(Throwable $ex){ flash('Error: '.$ex->getMessage()); }
  redirect('/admin/gallery.php');
}

$edit=($eid=(int)($_GET['edit']??0))?q1('SELECT * FROM gallery WHERE id=?',[$eid]):null;
$rows=q('SELECT * FROM gallery ORDER BY sort,id');
$r=fn($k,$d='')=>e($edit[$k]??$d);
admin_head('Gallery');
?>
<div class="panel"><div class="panel__head"><h2><?= $edit?'Edit Item':'Add Photo / Video' ?></h2><?php if($edit):?><a class="btn btn--ghost btn--sm" href="/admin/gallery.php">+ New</a><?php endif;?></div>
  <div class="panel__body"><form method="post" enctype="multipart/form-data"><?= csrf_field() ?>
    <input type="hidden" name="id" value="<?= $edit['id']??'' ?>">
    <div class="form-grid">
      <div class="fld"><label>Type</label><select name="type"><option value="image" <?= ($edit['type']??'')==='image'?'selected':'' ?>>Image</option><option value="video" <?= ($edit['type']??'')==='video'?'selected':'' ?>>Video</option></select></div>
      <div class="fld"><label>Category</label><input name="category" value="<?= $r('category','Clinic') ?>" placeholder="Clinic / Smile Makeover / Team"></div>
      <div class="fld fld--full"><label>Title (optional)</label><input name="title" value="<?= $r('title') ?>"></div>
      <div class="fld fld--full"><label>Upload file <small style="font-weight:400;color:var(--mut)">(image up to 8MB, video up to 80MB)</small></label>
        <?php if($edit['src']??''):?><div style="margin-bottom:8px"><?php if(($edit['type']??'')==='image'):?><img src="<?= $r('src')?>" class="thumb"><?php else:?><code><?= $r('src')?></code><?php endif;?></div><?php endif;?>
        <input type="file" name="file" accept="image/*,video/mp4,video/webm"></div>
      <div class="fld fld--full"><label>…or paste a URL (YouTube/Vimeo embed, or external image)</label><input name="src" value="<?= $r('src') ?>" placeholder="https://www.youtube.com/embed/..."></div>
      <div class="fld"><label>Sort</label><input type="number" name="sort" value="<?= $r('sort','0') ?>"></div>
    </div>
    <div class="form-actions"><button class="btn">Save Item</button></div>
  </form></div>
</div>

<div class="panel"><div class="panel__head"><h2>Gallery (<?= count($rows) ?>)</h2></div>
  <div class="panel__body" style="padding:0"><table class="tbl">
    <tr><th>Preview</th><th>Type</th><th>Category</th><th>Title</th><th>Status</th><th style="width:160px">Actions</th></tr>
    <?php foreach($rows as $g):?><tr>
      <td><?php if($g['type']==='image'):?><img class="thumb" src="<?= e($g['src'])?>"><?php else:?><span class="badge badge--new">▶ video</span><?php endif;?></td>
      <td><?= e($g['type'])?></td><td><?= e($g['category'])?></td><td><?= e($g['title'])?></td><td><?= status_badge((int)$g['status'])?></td>
      <td><div class="actions">
        <a class="btn btn--ghost btn--sm" href="?edit=<?= $g['id']?>">Edit</a>
        <form method="post" style="display:inline"><?= csrf_field()?><input type="hidden" name="id" value="<?= $g['id']?>"><input type="hidden" name="do" value="toggle"><button class="btn btn--ghost btn--sm"><?= $g['status']?'Hide':'Show'?></button></form>
        <form method="post" style="display:inline"><?= csrf_field()?><input type="hidden" name="id" value="<?= $g['id']?>"><input type="hidden" name="do" value="delete"><button class="btn btn--danger btn--sm" data-confirm="Delete?">Del</button></form>
      </div></td>
    </tr><?php endforeach;?>
  </table></div>
</div>
<?php admin_foot(); ?>
