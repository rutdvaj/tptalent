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
    $wave_js_path = get_template_directory() . '/assets/js/wave-field.js';
    $lightfall_js_path = get_template_directory() . '/assets/js/lightfall-bg.js';
    $main_js_path = get_template_directory() . '/assets/js/main.js';

    wp_enqueue_style('tp-style', TP_THEME_URI . '/style.css', [], file_exists($style_path) ? filemtime($style_path) : TP_THEME_VERSION);
    wp_enqueue_style('tp-main', TP_THEME_URI . '/assets/css/style.css', ['tp-style'], file_exists($main_css_path) ? filemtime($main_css_path) : TP_THEME_VERSION);

    // shader-bg is a plain custom element that loads its React dependencies
    // via dynamic import() at runtime — no <script type=module> needed.
    wp_enqueue_script('tp-shader-bg', TP_THEME_URI . '/assets/js/shader-bg.js', [], file_exists($shader_js_path) ? filemtime($shader_js_path) : TP_THEME_VERSION, true);
    // wave-field is a self-contained WebGL custom element (no external
    // deps, unlike shader-bg) used by the blog-post hero background.
    // Enqueued site-wide like shader-bg — registering the element costs
    // nothing on pages that don't use the <wave-field> tag.
    wp_enqueue_script('tp-wave-field', TP_THEME_URI . '/assets/js/wave-field.js', [], file_exists($wave_js_path) ? filemtime($wave_js_path) : TP_THEME_VERSION, true);
    // lightfall-bg replaces wave-field on the service-page hero background
    // (updated design handoff) — same self-contained WebGL custom element
    // pattern, just a different streak/glow effect instead of waves.
    wp_enqueue_script('tp-lightfall-bg', TP_THEME_URI . '/assets/js/lightfall-bg.js', [], file_exists($lightfall_js_path) ? filemtime($lightfall_js_path) : TP_THEME_VERSION, true);
    wp_enqueue_script('tp-main', TP_THEME_URI . '/assets/js/main.js', ['tp-shader-bg', 'tp-wave-field', 'tp-lightfall-bg'], file_exists($main_js_path) ? filemtime($main_js_path) : TP_THEME_VERSION, true);
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
 * Same pattern as tp_bootstrap_service_pages(), for the 2 launch blog
 * posts (see inc/blog-seed-content.php) — native 'post' type so they
 * behave like any post written in wp-admin from here on.
 */
function tp_bootstrap_blog_posts() {
    if (get_option('tp_blog_posts_bootstrapped')) return;

    foreach (tp_blog_seed_posts() as $slug => $post) {
        if (get_page_by_path($slug, OBJECT, 'post')) continue;
        wp_insert_post([
            'post_title'   => $post['title'],
            'post_name'    => $slug,
            'post_excerpt' => $post['excerpt'],
            'post_content' => $post['content'],
            'post_status'  => 'publish',
            'post_type'    => 'post',
            'post_date'    => '2026-07-01 09:00:00',
        ], true);
    }

    // WordPress's default sample post — was cluttering the live Insights
    // dropdown before any real posts existed; trashed (not permanently
    // deleted) now that real content is in place.
    $hello = get_page_by_path('hello-world', OBJECT, 'post');
    if ($hello) wp_trash_post($hello->ID);

    update_option('tp_blog_posts_bootstrapped', 1);
}
add_action('init', 'tp_bootstrap_blog_posts');

/**
 * Creates a real "Home" page and points Settings > Reading at it, so the
 * content meta-box / ACF system has an actual post to attach saved values
 * to. front-page.php already rendered at the site root before this (WP
 * treats the site root as the front page even in default "posts" mode),
 * so this doesn't change what's displayed — it just gives the editing
 * system somewhere real to save to instead of only ever seeing defaults.
 */
function tp_bootstrap_home_page() {
    if (get_option('tp_home_page_bootstrapped')) return;

    $home = get_page_by_path('home');
    $home_id = $home ? $home->ID : wp_insert_post([
        'post_title'   => 'Home',
        'post_name'    => 'home',
        'post_status'  => 'publish',
        'post_type'    => 'page',
        'post_content' => '',
    ], true);

    if (!is_wp_error($home_id) && $home_id) {
        update_option('show_on_front', 'page');
        update_option('page_on_front', $home_id);
    }
    update_option('tp_home_page_bootstrapped', 1);
}
add_action('init', 'tp_bootstrap_home_page');

/**
 * One-time: site title (Settings > General > Site Title) was left at
 * Local's auto-generated default ("Tptest") — this shows in the browser
 * tab. Guarded so it only ever runs once and won't overwrite a manual
 * edit made afterward in Settings.
 */
function tp_bootstrap_site_title() {
    if (get_option('tp_site_title_bootstrapped')) return;
    update_option('blogname', 'TecnoPrismTalent');
    update_option('tp_site_title_bootstrapped', 1);
}
add_action('init', 'tp_bootstrap_site_title');

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
