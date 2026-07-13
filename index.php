<?php
/**
 * Fallback template. This theme is built around the one-page homepage
 * (front-page.php) — set Settings -> Reading -> a static page to see it.
 */
get_header();
if (have_posts()) :
    while (have_posts()) : the_post();
        echo '<div style="max-width:760px;margin:0 auto;padding:80px 24px;">';
        the_title('<h1>', '</h1>');
        the_content();
        echo '</div>';
    endwhile;
endif;
get_footer();
