<?php
require_once __DIR__ . '/includes/bootstrap.php';
$page = ['active'=>'smilegallery','title'=>'Smile Makeovers — Smile Gallery | '.s('clinic_name'),'description'=>'Complete smile makeover transformations at '.s('clinic_name').', Trivandrum — veneers, whitening, alignment and more.'];
require __DIR__ . '/includes/header.php';
page_hero('Smile Makeovers', 'Complete, full-smile transformations crafted by our specialists.', ['Smile Gallery'=>'/before-after','Smile Makeovers'=>'']);
gallery_tabs('smile-makeovers');
$items = get_cases('makeover');
?>
<section class="section">
  <div class="container">
    <?php if ($items): ?>
    <div class="mk-grid">
      <?php foreach ($items as $m): ?>
        <a class="mk reveal" href="/case/<?= h($m['slug']) ?>"><img src="<?= h($m['image']) ?>" alt="<?= h($m['title']) ?>" loading="lazy"><figcaption><?= h($m['title']) ?></figcaption></a>
      <?php endforeach; ?>
    </div>
    <?php else: empty_state('sparkle', 'Smile makeovers coming soon',
      'Full-mouth makeover cases are published only with our patients\' written consent. Book a consultation and we will walk you through what is realistic for your smile.'); endif; ?>
    <div class="section__foot reveal"><a href="#" data-book class="btn btn--primary">Design My New Smile <?= icon('arrow') ?></a></div>
  </div>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>
