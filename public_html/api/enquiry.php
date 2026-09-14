<?php
/** Booking / contact form endpoint → stores in `enquiries`. Returns JSON. */
require __DIR__ . '/../includes/bootstrap.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') { http_response_code(405); exit(json_encode(['ok'=>false,'error'=>'Method not allowed'])); }

// Honeypot: real users never fill this hidden field.
if (!empty($_POST['website'])) { exit(json_encode(['ok'=>true])); }

$name  = trim($_POST['name'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$email = trim($_POST['email'] ?? '');
$svc   = trim($_POST['service'] ?? '');
$date  = trim($_POST['pref_date'] ?? '');
$msg   = trim($_POST['message'] ?? '');

$errors = [];
if (mb_strlen($name) < 2) $errors[] = 'Please enter your name.';
if (!preg_match('/^[0-9+\-\s()]{7,20}$/', $phone)) $errors[] = 'Please enter a valid phone number.';
if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Please enter a valid email.';
if ($date !== '' && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) $date = '';

if ($errors) { http_response_code(422); exit(json_encode(['ok'=>false,'error'=>implode(' ', $errors)])); }

try {
    db()->prepare('INSERT INTO enquiries(name,phone,email,service,pref_date,message,source,status) VALUES(?,?,?,?,?,?,?,?)')
        ->execute([$name, $phone, $email, $svc, $date ?: null, $msg, 'website', 'new']);
    echo json_encode(['ok'=>true,'message'=>'Thank you! We’ll call you shortly to confirm your appointment.']);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['ok'=>false,'error'=>'Something went wrong. Please call us instead.']);
}
