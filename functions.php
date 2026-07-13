<?php
/**
 * Tecnoprism Talent theme bootstrap.
 */

if (!defined('ABSPATH')) exit;

define('TP_THEME_VERSION', '1.0.0');
define('TP_THEME_DIR', get_template_directory());
define('TP_THEME_URI', get_template_directory_uri());

require_once TP_THEME_DIR . '/inc/helpers.php';
require_once TP_THEME_DIR . '/inc/setup.php';
require_once TP_THEME_DIR . '/inc/meta-boxes.php';
require_once TP_THEME_DIR . '/inc/service-content.php';
