<?php
/**
 * Testimonial — kinetic rotating "Chosen by" client stat + WebGL ribbon
 * background, plus a static quote block. No tweak panel in production;
 * the ribbon runs the shipped defaults (speed 2.85, spread 1.9).
 */
$t = tp_get_section('tp_testimonial');
$rotator = is_array($t['rotator']) ? $t['rotator'] : [];
$quotes = is_array($t['quotes']) ? $t['quotes'] : [];
?>
<section class="tp-testimonial" data-screen-label="Testimonial">
  <div class="tp-container tp-testimonial__inner">
    <div class="tp-h2 tp-testimonial__heading" data-reveal><?php echo esc_html($t['heading']); ?></div>

    <div class="tp-kinetic" data-reveal>
      <canvas id="tpRibbon" class="tp-kinetic__ribbon" aria-hidden="true"></canvas>
      <div class="tp-kinetic__scrim" aria-hidden="true"></div>

      <div class="tp-kinetic__body">
        <div class="tp-kinetic__eyebrow">Chosen by</div>
        <div class="tp-kinetic__namewrap">
          <div id="tpKinName" class="tp-kinetic__name"><?php echo $rotator ? esc_html($rotator[0]['name']) : ''; ?></div>
        </div>
        <div class="tp-kinetic__statwrap">
          <div id="tpKinMetric" class="tp-kinetic__metric"><?php echo $rotator ? esc_html($rotator[0]['metric']) : ''; ?></div>
          <div id="tpKinDesc" class="tp-kinetic__desc"><?php echo $rotator ? esc_html($rotator[0]['desc']) : ''; ?></div>
        </div>
        <div class="tp-kinetic__dots">
          <?php foreach ($rotator as $i => $r) : ?>
            <span class="tp-kinetic__dot" data-tp-dot></span>
          <?php endforeach; ?>
        </div>
      </div>
    </div>

    <div class="tp-quote-slider" id="tpQuoteSlider" data-reveal>
      <div class="tp-quote-slider__track">
        <?php foreach ($quotes as $i => $q) : ?>
          <div class="tp-quote tp-quote--slide<?php echo $i === 0 ? ' is-active' : ''; ?>" data-quote-slide>
            <div class="tp-quote__bar" aria-hidden="true"></div>
            <div>
              <div class="tp-quote__mark" aria-hidden="true">&ldquo;</div>
              <p class="tp-quote__text"><?php echo esc_html($q['quote']); ?></p>
              <div class="tp-quote__name"><?php echo esc_html($q['client_name']); ?></div>
              <div class="tp-quote__title"><?php echo esc_html($q['client_title']); ?></div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
      <?php if (count($quotes) > 1) : ?>
        <div class="tp-quote-slider__dots">
          <?php foreach ($quotes as $i => $q) : ?>
            <button type="button" class="tp-quote-slider__dot<?php echo $i === 0 ? ' is-active' : ''; ?>" data-quote-dot aria-label="Show testimonial <?php echo (int) ($i + 1); ?>"></button>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </div>
  </div>

  <div class="tp-testimonial__deco tp-testimonial__deco--a" aria-hidden="true"></div>
  <div class="tp-testimonial__deco tp-testimonial__deco--b" aria-hidden="true"></div>

  <script type="application/json" id="tpKineticData"><?php echo wp_json_encode($rotator); ?></script>
</section>
