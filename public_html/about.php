<?php
require_once __DIR__ . '/includes/bootstrap.php';
$pg = q1('SELECT * FROM pages WHERE slug=? AND status=1', ['about']);
$page = ['active'=>'about','title'=>($pg['seo_title']?:'About Us — '.s('clinic_name')),'description'=>($pg['seo_desc']?:'Learn about '.s('clinic_name').', a trusted dental clinic in Mangalapuram, Trivandrum.')];
require __DIR__ . '/includes/header.php';
$values = [['shield','Safety First','Strict, hospital-grade sterilisation on every visit.'],['heart','Patient Comfort','Gentle, painless techniques and a calming environment.'],['tech','Modern Dentistry','Digital X-rays, scanners and laser-assisted care.'],['check','Honest Care','Transparent pricing and only the treatment you need.']];
?>
<!-- INTRO -->
<section class="section about-intro">
  <div class="container about__grid">
    <div class="about__media reveal"><img src="https://images.unsplash.com/photo-1588776814546-1ffcf47267a5?auto=format&fit=crop&w=720&q=68" alt="<?= h($C['short']) ?> clinic" width="720" height="600" fetchpriority="high"><div class="about__badge"><span class="about__badge-num">15+</span><span>Years of care</span></div></div>
    <div class="about__copy reveal">
      <span class="kicker">About <?= h($C['short']) ?></span>
      <h1>Your Trusted <span class="grad">Dental Family</span> in Trivandrum</h1>
      <div class="rich"><?= $pg['content'] ?? '<p>At Bright Care, we blend the latest dental technology with a genuinely caring, gentle approach. Our mission is simple: make every patient feel relaxed, informed and proud of their smile.</p>' ?></div>
      <a href="/contact#book" class="btn btn--primary">Book a Visit <?= icon('arrow') ?></a>
    </div>
  </div>
</section>

<!-- COUNTER (between the two sections) -->
<section class="stats"><div class="container stats__grid">
  <?php foreach([[15,'+','Years of Care'],[12000,'+','Happy Patients'],[count(get_services())?:25,'+','Treatments'],[5,'★','Average Rating']] as $st): ?>
    <div class="stat reveal"><span class="stat__num" data-count="<?= $st[0] ?>" data-suffix="<?= $st[1] ?>">0</span><span class="stat__label"><?= $st[2] ?></span></div>
  <?php endforeach; ?>
</div></section>

<!-- VALUES -->
<section class="section section--alt">
  <div class="container">
    <div class="section__head reveal"><span class="kicker">Our Values</span><h2>What Makes Us <span class="grad">Different</span></h2></div>
    <div class="features">
      <?php foreach ($values as $v): ?><div class="feature reveal"><span class="feature__ic"><?= icon($v[0]) ?></span><h3><?= $v[1] ?></h3><p><?= $v[2] ?></p></div><?php endforeach; ?>
    </div>
  </div>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>
