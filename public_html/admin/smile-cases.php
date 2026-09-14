<?php
require __DIR__ . '/inc/layout.php';
require __DIR__ . '/inc/helpers.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  csrf_check(); $id=(int)($_POST['id']??0);
  if(($_POST['do']??'')==='delete'){ db()->prepare('DELETE FROM smile_cases WHERE id=?')->execute([$id]); flash('Item deleted.'); }
  if(($_POST['do']??'')==='toggle'){ db()->prepare('UPDATE smile_cases SET status=1-status WHERE id=?')->execute([$id]); flash('Status updated.'); }
  redirect('/admin/smile-cases.php');
}
$rows = q('SELECT * FROM smile_cases ORDER BY type, sort, id');
$typeLabel = ['before-after'=>'Before &amp; After','makeover'=>'Smile Makeover','case-study'=>'Case Study'];
admin_head('Smile Gallery');
?>
<div class="panel">
  <div class="panel__head"><h2>Smile Gallery (<?= count($rows) ?>)</h2><a class="btn" href="/admin/smile-case-edit.php"><?= adm_icon('sparkle') ?> Add Item</a></div>
  <div class="panel__body" style="padding:0">
    <table class="tbl">
      <tr><th>Preview</th><th>Type</th><th>Title</th><th>Service</th><th>Status</th><th style="width:170px">Actions</th></tr>
      <?php foreach ($rows as $r): $thumb = $r['type']==='before-after' ? ($r['after_img']?:$r['before_img']) : $r['image']; ?>
      <tr>
        <td><?php if($thumb):?><img class="thumb" src="<?= e($thumb) ?>"><?php else:?>—<?php endif;?></td>
        <td><span class="badge badge--new"><?= $typeLabel[$r['type']] ?? e($r['type']) ?></span></td>
        <td><strong><?= e($r['title']) ?></strong></td>
        <td><?= e($r['service']) ?></td>
        <td><?= status_badge((int)$r['status']) ?></td>
        <td><div class="actions">
          <a class="btn btn--ghost btn--sm" href="/admin/smile-case-edit.php?id=<?= $r['id'] ?>">Edit</a>
          <form method="post" style="display:inline"><?= csrf_field()?><input type="hidden" name="id" value="<?= $r['id']?>"><input type="hidden" name="do" value="toggle"><button class="btn btn--ghost btn--sm"><?= $r['status']?'Hide':'Show'?></button></form>
          <form method="post" style="display:inline"><?= csrf_field()?><input type="hidden" name="id" value="<?= $r['id']?>"><input type="hidden" name="do" value="delete"><button class="btn btn--danger btn--sm" data-confirm="Delete this item?">Del</button></form>
        </div></td>
      </tr>
      <?php endforeach; ?>
    </table>
  </div>
</div>
<?php admin_foot(); ?>
