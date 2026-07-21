<?php
/**
 * Our Services — heading cell + 5 cards in a uniform 3-col grid.
 * Hover: full-card flowing gradient wash; title/body flip to deep-ink,
 * bold, for readability against the bright gradient.
 *
 * Only the first 3 services (Executive Search, Virtual Assistance,
 * Payrolling Services) have real pages built yet — their "Read more"
 * links point there. The last 2 (Talent Acquisition, Recruiter on
 * Premise) don't have pages, so their Read more link is hidden until
 * they do.
 */
$s = tp_get_section('tp_services');
$items = is_array($s['items']) ? $s['items'] : [];
$service_pages = tp_get_services_nav_items();
foreach ($items as $i => &$item) {
    if (isset($service_pages[$i])) $item['url'] = $service_pages[$i]['url'];
}
unset($item);

$tp_service_icons = [
    '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="7" width="18" height="13" rx="2"/><path d="M8 7V5a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/><path d="M3 13h18"/></svg>',
    '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M4 13a8 8 0 0 1 16 0"/><rect x="2.5" y="13" width="4" height="6" rx="1.5"/><rect x="17.5" y="13" width="4" height="6" rx="1.5"/><path d="M19.5 19v1a3 3 0 0 1-3 3h-3"/></svg>',
    '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2h9l3 3v17l-3-2-3 2-3-2-3 2V2z"/><path d="M9 8h6M9 12h6M9 16h3"/></svg>',
    '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="10" cy="8" r="3.2"/><path d="M4.5 20c0-3.3 2.8-5.5 5.5-5.5"/><circle cx="17" cy="16" r="3"/><path d="M19.3 18.3 22 21"/></svg>',
    '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="4" y="3" width="11" height="18" rx="1"/><path d="M15 21v-6h5v6"/><path d="M7 7h3M7 11h3M7 15h3"/></svg>',
];
?>
<section id="services" class="tp-services" data-screen-label="Our services">
  <div class="tp-container">
    <div class="tp-services__grid">
      <div class="tp-services__intro" data-reveal>
        <div class="tp-eyebrow"><?php echo esc_html($s['eyebrow']); ?></div>
        <h2 class="tp-h2"><?php echo esc_html($s['heading']); ?></h2>
        <p class="tp-services__lede"><?php echo esc_html($s['intro']); ?></p>
      </div>
      <?php foreach ($items as $i => $item) : ?>
        <div class="tp-card" data-reveal data-hovercard>
          <div class="tp-card__top">
            <div class="tp-card__head">
              <div class="tp-card__badge-row">
                <div class="tp-card__glyph tp-card__glyph--<?php echo $i % 2 === 0 ? 'a' : 'b'; ?>"><?php echo $tp_service_icons[$i % count($tp_service_icons)]; ?></div>
                <div class="tp-card__num" data-cardnum><?php echo esc_html($item['num']); ?></div>
              </div>
              <div class="tp-card__title" data-cardtitle><?php echo esc_html($item['title']); ?></div>
              <p class="tp-card__body" data-cardbody><?php echo esc_html($item['body']); ?></p>
            </div>
            <div class="tp-card__arrow" aria-hidden="true">↗</div>
          </div>
          <?php if ($i < 3) : ?>
          <div class="tp-card__foot">
            <a href="<?php echo esc_url($item['url']); ?>" class="tp-card__more">Read more <span aria-hidden="true">→</span></a>
          </div>
          <?php endif; ?>
          <div class="tp-card__fx" aria-hidden="true">
            <div class="tp-card__glow tp-card__glow--<?php echo $i % 2 === 0 ? 'a' : 'b'; ?>"></div>
            <div class="tp-card__fill" data-fill></div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
