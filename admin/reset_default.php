<?php
declare(strict_types=1);

require_once __DIR__ . '/../helpers.php';

require_login();

$defaultPath = data_file('settings.default.json');
$activePath = data_file('settings.active.json');
$userPath = data_file('settings.user.json');

$default = load_json_file($defaultPath);
if ($default) {
    file_put_contents($activePath, json_encode($default, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    file_put_contents($userPath, json_encode(new stdClass()));
    $_SESSION['flash'] = 'Site content has been reset to developer defaults.';
} else {
    $_SESSION['flash'] = 'Unable to reset content. Default file missing.';
}

header('Location: /admin/dashboard.php');
exit;
