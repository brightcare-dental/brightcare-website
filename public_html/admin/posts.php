<?php
require __DIR__ . '/inc/layout.php';
require __DIR__ . '/inc/helpers.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  csrf_check(); $id=(int)($_POST['id']??0);
  if(($_POST['do']??'')==='delete'){ db()->prepare('DELETE FROM posts WHERE id=?')->execute([$id]); flash('Post deleted.'); }
  if(($_POST['do']??'')==='toggle'){ db()->prepare('UPDATE posts SET status=1-status WHERE id=?')->execute([$id]); flash('Status updated.'); }
  redirect('/admin/posts.php');
}
$rows=q('SELECT * FROM posts ORDER BY COALESCE(published_at,created_at) DESC, id DESC');
admin_head('Blog / News');
?>
<div class="panel"><div class="panel__head"><h2>Posts (<?= count($rows) ?>)</h2><a class="btn" href="/admin/post-edit.php"><?= adm_icon('news') ?> Add Post</a></div>
  <div class="panel__body" style="padding:0"><table class="tbl">
    <tr><th>Cover</th><th>Title</th><th>Category</th><th>Published</th><th>Status</th><th style="width:160px">Actions</th></tr>
    <?php foreach($rows as $p):?><tr>
      <td><?php if($p['cover']):?><img class="thumb" src="<?= e($p['cover'])?>"><?php else:?>—<?php endif;?></td>
      <td><strong><?= e($p['title'])?></strong></td><td><?= e($p['category'])?></td>
      <td><?= e($p['published_at']?:'—')?></td><td><?= status_badge((int)$p['status'])?></td>
      <td><div class="actions">
        <a class="btn btn--ghost btn--sm" href="/admin/post-edit.php?id=<?= $p['id']?>">Edit</a>
        <form method="post" style="display:inline"><?= csrf_field()?><input type="hidden" name="id" value="<?= $p['id']?>"><input type="hidden" name="do" value="toggle"><button class="btn btn--ghost btn--sm"><?= $p['status']?'Hide':'Show'?></button></form>
        <form method="post" style="display:inline"><?= csrf_field()?><input type="hidden" name="id" value="<?= $p['id']?>"><input type="hidden" name="do" value="delete"><button class="btn btn--danger btn--sm" data-confirm="Delete this post?">Del</button></form>
      </div></td>
    </tr><?php endforeach;?>
  </table></div>
</div>
<?php admin_foot(); ?>
