<?php
/**
 * Color math (ported 1:1 from the design prototype's applyTheme()) + content
 * defaults + small getters used by every template part.
 *
 * All homepage copy lives in post meta on the site's static front page,
 * one array per section (see tp_default() below for the shape/fallback
 * copy). Editors change it from the "Homepage Content" meta boxes.
 */

if (!defined('ABSPATH')) exit;

/* ---------------------------------------------------------------------
 * Palette + derived color tokens
 * The prototype could switch palettes live (Sea Mint / Prism / Custom).
 * Production ships Sea Mint only — the original ShaderGradient "Mint"
 * preset colors — computed once, not user-editable.
 * ------------------------------------------------------------------- */

function tp_palette() {
    return ['#94ffd1', '#6bf5ff', '#ffffff'];
}

/** Comma-joined palette for the <wave-field> custom element's "colors" attribute. */
function tp_wave_colors() {
    return implode(',', tp_palette());
}

function tp_hex_to_arr($hex) {
    $hex = ltrim($hex ?: '#808080', '#');
    $n = hexdec($hex);
    return [($n >> 16) & 255, ($n >> 8) & 255, $n & 255];
}

function tp_mix_arr($a, $b, $t) {
    return [
        $a[0] + ($b[0] - $a[0]) * $t,
        $a[1] + ($b[1] - $a[1]) * $t,
        $a[2] + ($b[2] - $a[2]) * $t,
    ];
}

function tp_to_hex($a) {
    $f = function ($v) { return str_pad(dechex(max(0, min(255, (int) round($v)))), 2, '0', STR_PAD_LEFT); };
    return '#' . $f($a[0]) . $f($a[1]) . $f($a[2]);
}

function tp_to_rgba($a, $alpha) {
    return 'rgba(' . round($a[0]) . ',' . round($a[1]) . ',' . round($a[2]) . ',' . $alpha . ')';
}

/**
 * Derives the full accent scale from the Sea Mint palette, exactly like
 * the prototype's Component.applyTheme(). Returns a flat [css-var => value]
 * map, printed once as a <style> block in wp_head.
 */
function tp_theme_vars() {
    static $vars = null;
    if ($vars !== null) return $vars;

    $pal = tp_palette();
    $black = [10, 12, 12];
    $white = [255, 255, 255];
    $blend = [0, 0, 0];
    foreach ($pal as $hex) {
        $a = tp_hex_to_arr($hex);
        $blend[0] += $a[0]; $blend[1] += $a[1]; $blend[2] += $a[2];
    }
    $n = count($pal);
    $blend = [$blend[0] / $n, $blend[1] / $n, $blend[2] / $n];

    $ink        = tp_mix_arr($blend, $black, 0.62);
    $ink_deep   = tp_mix_arr($blend, $black, 0.76);
    $mid        = tp_mix_arr($blend, $black, 0.30);
    $soft       = tp_mix_arr($blend, $white, 0.22);
    $wash_a     = tp_mix_arr($blend, $white, 0.42);
    $wash_b     = tp_mix_arr($blend, $white, 0.58);
    $chip_border = tp_mix_arr($blend, $white, 0.30);
    $metric     = tp_mix_arr($blend, $black, 0.42);

    $vars = [
        '--ink'        => tp_to_hex($ink),
        '--ink-deep'   => tp_to_hex($ink_deep),
        '--mid'        => tp_to_hex($mid),
        '--soft'       => tp_to_hex($soft),
        '--wash-a'     => tp_to_hex($wash_a),
        '--wash-b'     => tp_to_hex($wash_b),
        '--tint-soft'  => tp_to_rgba($wash_b, 0.6),
        '--tint-border' => tp_to_rgba($chip_border, 0.9),
        '--chip-border' => tp_to_hex($chip_border),
        '--chip-text'  => tp_to_hex($mid),
        '--metric'     => tp_to_hex($metric),
        '--divider'    => tp_to_rgba($mid, 0.35),
    ];
    for ($i = 0; $i < 5; $i++) $vars['--p' . ($i + 1)] = $pal[$i % $n];

    $c1 = tp_to_rgba(tp_hex_to_arr($pal[0]), 0.22);
    $c2 = tp_to_rgba(tp_hex_to_arr($pal[1]), 0.16);
    $c3 = tp_to_rgba(tp_hex_to_arr($pal[2]), 0.24);
    $vars['--testimonial-bg'] = "linear-gradient(150deg, $c1 0%, $c2 50%, $c3 100%), #FFFFFF";
    $vars['--map-bg'] = tp_to_hex(tp_mix_arr(tp_hex_to_arr($pal[0]), $black, 0.87));

    // Bento case-study cards — "Glassy" style (frosted, static border, no glow halo).
    $vars['--bento-bg']       = 'radial-gradient(120% 130% at 75% 30%, ' . tp_to_hex($mid) . ' -25%, ' . tp_to_hex($ink) . ' 32%, ' . tp_to_hex($ink_deep) . ' 68%, #08120F 100%)';
    $vars['--bento-border']   = 'rgba(255,255,255,0.14)';
    $vars['--bento-card-bg']  = 'rgba(6,15,11,0.55)';
    $vars['--bento-card-bg2'] = 'linear-gradient(140deg, rgba(6,15,11,0.62), rgba(6,15,11,0.45))';
    $vars['--bento-shadow']   = 'none';
    $vars['--bento-hov']      = 'inset 0 0 0 1.5px rgba(255,255,255,0.34)';

    return $vars;
}

function tp_print_theme_vars() {
    $css = ':root{';
    foreach (tp_theme_vars() as $k => $v) $css .= $k . ':' . $v . ';';
    $css .= '}';
    echo '<style id="tp-theme-vars">' . $css . '</style>' . "\n";
}
add_action('wp_head', 'tp_print_theme_vars', 5);

/**
 * Builds the shadergradient.co query string used by the hero background,
 * with the Sea Mint colors + the prototype's shipped motion defaults
 * (speed/density/strength/brightness — no live tweaker in production).
 */
function tp_shader_url() {
    $pal = tp_palette();
    $q = [
        'animate' => 'on', 'axesHelper' => 'off', 'brightness' => '1.2',
        'cAzimuthAngle' => '170', 'cDistance' => '4.4', 'cPolarAngle' => '70', 'cameraZoom' => '1',
        'color1' => $pal[0], 'color2' => $pal[1], 'color3' => $pal[2],
        'destination' => 'onCanvas', 'embedMode' => 'off', 'envPreset' => 'city', 'format' => 'gif', 'fov' => '45',
        'frameRate' => '10', 'gizmoHelper' => 'hide', 'grain' => 'off', 'lightType' => '3d', 'pixelDensity' => '1',
        'positionX' => '0', 'positionY' => '0.9', 'positionZ' => '-0.3', 'range' => 'disabled', 'rangeEnd' => '40',
        'rangeStart' => '0', 'reflection' => '0.1', 'rotationX' => '45', 'rotationY' => '0', 'rotationZ' => '0',
        'shader' => 'defaults', 'type' => 'waterPlane', 'uAmplitude' => '0', 'uDensity' => '1.2',
        'uFrequency' => '0', 'uSpeed' => '0.2', 'uStrength' => '3.4', 'uTime' => '0', 'wireframe' => 'false',
    ];
    return 'https://www.shadergradient.co/customize?' . http_build_query($q);
}

/* ---------------------------------------------------------------------
 * Content sections: defaults + saved-meta getters
 * ------------------------------------------------------------------- */

function tp_front_page_id() {
    $id = (int) get_option('page_on_front');
    return $id ?: 0;
}

function tp_default($section) {
    $defaults = [
        'tp_brand' => [
            'name' => 'TECNOPRISM',
            'sub'  => 'TALENT',
        ],
        'tp_nav' => [
            'services_label' => 'Services', 'services_url' => '#services',
            'industries_label' => 'Industries', 'industries_url' => '#',
            'insights_label' => 'Insights', 'insights_url' => '#insights',
            'about_label' => 'About Us', 'about_url' => '#',
            'cta_label' => 'Find jobs', 'cta_url' => '#',
        ],
        'tp_hero' => [
            'headline' => 'Workforce partners for long‑term growth',
            'subhead' => 'Staffing, RPO and workforce consulting for organisations that plan in years, not quarters.',
            'browse_label' => 'Browse talent', 'browse_url' => '#',
            'hire_label' => 'Hire talent', 'hire_url' => '#',
            'metric1_value' => '98%', 'metric1_label' => 'Fill-rate SLA',
            'metric2_value' => '2,000+', 'metric2_label' => 'Placements',
            'ticker_logos' => array_map(function ($n) {
                return ['id' => 0, 'fallback' => "assets/images/logos/image-$n.webp"];
            }, [1, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14]),
        ],
        'tp_services' => [
            'eyebrow' => 'What we do',
            'heading' => 'Five ways we build your workforce',
            'intro' => 'Embedded teams that own outcomes, not requisitions — from a single hire to an entire talent function.',
            'items' => [
                ['num' => '01', 'glyph' => 'E', 'title' => 'Executive Search', 'body' => 'Confidential, research-led search for CXO and senior leadership roles — mapped, vetted and closed with discretion.', 'url' => '#'],
                ['num' => '02', 'glyph' => 'V', 'title' => 'Virtual Assistance', 'body' => 'Trained remote professionals for admin, operations and back-office support — onboarded in days, managed by us.', 'url' => '#'],
                ['num' => '03', 'glyph' => 'P', 'title' => 'Payrolling Services', 'body' => 'Fully compliant payroll and contractor management — statutory filings, benefits and onboarding handled end to end.', 'url' => '#'],
                ['num' => '04', 'glyph' => 'T', 'title' => 'Talent Acquisition', 'body' => 'End-to-end hiring engines across technology and enterprise roles, built to scale with your growth plans.', 'url' => '#'],
                ['num' => '05', 'glyph' => 'R', 'title' => 'Recruiter on Premise', 'body' => 'Our recruiters embedded inside your offices, working your requisitions as a true in-house team.', 'url' => '#'],
            ],
        ],
        'tp_case_study' => [
            'eyebrow' => 'Featured case study',
            'heading' => 'How we scaled a 400-person delivery org in nine months',
            'sourcing_title' => 'One sourcing engine, forty disciplines',
            'sourcing_body' => 'Placeholder — describe how the pipeline covered every role family without adding vendors.',
            'roles' => array_map(function ($v) { return ['value' => $v]; }, ['Cloud Engineers', 'Data Platform', 'SRE', 'QA Automation', 'Scrum Leads', 'Security', 'FP&A Analysts', 'Delivery Managers']),
            'fill_rate_value' => '96%', 'fill_rate_label' => 'Fill rate at ramp peak',
            'months_count' => 9, 'months_value' => '9 months', 'months_label' => 'Zero to full delivery org',
            'retention_value' => '94%', 'retention_label' => 'Retention after year one',
            'cta_card_text' => 'The full ramp plan, sourcing engine and retention numbers — in 36 pages.',
            'cta_label' => 'Read the report', 'cta_url' => '#',
        ],
        'tp_testimonial' => [
            'heading' => 'What our clients say',
            'rotator' => [
                ['name' => 'Northwind', 'desc' => 'Fintech', 'metric' => '+38% fill-rate'],
                ['name' => 'Lumen', 'desc' => 'SaaS', 'metric' => '120 hires in 90 days'],
                ['name' => 'Atlas', 'desc' => 'Retail', 'metric' => '−31% attrition'],
                ['name' => 'Cobalt', 'desc' => 'Healthcare', 'metric' => '4.1x ROI'],
                ['name' => 'Vertex', 'desc' => 'Logistics', 'metric' => '24h SLA response'],
                ['name' => 'Meridian', 'desc' => 'Media', 'metric' => '+52% retention'],
                ['name' => 'Sable', 'desc' => 'Energy', 'metric' => '2,000+ placements'],
                ['name' => 'Halcyon', 'desc' => 'Travel', 'metric' => '8 markets entered'],
            ],
            'quote' => 'We’ve had a really good relationship with Tecnoprism. The quality is high. You’re keen, you understand, you learn, you’re adapting — we are also adapting together, and it’s been a success.',
            'client_name' => 'Client name',
            'client_title' => 'Chief Technology Officer · Company',
        ],
        'tp_capabilities' => [
            'eyebrow' => 'Capabilities',
            'heading' => 'Skills we place across every stack',
            'intro' => 'From engineering and data to finance and operations leadership — vetted, credentialed and ready for enterprise delivery.',
            'faqs' => [
                ['title' => 'Technology & Engineering', 'body' => 'Software, cloud, data engineering, security and platform roles — contract through executive search.'],
                ['title' => 'Finance & Operations', 'body' => 'Controllers, FP&A, procurement and operations leadership for scaling enterprises.'],
                ['title' => 'Human Resources', 'body' => 'Talent acquisition, HRBP and people-operations specialists who build teams that stay.'],
                ['title' => 'Sales & Go-to-market', 'body' => 'Revenue leadership, enterprise sales and marketing talent tuned to your growth motion.'],
            ],
        ],
        'tp_global' => [
            'eyebrow' => 'Where we operate',
            'heading' => "Global expertise,<br>local collaboration",
            'intro' => 'Onshore leadership, offshore scale. Ten delivery locations across three regions keep work moving around the clock.',
            'regions' => [
                ['label' => 'Americas', 'value' => 'USA · Canada · Brazil · Colombia · Chile · Argentina'],
                ['label' => 'MENA', 'value' => 'Dubai · Abu Dhabi · Oman · Kuwait · Saudi Arabia · Qatar · Bahrain'],
                ['label' => 'APAC', 'value' => 'India · Singapore · Malaysia · Australia · New Zealand · Hong Kong · Indonesia · Thailand · Philippines · Vietnam · Taiwan · Japan · South Korea'],
                ['label' => 'Africa', 'value' => 'Egypt · South Africa · Kenya · Nigeria · Mauritius'],
            ],
            // Fixed at 8 markers — the beam sequence below references these by
            // position, so rows can be edited in place but not added/removed.
            'locations' => [
                ['name' => 'India', 'lat' => '13.0', 'lon' => '77.6', 'hq' => 1],
                ['name' => 'USA', 'lat' => '40.7', 'lon' => '-74.0', 'hq' => 0],
                ['name' => 'Canada', 'lat' => '43.7', 'lon' => '-79.4', 'hq' => 0],
                ['name' => 'Brazil', 'lat' => '-23.55', 'lon' => '-46.6', 'hq' => 0],
                ['name' => 'Colombia', 'lat' => '4.7', 'lon' => '-74.1', 'hq' => 0],
                ['name' => 'Dubai', 'lat' => '25.2', 'lon' => '55.3', 'hq' => 0],
                ['name' => 'Kuwait', 'lat' => '29.4', 'lon' => '48.0', 'hq' => 0],
                ['name' => 'Singapore', 'lat' => '1.35', 'lon' => '103.8', 'hq' => 0],
            ],
        ],
        'tp_insights' => [
            'eyebrow' => 'Insights',
            'heading' => 'Field notes on the workforce',
            'view_all_label' => 'All insights', 'view_all_url' => '#',
            'articles' => [
                ['tag' => 'RPO', 'read' => '5 min read', 'title' => 'Article title placeholder goes here', 'url' => '#'],
                ['tag' => 'Hiring', 'read' => '8 min read', 'title' => 'Article title placeholder goes here', 'url' => '#'],
                ['tag' => 'Report', 'read' => '3 min read', 'title' => 'Article title placeholder goes here', 'url' => '#'],
            ],
        ],
        'tp_cta' => [
            'heading' => 'Let’s plan in years, not quarters',
            'subhead' => 'One conversation to understand your situation — we reply within one business day.',
            'button_label' => 'Start a conversation', 'button_url' => '#',
        ],
        'tp_footer' => [
            'tagline' => 'Enterprise workforce partner. India · US · Dubai · Singapore.',
            'company_links' => [
                ['label' => 'Services', 'url' => '#services'],
                ['label' => 'Industries', 'url' => '#'],
                ['label' => 'Insights', 'url' => '#insights'],
                ['label' => 'About', 'url' => '#'],
            ],
            'connect_links' => [
                ['label' => 'Talk to us', 'url' => '#'],
                ['label' => 'LinkedIn', 'url' => '#'],
                ['label' => 'Careers', 'url' => '#'],
            ],
            'copyright' => '© 2026 Tecnoprism Talent — a subsidiary of Tecnoprism Pvt. Ltd.',
        ],
    ];
    return $defaults[$section] ?? [];
}

/**
 * Saved-meta (on the static front page) merged over the section defaults.
 * Repeater/array fields are replaced wholesale when present in saved meta.
 */
function tp_get_section($section) {
    static $cache = [];
    if (isset($cache[$section])) return $cache[$section];
    $defaults = tp_default($section);
    $id = tp_front_page_id();
    $saved = $id ? tp_get_section_saved_data($section, $id) : '';
    $data = (is_array($saved) && !empty($saved)) ? array_replace($defaults, $saved) : $defaults;
    $cache[$section] = $data;
    return $data;
}

/**
 * tp_case_study and tp_testimonial are ACF-backed (see inc/acf-fields.php)
 * — every other section still reads the original meta-box post meta.
 * Keeping this switch inside tp_get_section() means the template parts
 * never need to know or care which system a given section uses.
 */
function tp_get_section_saved_data($section, $id) {
    if ($section === 'tp_case_study' && function_exists('get_field')) {
        $keys = ['eyebrow', 'heading', 'sourcing_title', 'sourcing_body', 'fill_rate_value', 'fill_rate_label', 'months_count', 'months_value', 'months_label', 'retention_value', 'retention_label', 'cta_card_text', 'cta_label', 'cta_url'];
        $out = [];
        foreach ($keys as $k) {
            $v = get_field($k, $id);
            if ($v !== null && $v !== '') $out[$k] = $v;
        }
        return $out;
    }
    if ($section === 'tp_testimonial' && function_exists('get_field')) {
        $rotator = [];
        for ($i = 1; $i <= 8; $i++) {
            $g = get_field('rotator_' . $i, $id);
            if (is_array($g) && !empty($g['name'])) {
                $rotator[] = ['name' => $g['name'], 'desc' => $g['desc'], 'metric' => $g['metric']];
            }
        }
        return $rotator ? ['rotator' => $rotator] : [];
    }
    return get_post_meta($id, $section, true);
}

function tp_field($section, $key, $fallback = '') {
    $data = tp_get_section($section);
    if (!isset($data[$key]) || $data[$key] === '') return $fallback;
    return $data[$key];
}

/** Resolves a repeater image row (['id'=>attachment_id,'fallback'=>theme-relative path]) to a URL. */
function tp_image_url($row, $size = 'medium') {
    if (!empty($row['id'])) {
        $url = wp_get_attachment_image_url((int) $row['id'], $size);
        if ($url) return $url;
    }
    return !empty($row['fallback']) ? TP_THEME_URI . '/' . ltrim($row['fallback'], '/') : '';
}

/* ---------------------------------------------------------------------
 * Nav data — Services dropdown + Insights dropdown (shared by the
 * homepage nav and the subpage nav partial).
 * ------------------------------------------------------------------- */

/**
 * Services are plain WP Pages for now (no CPT yet — see build notes).
 * Hardcoded here since there's no reliable way to auto-detect "which
 * pages are services" without a template/taxonomy convention; add a row
 * here when a new service page ships. get_permalink() is looked up by
 * slug so links stay correct even if the page's parent/path changes.
 */
function tp_get_services_nav_items() {
    $slugs = [
        'executive-search'   => 'Executive Search',
        'virtual-assistance' => 'Virtual Assistance',
        'payrolling-services' => 'Payrolling Services',
    ];
    $items = [];
    foreach ($slugs as $slug => $label) {
        $page = get_page_by_path($slug);
        $items[] = [
            'label' => $label,
            'url'   => $page ? get_permalink($page) : home_url('/services/' . $slug . '/'),
        ];
    }
    return $items;
}

/**
 * Insights dropdown mirrors Services but is sourced live from the
 * 'post' type — no per-post nav edits needed as new posts are published.
 */
function tp_get_insights_nav_items($limit = 5) {
    static $cache = [];
    if (isset($cache[$limit])) return $cache[$limit];
    $q = new WP_Query([
        'post_type'      => 'post',
        'post_status'    => 'publish',
        'posts_per_page' => $limit,
        'orderby'        => 'date',
        'order'          => 'DESC',
        'no_found_rows'  => true,
    ]);
    $items = [];
    foreach ($q->posts as $p) {
        $items[] = ['label' => get_the_title($p), 'url' => get_permalink($p), 'thumb' => get_the_post_thumbnail_url($p, 'medium')];
    }
    wp_reset_postdata();
    $cache[$limit] = $items;
    return $items;
}

/**
 * Auto-numbers every <h2> in a post's rendered content with an id +
 * scroll-margin, and builds the matching table-of-contents array from
 * the same pass. Editors never type ids/anchors themselves — this is
 * the only place that logic lives, so it works the same whether the
 * post came from wp-admin's normal editor or (for the two seed posts)
 * hand-written HTML.
 */
function tp_process_article_content($html) {
    $toc = [];
    $i = 0;
    $processed = preg_replace_callback(
        '/<h2([^>]*)>(.*?)<\/h2>/is',
        function ($m) use (&$toc, &$i) {
            $i++;
            $id = 's' . $i;
            $label = trim(wp_strip_all_tags($m[2]));
            $toc[] = ['id' => $id, 'label' => $label];
            $attrs = preg_replace('/\sid="[^"]*"/i', '', $m[1]);
            return '<h2' . $attrs . ' id="' . esc_attr($id) . '" class="tp-article__h2">' . $m[2] . '</h2>';
        },
        $html
    );
    return ['html' => $processed, 'toc' => $toc];
}

/**
 * "Keep reading" cross-links for the bottom of an article: other
 * published posts first (excluding the current one), padded out with
 * the service pages if there aren't 3 other posts yet — always fills
 * the row regardless of how many posts exist so far.
 */
function tp_get_related_posts($exclude_id, $limit = 3) {
    $items = [];
    $q = new WP_Query([
        'post_type'      => 'post',
        'post_status'    => 'publish',
        'posts_per_page' => $limit,
        'post__not_in'   => [$exclude_id],
        'orderby'        => 'date',
        'order'          => 'DESC',
        'no_found_rows'  => true,
    ]);
    foreach ($q->posts as $p) {
        $excerpt = has_excerpt($p) ? get_the_excerpt($p) : '';
        $items[] = ['label' => get_the_title($p), 'url' => get_permalink($p), 'excerpt' => $excerpt, 'tag' => 'Workforce'];
    }
    wp_reset_postdata();

    if (count($items) < $limit) {
        foreach (tp_get_services_nav_items() as $s) {
            if (count($items) >= $limit) break;
            $items[] = ['label' => $s['label'], 'url' => $s['url'], 'excerpt' => '', 'tag' => 'Service'];
        }
    }
    return array_slice($items, 0, $limit);
}
