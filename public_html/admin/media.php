<?php
require __DIR__ . '/inc/layout.php';
require __DIR__ . '/inc/helpers.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  csrf_check();
  try {
    if (($_POST['do']??'')==='delete') {
      $id=(int)($_POST['id']??0);
      if($m=q1('SELECT * FROM media WHERE id=?',[$id])){
        $abs=__DIR__.'/../'.ltrim($m['path'],'/'); // admin/../assets/uploads/...
        if(is_file($abs)) @unlink($abs);
        db()->prepare('DELETE FROM media WHERE id=?')->execute([$id]);
        flash('File deleted.');
      }
    } else {
      if($u=upload_file('file','any')) flash('Uploaded.'); else flash('Choose a file first.');
    }
  } catch(Throwable $ex){ flash('Error: '.$ex->getMessage()); }
  redirect('/admin/media.php');
}
$rows=q('SELECT * FROM media ORDER BY id DESC');
admin_head('Media Library');
?>
<div class="panel"><div class="panel__head"><h2>Upload</h2></div>
  <div class="panel__body"><form method="post" enctype="multipart/form-data" style="display:flex;gap:10px;align-items:center;flex-wrap:wrap"><?= csrf_field() ?>
    <input type="file" name="file" accept="image/*,video/mp4,video/webm" required><button class="btn">Upload</button>
    <span class="hint">Images ≤8MB · Videos ≤80MB</span>
  </form></div>
</div>
<div class="panel"><div class="panel__head"><h2>Library (<?= count($rows) ?>)</h2></div>
  <div class="panel__body">
    <?php if(!$rows):?><p style="color:var(--mut)">No files yet.</p><?php else:?>
    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(150px,1fr));gap:14px">
      <?php foreach($rows as $m):?>
        <div style="border:1px solid var(--line);border-radius:10px;overflow:hidden;background:#fafcfc">
          <?php if(str_starts_with($m['mime'],'image/')):?>
            <img src="<?= e($m['path'])?>" style="width:100%;height:110px;object-fit:cover">
          <?php else:?>
            <div style="height:110px;display:grid;place-items:center;background:#eef3f3;color:var(--mut)">▶ video</div>
          <?php endif;?>
          <div style="padding:8px;font-size:.72rem;word-break:break-all;color:var(--mut)"><?= e(round($m['size']/1024).' KB')?></div>
          <div style="padding:0 8px 8px;display:flex;gap:6px">
            <button class="btn btn--ghost btn--sm" type="button" onclick="navigator.clipboard.writeText('<?= e($m['path'])?>');this.textContent='Copied!'">Copy URL</button>
            <form method="post" style="display:inline"><?= csrf_field()?><input type="hidden" name="id" value="<?= $m['id']?>"><input type="hidden" name="do" value="delete"><button class="btn btn--danger btn--sm" data-confirm="Delete file?">Del</button></form>
          </div>
        </div>
      <?php endforeach;?>
    </div>
    <?php endif;?>
  </div>
</div>
<?php admin_foot(); ?>
