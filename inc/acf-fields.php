<?php
/**
 * ACF field groups for the homepage — scoped to exactly two sections per
 * project decision: the bento grid (Case Study) and the client kinetic
 * headline (Testimonial rotator). Everything else on the homepage still
 * runs on the original meta-box system in inc/meta-boxes.php.
 *
 * Registered in code (not built by hand in the ACF admin UI) so field
 * definitions live in git like everything else in this theme.
 */

if (!defined('ABSPATH')) exit;

add_action('acf/init', function () {
    if (!function_exists('acf_add_local_field_group')) return;

    acf_add_local_field_group([
        'key'    => 'group_tp_case_study',
        'title'  => 'Bento Grid (Case Study section)',
        'fields' => [
            ['key' => 'field_tp_cs_eyebrow', 'label' => 'Eyebrow', 'name' => 'eyebrow', 'type' => 'text'],
            ['key' => 'field_tp_cs_heading', 'label' => 'Heading', 'name' => 'heading', 'type' => 'text'],
            ['key' => 'field_tp_cs_sourcing_title', 'label' => 'Sourcing card title', 'name' => 'sourcing_title', 'type' => 'text'],
            ['key' => 'field_tp_cs_sourcing_body', 'label' => 'Sourcing card body', 'name' => 'sourcing_body', 'type' => 'textarea', 'rows' => 3],
            ['key' => 'field_tp_cs_fill_rate_value', 'label' => 'Fill rate — value', 'name' => 'fill_rate_value', 'type' => 'text'],
            ['key' => 'field_tp_cs_fill_rate_label', 'label' => 'Fill rate — caption', 'name' => 'fill_rate_label', 'type' => 'text'],
            ['key' => 'field_tp_cs_months_count', 'label' => 'Months — count (drives the timeline dots, keep as a plain number)', 'name' => 'months_count', 'type' => 'number'],
            ['key' => 'field_tp_cs_months_value', 'label' => 'Months — display value', 'name' => 'months_value', 'type' => 'text'],
            ['key' => 'field_tp_cs_months_label', 'label' => 'Months — caption', 'name' => 'months_label', 'type' => 'text'],
            ['key' => 'field_tp_cs_retention_value', 'label' => 'Retention — value', 'name' => 'retention_value', 'type' => 'text'],
            ['key' => 'field_tp_cs_retention_label', 'label' => 'Retention — caption', 'name' => 'retention_label', 'type' => 'text'],
            ['key' => 'field_tp_cs_cta_card_text', 'label' => 'CTA card text', 'name' => 'cta_card_text', 'type' => 'textarea', 'rows' => 3],
            ['key' => 'field_tp_cs_cta_label', 'label' => 'CTA button label', 'name' => 'cta_label', 'type' => 'text'],
            ['key' => 'field_tp_cs_cta_url', 'label' => 'CTA button URL', 'name' => 'cta_url', 'type' => 'url'],
        ],
        'location' => [[['param' => 'page_type', 'operator' => '==', 'value' => 'front_page']]],
        'menu_order' => 0,
    ]);

    $rotator_fields = [];
    for ($i = 1; $i <= 8; $i++) {
        $rotator_fields[] = [
            'key'        => "field_tp_rotator_{$i}",
            'label'      => "Client {$i}",
            'name'       => "rotator_{$i}",
            'type'       => 'group',
            'sub_fields' => [
                ['key' => "field_tp_rotator_{$i}_name", 'label' => 'Name', 'name' => 'name', 'type' => 'text'],
                ['key' => "field_tp_rotator_{$i}_desc", 'label' => 'Description / industry', 'name' => 'desc', 'type' => 'text'],
                ['key' => "field_tp_rotator_{$i}_metric", 'label' => 'Metric', 'name' => 'metric', 'type' => 'text'],
            ],
        ];
    }
    acf_add_local_field_group([
        'key'      => 'group_tp_testimonial_rotator',
        'title'    => 'Client Kinetic Headline (Testimonial rotator)',
        'fields'   => $rotator_fields,
        'location' => [[['param' => 'page_type', 'operator' => '==', 'value' => 'front_page']]],
        'menu_order' => 1,
    ]);
});

/**
 * One-time: pre-fill the two ACF-enabled sections with the current live
 * content (from tp_default()) so the admin form opens with real copy,
 * not blank fields — editing then means changing existing text, not
 * starting from nothing.
 */
function tp_bootstrap_acf_content() {
    if (get_option('tp_acf_content_bootstrapped')) return;
    if (!function_exists('update_field')) return;
    $id = tp_front_page_id();
    if (!$id) return;

    $cs = tp_default('tp_case_study');
    foreach (['eyebrow', 'heading', 'sourcing_title', 'sourcing_body', 'fill_rate_value', 'fill_rate_label', 'months_count', 'months_value', 'months_label', 'retention_value', 'retention_label', 'cta_card_text', 'cta_label', 'cta_url'] as $k) {
        update_field($k, $cs[$k], $id);
    }

    $testimonial = tp_default('tp_testimonial');
    foreach ($testimonial['rotator'] as $i => $client) {
        update_field('rotator_' . ($i + 1), $client, $id);
    }

    update_option('tp_acf_content_bootstrapped', 1);
}
add_action('wp_loaded', 'tp_bootstrap_acf_content');

/**
 * One-time: replace the client kinetic headline rotator's "name" values
 * with anonymized company-type labels (e.g. "A Big 4 company") instead of
 * direct client names — per client request not to name specific companies
 * in that rotator. Full replace of the saved ACF rotator rows.
 */
function tp_bootstrap_testimonial_rotator_v2() {
    if (get_option('tp_testimonial_rotator_v2_bootstrapped')) return;
    if (!function_exists('update_field')) return;
    $id = tp_front_page_id();
    if (!$id) return;

    $rotator = tp_default('tp_testimonial')['rotator'];
    foreach ($rotator as $i => $client) {
        update_field('rotator_' . ($i + 1), $client, $id);
    }

    update_option('tp_testimonial_rotator_v2_bootstrapped', 1);
}
add_action('wp_loaded', 'tp_bootstrap_testimonial_rotator_v2');

/**
 * One-time: drop the leading "A "/"An " article from the rotator's
 * company-type labels (e.g. "A Big 4 company" -> "Big 4 company") — the
 * client asked to just state the type of company. Same tp_default()
 * source as v2, just re-run against the now-article-free defaults.
 */
function tp_bootstrap_testimonial_rotator_v3() {
    if (get_option('tp_testimonial_rotator_v3_bootstrapped')) return;
    if (!function_exists('update_field')) return;
    $id = tp_front_page_id();
    if (!$id) return;

    $rotator = tp_default('tp_testimonial')['rotator'];
    foreach ($rotator as $i => $client) {
        update_field('rotator_' . ($i + 1), $client, $id);
    }

    update_option('tp_testimonial_rotator_v3_bootstrapped', 1);
}
add_action('wp_loaded', 'tp_bootstrap_testimonial_rotator_v3');

/**
 * One-time: replace the placeholder Case Study bento content with a real
 * case study from the client's provided case-studies document (Case
 * Study 7: "Global Engineering Capability Center" — Manufacturing / GCC
 * Staffing, 11-month build, 97% SLA compliance, 94% employee retention).
 * Full replace of the saved ACF case-study fields; the CTA label/url are
 * left untouched since they already point at the real uploaded PDF.
 */
function tp_bootstrap_case_study_v2() {
    if (get_option('tp_case_study_v2_bootstrapped')) return;
    if (!function_exists('update_field')) return;
    $id = tp_front_page_id();
    if (!$id) return;

    $cs = tp_default('tp_case_study');
    foreach (['eyebrow', 'heading', 'sourcing_title', 'sourcing_body', 'fill_rate_value', 'fill_rate_label', 'months_count', 'months_value', 'months_label', 'retention_value', 'retention_label', 'cta_card_text'] as $k) {
        update_field($k, $cs[$k], $id);
    }

    update_option('tp_case_study_v2_bootstrapped', 1);
}
add_action('wp_loaded', 'tp_bootstrap_case_study_v2');

/**
 * One-time: shorten the sourcing card body to a single crisp sentence
 * (was reading too long/dense), and swap the CTA card's PDF download for
 * a "see our services" link — the download option was removed for now.
 */
function tp_bootstrap_case_study_v3() {
    if (get_option('tp_case_study_v3_bootstrapped')) return;
    if (!function_exists('update_field')) return;
    $id = tp_front_page_id();
    if (!$id) return;

    $cs = tp_default('tp_case_study');
    foreach (['sourcing_body', 'cta_card_text', 'cta_label'] as $k) {
        update_field($k, $cs[$k], $id);
    }

    update_option('tp_case_study_v3_bootstrapped', 1);
}
add_action('wp_loaded', 'tp_bootstrap_case_study_v3');

/**
 * One-time: register the real report PDF (copied into uploads/2026/07/)
 * as a proper Media Library attachment, then point the Bento Grid's CTA
 * card at it with a real download instead of the placeholder "#" link.
 * Safe to leave running every request — the option flag makes the actual
 * work run exactly once.
 */
function tp_bootstrap_report_pdf() {
    if (get_option('tp_report_pdf_bootstrapped')) return;
    if (!function_exists('update_field')) return;
    $id = tp_front_page_id();
    if (!$id) return;

    $file = WP_CONTENT_DIR . '/uploads/2026/07/tecnoprism-global-consulting-services.pdf';
    if (!file_exists($file)) return;

    $file_url = WP_CONTENT_URL . '/uploads/2026/07/tecnoprism-global-consulting-services.pdf';
    $existing = get_page_by_title('Tecnoprism Global Consulting Services', OBJECT, 'attachment');
    $attachment_id = $existing ? $existing->ID : null;

    if (!$attachment_id) {
        require_once ABSPATH . 'wp-admin/includes/image.php';
        $attachment_id = wp_insert_attachment([
            'post_title'     => 'Tecnoprism Global Consulting Services',
            'post_mime_type' => 'application/pdf',
            'post_status'    => 'inherit',
        ], $file);
        if (!is_wp_error($attachment_id) && $attachment_id) {
            wp_update_attachment_metadata($attachment_id, wp_generate_attachment_metadata($attachment_id, $file));
        }
    }

    update_field('cta_label', 'Download report', $id);
    update_field('cta_url', $file_url, $id);

    update_option('tp_report_pdf_bootstrapped', 1);
}
add_action('wp_loaded', 'tp_bootstrap_report_pdf');

/**
 * One-time: replace the "Client logo ticker" (tp_hero.ticker_logos) with
 * a new curated set of real client logos (copied into uploads/2026/07/),
 * registering each as a proper Media Library attachment first. This is
 * a full replace of the saved repeater value, not an append — whatever
 * was there before (default fallbacks or earlier manual edits) is
 * discarded in favor of this list. Guarded so it only ever runs once;
 * afterward the logos are just normal repeater rows editable in
 * wp-admin like any other.
 */
function tp_bootstrap_ticker_logos_v2() {
    if (get_option('tp_ticker_logos_v2_bootstrapped')) return;
    $id = tp_front_page_id();
    if (!$id) return;

    $files = [
        'automation_anywhere_logo' => 'Automation Anywhere',
        'deloitte_logo'            => 'Deloitte',
        'hcl_technologies_logo'    => 'HCL Technologies',
        'infosys_logo'             => 'Infosys',
        'tech_mahindra_logo'       => 'Tech Mahindra',
        'vanderlande_logo'         => 'Vanderlande',
        'wipro_logo'               => 'Wipro',
        'zensar_logo'              => 'Zensar',
    ];

    require_once ABSPATH . 'wp-admin/includes/image.php';
    $new_logos = [];
    foreach ($files as $slug => $title) {
        $file = WP_CONTENT_DIR . "/uploads/2026/07/{$slug}.png";
        if (!file_exists($file)) continue;

        $existing = get_page_by_title($title, OBJECT, 'attachment');
        $attachment_id = $existing ? $existing->ID : null;
        if (!$attachment_id) {
            $attachment_id = wp_insert_attachment([
                'post_title'     => $title,
                'post_mime_type' => 'image/png',
                'post_status'    => 'inherit',
            ], $file);
            if (!is_wp_error($attachment_id) && $attachment_id) {
                wp_update_attachment_metadata($attachment_id, wp_generate_attachment_metadata($attachment_id, $file));
            }
        }
        if ($attachment_id && !is_wp_error($attachment_id)) {
            $new_logos[] = ['id' => $attachment_id, 'fallback' => ''];
        }
    }

    if ($new_logos) {
        $saved = get_post_meta($id, 'tp_hero', true);
        $saved = is_array($saved) ? $saved : tp_default('tp_hero');
        $saved['ticker_logos'] = $new_logos;
        update_post_meta($id, 'tp_hero', $saved);
    }

    update_option('tp_ticker_logos_v2_bootstrapped', 1);
}
add_action('wp_loaded', 'tp_bootstrap_ticker_logos_v2');

/**
 * One-time: the Home page already has explicit saved meta for
 * tp_global (baked in the moment the page was ever saved in wp-admin,
 * since the meta-box form posts back every section's current value on
 * Update — not just the ones actually edited). That saved copy was
 * silently overriding the new regions/countries added to tp_default()
 * in code. This directly replaces the saved 'regions' array with the
 * expanded one, leaving every other tp_global field (locations, map
 * copy, etc.) exactly as already saved/untouched.
 */
function tp_bootstrap_global_regions_v2() {
    if (get_option('tp_global_regions_v2_bootstrapped')) return;
    $id = tp_front_page_id();
    if (!$id) return;

    $saved = get_post_meta($id, 'tp_global', true);
    $saved = is_array($saved) ? $saved : tp_default('tp_global');
    $saved['regions'] = tp_default('tp_global')['regions'];
    update_post_meta($id, 'tp_global', $saved);

    update_option('tp_global_regions_v2_bootstrapped', 1);
}
add_action('wp_loaded', 'tp_bootstrap_global_regions_v2');

/**
 * One-time: revert the "Where we operate" regions back to the original
 * 3-region / 10-country set — the v2 expansion (4 regions, 31 countries)
 * was rolled back per client request. Same shadowing issue as v2: the
 * Home page has explicit saved tp_global meta, so the code default
 * change alone wouldn't show up without overwriting it directly here.
 */
function tp_bootstrap_global_regions_v3_revert() {
    if (get_option('tp_global_regions_v3_reverted')) return;
    $id = tp_front_page_id();
    if (!$id) return;

    $saved = get_post_meta($id, 'tp_global', true);
    $saved = is_array($saved) ? $saved : tp_default('tp_global');
    $saved['regions'] = tp_default('tp_global')['regions'];
    update_post_meta($id, 'tp_global', $saved);

    update_option('tp_global_regions_v3_reverted', 1);
}
add_action('wp_loaded', 'tp_bootstrap_global_regions_v3_revert');

/**
 * One-time: the 8 client logo master files on disk were replaced with
 * auto-trimmed versions (removed a large amount of dead transparent
 * padding baked into the originals — that's why some logos, e.g.
 * Vanderlande/Tech Mahindra, rendered near-invisible even at a decent
 * ticker size: most of their square canvas was empty space). WordPress
 * doesn't auto-regenerate its cached thumbnail sizes just because the
 * original file changed on disk, so this explicitly re-runs
 * wp_generate_attachment_metadata() against the new files for each of
 * the 8 logo attachments, refreshing every registered thumbnail size.
 */
function tp_bootstrap_ticker_logos_v3_retrim() {
    if (get_option('tp_ticker_logos_v3_retrim_bootstrapped')) return;

    $titles = ['Automation Anywhere', 'Deloitte', 'HCL Technologies', 'Infosys', 'Tech Mahindra', 'Vanderlande', 'Wipro', 'Zensar'];
    require_once ABSPATH . 'wp-admin/includes/image.php';

    foreach ($titles as $title) {
        $att = get_page_by_title($title, OBJECT, 'attachment');
        if (!$att) continue;
        $file = get_attached_file($att->ID);
        if (!$file || !file_exists($file)) continue;
        wp_update_attachment_metadata($att->ID, wp_generate_attachment_metadata($att->ID, $file));
    }

    update_option('tp_ticker_logos_v3_retrim_bootstrapped', 1);
}
add_action('wp_loaded', 'tp_bootstrap_ticker_logos_v3_retrim');
