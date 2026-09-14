<?php
require_once __DIR__ . '/includes/bootstrap.php';
$page = ['active'=>'blog','title'=>'Blog & Dental Tips — '.s('clinic_name'),'description'=>'Dental health tips, news and advice from the team at '.s('clinic_name').', Trivandrum.'];
require __DIR__ . '/includes/header.php';
page_hero('Blog & Dental Tips', 'Advice and news to help you keep a healthy, confident smile.', ['Blog'=>'']);
$posts = get_posts();
?>
<section class="section">
  <div class="container">
    <?php if ($posts): ?>
    <div class="cards">
      <?php foreach ($posts as $p): ?>
        <a class="post-card reveal" href="/blog/<?= h($p['slug']) ?>">
          <div class="post-card__img"><?php if($p['cover']): ?><img src="<?= h($p['cover']) ?>" alt="<?= h($p['title']) ?>" loading="lazy"><?php else: ?><span><?= icon('news') ?></span><?php endif; ?></div>
          <div class="post-card__body"><span class="post-card__cat"><?= h($p['category']) ?></span><h3><?= h($p['title']) ?></h3><p><?= h(mb_strimwidth($p['excerpt']?:strip_tags($p['content']),0,110,'…')) ?></p><span class="card__link">Read more <?= icon('arrow') ?></span></div>
        </a>
      <?php endforeach; ?>
    </div>
    <?php else: empty_state('tooth', 'Dental tips are on the way',
      'We are putting together advice on keeping your teeth healthy between visits. In the meantime, our team is happy to answer any question in person.'); endif; ?>
  </div>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>
