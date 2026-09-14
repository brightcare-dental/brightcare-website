<?php
require __DIR__ . '/includes/bootstrap.php';
$slug = preg_replace('/[^a-z0-9-]/', '', $_GET['s'] ?? '');
$sv = $slug ? q1_safe('SELECT * FROM services WHERE slug=? AND status=1', [$slug]) : null;
if (!$sv) { http_response_code(404); $page=['active'=>'services','title'=>'Service not found']; require __DIR__.'/includes/header.php'; echo '<section class="section"><div class="container" style="text-align:center"><h1>Service not found</h1><p><a class="btn btn--primary" href="/services">Back to Services</a></p></div></section>'; require __DIR__.'/includes/footer.php'; exit; }

$page = ['active'=>'services','title'=>($sv['seo_title']?:$sv['title'].' — '.s('clinic_name')),'description'=>($sv['seo_desc']?:$sv['excerpt']),'keywords'=>$sv['seo_keywords']];
require __DIR__ . '/includes/header.php';
page_hero($sv['title'], $sv['excerpt'], ['Services'=>'/services', $sv['title']=>'']);
$related = array_filter(get_services(), fn($r)=>$r['slug']!==$slug);
?>
<section class="section">
  <div class="container svc__grid">
    <article class="svc__main reveal">
      <?php if ($sv['image']): ?><img class="svc__img" src="<?= h($sv['image']) ?>" alt="<?= h($sv['title']) ?>" loading="lazy"><?php endif; ?>
      <div class="rich"><?= $sv['content'] ?: '<p>'.h($sv['excerpt']).'</p>' ?></div>
      <a href="/contact#book" class="btn btn--primary" style="margin-top:18px">Book this Treatment <?= icon('arrow') ?></a>
    </article>
    <aside class="svc__side reveal">
      <div class="svc__box">
        <h3>All Services</h3>
        <ul class="svc__list">
          <?php foreach (get_services() as $r): ?><li><a href="/services/<?= h($r['slug']) ?>" class="<?= $r['slug']===$slug?'on':'' ?>"><?= icon('arrow') ?> <?= h($r['title']) ?></a></li><?php endforeach; ?>
        </ul>
      </div>
      <div class="svc__cta">
        <h3>Have a question?</h3>
        <p>Talk to our team — we’re happy to help.</p>
        <a href="tel:<?= h($C['phone_raw']) ?>" class="btn btn--block btn--primary"><?= icon('phone') ?> Call Now</a>
        <a href="https://wa.me/<?= h($C['whatsapp']) ?>" target="_blank" rel="noopener" class="btn btn--block btn--ghost" style="margin-top:8px"><?= icon('wa') ?> WhatsApp</a>
      </div>
    </aside>
  </div>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>
