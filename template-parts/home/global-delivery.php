<?php
/**
 * Global delivery — region copy in a right-hand column, full-width dotted
 * world map below with a sequenced comet-beam animation (starts only once
 * the section scrolls into view; see assets/js/main.js initMap()).
 */
$g = tp_get_section('tp_global');
$regions = is_array($g['regions']) ? $g['regions'] : [];
?>
<section class="tp-global" data-screen-label="Global presence">
  <div class="tp-container tp-global__head" data-reveal>
    <div class="tp-global__copy">
      <div class="tp-eyebrow tp-eyebrow--light"><?php echo esc_html($g['eyebrow']); ?></div>
      <h2 class="tp-h2 tp-h2--light"><?php echo wp_kses($g['heading'], ['br' => []]); ?></h2>
      <p class="tp-global__lede"><?php echo esc_html($g['intro']); ?></p>
    </div>
    <div class="tp-global__regions">
      <?php foreach ($regions as $r) : ?>
        <div class="tp-global__region">
          <div class="tp-global__region-label"><?php echo esc_html($r['label']); ?></div>
          <div class="tp-global__region-chips">
            <?php foreach (array_filter(array_map('trim', explode('·', $r['value']))) as $country) : ?>
              <span class="tp-global__chip"><?php echo esc_html($country); ?></span>
            <?php endforeach; ?>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>

  <div id="tpMapWrap" class="tp-global__map-wrap" data-reveal>
    <canvas id="tpMap" class="tp-global__map"></canvas>
    <div class="tp-global__map-fade tp-global__map-fade--top" aria-hidden="true"></div>
    <div class="tp-global__map-fade tp-global__map-fade--bottom" aria-hidden="true"></div>
  </div>

  <script type="application/json" id="tpMapData"><?php echo wp_json_encode(is_array($g['locations']) ? $g['locations'] : []); ?></script>
</section>
