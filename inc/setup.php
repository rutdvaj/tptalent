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
    $vortex_js_path = get_template_directory() . '/assets/js/vortex-bg.js';
    $grid_glow_js_path = get_template_directory() . '/assets/js/sea-mint-grid-glow.js';
    $beam_js_path = get_template_directory() . '/assets/js/beam-bg.js';
    $main_js_path = get_template_directory() . '/assets/js/main.js';

    wp_enqueue_style('tp-style', TP_THEME_URI . '/style.css', [], file_exists($style_path) ? filemtime($style_path) : TP_THEME_VERSION);
    wp_enqueue_style('tp-main', TP_THEME_URI . '/assets/css/style.css', ['tp-style'], file_exists($main_css_path) ? filemtime($main_css_path) : TP_THEME_VERSION);

    // shader-bg is a self-hosted bundle (~340KB gzipped). Previously
    // enqueued in the footer, which meant the browser didn't even start
    // downloading it until it had parsed all the way to the end of the
    // page — the hero's text/nav/ticker (plain HTML) would paint well
    // before the shader had a chance to start loading, let alone mount.
    // 'defer' + in_footer:false puts the <script> in <head> so the
    // download starts immediately, in parallel with HTML parsing,
    // without blocking that parsing (defer semantics) — it still only
    // executes once the DOM is ready, same as before.
    wp_enqueue_script('tp-shader-bg', TP_THEME_URI . '/assets/js/shader-bg.js', [], file_exists($shader_js_path) ? filemtime($shader_js_path) : TP_THEME_VERSION, false);
    // wave-field is a self-contained WebGL custom element (no external
    // deps, unlike shader-bg) used by the blog-post hero background.
    // Enqueued site-wide like shader-bg — registering the element costs
    // nothing on pages that don't use the <wave-field> tag.
    wp_enqueue_script('tp-wave-field', TP_THEME_URI . '/assets/js/wave-field.js', [], file_exists($wave_js_path) ? filemtime($wave_js_path) : TP_THEME_VERSION, true);
    // vortex-bg replaces lightfall-bg on the service-page hero background
    // (Sea Mint Tecno Prism Vortex handoff) — self-contained Canvas 2D
    // particle flow-field, palette-driven via `colors`, with a pointer-
    // reactive swirl (not in the source design; added per request).
    wp_enqueue_script('tp-vortex-bg', TP_THEME_URI . '/assets/js/vortex-bg.js', [], file_exists($vortex_js_path) ? filemtime($vortex_js_path) : TP_THEME_VERSION, true);
    // sea-mint-grid-glow is the Industries-page hero background — a
    // self-contained Canvas 2D grid + drifting glow dots, same pattern
    // as wave-field/vortex-bg, no CDN dependency.
    wp_enqueue_script('tp-grid-glow', TP_THEME_URI . '/assets/js/sea-mint-grid-glow.js', [], file_exists($grid_glow_js_path) ? filemtime($grid_glow_js_path) : TP_THEME_VERSION, true);
    // beam-bg is the Solutions-page hero background (Tecno Prism animated-
    // background handoff) — self-contained Canvas 2D "meteor beam" streaks,
    // same pattern as vortex-bg/sea-mint-grid-glow.
    wp_enqueue_script('tp-beam-bg', TP_THEME_URI . '/assets/js/beam-bg.js', [], file_exists($beam_js_path) ? filemtime($beam_js_path) : TP_THEME_VERSION, true);
    wp_enqueue_script('tp-main', TP_THEME_URI . '/assets/js/main.js', ['tp-shader-bg', 'tp-wave-field', 'tp-vortex-bg', 'tp-grid-glow', 'tp-beam-bg'], file_exists($main_js_path) ? filemtime($main_js_path) : TP_THEME_VERSION, true);
}
add_action('wp_enqueue_scripts', 'tp_enqueue_assets');

/**
 * Preload hint for shader-bg.js on the homepage only (the only page that
 * actually uses <shader-bg>) — gets the browser starting the download a
 * beat earlier than even the deferred <script> tag would on its own,
 * since it's discovered the moment this early <link> is parsed rather
 * than waiting on the rest of <head> first.
 */
function tp_preload_shader_bg() {
    if (!is_front_page()) return;
    $path = get_template_directory() . '/assets/js/shader-bg.js';
    if (!file_exists($path)) return;
    $url = TP_THEME_URI . '/assets/js/shader-bg.js?ver=' . filemtime($path);
    echo '<link rel="preload" href="' . esc_url($url) . '" as="script">' . "\n";
}
add_action('wp_head', 'tp_preload_shader_bg', 1);

/**
 * Theme favicon (assets/img/favicon.svg — background-free icon mark,
 * see template-parts/brand-logo.php for the matching nav/footer logos).
 * Skipped if a Site Icon is already set in Settings > General, since
 * that takes precedence and already prints its own <link> tags.
 */
function tp_print_favicon() {
    if (has_site_icon()) return;
    $path = get_template_directory() . '/assets/img/favicon.svg';
    if (!file_exists($path)) return;
    $url = TP_THEME_URI . '/assets/img/favicon.svg?ver=' . filemtime($path);
    echo '<link rel="icon" type="image/svg+xml" href="' . esc_url($url) . '">' . "\n";
}
add_action('wp_head', 'tp_print_favicon', 1);

/**
 * Adds the defer attribute to shader-bg's <script> tag. Used the
 * 5-arg 'strategy' form of wp_enqueue_script first, but on this WP
 * version it didn't actually move the tag into <head> as documented —
 * this filter is the well-established, version-safe way to do it.
 */
function tp_defer_shader_bg_script($tag, $handle) {
    if ($handle !== 'tp-shader-bg') return $tag;
    if (strpos($tag, ' defer') !== false) return $tag;
    return str_replace(' src=', ' defer src=', $tag);
}
add_filter('script_loader_tag', 'tp_defer_shader_bg_script', 10, 2);

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
 * One-time: 4 more service pages (of 12 planned) + explicit menu_order
 * on all 7 pages so the nav dropdown (now sourced live by page template,
 * see tp_get_services_nav_items()) lists them in a controlled order
 * instead of whatever order they happened to be created in.
 */
function tp_bootstrap_service_pages_v2() {
    if (get_option('tp_service_pages_v2_bootstrapped')) return;

    $pages = [
        'executive-search'     => 'Executive Search',
        'virtual-assistance'   => 'Virtual Assistance',
        'payrolling-services'  => 'Payrolling Services',
        'talent-acquisition'   => 'Talent Acquisition',
        'recruiter-on-premise' => 'Recruiter on Premise (ROP)',
        'robotics-automation'  => 'Robotics & Automation',
        'generative-agentic-ai' => 'Generative AI & Agentic AI',
    ];
    $order = 0;
    foreach ($pages as $slug => $title) {
        $page = get_page_by_path($slug);
        if (!$page) {
            $id = wp_insert_post([
                'post_title'   => $title,
                'post_name'    => $slug,
                'post_status'  => 'publish',
                'post_type'    => 'page',
                'post_content' => '',
                'menu_order'   => $order,
            ], true);
            if (!is_wp_error($id)) {
                update_post_meta($id, '_wp_page_template', 'page-service.php');
            }
        } else {
            wp_update_post(['ID' => $page->ID, 'menu_order' => $order]);
        }
        $order++;
    }
    update_option('tp_service_pages_v2_bootstrapped', 1);
}
add_action('init', 'tp_bootstrap_service_pages_v2');

/**
 * One-time: 4 more service pages (of 12 planned) — Machine Learning,
 * PEGA, Engineering Services, Embedded Technologies. Continues the
 * menu_order sequence from v2 (7-10) so the nav dropdown keeps a
 * stable, controlled order as pages are added in batches.
 */
function tp_bootstrap_service_pages_v3() {
    if (get_option('tp_service_pages_v3_bootstrapped')) return;

    $pages = [
        'machine-learning'      => 'Machine Learning',
        'pega'                  => 'PEGA',
        'engineering-services'  => 'Engineering Services',
        'embedded-technologies' => 'Embedded Technologies',
    ];
    $order = 7;
    foreach ($pages as $slug => $title) {
        $page = get_page_by_path($slug);
        if (!$page) {
            $id = wp_insert_post([
                'post_title'   => $title,
                'post_name'    => $slug,
                'post_status'  => 'publish',
                'post_type'    => 'page',
                'post_content' => '',
                'menu_order'   => $order,
            ], true);
            if (!is_wp_error($id)) {
                update_post_meta($id, '_wp_page_template', 'page-service.php');
            }
        } else {
            wp_update_post(['ID' => $page->ID, 'menu_order' => $order]);
        }
        $order++;
    }
    update_option('tp_service_pages_v3_bootstrapped', 1);
}
add_action('init', 'tp_bootstrap_service_pages_v3');

/**
 * One-time: final 4 of the 12 planned service pages — Digital
 * Transformation, Software Testing, Data Analytics, Software
 * Development & Outsourcing. Continues the menu_order sequence from
 * v3 (11-14). All 15 service pages now exist.
 */
function tp_bootstrap_service_pages_v4() {
    if (get_option('tp_service_pages_v4_bootstrapped')) return;

    $pages = [
        'digital-transformation'           => 'Digital Transformation',
        'software-testing'                 => 'Software Testing',
        'data-analytics'                   => 'Data Analytics',
        'software-development-outsourcing' => 'Software Development & Outsourcing',
    ];
    $order = 11;
    foreach ($pages as $slug => $title) {
        $page = get_page_by_path($slug);
        if (!$page) {
            $id = wp_insert_post([
                'post_title'   => $title,
                'post_name'    => $slug,
                'post_status'  => 'publish',
                'post_type'    => 'page',
                'post_content' => '',
                'menu_order'   => $order,
            ], true);
            if (!is_wp_error($id)) {
                update_post_meta($id, '_wp_page_template', 'page-service.php');
            }
        } else {
            wp_update_post(['ID' => $page->ID, 'menu_order' => $order]);
        }
        $order++;
    }
    update_option('tp_service_pages_v4_bootstrapped', 1);
}
add_action('init', 'tp_bootstrap_service_pages_v4');

/**
 * One-time: first Industries page (Healthcare & Life Sciences), using
 * the new "Industry Page" template (page-industry.php). More industries
 * will follow the same pattern — just add a slug/title pair here.
 */
function tp_bootstrap_industry_pages() {
    if (get_option('tp_industry_pages_bootstrapped')) return;

    $pages = [
        'healthcare-life-sciences' => 'Healthcare & Life Sciences',
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
            update_post_meta($id, '_wp_page_template', 'page-industry.php');
        }
    }
    update_option('tp_industry_pages_bootstrapped', 1);
}
add_action('init', 'tp_bootstrap_industry_pages');

/**
 * One-time: the remaining 5 of the 6 planned Industries pages —
 * Technology & ITES, Manufacturing & Energy, BFSI, Automotive &
 * Aerospace, Digital Transformation. Same pattern as
 * tp_bootstrap_industry_pages(). All 6 industry pages now exist.
 */
function tp_bootstrap_industry_pages_v2() {
    if (get_option('tp_industry_pages_v2_bootstrapped')) return;

    $pages = [
        'technology-ites'        => 'Technology & ITES',
        'manufacturing-energy'   => 'Manufacturing & Energy',
        'bfsi'                   => 'BFSI',
        'automotive-aerospace'   => 'Automotive & Aerospace',
        'digital-transformation' => 'Digital Transformation',
    ];
    $order = 1;
    foreach ($pages as $slug => $title) {
        if (get_page_by_path($slug)) continue;
        $id = wp_insert_post([
            'post_title'   => $title,
            'post_name'    => $slug,
            'post_status'  => 'publish',
            'post_type'    => 'page',
            'post_content' => '',
            'menu_order'   => $order,
        ], true);
        if (!is_wp_error($id)) {
            update_post_meta($id, '_wp_page_template', 'page-industry.php');
        }
        $order++;
    }
    update_option('tp_industry_pages_v2_bootstrapped', 1);
}
add_action('init', 'tp_bootstrap_industry_pages_v2');

/**
 * One-time: the Digital Transformation INDUSTRY page, under its own
 * distinct slug (digital-transformation-industry) — v2 above tried to
 * use the plain 'digital-transformation' slug, which collided with the
 * pre-existing Digital Transformation SERVICE page. See
 * tp_bootstrap_fix_digital_transformation_collision() in
 * inc/acf-fields.php for the repair of that service page's corrupted
 * fields.
 */
function tp_bootstrap_industry_pages_v3() {
    if (get_option('tp_industry_pages_v3_bootstrapped')) return;

    $slug = 'digital-transformation-industry';
    if (!get_page_by_path($slug)) {
        $id = wp_insert_post([
            'post_title'   => 'Digital Transformation',
            'post_name'    => $slug,
            'post_status'  => 'publish',
            'post_type'    => 'page',
            'post_content' => '',
            'menu_order'   => 5,
        ], true);
        if (!is_wp_error($id)) {
            update_post_meta($id, '_wp_page_template', 'page-industry.php');
        }
    }
    update_option('tp_industry_pages_v3_bootstrapped', 1);
}
add_action('init', 'tp_bootstrap_industry_pages_v3');

/**
 * One-time: the 2 Solutions pages with real content so far (Permanent
 * Staffing, Contract Staffing) — same pattern as
 * tp_bootstrap_industry_pages(). Executive Search and Recruitment
 * Process Outsourcing get added here once their copy is provided; the
 * Solutions nav dropdown (tp_get_solutions_nav_items()) only lists
 * pages that actually exist, so it'll pick up the other 2 automatically
 * once they're created.
 */
function tp_bootstrap_solution_pages() {
    if (get_option('tp_solution_pages_bootstrapped')) return;

    $pages = [
        'permanent-staffing' => 'Permanent Staffing',
        'contract-staffing'  => 'Contract Staffing',
    ];
    $order = 1;
    foreach ($pages as $slug => $title) {
        if (get_page_by_path($slug)) { $order++; continue; }
        $id = wp_insert_post([
            'post_title'   => $title,
            'post_name'    => $slug,
            'post_status'  => 'publish',
            'post_type'    => 'page',
            'post_content' => '',
            'menu_order'   => $order,
        ], true);
        if (!is_wp_error($id)) {
            update_post_meta($id, '_wp_page_template', 'page-solution.php');
        }
        $order++;
    }
    update_option('tp_solution_pages_bootstrapped', 1);
}
add_action('init', 'tp_bootstrap_solution_pages');

/**
 * One-time: the remaining 2 Solutions pages (Executive Search,
 * Recruitment Process Outsourcing) now that their copy has been
 * provided — same pattern as tp_bootstrap_solution_pages(). Executive
 * Search uses the 'executive-search-solution' slug, not the plain
 * 'executive-search' slug already taken by the Executive Search SERVICE
 * page — see the comment in inc/solution-content.php for why.
 */
function tp_bootstrap_solution_pages_v2() {
    if (get_option('tp_solution_pages_v2_bootstrapped')) return;

    $pages = [
        'executive-search-solution'       => 'Executive Search',
        'recruitment-process-outsourcing' => 'Recruitment Process Outsourcing (RPO)',
    ];
    $order = 3;
    foreach ($pages as $slug => $title) {
        if (get_page_by_path($slug)) { $order++; continue; }
        $id = wp_insert_post([
            'post_title'   => $title,
            'post_name'    => $slug,
            'post_status'  => 'publish',
            'post_type'    => 'page',
            'post_content' => '',
            'menu_order'   => $order,
        ], true);
        if (!is_wp_error($id)) {
            update_post_meta($id, '_wp_page_template', 'page-solution.php');
        }
        $order++;
    }
    update_option('tp_solution_pages_v2_bootstrapped', 1);
}
add_action('init', 'tp_bootstrap_solution_pages_v2');

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
 * Same pattern as tp_bootstrap_blog_posts(), for the 2nd batch of blog
 * posts (inc/blog-seed-content.php's tp_blog_seed_posts_v2()) — also
 * stores each post's short 'nav_label' as post meta, read by
 * tp_get_insights_nav_items() for the Insights nav dropdown instead of
 * the (much longer) full title.
 */
function tp_bootstrap_blog_posts_v2() {
    if (get_option('tp_blog_posts_v2_bootstrapped')) return;

    foreach (tp_blog_seed_posts_v2() as $slug => $post) {
        if (get_page_by_path($slug, OBJECT, 'post')) continue;
        $id = wp_insert_post([
            'post_title'   => $post['title'],
            'post_name'    => $slug,
            'post_excerpt' => $post['excerpt'],
            'post_content' => $post['content'],
            'post_status'  => 'publish',
            'post_type'    => 'post',
            'post_date'    => '2026-07-23 09:00:00',
        ], true);
        if (!is_wp_error($id) && !empty($post['nav_label'])) {
            update_post_meta($id, 'tp_nav_label', $post['nav_label']);
        }
    }

    update_option('tp_blog_posts_v2_bootstrapped', 1);
}
add_action('init', 'tp_bootstrap_blog_posts_v2');

/**
 * One-time: retire the 2 original launch blog posts ("The new deal at
 * work", "The absorption gap") now that the 2nd batch has replaced them
 * as the site's live Insights content. Trashed, not permanently
 * deleted — same reversible pattern used for the "hello-world" sample
 * post in tp_bootstrap_blog_posts().
 */
function tp_bootstrap_trash_launch_blog_posts() {
    if (get_option('tp_launch_blog_posts_trashed')) return;

    foreach (['the-new-deal-at-work', 'the-absorption-gap'] as $slug) {
        $post = get_page_by_path($slug, OBJECT, 'post');
        if ($post) wp_trash_post($post->ID);
    }

    update_option('tp_launch_blog_posts_trashed', 1);
}
add_action('init', 'tp_bootstrap_trash_launch_blog_posts');

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
