<?php
/**
 * Template Name: Service Page
 *
 * Static gradient hero (not the homepage's shader) + Problems grid +
 * Engagement Model (4 steps) + Articles cross-links + CTA band + shared
 * footer. One template, content per page is ACF-backed (see
 * group_tp_service_page in inc/acf-fields.php), falling back to the
 * plain-array tp_service_content() in inc/service-content.php for any
 * page whose fields aren't filled in yet.
 */
get_header();

$slug = get_post_field('post_name');
$content = tp_get_service_page_content(get_the_ID(), $slug);
if (!$content) {
    $content = [
        'headline' => get_the_title(), 'subhead' => '', 'prob_heading' => '',
        'problems' => [], 'steps' => [], 'cta_heading' => '', 'cta_subhead' => '',
    ];
}
$insights_cross = tp_get_insights_nav_items(2);
?>

<div class="tp-pagehero tp-nav-scope" data-screen-label="Service hero">
  <lightfall-bg colors="<?php echo esc_attr(tp_wave_colors()); ?>" class="tp-pagehero__wave" aria-hidden="true"></lightfall-bg>

  <?php get_template_part('template-parts/nav-subpage'); ?>

  <div class="tp-pagehero__inner">
    <h1 class="tp-pagehero__headline"><?php echo esc_html($content['headline']); ?></h1>
    <p class="tp-pagehero__subhead"><?php echo esc_html($content['subhead']); ?></p>
    <div class="tp-pagehero__actions">
      <a href="#cta" class="tp-pill-btn" data-hover-icon>
        <span class="tp-pill-btn__icon"><span class="tp-pill-btn__arrow">&#8594;</span></span>
        Start a conversation
      </a>
      <a href="#engagement" class="tp-pagehero__ghost-btn">How we work</a>
    </div>
  </div>

  <?php get_template_part('template-parts/pagehero-ticker'); ?>
</div>

<section class="tp-problems" data-screen-label="Problems">
  <div class="tp-container tp-problems__inner">
    <div class="tp-problems__head">
      <div class="tp-problems__head-copy">
        <div class="tp-eyebrow">Where it breaks</div>
        <h2 class="tp-h2"><?php echo esc_html($content['prob_heading']); ?></h2>
      </div>
      <div class="tp-problems__hint">hover a problem &#8594; see the fix</div>
    </div>
    <div class="tp-problems__grid">
      <?php foreach ($content['problems'] as $i => $p) : ?>
        <a href="#engagement" class="tp-problem-card" data-reveal>
          <div class="tp-problem-card__top">
            <div class="tp-problem-card__num"><?php echo esc_html(sprintf('%02d', $i + 1)); ?></div>
            <div class="tp-problem-card__tag">see the fix <span aria-hidden="true">&#8595;</span></div>
          </div>
          <div class="tp-problem-card__heading"><?php echo esc_html($p['heading']); ?></div>
          <p class="tp-problem-card__text"><?php echo esc_html($p['text']); ?></p>
        </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section id="engagement" class="tp-engagement" data-screen-label="Engagement model">
  <div class="tp-container tp-engagement__inner">
    <div class="tp-engagement__head">
      <div class="tp-eyebrow tp-eyebrow--light">How we fix it</div>
      <h2 class="tp-h2 tp-h2--light">Four steps, one accountable partner</h2>
    </div>
    <div class="tp-engagement__timeline" id="tp-engagement-timeline">
      <div class="tp-engagement__fill" id="tp-engagement-fill" aria-hidden="true"></div>
      <?php foreach ($content['steps'] as $s) : ?>
        <div class="tp-engagement__step" data-reveal>
          <span class="tp-engagement__node" aria-hidden="true"></span>
          <div class="tp-engagement__num"><?php echo esc_html($s['num']); ?></div>
          <div class="tp-engagement__copy">
            <div class="tp-engagement__title"><?php echo esc_html($s['title']); ?></div>
            <p class="tp-engagement__body"><?php echo esc_html($s['body']); ?></p>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<?php if ($insights_cross) : ?>
<section class="tp-svc-articles" data-screen-label="Articles">
  <div class="tp-container tp-svc-articles__inner">
    <div class="tp-svc-articles__head">
      <div class="tp-eyebrow">Our thinking</div>
      <h2 class="tp-h2">Reading for the road ahead</h2>
    </div>
    <div class="tp-svc-articles__grid">
      <?php foreach ($insights_cross as $post) : ?>
        <a href="<?php echo esc_url($post['url']); ?>" class="tp-svc-article-card">
          <?php if (!empty($post['thumb'])) : ?>
            <div class="tp-svc-article-card__media tp-svc-article-card__media--image" aria-hidden="true">
              <img src="<?php echo esc_url($post['thumb']); ?>" alt="" loading="lazy">
            </div>
          <?php else : ?>
            <div class="tp-svc-article-card__media" aria-hidden="true"></div>
          <?php endif; ?>
          <div class="tp-svc-article-card__body">
            <div class="tp-svc-article-card__title"><?php echo esc_html($post['label']); ?></div>
            <div class="tp-svc-article-card__read">Read <span aria-hidden="true">&#8594;</span></div>
          </div>
        </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<section id="cta" class="tp-svc-cta" data-screen-label="CTA">
  <div class="tp-container tp-svc-cta__inner" data-reveal>
    <h2 class="tp-svc-cta__heading"><?php echo esc_html($content['cta_heading']); ?></h2>
    <p class="tp-svc-cta__subhead"><?php echo esc_html($content['cta_subhead']); ?></p>
    <a href="mailto:business@tecnoprism.com" class="tp-pill-btn" data-hover-icon>
      <span class="tp-pill-btn__icon"><span class="tp-pill-btn__arrow">&#8594;</span></span>
      Start a conversation
    </a>
  </div>
</section>

<?php get_footer(); ?>
