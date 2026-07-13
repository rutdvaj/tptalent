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
