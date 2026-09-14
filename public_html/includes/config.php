<?php
/**
 * Bright Care Dental Clinic — central site configuration.
 * Edit clinic details, services, doctors and testimonials here.
 * All placeholder content is clearly marked and safe to replace.
 */

// ── Clinic identity ──────────────────────────────────────────────
$CLINIC = [
    'name'      => 'Bright Care Dental Clinic',
    'short'     => 'Bright Care',
    'tagline'   => 'Caring for Healthy, Confident Smiles',
    'phone'     => '+91 94475 60532',
    'phone_raw' => '919447560532',             // for tel: / wa.me
    'whatsapp'  => '919447560532',
    'email'     => 'info@brightcaredentalclinic.com',
    'address'   => 'Mangalapuram, Trivandrum, Kerala 695317',
    'maps'      => 'https://maps.google.com/?q=Mangalapuram+Trivandrum',
    'hours'     => [
        'Mon – Sat' => '9:00 AM – 8:00 PM',
        'Sunday'    => '10:00 AM – 1:00 PM',
    ],
    'socials'   => [
        'facebook'  => '#',
        'instagram' => '#',
        'youtube'   => '#',
    ],
];

// ── Services (icon is an inline-SVG key defined in header.php) ────
$SERVICES = [
    ['icon' => 'tooth',    'title' => 'General Dentistry',    'desc' => 'Routine check-ups, cleanings and preventive care that keep your smile healthy for life.'],
    ['icon' => 'root',     'title' => 'Root Canal Treatment', 'desc' => 'Painless, single-sitting root canals using modern rotary endodontics.'],
    ['icon' => 'implant',  'title' => 'Dental Implants',      'desc' => 'Permanent, natural-looking tooth replacement that restores full chewing strength.'],
    ['icon' => 'braces',   'title' => 'Braces & Aligners',    'desc' => 'Straighten teeth discreetly with clear aligners or modern braces.'],
    ['icon' => 'sparkle',  'title' => 'Teeth Whitening',      'desc' => 'Brighten your smile by several shades in a single comfortable visit.'],
    ['icon' => 'child',    'title' => 'Pediatric Dentistry',  'desc' => 'Gentle, friendly dental care designed especially for children.'],
];

// ── Why-choose-us feature points ─────────────────────────────────
$FEATURES = [
    ['icon' => 'shield',  'title' => 'Sterile & Safe',        'desc' => 'Hospital-grade sterilisation and single-use disposables on every visit.'],
    ['icon' => 'tech',    'title' => 'Advanced Technology',   'desc' => 'Digital X-rays, intra-oral scanning and laser dentistry.'],
    ['icon' => 'heart',   'title' => 'Painless Care',         'desc' => 'Gentle techniques and sedation options for anxiety-free treatment.'],
    ['icon' => 'wallet',  'title' => 'Transparent Pricing',   'desc' => 'Clear estimates upfront with flexible EMI options.'],
];

// ── Stats (animated counters) ────────────────────────────────────
$STATS = [
    ['num' => 15,    'suffix' => '+',  'label' => 'Years of Experience'],
    ['num' => 12000, 'suffix' => '+',  'label' => 'Happy Patients'],
    ['num' => 25,    'suffix' => '+',  'label' => 'Treatments Offered'],
    ['num' => 5,     'suffix' => '★',  'label' => 'Average Rating'],
];

// ── Doctors (placeholder photos via Unsplash; replace later) ─────
$DOCTORS = [
    ['name' => 'Dr. Aravind Menon',  'role' => 'Chief Dental Surgeon · BDS, MDS',  'img' => 'https://images.unsplash.com/photo-1612349317150-e413f6a5b16d?auto=format&fit=crop&w=600&q=70'],
    ['name' => 'Dr. Priya Nair',     'role' => 'Orthodontist · BDS, MDS',          'img' => 'https://images.unsplash.com/photo-1594824476967-48c8b964273f?auto=format&fit=crop&w=600&q=70'],
    ['name' => 'Dr. Rahul Krishnan', 'role' => 'Implantologist · BDS',             'img' => 'https://images.unsplash.com/photo-1622253692010-333f2da6031d?auto=format&fit=crop&w=600&q=70'],
];

// ── Testimonials ─────────────────────────────────────────────────
$TESTIMONIALS = [
    ['name' => 'Anjali S.',   'text' => 'Best dental experience I have ever had. The root canal was completely painless and the staff were so caring.',  'rating' => 5],
    ['name' => 'Mohammed R.', 'text' => 'Got my implants done here. Professional, hygienic and honest about pricing. Highly recommend Bright Care.',     'rating' => 5],
    ['name' => 'Lakshmi V.',  'text' => 'My kids actually look forward to their dental visits now. Wonderful pediatric care and a lovely clinic.',         'rating' => 5],
];

// ── SEO defaults (overridable per page) ──────────────────────────
$SEO = [
    'title'       => 'Bright Care Dental Clinic — Best Dental Clinic in Mangalapuram, Trivandrum',
    'description' => 'Bright Care Dental Clinic offers painless, advanced dental care in Mangalapuram, Trivandrum — implants, root canals, braces, whitening & pediatric dentistry. Book your appointment today.',
    'keywords'    => 'dental clinic Trivandrum, dentist Mangalapuram, dental implants Trivandrum, root canal, braces, teeth whitening, pediatric dentist Kerala',
    'url'         => 'https://rosybrown-wolverine-261784.hostingersite.com/',
    'image'       => 'https://rosybrown-wolverine-261784.hostingersite.com/assets/img/og-image.jpg',
];
