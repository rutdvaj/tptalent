<?php
/**
 * Native WP post ("Insights"). Dark article-header hero (reusing the
 * service page's .tp-pagehero shell) + reading progress bar + 3-column
 * body (share rail / 720-measure reading column / sticky TOC with
 * scroll-driven active highlight) + Related + CTA + shared footer.
 *
 * The TOC/anchor ids are generated automatically from the post's <h2>
 * tags (see tp_process_article_content() in inc/helpers.php) — nothing
 * special to type when writing a post in wp-admin.
 */
get_header();
while (have_posts()) : the_post();

$raw = apply_filters('the_content', get_the_content());
$processed = tp_process_article_content($raw);
$toc = $processed['toc'];
$related = tp_get_related_posts(get_the_ID(), 3);
?>

<div id="tp-article-progress-track" class="tp-article-progress-track" aria-hidden="true">
  <div id="tp-article-progress" class="tp-article-progress"></div>
</div>

<div class="tp-pagehero tp-nav-scope" data-screen-label="Article header">
  <wave-field colors="<?php echo esc_attr(tp_wave_colors()); ?>" class="tp-pagehero__wave" aria-hidden="true"></wave-field>

  <?php get_template_part('template-parts/nav-subpage'); ?>

  <div class="tp-article-header__inner">
    <h1 class="tp-article-header__title"><?php the_title(); ?></h1>
    <?php if (has_excerpt()) : ?>
      <p class="tp-article-header__dek"><?php echo esc_html(get_the_excerpt()); ?></p>
    <?php endif; ?>
    <div class="tp-article-header__byline">
      <div class="tp-article-header__author">Tecnoprism Workforce Desk</div>
      <div class="tp-article-header__date"><?php echo esc_html(get_the_date('F Y')); ?></div>
    </div>
  </div>

  <?php get_template_part('template-parts/pagehero-ticker'); ?>
</div>

<div class="tp-article-cover-wrap">
  <div class="tp-article-cover">
    <span>cover image &middot; drop asset here</span>
  </div>
</div>

<div class="tp-article-body">
  <div class="tp-article-share">
    <div class="tp-article-share__label">Share</div>
    <a href="https://www.linkedin.com/sharing/share-offsite/?url=<?php echo urlencode(get_permalink()); ?>" target="_blank" rel="noopener" aria-label="Share on LinkedIn" class="tp-article-share__btn">in</a>
    <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode(get_permalink()); ?>" target="_blank" rel="noopener" aria-label="Share on X" class="tp-article-share__btn">x</a>
    <a href="<?php echo esc_url(get_permalink()); ?>" aria-label="Permalink" class="tp-article-share__btn">&#128279;</a>
  </div>

  <div class="tp-article-content">
    <?php echo $processed['html']; ?>

    <div class="tp-article-author">
      <div class="tp-article-author__name">Tecnoprism Workforce Desk</div>
      <div class="tp-article-author__bio">Research and commentary on hiring, retention and the changing shape of work &mdash; from the team behind 3,700+ workforce engagements.</div>
    </div>
  </div>

  <?php if ($toc) : ?>
  <div class="tp-article-toc">
    <div class="tp-article-toc__label">On this page</div>
    <?php foreach ($toc as $t) : ?>
      <a href="#<?php echo esc_attr($t['id']); ?>" class="tp-article-toc__link" data-toc-target="<?php echo esc_attr($t['id']); ?>"><?php echo esc_html($t['label']); ?></a>
    <?php endforeach; ?>
  </div>
  <?php endif; ?>
</div>

<?php if ($related) : ?>
<section class="tp-svc-articles" data-screen-label="Related">
  <div class="tp-container tp-svc-articles__inner">
    <div class="tp-eyebrow">Keep reading</div>
    <div class="tp-svc-articles__grid">
      <?php foreach ($related as $r) : ?>
        <a href="<?php echo esc_url($r['url']); ?>" class="tp-svc-article-card">
          <div class="tp-svc-article-card__media" aria-hidden="true"></div>
          <div class="tp-svc-article-card__body">
            <div class="tp-svc-article-card__title"><?php echo esc_html($r['label']); ?></div>
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
    <h2 class="tp-svc-cta__heading">More from the workforce desk</h2>
    <p class="tp-svc-cta__subhead">One insight in your inbox each month. No noise, no forwarding chains.</p>
    <a href="mailto:business@tecnoprism.com" class="tp-pill-btn" data-hover-icon>
      <span class="tp-pill-btn__icon"><span class="tp-pill-btn__arrow">&#8594;</span></span>
      Start a conversation
    </a>
  </div>
</section>

<?php endwhile; get_footer(); ?>
