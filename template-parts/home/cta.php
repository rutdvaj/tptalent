<?php
/** CTA band — breathing radial glow behind a centered headline + pill button. */
$c = tp_get_section('tp_cta');
?>
<section class="tp-cta" data-screen-label="CTA">
  <div id="tpCtaGlow" class="tp-cta__glow" aria-hidden="true"></div>
  <div class="tp-container tp-cta__inner" data-reveal>
    <h2 class="tp-h2 tp-h2--light tp-cta__heading"><?php echo esc_html($c['heading']); ?></h2>
    <p class="tp-cta__subhead"><?php echo esc_html($c['subhead']); ?></p>
    <a href="<?php echo esc_url($c['button_url']); ?>" class="tp-pill-btn" data-hover-icon>
      <span class="tp-pill-btn__icon"><span class="tp-pill-btn__arrow">→</span></span>
      <?php echo esc_html($c['button_label']); ?>
    </a>
  </div>
</section>
