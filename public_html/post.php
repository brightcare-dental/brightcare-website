<?php
require __DIR__ . '/includes/bootstrap.php';
$slug = preg_replace('/[^a-z0-9-]/', '', $_GET['s'] ?? '');
$p = $slug ? q1('SELECT * FROM posts WHERE slug=? AND status=1', [$slug]) : null;
if (!$p) { http_response_code(404); $page=['active'=>'blog','title'=>'Post not found']; require __DIR__.'/includes/header.php'; echo '<section class="section"><div class="container" style="text-align:center"><h1>Post not found</h1><p><a class="btn btn--primary" href="/blog">Back to Blog</a></p></div></section>'; require __DIR__.'/includes/footer.php'; exit; }
$page = ['active'=>'blog','title'=>($p['seo_title']?:$p['title'].' — '.s('clinic_name')),'description'=>($p['seo_desc']?:mb_strimwidth(strip_tags($p['content']),0,150)),'image'=>$p['cover']];
require __DIR__ . '/includes/header.php';
page_hero($p['title'], date('F j, Y', strtotime($p['published_at']?:$p['created_at'])).' · '.$p['category'], ['Blog'=>'/blog', mb_strimwidth($p['title'],0,30,'…')=>'']);
?>
<section class="section">
  <div class="container article">
    <?php if ($p['cover']): ?><img class="article__cover reveal" src="<?= h($p['cover']) ?>" alt="<?= h($p['title']) ?>" loading="lazy"><?php endif; ?>
    <div class="rich reveal"><?= $p['content'] ?></div>
    <div class="section__foot reveal"><a href="/blog" class="btn btn--ghost"><?= icon('arrow') ?> All Articles</a> <a href="/contact#book" class="btn btn--primary">Book an Appointment</a></div>
  </div>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>
