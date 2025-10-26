<?php
declare(strict_types=1);

require_once __DIR__ . '/../helpers.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    respond_json(['success' => false, 'message' => 'Method not allowed'], 405);
}

ensure_session();
$settings = load_settings();
$lang = handle_language_switch($settings);
$langContent = $settings['languages'][$lang]['contact'] ?? [];
$messages = [
    'success' => $langContent['success'] ?? 'Thank you! Your message has been received.',
    'error' => $langContent['error'] ?? 'Sorry, something went wrong. Please try again later.'
];

$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$message = trim($_POST['message'] ?? '');
$honeypot = trim($_POST['website'] ?? '');
$token = $_POST['token'] ?? '';
$startedAt = isset($_POST['started_at']) ? (int) $_POST['started_at'] : 0;

if ($honeypot !== '') {
    respond_json(['success' => true, 'message' => $messages['success']]);
}

if (!$token || !isset($_SESSION['contact_token']) || !hash_equals($_SESSION['contact_token'], $token)) {
    respond_json(['success' => false, 'message' => $messages['error']], 400);
}

$delay = (int) ($settings['contact_form']['delay_seconds'] ?? 2);
if ($startedAt === 0 || (time() - $startedAt) < $delay) {
    respond_json(['success' => false, 'message' => $messages['error']], 429);
}

$rateLimit = (int) ($settings['contact_form']['rate_limit_seconds'] ?? 120);
$ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
$rateFile = data_dir() . '/rate_limit/' . md5($ip) . '.json';
$rateDir = dirname($rateFile);
if (!is_dir($rateDir)) {
    mkdir($rateDir, 0775, true);
}
$now = time();
if (is_file($rateFile)) {
    $last = (int) json_decode((string) file_get_contents($rateFile), true);
    if ($last && ($now - $last) < $rateLimit) {
        respond_json(['success' => false, 'message' => $messages['error']], 429);
    }
}
file_put_contents($rateFile, json_encode($now));

if ($name === '' || $email === '' || $message === '') {
    respond_json(['success' => false, 'message' => $messages['error']], 422);
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    respond_json(['success' => false, 'message' => $messages['error']], 422);
}

$payload = [
    'name' => $name,
    'email' => $email,
    'phone' => $phone,
    'message' => $message,
    'ip' => $ip,
    'submitted_at' => date('c', $now)
];

$logDir = data_dir() . '/backups/contact_submissions';
if (!is_dir($logDir)) {
    mkdir($logDir, 0775, true);
}
file_put_contents($logDir . '/contact-' . date('Ymd-His') . '-' . bin2hex(random_bytes(4)) . '.json', json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

$newToken = bin2hex(random_bytes(16));
$_SESSION['contact_token'] = $newToken;
$_SESSION['contact_time'] = time();
respond_json([
    'success' => true,
    'message' => $messages['success'],
    'token' => $newToken,
    'started_at' => $_SESSION['contact_time']
]);
