<?php
require_once __DIR__ . '/includes/bootstrap.php';
$page = ['active'=>'contact','title'=>'Contact & Book — '.s('clinic_name'),'description'=>'Book an appointment or get directions to '.s('clinic_name').', Mangalapuram, Trivandrum. Call, WhatsApp or use our form.'];
require __DIR__ . '/includes/header.php';
page_hero('Contact & Appointments', 'We’d love to see your smile — reach out or book below.', ['Contact'=>'']);
$services = get_services();
?>
<section class="section" id="book">
  <div class="container contact__grid">
    <div class="contact__info reveal">
      <div class="cinfo"><span class="cinfo__ic"><?= icon('pin') ?></span><div><strong>Visit Us</strong><p><?= h($C['address']) ?></p><a href="<?= h($C['maps']) ?>" target="_blank" rel="noopener" class="cinfo__link">Get directions <?= icon('arrow') ?></a></div></div>
      <div class="cinfo"><span class="cinfo__ic"><?= icon('phone') ?></span><div><strong>Call / WhatsApp</strong><p><a href="tel:<?= h($C['phone_raw']) ?>"><?= h($C['phone']) ?></a></p><a href="https://wa.me/<?= h($C['whatsapp']) ?>" target="_blank" rel="noopener" class="cinfo__link">Chat on WhatsApp <?= icon('arrow') ?></a></div></div>
      <div class="cinfo"><span class="cinfo__ic"><?= icon('clock') ?></span><div><strong>Opening Hours</strong><p>Mon – Sat: <?= h($C['hours_weekday']) ?><br>Sunday: <?= h($C['hours_sunday']) ?></p></div></div>
      <div class="cinfo"><span class="cinfo__ic"><?= icon('star') ?></span><div><strong>Email</strong><p><a href="mailto:<?= h($C['email']) ?>"><?= h($C['email']) ?></a></p></div></div>
    </div>
    <form class="book__form book__form--light reveal" id="bookForm" novalidate>
      <h3>Request an Appointment</h3>
      <div class="field"><input type="text" name="name" required placeholder="Full name *"></div>
      <div class="field"><input type="tel" name="phone" required placeholder="Phone number *"></div>
      <div class="field"><input type="email" name="email" placeholder="Email (optional)"></div>
      <div class="field"><select name="service"><option value="">Select a service</option><?php foreach($services as $sv): ?><option><?= h($sv['title']) ?></option><?php endforeach; ?><option>Other</option></select></div>
      <div class="field"><input type="date" name="pref_date" aria-label="Preferred date"></div>
      <div class="field"><textarea name="message" rows="3" placeholder="Message (optional)"></textarea></div>
      <input type="text" name="website" class="hp" tabindex="-1" autocomplete="off" aria-hidden="true">
      <button type="submit" class="btn btn--primary btn--block btn--lg">Request Appointment <?= icon('arrow') ?></button>
      <p class="book__note" id="formNote">We’ll call you to confirm. Your details are kept private.</p>
    </form>
  </div>
</section>
<?php $branches = get_branches(); if ($branches): ?>
<section class="section section--alt" id="branches">
  <div class="container">
    <div class="section__head reveal"><span class="kicker">Our Locations</span><h2>Find Us Across <span class="grad">Trivandrum</span></h2><p>Visit whichever branch is most convenient for you.</p></div>
    <div class="branches">
      <?php foreach ($branches as $b): ?>
        <article class="branch reveal">
          <span class="branch__ic"><?= icon('pin') ?></span>
          <h3><?= h($b['name']) ?></h3>
          <p class="branch__loc"><?= h($b['area'] ? $b['area'] . ', ' . $b['city'] : $b['city']) ?></p>
          <?php if ($b['address']): ?><p class="branch__addr"><?= h($b['address']) ?></p><?php endif; ?>
          <?php if ($b['phone']): ?><p class="branch__addr"><?= icon('phone') ?> <a href="tel:<?= h(preg_replace('/\s+/','',$b['phone'])) ?>"><?= h($b['phone']) ?></a></p><?php endif; ?>
          <?php if ($b['hours']): ?><p class="branch__addr"><?= icon('clock') ?> <?= h($b['hours']) ?></p><?php endif; ?>
        </article>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>
<?php require __DIR__ . '/includes/footer.php'; ?>
