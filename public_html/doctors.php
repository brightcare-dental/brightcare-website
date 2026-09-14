<?php
require_once __DIR__ . '/includes/bootstrap.php';
$page = ['active'=>'doctors','title'=>'Our Doctors & Team — '.s('clinic_name'),'description'=>'Meet the experienced specialists at '.s('clinic_name').', Trivandrum — lead surgeons, duty doctors and visiting consultants.'];
require __DIR__ . '/includes/header.php';

$main = doctors_by('main');
$duty = doctors_by('duty');
$cons = doctors_by('consultant');

?>

<!-- HERO: SENIOR DOCTORS -->
<section class="dochero">
  <div class="dochero__bg" aria-hidden="true"><span class="mesh mesh--1"></span><span class="mesh mesh--2"></span><span class="grid-dots"></span></div>
  <div class="container">
    <div class="dochero__head">
      <span class="kicker reveal">Chief Doctors</span>
      <h1 class="reveal">Our <span class="grad">Chief Doctors</span></h1>
      <p class="reveal">Leading every treatment at <?= h($C['short']) ?> with decades of combined expertise and a gentle, caring approach.</p>
    </div>
    <div class="team-main">
      <?php foreach ($main as $d): ?>
        <article class="leaddoc reveal">
          <div class="leaddoc__photo">
            <?php if ($d['photo']): ?><img src="<?= h($d['photo']) ?>" alt="<?= h($d['name']) ?>" width="600" height="600" fetchpriority="high">
            <?php else: ?><span class="doc-initials doc-initials--lg"><?= docInitials($d['name']) ?></span><?php endif; ?>
          </div>
          <div class="leaddoc__body">
            <span class="leaddoc__tag">Chief Doctor</span>
            <h3><?= h($d['name']) ?></h3>
            <span class="leaddoc__role"><?= $d['role'] ?></span>
            <?php if ($d['bio']): ?><p><?= h($d['bio']) ?></p><?php endif; ?>
            <a href="#" data-book class="btn btn--primary btn--sm">Book with <?= h(explode(' ', $d['name'])[0].' '.(explode(' ', $d['name'])[1] ?? '')) ?> <?= icon('arrow') ?></a>
          </div>
        </article>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- DUTY DOCTORS -->
<?php if ($duty): ?>
<section class="section section--alt">
  <div class="container">
    <div class="section__head reveal"><span class="kicker">Always Available</span><h2>Duty <span class="grad">Doctors</span></h2><p>On hand every day for check-ups, treatments and urgent care.</p></div>
    <div class="team-grid team-grid--3">
      <?php foreach ($duty as $d): ?>
        <article class="docard reveal">
          <div class="docard__photo">
            <?php if ($d['photo']): ?><img src="<?= h($d['photo']) ?>" alt="<?= h($d['name']) ?>" width="400" height="400" loading="lazy">
            <?php else: ?><span class="doc-initials"><?= docInitials($d['name']) ?></span><?php endif; ?>
          </div>
          <h3><?= h($d['name']) ?></h3>
          <span class="docard__role"><?= $d['role'] ?></span>
        </article>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- CONSULTANTS -->
<?php if ($cons): ?>
<section class="section">
  <div class="container">
    <div class="section__head reveal"><span class="kicker">Visiting Specialists</span><h2>Consultant <span class="grad">Doctors</span></h2><p>Specialist consultants across every branch of dentistry, on call for advanced care.</p></div>
    <div class="team-grid team-grid--cons">
      <?php foreach ($cons as $d): ?>
        <article class="conscard reveal">
          <div class="conscard__avatar">
            <?php if ($d['photo']): ?><img src="<?= h($d['photo']) ?>" alt="<?= h($d['name']) ?>" width="120" height="120" loading="lazy">
            <?php else: ?><span class="doc-initials doc-initials--sm"><?= docInitials($d['name']) ?></span><?php endif; ?>
          </div>
          <div class="conscard__body"><h3><?= h($d['name']) ?></h3><span><?= $d['role'] ?></span></div>
        </article>
      <?php endforeach; ?>
    </div>
    <div class="section__foot reveal"><a href="#" data-book class="btn btn--primary">Book an Appointment <?= icon('arrow') ?></a></div>
  </div>
</section>
<?php endif; ?>

<?php require __DIR__ . '/includes/footer.php'; ?>
