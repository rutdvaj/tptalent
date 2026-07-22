<?php
/**
 * Shared brand logo <picture> — desktop/tablet/mobile SVG variants
 * (assets/img/logo-*.svg), background-free so they sit directly on
 * whatever pill/card chrome the caller already provides. Swaps by the
 * same breakpoints as the rest of the responsive CSS (980px/640px).
 *
 * $args['prefix'] picks the BEM prefix for the wrapping <picture> class
 * (e.g. 'nav' -> tp-nav__logo), so each caller keeps its own hook for
 * sizing without needing separate markup.
 */
$prefix = isset($args['prefix']) ? $args['prefix'] : 'nav';
$brand = tp_get_section('tp_brand');
?>
<picture class="tp-<?php echo esc_attr($prefix); ?>__logo">
  <source media="(max-width: 640px)" srcset="<?php echo esc_url(TP_THEME_URI . '/assets/img/logo-mobile.svg'); ?>">
  <source media="(max-width: 980px)" srcset="<?php echo esc_url(TP_THEME_URI . '/assets/img/logo-tablet.svg'); ?>">
  <img src="<?php echo esc_url(TP_THEME_URI . '/assets/img/logo-desktop.svg'); ?>" alt="<?php echo esc_attr(trim($brand['name'] . ' ' . $brand['sub'])); ?>" class="tp-<?php echo esc_attr($prefix); ?>__logo-img">
</picture>
