<?php
/**
 * Template Name: Industry Page
 *
 * Hero (Sea Mint grid-glow background) + pressure-point problem cards
 * (severity meter, hover flip) + solution cards + testimonial + Where
 * we operate (reuses the homepage's global-delivery section as-is) +
 * Related Insights + FAQ accordion + CTA band + shared footer. One
 * template, content per page is ACF-backed (group_tp_industry_page),
 * falling back to tp_industry_content() in inc/industry-content.php.
 */
get_header();

$slug = get_post_field('post_name');
$content = tp_get_industry_page_content(get_the_ID(), $slug);
if (!$content) {
    $content = [
        'industry_name' => get_the_title(), 'headline' => get_the_title(), 'subhead' => '',
        'prob_heading' => '', 'prob_intro' => '', 'problems' => [],
        'sol_heading' => '', 'sol_intro' => '', 'solutions' => [],
        'testimonial_quote' => '', 'testimonial_name' => '', 'testimonial_title' => '',
        'faq_heading' => '', 'faq_intro' => '', 'faqs' => [], 'cta_subhead' => '',
    ];
}
$industry = $content['industry_name'];
$sev_map = ['Moderate' => 3, 'High' => 4, 'Critical' => 5];
$insights_cross = tp_get_insights_nav_items(2);
?>

<div class="tp-pagehero tp-nav-scope" data-screen-label="Industry hero">
  <sea-mint-grid-glow class="tp-pagehero__wave" aria-hidden="true"></sea-mint-grid-glow>

  <?php get_template_part('template-parts/nav-subpage'); ?>

  <div class="tp-pagehero__inner">
    <h1 class="tp-pagehero__headline"><?php echo esc_html($content['headline']); ?></h1>
    <p class="tp-pagehero__subhead"><?php echo esc_html($content['subhead']); ?></p>
    <div class="tp-pagehero__actions">
      <a href="#solutions" class="tp-pill-btn" data-hover-icon>
        <span class="tp-pill-btn__icon"><span class="tp-pill-btn__arrow">&#8594;</span></span>
        Talk to a specialist
      </a>
      <a href="#problems" class="tp-pagehero__ghost-btn">See the challenges</a>
    </div>
  </div>

  <?php get_template_part('template-parts/pagehero-ticker'); ?>
</div>

<section id="problems" class="tp-problems" data-screen-label="Problems">
  <div class="tp-container tp-problems__inner">
    <div class="tp-problems__head">
      <div class="tp-problems__head-copy">
        <div class="tp-eyebrow">The problem</div>
        <h2 class="tp-h2"><?php echo esc_html($content['prob_heading']); ?></h2>
        <p class="tp-ind-lede"><?php echo esc_html($content['prob_intro']); ?></p>
      </div>
      <div class="tp-problems__hint">severity by impact</div>
    </div>
    <div class="tp-problems__grid">
      <?php foreach ($content['problems'] as $i => $p) :
          $sev = $sev_map[$p['severity']] ?? 3;
      ?>
        <div class="tp-problem-card tp-problem-card--industry" data-reveal>
          <div class="tp-problem-card__ghost" aria-hidden="true"><?php echo esc_html(sprintf('%02d', $i + 1)); ?></div>
          <div class="tp-problem-card__top">
            <div class="tp-problem-card__tag">Pain point &middot; <?php echo esc_html(sprintf('%02d', $i + 1)); ?></div>
          </div>
          <div class="tp-problem-card__heading"><?php echo esc_html($p['heading']); ?></div>
          <p class="tp-problem-card__text"><?php echo esc_html($p['text']); ?></p>
          <div class="tp-problem-card__meter">
            <div class="tp-problem-card__meter-bars">
              <?php for ($s = 0; $s < 5; $s++) : ?>
                <span class="tp-problem-card__meter-seg<?php echo $s < $sev ? ' is-filled' : ''; ?>"></span>
              <?php endfor; ?>
            </div>
            <span class="tp-problem-card__sev-label"><?php echo esc_html($p['severity']); ?></span>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section id="solutions" class="tp-ind-solutions" data-screen-label="Solutions">
  <div class="tp-container tp-ind-solutions__inner">
    <div class="tp-ind-solutions__head">
      <div>
        <div class="tp-eyebrow tp-eyebrow--light">Our solution</div>
        <h2 class="tp-h2 tp-h2--light"><?php echo esc_html($content['sol_heading']); ?></h2>
      </div>
      <p class="tp-ind-lede tp-ind-lede--light"><?php echo esc_html($content['sol_intro']); ?></p>
    </div>
    <div class="tp-ind-solutions__grid">
      <?php foreach ($content['solutions'] as $i => $s) : ?>
        <div class="tp-ind-solution-card" data-reveal>
          <div class="tp-ind-solution-card__media" aria-hidden="true">
            <?php if (!empty($s['image'])) : ?>
              <img src="<?php echo esc_url($s['image']); ?>" alt="" loading="lazy">
            <?php else : ?>
              <div class="tp-card__media-glow"></div>
              <div class="tp-card__media-label">Image placeholder</div>
            <?php endif; ?>
            <div class="tp-ind-solution-card__num"><?php echo esc_html(sprintf('%02d', $i + 1)); ?></div>
          </div>
          <div class="tp-ind-solution-card__body">
            <h3 class="tp-ind-solution-card__title"><?php echo esc_html($s['title']); ?></h3>
            <p class="tp-ind-solution-card__text"><?php echo esc_html($s['body']); ?></p>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section class="tp-ind-quote" data-screen-label="Testimonial">
  <div class="tp-container tp-ind-quote__inner" data-reveal>
    <div class="tp-ind-quote__mark" aria-hidden="true">&ldquo;</div>
    <blockquote class="tp-ind-quote__text"><?php echo esc_html($content['testimonial_quote']); ?></blockquote>
    <div class="tp-ind-quote__attr">
      <div class="tp-ind-quote__avatar" aria-hidden="true">IMG</div>
      <div>
        <div class="tp-ind-quote__name"><?php echo esc_html($content['testimonial_name']); ?></div>
        <div class="tp-ind-quote__title"><?php echo esc_html($content['testimonial_title']); ?></div>
      </div>
    </div>
  </div>
</section>

<?php get_template_part('template-parts/home/global-delivery'); ?>

<?php if ($insights_cross) : ?>
<section class="tp-svc-articles" data-screen-label="Related insights">
  <div class="tp-container tp-svc-articles__inner">
    <div class="tp-svc-articles__head">
      <div class="tp-eyebrow">Insights</div>
      <h2 class="tp-h2">Reading for <?php echo esc_html(mb_strtolower($industry)); ?> leaders</h2>
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

<section class="tp-capabilities" data-screen-label="FAQ">
  <div class="tp-container tp-ind-faq__inner">
    <div class="tp-ind-faq__head">
      <div class="tp-eyebrow">FAQ</div>
      <h2 class="tp-h2"><?php echo esc_html($content['faq_heading']); ?></h2>
      <p class="tp-ind-lede"><?php echo esc_html($content['faq_intro']); ?></p>
    </div>
    <div class="tp-accordion">
      <?php foreach ($content['faqs'] as $f) : ?>
        <div class="tp-accordion__item" data-reveal>
          <button type="button" class="tp-accordion__q" data-accordion-toggle aria-expanded="false">
            <span><?php echo esc_html($f['q']); ?></span>
            <span class="tp-accordion__sign" aria-hidden="true">+</span>
          </button>
          <div class="tp-accordion__a">
            <p><?php echo esc_html($f['a']); ?></p>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section id="cta" class="tp-svc-cta" data-screen-label="CTA">
  <div class="tp-container tp-svc-cta__inner" data-reveal>
    <h2 class="tp-svc-cta__heading">Building a team in <?php echo esc_html(mb_strtolower($industry)); ?>?</h2>
    <p class="tp-svc-cta__subhead"><?php echo esc_html($content['cta_subhead']); ?></p>
    <a href="mailto:business@tecnoprism.com" class="tp-pill-btn" data-hover-icon>
      <span class="tp-pill-btn__icon"><span class="tp-pill-btn__arrow">&#8594;</span></span>
      Start a conversation
    </a>
  </div>
</section>

<?php get_footer(); ?>
