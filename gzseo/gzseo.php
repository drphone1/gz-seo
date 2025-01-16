<?php
/**
 * 
 * 
 */
/*
Plugin Name: GZSEO
Description: Generation Z SEO Plugin | Website SEO Powered by Artificial Intelligence
Author: amin hashemi
Version: 1.0.5
Author URI: https://aminhashemy.org/
License: GPLv2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/
if ( ! defined( 'ABSPATH' ) ) exit;

// GZSEO Consts
define('GZSEO_PLUGIN_DIR',plugin_dir_path(__FILE__));
define('GZSEO_URI',plugin_dir_url(__FILE__));
define('GZSEO_VIEWS_DIR',plugin_dir_path(__FILE__).'views/');
define('GZSEO_VIEWS_URI',plugin_dir_url(__FILE__).'views/');

// Load Modules
require_once(GZSEO_PLUGIN_DIR.'modules/modules.php');

// Init Classes
$gzseo = new gzseo();
$gzseo_comment= new gzseo_comment();
$gzseo_gsc = new gzseo_gsc();
$gzseo_ai = new gzseo_ai();

// Init WP Menus
add_action('admin_menu', [$gzseo , 'create_menu']);

// Init Metaboxes
add_action('add_meta_boxes', [$gzseo_comment , 'add_meta_box']);

// Init REST API
add_action( 'rest_api_init', [$gzseo_gsc,'register_rest_route']);
add_filter('rest_index', [$gzseo_gsc,'unsetrests']);

// Handle AJAX request
add_action('wp_ajax_gzseo_search', [$gzseo,'ajax_search']);
add_action('wp_ajax_gzseo_add_comment', [$gzseo_comment ,'add_comment']);
add_action('wp_ajax_gzseo_save_comments', [$gzseo_comment ,'save_user_comments']);
add_action('wp_ajax_gzseo_ai_login', [$gzseo_ai,'login']);
add_action('wp_ajax_gzseo_ajax_remove_acc', [$gzseo_ai,'remove_acc']);
add_action('wp_ajax_gzseo_ajax_register', [$gzseo_ai,'register']);
add_action('wp_ajax_gzseo_ajax_get_payment_url', [$gzseo_ai,'get_payment_url']);
add_action('add_meta_boxes', [$gzseo_ai,'add_ai_comment_meta_box']);

add_action('wp_ajax_gzseo_ajax_create_comment', [$gzseo_ai,'create_comment']);
add_action('wp_ajax_gzseo_submit_selected_comments', [$gzseo_ai,'submit_comment']);
add_action('wp_ajax_gzseo_ajax_create_table', [$gzseo_ai,'create_table']);
add_filter('the_content', [$gzseo_ai,'show_table_in_single_pages']);


add_action('wp_ajax_gzseo_submit_table_style', [$gzseo_ai,'submit_table_style']);
add_action('wp_ajax_gzseo_get_table_style', [$gzseo_ai,'get_table_style']);
add_action('wp_ajax_gzseo_ajax_submit_table', [$gzseo_ai,'submit_table']);  
add_action('wp_ajax_gzseo_config', [$gzseo_ai,'save_setings_comments']);

add_action('wp_ajax_gzseo_run_bulk_update_preview', [$gzseo_ai,'bulk_update_preview']);
add_action('wp_ajax_gzseo_save_bulks_updates', [$gzseo_ai,'save_bulks_updates']);
add_action('wp_ajax_gzseo_run_bulk_update', [$gzseo_ai,'get_bulks_posts' ]);
add_action('wp_ajax_gzseo_perform_update', [$gzseo_ai,'bulk_update_single']);

add_action('wp_ajax_gzseo_table_delete', [$gzseo_ai,'table_delete']);

add_action('wp_ajax_gzseo_reports_bulk_actions', [$gzseo_ai,'reports_bulk_actions']);

add_action( 'wp_ajax_gzseo_save_advanced_settings', 'gzseo_save_advanced_settings_callback' );
// اگر کاربران غیرواردشده هم باید بتوانند این اکشن را انجام دهند:
// add_action( 'wp_ajax_nopriv_save_advanced_settings', 'save_advanced_settings_callback' );

function gzseo_save_advanced_settings_callback() {
// بررسی nonce برای امنیت
        check_ajax_referer( 'save_advanced_settings_nonce', 'security' );

        $site_address = isset( $_POST['siteAddress'] ) ? sanitize_text_field( wp_unslash($_POST['siteAddress'])  ) : '';
        $domain_type  = isset( $_POST['domainType'] ) ? sanitize_text_field( wp_unslash($_POST['domainType']) ) : '';
        if($domain_type == 'domain'){
            $site_address = 'sc-domain:'.$site_address;
        }
        gzseo_gsc::update_gsc_domain($site_address);

    }

add_action('wp_ajax_gzseo_update_dashboard_content',[$gzseo,'update_dashboard_content']);