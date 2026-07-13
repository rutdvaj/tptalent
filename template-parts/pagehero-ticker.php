<?php
/**
 * "Trusted by" client-logo ticker used in the dark pagehero (service pages
 * + blog article header). Shared so both templates stay in sync.
 */
$hero_data = tp_get_section('tp_hero');
$logos = $hero_data['ticker_logos'];
$loop = array_merge($logos, $logos);
?>
<div class="tp-pagehero__ticker">
  <div class="tp-pagehero__ticker-inner">
    <div class="tp-pagehero__ticker-label">Trusted by</div>
    <div class="tp-ticker">
      <div class="tp-ticker__track">
        <?php foreach ($loop as $logo) :
            $src = tp_image_url($logo);
            if (!$src) continue;
        ?>
          <img src="<?php echo esc_url($src); ?>" alt="Client logo" class="tp-ticker__logo" loading="lazy">
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</div>
