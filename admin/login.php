<?php
require_once __DIR__ . '/../helpers.php';

$settings = load_settings();
$lang = handle_language_switch($settings);
ensure_session();

if (isset($_SESSION['user'])) {
    header('Location: /admin/dashboard.php');
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    foreach (read_users() as $user) {
        if ($user['username'] === $username && password_verify($password, $user['password'])) {
            $_SESSION['user'] = [
                'username' => $user['username'],
                'roles' => $user['roles'],
                'name' => $user['name'] ?? $user['username']
            ];
            header('Location: /admin/dashboard.php');
            exit;
        }
    }

    $error = 'Invalid credentials. Please try again.';
}

$pageStyles = ['../public/assets/css/style.css', './admin.css'];
include __DIR__ . '/../public/theme_head.php';
?>
<header class="admin-header">
  <h1><?= e($settings['site']['brand_name'] ?? 'Admin'); ?></h1>
  <div class="admin-lang">
    <?php foreach ($settings['languages'] as $code => $_data): ?>
      <a href="?lang=<?= e($code); ?>">
        <img src="<?= e(asset('/web/static/' . ($code === 'am' ? 'amharic' : 'english') . '.png')); ?>" alt="<?= e(strtoupper($code)); ?>">
        <span><?= e($code === 'am' ? 'አማርኛ' : 'English'); ?></span>
      </a>
    <?php endforeach; ?>
  </div>
</header>
<main class="admin-main">
  <section class="admin-section" style="max-width:480px;margin-inline:auto;">
    <h2>Admin Login</h2>
    <?php if ($error): ?>
      <p class="flash error-msg"><?= e($error); ?></p>
    <?php endif; ?>
    <form method="post" class="card-management" autocomplete="off">
      <div class="field-group">
        <label for="username">Username</label>
        <input type="text" id="username" name="username" required>
      </div>
      <div class="field-group">
        <label for="password">Password</label>
        <input type="password" id="password" name="password" required>
      </div>
      <button type="submit" class="button-primary">Sign In</button>
    </form>
  </section>
</main>
</body>
</html>
