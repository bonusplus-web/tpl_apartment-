<?php
declare(strict_types=1);

function asset(string $path): string
{
    return $path;
}

function site_root(): string
{
    return __DIR__;
}

function data_dir(): string
{
    return site_root() . '/site_data';
}

function data_file(string $filename): string
{
    return data_dir() . '/' . $filename;
}

function load_json_file(string $file): array
{
    if (!is_file($file)) {
        return [];
    }

    $json = file_get_contents($file);
    if ($json === false) {
        return [];
    }

    $data = json_decode($json, true);
    return is_array($data) ? $data : [];
}

function load_settings(): array
{
    static $settings;
    if ($settings !== null) {
        return $settings;
    }

    $activePath = data_file('settings.active.json');
    $defaultPath = data_file('settings.default.json');
    $userPath = data_file('settings.user.json');

    $default = load_json_file($defaultPath);
    $user = load_json_file($userPath);
    $active = load_json_file($activePath);

    if (!$active) {
        $active = array_replace_recursive($default, $user);
    }

    $settings = $active;
    return $settings;
}

function get_available_languages(array $settings): array
{
    return isset($settings['languages']) && is_array($settings['languages'])
        ? array_keys($settings['languages'])
        : ['en'];
}

function ensure_session(): void
{
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start([
            'cookie_httponly' => true,
            'cookie_samesite' => 'Lax',
        ]);
    }
}

function get_current_language(array $settings): string
{
    ensure_session();
    $default = $settings['site']['default_language'] ?? 'en';
    $available = get_available_languages($settings);

    if (isset($_GET['lang']) && in_array($_GET['lang'], $available, true)) {
        $_SESSION['lang'] = $_GET['lang'];
        header('Location: ' . strtok($_SERVER['REQUEST_URI'], '?'));
        exit;
    }

    if (!empty($_SESSION['lang']) && in_array($_SESSION['lang'], $available, true)) {
        return $_SESSION['lang'];
    }

    return $default;
}

function t(array $node, string $lang, string $fallback = 'en'): string
{
    if (!is_array($node)) {
        return (string) $node;
    }

    if (isset($node[$lang]) && $node[$lang] !== '') {
        return (string) $node[$lang];
    }

    if (isset($node[$fallback]) && $node[$fallback] !== '') {
        return (string) $node[$fallback];
    }

    $first = reset($node);
    return is_string($first) ? $first : '';
}

function e(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function format_phone_display(string $phone): string
{
    return preg_replace('/[^+0-9]/', '', $phone) ?: $phone;
}

function get_language_meta(array $settings, string $lang): array
{
    return $settings['languages'][$lang]['meta'] ?? [];
}

function respond_json(array $data, int $status = 200): never
{
    http_response_code($status);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

function require_login(): array
{
    ensure_session();
    if (!isset($_SESSION['user'])) {
        header('Location: /admin/login.php');
        exit;
    }

    return $_SESSION['user'];
}

function read_users(): array
{
    $file = data_file('users.json');
    $users = load_json_file($file);
    return $users['users'] ?? [];
}

function save_active_settings(array $data): bool
{
    $activePath = data_file('settings.active.json');
    $userPath = data_file('settings.user.json');
    $default = load_json_file(data_file('settings.default.json'));

    $mergedUser = json_decode(json_encode($data), true);
    if (!is_array($mergedUser)) {
        return false;
    }

    $userData = [];
    foreach ($mergedUser as $key => $value) {
        if (!array_key_exists($key, $default) || $default[$key] !== $value) {
            $userData[$key] = $value;
        }
    }

    $backupDir = data_dir() . '/backups';
    if (!is_dir($backupDir)) {
        mkdir($backupDir, 0775, true);
    }

    $timestamp = date('Ymd-His');
    if (is_file($activePath)) {
        copy($activePath, $backupDir . '/settings.active.' . $timestamp . '.json');
    }

    $userJson = empty($userData)
        ? "{}\n"
        : json_encode($userData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    file_put_contents($userPath, $userJson);
    return (bool) file_put_contents($activePath, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}

function admin_has_role(array $user, string $role): bool
{
    $roles = $user['roles'] ?? [];
    return in_array($role, $roles, true);
}

function normalize_upload_filename(string $name): string
{
    $name = strtolower(trim($name));
    $name = preg_replace('/[^a-z0-9\.\-]+/', '-', $name);
    return trim($name, '-');
}

function handle_language_switch(array $settings): string
{
    ensure_session();
    $available = get_available_languages($settings);
    if (isset($_GET['lang']) && in_array($_GET['lang'], $available, true)) {
        $_SESSION['lang'] = $_GET['lang'];
        header('Location: ' . strtok($_SERVER['REQUEST_URI'], '?'));
        exit;
    }

    if (!empty($_SESSION['lang']) && in_array($_SESSION['lang'], $available, true)) {
        return $_SESSION['lang'];
    }

    return $settings['site']['default_language'] ?? 'en';
}
