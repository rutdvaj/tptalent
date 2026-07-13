<?php
/**
 * Featured case study — bento grid (glassy cards, animated WebGL fluid
 * ripple background, no gradient-glow border/halo).
 */
$c = tp_get_section('tp_case_study');
$roles = array_map(function ($r) { return $r['value']; }, is_array($c['roles']) ? $c['roles'] : []);
$rolesLoop = array_merge($roles, $roles);
$monthsCount = max(1, (int) $c['months_count']);
?>
<section id="tpBentoSec" class="tp-bento" data-screen-label="Case study bento">
  <canvas id="tpBentoFx" class="tp-bento__fx" aria-hidden="true"></canvas>
  <div class="tp-container tp-bento__inner">
    <div class="tp-bento__head" data-reveal>
      <div class="tp-eyebrow tp-eyebrow--light"><?php echo esc_html($c['eyebrow']); ?></div>
      <h2 class="tp-h2 tp-h2--light"><?php echo esc_html($c['heading']); ?></h2>
    </div>

    <div class="tp-bento__grid">

      <div class="tp-bento-card tp-bento-card--wide" data-reveal>
        <div class="tp-bento-card__marquee tp-bento-card__marquee--a">
          <div class="tp-bento-card__marquee-track">
            <?php foreach ($rolesLoop as $r) : ?><span class="tp-role-chip"><?php echo esc_html($r); ?></span><?php endforeach; ?>
          </div>
        </div>
        <div class="tp-bento-card__marquee tp-bento-card__marquee--b">
          <div class="tp-bento-card__marquee-track tp-bento-card__marquee-track--reverse">
            <?php foreach ($rolesLoop as $r) : ?><span class="tp-role-chip tp-role-chip--dim"><?php echo esc_html($r); ?></span><?php endforeach; ?>
          </div>
        </div>
        <div class="tp-bento-card__title"><?php echo esc_html($c['sourcing_title']); ?></div>
        <p class="tp-bento-card__body"><?php echo esc_html($c['sourcing_body']); ?></p>
      </div>

      <div class="tp-bento-card" data-reveal>
        <div class="tp-bento-card__bars" aria-hidden="true">
          <?php for ($b = 0; $b < 5; $b++) : $h = [42, 58, 70, 84, 100][$b]; ?>
            <div class="tp-bento-card__bar" style="height:<?php echo (int) $h; ?>%; animation-delay:<?php echo number_format($b * 0.3, 1); ?>s;"></div>
          <?php endfor; ?>
        </div>
        <div class="tp-bento-card__stat"><?php echo esc_html($c['fill_rate_value']); ?></div>
        <div class="tp-bento-card__caption"><?php echo esc_html($c['fill_rate_label']); ?></div>
      </div>

      <div class="tp-bento-card" data-reveal>
        <div class="tp-bento-card__dots" aria-hidden="true">
          <?php for ($m = 1; $m <= $monthsCount; $m++) : ?>
            <div class="tp-bento-card__dot-col">
              <span class="tp-bento-card__dot" style="animation-delay:<?php echo number_format(($m - 1) * 0.4, 1); ?>s;"></span>
              <span class="tp-bento-card__dot-label">M<?php echo (int) $m; ?></span>
            </div>
          <?php endfor; ?>
        </div>
        <div class="tp-bento-card__stat tp-bento-card__stat--white"><?php echo esc_html($c['months_value']); ?></div>
        <div class="tp-bento-card__caption"><?php echo esc_html($c['months_label']); ?></div>
      </div>

      <div class="tp-bento-card" data-reveal>
        <div class="tp-bento-card__rings" aria-hidden="true">
          <span class="tp-bento-card__ring tp-bento-card__ring--outer"></span>
          <span class="tp-bento-card__ring tp-bento-card__ring--inner"></span>
        </div>
        <div class="tp-bento-card__stat tp-bento-card__stat--white"><?php echo esc_html($c['retention_value']); ?></div>
        <div class="tp-bento-card__caption"><?php echo esc_html($c['retention_label']); ?></div>
      </div>

      <div class="tp-bento-card tp-bento-card--cta" data-reveal>
        <span class="tp-bento-card__cube tp-bento-card__cube--a" aria-hidden="true"></span>
        <span class="tp-bento-card__cube tp-bento-card__cube--b" aria-hidden="true"></span>
        <div class="tp-bento-card__cta-text"><?php echo esc_html($c['cta_card_text']); ?></div>
        <a href="<?php echo esc_url($c['cta_url']); ?>" class="tp-pill-btn tp-pill-btn--dark" data-hover-icon>
          <span class="tp-pill-btn__icon"><span class="tp-pill-btn__arrow">→</span></span>
          <?php echo esc_html($c['cta_label']); ?>
        </a>
      </div>

    </div>
  </div>
</section>
