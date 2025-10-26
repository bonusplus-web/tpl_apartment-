<?php
declare(strict_types=1);

require_once __DIR__ . '/../helpers.php';

require_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $_SESSION['flash'] = 'Invalid card action.';
    header('Location: /admin/dashboard.php');
    exit;
}

$type = $_POST['type'] ?? '';
$index = isset($_POST['index']) ? (int) $_POST['index'] : null;

$settings = load_settings();

if (!isset($settings[$type][$index])) {
    $_SESSION['flash'] = 'Item not found.';
    header('Location: /admin/dashboard.php');
    exit;
}

$removed = $settings[$type][$index];
unset($settings[$type][$index]);
$settings[$type] = array_values($settings[$type]);

if (!save_active_settings($settings)) {
    $_SESSION['flash'] = 'Unable to delete item.';
    header('Location: /admin/dashboard.php');
    exit;
}

$collectImages = static function ($item): array {
    $images = [];
    $iterator = new RecursiveIteratorIterator(new RecursiveArrayIterator((array) $item));
    foreach ($iterator as $value) {
        if (is_string($value) && str_starts_with($value, '/site_data/uploads/')) {
            $images[] = $value;
        }
    }
    return array_unique($images);
};

$existingIterator = new RecursiveIteratorIterator(new RecursiveArrayIterator($settings));
$imageUsage = [];
foreach ($existingIterator as $value) {
    if (is_string($value) && str_starts_with($value, '/site_data/uploads/')) {
        $imageUsage[$value] = true;
    }
}

foreach ($collectImages($removed) as $path) {
    if (!isset($imageUsage[$path])) {
        $file = site_root() . $path;
        if (is_file($file)) {
            unlink($file);
        }
    }
}

$_SESSION['flash'] = 'Item removed successfully.';
header('Location: /admin/dashboard.php');
exit;
