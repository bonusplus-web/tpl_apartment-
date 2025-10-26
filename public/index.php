<?php
require_once __DIR__ . '/../helpers.php';

$settings = load_settings();
$lang = handle_language_switch($settings);
$contactToken = bin2hex(random_bytes(16));
ensure_session();
$_SESSION['contact_token'] = $contactToken;
$_SESSION['contact_time'] = time();
$pageStyles = ['./assets/css/style.css'];
include __DIR__ . '/theme_head.php';

$langContent = $settings['languages'][$lang] ?? [];
$header = $langContent['header'] ?? [];
$hero = $langContent['hero'] ?? [];
$about = $langContent['about'] ?? [];
$service = $langContent['service'] ?? [];
$property = $langContent['property'] ?? [];
$features = $langContent['features'] ?? [];
$blog = $langContent['blog'] ?? [];
$cta = $langContent['cta'] ?? [];
$contactText = $langContent['contact'] ?? [];
$footerLang = $langContent['footer'] ?? [];
$contact = $settings['site']['contact'] ?? [];
$social = $settings['site']['social'] ?? [];
$languages = $settings['languages'];
?>

<header class="header" data-header>
  <div class="overlay" data-overlay></div>

  <div class="header-top">
    <div class="container">
      <ul class="header-top-list">
        <?php foreach ($header['top_links'] ?? [] as $item): ?>
          <li>
            <a href="<?= e($item['href'] ?? '#'); ?>" class="header-top-link">
              <ion-icon name="<?= e($item['icon'] ?? 'mail-outline'); ?>"></ion-icon>
              <span><?= e($item['label'] ?? ''); ?></span>
            </a>
          </li>
        <?php endforeach; ?>
      </ul>

      <div class="wrapper">
        <ul class="header-top-social-list">
          <?php foreach ($header['social'] ?? [] as $socialLink): ?>
            <li>
              <a href="<?= e($socialLink['href'] ?? '#'); ?>" class="header-top-social-link">
                <ion-icon name="<?= e($socialLink['icon'] ?? 'logo-facebook'); ?>"></ion-icon>
              </a>
            </li>
          <?php endforeach; ?>
        </ul>

        <div class="lang-switch" data-lang-switch>
          <button type="button" class="lang-switch__toggle" aria-haspopup="true" aria-expanded="false">
            <img src="<?= e(asset('/web/static/' . ($lang === 'am' ? 'amharic' : 'english') . '.png')); ?>" alt="<?= $lang === 'am' ? 'አማርኛ' : 'English'; ?>">
          </button>
          <div class="lang-menu" role="menu">
            <?php foreach ($languages as $code => $config): ?>
              <a role="menuitem" href="?lang=<?= e($code); ?>">
                <img src="<?= e(asset('/web/static/' . ($code === 'am' ? 'amharic' : 'english') . '.png')); ?>" alt="<?= e(strtoupper($code)); ?>">
                <span><?= e($code === 'am' ? 'አማርኛ' : 'English'); ?></span>
              </a>
            <?php endforeach; ?>
          </div>
        </div>

        <button class="header-top-btn"><?= e($header['cta'] ?? ''); ?></button>
      </div>
    </div>
  </div>

  <div class="header-bottom">
    <div class="container">
      <a href="#home" class="logo">
        <img src="<?= e(asset('./assets/images/logo.png')); ?>" alt="<?= e($settings['site']['brand_name'] ?? ''); ?> logo">
      </a>

      <nav class="navbar" data-navbar>
        <div class="navbar-top">
          <a href="#home" class="logo">
            <img src="<?= e(asset('./assets/images/logo.png')); ?>" alt="<?= e($settings['site']['brand_name'] ?? ''); ?> logo">
          </a>

          <button class="nav-close-btn" data-nav-close-btn aria-label="Close Menu">
            <ion-icon name="close-outline"></ion-icon>
          </button>
        </div>

        <div class="navbar-bottom">
          <ul class="navbar-list">
            <?php foreach ($header['nav'] ?? [] as $item): ?>
              <li>
                <a href="<?= e($item['href'] ?? '#'); ?>" class="navbar-link" data-nav-link><?= e($item['text'] ?? ''); ?></a>
              </li>
            <?php endforeach; ?>
          </ul>
        </div>
      </nav>

      <div class="header-bottom-actions">
        <?php foreach ($header['action_buttons'] ?? [] as $button): ?>
          <button class="header-bottom-actions-btn" aria-label="<?= e($button['label'] ?? ''); ?>">
            <ion-icon name="<?= e($button['icon'] ?? ''); ?>"></ion-icon>
            <span><?= e($button['label'] ?? ''); ?></span>
          </button>
        <?php endforeach; ?>

        <button class="header-bottom-actions-btn" data-nav-open-btn aria-label="<?= e($header['menu_label'] ?? 'Menu'); ?>">
          <ion-icon name="menu-outline"></ion-icon>
          <span><?= e($header['menu_label'] ?? 'Menu'); ?></span>
        </button>
      </div>
    </div>
  </div>
</header>

<main>
  <article>
    <section class="hero" id="home">
      <div class="container">
        <div class="hero-content">
          <p class="hero-subtitle">
            <ion-icon name="<?= e($hero['subtitle_icon'] ?? 'home'); ?>"></ion-icon>
            <span><?= e($hero['subtitle'] ?? ''); ?></span>
          </p>

          <h1 class="h1 hero-title"><?= e($hero['title'] ?? ''); ?></h1>

          <p class="hero-text"><?= e($hero['text'] ?? ''); ?></p>

          <a href="#contact" class="btn"><?= e($hero['button'] ?? ''); ?></a>
        </div>

        <figure class="hero-banner">
          <img src="<?= e(asset('./assets/images/hero-banner.png')); ?>" alt="<?= e($hero['image_alt'] ?? ''); ?>" class="w-100">
        </figure>
      </div>
    </section>

    <section class="about" id="about">
      <div class="container">
        <figure class="about-banner">
          <img src="<?= e(asset('./assets/images/about-banner-1.png')); ?>" alt="House interior">
          <img src="<?= e(asset('./assets/images/about-banner-2.jpg')); ?>" alt="House interior" class="abs-img">
        </figure>

        <div class="about-content">
          <p class="section-subtitle"><?= e($about['subtitle'] ?? ''); ?></p>
          <h2 class="h2 section-title"><?= e($about['title'] ?? ''); ?></h2>
          <p class="about-text"><?= e($about['text'] ?? ''); ?></p>

          <ul class="about-list">
            <?php foreach ($about['highlights'] ?? [] as $item): ?>
              <li class="about-item">
                <div class="about-item-icon">
                  <ion-icon name="<?= e($item['icon'] ?? ''); ?>"></ion-icon>
                </div>
                <p class="about-item-text"><?= e($item['text'] ?? ''); ?></p>
              </li>
            <?php endforeach; ?>
          </ul>

          <p class="callout"><?= e($about['quote'] ?? ''); ?></p>
          <a href="#service" class="btn"><?= e($about['button'] ?? ''); ?></a>
        </div>
      </div>
    </section>

    <section class="service" id="service">
      <div class="container">
        <p class="section-subtitle"><?= e($service['subtitle'] ?? ''); ?></p>
        <h2 class="h2 section-title"><?= e($service['title'] ?? ''); ?></h2>

        <ul class="service-list cards-grid">
          <?php foreach ($settings['services'] as $item): ?>
            <li>
              <div class="service-card">
                <div class="card-icon">
                  <img src="<?= e(asset($item['image'])); ?>" alt="Service icon">
                </div>
                <h3 class="h3 card-title">
                  <a href="<?= e($item['link']); ?>"><?= e(t($item['title'], $lang)); ?></a>
                </h3>
                <p class="card-text"><?= e(t($item['text'], $lang)); ?></p>
                <a href="<?= e($item['link']); ?>" class="card-link">
                  <span><?= e(t($item['link_label'], $lang)); ?></span>
                  <ion-icon name="arrow-forward-outline"></ion-icon>
                </a>
              </div>
            </li>
          <?php endforeach; ?>
        </ul>
      </div>
    </section>

    <section class="property" id="property">
      <div class="container">
        <p class="section-subtitle"><?= e($property['subtitle'] ?? ''); ?></p>
        <h2 class="h2 section-title"><?= e($property['title'] ?? ''); ?></h2>

        <ul class="property-list has-scrollbar">
          <?php foreach ($settings['properties'] as $item): ?>
            <li>
              <div class="property-card">
                <figure class="card-banner card__media">
                  <a href="#">
                    <img src="<?= e(asset($item['image'])); ?>" alt="<?= e(t($item['title'], $lang)); ?>" class="w-100">
                  </a>
                  <div class="card-badge <?= e($item['badge_color']); ?>"><?= e(t($item['badge'], $lang)); ?></div>
                  <div class="banner-actions">
                    <button class="banner-actions-btn">
                      <ion-icon name="location"></ion-icon>
                      <address><?= e(t($item['location'], $lang)); ?></address>
                    </button>
                    <button class="banner-actions-btn">
                      <ion-icon name="camera"></ion-icon>
                      <span><?= e((string) ($item['media']['photos'] ?? '')); ?></span>
                    </button>
                    <button class="banner-actions-btn">
                      <ion-icon name="film"></ion-icon>
                      <span><?= e((string) ($item['media']['videos'] ?? '')); ?></span>
                    </button>
                  </div>
                </figure>

                <div class="card-content">
                  <div class="card-price">
                    <strong><?= e($item['price']['amount']); ?></strong><?= e(t($item['price']['suffix'], $lang)); ?>
                  </div>

                  <h3 class="h3 card-title">
                    <a href="#"><?= e(t($item['title'], $lang)); ?></a>
                  </h3>

                  <p class="card-text"><?= e(t($item['description'], $lang)); ?></p>

                  <ul class="card-list">
                    <?php foreach ($item['stats'] as $stat): ?>
                      <li class="card-item">
                        <strong><?= e((string) $stat['value']); ?></strong>
                        <ion-icon name="<?= e($stat['icon']); ?>"></ion-icon>
                        <span><?= e(t($stat['label'], $lang)); ?></span>
                      </li>
                    <?php endforeach; ?>
                  </ul>
                </div>

                <div class="card-footer">
                  <div class="card-author">
                    <figure class="author-avatar">
                      <img src="<?= e(asset($item['author']['avatar'])); ?>" alt="<?= e(t($item['author']['name'], $lang)); ?>" class="w-100">
                    </figure>
                    <div>
                      <p class="author-name">
                        <a href="#"><?= e(t($item['author']['name'], $lang)); ?></a>
                      </p>
                      <p class="author-title"><?= e(t($item['author']['title'], $lang)); ?></p>
                    </div>
                  </div>

                  <div class="card-footer-actions">
                    <button class="card-footer-actions-btn">
                      <ion-icon name="resize-outline"></ion-icon>
                    </button>
                    <button class="card-footer-actions-btn">
                      <ion-icon name="heart-outline"></ion-icon>
                    </button>
                    <button class="card-footer-actions-btn">
                      <ion-icon name="add-circle-outline"></ion-icon>
                    </button>
                  </div>
                </div>
              </div>
            </li>
          <?php endforeach; ?>
        </ul>
      </div>
    </section>

    <section class="features">
      <div class="container">
        <p class="section-subtitle"><?= e($features['subtitle'] ?? ''); ?></p>
        <h2 class="h2 section-title"><?= e($features['title'] ?? ''); ?></h2>

        <ul class="features-list cards-grid">
          <?php foreach ($settings['features'] as $item): ?>
            <li>
              <a href="<?= e($item['href']); ?>" class="features-card">
                <div class="card-icon">
                  <ion-icon name="<?= e($item['icon']); ?>"></ion-icon>
                </div>
                <h3 class="card-title"><?= e(t($item['title'], $lang)); ?></h3>
                <div class="card-btn">
                  <ion-icon name="arrow-forward-outline"></ion-icon>
                </div>
              </a>
            </li>
          <?php endforeach; ?>
        </ul>
      </div>
    </section>

    <section class="blog" id="blog">
      <div class="container">
        <p class="section-subtitle"><?= e($blog['subtitle'] ?? ''); ?></p>
        <h2 class="h2 section-title"><?= e($blog['title'] ?? ''); ?></h2>

        <ul class="blog-list has-scrollbar">
          <?php foreach ($settings['blogs'] as $item): ?>
            <li>
              <div class="blog-card">
                <figure class="card-banner card__media">
                  <img src="<?= e(asset($item['image'])); ?>" alt="<?= e(t($item['title'], $lang)); ?>" class="w-100">
                </figure>
                <div class="blog-content">
                  <div class="blog-content-top">
                    <ul class="card-meta-list">
                      <li>
                        <a href="#" class="card-meta-link">
                          <ion-icon name="person"></ion-icon>
                          <span><?= e(t($item['author'], $lang)); ?></span>
                        </a>
                      </li>
                      <li>
                        <a href="#" class="card-meta-link">
                          <ion-icon name="pricetags"></ion-icon>
                          <span><?= e(t($item['category'], $lang)); ?></span>
                        </a>
                      </li>
                    </ul>
                    <h3 class="h3 blog-title">
                      <a href="<?= e($item['link']); ?>"><?= e(t($item['title'], $lang)); ?></a>
                    </h3>
                  </div>
                  <div class="blog-content-bottom">
                    <div class="publish-date">
                      <ion-icon name="calendar"></ion-icon>
                      <time datetime="<?= e($item['date']); ?>"><?= e(t($item['date_label'], $lang)); ?></time>
                    </div>
                    <a href="<?= e($item['link']); ?>" class="read-more-btn"><?= e(t($item['read_more'], $lang)); ?></a>
                  </div>
                </div>
              </div>
            </li>
          <?php endforeach; ?>
        </ul>
      </div>
    </section>

    <section class="cta">
      <div class="container">
        <div class="cta-card">
          <div class="card-content">
            <h2 class="h2 card-title"><?= e($cta['title'] ?? ''); ?></h2>
            <p class="card-text"><?= e($cta['text'] ?? ''); ?></p>
          </div>
          <a href="#property" class="btn cta-btn">
            <span><?= e($cta['button'] ?? ''); ?></span>
            <ion-icon name="arrow-forward-outline"></ion-icon>
          </a>
        </div>
      </div>
    </section>

    <section class="contact" id="contact">
      <div class="container">
        <div class="contact-card">
          <div class="contact-info">
            <h2 class="h2 section-title"><?= e($contactText['title'] ?? ''); ?></h2>
            <p><?= e($contactText['description'] ?? ''); ?></p>
            <ul class="contact-list">
              <li>
                <ion-icon name="call-outline"></ion-icon>
                <a href="tel:<?= e(format_phone_display($contact['phone_main'] ?? '')); ?>"><?= e($contact['phone_main'] ?? ''); ?></a>
              </li>
              <li>
                <ion-icon name="logo-whatsapp"></ion-icon>
                <a href="https://wa.me/<?= e(preg_replace('/\D+/', '', $contact['whatsapp'] ?? '')); ?>" rel="noopener" target="_blank"><?= e($contact['whatsapp'] ?? ''); ?></a>
              </li>
              <li>
                <ion-icon name="mail-outline"></ion-icon>
                <a href="mailto:<?= e($contact['email'] ?? ''); ?>"><?= e($contact['email'] ?? ''); ?></a>
              </li>
              <li>
                <ion-icon name="location-outline"></ion-icon>
                <address><?= e($contact['address'] ?? ''); ?></address>
              </li>
            </ul>
          </div>
          <form action="<?= e(asset('./contact_submit.php')); ?>" method="post" class="contact-form" novalidate>
            <p class="form-status" role="status" aria-live="polite" tabindex="-1"></p>
            <div class="input-group">
              <label for="name"><?= e($contactText['name_label'] ?? 'Name'); ?></label>
              <input type="text" id="name" name="name" required>
            </div>
            <div class="input-group">
              <label for="email"><?= e($contactText['email_label'] ?? 'Email'); ?></label>
              <input type="email" id="email" name="email" required>
            </div>
            <div class="input-group">
              <label for="phone"><?= e($contactText['phone_label'] ?? 'Phone'); ?></label>
              <input type="tel" id="phone" name="phone">
            </div>
            <div class="input-group">
              <label for="message"><?= e($contactText['message_label'] ?? 'Message'); ?></label>
              <textarea id="message" name="message" rows="4" required></textarea>
            </div>
            <div class="sr-only">
              <label for="website"><?= e($contactText['honeypot'] ?? ''); ?></label>
              <input type="text" id="website" name="website" autocomplete="off">
            </div>
            <input type="hidden" name="token" value="<?= e($contactToken); ?>">
            <input type="hidden" name="started_at" value="<?= e((string) $_SESSION['contact_time']); ?>">
            <button type="submit" class="btn">
              <span><?= e($contactText['button'] ?? ''); ?></span>
              <ion-icon name="arrow-forward-outline"></ion-icon>
            </button>
          </form>
        </div>
      </div>
    </section>
  </article>
</main>

<footer class="footer">
  <div class="footer-top">
    <div class="container">
      <div class="footer-brand">
        <a href="#home" class="logo">
          <img src="<?= e(asset('./assets/images/logo-light.png')); ?>" alt="<?= e($settings['site']['brand_name'] ?? ''); ?> logo">
        </a>
        <p class="section-text"><?= e($footerLang['text'] ?? ''); ?></p>
        <ul class="contact-list">
          <li>
            <a href="#" class="contact-link">
              <ion-icon name="location-outline"></ion-icon>
              <address><?= e($contact['address'] ?? ''); ?></address>
            </a>
          </li>
          <li>
            <a href="tel:<?= e(format_phone_display($contact['phone_main'] ?? '')); ?>" class="contact-link">
              <ion-icon name="call-outline"></ion-icon>
              <span><?= e($contact['phone_main'] ?? ''); ?></span>
            </a>
          </li>
          <li>
            <a href="mailto:<?= e($contact['email'] ?? ''); ?>" class="contact-link">
              <ion-icon name="mail-outline"></ion-icon>
              <span><?= e($contact['email'] ?? ''); ?></span>
            </a>
          </li>
        </ul>
        <ul class="social-list">
          <?php foreach ($social as $icon => $url): ?>
            <li>
              <a href="<?= e($url); ?>" class="social-link">
                <ion-icon name="logo-<?= e($icon); ?>"></ion-icon>
              </a>
            </li>
          <?php endforeach; ?>
        </ul>
      </div>

      <div class="footer-link-box">
        <ul class="footer-list">
          <li><p class="footer-list-title"><?= e($footerLang['company_title'] ?? ''); ?></p></li>
          <?php foreach ($settings['footer_links']['company'] as $item): ?>
            <li><a href="<?= e($item['href']); ?>" class="footer-link"><?= e(t($item['text'], $lang)); ?></a></li>
          <?php endforeach; ?>
        </ul>
        <ul class="footer-list">
          <li><p class="footer-list-title"><?= e($footerLang['service_title'] ?? ''); ?></p></li>
          <?php foreach ($settings['footer_links']['services'] as $item): ?>
            <li><a href="<?= e($item['href']); ?>" class="footer-link"><?= e(t($item['text'], $lang)); ?></a></li>
          <?php endforeach; ?>
        </ul>
        <ul class="footer-list">
          <li><p class="footer-list-title"><?= e($footerLang['customer_title'] ?? ''); ?></p></li>
          <?php foreach ($settings['footer_links']['customer'] as $item): ?>
            <li><a href="<?= e($item['href']); ?>" class="footer-link"><?= e(t($item['text'], $lang)); ?></a></li>
          <?php endforeach; ?>
        </ul>
      </div>
    </div>
  </div>
  <div class="footer-bottom">
    <div class="container">
      <p class="copyright"><?= $footerLang['copyright'] ?? ''; ?></p>
    </div>
  </div>
</footer>

<script>
  window.__contactCopy = {
    success: <?= json_encode($contactText['success'] ?? ''); ?>,
    error: <?= json_encode($contactText['error'] ?? ''); ?>
  };
</script>
<script src="<?= e(asset('./assets/js/script.js')); ?>" defer></script>
<script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
</body>
</html>
