<?php
/**
 * Plugin Name: AI SEO Content Analyzer
 * Plugin URI: https://aiseoautooptimize.pro
 * Description: AI SEO Content Analyzer is a comprehensive SEO tool designed to scan and evaluate your content for potential SEO issues. By examining your content based on predefined parameters, it provides actionable recommendations and insights to improve its SEO optimization. Whether you're looking to increase your content's visibility, address common SEO mistakes, or just ensure that your website is on the right track, this plugin offers a streamlined and user-friendly solution.
 * Version: 1.0.1
 * Author: BuyReadySite.com
 * Author URI: https://aiseoautooptimize.pro
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: ai-seo-content-analyzer
 * Domain Path: /languages
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

// Define plugin path
define( 'AI_SEO_ANALYZER_PATH', plugin_dir_path( __FILE__ ) );

// Load translation
function ai_seo_content_analyzer_load_textdomain() {
	load_plugin_textdomain( 'ai-seo-content-analyzer', false, basename( dirname( __FILE__ ) ) . '/languages' ); 
}
add_action( 'init', 'ai_seo_content_analyzer_load_textdomain' );

// Include admin files
require_once AI_SEO_ANALYZER_PATH . 'admin/ai_seo_optimization_score.php';
require_once AI_SEO_ANALYZER_PATH . 'admin/ai_seo_score_display.php';
require_once AI_SEO_ANALYZER_PATH . 'admin/seo-plugins.php';
require_once AI_SEO_ANALYZER_PATH . 'admin/console/mass-analysis.php';
require_once AI_SEO_ANALYZER_PATH . 'admin/console/console.php'; // добавляем эту строку

// Include analysis parameters
$analysis_files = glob( AI_SEO_ANALYZER_PATH . 'admin/analysis-parameters/*.php' );
foreach ( $analysis_files as $file ) {
	require_once $file;
}


// Enqueue styles and scripts
function ai_seo_content_analyzer_assets() {
	wp_enqueue_style( 'ai-seo-content-analyzer-styles', plugins_url( 'assets/styles.css', __FILE__ ) );
	wp_enqueue_script( 'ai-seo-content-analyzer-scripts', plugins_url( 'assets/scripts.js', __FILE__ ), array('jquery'), '1.0.0', true );
}
add_action( 'admin_enqueue_scripts', 'ai_seo_content_analyzer_assets' );

// Create admin menu
function ai_seo_content_analyzer_menu() {
	add_menu_page(
		'AI SEO Content Analyzer',
		'AI SEO Content Analyzer',
		'manage_options',
		'ai-seo-content-analyzer',
		'ai_seo_content_analyzer_console',
		plugins_url( 'assets/icon.png', __FILE__ ),
		2
	);

	add_submenu_page(
		'ai-seo-content-analyzer',
		__('Mass Analyzer', 'ai-seo-content-analyzer'),
		__('Mass Analyzer', 'ai-seo-content-analyzer'),
		'manage_options',
		'ai-seo-content-analyzer-mass-optimization',
		'ai_seo_content_analyzer_mass_optimization'
	);
	
		add_submenu_page(
		'ai-seo-content-analyzer',
		__('AI SEO AutoOptimize Pro website', 'ai-seo-content-analyzer'),
		__('AI SEO AutoOptimize Pro website', 'ai-seo-content-analyzer'),
		'manage_options',
		'https://aiseoautooptimize.pro'
	);
}
add_action( 'admin_menu', 'ai_seo_content_analyzer_menu' );


// Hook for checking plugin dependencies
add_action( 'admin_init', 'ai_seo_content_analyzer_check_dependencies' );

function ai_seo_content_analyzer_check_dependencies() {
    // Check if Yoast SEO, Rank Math, or Classic Editor is active
    if (!ai_seo_content_analyzer_check_seo_plugins_active()) {
        // Deactivate the plugin
        deactivate_plugins(plugin_basename(__FILE__));

        // Show admin notice
        add_action('admin_notices', 'ai_seo_content_analyzer_plugin_notice');

        // Remove any additional actions
        if (isset($_GET['activate'])) {
            unset($_GET['activate']);
        }
    }
}

// Функция проверки активации Rank Math, Yoast и Classic Editor
function ai_seo_content_analyzer_check_seo_plugins_active() {
    $is_seo_plugin_active = is_plugin_active('wordpress-seo/wp-seo.php') || is_plugin_active('seo-by-rank-math/rank-math.php');
    return $is_seo_plugin_active && is_plugin_active('classic-editor/classic-editor.php');
}


// Если Rank Math, Yoast или Classic Editor не активированы, удаляем пункты меню
function ai_seo_content_analyzer_remove_menu() {
    if (!ai_seo_content_analyzer_check_seo_plugins_active()) {
        remove_menu_page('ai-seo-content-analyzer');
    }
}
add_action('admin_menu', 'ai_seo_content_analyzer_remove_menu', 11); // Приоритет 11, чтобы выполнить после создания меню

// Добавляем уведомление, если Rank Math, Yoast или Classic Editor не активированы
function ai_seo_content_analyzer_plugin_notice() {
    if (!ai_seo_content_analyzer_check_seo_plugins_active()) {
        echo '<div class="notice notice-error is-dismissible">';
        echo '<p>' . __('AI SEO Content Analyzer requires <a href="https://wordpress.org/plugins/wordpress-seo/" target="_blank">Yoast SEO</a>, <a href="https://wordpress.org/plugins/seo-by-rank-math/" target="_blank">Rank Math</a>, or <a href="https://wordpress.org/plugins/classic-editor/" target="_blank">Classic Editor</a> to be activated. Please activate one of these plugins to use AI SEO Content Analyzer.', 'ai-seo-content-analyzer') . '</p>';
        echo '</div>';
    }
}
add_action('admin_notices', 'ai_seo_content_analyzer_plugin_notice');


function ai_seo_content_analyzer_add_plugin_links($links, $file) {
    $plugin_base = plugin_basename(__FILE__);
    if ($file == $plugin_base) {
        $new_links = array(
            '<a href="https://aiseoautooptimize.pro/wiki/">' . __('WIKI Plugin', 'ai-seo-content-analyzer') . '</a>'
        );
        return array_merge($links, $new_links);
    }
    return $links;
}
add_filter('plugin_row_meta', 'ai_seo_content_analyzer_add_plugin_links', 10, 2);




/* 
// Hook for checking plugin dependencies
add_action( 'admin_init', 'ai_seo_content_analyzer_check_dependencies' );

function ai_seo_content_analyzer_check_dependencies() {
    // Check if Yoast SEO, Rank Math, or Classic Editor is active
    if ( ! is_plugin_active( 'wordpress-seo/wp-seo.php' ) && ! is_plugin_active( 'seo-by-rank-math/rank-math.php' ) && ! is_plugin_active( 'classic-editor/classic-editor.php' ) ) {
        // Deactivate the plugin
        deactivate_plugins( plugin_basename( __FILE__ ) );

        // Show admin notice
        add_action( 'admin_notices', 'ai_seo_content_analyzer_dependency_notice' );

        // Remove any additional actions
        if ( isset( $_GET['activate'] ) ) {
            unset( $_GET['activate'] );
        }
    }
}

function ai_seo_content_analyzer_dependency_notice() {
    ?>
    <div class="notice notice-error">
        <p><?php _e( 'AI SEO Content Analyzer requires Yoast SEO or Rank Math to be active!', 'ai-seo-content-analyzer' ); ?></p>
    </div>
    <?php
}


function ai_seo_content_analyzer_add_plugin_links($links, $file) {
    $plugin_base = plugin_basename(__FILE__);
    if ($file == $plugin_base) {
        $new_links = array(
            '<a href="https://aiseoautooptimize.pro/wiki/">' . __('WIKI Plugin', 'ai-seo-content-analyzer') . '</a>'
        );
        return array_merge($links, $new_links);
    }
    return $links;
}
add_filter('plugin_row_meta', 'ai_seo_content_analyzer_add_plugin_links', 10, 2);





// Функция проверки активации Rank Math, Yoast или Classic Editor
function ai_seo_content_analyzer_check_seo_plugins_active() {
    return is_plugin_active('wordpress-seo/wp-seo.php') || is_plugin_active('seo-by-rank-math/rank-math.php') || is_plugin_active('classic-editor/classic-editor.php');
}

// Если Rank Math или Yoast не активированы, удаляем пункты меню
function ai_seo_content_analyzer_remove_menu() {
    if (!ai_seo_content_analyzer_check_seo_plugins_active()) {
        remove_menu_page('ai-seo-content-analyzer');
    }
}
add_action('admin_menu', 'ai_seo_content_analyzer_remove_menu', 11); // Приоритет 11, чтобы выполнить после создания меню

// Добавляем уведомление, если Rank Math, Yoast или Classic Editor не активированы
function ai_seo_content_analyzer_plugin_notice() {
    if (!ai_seo_content_analyzer_check_seo_plugins_active()) {
        echo '<div class="notice notice-error is-dismissible">';
        echo '<p>' . __('AI SEO Content Analyzer requires Yoast SEO, Rank Math, or Classic Editor to be activated. Please activate one of these plugins to use AI SEO Content Analyzer.', 'ai-seo-content-analyzer') . '</p>';
        echo '</div>';
    }
}
add_action('admin_notices', 'ai_seo_content_analyzer_plugin_notice');
 */