<?php
declare(strict_types=1);

require_once __DIR__ . '/../helpers.php';

$user = require_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /admin/dashboard.php');
    exit;
}

$settings = load_settings();

$sanitize = static function ($value) use (&$sanitize) {
    if (is_array($value)) {
        $clean = [];
        foreach ($value as $k => $v) {
            $clean[$k] = $sanitize($v);
        }
        return $clean;
    }

    if (is_string($value)) {
        return trim($value);
    }

    return $value;
};

if (isset($_POST['site'])) {
    $settings['site'] = array_replace_recursive($settings['site'], $sanitize($_POST['site']));
}

if (isset($_POST['languages'])) {
    foreach ($sanitize($_POST['languages']) as $code => $langData) {
        $existing = $settings['languages'][$code] ?? [];
        $settings['languages'][$code] = array_replace_recursive($existing, $langData);
    }
}

$normalizeList = static function ($list) use ($sanitize) {
    if (!is_array($list)) {
        return [];
    }
    $clean = [];
    foreach ($list as $item) {
        $clean[] = $sanitize($item);
    }
    return $clean;
};

if (isset($_POST['services'])) {
    $settings['services'] = $normalizeList($_POST['services']);
}

if (isset($_POST['properties'])) {
    $settings['properties'] = $normalizeList($_POST['properties']);
}

if (isset($_POST['features'])) {
    $settings['features'] = $normalizeList($_POST['features']);
}

if (isset($_POST['blogs'])) {
    $settings['blogs'] = $normalizeList($_POST['blogs']);
}

if (isset($_POST['footer_links'])) {
    $settings['footer_links'] = $sanitize($_POST['footer_links']);
}

if (isset($_POST['contact_form'])) {
    $settings['contact_form'] = array_replace($settings['contact_form'] ?? [], $sanitize($_POST['contact_form']));
}

$settings['site']['primary_color'] = $settings['site']['primary_color'] ?: '#10b981';

if (save_active_settings($settings)) {
    $_SESSION['flash'] = 'Settings saved successfully.';
} else {
    $_SESSION['flash'] = 'Unable to save settings. Please try again.';
}

header('Location: /admin/dashboard.php');
exit;
