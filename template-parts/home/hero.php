<?php
/**
 * Hero — shader-gradient background (rendered by the <shader-bg> custom
 * element / @shadergradient/react), nav, headline + CTAs, metrics + ticker.
 */
$brand = tp_get_section('tp_brand');
$nav   = tp_get_section('tp_nav');
$hero  = tp_get_section('tp_hero');
?>
<div class="tp-hero tp-nav-scope" data-screen-label="Hero">

  <shader-bg url="<?php echo esc_url(tp_shader_url()); ?>" class="tp-hero__shader" aria-hidden="true"></shader-bg>
  <div class="tp-hero__veil" aria-hidden="true"></div>

  <?php
  $insights_nav = tp_get_insights_nav_items();
  $solutions_nav = tp_get_solutions_nav_items();
  $about_nav = tp_get_placeholder_about_nav_items();
  ?>
  <nav class="tp-nav tp-nav--pill">
    <a class="tp-nav__brand" href="<?php echo esc_url(home_url('/')); ?>">
      <span class="tp-nav__bar" aria-hidden="true"></span>
      <span class="tp-nav__wordmark">
        <span class="tp-nav__name"><?php echo esc_html($brand['name']); ?></span>
        <span class="tp-nav__sub"><?php echo esc_html($brand['sub']); ?></span>
      </span>
    </a>
    <div class="tp-nav__slider">
      <div class="tp-nav__dropdown" data-nav-item>
        <button type="button" class="tp-nav__dropdown-trigger"><?php echo esc_html($nav['services_label']); ?> <span class="tp-nav__caret" aria-hidden="true">&#9662;</span></button>
        <div class="tp-nav__dropdown-panel tp-nav__dropdown-panel--mega">
          <?php foreach (tp_get_services_nav_categories() as $cat) : ?>
            <div class="tp-nav__dropdown-col">
              <div class="tp-nav__dropdown-col-label"><?php echo esc_html($cat['label']); ?></div>
              <?php foreach ($cat['items'] as $s) : ?>
                <a href="<?php echo esc_url($s['url']); ?>"><?php echo esc_html($s['label']); ?></a>
              <?php endforeach; ?>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
      <div class="tp-nav__dropdown" data-nav-item>
        <button type="button" class="tp-nav__dropdown-trigger"><?php echo esc_html($nav['industries_label']); ?> <span class="tp-nav__caret" aria-hidden="true">&#9662;</span></button>
        <div class="tp-nav__dropdown-panel">
          <?php foreach (tp_get_industries_nav_items() as $ind) : ?>
            <a href="<?php echo esc_url($ind['url']); ?>"><?php echo esc_html($ind['label']); ?></a>
          <?php endforeach; ?>
        </div>
      </div>
      <div class="tp-nav__dropdown" data-nav-item>
        <button type="button" class="tp-nav__dropdown-trigger"><?php echo esc_html($nav['solutions_label']); ?> <span class="tp-nav__caret" aria-hidden="true">&#9662;</span></button>
        <div class="tp-nav__dropdown-panel">
          <?php foreach ($solutions_nav as $sol) : ?>
            <a href="<?php echo esc_url($sol['url']); ?>"><?php echo esc_html($sol['label']); ?></a>
          <?php endforeach; ?>
        </div>
      </div>
      <div class="tp-nav__dropdown" data-nav-item>
        <button type="button" class="tp-nav__dropdown-trigger"><?php echo esc_html($nav['insights_label']); ?> <span class="tp-nav__caret" aria-hidden="true">&#9662;</span></button>
        <div class="tp-nav__dropdown-panel">
          <?php if ($insights_nav) : foreach ($insights_nav as $i) : ?>
            <a href="<?php echo esc_url($i['url']); ?>"><?php echo esc_html($i['label']); ?></a>
          <?php endforeach; else : ?>
            <span class="tp-nav__dropdown-empty">No posts yet</span>
          <?php endif; ?>
        </div>
      </div>
      <div class="tp-nav__dropdown" data-nav-item>
        <button type="button" class="tp-nav__dropdown-trigger"><?php echo esc_html($nav['about_label']); ?> <span class="tp-nav__caret" aria-hidden="true">&#9662;</span></button>
        <div class="tp-nav__dropdown-panel">
          <?php foreach ($about_nav as $ab) : ?>
            <a href="<?php echo esc_url($ab['url']); ?>"><?php echo esc_html($ab['label']); ?></a>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
    <div class="tp-nav__cta-wrap">
      <a class="tp-nav__cta" href="<?php echo esc_url($nav['cta_url']); ?>"><?php echo esc_html($nav['cta_label']); ?></a>
      <button type="button" class="tp-nav__burger" aria-label="Menu" aria-expanded="false" aria-controls="tp-nav-mobile">
        <span class="tp-nav__burger-bar"></span>
        <span class="tp-nav__burger-bar"></span>
        <span class="tp-nav__burger-bar"></span>
      </button>
    </div>
  </nav>

  <div class="tp-nav-mobile" id="tp-nav-mobile" aria-hidden="true">
    <div class="tp-nav-mobile__group">
      <button type="button" class="tp-nav-mobile__toggle" aria-expanded="false"><?php echo esc_html($nav['services_label']); ?> <span class="tp-nav__caret" aria-hidden="true">&#9662;</span></button>
      <div class="tp-nav-mobile__submenu">
        <?php foreach (tp_get_services_nav_categories() as $cat) : ?>
          <div class="tp-nav-mobile__cat-label"><?php echo esc_html($cat['label']); ?></div>
          <?php foreach ($cat['items'] as $s) : ?>
            <a href="<?php echo esc_url($s['url']); ?>"><?php echo esc_html($s['label']); ?></a>
          <?php endforeach; ?>
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
      <button type="button" class="tp-nav-mobile__toggle" aria-expanded="false"><?php echo esc_html($nav['solutions_label']); ?> <span class="tp-nav__caret" aria-hidden="true">&#9662;</span></button>
      <div class="tp-nav-mobile__submenu">
        <?php foreach ($solutions_nav as $sol) : ?>
          <a href="<?php echo esc_url($sol['url']); ?>"><?php echo esc_html($sol['label']); ?></a>
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
    <div class="tp-nav-mobile__group">
      <button type="button" class="tp-nav-mobile__toggle" aria-expanded="false"><?php echo esc_html($nav['about_label']); ?> <span class="tp-nav__caret" aria-hidden="true">&#9662;</span></button>
      <div class="tp-nav-mobile__submenu">
        <?php foreach ($about_nav as $ab) : ?>
          <a href="<?php echo esc_url($ab['url']); ?>"><?php echo esc_html($ab['label']); ?></a>
        <?php endforeach; ?>
      </div>
    </div>
    <a href="<?php echo esc_url($nav['cta_url']); ?>" class="tp-nav-mobile__cta"><?php echo esc_html($nav['cta_label']); ?></a>
  </div>

  <div class="tp-hero__inner">
    <h1 class="tp-hero__headline"><?php echo esc_html($hero['headline']); ?></h1>
    <p class="tp-hero__subhead"><?php echo esc_html($hero['subhead']); ?></p>

    <div class="tp-hero__actions">
      <a href="<?php echo esc_url($hero['browse_url']); ?>" class="tp-pill-btn" data-hover-icon>
        <span class="tp-pill-btn__icon"><span class="tp-pill-btn__arrow">→</span></span>
        <?php echo esc_html($hero['browse_label']); ?>
      </a>
      <a href="<?php echo esc_url($hero['hire_url']); ?>" class="tp-solid-btn"><?php echo esc_html($hero['hire_label']); ?></a>
    </div>

    <div class="tp-hero__stats">
      <div class="tp-ticker tp-ticker--full">
        <div class="tp-ticker__track">
          <?php
          $logos = $hero['ticker_logos'];
          $loop = array_merge($logos, $logos);
          foreach ($loop as $logo) :
              $src = tp_image_url($logo);
              if (!$src) continue;
          ?>
            <img src="<?php echo esc_url($src); ?>" alt="Client logo" class="tp-ticker__logo" loading="lazy">
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </div>
</div>
