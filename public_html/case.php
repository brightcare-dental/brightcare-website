<?php
require_once __DIR__ . '/includes/bootstrap.php';
$slug = preg_replace('/[^a-z0-9-]/', '', $_GET['s'] ?? '');
$c = $slug ? get_case($slug) : null;
if (!$c) {
  http_response_code(404);
  $page=['active'=>'smilegallery','title'=>'Case not found'];
  require __DIR__.'/includes/header.php';
  echo '<section class="section"><div class="container" style="text-align:center"><h1>Case not found</h1><p><a class="btn btn--primary" href="/before-after">Back to Smile Gallery</a></p></div></section>';
  require __DIR__.'/includes/footer.php'; exit;
}
$typeMap = ['before-after'=>['Before & After','/before-after'],'makeover'=>['Smile Makeovers','/smile-makeovers'],'case-study'=>['Case Studies','/case-studies']];
[$tLabel,$tPage] = $typeMap[$c['type']] ?? ['Smile Gallery','/before-after'];
$page = ['active'=>'smilegallery','title'=>($c['seo_title']?:$c['title'].' — '.s('clinic_name')),'description'=>($c['seo_desc']?:($c['subtitle']?:'')),'image'=>($c['after_img']?:$c['image'])];
require __DIR__ . '/includes/header.php';
page_hero($c['title'], $c['service'] ?: $c['subtitle'], ['Smile Gallery'=>'/before-after', $tLabel=>$tPage, mb_strimwidth($c['title'],0,28,'…')=>'']);
?>
<section class="section">
  <div class="container article">
    <?php if ($c['type']==='before-after' && ($c['before_img']||$c['after_img'])): ?>
      <div class="ba-detail reveal">
        <figure class="casecard__img"><img src="<?= h($c['before_img']) ?>" alt="Before — <?= h($c['title']) ?>"><span class="casecard__tag">Before</span></figure>
        <figure class="casecard__img"><img src="<?= h($c['after_img']) ?>" alt="After — <?= h($c['title']) ?>"><span class="casecard__tag casecard__tag--after">After</span></figure>
      </div>
    <?php elseif ($c['image']): ?>
      <img class="article__cover reveal" src="<?= h($c['image']) ?>" alt="<?= h($c['title']) ?>">
    <?php endif; ?>
    <?php if ($c['subtitle'] && $c['type']!=='case-study'): ?><p class="case__sub reveal"><?= h($c['subtitle']) ?></p><?php endif; ?>
    <div class="rich reveal"><?= $c['content'] ?: '<p>'.h($c['subtitle']).'</p>' ?></div>
    <div class="section__foot reveal">
      <a href="<?= $tPage ?>" class="btn btn--ghost"><?= icon('arrow') ?> Back to <?= h($tLabel) ?></a>
      <a href="#" data-book class="btn btn--primary">Book a Consultation <?= icon('arrow') ?></a>
    </div>
  </div>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>
