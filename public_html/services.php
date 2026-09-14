<?php
require_once __DIR__ . '/includes/bootstrap.php';
$page = ['active'=>'services','title'=>'Dental Services — '.s('clinic_name'),'description'=>'Explore our full range of dental services in Mangalapuram, Trivandrum — implants, root canals, braces, whitening, pediatric & general dentistry.'];
require __DIR__ . '/includes/header.php';
page_hero('Our Dental Services', 'Comprehensive, gentle care for every smile in your family.', ['Services'=>'']);
$services = get_services();
?>
<section class="section">
  <div class="container">
    <div class="cards">
      <?php foreach ($services as $sv): ?>
        <a class="card reveal" href="/services/<?= h($sv['slug']) ?>"><span class="card__ic"><?= icon($sv['icon']?:'tooth') ?></span><h3><?= h($sv['title']) ?></h3><p><?= h($sv['excerpt']) ?></p><span class="card__link">Learn more <?= icon('arrow') ?></span></a>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<section class="section section--alt">
  <div class="container">
    <div class="section__head reveal"><span class="kicker">Simple &amp; Stress-Free</span><h2>How Your Visit <span class="grad">Works</span></h2></div>
    <div class="steps">
      <?php foreach([['1','Book','Call, WhatsApp or use our form.'],['2','Consult','Check-up + a clear, honest plan.'],['3','Treat','Comfortable, modern treatment.'],['4','Smile','Follow-ups to keep it bright.']] as $s): ?>
        <div class="step reveal"><span class="step__n"><?= $s[0] ?></span><h3><?= $s[1] ?></h3><p><?= $s[2] ?></p></div>
      <?php endforeach; ?>
    </div>
    <div class="section__foot reveal"><a href="/contact#book" class="btn btn--primary">Book an Appointment <?= icon('arrow') ?></a></div>
  </div>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>
