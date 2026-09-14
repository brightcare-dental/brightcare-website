<?php
/**
 * One-time installer: creates tables + seeds initial content.
 * Run from CLI:  php admin/_install.php
 * Safe to re-run (CREATE IF NOT EXISTS; seeds only when a table is empty).
 * DELETE this file after a successful install.
 */
require __DIR__ . '/../includes/db.php';
$pdo = db();
$log = [];

$tables = [
"admins" => "CREATE TABLE IF NOT EXISTS admins(
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(120), email VARCHAR(190),
  username VARCHAR(80) UNIQUE NOT NULL,
  password VARCHAR(255) NOT NULL,
  role VARCHAR(30) DEFAULT 'admin',
  last_login DATETIME NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",

"settings" => "CREATE TABLE IF NOT EXISTS settings(
  id INT AUTO_INCREMENT PRIMARY KEY,
  skey VARCHAR(80) UNIQUE NOT NULL,
  svalue TEXT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",

"services" => "CREATE TABLE IF NOT EXISTS services(
  id INT AUTO_INCREMENT PRIMARY KEY,
  slug VARCHAR(120) UNIQUE NOT NULL,
  icon VARCHAR(40) DEFAULT 'tooth',
  title VARCHAR(160) NOT NULL,
  excerpt TEXT,
  content LONGTEXT,
  image VARCHAR(255),
  seo_title VARCHAR(190), seo_desc VARCHAR(300), seo_keywords VARCHAR(300),
  sort INT DEFAULT 0,
  status TINYINT DEFAULT 1,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",

"doctors" => "CREATE TABLE IF NOT EXISTS doctors(
  id INT AUTO_INCREMENT PRIMARY KEY,
  slug VARCHAR(120) UNIQUE,
  name VARCHAR(160) NOT NULL,
  role VARCHAR(190),
  category VARCHAR(30) DEFAULT 'main',
  photo VARCHAR(255),
  bio TEXT,
  sort INT DEFAULT 0,
  status TINYINT DEFAULT 1,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",

"branches" => "CREATE TABLE IF NOT EXISTS branches(
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(160) NOT NULL,
  area VARCHAR(160),
  city VARCHAR(120) DEFAULT 'Trivandrum',
  address VARCHAR(400),
  phone VARCHAR(40),
  map_url VARCHAR(500),
  map_embed TEXT,
  hours VARCHAR(190),
  is_main TINYINT DEFAULT 0,
  sort INT DEFAULT 0,
  status TINYINT DEFAULT 1,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",

"smile_cases" => "CREATE TABLE IF NOT EXISTS smile_cases(
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",

"testimonials" => "CREATE TABLE IF NOT EXISTS testimonials(
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(160) NOT NULL,
  text TEXT,
  rating TINYINT DEFAULT 5,
  photo VARCHAR(255),
  sort INT DEFAULT 0,
  status TINYINT DEFAULT 1,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",

"gallery" => "CREATE TABLE IF NOT EXISTS gallery(
  id INT AUTO_INCREMENT PRIMARY KEY,
  type ENUM('image','video') DEFAULT 'image',
  category VARCHAR(80) DEFAULT 'Clinic',
  title VARCHAR(190),
  src VARCHAR(500),
  thumb VARCHAR(500),
  sort INT DEFAULT 0,
  status TINYINT DEFAULT 1,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",

"media" => "CREATE TABLE IF NOT EXISTS media(
  id INT AUTO_INCREMENT PRIMARY KEY,
  filename VARCHAR(255),
  path VARCHAR(500),
  mime VARCHAR(100),
  size INT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",

"pages" => "CREATE TABLE IF NOT EXISTS pages(
  id INT AUTO_INCREMENT PRIMARY KEY,
  slug VARCHAR(120) UNIQUE NOT NULL,
  title VARCHAR(190) NOT NULL,
  content LONGTEXT,
  hero_image VARCHAR(255),
  seo_title VARCHAR(190), seo_desc VARCHAR(300), seo_keywords VARCHAR(300),
  status TINYINT DEFAULT 1,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",

"posts" => "CREATE TABLE IF NOT EXISTS posts(
  id INT AUTO_INCREMENT PRIMARY KEY,
  slug VARCHAR(160) UNIQUE NOT NULL,
  title VARCHAR(190) NOT NULL,
  excerpt TEXT,
  content LONGTEXT,
  cover VARCHAR(255),
  category VARCHAR(80) DEFAULT 'General',
  seo_title VARCHAR(190), seo_desc VARCHAR(300),
  status TINYINT DEFAULT 1,
  published_at DATETIME NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",

"enquiries" => "CREATE TABLE IF NOT EXISTS enquiries(
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(160), phone VARCHAR(40), email VARCHAR(190),
  service VARCHAR(160), pref_date DATE NULL, message TEXT,
  source VARCHAR(40) DEFAULT 'website',
  status VARCHAR(30) DEFAULT 'new',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",
];

foreach ($tables as $name => $sql) {
    $pdo->exec($sql);
    $log[] = "table ok: $name";
}

$empty = fn($t) => (int)$pdo->query("SELECT COUNT(*) FROM $t")->fetchColumn() === 0;

// ── Seed admin ───────────────────────────────────────────────
$ADMIN_USER = 'admin';
$ADMIN_PASS = 'BrightCare@2026';
if ($empty('admins')) {
    $st = $pdo->prepare("INSERT INTO admins(name,email,username,password,role) VALUES(?,?,?,?,'admin')");
    $st->execute(['Administrator', 'info@brightcaredentalclinic.com', $ADMIN_USER, password_hash($ADMIN_PASS, PASSWORD_DEFAULT)]);
    $log[] = "seeded admin → user: $ADMIN_USER  pass: $ADMIN_PASS";
}

// ── Seed settings ────────────────────────────────────────────
if ($empty('settings')) {
    $settings = [
        'clinic_name' => 'Bright Care Dental Clinic',
        'clinic_short' => 'Bright Care',
        'tagline' => 'Caring for Healthy, Confident Smiles',
        'phone' => '+91 94475 60532', 'phone_raw' => '919447560532', 'whatsapp' => '919447560532',
        'email' => 'info@brightcaredentalclinic.com',
        'address' => 'Mangalapuram, Trivandrum, Kerala 695317',
        'maps_url' => 'https://maps.google.com/?q=Mangalapuram+Trivandrum',
        'map_embed' => '',
        'hours_weekday' => '9:00 AM – 8:00 PM', 'hours_sunday' => '10:00 AM – 1:00 PM',
        'social_facebook' => '#', 'social_instagram' => '#', 'social_youtube' => '#',
        'logo' => '/assets/img/logo.png', 'favicon' => '/assets/img/favicon.png',
        'color_primary' => '#2e9c8a', 'color_secondary' => '#2b474d', 'color_accent' => '#e2a33c',
        'seo_title' => 'Bright Care Dental Clinic — Dental Care in Mangalapuram, Trivandrum',
        'seo_desc' => 'Bright Care Dental Clinic provides advanced dental care in Mangalapuram, Trivandrum — implants, root canals, braces, whitening and pediatric dentistry. Book your appointment today.',
        'seo_keywords' => 'dental clinic Trivandrum, dentist Mangalapuram, dental implants, root canal, braces, teeth whitening',
        'head_scripts' => '', 'body_scripts' => '',
    ];
    $st = $pdo->prepare("INSERT INTO settings(skey,svalue) VALUES(?,?)");
    foreach ($settings as $k => $v) $st->execute([$k, $v]);
    $log[] = "seeded " . count($settings) . " settings";
}

// ── Seed services ────────────────────────────────────────────
if ($empty('services')) {
    $services = [
        ['general-dentistry','tooth','General Dentistry','Routine check-ups, cleanings and preventive care that keep your smile healthy for life.',
'<p>General dentistry is the everyday care that keeps small problems small. Most of what causes people real trouble — deep decay, abscesses, teeth that need extracting — began as something minor that went unnoticed for a year or two. Regular examination is how that gets caught.</p>
<h3>What a check-up actually involves</h3>
<p>We examine every tooth surface, check existing fillings and crowns for leakage, assess your gums and the bone supporting them, and check the soft tissues of your mouth, tongue and throat. Where something is not visible to the eye, a digital X-ray shows decay between teeth and below existing restorations.</p>
<h3>Scaling and polishing</h3>
<p>Plaque hardens into calculus within days, and once hardened, brushing cannot remove it. Professional scaling removes it from above and below the gumline using an ultrasonic scaler, followed by polishing. Most people need this every six to twelve months; if you have gum disease, smoke, or have diabetes, more often.</p>
<h3>Fillings</h3>
<p>Where decay has started, the affected tissue is removed and the cavity restored. We use tooth-coloured composite for most fillings, which bonds to the tooth and needs less healthy structure removed than older silver amalgam. A filling placed early is a twenty-minute appointment; the same tooth left for two years may need a root canal and a crown.</p>
<h3>Gum treatment</h3>
<p>Bleeding gums, persistent bad breath and gum recession are all signs of periodontal disease, which is the most common cause of tooth loss in adults — more common than decay. Early-stage gum disease is reversible with thorough cleaning. Once bone has been lost around a tooth, it does not grow back, which is why we treat this seriously and early.</p>
<h3>How often should you come?</h3>
<p>For most adults with healthy teeth and gums, once every six months. If you have a history of decay or gum disease, are pregnant, wear braces, or have a medical condition affecting healing, we will suggest a shorter interval and explain why.</p>
<h3>What it costs</h3>
<p>Examination and scaling are among the least expensive things we do, and the cost of a filling is a fraction of what the same tooth costs once it needs a root canal and crown. We give you a written estimate before any treatment starts, and there are no charges you have not agreed to first.</p>'],

        ['root-canal','root','Root Canal Treatment','Single-sitting root canals using modern rotary endodontics, under full local anaesthetic.',
'<p>Root canal treatment saves a tooth whose inner pulp has become infected. Without it, the alternative is extraction — and replacing a missing tooth with an implant or bridge is considerably more involved and more expensive than saving the one you have.</p>
<h3>How you know you might need one</h3>
<ul>
<li>Pain that lingers for more than a few seconds after something hot or cold</li>
<li>Pain that wakes you at night, or throbs when you lie down</li>
<li>Tenderness when biting on one particular tooth</li>
<li>A tooth that has darkened compared to its neighbours</li>
<li>A small pimple-like swelling on the gum near a tooth</li>
</ul>
<p>Some infected teeth cause no pain at all and are found on a routine X-ray. Pain is not a reliable measure of how serious the problem is.</p>
<h3>What happens during treatment</h3>
<p>The tooth and the surrounding area are fully anaesthetised first — you should feel pressure and movement, but not pain. A rubber dam isolates the tooth so it stays clean and dry. A small access opening is made, the infected pulp removed, and the canals cleaned and shaped with fine rotary instruments. The canals are disinfected, dried, and sealed with a rubber-like filling material. The access opening is then closed.</p>
<h3>How long it takes</h3>
<p>Most front teeth and premolars can be completed in a single sitting of about forty-five to ninety minutes. Molars have more canals and sometimes need two visits, particularly where there is an active abscess that needs to settle between appointments.</p>
<h3>Why the tooth usually needs a crown afterwards</h3>
<p>A root-treated back tooth has lost a good deal of internal structure and no longer has a blood supply, which makes it more brittle. Under normal chewing load it can fracture, and a fractured root-treated tooth usually cannot be saved. A crown distributes the force and protects it. For front teeth, a crown is not always necessary.</p>
<h3>Afterwards</h3>
<p>Mild tenderness for two to three days is normal and responds to ordinary painkillers. Avoid chewing hard food on that side until the permanent restoration is placed. Severe pain, swelling, or a bite that feels high are not expected — contact us if any of those occur.</p>
<h3>Does it hurt?</h3>
<p>The procedure has a reputation that dates from before modern anaesthetics and instruments. For most patients the appointment itself is comparable to having a deep filling. The pain people remember is usually the toothache that brought them in — which the treatment relieves.</p>'],

        ['dental-implants','implant','Dental Implants','Permanent, natural-looking tooth replacement that restores full chewing strength.',
'<p>An implant is a titanium post placed into the jawbone to act as an artificial tooth root, with a crown fitted on top. Unlike a bridge, it does not require cutting down the healthy teeth on either side, and unlike a denture, it is fixed and transmits chewing force into the bone the way a natural tooth does.</p>
<h3>Why replacing a missing tooth matters</h3>
<p>A gap is not only cosmetic. The teeth on either side drift into it and the opposing tooth over-erupts, which changes your bite and makes both harder to clean. The bone that once supported the missing tooth also begins to resorb — noticeably within the first year. An implant is the only replacement that loads the bone and slows that loss.</p>
<h3>The stages</h3>
<ul>
<li><strong>Assessment.</strong> A scan shows the bone volume available and the position of the nerve and sinus. This determines whether an implant is possible and what size is appropriate.</li>
<li><strong>Placement.</strong> The implant is placed under local anaesthetic. For a single tooth this typically takes under an hour. Most people return to work the next day.</li>
<li><strong>Healing.</strong> Over roughly three to four months, bone grows into direct contact with the implant surface and locks it in place. This stage cannot be shortened; loading an implant early is the most common reason they fail.</li>
<li><strong>The crown.</strong> Once integration is confirmed, impressions or a digital scan are taken and a crown is made to match the shape and shade of your other teeth.</li>
</ul>
<h3>When it takes longer</h3>
<p>If a tooth has been missing for years, the bone may have resorbed too far to hold an implant, and grafting is needed first — adding three to six months. Where the upper back teeth are involved, a sinus lift may be required. The scan at your first visit will tell you which applies before you commit to anything.</p>
<h3>Looking after an implant</h3>
<p>An implant cannot decay, but the gum and bone around it can become inflamed and infected in the same way as around a natural tooth — and when that happens the implant can be lost. Daily cleaning around the implant, including between teeth, and regular professional maintenance are what make the difference between an implant lasting decades and one failing in a few years.</p>
<h3>Are you a candidate?</h3>
<p>Most healthy adults are. Uncontrolled diabetes, heavy smoking and certain medications affecting bone metabolism reduce success rates significantly, and we will discuss these honestly with you rather than proceeding regardless. Implants are not placed in growing children.</p>'],

        ['braces-aligners','braces','Braces & Aligners','Straighten teeth discreetly with clear aligners or modern fixed braces.',
'<p>Orthodontic treatment moves teeth into better alignment. Straighter teeth are easier to clean, which reduces decay and gum problems over a lifetime, and a bite that meets evenly puts less strain on individual teeth and on the jaw joints.</p>
<h3>Clear aligners</h3>
<p>A series of custom transparent trays, each worn about two weeks, moving teeth gradually. They are removable, so you eat normally and clean your teeth normally, and they are close to invisible at conversational distance.</p>
<p>The honest catch is that they work only while worn — around twenty-two hours a day. If they spend mealtimes and meetings in a pocket, treatment stalls and the plan stops matching reality. For a disciplined adult they are excellent; for someone who knows they will not keep to it, fixed braces remove the variable entirely.</p>
<h3>Fixed braces</h3>
<p>Brackets bonded to the teeth and connected by a wire. Nothing to remember, nothing to lose, and significantly more control over difficult movements — large rotations, teeth that need moving vertically, and complex bite corrections. Modern brackets are considerably smaller than they once were, and ceramic tooth-coloured brackets are far less visible than metal.</p>
<h3>Which is right for you</h3>
<p>This depends on what your teeth actually need, not preference alone. Mild to moderate crowding, spacing, and relapse after previous orthodontic work usually suit aligners well. Significant skeletal discrepancies, impacted teeth and severe rotations are more predictable with fixed appliances. Some cases are best treated with a combination.</p>
<h3>How long it takes</h3>
<p>Minor corrections can be complete in six months. Comprehensive treatment in an adult typically runs eighteen months to two years. You will be given an estimate after your records — photographs, scans and X-rays — are taken and assessed, not before.</p>
<h3>Retainers are not optional</h3>
<p>Teeth have a genuine tendency to drift back toward their original positions, most strongly in the first year and to some degree for life. Whatever appliance moved them, a retainer is what keeps them there. Patients who stop wearing retainers commonly see noticeable relapse within a few years, and re-treatment costs far more than the retainer did.</p>
<h3>Is there an age limit?</h3>
<p>No. Healthy teeth and bone can be moved at any age, and a good proportion of orthodontic patients are adults. Children are different in that growth can be used to advantage, which is why an assessment around age seven is worthwhile even if treatment does not start then.</p>'],

        ['teeth-whitening','sparkle','Teeth Whitening','Professionally supervised whitening that lifts staining several shades safely.',
'<p>Whitening uses a peroxide-based gel to break down staining that has built up within the enamel and dentine over years of tea, coffee, tobacco and simply ageing. Done under supervision, it does not soften or damage tooth structure.</p>
<h3>Why an examination comes first</h3>
<p>This is the part over-the-counter kits skip, and it matters. Whitening gel over untreated decay, an exposed root surface or a cracked tooth causes genuine pain. Active gum disease makes it worse. A short examination rules these out, and any necessary treatment is done first.</p>
<h3>In-clinic whitening</h3>
<p>A protective barrier is placed over the gums and a higher-concentration gel applied to the teeth, usually in two or three cycles within a single appointment of about ninety minutes. The advantage is speed and that the whole process is controlled.</p>
<h3>Take-home whitening</h3>
<p>Custom trays are made from impressions of your teeth, and you wear them with a lower-concentration gel for a set period each day over one to two weeks. It takes longer, but the result is very even, the trays can be reused for future top-ups, and many patients find the gradual approach causes less sensitivity.</p>
<h3>What whitening will not do</h3>
<ul>
<li>It does not change the colour of crowns, veneers, bridges or composite fillings. If you have visible restorations on your front teeth, the surrounding teeth will lighten and those will not — they may need replacing to match.</li>
<li>It does not remove surface stain from tartar; that needs scaling first.</li>
<li>Grey discolouration from tetracycline or from a single dead tooth responds far less predictably than general yellowing, and may need a different approach such as internal bleaching or veneers.</li>
</ul>
<h3>Sensitivity</h3>
<p>Around half of patients experience some cold sensitivity during a course of whitening. It is temporary and settles within a few days of finishing. Using a desensitising toothpaste for two weeks beforehand reduces it considerably, and we can adjust the concentration or the wear time if it becomes uncomfortable.</p>
<h3>How long does it last?</h3>
<p>Typically one to three years, depending heavily on diet and smoking. Tea, coffee, red wine and tobacco will bring staining back sooner. Occasional top-up nights with take-home trays maintain the result at minimal cost.</p>'],

        ['pediatric-dentistry','child','Pediatric Dentistry','Gentle, unhurried dental care designed around how children actually experience a visit.',
'<p>Children are not small adults. The aim of a first visit is not treatment — it is that the clinic becomes an ordinary, unremarkable place. A child who is comfortable at seven is an adult who attends regularly at thirty.</p>
<h3>When to come first</h3>
<p>By the first birthday, or within six months of the first tooth appearing. That sounds very early, and the point is familiarity rather than intervention. Early visits also let us spot feeding and habit patterns that cause problems later, while they are still easy to change.</p>
<h3>What we do at a child appointment</h3>
<p>A gentle examination, often with a young child sitting on a parent&rsquo;s lap; a check that teeth are erupting in the expected sequence; a look for early decay, which in children can progress very quickly; and a conversation with you about brushing, diet and habits such as thumb-sucking or prolonged bottle use.</p>
<h3>Preventive treatments</h3>
<ul>
<li><strong>Fluoride application.</strong> A varnish painted onto the teeth that strengthens enamel and can arrest early decay before it needs a filling.</li>
<li><strong>Fissure sealants.</strong> A thin protective coating flowed into the deep grooves of the back teeth, where toothbrush bristles cannot reach. Most effective applied soon after the permanent molars erupt around age six.</li>
<li><strong>Space maintainers.</strong> Where a baby tooth is lost too early, a small appliance holds the space so the permanent tooth has somewhere to come through.</li>
</ul>
<h3>Why baby teeth matter</h3>
<p>A common belief is that decay in baby teeth does not matter because they fall out anyway. It does. Decayed baby teeth cause real pain and infection, can damage the developing permanent tooth beneath, and when lost early they allow the other teeth to drift into the space — which is a frequent cause of crowding later.</p>
<h3>Helping your child before the visit</h3>
<p>Keep the language neutral. Avoid words like pain, needle, hurt and drill, even in reassurance — saying &ldquo;it will not hurt&rdquo; introduces the idea. Describe it as someone counting their teeth. A picture book about a dental visit beforehand helps a great deal. And if you are anxious yourself, it is worth knowing that children read that very accurately.</p>
<h3>Brushing at home</h3>
<p>Begin as soon as the first tooth appears, twice daily, with a smear of fluoride toothpaste. Keep brushing for your child until around age seven — before that, most children simply do not have the manual control to clean effectively, however willing they are. No bottle in bed, at any age.</p>'],
    ];
    $st = $pdo->prepare("INSERT INTO services(slug,icon,title,excerpt,content,sort) VALUES(?,?,?,?,?,?)");
    foreach ($services as $i => $s) {
        $st->execute([$s[0],$s[1],$s[2],$s[3],$s[4],$i]);
    }
    $log[] = "seeded " . count($services) . " services";
}

// ── Seed doctors ─────────────────────────────────────────────
if ($empty('doctors')) {
    // Real roster. Photos deliberately blank — the initials avatar renders until portraits are supplied.
    $docs = [
        // Chief doctors
        ['dr-jeseer','Dr. Jeseer','Founder &amp; Managing Director · B.D.S.','main',''],
        ['dr-miyada-jeseer','Dr. Miyada Jeseer','Founder &amp; Managing Director','main',''],
        // Duty doctors
        ['dr-sreya-sp','Dr. Sreya S.P.','Duty Doctor','duty',''],
        ['dr-khadeeja-ashiq','Dr. Khadeeja Ashiq','Duty Doctor','duty',''],
        ['dr-sandra-victor','Dr. Sandra Victor','Duty Doctor','duty',''],
        // Consultant specialists
        ['dr-sujeesh-kuruvila-joy','Dr. Sujeesh Kuruvila Joy','Endodontist · Root Canal Specialist','consultant',''],
        ['dr-sachi','Dr. Sachi','Endodontist · Root Canal Specialist','consultant',''],
        ['dr-midhun','Dr. Midhun','Prosthodontist &amp; Implant Specialist · M.D.S.','consultant',''],
        ['dr-sruthy-gladson','Dr. Sruthy Gladson','Prosthodontist &amp; Implant Specialist','consultant',''],
        ['dr-gayathri','Dr. Gayathri','Paediatric Dental Specialist · M.D.S.','consultant',''],
        ['dr-dilhith-rishi','Dr. Dilhith Rishi','Oral &amp; Maxillofacial Surgeon','consultant',''],
        ['dr-sajna','Dr. Sajna','Periodontist &amp; Laser Specialist · M.D.S.','consultant',''],
        ['dr-jithin-ashok','Dr. Jithin Ashok','Cosmetic Dental Specialist','consultant',''],
        ['dr-jaisa-jithin','Dr. Jaisa Jithin','Cosmetic Dental Specialist','consultant',''],
    ];
    $st = $pdo->prepare("INSERT INTO doctors(slug,name,role,category,bio,sort) VALUES(?,?,?,?,?,?)");
    foreach ($docs as $i => $d) $st->execute([$d[0],$d[1],$d[2],$d[3],$d[4],$i]);
    $log[] = "seeded " . count($docs) . " doctors";
}

// ── Seed testimonials ────────────────────────────────────────
if ($empty('testimonials')) {
    $t = [
        ['Anjali S.','Best dental experience I have ever had. The root canal was completely painless and the staff were so caring.',5],
        ['Mohammed R.','Got my implants done here. Professional, hygienic and honest about pricing. Highly recommend Bright Care.',5],
        ['Lakshmi V.','My kids actually look forward to their dental visits now. Wonderful pediatric care and a lovely clinic.',5],
    ];
    $st = $pdo->prepare("INSERT INTO testimonials(name,text,rating,sort) VALUES(?,?,?,?)");
    foreach ($t as $i => $r) $st->execute([$r[0],$r[1],$r[2],$i]);
    $log[] = "seeded " . count($t) . " testimonials";
}

// ── Seed blog posts ──────────────────────────────────────────
if ($empty('posts')) {
    $posts = [
        ['why-gums-bleed-when-brushing','Why Your Gums Bleed When You Brush','Oral Health',
         'Bleeding gums are common, but they are not normal. Here is what the bleeding is telling you and when to get it checked.',
         '<p>Most people notice a little pink in the sink at some point and assume they brushed too hard. Occasionally that is true. Far more often, bleeding is the earliest sign of gingivitis — inflammation caused by plaque sitting along the gumline.</p>
          <h3>What is actually happening</h3>
          <p>Plaque is a soft film of bacteria that forms within hours of brushing. Where it is left undisturbed, the gum responds with inflammation: the tissue swells slightly, becomes rich with blood vessels, and bleeds at the lightest touch. Healthy gum does not bleed when you brush it.</p>
          <h3>The counterintuitive part</h3>
          <p>People often respond by brushing the area more gently, or avoiding it. That allows more plaque to accumulate and the bleeding worsens. Cleaning the area thoroughly — gently but completely, including between the teeth — usually settles it within one to two weeks.</p>
          <h3>When to come in</h3>
          <p>If bleeding persists beyond two weeks of careful cleaning, if your gums have receded, or if teeth feel loose, book an examination. Untreated gingivitis can progress to periodontitis, which damages the bone supporting the tooth and is not reversible.</p>'],

        ['root-canal-what-to-expect','What Actually Happens During a Root Canal','Treatments',
         'The procedure has a reputation it no longer deserves. Here is a step-by-step account of what the appointment involves.',
         '<p>Root canal treatment is probably the most feared and least understood procedure in dentistry. The reputation dates from a time before modern anaesthetics and rotary instruments. The reality today is closer to having a deep filling.</p>
          <h3>Why it is needed</h3>
          <p>Inside every tooth is a chamber containing nerve and blood vessels — the pulp. When decay, a crack, or trauma lets bacteria reach the pulp, it becomes infected. That infection does not resolve on its own, and the pain can be severe. Root canal treatment removes the infected tissue and seals the space, letting you keep the tooth.</p>
          <h3>The appointment</h3>
          <p>The tooth and surrounding area are fully anaesthetised — you should feel pressure but not pain. A small opening is made in the crown of the tooth. Fine instruments clean the canals, which are then shaped, disinfected and dried. The space is filled with a rubber-like material and sealed.</p>
          <h3>Afterwards</h3>
          <p>Some tenderness for two to three days is normal and responds to ordinary painkillers. A treated back tooth usually needs a crown afterwards, because the remaining tooth structure is more brittle and prone to fracture under chewing load.</p>'],

        ['clear-aligners-vs-braces','Clear Aligners or Braces: How to Choose','Orthodontics',
         'Both straighten teeth. They differ in what they can correct, what they cost you in daily discipline, and how visible they are.',
         '<p>The right choice depends less on preference than on what your teeth actually need. Here is an honest comparison.</p>
          <h3>What aligners do well</h3>
          <p>Clear aligners handle mild to moderate crowding, spacing, and relapse after previous orthodontic work. They are removable, so you eat normally and clean your teeth normally. They are close to invisible at conversational distance.</p>
          <h3>Where fixed braces still win</h3>
          <p>Significant rotations, large vertical movements, and complex bite corrections are more predictable with fixed appliances. If a tooth needs to move a long way or turn substantially, brackets give the orthodontist more control.</p>
          <h3>The discipline question</h3>
          <p>Aligners work only while they are in the mouth — around 22 hours a day. If they sit in a pocket during meals and meetings, treatment stalls and the plan stops matching reality. Fixed braces remove that variable entirely, which for some patients is the deciding factor.</p>
          <h3>How to decide</h3>
          <p>Have both assessed. A consultation with records — photographs, scans and X-rays — will show what each approach can realistically achieve for your case and how long it would take.</p>'],

        ['children-first-dental-visit','Your Child\'s First Dental Visit','Children',
         'When to come, what we do, and how to prepare a child so the appointment is calm rather than frightening.',
         '<p>The recommendation is a first visit by the child\'s first birthday, or within six months of the first tooth appearing. That sounds early — the point is not treatment, it is familiarity.</p>
          <h3>What the first visit involves</h3>
          <p>Usually a short, gentle look at the teeth and gums, often with the child sitting on a parent\'s lap. We check that teeth are erupting as expected, look for early decay, and talk through brushing, feeding and habits like thumb-sucking.</p>
          <h3>How to prepare them</h3>
          <p>Keep the language neutral and positive. Avoid words like "pain", "needle", "hurt" or "drill" — even in reassurance, since "it will not hurt" plants the idea. Describe it as someone counting their teeth. Reading a picture book about a dental visit beforehand helps a great deal.</p>
          <h3>Habits that matter more than anything we do</h3>
          <p>No bottle in bed. Brush twice daily with a smear of fluoride toothpaste as soon as the first tooth appears, and keep brushing for them until they are around seven — before that, most children lack the manual control to clean effectively.</p>'],

        ['teeth-whitening-safety','Is Teeth Whitening Safe?','Cosmetic',
         'Professionally supervised whitening is safe for most people. The risks come from unsupervised products and unrealistic expectations.',
         '<p>Whitening works by allowing a peroxide-based gel to break down staining within the enamel and dentine. Done correctly, it does not soften or damage tooth structure.</p>
          <h3>Sensitivity is the common side effect</h3>
          <p>Roughly half of patients get some cold sensitivity during a course of whitening. It is temporary and resolves within a few days of finishing. Using a desensitising toothpaste beforehand reduces it considerably.</p>
          <h3>What whitening will not do</h3>
          <p>It does not change the colour of crowns, veneers or composite fillings. If you have visible restorations on your front teeth, whitening the surrounding teeth can leave the restorations looking noticeably darker, and they may need replacing to match.</p>
          <h3>Why supervision matters</h3>
          <p>An examination first rules out decay, cracks and gum disease — whitening over any of those causes real pain. Custom trays also keep gel off the gums, which over-the-counter kits frequently fail to do.</p>'],

        ['dental-implant-timeline','How Long Does a Dental Implant Take?','Treatments',
         'From extraction to final crown is usually three to six months. Here is what happens during that time and why the waiting matters.',
         '<p>Patients are often surprised that an implant is not a single appointment. The timeline is driven by biology rather than convenience.</p>
          <h3>Stage one: assessment and placement</h3>
          <p>A scan establishes the bone volume and the position of nerves and sinuses. The implant — a titanium screw acting as an artificial root — is then placed into the jawbone under local anaesthetic. The procedure itself typically takes under an hour for a single tooth.</p>
          <h3>Stage two: osseointegration</h3>
          <p>This is the wait, and it is the part that cannot be rushed. Over roughly three to four months, bone grows into direct contact with the implant surface, locking it in place. Loading an implant before this is complete is the most common cause of failure.</p>
          <h3>Stage three: the crown</h3>
          <p>Once integration is confirmed, an abutment is attached and impressions or scans are taken for the crown, which is made to match your surrounding teeth in shape and shade.</p>
          <h3>When it takes longer</h3>
          <p>If the bone has resorbed after long-term tooth loss, grafting may be needed first, which adds several months. A scan at the consultation will tell you which situation applies.</p>'],
    ];
    $st = $pdo->prepare("INSERT INTO posts(slug,title,category,excerpt,content,published_at,status) VALUES(?,?,?,?,?,?,1)");
    foreach ($posts as $i => $p) {
        $st->execute([$p[0],$p[1],$p[2],$p[3],$p[4], date('Y-m-d H:i:s', strtotime("-" . ($i * 9 + 3) . " days"))]);
    }
    $log[] = "seeded " . count($posts) . " posts";
}

// ── Seed branches ────────────────────────────────────────────
if ($empty('branches')) {
    // Street addresses and per-branch map links still to be supplied by the clinic.
    $b = [
        ['Mangalapuram','','Trivandrum','Mangalapuram, Trivandrum, Kerala 695317','+91 94475 60532','https://maps.google.com/?q=Mangalapuram+Trivandrum','Mon–Sat 9:00 AM – 8:00 PM',1,0],
        ['Vengode','','Trivandrum','','+91 94475 60532','','Mon–Sat 9:00 AM – 8:00 PM',0,1],
        ['Mananakku','','Trivandrum','','+91 94475 60532','','Mon–Sat 9:00 AM – 8:00 PM',0,2],
    ];
    $st = $pdo->prepare("INSERT INTO branches(name,area,city,address,phone,map_url,hours,is_main,sort) VALUES(?,?,?,?,?,?,?,?,?)");
    foreach ($b as $r) $st->execute($r);
    $log[] = "seeded " . count($b) . " branches";
}

// ── Seed pages ───────────────────────────────────────────────
if ($empty('pages')) {
    $pages = [
        ['about','About Us',
'<p>Bright Care is a family dental practice serving Mangalapuram, Vengode and Mananakku. We look after children having their first check-up, adults who have not seen a dentist in a decade, and everyone in between — and we try to make all three feel equally unremarkable.</p>
<h3>How we work</h3>
<p>We explain what we find before we treat it. You will be shown the X-ray or the intra-oral photograph, told what the options are including doing nothing for now, and given a written estimate before anything starts. If a tooth can be watched rather than filled, we will tell you that — it is a shorter conversation than the one that follows unnecessary treatment.</p>
<h3>Comfort is a clinical matter, not a courtesy</h3>
<p>A patient who is tense is harder to treat and heals less comfortably. We take time over anaesthetic, we stop when you ask, and we would rather book a longer appointment than rush one. For patients who have avoided dental care for years because of a bad experience, tell us at the start — it changes how we plan the visit.</p>
<h3>Sterilisation</h3>
<p>Instruments are cleaned, packaged and autoclaved between every patient, with single-use disposables wherever they exist. Handpieces are sterilised, not just wiped. This is not a selling point so much as the baseline, and you are welcome to ask to see how it is done.</p>
<h3>Three clinics, one standard</h3>
<p>Our branches share the same protocols, the same sterilisation standards and the same records system, so you can be seen at whichever location suits you and your history follows you there.</p>
<h3>What we treat</h3>
<p>Routine examinations and cleaning, fillings, root canal treatment, extractions, crowns and bridges, dental implants, braces and clear aligners, whitening, and children&rsquo;s dentistry. Where a case is better handled by a specialist we do not ordinarily see, we will say so and refer you rather than attempt it.</p>'],
        ['contact','Contact Us','<p>Get in touch with Bright Care Dental Clinic — Mangalapuram, Vengode and Mananakku, Trivandrum.</p>'],
    ];
    $st = $pdo->prepare("INSERT INTO pages(slug,title,content) VALUES(?,?,?)");
    foreach ($pages as $p) $st->execute([$p[0],$p[1],$p[2]]);
    $log[] = "seeded " . count($pages) . " pages";
}

echo implode("\n", $log) . "\nDONE\n";
