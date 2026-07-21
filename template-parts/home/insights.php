<?php
/**
 * Insights — article cards with the same flowing-gradient hover as Services.
 * First 2 cards pull the actual latest published blog posts (title, link,
 * featured image) live — no manual syncing needed as posts are added.
 * The 3rd slot is a static "Releasing soon" card until a 3rd post exists.
 */
$s = tp_get_section('tp_insights');

$real_q = new WP_Query([
    'post_type'      => 'post',
    'post_status'    => 'publish',
    'posts_per_page' => 2,
    'orderby'        => 'date',
    'order'          => 'DESC',
    'no_found_rows'  => true,
]);
$real_articles = [];
foreach ($real_q->posts as $p) {
    $words = str_word_count(wp_strip_all_tags($p->post_content));
    $real_articles[] = [
        'tag'   => 'Insight',
        'read'  => max(1, (int) round($words / 200)) . ' min read',
        'title' => get_the_title($p),
        'url'   => get_permalink($p),
        'thumb' => get_the_post_thumbnail_url($p, 'medium'),
    ];
}
wp_reset_postdata();
while (count($real_articles) < 2) $real_articles[] = null;
$cards = [$real_articles[0], $real_articles[1], null];
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
      <?php foreach ($cards as $a) : ?>
        <?php if ($a) : ?>
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
            <?php if ($a['thumb']) : ?>
              <div class="tp-card__media tp-card__media--image" aria-hidden="true">
                <img src="<?php echo esc_url($a['thumb']); ?>" alt="" loading="lazy">
              </div>
            <?php else : ?>
              <div class="tp-card__media" aria-hidden="true">
                <div class="tp-card__media-glow"></div>
                <div class="tp-card__media-label">Image placeholder</div>
              </div>
            <?php endif; ?>
          </a>
        <?php else : ?>
          <div class="tp-card tp-card--article tp-card--soon" data-reveal aria-hidden="false">
            <div class="tp-card__top">
              <div class="tp-card__head">
                <div class="tp-card__title tp-card__title--soon">Releasing soon</div>
              </div>
            </div>
            <div class="tp-card__media" aria-hidden="true">
              <div class="tp-card__media-glow"></div>
            </div>
          </div>
        <?php endif; ?>
      <?php endforeach; ?>
    </div>
  </div>
</section>
