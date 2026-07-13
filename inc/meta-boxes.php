<?php
/**
 * "Homepage Content" meta boxes — one per section, shown only on the page
 * that's set as the site's static front page (Settings -> Reading).
 * Field values are stored as a single array per section under a post-meta
 * key of the same name (e.g. 'tp_hero'), read back via tp_get_section().
 */

if (!defined('ABSPATH')) exit;

/**
 * Field config per section. Mirrors the keys in tp_default() 1:1 —
 * whatever is saved here is what tp_field()/tp_get_section() return.
 */
function tp_meta_boxes_config() {
    return [
        'tp_brand' => [
            'title' => 'Brand / Logo',
            'fields' => [
                ['key' => 'name', 'label' => 'Wordmark', 'type' => 'text'],
                ['key' => 'sub', 'label' => 'Wordmark subline', 'type' => 'text'],
            ],
        ],
        'tp_nav' => [
            'title' => 'Navigation',
            'fields' => [
                ['key' => 'services_label', 'label' => 'Link 1 label', 'type' => 'text'],
                ['key' => 'services_url', 'label' => 'Link 1 URL', 'type' => 'url'],
                ['key' => 'industries_label', 'label' => 'Link 2 label', 'type' => 'text'],
                ['key' => 'industries_url', 'label' => 'Link 2 URL', 'type' => 'url'],
                ['key' => 'insights_label', 'label' => 'Link 3 label', 'type' => 'text'],
                ['key' => 'insights_url', 'label' => 'Link 3 URL', 'type' => 'url'],
                ['key' => 'about_label', 'label' => 'Link 4 label (About)', 'type' => 'text'],
                ['key' => 'about_url', 'label' => 'Link 4 URL (About)', 'type' => 'url'],
                ['key' => 'cta_label', 'label' => 'Nav button label', 'type' => 'text'],
                ['key' => 'cta_url', 'label' => 'Nav button URL', 'type' => 'url'],
            ],
        ],
        'tp_hero' => [
            'title' => 'Hero',
            'fields' => [
                ['key' => 'headline', 'label' => 'Headline', 'type' => 'textarea'],
                ['key' => 'subhead', 'label' => 'Subheading', 'type' => 'textarea'],
                ['key' => 'browse_label', 'label' => '"Browse talent" button label', 'type' => 'text'],
                ['key' => 'browse_url', 'label' => '"Browse talent" button URL', 'type' => 'url'],
                ['key' => 'hire_label', 'label' => '"Hire talent" button label', 'type' => 'text'],
                ['key' => 'hire_url', 'label' => '"Hire talent" button URL', 'type' => 'url'],
                ['key' => 'metric1_value', 'label' => 'Metric 1 value', 'type' => 'text'],
                ['key' => 'metric1_label', 'label' => 'Metric 1 label', 'type' => 'text'],
                ['key' => 'metric2_value', 'label' => 'Metric 2 value', 'type' => 'text'],
                ['key' => 'metric2_label', 'label' => 'Metric 2 label', 'type' => 'text'],
                [
                    'key' => 'ticker_logos', 'label' => 'Client logo ticker', 'type' => 'repeater',
                    'fields' => [
                        ['key' => 'id', 'label' => 'Logo', 'type' => 'image'],
                        ['key' => 'fallback', 'label' => '', 'type' => 'hidden'],
                    ],
                ],
            ],
        ],
        'tp_services' => [
            'title' => 'Our Services',
            'fields' => [
                ['key' => 'eyebrow', 'label' => 'Eyebrow label', 'type' => 'text'],
                ['key' => 'heading', 'label' => 'Heading', 'type' => 'text'],
                ['key' => 'intro', 'label' => 'Intro text', 'type' => 'textarea'],
                [
                    'key' => 'items', 'label' => 'Service cards (first 2 sit beside the heading, the rest fill the row below)', 'type' => 'repeater',
                    'fields' => [
                        ['key' => 'num', 'label' => 'Number', 'type' => 'text'],
                        ['key' => 'glyph', 'label' => 'Glyph (1 letter)', 'type' => 'text'],
                        ['key' => 'title', 'label' => 'Title', 'type' => 'text'],
                        ['key' => 'body', 'label' => 'Body', 'type' => 'textarea'],
                        ['key' => 'url', 'label' => '"Read more" URL', 'type' => 'url'],
                    ],
                ],
            ],
        ],
        'tp_case_study' => [
            'title' => 'Featured Case Study (bento grid)',
            'fields' => [
                ['key' => 'eyebrow', 'label' => 'Eyebrow label', 'type' => 'text'],
                ['key' => 'heading', 'label' => 'Heading', 'type' => 'textarea'],
                ['key' => 'sourcing_title', 'label' => 'Sourcing card title', 'type' => 'text'],
                ['key' => 'sourcing_body', 'label' => 'Sourcing card body', 'type' => 'textarea'],
                [
                    'key' => 'roles', 'label' => 'Sourcing marquee roles', 'type' => 'repeater',
                    'fields' => [['key' => 'value', 'label' => 'Role', 'type' => 'text']],
                ],
                ['key' => 'fill_rate_value', 'label' => 'Fill-rate value', 'type' => 'text'],
                ['key' => 'fill_rate_label', 'label' => 'Fill-rate caption', 'type' => 'text'],
                ['key' => 'months_count', 'label' => 'Timeline dots (count)', 'type' => 'number'],
                ['key' => 'months_value', 'label' => 'Timeline value', 'type' => 'text'],
                ['key' => 'months_label', 'label' => 'Timeline caption', 'type' => 'text'],
                ['key' => 'retention_value', 'label' => 'Retention value', 'type' => 'text'],
                ['key' => 'retention_label', 'label' => 'Retention caption', 'type' => 'text'],
                ['key' => 'cta_card_text', 'label' => 'CTA card text', 'type' => 'textarea'],
                ['key' => 'cta_label', 'label' => 'CTA button label', 'type' => 'text'],
                ['key' => 'cta_url', 'label' => 'CTA button URL', 'type' => 'url'],
            ],
        ],
        'tp_testimonial' => [
            'title' => 'Testimonial',
            'fields' => [
                ['key' => 'heading', 'label' => 'Section heading', 'type' => 'text'],
                [
                    'key' => 'rotator', 'label' => 'Rotating client wins ("Chosen by")', 'type' => 'repeater',
                    'fields' => [
                        ['key' => 'name', 'label' => 'Client name', 'type' => 'text'],
                        ['key' => 'metric', 'label' => 'Win metric', 'type' => 'text'],
                        ['key' => 'desc', 'label' => 'Industry', 'type' => 'text'],
                    ],
                ],
                ['key' => 'quote', 'label' => 'Quote', 'type' => 'textarea'],
                ['key' => 'client_name', 'label' => 'Quote attribution — name', 'type' => 'text'],
                ['key' => 'client_title', 'label' => 'Quote attribution — title', 'type' => 'text'],
            ],
        ],
        'tp_capabilities' => [
            'title' => 'Capabilities',
            'fields' => [
                ['key' => 'eyebrow', 'label' => 'Eyebrow label', 'type' => 'text'],
                ['key' => 'heading', 'label' => 'Heading', 'type' => 'text'],
                ['key' => 'intro', 'label' => 'Intro text', 'type' => 'textarea'],
                [
                    'key' => 'faqs', 'label' => 'Accordion items', 'type' => 'repeater',
                    'fields' => [
                        ['key' => 'title', 'label' => 'Title', 'type' => 'text'],
                        ['key' => 'body', 'label' => 'Body', 'type' => 'textarea'],
                    ],
                ],
            ],
        ],
        'tp_global' => [
            'title' => 'Global Delivery (map)',
            'fields' => [
                ['key' => 'eyebrow', 'label' => 'Eyebrow label', 'type' => 'text'],
                ['key' => 'heading', 'label' => 'Heading (HTML &lt;br&gt; allowed)', 'type' => 'textarea'],
                ['key' => 'intro', 'label' => 'Intro text', 'type' => 'textarea'],
                [
                    'key' => 'regions', 'label' => 'Region list (right column)', 'type' => 'repeater',
                    'fields' => [
                        ['key' => 'label', 'label' => 'Region name', 'type' => 'text'],
                        ['key' => 'value', 'label' => 'Countries', 'type' => 'text'],
                    ],
                ],
                [
                    'key' => 'locations', 'label' => 'Map markers — fixed at 8, edit in place (the beam animation sequence is wired to this order: India, USA, Canada, Brazil, Colombia, Dubai, Kuwait, Singapore)', 'type' => 'repeater', 'fixed' => true,
                    'fields' => [
                        ['key' => 'name', 'label' => 'Name', 'type' => 'text'],
                        ['key' => 'lat', 'label' => 'Latitude', 'type' => 'number'],
                        ['key' => 'lon', 'label' => 'Longitude', 'type' => 'number'],
                        ['key' => 'hq', 'label' => 'HQ marker', 'type' => 'checkbox'],
                    ],
                ],
            ],
        ],
        'tp_insights' => [
            'title' => 'Insights',
            'fields' => [
                ['key' => 'eyebrow', 'label' => 'Eyebrow label', 'type' => 'text'],
                ['key' => 'heading', 'label' => 'Heading', 'type' => 'text'],
                ['key' => 'view_all_label', 'label' => '"All insights" link label', 'type' => 'text'],
                ['key' => 'view_all_url', 'label' => '"All insights" link URL', 'type' => 'url'],
                [
                    'key' => 'articles', 'label' => 'Articles', 'type' => 'repeater',
                    'fields' => [
                        ['key' => 'tag', 'label' => 'Tag', 'type' => 'text'],
                        ['key' => 'read', 'label' => 'Read time', 'type' => 'text'],
                        ['key' => 'title', 'label' => 'Title', 'type' => 'text'],
                        ['key' => 'url', 'label' => 'URL', 'type' => 'url'],
                    ],
                ],
            ],
        ],
        'tp_cta' => [
            'title' => 'CTA Band',
            'fields' => [
                ['key' => 'heading', 'label' => 'Heading', 'type' => 'textarea'],
                ['key' => 'subhead', 'label' => 'Subheading', 'type' => 'textarea'],
                ['key' => 'button_label', 'label' => 'Button label', 'type' => 'text'],
                ['key' => 'button_url', 'label' => 'Button URL', 'type' => 'url'],
            ],
        ],
        'tp_footer' => [
            'title' => 'Footer',
            'fields' => [
                ['key' => 'tagline', 'label' => 'Tagline', 'type' => 'textarea'],
                [
                    'key' => 'company_links', 'label' => '"Company" column links', 'type' => 'repeater',
                    'fields' => [['key' => 'label', 'label' => 'Label', 'type' => 'text'], ['key' => 'url', 'label' => 'URL', 'type' => 'url']],
                ],
                [
                    'key' => 'connect_links', 'label' => '"Connect" column links', 'type' => 'repeater',
                    'fields' => [['key' => 'label', 'label' => 'Label', 'type' => 'text'], ['key' => 'url', 'label' => 'URL', 'type' => 'url']],
                ],
                ['key' => 'copyright', 'label' => 'Copyright line', 'type' => 'text'],
            ],
        ],
    ];
}

function tp_add_meta_boxes($post) {
    if (!$post || (int) $post->ID !== tp_front_page_id()) return;
    foreach (tp_meta_boxes_config() as $section => $box) {
        add_meta_box(
            $section,
            'Homepage — ' . $box['title'],
            'tp_render_meta_box',
            'page',
            'normal',
            'default',
            ['section' => $section, 'fields' => $box['fields']]
        );
    }
}
add_action('add_meta_boxes_page', 'tp_add_meta_boxes');

function tp_render_meta_box($post, $box) {
    $section = $box['args']['section'];
    $fields = $box['args']['fields'];
    $data = get_post_meta($post->ID, $section, true);
    if (!is_array($data) || empty($data)) $data = tp_default($section);

    wp_nonce_field('tp_save_meta', 'tp_meta_nonce');
    echo '<div class="tp-fields">';
    foreach ($fields as $field) {
        tp_render_field($section, $field, $data[$field['key']] ?? '');
    }
    echo '</div>';
}

function tp_field_label($label, $for = '') {
    if ($label === '') return;
    echo '<label' . ($for ? ' for="' . esc_attr($for) . '"' : '') . '>' . esc_html($label) . '</label>';
}

/** Renders one input/textarea/image/checkbox control for a fully-qualified $name. */
function tp_field_control($name, $type, $value) {
    $id = 'f-' . sanitize_html_class($name);
    switch ($type) {
        case 'textarea':
            echo '<textarea id="' . esc_attr($id) . '" name="' . esc_attr($name) . '" rows="3" class="widefat">' . esc_textarea($value) . '</textarea>';
            break;
        case 'url':
            echo '<input type="text" id="' . esc_attr($id) . '" name="' . esc_attr($name) . '" value="' . esc_attr($value) . '" class="widefat" placeholder="#">';
            break;
        case 'number':
            echo '<input type="text" inputmode="decimal" id="' . esc_attr($id) . '" name="' . esc_attr($name) . '" value="' . esc_attr($value) . '" class="widefat">';
            break;
        case 'checkbox':
            echo '<input type="checkbox" id="' . esc_attr($id) . '" name="' . esc_attr($name) . '" value="1"' . checked(!empty($value), true, false) . '>';
            break;
        case 'hidden':
            echo '<input type="hidden" name="' . esc_attr($name) . '" value="' . esc_attr($value) . '" class="tp-hidden-fallback">';
            break;
        case 'image':
            $img_id = (int) $value;
            $preview = $img_id ? wp_get_attachment_image_url($img_id, 'thumbnail') : '';
            echo '<div class="tp-image-field">';
            echo '<img class="tp-image-preview" src="' . esc_url($preview) . '" style="' . ($preview ? '' : 'display:none;') . '">';
            echo '<input type="hidden" class="tp-image-id" name="' . esc_attr($name) . '" value="' . esc_attr($img_id) . '">';
            echo '<button type="button" class="button tp-image-select">' . ($preview ? 'Change image' : 'Select image') . '</button> ';
            echo '<button type="button" class="button tp-image-clear"' . ($preview ? '' : ' style="display:none;"') . '>Remove</button>';
            echo '</div>';
            break;
        case 'text':
        default:
            echo '<input type="text" id="' . esc_attr($id) . '" name="' . esc_attr($name) . '" value="' . esc_attr($value) . '" class="widefat">';
    }
}

function tp_render_field($section, $field, $value) {
    if ($field['type'] === 'repeater') {
        tp_render_repeater($section, $field, is_array($value) ? $value : []);
        return;
    }
    $name = $section . '[' . $field['key'] . ']';
    echo '<p class="tp-field tp-field--' . esc_attr($field['type']) . '">';
    tp_field_label($field['label'], 'f-' . sanitize_html_class($name));
    tp_field_control($name, $field['type'], $value);
    echo '</p>';
}

function tp_render_repeater($section, $field, $rows) {
    $repKey = $field['key'];
    $fixed = !empty($field['fixed']);
    echo '<div class="tp-repeater" data-section="' . esc_attr($section) . '" data-rep="' . esc_attr($repKey) . '"' . ($fixed ? ' data-fixed="1"' : '') . '>';
    if ($field['label'] !== '') echo '<h4 class="tp-repeater__title">' . esc_html($field['label']) . '</h4>';
    echo '<div class="tp-repeater__rows">';
    foreach ($rows as $i => $row) {
        tp_render_repeater_row($section, $repKey, $i, $field['fields'], $row, $fixed);
    }
    echo '</div>';
    if (!$fixed) {
        echo '<template class="tp-repeater__template">';
        tp_render_repeater_row($section, $repKey, '__INDEX__', $field['fields'], [], false);
        echo '</template>';
        echo '<p><button type="button" class="button tp-repeater__add">+ Add row</button></p>';
    }
    echo '</div>';
}

function tp_render_repeater_row($section, $repKey, $index, $subfields, $row, $fixed) {
    echo '<div class="tp-repeater__row">';
    echo '<div class="tp-repeater__row-fields">';
    foreach ($subfields as $sf) {
        if ($sf['type'] === 'hidden') {
            $name = $section . '[' . $repKey . '][' . $index . '][' . $sf['key'] . ']';
            tp_field_control($name, 'hidden', $row[$sf['key']] ?? '');
            continue;
        }
        $name = $section . '[' . $repKey . '][' . $index . '][' . $sf['key'] . ']';
        echo '<span class="tp-repeater__cell tp-repeater__cell--' . esc_attr($sf['type']) . '">';
        tp_field_label($sf['label']);
        tp_field_control($name, $sf['type'], $row[$sf['key']] ?? '');
        echo '</span>';
    }
    echo '</div>';
    if (!$fixed) {
        echo '<button type="button" class="button-link tp-repeater__remove" aria-label="Remove row">&times;</button>';
    }
    echo '</div>';
}

/* ---------------------------------------------------------------------
 * Save
 * ------------------------------------------------------------------- */

function tp_sanitize_field_value($type, $value) {
    switch ($type) {
        case 'textarea': return sanitize_textarea_field($value);
        case 'url': return esc_url_raw($value);
        case 'image': return (int) $value;
        case 'checkbox': return empty($value) ? 0 : 1;
        case 'number':
        case 'hidden':
        case 'text':
        default: return sanitize_text_field($value);
    }
}

function tp_sanitize_fields($fields_config, $raw) {
    $out = [];
    foreach ($fields_config as $f) {
        $key = $f['key'];
        if ($f['type'] === 'repeater') {
            $rows = (isset($raw[$key]) && is_array($raw[$key])) ? $raw[$key] : [];
            $clean_rows = [];
            foreach ($rows as $rowRaw) {
                if (!is_array($rowRaw)) continue;
                $row = [];
                foreach ($f['fields'] as $sf) {
                    $row[$sf['key']] = tp_sanitize_field_value($sf['type'], $rowRaw[$sf['key']] ?? '');
                }
                $clean_rows[] = $row;
            }
            $out[$key] = $clean_rows;
        } else {
            $out[$key] = tp_sanitize_field_value($f['type'], $raw[$key] ?? '');
        }
    }
    return $out;
}

function tp_save_meta_boxes($post_id) {
    if (!isset($_POST['tp_meta_nonce']) || !wp_verify_nonce($_POST['tp_meta_nonce'], 'tp_save_meta')) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_page', $post_id)) return;
    if ((int) $post_id !== tp_front_page_id()) return;

    $posted = wp_unslash($_POST);
    foreach (tp_meta_boxes_config() as $section => $box) {
        if (!isset($posted[$section]) || !is_array($posted[$section])) continue;
        $clean = tp_sanitize_fields($box['fields'], $posted[$section]);
        update_post_meta($post_id, $section, $clean);
    }
}
add_action('save_post_page', 'tp_save_meta_boxes');

/* ---------------------------------------------------------------------
 * Admin assets (media uploader + repeater add/remove)
 * ------------------------------------------------------------------- */

function tp_admin_assets($hook) {
    if (!in_array($hook, ['post.php', 'post-new.php'], true)) return;
    $post = get_post();
    if (!$post || $post->post_type !== 'page' || (int) $post->ID !== tp_front_page_id()) return;

    wp_enqueue_media();
    wp_enqueue_style('tp-admin-meta', TP_THEME_URI . '/assets/css/admin-meta.css', [], TP_THEME_VERSION);
    wp_enqueue_script('tp-admin-meta', TP_THEME_URI . '/assets/js/admin-meta.js', ['jquery'], TP_THEME_VERSION, true);
}
add_action('admin_enqueue_scripts', 'tp_admin_assets');
