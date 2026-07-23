<?php
/**
 * Template Name: Solution Page
 *
 * Dark hero (animated beam background) + "How it works" 4-step grid +
 * Where we operate (reuses the homepage's global-delivery section as-is,
 * same as page-industry.php) + Related Insights + CTA band + shared
 * footer. One template, content per page is ACF-backed (see
 * group_tp_solution_page in inc/acf-fields.php), falling back to the
 * plain-array tp_solution_content() in inc/solution-content.php for any
 * page whose fields aren't filled in yet.
 */
get_header();

$slug = get_post_field('post_name');
$content = tp_get_solution_page_content(get_the_ID(), $slug);
if (!$content) {
    $content = [
        'solution_name' => get_the_title(), 'headline' => get_the_title(), 'subhead' => '',
        'steps_intro' => '', 'steps' => [],
    ];
}
$insights_cross = tp_get_insights_nav_items(3);
?>

<div class="tp-pagehero tp-nav-scope" data-screen-label="Solution hero">
  <beam-bg colors="<?php echo esc_attr(tp_wave_colors()); ?>" class="tp-pagehero__wave" aria-hidden="true"></beam-bg>

  <?php get_template_part('template-parts/nav-subpage'); ?>

  <div class="tp-pagehero__inner">
    <h1 class="tp-pagehero__headline"><?php echo esc_html($content['headline']); ?></h1>
    <p class="tp-pagehero__subhead"><?php echo esc_html($content['subhead']); ?></p>
    <div class="tp-pagehero__actions">
      <a href="#cta" class="tp-pill-btn" data-hover-icon>
        <span class="tp-pill-btn__icon"><span class="tp-pill-btn__arrow">&#8594;</span></span>
        Start a conversation
      </a>
      <a href="#steps" class="tp-pagehero__ghost-btn">See how it works</a>
    </div>
  </div>

  <?php get_template_part('template-parts/pagehero-ticker'); ?>
</div>

<section id="steps" class="tp-sol-steps" data-screen-label="How it works">
  <div class="tp-container tp-sol-steps__head">
    <div class="tp-eyebrow">How it works</div>
    <h2 class="tp-h2">Unlock the power of <?php echo esc_html($content['solution_name']); ?></h2>
    <p class="tp-sol-steps__intro"><?php echo esc_html($content['steps_intro']); ?></p>
  </div>
  <div class="tp-container tp-sol-steps__grid">
    <?php foreach ($content['steps'] as $s) : ?>
      <div class="tp-sol-step-card" data-reveal>
        <div class="tp-sol-step-card__num"><?php echo esc_html($s['num']); ?></div>
        <h3 class="tp-sol-step-card__title"><?php echo esc_html($s['title']); ?></h3>
        <p class="tp-sol-step-card__body"><?php echo esc_html($s['body']); ?></p>
      </div>
    <?php endforeach; ?>
  </div>
</section>

<?php get_template_part('template-parts/home/global-delivery'); ?>

<?php if ($insights_cross) : ?>
<section class="tp-svc-articles" data-screen-label="Related insights">
  <div class="tp-container tp-svc-articles__inner">
    <div class="tp-svc-articles__head">
      <div class="tp-eyebrow">Insights</div>
      <h2 class="tp-h2">Reading for teams evaluating <?php echo esc_html($content['solution_name']); ?></h2>
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
    <h2 class="tp-svc-cta__heading">Ready to put <?php echo esc_html($content['solution_name']); ?> to work?</h2>
    <p class="tp-svc-cta__subhead">Tell us what you&#8217;re trying to solve &#8212; we&#8217;ll come back within one business day with an honest read and a plan.</p>
    <a href="mailto:business@tecnoprism.com" class="tp-pill-btn" data-hover-icon>
      <span class="tp-pill-btn__icon"><span class="tp-pill-btn__arrow">&#8594;</span></span>
      Start a conversation
    </a>
  </div>
</section>

<?php get_footer(); ?>
