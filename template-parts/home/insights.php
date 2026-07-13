<?php
/** Insights — article cards with the same flowing-gradient hover as Services. */
$s = tp_get_section('tp_insights');
$articles = is_array($s['articles']) ? $s['articles'] : [];
?>
<section id="insights" class="tp-insights" data-screen-label="Insights">
  <div class="tp-container">
    <div class="tp-insights__head" data-reveal>
      <div>
        <div class="tp-eyebrow"><?php echo esc_html($s['eyebrow']); ?></div>
        <h2 class="tp-h2"><?php echo esc_html($s['heading']); ?></h2>
      </div>
      <a href="<?php echo esc_url($s['view_all_url']); ?>" class="tp-insights__all"><?php echo esc_html($s['view_all_label']); ?> <span aria-hidden="true">→</span></a>
    </div>
    <div class="tp-insights__grid">
      <?php foreach ($articles as $a) : ?>
        <a href="<?php echo esc_url($a['url']); ?>" class="tp-card tp-card--article" data-reveal data-hovercard>
          <div class="tp-card__fill" data-fill></div>
          <div class="tp-card__top">
            <div class="tp-card__head">
              <div class="tp-card__tags">
                <span class="tp-tag"><?php echo esc_html($a['tag']); ?></span>
                <span class="tp-tag"><?php echo esc_html($a['read']); ?></span>
              </div>
              <div class="tp-card__title" data-cardtitle><?php echo esc_html($a['title']); ?></div>
            </div>
            <div class="tp-card__arrow" aria-hidden="true">↗</div>
          </div>
          <div class="tp-card__media" aria-hidden="true">
            <div class="tp-card__media-glow"></div>
            <div class="tp-card__media-label">Image placeholder</div>
          </div>
        </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>
