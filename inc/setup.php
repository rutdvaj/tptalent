<?php
/**
 * Theme setup: supports, menus, asset enqueue.
 */

if (!defined('ABSPATH')) exit;

function tp_setup() {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', ['search-form', 'gallery', 'caption']);
    add_theme_support('custom-logo');

    register_nav_menus([
        'primary' => __('Primary navigation', 'tecnoprism-talent'),
    ]);
}
add_action('after_setup_theme', 'tp_setup');

function tp_enqueue_assets() {
    wp_enqueue_style(
        'tp-fonts',
        'https://fonts.googleapis.com/css2?family=Newsreader:ital,opsz,wght@0,6..72,400;0,6..72,500;1,6..72,400&family=Libre+Franklin:wght@400;500;600;700&family=IBM+Plex+Mono:wght@500&display=swap',
        [],
        null
    );
    wp_enqueue_style('tp-style', TP_THEME_URI . '/style.css', [], TP_THEME_VERSION);
    wp_enqueue_style('tp-main', TP_THEME_URI . '/assets/css/style.css', ['tp-style'], TP_THEME_VERSION);

    // shader-bg is a plain custom element that loads its React dependencies
    // via dynamic import() at runtime — no <script type=module> needed.
    wp_enqueue_script('tp-shader-bg', TP_THEME_URI . '/assets/js/shader-bg.js', [], TP_THEME_VERSION, true);
    wp_enqueue_script('tp-main', TP_THEME_URI . '/assets/js/main.js', ['tp-shader-bg'], TP_THEME_VERSION, true);
}
add_action('wp_enqueue_scripts', 'tp_enqueue_assets');

/**
 * If no static front page is assigned yet, gently nudge the admin (front-page.php
 * only renders automatically once Settings > Reading is set to a static page).
 */
function tp_front_page_notice() {
    if (get_option('show_on_front') !== 'page' || !get_option('page_on_front')) {
        echo '<div class="notice notice-warning"><p><strong>Tecnoprism Talent:</strong> set Settings &rarr; Reading &rarr; "Your homepage displays" to a static page and select/create a page for it to see the homepage content fields.</p></div>';
    }
}
add_action('admin_notices', 'tp_front_page_notice');
