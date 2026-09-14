<?php
require __DIR__ . '/inc/layout.php';
require __DIR__ . '/inc/helpers.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  csrf_check();
  $do=$_POST['do']??'save'; $id=(int)($_POST['id']??0);
  try {
    if ($do==='delete')     { db()->prepare('DELETE FROM branches WHERE id=?')->execute([$id]); flash('Branch deleted.'); }
    elseif ($do==='toggle') { db()->prepare('UPDATE branches SET status=1-status WHERE id=?')->execute([$id]); flash('Status updated.'); }
    else {
      $name=trim($_POST['name']??''); if($name==='') throw new RuntimeException('Branch name required.');
      $main=(int)($_POST['is_main']??0);
      $d=[$name,trim($_POST['area']??''),trim($_POST['city']?:'Trivandrum'),trim($_POST['address']??''),trim($_POST['phone']??''),trim($_POST['map_url']??''),trim($_POST['map_embed']??''),trim($_POST['hours']??''),$main,(int)($_POST['sort']??0),(int)($_POST['status']??1)];
      if($id){ $d[]=$id; db()->prepare('UPDATE branches SET name=?,area=?,city=?,address=?,phone=?,map_url=?,map_embed=?,hours=?,is_main=?,sort=?,status=? WHERE id=?')->execute($d); flash('Branch updated.'); }
      else   { db()->prepare('INSERT INTO branches(name,area,city,address,phone,map_url,map_embed,hours,is_main,sort,status) VALUES(?,?,?,?,?,?,?,?,?,?,?)')->execute($d); flash('Branch added.'); }
      if($main){ db()->prepare('UPDATE branches SET is_main=0 WHERE id<>?')->execute([$id?:db()->lastInsertId()]); }
    }
  } catch(Throwable $ex){ flash('Error: '.$ex->getMessage()); }
  redirect('/admin/branches.php');
}

$edit=($eid=(int)($_GET['edit']??0))?q1('SELECT * FROM branches WHERE id=?',[$eid]):null;
$rows=q('SELECT * FROM branches ORDER BY is_main DESC, sort, id');
$r=fn($k,$d='')=>e($edit[$k]??$d);
admin_head('Branches');
?>
<div class="panel"><div class="panel__head"><h2><?= $edit?'Edit Branch':'Add Branch' ?></h2><?php if($edit):?><a class="btn btn--ghost btn--sm" href="/admin/branches.php">+ New</a><?php endif;?></div>
  <div class="panel__body"><form method="post"><?= csrf_field() ?>
    <input type="hidden" name="id" value="<?= $edit['id']??'' ?>">
    <div class="form-grid">
      <div class="fld"><label>Branch Name</label><input name="name" value="<?= $r('name') ?>" required placeholder="e.g. Mangalapuram"></div>
      <div class="fld"><label>Area / Locality</label><input name="area" value="<?= $r('area') ?>"></div>
      <div class="fld"><label>City</label><input name="city" value="<?= $r('city','Trivandrum') ?>"></div>
      <div class="fld"><label>Phone</label><input name="phone" value="<?= $r('phone') ?>"></div>
      <div class="fld fld--full"><label>Full Address</label><input name="address" value="<?= $r('address') ?>"></div>
      <div class="fld"><label>Opening Hours</label><input name="hours" value="<?= $r('hours') ?>" placeholder="Mon–Sat 9AM–8PM"></div>
      <div class="fld"><label>Google Maps link</label><input name="map_url" value="<?= $r('map_url') ?>"></div>
      <div class="fld fld--full"><label>Google Maps embed (iframe)</label><textarea name="map_embed"><?= $r('map_embed') ?></textarea><div class="hint">Optional — paste the &lt;iframe&gt; from Google Maps → Share → Embed.</div></div>
      <div class="fld"><label>Sort</label><input type="number" name="sort" value="<?= $r('sort','0') ?>"></div>
      <div class="fld"><label>Main branch?</label><select name="is_main"><option value="0" <?= empty($edit['is_main'])?'selected':'' ?>>No</option><option value="1" <?= !empty($edit['is_main'])?'selected':'' ?>>Yes (primary)</option></select></div>
    </div>
    <div class="form-actions"><button class="btn">Save Branch</button></div>
  </form></div>
</div>

<div class="panel"><div class="panel__head"><h2>Branches (<?= count($rows) ?>)</h2></div>
  <div class="panel__body" style="padding:0"><table class="tbl">
    <tr><th>Name</th><th>Area</th><th>Phone</th><th>Main</th><th>Status</th><th style="width:160px">Actions</th></tr>
    <?php foreach($rows as $b):?><tr>
      <td><strong><?= e($b['name'])?></strong></td><td><?= e($b['area'])?>, <?= e($b['city'])?></td><td><?= e($b['phone']?:'—')?></td>
      <td><?= $b['is_main']?'<span class="badge badge--on">Main</span>':'' ?></td><td><?= status_badge((int)$b['status'])?></td>
      <td><div class="actions">
        <a class="btn btn--ghost btn--sm" href="?edit=<?= $b['id']?>">Edit</a>
        <form method="post" style="display:inline"><?= csrf_field()?><input type="hidden" name="id" value="<?= $b['id']?>"><input type="hidden" name="do" value="toggle"><button class="btn btn--ghost btn--sm"><?= $b['status']?'Hide':'Show'?></button></form>
        <form method="post" style="display:inline"><?= csrf_field()?><input type="hidden" name="id" value="<?= $b['id']?>"><input type="hidden" name="do" value="delete"><button class="btn btn--danger btn--sm" data-confirm="Delete branch?">Del</button></form>
      </div></td>
    </tr><?php endforeach;?>
  </table></div>
</div>
<?php admin_foot(); ?>
