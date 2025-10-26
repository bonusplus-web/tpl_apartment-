<?php
/** @var array $settings */
/** @var string $lang */
/** @var array $pageStyles */
require_once __DIR__ . '/../helpers.php';

$langMeta = get_language_meta($settings, $lang);
$title = $langMeta['title'] ?? ($settings['site']['brand_name'] ?? '');
$description = $langMeta['description'] ?? '';
$keywords = $langMeta['keywords'] ?? '';
$ogImage = $settings['site']['seo']['og_image'] ?? '';
$brand = $settings['site']['brand_name'] ?? '';
?>
<!DOCTYPE html>
<html lang="<?= e($lang); ?>">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= e($title); ?></title>
  <meta name="description" content="<?= e($description); ?>">
  <meta name="keywords" content="<?= e($keywords); ?>">
  <meta property="og:title" content="<?= e($title); ?>">
  <meta property="og:description" content="<?= e($description); ?>">
  <?php if ($ogImage): ?>
    <meta property="og:image" content="<?= e($ogImage); ?>">
  <?php endif; ?>
  <meta property="og:site_name" content="<?= e($brand); ?>">
  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:title" content="<?= e($title); ?>">
  <meta name="twitter:description" content="<?= e($description); ?>">
  <?php if (!empty($settings['site']['seo']['twitter_handle'])): ?>
    <meta name="twitter:site" content="<?= e($settings['site']['seo']['twitter_handle']); ?>">
  <?php endif; ?>
  <link rel="shortcut icon" href="<?= e(asset('./favicon.svg')); ?>" type="image/svg+xml">
  <?php foreach ($pageStyles as $style): ?>
    <link rel="stylesheet" href="<?= e(asset($style)); ?>">
  <?php endforeach; ?>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@400;600;700&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
