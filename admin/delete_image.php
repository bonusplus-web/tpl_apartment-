<?php
declare(strict_types=1);

require_once __DIR__ . '/../helpers.php';

require_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $_SESSION['flash'] = 'Invalid delete request.';
    header('Location: /admin/dashboard.php');
    exit;
}

$type = $_POST['type'] ?? '';
$index = isset($_POST['index']) ? (int) $_POST['index'] : null;
$fieldPath = $_POST['field'] ?? '';

$validTypes = ['services', 'properties', 'blogs'];
if (!in_array($type, $validTypes, true)) {
    $_SESSION['flash'] = 'Unsupported image target.';
    header('Location: /admin/dashboard.php');
    exit;
}

$settings = load_settings();
if (!isset($settings[$type][$index])) {
    $_SESSION['flash'] = 'Record not found.';
    header('Location: /admin/dashboard.php');
    exit;
}

$parts = array_filter(explode('.', $fieldPath), static fn ($value) => $value !== '');
$ref =& $settings[$type][$index];
$lastIndex = count($parts) - 1;
$currentPath = null;
foreach ($parts as $i => $part) {
    if ($i === $lastIndex) {
        $currentPath = $ref[$part] ?? null;
        $ref[$part] = '';
    } else {
        if (!isset($ref[$part]) || !is_array($ref[$part])) {
            $_SESSION['flash'] = 'Image field not found.';
            header('Location: /admin/dashboard.php');
            exit;
        }
        $ref =& $ref[$part];
    }
}

if ($currentPath && str_starts_with($currentPath, '/site_data/uploads/')) {
    $absolute = site_root() . $currentPath;
    $inUse = false;
    $iterator = new RecursiveIteratorIterator(new RecursiveArrayIterator($settings));
    foreach ($iterator as $value) {
        if ($value === $currentPath) {
            $inUse = true;
            break;
        }
    }
    if (!$inUse && is_file($absolute)) {
        unlink($absolute);
    }
}

if (save_active_settings($settings)) {
    $_SESSION['flash'] = 'Image removed successfully.';
} else {
    $_SESSION['flash'] = 'Unable to update configuration after removal.';
}

header('Location: /admin/dashboard.php');
exit;
