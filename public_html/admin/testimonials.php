<?php
require __DIR__ . '/inc/layout.php';
require __DIR__ . '/inc/helpers.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  csrf_check();
  $do=$_POST['do']??'save'; $id=(int)($_POST['id']??0);
  try {
    if ($do==='delete')     { db()->prepare('DELETE FROM testimonials WHERE id=?')->execute([$id]); flash('Review deleted.'); }
    elseif ($do==='toggle') { db()->prepare('UPDATE testimonials SET status=1-status WHERE id=?')->execute([$id]); flash('Status updated.'); }
    else {
      $name=trim($_POST['name']??''); if($name==='') throw new RuntimeException('Name required.');
      $d=[$name, trim($_POST['text']??''), max(1,min(5,(int)($_POST['rating']??5))), (int)($_POST['sort']??0), (int)($_POST['status']??1)];
      $img=upload_file('photo_file','image');
      if($id){ $sql='UPDATE testimonials SET name=?,text=?,rating=?,sort=?,status=?'.($img?',photo=?':'').' WHERE id=?'; if($img)$d[]=$img['url']; $d[]=$id; db()->prepare($sql)->execute($d); flash('Review updated.'); }
      else { $cols='name,text,rating,sort,status'.($img?',photo':''); $ph='?,?,?,?,?'.($img?',?':''); if($img)$d[]=$img['url']; db()->prepare("INSERT INTO testimonials($cols) VALUES($ph)")->execute($d); flash('Review added.'); }
    }
  } catch(Throwable $ex){ flash('Error: '.$ex->getMessage()); }
  redirect('/admin/testimonials.php');
}

$edit=($eid=(int)($_GET['edit']??0))?q1('SELECT * FROM testimonials WHERE id=?',[$eid]):null;
$rows=q('SELECT * FROM testimonials ORDER BY sort,id');
$r=fn($k,$d='')=>e($edit[$k]??$d);
admin_head('Testimonials');
?>
<div class="panel"><div class="panel__head"><h2><?= $edit?'Edit Review':'Add Review' ?></h2><?php if($edit):?><a class="btn btn--ghost btn--sm" href="/admin/testimonials.php">+ New</a><?php endif;?></div>
  <div class="panel__body"><form method="post" enctype="multipart/form-data"><?= csrf_field() ?>
    <input type="hidden" name="id" value="<?= $edit['id']??'' ?>">
    <div class="form-grid">
      <div class="fld"><label>Patient Name</label><input name="name" value="<?= $r('name') ?>" required></div>
      <div class="fld"><label>Rating (1–5)</label><input type="number" min="1" max="5" name="rating" value="<?= $r('rating','5') ?>"></div>
      <div class="fld fld--full"><label>Review text</label><textarea name="text"><?= $r('text') ?></textarea></div>
      <div class="fld"><label>Photo (optional)</label><?php if($edit['photo']??''):?><div style="margin-bottom:8px"><img src="<?= $r('photo')?>" class="thumb"></div><?php endif;?><input type="file" name="photo_file" accept="image/*"></div>
      <div class="fld"><label>Sort</label><input type="number" name="sort" value="<?= $r('sort','0') ?>"></div>
    </div>
    <div class="form-actions"><button class="btn">Save Review</button></div>
  </form></div>
</div>

<div class="panel"><div class="panel__head"><h2>Reviews (<?= count($rows) ?>)</h2></div>
  <div class="panel__body" style="padding:0"><table class="tbl">
    <tr><th>Name</th><th>Rating</th><th>Text</th><th>Status</th><th style="width:160px">Actions</th></tr>
    <?php foreach($rows as $t):?><tr>
      <td><strong><?= e($t['name'])?></strong></td><td><?= str_repeat('★',(int)$t['rating'])?></td>
      <td><small><?= e(mb_strimwidth($t['text'],0,70,'…'))?></small></td><td><?= status_badge((int)$t['status'])?></td>
      <td><div class="actions">
        <a class="btn btn--ghost btn--sm" href="?edit=<?= $t['id']?>">Edit</a>
        <form method="post" style="display:inline"><?= csrf_field()?><input type="hidden" name="id" value="<?= $t['id']?>"><input type="hidden" name="do" value="toggle"><button class="btn btn--ghost btn--sm"><?= $t['status']?'Hide':'Show'?></button></form>
        <form method="post" style="display:inline"><?= csrf_field()?><input type="hidden" name="id" value="<?= $t['id']?>"><input type="hidden" name="do" value="delete"><button class="btn btn--danger btn--sm" data-confirm="Delete?">Del</button></form>
      </div></td>
    </tr><?php endforeach;?>
  </table></div>
</div>
<?php admin_foot(); ?>
