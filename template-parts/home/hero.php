<?php
/**
 * Hero — shader-gradient background (rendered by the <shader-bg> custom
 * element / @shadergradient/react), nav, headline + CTAs, metrics + ticker.
 */
$brand = tp_get_section('tp_brand');
$nav   = tp_get_section('tp_nav');
$hero  = tp_get_section('tp_hero');
?>
<div class="tp-hero" data-screen-label="Hero">

  <shader-bg url="<?php echo esc_url(tp_shader_url()); ?>" class="tp-hero__shader" aria-hidden="true"></shader-bg>
  <div class="tp-hero__veil" aria-hidden="true"></div>

  <nav class="tp-nav">
    <a class="tp-nav__brand" href="<?php echo esc_url(home_url('/')); ?>">
      <span class="tp-nav__bar" aria-hidden="true"></span>
      <span class="tp-nav__wordmark">
        <span class="tp-nav__name"><?php echo esc_html($brand['name']); ?></span>
        <span class="tp-nav__sub"><?php echo esc_html($brand['sub']); ?></span>
      </span>
    </a>
    <div class="tp-nav__links">
      <a href="<?php echo esc_url($nav['services_url']); ?>"><?php echo esc_html($nav['services_label']); ?></a>
      <a href="<?php echo esc_url($nav['industries_url']); ?>"><?php echo esc_html($nav['industries_label']); ?></a>
      <a href="<?php echo esc_url($nav['insights_url']); ?>"><?php echo esc_html($nav['insights_label']); ?></a>
      <a href="<?php echo esc_url($nav['contact_url']); ?>"><?php echo esc_html($nav['contact_label']); ?></a>
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
    <a href="<?php echo esc_url($nav['services_url']); ?>"><?php echo esc_html($nav['services_label']); ?></a>
    <a href="<?php echo esc_url($nav['industries_url']); ?>"><?php echo esc_html($nav['industries_label']); ?></a>
    <a href="<?php echo esc_url($nav['insights_url']); ?>"><?php echo esc_html($nav['insights_label']); ?></a>
    <a href="<?php echo esc_url($nav['contact_url']); ?>"><?php echo esc_html($nav['contact_label']); ?></a>
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
      <div class="tp-hero__metrics">
        <div class="tp-metric">
          <div class="tp-metric__value"><?php echo esc_html($hero['metric1_value']); ?></div>
          <div class="tp-metric__label"><?php echo esc_html($hero['metric1_label']); ?></div>
        </div>
        <div class="tp-metric">
          <div class="tp-metric__value"><?php echo esc_html($hero['metric2_value']); ?></div>
          <div class="tp-metric__label"><?php echo esc_html($hero['metric2_label']); ?></div>
        </div>
      </div>
      <div class="tp-hero__divider" aria-hidden="true"></div>
      <div class="tp-ticker">
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
