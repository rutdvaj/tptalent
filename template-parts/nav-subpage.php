<?php
/**
 * Shared nav for the subpages (service pages + blog posts) — same link
 * data and dropdown/submenu logic as the homepage nav (template-parts/
 * home/hero.php), re-skinned for the static dark-gradient hero these
 * pages use instead of the shader background.
 *
 * Contract: whatever template includes this partial must wrap it (plus
 * the rest of its hero) in an element carrying the "tp-nav-scope" class —
 * that's what the mobile-nav open/close JS (initMobileNav in main.js)
 * scopes itself to, since #tp-nav-mobile is a sibling of <nav>, not a
 * descendant, and needs a shared ancestor to toggle state on.
 */
$brand = tp_get_section('tp_brand');
$nav   = tp_get_section('tp_nav');
$insights_nav = tp_get_insights_nav_items();
?>
<nav class="tp-subnav">
  <a class="tp-subnav__brand" href="<?php echo esc_url(home_url('/')); ?>">
    <span class="tp-subnav__bar" aria-hidden="true"></span>
    <span class="tp-subnav__wordmark">
      <span class="tp-subnav__name"><?php echo esc_html($brand['name']); ?></span>
      <span class="tp-subnav__sub"><?php echo esc_html($brand['sub']); ?></span>
    </span>
  </a>

  <div class="tp-subnav__links">
    <div class="tp-nav__dropdown">
      <button type="button" class="tp-nav__dropdown-trigger tp-subnav__trigger"><?php echo esc_html($nav['services_label']); ?> <span class="tp-nav__caret" aria-hidden="true">&#9662;</span></button>
      <div class="tp-nav__dropdown-panel">
        <?php foreach (tp_get_services_nav_items() as $s) : ?>
          <a href="<?php echo esc_url($s['url']); ?>"><?php echo esc_html($s['label']); ?></a>
        <?php endforeach; ?>
      </div>
    </div>
    <div class="tp-nav__dropdown">
      <button type="button" class="tp-nav__dropdown-trigger tp-subnav__trigger"><?php echo esc_html($nav['industries_label']); ?> <span class="tp-nav__caret" aria-hidden="true">&#9662;</span></button>
      <div class="tp-nav__dropdown-panel">
        <?php foreach (tp_get_industries_nav_items() as $ind) : ?>
          <a href="<?php echo esc_url($ind['url']); ?>"><?php echo esc_html($ind['label']); ?></a>
        <?php endforeach; ?>
      </div>
    </div>
    <div class="tp-nav__dropdown">
      <button type="button" class="tp-nav__dropdown-trigger tp-subnav__trigger"><?php echo esc_html($nav['insights_label']); ?> <span class="tp-nav__caret" aria-hidden="true">&#9662;</span></button>
      <div class="tp-nav__dropdown-panel">
        <?php if ($insights_nav) : foreach ($insights_nav as $i) : ?>
          <a href="<?php echo esc_url($i['url']); ?>"><?php echo esc_html($i['label']); ?></a>
        <?php endforeach; else : ?>
          <span class="tp-nav__dropdown-empty">No posts yet</span>
        <?php endif; ?>
      </div>
    </div>
    <a class="tp-subnav__link" href="<?php echo esc_url($nav['about_url']); ?>"><?php echo esc_html($nav['about_label']); ?></a>
  </div>

  <div class="tp-subnav__cta-wrap">
    <a class="tp-subnav__cta" href="<?php echo esc_url($nav['cta_url']); ?>"><?php echo esc_html($nav['cta_label']); ?></a>
    <button type="button" class="tp-nav__burger tp-subnav__burger" aria-label="Menu" aria-expanded="false" aria-controls="tp-nav-mobile">
      <span class="tp-nav__burger-bar tp-subnav__burger-bar"></span>
      <span class="tp-nav__burger-bar tp-subnav__burger-bar"></span>
      <span class="tp-nav__burger-bar tp-subnav__burger-bar"></span>
    </button>
  </div>
</nav>

<div class="tp-nav-mobile" id="tp-nav-mobile" aria-hidden="true">
  <div class="tp-nav-mobile__group">
    <button type="button" class="tp-nav-mobile__toggle" aria-expanded="false"><?php echo esc_html($nav['services_label']); ?> <span class="tp-nav__caret" aria-hidden="true">&#9662;</span></button>
    <div class="tp-nav-mobile__submenu">
      <?php foreach (tp_get_services_nav_items() as $s) : ?>
        <a href="<?php echo esc_url($s['url']); ?>"><?php echo esc_html($s['label']); ?></a>
      <?php endforeach; ?>
    </div>
  </div>
  <div class="tp-nav-mobile__group">
    <button type="button" class="tp-nav-mobile__toggle" aria-expanded="false"><?php echo esc_html($nav['industries_label']); ?> <span class="tp-nav__caret" aria-hidden="true">&#9662;</span></button>
    <div class="tp-nav-mobile__submenu">
      <?php foreach (tp_get_industries_nav_items() as $ind) : ?>
        <a href="<?php echo esc_url($ind['url']); ?>"><?php echo esc_html($ind['label']); ?></a>
      <?php endforeach; ?>
    </div>
  </div>
  <div class="tp-nav-mobile__group">
    <button type="button" class="tp-nav-mobile__toggle" aria-expanded="false"><?php echo esc_html($nav['insights_label']); ?> <span class="tp-nav__caret" aria-hidden="true">&#9662;</span></button>
    <div class="tp-nav-mobile__submenu">
      <?php if ($insights_nav) : foreach ($insights_nav as $i) : ?>
        <a href="<?php echo esc_url($i['url']); ?>"><?php echo esc_html($i['label']); ?></a>
      <?php endforeach; else : ?>
        <span class="tp-nav-mobile__empty">No posts yet</span>
      <?php endif; ?>
    </div>
  </div>
  <a href="<?php echo esc_url($nav['about_url']); ?>"><?php echo esc_html($nav['about_label']); ?></a>
  <a href="<?php echo esc_url($nav['cta_url']); ?>" class="tp-nav-mobile__cta"><?php echo esc_html($nav['cta_label']); ?></a>
</div>
