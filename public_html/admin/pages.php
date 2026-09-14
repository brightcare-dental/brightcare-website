<?php
require __DIR__ . '/inc/layout.php';
require __DIR__ . '/inc/helpers.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  csrf_check(); $id=(int)($_POST['id']??0);
  if (($_POST['do']??'')==='delete'){ db()->prepare('DELETE FROM pages WHERE id=?')->execute([$id]); flash('Page deleted.'); }
  redirect('/admin/pages.php');
}
$rows=q('SELECT * FROM pages ORDER BY id');
admin_head('Pages');
?>
<div class="panel"><div class="panel__head"><h2>Pages (<?= count($rows) ?>)</h2><a class="btn" href="/admin/page-edit.php"><?= adm_icon('file') ?> Add Page</a></div>
  <div class="panel__body" style="padding:0"><table class="tbl">
    <tr><th>Title</th><th>Slug</th><th>SEO Title</th><th>Status</th><th style="width:150px">Actions</th></tr>
    <?php foreach($rows as $p):?><tr>
      <td><strong><?= e($p['title'])?></strong></td><td><code>/<?= e($p['slug'])?></code></td>
      <td><small><?= e($p['seo_title']?:'—')?></small></td><td><?= status_badge((int)$p['status'])?></td>
      <td><div class="actions">
        <a class="btn btn--ghost btn--sm" href="/admin/page-edit.php?id=<?= $p['id']?>">Edit</a>
        <form method="post" style="display:inline"><?= csrf_field()?><input type="hidden" name="id" value="<?= $p['id']?>"><input type="hidden" name="do" value="delete"><button class="btn btn--danger btn--sm" data-confirm="Delete this page?">Del</button></form>
      </div></td>
    </tr><?php endforeach;?>
  </table></div>
</div>
<?php admin_foot(); ?>
