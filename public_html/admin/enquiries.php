<?php
require __DIR__ . '/inc/layout.php';
require __DIR__ . '/inc/helpers.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  csrf_check(); $id=(int)($_POST['id']??0);
  if(($_POST['do']??'')==='delete'){ db()->prepare('DELETE FROM enquiries WHERE id=?')->execute([$id]); flash('Enquiry deleted.'); }
  if(($_POST['do']??'')==='status'){ db()->prepare('UPDATE enquiries SET status=? WHERE id=?')->execute([$_POST['status']??'new',$id]); flash('Status updated.'); }
  redirect('/admin/enquiries.php');
}
$rows=q('SELECT * FROM enquiries ORDER BY id DESC');
admin_head('Enquiries');
?>
<div class="panel"><div class="panel__head"><h2>Appointment Enquiries (<?= count($rows) ?>)</h2></div>
  <div class="panel__body" style="padding:0">
    <?php if(!$rows):?><p style="padding:20px;color:var(--mut)">No enquiries yet. Booking-form submissions from the website will appear here.</p>
    <?php else:?>
    <table class="tbl">
      <tr><th>When</th><th>Name</th><th>Phone</th><th>Service</th><th>Date</th><th>Message</th><th>Status</th><th style="width:120px"></th></tr>
      <?php foreach($rows as $q):?><tr>
        <td><small><?= e($q['created_at'])?></small></td>
        <td><strong><?= e($q['name'])?></strong></td>
        <td><a href="tel:<?= e($q['phone'])?>"><?= e($q['phone'])?></a></td>
        <td><?= e($q['service'])?></td><td><?= e($q['pref_date']?:'—')?></td>
        <td><small><?= e(mb_strimwidth((string)$q['message'],0,50,'…'))?></small></td>
        <td>
          <form method="post" style="display:inline"><?= csrf_field()?><input type="hidden" name="id" value="<?= $q['id']?>"><input type="hidden" name="do" value="status">
            <select name="status" onchange="this.form.submit()" class="badge" style="border:0;font-weight:700;cursor:pointer">
              <?php foreach(['new'=>'New','contacted'=>'Contacted','closed'=>'Closed'] as $k=>$v):?><option value="<?= $k?>" <?= $q['status']===$k?'selected':'' ?>><?= $v?></option><?php endforeach;?>
            </select>
          </form>
        </td>
        <td><form method="post"><?= csrf_field()?><input type="hidden" name="id" value="<?= $q['id']?>"><input type="hidden" name="do" value="delete"><button class="btn btn--danger btn--sm" data-confirm="Delete?">Del</button></form></td>
      </tr><?php endforeach;?>
    </table>
    <?php endif;?>
  </div>
</div>
<?php admin_foot(); ?>
