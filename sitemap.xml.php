<?php
declare(strict_types=1);

require_once __DIR__ . '/helpers.php';

$settings = load_settings();

$scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
$base = rtrim($scheme . '://' . $host, '/');

$urls = [
    $base . '/',
    $base . '/#about',
    $base . '/#service',
    $base . '/#property',
    $base . '/#blog',
    $base . '/#contact'
];

header('Content-Type: application/xml; charset=utf-8');
echo '<?xml version="1.0" encoding="UTF-8"?>';
?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
  <?php foreach ($urls as $url): ?>
  <url>
    <loc><?= e($url); ?></loc>
    <changefreq>weekly</changefreq>
    <priority>0.8</priority>
  </url>
  <?php endforeach; ?>
</urlset>
