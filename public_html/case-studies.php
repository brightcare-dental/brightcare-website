<?php
require_once __DIR__ . '/includes/bootstrap.php';
$page = ['active'=>'smilegallery','title'=>'Case Studies — Smile Gallery | '.s('clinic_name'),'description'=>'In-depth dental case studies from '.s('clinic_name').', Trivandrum — how we plan and solve real cases.'];
require __DIR__ . '/includes/header.php';
page_hero('Case Studies', 'How we approach and solve real dental challenges, step by step.', ['Smile Gallery'=>'/before-after','Case Studies'=>'']);
gallery_tabs('case-studies');
$items = get_cases('case-study');
?>
<section class="section">
  <div class="container">
    <?php if ($items): ?>
    <div class="cs-grid">
      <?php foreach ($items as $st): $excerpt = $st['subtitle'] ?: mb_strimwidth(trim(strip_tags($st['content'])),0,150,'…'); ?>
        <a class="csentry reveal" href="/case/<?= h($st['slug']) ?>">
          <div class="csentry__img"><img src="<?= h($st['image']) ?>" alt="<?= h($st['title']) ?>" loading="lazy"></div>
          <div class="csentry__body"><span class="csentry__tag"><?= h($st['service']) ?></span><h3><?= h($st['title']) ?></h3><p><?= h($excerpt) ?></p><span class="btn btn--primary btn--sm">Read Full Case <?= icon('arrow') ?></span></div>
        </a>
      <?php endforeach; ?>
    </div>
    <?php else: empty_state('tech', 'Case studies coming soon',
      'We are writing up a few treatments in detail — what the problem was, what we did, and how long it took. Published only with patient consent.'); endif; ?>
  </div>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>
