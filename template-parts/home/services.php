<?php
/**
 * Our Services — heading cell + 5 cards in a uniform 3-col grid.
 * Hover: full-card flowing gradient wash; title/body flip to deep-ink,
 * bold, for readability against the bright gradient.
 */
$s = tp_get_section('tp_services');
$items = is_array($s['items']) ? $s['items'] : [];
?>
<section id="services" class="tp-services" data-screen-label="Our services">
  <div class="tp-container">
    <div class="tp-services__grid">
      <div class="tp-services__intro">
        <div class="tp-eyebrow"><?php echo esc_html($s['eyebrow']); ?></div>
        <h2 class="tp-h2"><?php echo esc_html($s['heading']); ?></h2>
        <p class="tp-services__lede"><?php echo esc_html($s['intro']); ?></p>
      </div>
      <?php foreach ($items as $i => $item) : ?>
        <div class="tp-card" data-reveal data-hovercard>
          <div class="tp-card__top">
            <div class="tp-card__head">
              <div class="tp-card__badge-row">
                <div class="tp-card__glyph tp-card__glyph--<?php echo $i % 2 === 0 ? 'a' : 'b'; ?>"><?php echo esc_html($item['glyph']); ?></div>
                <div class="tp-card__num" data-cardnum><?php echo esc_html($item['num']); ?></div>
              </div>
              <div class="tp-card__title" data-cardtitle><?php echo esc_html($item['title']); ?></div>
              <p class="tp-card__body" data-cardbody><?php echo esc_html($item['body']); ?></p>
            </div>
            <div class="tp-card__arrow" aria-hidden="true">↗</div>
          </div>
          <div class="tp-card__foot">
            <a href="<?php echo esc_url($item['url']); ?>" class="tp-card__more">Read more <span aria-hidden="true">→</span></a>
          </div>
          <div class="tp-card__fx" aria-hidden="true">
            <div class="tp-card__glow tp-card__glow--<?php echo $i % 2 === 0 ? 'a' : 'b'; ?>"></div>
            <div class="tp-card__fill" data-fill></div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
