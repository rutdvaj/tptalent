<?php
/**
 * The static front page: hero → numbers → services → case study →
 * testimonial → capabilities → global delivery → insights → CTA → footer.
 */
get_header();
get_template_part('template-parts/home/hero');
get_template_part('template-parts/home/numbers');
get_template_part('template-parts/home/services');
get_template_part('template-parts/home/case-study-bento');
get_template_part('template-parts/home/testimonial');
get_template_part('template-parts/home/capabilities');
get_template_part('template-parts/home/global-delivery');
get_template_part('template-parts/home/insights');
get_template_part('template-parts/home/cta');
get_footer();
