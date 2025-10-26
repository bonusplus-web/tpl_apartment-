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
$settings = load_settings();
$langCodes = array_keys($settings['languages']);

$makeLangArray = static function (array $codes, string $value = ''): array {
    $data = [];
    foreach ($codes as $code) {
        $data[$code] = $value;
    }
    return $data;
};

switch ($type) {
    case 'services':
        $settings['services'][] = [
            'image' => '',
            'link' => '#',
            'title' => $makeLangArray($langCodes, ''),
            'text' => $makeLangArray($langCodes, ''),
            'link_label' => $makeLangArray($langCodes, '')
        ];
        $_SESSION['flash'] = 'Service card added. Please upload an image.';
        break;

    case 'properties':
        $settings['properties'][] = [
            'image' => '',
            'badge_color' => 'green',
            'badge' => $makeLangArray($langCodes, ''),
            'title' => $makeLangArray($langCodes, ''),
            'location' => $makeLangArray($langCodes, ''),
            'media' => ['photos' => 0, 'videos' => 0],
            'price' => [
                'amount' => '',
                'suffix' => $makeLangArray($langCodes, '')
            ],
            'description' => $makeLangArray($langCodes, ''),
            'stats' => [
                ['value' => 0, 'icon' => 'bed-outline', 'label' => $makeLangArray($langCodes, '')],
                ['value' => 0, 'icon' => 'man-outline', 'label' => $makeLangArray($langCodes, '')],
                ['value' => 0, 'icon' => 'square-outline', 'label' => $makeLangArray($langCodes, '')]
            ],
            'author' => [
                'name' => $makeLangArray($langCodes, ''),
                'title' => $makeLangArray($langCodes, ''),
                'avatar' => ''
            ]
        ];
        $_SESSION['flash'] = 'Property card added. Please upload images.';
        break;

    case 'features':
        $settings['features'][] = [
            'icon' => 'home-outline',
            'href' => '#',
            'title' => $makeLangArray($langCodes, '')
        ];
        $_SESSION['flash'] = 'Feature card added.';
        break;

    case 'blogs':
        $settings['blogs'][] = [
            'image' => '',
            'link' => '#',
            'date' => date('Y-m-d'),
            'title' => $makeLangArray($langCodes, ''),
            'category' => $makeLangArray($langCodes, ''),
            'author' => $makeLangArray($langCodes, ''),
            'date_label' => $makeLangArray($langCodes, ''),
            'read_more' => $makeLangArray($langCodes, '')
        ];
        $_SESSION['flash'] = 'Blog card added. Please upload an image.';
        break;

    default:
        $_SESSION['flash'] = 'Unsupported card type.';
        header('Location: /admin/dashboard.php');
        exit;
}

save_active_settings($settings);
header('Location: /admin/dashboard.php');
exit;
