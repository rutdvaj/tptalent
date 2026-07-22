<?php
/** Site footer — brand lockup, link columns, legal line. */
$brand = tp_get_section('tp_brand');
$f = tp_get_section('tp_footer');
$company = is_array($f['company_links']) ? $f['company_links'] : [];
$connect = is_array($f['connect_links']) ? $f['connect_links'] : [];
?>
<footer class="tp-footer" data-screen-label="Footer">
  <div class="tp-container">
    <div class="tp-footer__top" data-reveal>
      <div>
        <a class="tp-footer__brand" href="<?php echo esc_url(home_url('/')); ?>">
          <?php get_template_part('template-parts/brand-logo', null, ['prefix' => 'footer']); ?>
        </a>
        <p class="tp-footer__tagline"><?php echo esc_html($f['tagline']); ?></p>
      </div>
      <div class="tp-footer__col">
        <div class="tp-footer__col-label">Company</div>
        <?php foreach ($company as $l) : ?>
          <a href="<?php echo esc_url($l['url']); ?>"><?php echo esc_html($l['label']); ?></a>
        <?php endforeach; ?>
      </div>
      <div class="tp-footer__col">
        <div class="tp-footer__col-label">Connect</div>
        <?php foreach ($connect as $l) : ?>
          <a href="<?php echo esc_url($l['url']); ?>"><?php echo esc_html($l['label']); ?></a>
        <?php endforeach; ?>
      </div>
    </div>
    <div class="tp-footer__legal">
      <div><?php echo esc_html($f['copyright']); ?></div>
      <div class="tp-footer__legal-links">
        <a href="<?php echo esc_url(home_url('/privacy')); ?>">Privacy</a>
        <a href="<?php echo esc_url(home_url('/terms')); ?>">Terms</a>
      </div>
    </div>
  </div>
</footer>
