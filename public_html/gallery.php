<?php
require_once __DIR__ . '/includes/bootstrap.php';
$page = ['active'=>'gallery','title'=>'Gallery — '.s('clinic_name'),'description'=>'Take a look inside '.s('clinic_name').' and see smiles we’ve transformed in Mangalapuram, Trivandrum.'];
require __DIR__ . '/includes/header.php';
page_hero('Our Gallery', 'A look inside our clinic and the smiles we’ve cared for.', ['Gallery'=>'']);
$items = get_gallery();
function embed_src($url){ // normalise common video URLs to embed form
  if (preg_match('~youtu\.be/([\w-]+)~',$url,$m)||preg_match('~v=([\w-]+)~',$url,$m)) return 'https://www.youtube.com/embed/'.$m[1];
  return $url;
}
?>
<section class="section">
  <div class="container">
    <?php if ($items): ?>
    <div class="gallery">
      <?php foreach ($items as $g): ?>
        <figure class="gitem reveal">
          <?php if ($g['type']==='video'): ?>
            <div class="gitem__video"><iframe src="<?= h(embed_src($g['src'])) ?>" title="<?= h($g['title']?:'Video') ?>" loading="lazy" allowfullscreen frameborder="0"></iframe></div>
          <?php else: ?>
            <img src="<?= h($g['src']) ?>" alt="<?= h($g['title']?:'Clinic photo') ?>" loading="lazy">
          <?php endif; ?>
          <?php if ($g['title']): ?><figcaption><?= h($g['title']) ?></figcaption><?php endif; ?>
        </figure>
      <?php endforeach; ?>
    </div>
    <?php else: empty_state('sparkle', 'Our clinic photos are coming soon',
      'We are photographing our three Trivandrum clinics so you can see the space before you visit. You are welcome to drop in and look around any time.'); endif; ?>
  </div>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>
