<?php
declare(strict_types=1);

require_once __DIR__ . '/../helpers.php';

require_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_FILES['image']['tmp_name'])) {
    $_SESSION['flash'] = 'Invalid image upload request.';
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

$file = $_FILES['image'];
if ($file['error'] !== UPLOAD_ERR_OK) {
    $_SESSION['flash'] = 'Upload failed. Please try again.';
    header('Location: /admin/dashboard.php');
    exit;
}

$ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
$allowed = ['jpg', 'jpeg', 'png', 'webp', 'svg'];
if (!in_array($ext, $allowed, true)) {
    $_SESSION['flash'] = 'Unsupported file type.';
    header('Location: /admin/dashboard.php');
    exit;
}

$uploadsDir = data_dir() . '/uploads';
if (!is_dir($uploadsDir)) {
    mkdir($uploadsDir, 0775, true);
}

$filename = time() . '-' . normalize_upload_filename($file['name']);
$target = $uploadsDir . '/' . $filename;
if (!move_uploaded_file($file['tmp_name'], $target)) {
    $_SESSION['flash'] = 'Unable to store uploaded image.';
    header('Location: /admin/dashboard.php');
    exit;
}

$relativePath = '/site_data/uploads/' . $filename;
$parts = array_filter(explode('.', $fieldPath), static fn ($value) => $value !== '');
$ref =& $settings[$type][$index];
$lastIndex = count($parts) - 1;
foreach ($parts as $i => $part) {
    if ($i === $lastIndex) {
        $ref[$part] = $relativePath;
        break;
    }
    if (!isset($ref[$part]) || !is_array($ref[$part])) {
        $ref[$part] = [];
    }
    $ref =& $ref[$part];
}

if (save_active_settings($settings)) {
    $_SESSION['flash'] = 'Image updated successfully.';
} else {
    $_SESSION['flash'] = 'Image saved, but configuration update failed.';
}

header('Location: /admin/dashboard.php');
exit;
