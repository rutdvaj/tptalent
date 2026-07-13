<?php
/** Capabilities — intro + accordion FAQ list. */
$c = tp_get_section('tp_capabilities');
$faqs = is_array($c['faqs']) ? $c['faqs'] : [];
?>
<section class="tp-capabilities" data-screen-label="Capabilities">
  <div class="tp-container tp-capabilities__grid">
    <div class="tp-capabilities__intro" data-reveal>
      <div class="tp-eyebrow"><?php echo esc_html($c['eyebrow']); ?></div>
      <h2 class="tp-h2"><?php echo esc_html($c['heading']); ?></h2>
      <p class="tp-capabilities__lede"><?php echo esc_html($c['intro']); ?></p>
    </div>
    <div class="tp-accordion">
      <?php foreach ($faqs as $i => $q) : ?>
        <div class="tp-accordion__item" data-reveal>
          <button type="button" class="tp-accordion__q" data-accordion-toggle aria-expanded="false">
            <span><?php echo esc_html($q['title']); ?></span>
            <span class="tp-accordion__sign" aria-hidden="true">+</span>
          </button>
          <div class="tp-accordion__a">
            <p><?php echo esc_html($q['body']); ?></p>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
