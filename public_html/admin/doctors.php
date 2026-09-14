<?php
require __DIR__ . '/inc/layout.php';
require __DIR__ . '/inc/helpers.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  csrf_check();
  $do = $_POST['do'] ?? 'save'; $id = (int)($_POST['id'] ?? 0);
  try {
    if ($do === 'delete')      { db()->prepare('DELETE FROM doctors WHERE id=?')->execute([$id]); flash('Doctor deleted.'); }
    elseif ($do === 'toggle')  { db()->prepare('UPDATE doctors SET status=1-status WHERE id=?')->execute([$id]); flash('Status updated.'); }
    else {
      $name = trim($_POST['name'] ?? ''); if ($name==='') throw new RuntimeException('Name required.');
      $cat = in_array($_POST['category']??'consultant',['main','duty','consultant'],true) ? $_POST['category'] : 'consultant';
      $d = [slugify($_POST['slug'] ?: $name), $name, trim($_POST['role']??''), $cat, trim($_POST['bio']??''), (int)($_POST['sort']??0), (int)($_POST['status']??1)];
      $img = upload_file('photo_file','image');
      if ($id) {
        $sql='UPDATE doctors SET slug=?,name=?,role=?,category=?,bio=?,sort=?,status=?'.($img?',photo=?':'').' WHERE id=?';
        if($img)$d[]=$img['url']; $d[]=$id; db()->prepare($sql)->execute($d); flash('Doctor updated.');
      } else {
        $cols='slug,name,role,category,bio,sort,status'.($img?',photo':''); $ph='?,?,?,?,?,?,?'.($img?',?':'');
        if($img)$d[]=$img['url']; db()->prepare("INSERT INTO doctors($cols) VALUES($ph)")->execute($d); flash('Doctor added.');
      }
    }
  } catch (Throwable $ex){ flash('Error: '.$ex->getMessage()); }
  redirect('/admin/doctors.php');
}

$edit = ($eid=(int)($_GET['edit']??0)) ? q1('SELECT * FROM doctors WHERE id=?',[$eid]) : null;
$rows = q('SELECT * FROM doctors ORDER BY sort,id');
$r = fn($k,$d='')=>e($edit[$k]??$d);
admin_head('Doctors');
?>
<div class="panel"><div class="panel__head"><h2><?= $edit?'Edit Doctor':'Add Doctor' ?></h2><?php if($edit):?><a class="btn btn--ghost btn--sm" href="/admin/doctors.php">+ New</a><?php endif;?></div>
  <div class="panel__body"><form method="post" enctype="multipart/form-data"><?= csrf_field() ?>
    <input type="hidden" name="id" value="<?= $edit['id']??'' ?>">
    <div class="form-grid">
      <div class="fld"><label>Name</label><input name="name" value="<?= $r('name') ?>" required></div>
      <div class="fld"><label>Role / Qualifications</label><input name="role" value="<?= $r('role') ?>" placeholder="e.g. Orthodontist · BDS, MDS"></div>
      <div class="fld"><label>Team Category</label><select name="category">
        <?php $cc=$edit['category']??'consultant'; foreach(['main'=>'Senior Doctor (shown on homepage)','duty'=>'Duty Doctor','consultant'=>'Consultant Doctor'] as $cv=>$cl): ?>
          <option value="<?= $cv ?>" <?= $cc===$cv?'selected':'' ?>><?= $cl ?></option>
        <?php endforeach; ?>
        </select><div class="hint">Senior doctors appear on the homepage &amp; lead the Doctors page. Duty &amp; Consultant doctors show in their own sections.</div></div>
      <div class="fld fld--full"><label>Short bio</label><textarea name="bio"><?= $r('bio') ?></textarea></div>
      <div class="fld"><label>Photo</label><?php if($edit['photo']??''):?><div style="margin-bottom:8px"><img src="<?= $r('photo')?>" class="thumb"></div><?php endif;?><input type="file" name="photo_file" accept="image/*"></div>
      <div class="fld"><label>Sort</label><input type="number" name="sort" value="<?= $r('sort','0') ?>"></div>
    </div>
    <div class="form-actions"><button class="btn">Save Doctor</button></div>
  </form></div>
</div>

<div class="panel"><div class="panel__head"><h2>Doctors (<?= count($rows) ?>)</h2></div>
  <div class="panel__body" style="padding:0"><table class="tbl">
    <tr><th>Photo</th><th>Name</th><th>Category</th><th>Role</th><th>Status</th><th style="width:160px">Actions</th></tr>
    <?php $catLabels=['main'=>'Senior','duty'=>'Duty','consultant'=>'Consultant']; foreach($rows as $d):?><tr>
      <td><?php if($d['photo']):?><img class="thumb" src="<?= e($d['photo'])?>"><?php else:?>—<?php endif;?></td>
      <td><strong><?= e($d['name'])?></strong></td>
      <td><span class="badge badge--new"><?= e($catLabels[$d['category']??'consultant'] ?? 'Consultant') ?></span></td>
      <td><?= e($d['role'])?></td><td><?= status_badge((int)$d['status'])?></td>
      <td><div class="actions">
        <a class="btn btn--ghost btn--sm" href="?edit=<?= $d['id']?>">Edit</a>
        <form method="post" style="display:inline"><?= csrf_field()?><input type="hidden" name="id" value="<?= $d['id']?>"><input type="hidden" name="do" value="toggle"><button class="btn btn--ghost btn--sm"><?= $d['status']?'Hide':'Show'?></button></form>
        <form method="post" style="display:inline"><?= csrf_field()?><input type="hidden" name="id" value="<?= $d['id']?>"><input type="hidden" name="do" value="delete"><button class="btn btn--danger btn--sm" data-confirm="Delete?">Del</button></form>
      </div></td>
    </tr><?php endforeach;?>
  </table></div>
</div>
<?php admin_foot(); ?>
