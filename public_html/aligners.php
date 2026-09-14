<?php
require_once __DIR__ . '/includes/bootstrap.php';
$page = [
  'active'      => 'aligners',
  'title'       => 'Invisible Clear Aligners in Trivandrum — Bright Care Dental Clinic',
  'description' => 'Straighten your teeth invisibly with clear aligners at Bright Care Dental Clinic, Trivandrum. Comfortable, removable, custom 3D-planned. Book your free smile assessment today.',
  'keywords'    => 'clear aligners Trivandrum, invisible braces, teeth straightening, invisalign Trivandrum, aligners Kerala',
  'css'         => '/assets/css/aligners.css?v=6',
  'js'          => '/assets/js/aligners.js?v=4',
];
require __DIR__ . '/includes/header.php';

// Infinite marquee band (content duplicated for seamless loop)
function marquee(array $words, bool $rev = false): void {
  $one = '<span class="marquee__item">';
  foreach ($words as $w) $one .= ($w[0]==='*' ? '<b>'.substr($w,1).'</b>' : $w) . '<i class="marquee__dot"></i>';
  $one .= '</span>';
  echo '<div class="marquee' . ($rev?' marquee--rev':'') . '" aria-hidden="true"><div class="marquee__track">' . $one . $one . '</div></div>';
}

// Reusable glossy, dimensional clear-aligner tray
$ALIGNER = <<<'SVG'
<svg class="aligner-svg" viewBox="0 0 320 250" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
  <defs>
    <linearGradient id="alFace" x1="0" y1="0" x2=".25" y2="1">
      <stop offset="0" stop-color="#ffffff" stop-opacity=".97"/>
      <stop offset=".5" stop-color="#cdf3ed" stop-opacity=".82"/>
      <stop offset="1" stop-color="#6fc7bd" stop-opacity=".74"/>
    </linearGradient>
    <linearGradient id="alBack" x1="0" y1="0" x2="0" y2="1">
      <stop offset="0" stop-color="#34a99e" stop-opacity=".55"/>
      <stop offset="1" stop-color="#0c6b70" stop-opacity=".62"/>
    </linearGradient>
    <radialGradient id="alShade" cx=".5" cy=".5" r=".5">
      <stop offset="0" stop-color="#021d20" stop-opacity=".5"/><stop offset="1" stop-color="#021d20" stop-opacity="0"/>
    </radialGradient>
    <filter id="alSh" x="-40%" y="-30%" width="180%" height="175%"><feDropShadow dx="0" dy="22" stdDeviation="24" flood-color="#02272b" flood-opacity=".6"/></filter>
  </defs>
  <ellipse cx="160" cy="228" rx="98" ry="15" fill="url(#alShade)"/>
  <path transform="translate(7 9)" d="M58 44 C58 140 110 196 160 196 C210 196 262 140 262 44 L243 44 C243 128 205 168 160 168 C115 168 77 128 77 44 Z" fill="url(#alBack)"/>
  <path filter="url(#alSh)" d="M52 40 C52 138 106 196 160 196 C214 196 268 138 268 40 L246 40 C246 126 206 166 160 166 C114 166 74 126 74 40 Z" fill="url(#alFace)" stroke="#eafaf7" stroke-width="1.5" stroke-opacity=".85"/>
  <g stroke="#bfeee6" stroke-width="2" fill="none" opacity=".5">
    <path d="M86 72 q10 15 20 0"/><path d="M106 100 q11 15 22 0"/><path d="M130 122 q14 15 28 0"/>
    <path d="M194 72 q-10 15 -20 0"/><path d="M174 100 q-11 15 -22 0"/><path d="M172 122 q-14 15 -28 0"/>
  </g>
  <path d="M66 48 C70 122 106 170 134 186" stroke="#ffffff" stroke-width="9" stroke-linecap="round" opacity=".9" fill="none"/>
  <path d="M82 44 C86 96 98 130 116 154" stroke="#ffffff" stroke-width="3.5" stroke-linecap="round" opacity=".55" fill="none"/>
  <path d="M252 50 C248 112 226 152 206 174" stroke="#ffffff" stroke-width="5" stroke-linecap="round" opacity=".42" fill="none"/>
  <path d="M76 41 L244 41" stroke="#ffffff" stroke-width="3" stroke-linecap="round" opacity=".7"/>
</svg>
SVG;
?>

<div class="alpage">

<!-- ════════ HERO ════════ -->
<section class="alh">
  <div class="alh__bg" aria-hidden="true">
    <span class="aurora aurora--1"></span><span class="aurora aurora--2"></span><span class="aurora aurora--3"></span>
    <span class="spark" style="--x:12%;--y:24%;--d:0s"></span><span class="spark" style="--x:82%;--y:18%;--d:1.1s"></span>
    <span class="spark" style="--x:68%;--y:70%;--d:.5s"></span><span class="spark" style="--x:22%;--y:78%;--d:1.6s"></span>
    <span class="spark" style="--x:90%;--y:52%;--d:2s"></span><span class="spark" style="--x:40%;--y:14%;--d:.8s"></span>
  </div>
  <canvas class="hero-particles" data-particles aria-hidden="true"></canvas>
  <div class="container alh__in">
    <div class="alh__copy">
      <span class="eyebrow alh__eye"><?= icon('sparkle') ?> Clear Aligners · Trivandrum</span>
      <h1 class="alh__title">The Invisible Way to a <span class="grad">Perfect Smile</span></h1>
      <p class="alh__lead">Straighten your teeth without anyone noticing. Custom-made, removable, and remarkably comfortable clear aligners — designed in 3D for your smile at <strong><?= h($C['short']) ?></strong>.</p>
      <div class="alh__cta">
        <a href="#" data-book data-service="Braces &amp; Aligners" class="btn btn--primary btn--lg">Book Free Assessment <?= icon('arrow') ?></a>
        <a href="#stage" class="btn btn--ghost btn--lg">See How It Works</a>
      </div>
      <div class="alh__trust"><?= google_badge('5.0','300+') ?></div>
    </div>
    <div class="alh__stage" data-tilt>
      <div class="alh__halo" aria-hidden="true"></div>
      <div class="alh__aligner" data-tilt-el>
        <?= $ALIGNER ?>
        <span class="flare flare--1" aria-hidden="true"></span>
        <span class="flare flare--2" aria-hidden="true"></span>
      </div>
    </div>
  </div>
  <a href="#stage" class="alh__scroll" aria-label="Scroll down"><span></span>Scroll</a>
</section>

<!-- ════════ CINEMATIC SCROLL STAGE ════════ -->
<section class="stage" id="stage" data-stage>
  <div class="stage__sticky">
    <div class="stage__glow" aria-hidden="true"></div>
    <div class="stage__track">
      <div class="stage__aligner" data-stage-aligner><?= $ALIGNER ?></div>
    </div>
    <div class="stage__caps">
      <div class="stage__cap" data-cap><span class="stage__no">01</span><h2>Invisible.</h2><p>So clear, no one will know you’re straightening your teeth.</p></div>
      <div class="stage__cap" data-cap><span class="stage__no">02</span><h2>Removable.</h2><p>Eat, brush and floss exactly the way you always do.</p></div>
      <div class="stage__cap" data-cap><span class="stage__no">03</span><h2>Comfortable.</h2><p>Smooth, custom-fit trays — no metal, no wires, no scratches.</p></div>
      <div class="stage__cap" data-cap><span class="stage__no">04</span><h2>Custom-made.</h2><p>3D-planned for your smile and swapped tray by tray.</p></div>
    </div>
    <div class="stage__progress"><span data-stage-bar></span></div>
  </div>
</section>

<?php marquee(['*Invisible','Removable','*Comfortable','Discreet','*Confident','Custom-Made']); ?>

<!-- ════════ TEETH STRAIGHTEN ════════ -->
<section class="section straighten">
  <div class="container">
    <div class="section__head reveal"><span class="kicker">The Transformation</span><h2>Watch Your Smile <span class="grad">Straighten</span></h2><p>Aligners gently guide each tooth into its perfect position — week by week.</p></div>
    <div class="teeth reveal" data-teeth>
      <span class="tooth" style="--r:-18deg;--y:10px"></span>
      <span class="tooth" style="--r:14deg;--y:-8px"></span>
      <span class="tooth" style="--r:-9deg;--y:14px"></span>
      <span class="tooth tooth--big" style="--r:20deg;--y:-6px"></span>
      <span class="tooth tooth--big" style="--r:-16deg;--y:8px"></span>
      <span class="tooth" style="--r:11deg;--y:-12px"></span>
      <span class="tooth" style="--r:-13deg;--y:9px"></span>
      <span class="tooth" style="--r:17deg;--y:-7px"></span>
    </div>
    <div class="straighten__labels reveal"><span>Before</span><span class="straighten__arrow"><?= icon('arrow') ?></span><span class="grad">After</span></div>
  </div>
</section>

<!-- ════════ BENEFITS ════════ -->
<section class="section section--alt">
  <div class="container">
    <div class="section__head reveal"><span class="kicker">Why Aligners</span><h2>Everything to Love, <span class="grad">Nothing to Hide</span></h2></div>
    <div class="albenefits">
      <?php
      $bens = [
        ['sparkle','Virtually Invisible','Clear trays almost no one will notice.'],
        ['heart','Pain-Free','Gentle, gradual movement — far comfier than braces.'],
        ['check','Removable','Take them out to eat, drink and clean.'],
        ['clock','Fewer Visits','Most plans need only occasional check-ins.'],
        ['tech','3D-Planned','Preview your new smile before you even start.'],
        ['shield','Predictable','A precise, mapped-out path to straight teeth.'],
      ];
      foreach ($bens as $i => $b): ?>
        <div class="alben reveal" style="--i:<?= $i ?>"><span class="alben__ic"><?= icon($b[0]) ?></span><h3><?= $b[1] ?></h3><p><?= $b[2] ?></p></div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ════════ PROCESS ════════ -->
<section class="section">
  <div class="container">
    <div class="section__head reveal"><span class="kicker">Your Journey</span><h2>Three Simple <span class="grad">Steps</span></h2></div>
    <div class="alsteps">
      <div class="alstep reveal"><div class="alstep__n">1</div><h3>3D Smile Scan</h3><p>A quick, comfortable digital scan maps your teeth — no messy moulds.</p></div>
      <div class="alstep__line reveal" aria-hidden="true"></div>
      <div class="alstep reveal"><div class="alstep__n">2</div><h3>Custom Aligners</h3><p>We craft a series of clear trays, each nudging your teeth a little closer.</p></div>
      <div class="alstep__line reveal" aria-hidden="true"></div>
      <div class="alstep reveal"><div class="alstep__n">3</div><h3>Your New Smile</h3><p>Wear, swap, smile. Reveal the confident smile you’ve always wanted.</p></div>
    </div>
  </div>
</section>

<!-- ════════ STATS ════════ -->
<section class="stats"><div class="container stats__grid">
  <?php foreach([[15,'+','Years of Care'],[12000,'+','Smiles Created'],[98,'%','Would Recommend'],[5,'★','Google Rating']] as $st): ?>
    <div class="stat reveal"><span class="stat__num" data-count="<?= $st[0] ?>" data-suffix="<?= $st[1] ?>">0</span><span class="stat__label"><?= $st[2] ?></span></div>
  <?php endforeach; ?>
</div></section>

<!-- ════════ FAQ ════════ -->
<section class="section section--alt">
  <div class="container faq__wrap">
    <div class="section__head reveal"><span class="kicker">Good to Know</span><h2>Aligner <span class="grad">Questions</span></h2></div>
    <div class="faq">
      <?php
      $faqs = [
        ['How long does treatment take?','Most cases take 6–18 months depending on complexity. We’ll give you a clear timeline at your free assessment.'],
        ['Do aligners hurt?','You may feel mild pressure for a day or two with each new tray — that’s them working. It’s far gentler than traditional braces.'],
        ['How many hours a day do I wear them?','For best results, wear your aligners 20–22 hours a day, removing them only to eat and clean your teeth.'],
        ['Are they really invisible?','They’re made of clear, medical-grade material that fits snugly over your teeth — most people won’t notice them at all.'],
      ];
      foreach ($faqs as $i=>$fq): ?>
        <details class="faq__item reveal" <?= $i===0?'open':'' ?>><summary><?= $fq[0] ?><span class="faq__plus"></span></summary><div class="faq__a"><p><?= $fq[1] ?></p></div></details>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<?php marquee(['*Your Smile','Your Confidence','*Your Moment','Starts Now'], true); ?>

<!-- ════════ FINAL CTA ════════ -->
<section class="alcta">
  <div class="alcta__bg" aria-hidden="true"><span class="aurora aurora--1"></span><span class="aurora aurora--2"></span></div>
  <div class="alcta__aligner" aria-hidden="true"><?= $ALIGNER ?></div>
  <div class="container alcta__in reveal">
    <span class="eyebrow alh__eye"><?= icon('sparkle') ?> Free Smile Assessment</span>
    <h2>Your Invisible Smile Journey <span class="grad">Starts Today</span></h2>
    <p>Book a free, no-obligation assessment at <?= h($C['short']) ?> and find out if clear aligners are right for you.</p>
    <div class="alcta__btns">
      <a href="#" data-book data-service="Braces &amp; Aligners" class="btn btn--primary btn--lg">Book My Free Assessment <?= icon('arrow') ?></a>
      <a href="tel:<?= h($C['phone_raw']) ?>" class="btn btn--ghost btn--lg"><?= icon('phone') ?> <?= h($C['phone']) ?></a>
    </div>
  </div>
</section>

</div><!-- /.alpage -->

<script src="https://cdn.jsdelivr.net/npm/lenis@1.1.13/dist/lenis.min.js" defer></script>

<?php require __DIR__ . '/includes/footer.php'; ?>
