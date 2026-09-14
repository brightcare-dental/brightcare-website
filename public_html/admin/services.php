<?php
require __DIR__ . '/inc/layout.php';
require __DIR__ . '/inc/helpers.php';

// actions: delete / toggle
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  csrf_check();
  $id = (int)($_POST['id'] ?? 0);
  if (($_POST['do'] ?? '') === 'delete') { db()->prepare('DELETE FROM services WHERE id=?')->execute([$id]); flash('Service deleted.'); }
  if (($_POST['do'] ?? '') === 'toggle') { db()->prepare('UPDATE services SET status=1-status WHERE id=?')->execute([$id]); flash('Status updated.'); }
  redirect('/admin/services.php');
}

$rows = q('SELECT * FROM services ORDER BY sort, id');
admin_head('Services');
?>
<div class="panel">
  <div class="panel__head"><h2>Services (<?= count($rows) ?>)</h2><a class="btn" href="/admin/service-edit.php"><?= adm_icon('file') ?> Add Service</a></div>
  <div class="panel__body" style="padding:0">
    <table class="tbl">
      <tr><th>Image</th><th>Title</th><th>Slug</th><th>Status</th><th style="width:170px">Actions</th></tr>
      <?php foreach ($rows as $r): ?>
      <tr>
        <td><?php if($r['image']):?><img class="thumb" src="<?= e($r['image'])?>"><?php else:?><span style="color:var(--mut)">—</span><?php endif;?></td>
        <td><strong><?= e($r['title']) ?></strong><br><small style="color:var(--mut)"><?= e(mb_strimwidth(strip_tags($r['excerpt']),0,60,'…')) ?></small></td>
        <td><code><?= e($r['slug']) ?></code></td>
        <td><?= status_badge((int)$r['status']) ?></td>
        <td><div class="actions">
          <a class="btn btn--ghost btn--sm" href="/admin/service-edit.php?id=<?= $r['id'] ?>">Edit</a>
          <form method="post" style="display:inline"><?= csrf_field() ?><input type="hidden" name="id" value="<?= $r['id'] ?>"><input type="hidden" name="do" value="toggle"><button class="btn btn--ghost btn--sm"><?= $r['status']?'Hide':'Show' ?></button></form>
          <form method="post" style="display:inline"><?= csrf_field() ?><input type="hidden" name="id" value="<?= $r['id'] ?>"><input type="hidden" name="do" value="delete"><button class="btn btn--danger btn--sm" data-confirm="Delete this service?">Del</button></form>
        </div></td>
      </tr>
      <?php endforeach; ?>
    </table>
  </div>
</div>
<?php admin_foot(); ?>
