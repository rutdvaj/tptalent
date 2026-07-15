<?php
/**
 * Numbers — dark band with 4 stats (count-up on scroll into view) +
 * performance-by-percentage bars (animated fill on scroll into view).
 * Sits directly after the hero. Variant "1b/2b" from the design
 * handoff (Numbers Section Variations).
 */
$n = tp_get_section('tp_numbers');
$stats = is_array($n['stats']) ? $n['stats'] : [];
$perf = is_array($n['perf']) ? $n['perf'] : [];
?>
<section class="tp-numbers" data-screen-label="Numbers">
  <div class="tp-numbers__glow" aria-hidden="true"></div>
  <div class="tp-container tp-numbers__inner" data-reveal>
    <div class="tp-eyebrow tp-eyebrow--light"><?php echo esc_html($n['eyebrow']); ?></div>
    <h2 class="tp-h2 tp-h2--light tp-numbers__heading"><?php echo esc_html($n['heading']); ?></h2>

    <div class="tp-numbers__body">
      <div class="tp-numbers__stats">
        <?php foreach ($stats as $s) : ?>
          <div class="tp-numbers__stat">
            <div class="tp-numbers__stat-value">
              <span data-count="<?php echo esc_attr($s['value']); ?>" data-prefix="<?php echo esc_attr($s['prefix']); ?>" data-suffix="<?php echo esc_attr($s['suffix']); ?>"><?php echo esc_html($s['prefix'] . $s['value'] . $s['suffix']); ?></span>
              <?php if (!empty($s['unit'])) : ?><span class="tp-numbers__stat-unit"><?php echo esc_html($s['unit']); ?></span><?php endif; ?>
            </div>
            <div class="tp-numbers__stat-label"><?php echo esc_html($s['label']); ?></div>
          </div>
        <?php endforeach; ?>
      </div>

      <div class="tp-numbers__rule" aria-hidden="true"></div>

      <div class="tp-numbers__perf">
        <div class="tp-numbers__perf-label"><?php echo esc_html($n['perf_label']); ?></div>
        <?php foreach ($perf as $p) : ?>
          <div class="tp-numbers__bar-row">
            <div class="tp-numbers__bar-top">
              <div class="tp-numbers__bar-label"><?php echo esc_html($p['label']); ?></div>
              <div class="tp-numbers__bar-pct"><span data-count="<?php echo esc_attr($p['pct']); ?>" data-suffix="%"><?php echo esc_html($p['pct']); ?>%</span></div>
            </div>
            <div class="tp-numbers__bar-track">
              <div class="tp-numbers__bar-fill" data-bar="<?php echo esc_attr($p['pct']); ?>"></div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</section>
