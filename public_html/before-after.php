<?php
require_once __DIR__ . '/includes/bootstrap.php';
$page = ['active'=>'smilegallery','title'=>'Before & After — Smile Gallery | '.s('clinic_name'),'description'=>'Real before & after dental results from patients at '.s('clinic_name').', Trivandrum.'];
require __DIR__ . '/includes/header.php';
page_hero('Before & After', 'Real results from real patients across our Trivandrum branches.', ['Smile Gallery'=>'/before-after','Before & After'=>'']);
gallery_tabs('before-after');
$items = get_cases('before-after');
?>
<section class="section">
  <div class="container">
    <?php if ($items): ?>
    <div class="cases-grid">
      <?php foreach ($items as $c): ?>
        <a class="casecard reveal" href="/case/<?= h($c['slug']) ?>">
          <div class="casecard__pair">
            <figure class="casecard__img"><img src="<?= h($c['before_img']) ?>" alt="Before — <?= h($c['title']) ?>" loading="lazy"><span class="casecard__tag">Before</span></figure>
            <figure class="casecard__img"><img src="<?= h($c['after_img']) ?>" alt="After — <?= h($c['title']) ?>" loading="lazy"><span class="casecard__tag casecard__tag--after">After</span></figure>
          </div>
          <div class="casecard__body"><span class="casecard__svc"><?= h($c['service']) ?></span><h3><?= h($c['title']) ?></h3><span class="casecard__more">View case <?= icon('arrow') ?></span></div>
        </a>
      <?php endforeach; ?>
    </div>
    <?php else: empty_state('sparkle', 'Before & after results coming soon',
      'We only publish treatment photographs with our patients\' written consent, so this gallery grows slowly. Ask us during your consultation and we can show you comparable cases.'); endif; ?>
    <div class="section__foot reveal"><a href="#" data-book class="btn btn--primary">Start Your Transformation <?= icon('arrow') ?></a></div>
  </div>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>
