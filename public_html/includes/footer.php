</main>
<?php $FBR = get_branches(); $FBR_NAMES = implode(' · ', array_map(fn($b)=>$b['name'], $FBR)); ?>

<footer class="footer">
  <div class="container footer__grid">
    <div class="footer__col">
      <a class="brand brand--light" href="/">
        <?php if ($C['logo']): ?>
          <img class="brand__img" src="<?= h($C['logo']) ?>" alt="<?= h($C['name']) ?>" height="44" style="filter:brightness(0) invert(1)">
        <?php else: ?>
          <span class="brand__mark"><?= icon('tooth') ?></span>
          <span class="brand__text"><span class="brand__name"><?= h($C['short']) ?></span><span class="brand__sub">Dental Clinic</span></span>
        <?php endif; ?>
      </a>
      <p class="footer__about"><?= h($C['tagline']) ?>. Advanced, painless and affordable dental care for the whole family<?= $FBR ? ', with branches across Trivandrum' : ' in Trivandrum' ?>.</p>
      <?php if ($SOC = socials()): ?>
      <div class="footer__socials">
        <?php foreach ($SOC as $k => $s): ?><a href="<?= h($s['url']) ?>" target="_blank" rel="noopener" aria-label="<?= h($s['label']) ?>"><?= icon($k) ?></a><?php endforeach; ?>
      </div>
      <?php endif; ?>
    </div>
    <div class="footer__col">
      <h4>Quick Links</h4>
      <a href="/about">About Us</a>
      <a href="/services">Services</a>
      <a href="/aligners">Clear Aligners</a>
      <a href="/before-after">Smile Gallery</a>
      <a href="/doctors">Our Doctors</a>
      <a href="/blog">Blog</a>
      <a href="/contact">Contact</a>
    </div>
    <div class="footer__col">
      <h4>Our Services</h4>
      <?php foreach (get_services(6) as $fsv): ?>
        <a href="/services/<?= h($fsv['slug']) ?>"><?= h($fsv['title']) ?></a>
      <?php endforeach; ?>
    </div>
    <div class="footer__col">
      <h4>Get in Touch</h4>
      <p class="footer__contact"><?= icon('pin') ?> <span><?= $FBR ? 'Branches: '.h($FBR_NAMES).' — Trivandrum' : h($C['address']) ?></span></p>
      <p class="footer__contact"><?= icon('phone') ?> <a href="tel:<?= h($C['phone_raw']) ?>"><?= h($C['phone']) ?></a></p>
      <p class="footer__contact"><?= icon('clock') ?> <span>Mon–Sat: <?= h($C['hours_weekday']) ?><br>Sun: <?= h($C['hours_sunday']) ?></span></p>
    </div>
  </div>
  <div class="footer__bar">
    <div class="container footer__bar-in">
      <span>&copy; <?= date('Y') ?> <?= h($C['name']) ?>. All rights reserved.</span>
      <span>Mangalapuram · Trivandrum · Kerala</span>
    </div>
  </div>
</footer>

<a class="fab fab--call" href="tel:<?= h($C['phone_raw']) ?>" aria-label="Call <?= h($C['phone']) ?>"><?= icon('phone') ?></a>
<a class="fab fab--wa" href="https://wa.me/<?= h($C['whatsapp']) ?>" target="_blank" rel="noopener" aria-label="Chat on WhatsApp"><?= icon('wa') ?></a>
<a class="mobile-cta" data-book><?= icon('clock') ?> Book Appointment</a>

<!-- Appointment pop-up (opens from any Book button) -->
<div class="modal" id="apptModal" aria-hidden="true">
  <div class="modal__overlay" data-close></div>
  <div class="modal__dialog" role="dialog" aria-modal="true" aria-labelledby="apptTitle">
    <button class="modal__x" data-close aria-label="Close"><?= icon('close') ?></button>
    <div class="modal__head">
      <span class="modal__badge"><?= icon('clock') ?></span>
      <h3 id="apptTitle">Book an Appointment</h3>
      <p>Leave your details and we’ll call you back to confirm — usually within hours.</p>
    </div>
    <form class="modal__form" id="apptForm" novalidate>
      <div class="field"><input type="text" name="name" required placeholder="Your name *"></div>
      <div class="field"><input type="tel" name="phone" required placeholder="Phone number *"></div>
      <div class="field"><select name="service">
        <option value="">What are you looking for?</option>
        <?php foreach (get_services() as $sv): ?><option><?= h($sv['title']) ?></option><?php endforeach; ?>
        <option>General Enquiry</option>
      </select></div>
      <div class="field"><textarea name="message" rows="2" placeholder="Description (optional)"></textarea></div>
      <input type="text" name="website" class="hp" tabindex="-1" autocomplete="off" aria-hidden="true">
      <button type="submit" class="btn btn--primary btn--block btn--lg">Request Appointment <?= icon('arrow') ?></button>
      <p class="modal__note" id="apptNote"><?= icon('shield') ?> Your details are private &amp; secure.</p>
    </form>
  </div>
</div>

<script src="/assets/js/main.js?v=7" defer></script>
<?php if (!empty($page['js'])): ?><script src="<?= h($page['js']) ?>" defer></script><?php endif; ?>
<?= s('body_scripts') ?>
</body>
</html>
