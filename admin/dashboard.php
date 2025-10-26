<?php
require_once __DIR__ . '/../helpers.php';

$user = require_login();
$settings = load_settings();
$lang = handle_language_switch($settings);
ensure_session();

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);

function admin_input(string $name, string $label, string $value, string $type = 'text'): string
{
    $id = str_replace(['[', ']'], '_', $name);
    return '<div class="field-group">'
        . '<label for="' . e($id) . '">' . e($label) . '</label>'
        . '<input form="content-form" type="' . e($type) . '" id="' . e($id) . '" name="' . e($name) . '" value="' . e($value) . '">' . '</div>';
}

function admin_textarea(string $name, string $label, string $value): string
{
    $id = str_replace(['[', ']'], '_', $name);
    return '<div class="field-group">'
        . '<label for="' . e($id) . '">' . e($label) . '</label>'
        . '<textarea form="content-form" id="' . e($id) . '" name="' . e($name) . '">' . e($value) . '</textarea>'
        . '</div>';
}

$pageStyles = ['../public/assets/css/style.css', './admin.css'];
include __DIR__ . '/../public/theme_head.php';
?>
<header class="admin-header">
  <h1>Dashboard</h1>
  <div class="admin-lang">
    <?php foreach ($settings['languages'] as $code => $_data): ?>
      <a href="?lang=<?= e($code); ?>">
        <img src="<?= e(asset('/web/static/' . ($code === 'am' ? 'amharic' : 'english') . '.png')); ?>" alt="<?= e(strtoupper($code)); ?>">
        <span><?= e($code === 'am' ? 'አማርኛ' : 'English'); ?></span>
      </a>
    <?php endforeach; ?>
    <a href="/admin/logout.php" class="button-secondary">Logout</a>
  </div>
</header>
<form id="content-form" action="/admin/save.php" method="post"></form>
<main class="admin-main">
  <?php if ($flash): ?>
    <p class="flash"><?= e($flash); ?></p>
  <?php endif; ?>
    <section class="admin-section">
      <h2>Site Settings</h2>
      <div class="grid-two">
        <?= admin_input('site[brand_name]', 'Brand Name', $settings['site']['brand_name'] ?? ''); ?>
        <?= admin_input('site[business_type]', 'Business Type', $settings['site']['business_type'] ?? ''); ?>
        <?= admin_input('site[primary_color]', 'Primary Color', $settings['site']['primary_color'] ?? '#10b981', 'color'); ?>
        <?= admin_input('site[seo][twitter_handle]', 'Twitter Handle', $settings['site']['seo']['twitter_handle'] ?? ''); ?>
        <?= admin_input('site[seo][og_image]', 'Default Share Image', $settings['site']['seo']['og_image'] ?? ''); ?>
        <?= admin_input('site[default_language]', 'Default Language', $settings['site']['default_language'] ?? 'en'); ?>
      </div>
    </section>

    <section class="admin-section">
      <h2>Contact Information</h2>
      <div class="grid-two">
        <?= admin_input('site[contact][phone_main]', 'Primary Phone', $settings['site']['contact']['phone_main'] ?? ''); ?>
        <?= admin_input('site[contact][whatsapp]', 'WhatsApp Number', $settings['site']['contact']['whatsapp'] ?? ''); ?>
        <?= admin_input('site[contact][email]', 'Email', $settings['site']['contact']['email'] ?? ''); ?>
        <?= admin_input('site[contact][address]', 'Address', $settings['site']['contact']['address'] ?? ''); ?>
      </div>
    </section>

    <section class="admin-section">
      <h2>Social Links</h2>
      <div class="grid-two">
        <?php foreach ($settings['site']['social'] as $network => $url): ?>
          <?= admin_input('site[social][' . $network . ']', ucfirst($network) . ' URL', $url); ?>
        <?php endforeach; ?>
      </div>
    </section>

    <?php foreach ($settings['languages'] as $code => $languageData): ?>
      <section class="admin-section">
        <h2>Language: <?= e(strtoupper($code)); ?></h2>
        <div class="grid-two">
          <?= admin_input('languages[' . $code . '][meta][title]', 'Meta Title', $languageData['meta']['title'] ?? ''); ?>
          <?= admin_input('languages[' . $code . '][meta][description]', 'Meta Description', $languageData['meta']['description'] ?? ''); ?>
          <?= admin_input('languages[' . $code . '][meta][keywords]', 'Meta Keywords', $languageData['meta']['keywords'] ?? ''); ?>
        </div>
        <div class="grid-two">
          <?= admin_input('languages[' . $code . '][hero][title]', 'Hero Title', $languageData['hero']['title'] ?? ''); ?>
          <?= admin_input('languages[' . $code . '][hero][subtitle]', 'Hero Subtitle', $languageData['hero']['subtitle'] ?? ''); ?>
          <?= admin_input('languages[' . $code . '][hero][text]', 'Hero Text', $languageData['hero']['text'] ?? ''); ?>
          <?= admin_input('languages[' . $code . '][hero][button]', 'Hero Button', $languageData['hero']['button'] ?? ''); ?>
        </div>
        <div class="grid-two">
          <?= admin_input('languages[' . $code . '][about][title]', 'About Title', $languageData['about']['title'] ?? ''); ?>
          <?= admin_input('languages[' . $code . '][about][subtitle]', 'About Subtitle', $languageData['about']['subtitle'] ?? ''); ?>
          <?= admin_textarea('languages[' . $code . '][about][text]', 'About Text', $languageData['about']['text'] ?? ''); ?>
          <?= admin_input('languages[' . $code . '][about][button]', 'About Button', $languageData['about']['button'] ?? ''); ?>
          <?= admin_input('languages[' . $code . '][about][quote]', 'About Quote', $languageData['about']['quote'] ?? ''); ?>
        </div>
        <div class="grid-two">
          <?= admin_input('languages[' . $code . '][service][title]', 'Service Title', $languageData['service']['title'] ?? ''); ?>
          <?= admin_input('languages[' . $code . '][service][subtitle]', 'Service Subtitle', $languageData['service']['subtitle'] ?? ''); ?>
          <?= admin_input('languages[' . $code . '][property][title]', 'Property Title', $languageData['property']['title'] ?? ''); ?>
          <?= admin_input('languages[' . $code . '][property][subtitle]', 'Property Subtitle', $languageData['property']['subtitle'] ?? ''); ?>
          <?= admin_input('languages[' . $code . '][features][title]', 'Features Title', $languageData['features']['title'] ?? ''); ?>
          <?= admin_input('languages[' . $code . '][features][subtitle]', 'Features Subtitle', $languageData['features']['subtitle'] ?? ''); ?>
          <?= admin_input('languages[' . $code . '][blog][title]', 'Blog Title', $languageData['blog']['title'] ?? ''); ?>
          <?= admin_input('languages[' . $code . '][blog][subtitle]', 'Blog Subtitle', $languageData['blog']['subtitle'] ?? ''); ?>
          <?= admin_input('languages[' . $code . '][cta][title]', 'CTA Title', $languageData['cta']['title'] ?? ''); ?>
          <?= admin_input('languages[' . $code . '][cta][text]', 'CTA Text', $languageData['cta']['text'] ?? ''); ?>
          <?= admin_input('languages[' . $code . '][cta][button]', 'CTA Button', $languageData['cta']['button'] ?? ''); ?>
          <?= admin_input('languages[' . $code . '][contact][title]', 'Contact Title', $languageData['contact']['title'] ?? ''); ?>
          <?= admin_input('languages[' . $code . '][contact][description]', 'Contact Description', $languageData['contact']['description'] ?? ''); ?>
          <?= admin_input('languages[' . $code . '][contact][button]', 'Contact Button', $languageData['contact']['button'] ?? ''); ?>
          <?= admin_input('languages[' . $code . '][contact][success]', 'Contact Success Message', $languageData['contact']['success'] ?? ''); ?>
          <?= admin_input('languages[' . $code . '][contact][error]', 'Contact Error Message', $languageData['contact']['error'] ?? ''); ?>
          <?= admin_input('languages[' . $code . '][footer][text]', 'Footer Text', $languageData['footer']['text'] ?? ''); ?>
          <?= admin_input('languages[' . $code . '][footer][company_title]', 'Footer Company Title', $languageData['footer']['company_title'] ?? ''); ?>
          <?= admin_input('languages[' . $code . '][footer][service_title]', 'Footer Services Title', $languageData['footer']['service_title'] ?? ''); ?>
          <?= admin_input('languages[' . $code . '][footer][customer_title]', 'Footer Customer Title', $languageData['footer']['customer_title'] ?? ''); ?>
          <?= admin_textarea('languages[' . $code . '][footer][copyright]', 'Footer Copyright', $languageData['footer']['copyright'] ?? ''); ?>
        </div>
      </section>
    <?php endforeach; ?>

    <section class="admin-section">
      <h2>Services</h2>
      <div class="card-management">
        <?php foreach ($settings['services'] as $index => $service): ?>
          <div class="card-box">
            <header>
              <h3>Service #<?= $index + 1; ?></h3>
              <div class="actions">
                <form action="/admin/card_delete.php" method="post">
                  <input type="hidden" name="type" value="services">
                  <input type="hidden" name="index" value="<?= $index; ?>">
                  <button type="submit" class="button-secondary">Delete</button>
                </form>
              </div>
            </header>
            <input form="content-form" type="hidden" name="services[<?= $index; ?>][image]" value="<?= e($service['image']); ?>">
            <?= admin_input('services[' . $index . '][link]', 'Link URL', $service['link'] ?? '#'); ?>
            <?php foreach ($settings['languages'] as $code => $_data): ?>
              <?= admin_input('services[' . $index . '][title][' . $code . ']', strtoupper($code) . ' Title', $service['title'][$code] ?? ''); ?>
              <?= admin_textarea('services[' . $index . '][text][' . $code . ']', strtoupper($code) . ' Text', $service['text'][$code] ?? ''); ?>
              <?= admin_input('services[' . $index . '][link_label][' . $code . ']', strtoupper($code) . ' Link Label', $service['link_label'][$code] ?? ''); ?>
            <?php endforeach; ?>
            <form action="/admin/upload_image.php" method="post" class="upload-form" enctype="multipart/form-data">
              <input type="hidden" name="type" value="services">
              <input type="hidden" name="index" value="<?= $index; ?>">
              <input type="hidden" name="field" value="image">
              <input type="file" name="image" accept="image/*" required>
              <button type="submit" class="button-primary">Replace Image</button>
            </form>
            <form action="/admin/delete_image.php" method="post" class="upload-form">
              <input type="hidden" name="type" value="services">
              <input type="hidden" name="index" value="<?= $index; ?>">
              <input type="hidden" name="field" value="image">
              <button type="submit" class="button-secondary" onclick="return confirm('Remove this image?');">Remove Image</button>
            </form>
          </div>
        <?php endforeach; ?>
        <form action="/admin/card_add.php" method="post" class="actions">
          <input type="hidden" name="type" value="services">
          <button type="submit" class="button-primary">Add Service</button>
        </form>
      </div>
    </section>

    <section class="admin-section">
      <h2>Properties</h2>
      <div class="card-management">
        <?php foreach ($settings['properties'] as $index => $propertyItem): ?>
          <div class="card-box">
            <header>
              <h3>Property #<?= $index + 1; ?></h3>
              <div class="actions">
                <form action="/admin/card_delete.php" method="post">
                  <input type="hidden" name="type" value="properties">
                  <input type="hidden" name="index" value="<?= $index; ?>">
                  <button type="submit" class="button-secondary">Delete</button>
                </form>
              </div>
            </header>
            <input form="content-form" type="hidden" name="properties[<?= $index; ?>][image]" value="<?= e($propertyItem['image']); ?>">
            <?= admin_input('properties[' . $index . '][badge_color]', 'Badge Color Class', $propertyItem['badge_color'] ?? ''); ?>
            <?= admin_input('properties[' . $index . '][media][photos]', 'Photo Count', (string) ($propertyItem['media']['photos'] ?? '')); ?>
            <?= admin_input('properties[' . $index . '][media][videos]', 'Video Count', (string) ($propertyItem['media']['videos'] ?? '')); ?>
            <?= admin_input('properties[' . $index . '][price][amount]', 'Price Amount', $propertyItem['price']['amount'] ?? ''); ?>
            <?php foreach ($settings['languages'] as $code => $_data): ?>
              <?= admin_input('properties[' . $index . '][title][' . $code . ']', strtoupper($code) . ' Title', $propertyItem['title'][$code] ?? ''); ?>
              <?= admin_input('properties[' . $index . '][badge][' . $code . ']', strtoupper($code) . ' Badge', $propertyItem['badge'][$code] ?? ''); ?>
              <?= admin_input('properties[' . $index . '][location][' . $code . ']', strtoupper($code) . ' Location', $propertyItem['location'][$code] ?? ''); ?>
              <?= admin_input('properties[' . $index . '][price][suffix][' . $code . ']', strtoupper($code) . ' Price Suffix', $propertyItem['price']['suffix'][$code] ?? ''); ?>
              <?= admin_textarea('properties[' . $index . '][description][' . $code . ']', strtoupper($code) . ' Description', $propertyItem['description'][$code] ?? ''); ?>
              <?= admin_input('properties[' . $index . '][author][name][' . $code . ']', strtoupper($code) . ' Author Name', $propertyItem['author']['name'][$code] ?? ''); ?>
              <?= admin_input('properties[' . $index . '][author][title][' . $code . ']', strtoupper($code) . ' Author Title', $propertyItem['author']['title'][$code] ?? ''); ?>
            <?php endforeach; ?>
            <?php foreach ($propertyItem['stats'] as $statIndex => $stat): ?>
              <div class="card-box">
                <header><h4>Stat #<?= $statIndex + 1; ?></h4></header>
                <?= admin_input('properties[' . $index . '][stats][' . $statIndex . '][value]', 'Value', (string) ($stat['value'] ?? '')); ?>
                <?= admin_input('properties[' . $index . '][stats][' . $statIndex . '][icon]', 'Icon', $stat['icon'] ?? ''); ?>
                <?php foreach ($settings['languages'] as $code => $_data): ?>
                  <?= admin_input('properties[' . $index . '][stats][' . $statIndex . '][label][' . $code . ']', strtoupper($code) . ' Label', $stat['label'][$code] ?? ''); ?>
                <?php endforeach; ?>
              </div>
            <?php endforeach; ?>
            <input form="content-form" type="hidden" name="properties[<?= $index; ?>][author][avatar]" value="<?= e($propertyItem['author']['avatar']); ?>">
            <form action="/admin/upload_image.php" method="post" class="upload-form" enctype="multipart/form-data">
              <input type="hidden" name="type" value="properties">
              <input type="hidden" name="index" value="<?= $index; ?>">
              <input type="hidden" name="field" value="image">
              <input type="file" name="image" accept="image/*" required>
              <button type="submit" class="button-primary">Replace Property Image</button>
            </form>
            <form action="/admin/delete_image.php" method="post" class="upload-form">
              <input type="hidden" name="type" value="properties">
              <input type="hidden" name="index" value="<?= $index; ?>">
              <input type="hidden" name="field" value="image">
              <button type="submit" class="button-secondary" onclick="return confirm('Remove this image?');">Remove Property Image</button>
            </form>
            <form action="/admin/upload_image.php" method="post" class="upload-form" enctype="multipart/form-data">
              <input type="hidden" name="type" value="properties">
              <input type="hidden" name="index" value="<?= $index; ?>">
              <input type="hidden" name="field" value="author.avatar">
              <input type="file" name="image" accept="image/*" required>
              <button type="submit" class="button-primary">Replace Author Avatar</button>
            </form>
            <form action="/admin/delete_image.php" method="post" class="upload-form">
              <input type="hidden" name="type" value="properties">
              <input type="hidden" name="index" value="<?= $index; ?>">
              <input type="hidden" name="field" value="author.avatar">
              <button type="submit" class="button-secondary" onclick="return confirm('Remove this avatar?');">Remove Avatar</button>
            </form>
          </div>
        <?php endforeach; ?>
        <form action="/admin/card_add.php" method="post" class="actions">
          <input type="hidden" name="type" value="properties">
          <button type="submit" class="button-primary">Add Property</button>
        </form>
      </div>
    </section>

    <section class="admin-section">
      <h2>Features</h2>
      <div class="card-management">
        <?php foreach ($settings['features'] as $index => $feature): ?>
          <div class="card-box">
            <header>
              <h3>Feature #<?= $index + 1; ?></h3>
              <div class="actions">
                <form action="/admin/card_delete.php" method="post">
                  <input type="hidden" name="type" value="features">
                  <input type="hidden" name="index" value="<?= $index; ?>">
                  <button type="submit" class="button-secondary">Delete</button>
                </form>
              </div>
            </header>
            <?= admin_input('features[' . $index . '][icon]', 'Icon', $feature['icon'] ?? ''); ?>
            <?= admin_input('features[' . $index . '][href]', 'Link URL', $feature['href'] ?? '#'); ?>
            <?php foreach ($settings['languages'] as $code => $_data): ?>
              <?= admin_input('features[' . $index . '][title][' . $code . ']', strtoupper($code) . ' Title', $feature['title'][$code] ?? ''); ?>
            <?php endforeach; ?>
          </div>
        <?php endforeach; ?>
        <form action="/admin/card_add.php" method="post" class="actions">
          <input type="hidden" name="type" value="features">
          <button type="submit" class="button-primary">Add Feature</button>
        </form>
      </div>
    </section>

    <section class="admin-section">
      <h2>Blog Posts</h2>
      <div class="card-management">
        <?php foreach ($settings['blogs'] as $index => $post): ?>
          <div class="card-box">
            <header>
              <h3>Post #<?= $index + 1; ?></h3>
              <div class="actions">
                <form action="/admin/card_delete.php" method="post">
                  <input type="hidden" name="type" value="blogs">
                  <input type="hidden" name="index" value="<?= $index; ?>">
                  <button type="submit" class="button-secondary">Delete</button>
                </form>
              </div>
            </header>
            <input form="content-form" type="hidden" name="blogs[<?= $index; ?>][image]" value="<?= e($post['image']); ?>">
            <?= admin_input('blogs[' . $index . '][link]', 'Link URL', $post['link'] ?? '#'); ?>
            <?= admin_input('blogs[' . $index . '][date]', 'Publish Date', $post['date'] ?? '', 'date'); ?>
            <?php foreach ($settings['languages'] as $code => $_data): ?>
              <?= admin_input('blogs[' . $index . '][title][' . $code . ']', strtoupper($code) . ' Title', $post['title'][$code] ?? ''); ?>
              <?= admin_input('blogs[' . $index . '][category][' . $code . ']', strtoupper($code) . ' Category', $post['category'][$code] ?? ''); ?>
              <?= admin_input('blogs[' . $index . '][author][' . $code . ']', strtoupper($code) . ' Author', $post['author'][$code] ?? ''); ?>
              <?= admin_input('blogs[' . $index . '][date_label][' . $code . ']', strtoupper($code) . ' Date Label', $post['date_label'][$code] ?? ''); ?>
              <?= admin_input('blogs[' . $index . '][read_more][' . $code . ']', strtoupper($code) . ' Read More Label', $post['read_more'][$code] ?? ''); ?>
            <?php endforeach; ?>
            <form action="/admin/upload_image.php" method="post" class="upload-form" enctype="multipart/form-data">
              <input type="hidden" name="type" value="blogs">
              <input type="hidden" name="index" value="<?= $index; ?>">
              <input type="hidden" name="field" value="image">
              <input type="file" name="image" accept="image/*" required>
              <button type="submit" class="button-primary">Replace Blog Image</button>
            </form>
            <form action="/admin/delete_image.php" method="post" class="upload-form">
              <input type="hidden" name="type" value="blogs">
              <input type="hidden" name="index" value="<?= $index; ?>">
              <input type="hidden" name="field" value="image">
              <button type="submit" class="button-secondary" onclick="return confirm('Remove this image?');">Remove Blog Image</button>
            </form>
          </div>
        <?php endforeach; ?>
        <form action="/admin/card_add.php" method="post" class="actions">
          <input type="hidden" name="type" value="blogs">
          <button type="submit" class="button-primary">Add Blog Post</button>
        </form>
      </div>
    </section>

    <section class="admin-section">
      <h2>Footer Links</h2>
      <?php foreach ($settings['footer_links'] as $group => $items): ?>
        <h3><?= e(ucfirst($group)); ?></h3>
        <div class="card-management">
          <?php foreach ($items as $index => $item): ?>
            <div class="card-box">
              <?= admin_input('footer_links[' . $group . '][' . $index . '][href]', 'URL', $item['href'] ?? '#'); ?>
              <?php foreach ($settings['languages'] as $code => $_data): ?>
                <?= admin_input('footer_links[' . $group . '][' . $index . '][text][' . $code . ']', strtoupper($code) . ' Label', $item['text'][$code] ?? ''); ?>
              <?php endforeach; ?>
            </div>
          <?php endforeach; ?>
        </div>
      <?php endforeach; ?>
    </section>

    <section class="admin-section">
      <h2>Contact Form Settings</h2>
      <div class="grid-two">
        <?= admin_input('contact_form[rate_limit_seconds]', 'Rate Limit Seconds', (string) ($settings['contact_form']['rate_limit_seconds'] ?? 120)); ?>
        <?= admin_input('contact_form[delay_seconds]', 'Minimum Submit Delay (s)', (string) ($settings['contact_form']['delay_seconds'] ?? 2)); ?>
      </div>
    </section>

    <section class="admin-section">
      <div class="actions">
        <button type="submit" form="content-form" class="button-primary">Save Changes</button>
        <a href="/admin/reset_default.php" class="button-secondary" onclick="return confirm('Reset to developer defaults?');">Reset to Default</a>
        <?php if (admin_has_role($user, 'developer')): ?>
          <a href="/admin/promote_default.php" class="button-secondary" onclick="return confirm('Promote current content to developer default?');">Promote Current as Default</a>
        <?php endif; ?>
      </div>
    </section>
</main>
</body>
</html>
