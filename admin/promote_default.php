<?php
declare(strict_types=1);

require_once __DIR__ . '/../helpers.php';

$user = require_login();

if (!admin_has_role($user, 'developer')) {
    $_SESSION['flash'] = 'You do not have permission to promote defaults.';
    header('Location: /admin/dashboard.php');
    exit;
}

$active = load_settings();
$defaultPath = data_file('settings.default.json');

if (file_put_contents($defaultPath, json_encode($active, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE))) {
    $_SESSION['flash'] = 'Current content promoted to developer default.';
} else {
    $_SESSION['flash'] = 'Unable to promote current content.';
}

header('Location: /admin/dashboard.php');
exit;
