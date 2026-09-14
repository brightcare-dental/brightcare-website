<?php
/** One-time: create smile_cases table + seed from current placeholders. Run via CLI then delete. */
require __DIR__ . '/../includes/db.php';
$pdo = db(); $log = [];
$pdo->exec("CREATE TABLE IF NOT EXISTS smile_cases(
  id INT AUTO_INCREMENT PRIMARY KEY,
  type VARCHAR(20) NOT NULL DEFAULT 'before-after',
  slug VARCHAR(160) UNIQUE NOT NULL,
  title VARCHAR(190) NOT NULL,
  subtitle VARCHAR(255),
  service VARCHAR(120),
  before_img VARCHAR(255),
  after_img VARCHAR(255),
  image VARCHAR(255),
  content LONGTEXT,
  seo_title VARCHAR(190), seo_desc VARCHAR(300),
  sort INT DEFAULT 0,
  status TINYINT DEFAULT 1,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
$log[] = "table ok";

if ((int)$pdo->query('SELECT COUNT(*) FROM smile_cases')->fetchColumn() === 0) {
  $u = 'https://images.unsplash.com/';
  $rows = [
    // type, slug, title, subtitle/service, before, after, image, content
    ['before-after','aligners-crowded','Crowded Teeth, Beautifully Aligned','Clear Aligners',$u.'photo-1606811971618-4486d14f3f99?auto=format&fit=crop&w=700&q=65',$u.'photo-1588776814546-1ffcf47267a5?auto=format&fit=crop&w=700&q=65','','<p>This patient came to us self-conscious about crowding in the upper front teeth. Using a custom clear-aligner plan, we gradually guided each tooth into alignment over several months — discreetly and comfortably.</p><p>The result is a naturally straight, even smile with no metal braces and minimal disruption to daily life.</p>'],
    ['before-after','whitening-stains','Years of Staining, Brightened in One Visit','Teeth Whitening',$u.'photo-1607619056574-7b8d3ee536b2?auto=format&fit=crop&w=700&q=65',$u.'photo-1581585504991-9d3e6c3b6e0a?auto=format&fit=crop&w=700&q=65','','<p>Years of coffee and tea had dulled this smile. A single in-clinic professional whitening session lifted the shade by several levels — safely and without sensitivity.</p>'],
    ['before-after','implant-gap','A Confident Smile, Gap Restored','Dental Implants',$u.'photo-1612349317150-e413f6a5b16d?auto=format&fit=crop&w=700&q=65',$u.'photo-1622253692010-333f2da6031d?auto=format&fit=crop&w=700&q=65','','<p>After losing a front tooth, the patient wanted a permanent, natural-looking solution. We placed a titanium implant and a custom crown that blends seamlessly with the surrounding teeth.</p>'],
    ['before-after','bonding-chip','Chipped Tooth, Seamlessly Repaired','Cosmetic Bonding',$u.'photo-1620916566398-39f1143ab7be?auto=format&fit=crop&w=700&q=65',$u.'photo-1629909613654-28e377c37b09?auto=format&fit=crop&w=700&q=65','','<p>A small chip on a front tooth was repaired in a single visit using tooth-coloured composite bonding — restoring a smooth, natural edge.</p>'],
    ['makeover','full-smile-makeover','Full Smile Makeover','Veneers, whitening & alignment combined','','',$u.'photo-1588776814546-1ffcf47267a5?auto=format&fit=crop&w=800&q=65','<p>A complete smile transformation combining alignment, whitening and porcelain veneers — carefully designed around the patient’s face for a balanced, natural result.</p>'],
    ['makeover','veneers-whitening','Veneers & Whitening','Porcelain veneers + whitening','','',$u.'photo-1607619056574-7b8d3ee536b2?auto=format&fit=crop&w=800&q=65','<p>Custom porcelain veneers paired with whitening for a bright, even and durable smile.</p>'],
    ['makeover','aligner-treatment','Aligner Smile Design','Clear aligner treatment','','',$u.'photo-1609840114035-3c981b782dfe?auto=format&fit=crop&w=800&q=65','<p>A discreet clear-aligner journey that reshaped the smile line over several months.</p>'],
    ['makeover','implant-restoration','Implant Restoration','Implant-supported restoration','','',$u.'photo-1622253692010-333f2da6031d?auto=format&fit=crop&w=800&q=65','<p>Multiple implant-supported crowns restored both function and aesthetics for this patient.</p>'],
    ['case-study','invisible-aligners-9-months','From Crowded to Confident in 9 Months','Invisible Aligners','','',$u.'photo-1629909613654-28e377c37b09?auto=format&fit=crop&w=900&q=68','<h2>The challenge</h2><p>A 24-year-old patient came to us self-conscious about crowded front teeth, but did not want visible braces.</p><h2>Our approach</h2><p>We created a custom clear-aligner plan, mapping every tooth movement in 3D before treatment began. The patient changed trays every two weeks.</p><h2>The result</h2><p>Over 9 months, the arch was gently realigned — no braces, no one noticing — leaving a naturally straight, confident smile.</p>'],
    ['case-study','single-tooth-implant','Restoring a Missing Molar','Single-Tooth Implant','','',$u.'photo-1612349317150-e413f6a5b16d?auto=format&fit=crop&w=900&q=68','<h2>The challenge</h2><p>After losing a molar, the patient struggled to chew comfortably on one side.</p><h2>Our approach</h2><p>We placed a titanium implant and, after healing, fitted a natural-looking crown — a staged, painless procedure.</p><h2>The result</h2><p>Full chewing strength and a complete smile were restored.</p>'],
    ['case-study','full-mouth-rehab','Rebuilding a Worn, Painful Bite','Full-Mouth Rehabilitation','','',$u.'photo-1581585504991-9d3e6c3b6e0a?auto=format&fit=crop&w=900&q=68','<h2>The challenge</h2><p>Years of wear had left the patient with a collapsed, uncomfortable bite.</p><h2>Our approach</h2><p>Through a carefully sequenced plan of crowns and restorations, we rebuilt the bite to a comfortable, functional height.</p><h2>The result</h2><p>Comfort, function and a complete smile restored.</p>'],
  ];
  $st = $pdo->prepare("INSERT INTO smile_cases(type,slug,title,service,before_img,after_img,image,content,sort) VALUES(?,?,?,?,?,?,?,?,?)");
  foreach ($rows as $i => $r) $st->execute([$r[0],$r[1],$r[2],$r[3],$r[4],$r[5],$r[6],$r[7],$i]);
  $log[] = "seeded " . count($rows) . " cases";
}
foreach (['before-after','makeover','case-study'] as $t) {
  $s = $pdo->prepare("SELECT COUNT(*) FROM smile_cases WHERE type=?"); $s->execute([$t]);
  $log[] = "$t: " . $s->fetchColumn();
}
echo implode("\n", $log) . "\nDONE\n";
