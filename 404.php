<?php
require_once __DIR__ . '/helpers.php';

$settings = load_settings();
$lang = handle_language_switch($settings);
$pageStyles = ['./public/assets/css/style.css'];
include __DIR__ . '/public/theme_head.php';
?>
<main class="admin-main" style="min-height:60vh;display:flex;align-items:center;justify-content:center;">
  <section class="admin-section" style="max-width:520px;text-align:center;">
    <h1 style="font-size:clamp(2rem,5vw,3rem);">404</h1>
    <p style="margin-block:1rem;">The page you were looking for could not be found.</p>
    <a class="btn" href="/">Return Home</a>
  </section>
</main>
</body>
</html>
