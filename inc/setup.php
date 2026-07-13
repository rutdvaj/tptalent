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
        'https://fonts.googleapis.com/css2?family=Newsreader:ital,opsz,wght@0,6..72,400;0,6..72,500;0,6..72,600;1,6..72,400&family=Libre+Franklin:wght@400;500;600;700&family=IBM+Plex+Mono:wght@500&display=swap',
        [],
        null
    );
    // filemtime() cache-busts automatically on every edit — TP_THEME_VERSION
    // alone was static, so browsers could keep serving a stale cached copy
    // of these files after a change until it was manually bumped.
    $style_path = get_template_directory() . '/style.css';
    $main_css_path = get_template_directory() . '/assets/css/style.css';
    $shader_js_path = get_template_directory() . '/assets/js/shader-bg.js';
    $main_js_path = get_template_directory() . '/assets/js/main.js';

    wp_enqueue_style('tp-style', TP_THEME_URI . '/style.css', [], file_exists($style_path) ? filemtime($style_path) : TP_THEME_VERSION);
    wp_enqueue_style('tp-main', TP_THEME_URI . '/assets/css/style.css', ['tp-style'], file_exists($main_css_path) ? filemtime($main_css_path) : TP_THEME_VERSION);

    // shader-bg is a plain custom element that loads its React dependencies
    // via dynamic import() at runtime — no <script type=module> needed.
    wp_enqueue_script('tp-shader-bg', TP_THEME_URI . '/assets/js/shader-bg.js', [], file_exists($shader_js_path) ? filemtime($shader_js_path) : TP_THEME_VERSION, true);
    wp_enqueue_script('tp-main', TP_THEME_URI . '/assets/js/main.js', ['tp-shader-bg'], file_exists($main_js_path) ? filemtime($main_js_path) : TP_THEME_VERSION, true);
}
add_action('wp_enqueue_scripts', 'tp_enqueue_assets');

/**
 * One-time bootstrap: create the 3 service pages (if missing) with the
 * "Service Page" template already assigned, so the nav dropdown links
 * resolve instead of 404ing. Uses wp_insert_post() (WordPress's own API,
 * not raw SQL) and is guarded by an option flag + a per-slug existence
 * check, so it only ever inserts what's actually missing and never runs
 * its work twice.
 */
function tp_bootstrap_service_pages() {
    if (get_option('tp_service_pages_bootstrapped')) return;

    $pages = [
        'executive-search'   => 'Executive Search',
        'virtual-assistance' => 'Virtual Assistance',
        'payrolling-services' => 'Payrolling Services',
    ];
    foreach ($pages as $slug => $title) {
        if (get_page_by_path($slug)) continue;
        $id = wp_insert_post([
            'post_title'   => $title,
            'post_name'    => $slug,
            'post_status'  => 'publish',
            'post_type'    => 'page',
            'post_content' => '',
        ], true);
        if (!is_wp_error($id)) {
            update_post_meta($id, '_wp_page_template', 'page-service.php');
        }
    }
    update_option('tp_service_pages_bootstrapped', 1);
}
add_action('init', 'tp_bootstrap_service_pages');

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
