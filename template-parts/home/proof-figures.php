<?php
/**
 * Proof, in four figures — sticky intro + stat cards split around a
 * hiring-funnel panel. Variant 1e/2e from the design handoff (Firm
 * metrics presentation variations). Sits between "Our services" and
 * the case-study bento grid.
 */
$p = tp_get_section('tp_proof');
$stats = is_array($p['stats']) ? $p['stats'] : [];
$perf = is_array($p['perf']) ? $p['perf'] : [];
$stats_top = array_slice($stats, 0, 2);
$stats_bottom = array_slice($stats, 2, 2);
?>
<section class="tp-proof" data-screen-label="Proof in four figures">
  <div class="tp-container tp-proof__card" data-reveal>
    <div class="tp-proof__intro">
      <div class="tp-eyebrow"><?php echo esc_html($p['eyebrow']); ?></div>
      <h2 class="tp-h2 tp-proof__heading"><?php echo esc_html($p['heading']); ?></h2>
      <p class="tp-proof__lede"><?php echo esc_html($p['intro']); ?></p>
      <a href="<?php echo esc_url($p['cta_url']); ?>" class="tp-pill-btn tp-pill-btn--dark" data-hover-icon>
        <span class="tp-pill-btn__icon"><span class="tp-pill-btn__arrow">→</span></span>
        <?php echo esc_html($p['cta_label']); ?>
      </a>
    </div>

    <div class="tp-proof__panels">
      <div class="tp-proof__stats-row tp-proof__stats-row--top">
        <?php foreach ($stats_top as $s) : ?>
          <div class="tp-proof__stat">
            <div class="tp-proof__stat-value"><span data-count="<?php echo esc_attr($s['value']); ?>" data-prefix="<?php echo esc_attr($s['prefix']); ?>" data-suffix="<?php echo esc_attr($s['suffix']); ?>"><?php echo esc_html($s['display']); ?></span></div>
            <div class="tp-proof__stat-label"><?php echo esc_html($s['label']); ?></div>
          </div>
        <?php endforeach; ?>
      </div>

      <div class="tp-proof__funnel">
        <div class="tp-proof__funnel-label"><?php echo esc_html($p['funnel_label']); ?></div>
        <?php foreach ($perf as $row) : ?>
          <div class="tp-proof__funnel-row">
            <div class="tp-proof__funnel-name"><?php echo esc_html($row['label']); ?></div>
            <div class="tp-proof__funnel-track"><div class="tp-proof__funnel-fill" data-bar="<?php echo esc_attr($row['pct']); ?>"></div></div>
            <div class="tp-proof__funnel-pct"><span data-count="<?php echo esc_attr($row['pct']); ?>" data-suffix="%"><?php echo esc_html($row['pct']); ?>%</span></div>
          </div>
        <?php endforeach; ?>
      </div>

      <div class="tp-proof__stats-row tp-proof__stats-row--bottom">
        <?php foreach ($stats_bottom as $i => $s) : ?>
          <div class="tp-proof__stat<?php echo $i === 0 ? ' tp-proof__stat--tint' : ''; ?>">
            <div class="tp-proof__stat-value"><span data-count="<?php echo esc_attr($s['value']); ?>" data-prefix="<?php echo esc_attr($s['prefix']); ?>" data-suffix="<?php echo esc_attr($s['suffix']); ?>"><?php echo esc_html($s['display']); ?></span></div>
            <div class="tp-proof__stat-label"><?php echo esc_html($s['label']); ?></div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</section>
