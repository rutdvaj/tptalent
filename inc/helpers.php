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
    return ['#FF8063', '#E5C5FA', '#FFFFFF'];
}

/** Comma-joined palette for the <wave-field> custom element's "colors" attribute. */
function tp_wave_colors() {
    return implode(',', tp_palette());
}

/**
 * Darker particle palette for <vortex-bg> on a WHITE hero background
 * (service pages) — the default tp_wave_colors() (coral/lavender/white)
 * is tuned for the dark pageheroes and would wash out with too little
 * contrast against white.
 */
function tp_wave_colors_dark() {
    return implode(',', ['#3D1B2E', '#AF8B91', '#FF8063']);
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

    // Mechanically mixing the palette average toward black (like every
    // other token below) desaturates badly for this palette specifically
    // — averaging a warm coral with a cool pastel lavender and then
    // darkening washes out into a muddy mauve-gray instead of a rich
    // tone. Ink/ink-deep use a hand-picked deep plum instead, chosen to
    // complement both accents without favoring either; every other
    // token still reads fine with the mechanical formula since it's
    // only lightly tinted toward black/white, not fully darkened.
    $ink_base   = [0x3D, 0x1B, 0x2E]; // #3D1B2E
    $ink        = $ink_base;
    $ink_deep   = tp_mix_arr($ink_base, $black, 0.35);
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
    // Same value as --ink-deep (#2B1622) — a specific dark-plum shade
    // requested directly, rather than the earlier looser 0.4-mix. Kept
    // as its own token (not literally var(--ink-deep) in the CSS) in
    // case the two need to diverge again later.
    $vars['--map-bg'] = tp_to_hex($ink_deep);

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
            'industries_label' => 'Industries',
            'solutions_label' => 'Solutions',
            'insights_label' => 'Insights', 'insights_url' => '#insights',
            'about_label' => 'About',
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
        // "Numbers" section — dark band + performance bars (variant 1b/2b
        // from the design handoff), placed directly after the hero.
        'tp_numbers' => [
            'eyebrow' => 'The record',
            'heading' => "Numbers we're held to, not numbers we hide behind",
            'stats' => [
                ['value' => 10, 'prefix' => '$', 'suffix' => 'M+', 'unit' => '', 'label' => 'Annual client cost savings'],
                ['value' => 3700, 'prefix' => '', 'suffix' => '+', 'unit' => '', 'label' => 'Successful engagements'],
                ['value' => 21, 'prefix' => '', 'suffix' => '', 'unit' => '', 'label' => 'Countries supported'],
                ['value' => 20, 'prefix' => '', 'suffix' => '', 'unit' => 'yrs', 'label' => 'In IT consulting & services'],
            ],
            'perf_label' => 'Performance by percentage',
            'perf' => [
                ['label' => 'Offer to start', 'pct' => 97],
                ['label' => 'Coverage', 'pct' => 96],
                ['label' => 'Interview to offer', 'pct' => 26],
                ['label' => 'Fill ratio', 'pct' => 18],
            ],
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
            'heading' => 'How we built a 275-person engineering capability center in eleven months',
            'sourcing_title' => 'One sourcing engine, every engineering discipline',
            'sourcing_body' => 'One dedicated pipeline covered every engineering discipline — no extra vendors.',
            'roles' => array_map(function ($v) { return ['value' => $v]; }, ['Software Engineers', 'Cloud Engineers', 'Data Engineers', 'DevOps', 'QA Automation', 'Embedded Systems', 'Product Managers', 'Engineering Managers']),
            'fill_rate_value' => '97%', 'fill_rate_label' => 'SLA compliance',
            'months_count' => 11, 'months_value' => '11 months', 'months_label' => 'Zero to full capability center',
            'retention_value' => '94%', 'retention_label' => 'Employee retention',
            'cta_card_text' => 'Ready to build a delivery org this fast? Let’s talk about what it takes.',
            'cta_label' => 'Explore our services', 'cta_url' => '#',
        ],
        'tp_testimonial' => [
            'heading' => 'What our clients say',
            'rotator' => [
                ['name' => 'Fortune 500 company', 'desc' => 'Sports', 'metric' => '+38% fill-rate'],
                ['name' => 'Big 4 company', 'desc' => 'IT & Consulting', 'metric' => '120 hires in 90 days'],
                ['name' => 'Global automation leader', 'desc' => 'IT & Consulting', 'metric' => '−31% attrition'],
                ['name' => 'Global IT services leader', 'desc' => 'IT & Consulting', 'metric' => '4.1x ROI'],
                ['name' => 'Global technology consultancy', 'desc' => 'IT & Consulting', 'metric' => '24h SLA response'],
                ['name' => 'Global digital transformation partner', 'desc' => 'IT & Consulting', 'metric' => '+52% retention'],
                ['name' => 'Global enterprise IT partner', 'desc' => 'IT & Consulting', 'metric' => '2,000+ placements'],
                ['name' => 'Global technology partner', 'desc' => 'IT & Consulting', 'metric' => '8 markets entered'],
            ],
            'quote' => 'We’ve had a really good relationship with Tecnoprism. The quality is high. You’re keen, you understand, you learn, you’re adapting — we are also adapting together, and it’s been a success.',
            'client_name' => 'Senior Technology Executive',
            'client_title' => 'Big 4 company',
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
            'intro' => 'Anchor delivery centers in each region, backed by presence in 25+ markets.',
            // "Anchors + quiet chips" layout (design handoff option 1b/2b):
            // anchor cities stay bold, secondary markets become outlined
            // chips with a "+N more" cap that expands in place on click.
            'regions' => [
                ['label' => 'APAC', 'anchor' => 'India · Singapore · Australia', 'chips' => ['Malaysia', 'Indonesia', 'Philippines', 'Vietnam', 'Thailand', 'Japan', 'South Korea']],
                ['label' => 'MENA & Africa', 'anchor' => 'Dubai · Abu Dhabi · Saudi Arabia', 'chips' => ['Qatar', 'Oman', 'Kuwait', 'Bahrain', 'Egypt', 'South Africa', 'Kenya', 'Nigeria', 'Mauritius']],
                ['label' => 'Americas', 'anchor' => 'USA · Canada · Brazil', 'chips' => ['Colombia', 'Chile', 'Argentina', 'Mexico', 'Peru']],
            ],
            // Fixed at 11 markers — the beam sequence below references these by
            // position, so rows can be edited in place but not added/removed.
            'locations' => [
                ['name' => 'India', 'lat' => '13.0', 'lon' => '77.6', 'hq' => 1],
                ['name' => 'USA', 'lat' => '40.7', 'lon' => '-74.0', 'hq' => 0],
                ['name' => 'Canada', 'lat' => '43.7', 'lon' => '-79.4', 'hq' => 0],
                ['name' => 'Brazil', 'lat' => '-23.55', 'lon' => '-46.6', 'hq' => 0],
                ['name' => 'Colombia', 'lat' => '4.7', 'lon' => '-74.1', 'hq' => 0],
                ['name' => 'Dubai', 'lat' => '25.2', 'lon' => '55.3', 'hq' => 0],
                ['name' => 'Singapore', 'lat' => '1.35', 'lon' => '103.8', 'hq' => 0],
                ['name' => 'Japan', 'lat' => '35.68', 'lon' => '139.65', 'hq' => 0],
                ['name' => 'South Africa', 'lat' => '-26.2', 'lon' => '28.05', 'hq' => 0],
                ['name' => 'Australia', 'lat' => '-33.87', 'lon' => '151.21', 'hq' => 0],
                ['name' => 'Norway', 'lat' => '59.91', 'lon' => '10.75', 'hq' => 0],
            ],
        ],
        // "Proof, in four figures" — split narrative + hiring-funnel panel.
        // Variant 1e/2e from the design handoff (Firm metrics presentation
        // variations). Sits between "Our services" and the case-study bento.
        'tp_proof' => [
            'eyebrow' => 'Proof, in four figures',
            'heading' => 'We’d rather show the maths than the adjectives',
            'intro' => 'Every engagement is tracked from first submission to first day. These are the figures our clients hold us to across 21 countries.',
            'cta_label' => 'See how we measure', 'cta_url' => '#',
            'funnel_label' => 'The hiring funnel, honestly',
            'stats' => [
                ['value' => '10', 'prefix' => '$', 'suffix' => 'M+', 'display' => '$10M+', 'label' => 'Annual client cost savings'],
                ['value' => '3700', 'prefix' => '', 'suffix' => '+', 'display' => '3,700+', 'label' => 'Successful engagements'],
                ['value' => '21', 'prefix' => '', 'suffix' => '', 'display' => '21', 'label' => 'Countries supported'],
                ['value' => '250', 'prefix' => '', 'suffix' => '+', 'display' => '250+', 'label' => 'Professionals on team'],
            ],
            'perf' => [
                ['label' => 'Offer to start', 'pct' => '97'],
                ['label' => 'Coverage', 'pct' => '96'],
                ['label' => 'Interview to offer', 'pct' => '26'],
                ['label' => 'Fill ratio', 'pct' => '18'],
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

/**
 * Service pages (page-service.php) are ACF-backed per page (see
 * group_tp_service_page in inc/acf-fields.php) — falls back to the
 * plain-array tp_service_content($slug) for any page whose ACF fields
 * haven't been filled in yet.
 */
function tp_get_service_page_content($post_id, $slug) {
    if (function_exists('get_field') && get_field('headline', $post_id)) {
        $problems = [];
        for ($i = 1; $i <= 6; $i++) {
            $g = get_field("problem_{$i}", $post_id);
            if (is_array($g) && !empty($g['heading'])) {
                $problems[] = ['heading' => $g['heading'], 'text' => $g['text']];
            }
        }
        $steps = [];
        for ($i = 1; $i <= 4; $i++) {
            $g = get_field("step_{$i}", $post_id);
            if (is_array($g) && !empty($g['title'])) {
                $steps[] = ['num' => sprintf('%02d', count($steps) + 1), 'title' => $g['title'], 'body' => $g['body']];
            }
        }
        return [
            'headline' => get_field('headline', $post_id),
            'subhead' => get_field('subhead', $post_id),
            'prob_heading' => get_field('prob_heading', $post_id),
            'problems' => $problems,
            'steps' => $steps,
            'cta_heading' => get_field('cta_heading', $post_id),
            'cta_subhead' => get_field('cta_subhead', $post_id),
        ];
    }
    return tp_service_content($slug);
}

/**
 * Same ACF-first/array-fallback pattern as tp_get_service_page_content(),
 * for the Industries page template (group_tp_industry_page).
 */
function tp_get_industry_page_content($post_id, $slug) {
    if (function_exists('get_field') && get_field('headline', $post_id)) {
        $problems = [];
        for ($i = 1; $i <= 5; $i++) {
            $g = get_field("problem_{$i}", $post_id);
            if (is_array($g) && !empty($g['heading'])) {
                $problems[] = ['heading' => $g['heading'], 'text' => $g['text'], 'severity' => $g['severity']];
            }
        }
        $solutions = [];
        for ($i = 1; $i <= 3; $i++) {
            $g = get_field("solution_{$i}", $post_id);
            if (is_array($g) && !empty($g['title'])) {
                $solutions[] = ['title' => $g['title'], 'body' => $g['body'], 'image' => $g['image']];
            }
        }
        $faqs = [];
        for ($i = 1; $i <= 6; $i++) {
            $g = get_field("faq_{$i}", $post_id);
            if (is_array($g) && !empty($g['q'])) {
                $faqs[] = ['q' => $g['q'], 'a' => $g['a']];
            }
        }
        return [
            'industry_name' => get_field('industry_name', $post_id),
            'headline' => get_field('headline', $post_id),
            'subhead' => get_field('subhead', $post_id),
            'prob_heading' => get_field('prob_heading', $post_id),
            'prob_intro' => get_field('prob_intro', $post_id),
            'problems' => $problems,
            'sol_heading' => get_field('sol_heading', $post_id),
            'sol_intro' => get_field('sol_intro', $post_id),
            'solutions' => $solutions,
            'testimonial_quote' => get_field('testimonial_quote', $post_id),
            'testimonial_name' => get_field('testimonial_name', $post_id),
            'testimonial_title' => get_field('testimonial_title', $post_id),
            'faq_heading' => get_field('faq_heading', $post_id),
            'faq_intro' => get_field('faq_intro', $post_id),
            'faqs' => $faqs,
            'cta_subhead' => get_field('cta_subhead', $post_id),
        ];
    }
    return tp_industry_content($slug);
}

/**
 * Same ACF-first/array-fallback pattern as tp_get_service_page_content(),
 * for the Solutions page template (group_tp_solution_page).
 */
function tp_get_solution_page_content($post_id, $slug) {
    if (function_exists('get_field') && get_field('headline', $post_id)) {
        $steps = [];
        for ($i = 1; $i <= 4; $i++) {
            $g = get_field("step_{$i}", $post_id);
            if (is_array($g) && !empty($g['title'])) {
                $steps[] = ['num' => sprintf('%02d', count($steps) + 1), 'title' => $g['title'], 'body' => $g['body']];
            }
        }
        return [
            'solution_name' => get_field('solution_name', $post_id),
            'headline' => get_field('headline', $post_id),
            'subhead' => get_field('subhead', $post_id),
            'steps_intro' => get_field('steps_intro', $post_id),
            'steps' => $steps,
        ];
    }
    return tp_solution_content($slug);
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
/**
 * Sourced live from every Page using the "Service Page" template,
 * ordered by the page's own menu_order — new service pages just need
 * to be created with that template to appear here, no code edit.
 */
function tp_get_services_nav_items() {
    static $cache = null;
    if ($cache !== null) return $cache;
    $q = new WP_Query([
        'post_type'      => 'page',
        'post_status'    => 'publish',
        'posts_per_page' => -1,
        'meta_key'       => '_wp_page_template',
        'meta_value'     => 'page-service.php',
        'orderby'        => 'menu_order',
        'order'          => 'ASC',
        'no_found_rows'  => true,
    ]);
    $items = [];
    foreach ($q->posts as $p) {
        $items[] = ['label' => get_the_title($p), 'url' => get_permalink($p)];
    }
    wp_reset_postdata();
    $cache = $items;
    return $items;
}

/**
 * Same pattern as tp_get_industries_nav_items(), for pages using the
 * "Solution Page" template — sourced live by page_template + menu_order.
 * Replaces the earlier tp_get_placeholder_solutions_nav_items() now that
 * real solution pages exist; only lists pages that actually exist, so
 * the dropdown grows on its own as Executive Search / RPO are added.
 */
function tp_get_solutions_nav_items() {
    static $cache = null;
    if ($cache !== null) return $cache;
    $q = new WP_Query([
        'post_type'      => 'page',
        'post_status'    => 'publish',
        'posts_per_page' => -1,
        'meta_key'       => '_wp_page_template',
        'meta_value'     => 'page-solution.php',
        'orderby'        => 'menu_order',
        'order'          => 'ASC',
        'no_found_rows'  => true,
    ]);
    $items = [];
    foreach ($q->posts as $p) {
        $items[] = ['label' => get_the_title($p), 'url' => get_permalink($p)];
    }
    wp_reset_postdata();
    $cache = $items;
    return $items;
}

/**
 * Services grouped into 3 labeled columns for the mega-dropdown (navbar
 * redesign, "3c/4c" handoff) — same live page lookup as
 * tp_get_services_nav_items(), just organized by category instead of a
 * flat list. Category membership is by slug since there's no ACF
 * taxonomy field for this; the design's own grouping only named 12 of
 * our 15 service pages, so the original 3 (Executive Search, Virtual
 * Assistance, Payrolling Services) were folded into "Talent &
 * Transformation" as the closest fit rather than dropped from the nav.
 */
function tp_get_services_nav_categories() {
    static $cache = null;
    if ($cache !== null) return $cache;

    $groups = [
        'AI & Automation' => ['robotics-automation', 'generative-agentic-ai', 'machine-learning', 'data-analytics'],
        'Engineering & Development' => ['pega', 'engineering-services', 'embedded-technologies', 'software-development-outsourcing', 'software-testing'],
        'Talent & Transformation' => ['talent-acquisition', 'recruiter-on-premise', 'digital-transformation', 'executive-search', 'virtual-assistance', 'payrolling-services'],
    ];

    // Own query keyed by slug — tp_get_services_nav_items() only returns
    // label/url, and changing its shape risks its other callers.
    $q = new WP_Query([
        'post_type'      => 'page',
        'post_status'    => 'publish',
        'posts_per_page' => -1,
        'meta_key'       => '_wp_page_template',
        'meta_value'     => 'page-service.php',
        'no_found_rows'  => true,
    ]);
    $by_slug = [];
    foreach ($q->posts as $p) {
        $by_slug[$p->post_name] = ['label' => get_the_title($p), 'url' => get_permalink($p)];
    }
    wp_reset_postdata();

    $categories = [];
    foreach ($groups as $label => $slugs) {
        $items = [];
        foreach ($slugs as $slug) {
            if (isset($by_slug[$slug])) $items[] = $by_slug[$slug];
        }
        if ($items) $categories[] = ['label' => $label, 'items' => $items];
    }
    $cache = $categories;
    return $categories;
}

/**
 * Same pattern as tp_get_services_nav_items(), for pages using the
 * "Industry Page" template — sourced live by page_template + menu_order,
 * so new industry pages just need the right template to appear here.
 */
function tp_get_industries_nav_items() {
    static $cache = null;
    if ($cache !== null) return $cache;
    $q = new WP_Query([
        'post_type'      => 'page',
        'post_status'    => 'publish',
        'posts_per_page' => -1,
        'meta_key'       => '_wp_page_template',
        'meta_value'     => 'page-industry.php',
        'orderby'        => 'menu_order',
        'order'          => 'ASC',
        'no_found_rows'  => true,
    ]);
    $items = [];
    foreach ($q->posts as $p) {
        $items[] = ['label' => get_the_title($p), 'url' => get_permalink($p)];
    }
    wp_reset_postdata();
    $cache = $items;
    return $items;
}

/**
 * Placeholder-only nav items (navbar redesign, "About" dropdown) — no
 * real pages yet, every link is '#' until the client builds them. Swap
 * for a live page_template query, same pattern as
 * tp_get_services_nav_items(), once those pages exist.
 */
function tp_get_placeholder_about_nav_items() {
    return [
        ['label' => 'About Tecnoprism', 'url' => '#'],
        ['label' => 'Privacy Policy', 'url' => '#'],
        ['label' => 'Terms & Conditions', 'url' => '#'],
    ];
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
        $title = get_the_title($p);
        // nav_label is a short version for the Insights nav dropdown
        // specifically (see tp_nav_label post meta, set by
        // tp_bootstrap_blog_posts_v2()) — the "Related Insights"
        // cross-link cards elsewhere keep using the full 'label'/title.
        $navLabel = get_post_meta($p->ID, 'tp_nav_label', true);
        $items[] = [
            'label' => $title,
            'nav_label' => $navLabel ?: $title,
            'url' => get_permalink($p),
            'thumb' => get_the_post_thumbnail_url($p, 'medium'),
        ];
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
