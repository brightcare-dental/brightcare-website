<?php
$page = ['active' => 'home'];
require __DIR__ . '/includes/header.php';
$services = get_services();
$doctors  = doctors_by('main');   // homepage shows senior doctors only
$reviews  = get_testimonials();
$branches = get_branches();
$branchNames = implode(', ', array_map(fn($b)=>$b['area'] ?: $b['name'], $branches));
$stats = [
  ['n'=>15,'s'=>'+','l'=>'Years of Experience'],
  ['n'=>12000,'s'=>'+','l'=>'Happy Patients'],
  ['n'=>count($services)?:25,'s'=>'+','l'=>'Treatments Offered'],
  ['n'=>5,'s'=>'★','l'=>'Average Rating'],
];
$features = [
  ['shield','100% Sterile','Hospital-grade hygiene'],
  ['heart','Painless Care','Gentle, anxiety-free'],
  ['tech','Latest Technology','Digital & laser dentistry'],
  ['wallet','Easy on Pocket','Transparent pricing & EMI'],
];
$steps = [
  ['1','Book Online','Pick a time that suits you — call, WhatsApp or the form below.'],
  ['2','Consultation','A thorough check-up and a clear, honest treatment plan.'],
  ['3','Gentle Treatment','Comfortable, modern care from our specialist team.'],
  ['4','Healthy Smile','Follow-ups and tips to keep your smile bright for life.'],
];
$faqs = [
  ['Does a root canal hurt?','The tooth is fully anaesthetised first, so for most patients the appointment feels similar to having a deep filling. The pain people remember is usually the toothache that brought them in, which the treatment relieves. Mild tenderness for two to three days afterwards is normal.'],
  ['What will it cost?','You get a written estimate before any treatment begins, and we do not add charges you have not agreed to. Costs vary with what is actually involved — a filling and a root canal with a crown are very different — so we quote after examining you rather than over the phone.'],
  ['Do you offer EMI or instalment options?','Yes, on major treatments such as implants, crowns and orthodontics. Ask at your consultation and we will set out what is available for your treatment plan.'],
  ['What if I have a dental emergency?','Call us on the clinic number as early in the day as you can and describe what has happened. Pain, swelling, a knocked-out tooth or a broken front tooth are treated as urgent and we will fit you in the same day wherever possible. For a knocked-out adult tooth, keep it in milk and come immediately — time matters a great deal.'],
  ['I have not been to a dentist in years. Is it too late?','No, and you will not be lectured. Long gaps are common and we see them every week. The first visit is an examination and a conversation about where things stand and what you want to do about it. Nothing is treated on the first visit unless you are in pain and want it dealt with.'],
  ['I am very anxious about dental treatment. Can you help?','Tell us when you book, not when you sit down — it changes how we plan the appointment. We allow more time, explain each step before it happens, and stop whenever you raise your hand. Many of our patients started out avoiding dental care entirely.'],
  ['Is the clinic suitable for young children?','Yes. A first visit is best by around your child\'s first birthday, and it is a gentle look rather than treatment — often with the child on your lap. We also do fluoride application and fissure sealants, which prevent a great deal of decay.'],
  ['How do I book an appointment?','Use the booking form below, call us, or message us on WhatsApp. Let us know which branch is most convenient — Mangalapuram, Vengode or Mananakku.'],
];
?>

<!-- ───────── HERO ───────── -->
<section class="hero">
  <div class="hero__bg" aria-hidden="true"><span class="mesh mesh--1"></span><span class="mesh mesh--2"></span><span class="mesh mesh--3"></span><span class="grid-dots"></span></div>
  <div class="container hero__in">
    <div class="hero__copy">
      <span class="eyebrow reveal"><?= icon('star') ?> Trusted by 12,000+ smiles in Trivandrum</span>
      <h1 class="hero__title reveal">Your Smile,<br><span class="grad type">Our Passion.</span></h1>
      <p class="hero__lead reveal">Painless, world-class dental care at <strong><?= h($C['short']) ?></strong> — <?= $branches ? 'now at '.count($branches).' branches across Trivandrum' : 'Trivandrum\'s most advanced dental clinic' ?> for implants, braces, root canals and family dentistry.</p>
      <div class="hero__cta reveal">
        <a href="/contact#book" class="btn btn--primary btn--lg mobile-hide">Book Appointment <?= icon('arrow') ?></a>
        <a href="tel:<?= h($C['phone_raw']) ?>" class="btn btn--ghost btn--lg"><?= icon('phone') ?> <?= h($C['phone']) ?></a>
      </div>
      <div class="hero__trust reveal">
        <?= google_badge('5.0', '300+') ?>
        <div class="hero__trust-by"><div class="avatars" aria-hidden="true"><span></span><span></span><span></span><span></span></div><span>Loved by 12,000+ patients</span></div>
      </div>
    </div>
    <div class="hero__media reveal">
      <div class="hero__ring" aria-hidden="true"></div>
      <div class="hero__photo">
        <img src="https://images.unsplash.com/photo-1629909613654-28e377c37b09?auto=format&fit=crop&w=720&q=68"
             alt="Dentist treating a smiling patient at <?= h($C['short']) ?>" width="720" height="810" fetchpriority="high" decoding="async">
      </div>
      <div class="floatcard floatcard--a"><span class="floatcard__ic"><?= icon('shield') ?></span><div><strong>100% Sterile</strong><small>Safe &amp; hygienic</small></div></div>
      <div class="floatcard floatcard--b"><span class="floatcard__ic floatcard__ic--amber"><?= icon('heart') ?></span><div><strong>Painless</strong><small>Gentle care</small></div></div>
    </div>
  </div>
</section>

<!-- ───────── FEATURE STRIP ───────── -->
<section class="featstrip"><div class="container featstrip__in">
  <?php foreach ($features as $f): ?>
    <div class="featstrip__item reveal"><span class="featstrip__ic"><?= icon($f[0]) ?></span><div><strong><?= $f[1] ?></strong><small><?= $f[2] ?></small></div></div>
  <?php endforeach; ?>
</div></section>

<!-- ───────── SERVICES ───────── -->
<section class="section cv" id="services">
  <div class="container">
    <div class="section__head reveal"><span class="kicker">What We Offer</span><h2>Comprehensive Dental <span class="grad">Services</span></h2><p>Everything your family needs under one roof — from routine cleanings to advanced cosmetic dentistry.</p></div>
    <div class="cards">
      <?php foreach ($services as $sv): ?>
        <a class="card reveal" href="/services/<?= h($sv['slug']) ?>">
          <span class="card__ic"><?= icon($sv['icon'] ?: 'tooth') ?></span>
          <h3><?= h($sv['title']) ?></h3>
          <p><?= h($sv['excerpt']) ?></p>
          <span class="card__link">Learn more <?= icon('arrow') ?></span>
        </a>
      <?php endforeach; ?>
    </div>
    <div class="section__foot reveal"><a href="/services" class="btn btn--primary">View All Services <?= icon('arrow') ?></a></div>
  </div>
</section>

<!-- ───────── ABOUT ───────── -->
<section class="section section--alt cv" id="about">
  <div class="container about__grid">
    <div class="about__media reveal">
      <img src="https://images.unsplash.com/photo-1588776814546-1ffcf47267a5?auto=format&fit=crop&w=720&q=68" alt="Modern <?= h($C['short']) ?> clinic interior" width="720" height="600" loading="lazy" decoding="async">
      <div class="about__badge"><span class="about__badge-num">15+</span><span>Years of trusted care</span></div>
    </div>
    <div class="about__copy reveal">
      <span class="kicker">About <?= h($C['short']) ?></span>
      <h2>A Dental Home Built on <span class="grad">Trust &amp; Comfort</span></h2>
      <p>We blend the latest dental technology with a genuinely caring, gentle approach. Our mission is simple: make every patient feel relaxed, informed and proud of their smile.</p>
      <ul class="ticks">
        <li><?= icon('check') ?> State-of-the-art digital dentistry</li>
        <li><?= icon('check') ?> Experienced specialist team</li>
        <li><?= icon('check') ?> Strict sterilisation protocols</li>
        <li><?= icon('check') ?> Transparent, affordable pricing</li>
      </ul>
      <a href="/about" class="btn btn--primary">More About Us <?= icon('arrow') ?></a>
    </div>
  </div>
</section>

<!-- ───────── STATS ───────── -->
<section class="stats cv"><div class="container stats__grid">
  <?php foreach ($stats as $st): ?>
    <div class="stat reveal"><span class="stat__num" data-count="<?= $st['n'] ?>" data-suffix="<?= $st['s'] ?>">0</span><span class="stat__label"><?= $st['l'] ?></span></div>
  <?php endforeach; ?>
</div></section>

<!-- ───────── PROCESS ───────── -->
<section class="section cv">
  <div class="container">
    <div class="section__head reveal"><span class="kicker">Simple &amp; Stress-Free</span><h2>Your Visit in <span class="grad">4 Easy Steps</span></h2></div>
    <div class="steps">
      <?php foreach ($steps as $stp): ?>
        <div class="step reveal"><span class="step__n"><?= $stp[0] ?></span><h3><?= $stp[1] ?></h3><p><?= $stp[2] ?></p></div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ───────── DOCTORS ───────── -->
<?php if ($doctors): ?>
<section class="section section--alt cv" id="doctors">
  <div class="container">
    <div class="section__head reveal"><span class="kicker">Meet The Team</span><h2>Our Expert <span class="grad">Dentists</span></h2><p>Caring specialists dedicated to your comfort and confidence.</p></div>
    <div class="team-main">
      <?php foreach ($doctors as $d): ?>
        <article class="leaddoc reveal">
          <div class="leaddoc__photo">
            <?php if ($d['photo']): ?><img src="<?= h($d['photo']) ?>" alt="<?= h($d['name']) ?>" width="600" height="600" loading="lazy" decoding="async">
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
    <div class="section__foot reveal"><a href="/doctors" class="btn btn--primary">Meet the Whole Team <?= icon('arrow') ?></a></div>
  </div>
</section>
<?php endif; ?>

<!-- ───────── TESTIMONIALS ───────── -->
<?php if ($reviews): ?>
<section class="section cv" id="reviews">
  <div class="container">
    <div class="section__head reveal"><span class="kicker">Patient Stories</span><h2>Smiles That Say It <span class="grad">All</span></h2><div class="ghead"><?= google_badge('5.0', '300+') ?></div></div>
    <div class="quotes">
      <?php foreach ($reviews as $t): ?>
        <figure class="quote reveal"><div class="quote__stars"><?php for($i=0;$i<(int)$t['rating'];$i++) echo icon('star'); ?></div><blockquote>“<?= h($t['text']) ?>”</blockquote><figcaption><span class="quote__avatar"><?= h(strtoupper(mb_substr($t['name'],0,1))) ?></span><?= h($t['name']) ?></figcaption></figure>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- ───────── BRANCHES ───────── -->
<?php if ($branches): ?>
<section class="section cv" id="branches">
  <div class="container">
    <div class="section__head reveal"><span class="kicker">Visit Us</span><h2>Our Clinics Across <span class="grad">Trivandrum</span></h2><p>Three convenient locations — premium dental care close to you.</p></div>
    <div class="branches">
      <?php foreach ($branches as $b): ?>
        <article class="branch reveal">
          <span class="branch__ic"><?= icon('pin') ?></span>
          <h3><?= h($b['name']) ?></h3>
          <p class="branch__loc"><?= h($b['area'] ? $b['area'] . ', ' . $b['city'] : $b['city']) ?></p>
          <?php if ($b['address']): ?><p class="branch__addr"><?= h($b['address']) ?></p><?php endif; ?>
          <?php if ($b['phone']): ?><p class="branch__addr"><?= icon('phone') ?> <a href="tel:<?= h(preg_replace('/\s+/','',$b['phone'])) ?>"><?= h($b['phone']) ?></a></p><?php endif; ?>
        </article>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- ───────── FAQ ───────── -->
<section class="section section--alt cv">
  <div class="container faq__wrap">
    <div class="section__head reveal"><span class="kicker">Good to Know</span><h2>Frequently Asked <span class="grad">Questions</span></h2></div>
    <div class="faq">
      <?php foreach ($faqs as $i => $fq): ?>
        <details class="faq__item reveal" <?= $i===0?'open':'' ?>><summary><?= $fq[0] ?><span class="faq__plus"></span></summary><div class="faq__a"><p><?= $fq[1] ?></p></div></details>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ───────── BOOK ───────── -->
<section class="book cv" id="book">
  <div class="container book__grid">
    <div class="book__copy reveal">
      <span class="kicker kicker--light">Book Your Visit</span>
      <h2>Ready for a Healthier, Brighter Smile?</h2>
      <p>Request an appointment and our team will confirm your slot within hours. Walk-ins welcome too.</p>
      <ul class="book__points">
        <li><?= icon('phone') ?> <a href="tel:<?= h($C['phone_raw']) ?>"><?= h($C['phone']) ?></a></li>
        <li><?= icon('wa') ?> <a href="https://wa.me/<?= h($C['whatsapp']) ?>" target="_blank" rel="noopener">Chat on WhatsApp</a></li>
        <li><?= icon('pin') ?> <?= h($C['address']) ?></li>
        <li><?= icon('clock') ?> Mon–Sat <?= h($C['hours_weekday']) ?> · Sun <?= h($C['hours_sunday']) ?></li>
      </ul>
    </div>
    <form class="book__form reveal" id="bookForm" novalidate>
      <h3>Request an Appointment</h3>
      <div class="field"><input type="text" name="name" required placeholder="Full name *"></div>
      <div class="field"><input type="tel" name="phone" required placeholder="Phone number *"></div>
      <div class="field"><input type="email" name="email" placeholder="Email (optional)"></div>
      <div class="field"><select name="service"><option value="">Select a service</option><?php foreach ($services as $sv): ?><option><?= h($sv['title']) ?></option><?php endforeach; ?><option>Other</option></select></div>
      <div class="field"><input type="date" name="pref_date" aria-label="Preferred date"></div>
      <div class="field"><textarea name="message" rows="2" placeholder="Message (optional)"></textarea></div>
      <input type="text" name="website" class="hp" tabindex="-1" autocomplete="off" aria-hidden="true">
      <button type="submit" class="btn btn--primary btn--block btn--lg">Request Appointment <?= icon('arrow') ?></button>
      <p class="book__note" id="formNote">We’ll call you to confirm. Your details are kept private.</p>
    </form>
  </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
