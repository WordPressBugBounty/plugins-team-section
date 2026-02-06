<?php

/**
 * Plugin Name: Team Section - Block
 * Description: Makes background element scrolls slower than foreground content.
 * Version: 2.0.1
 * Author: bPlugins
 * Author URI: http://bplugins.com
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl-3.0.txt
 * Text Domain: team-section
 */
// ABS PATH
if ( !defined( 'ABSPATH' ) ) {
    exit;
}
if ( function_exists( 'ts_fs' ) ) {
    ts_fs()->set_basename( false, __FILE__ );
} else {
    // Constant
    define( 'TSB_VERSION', ( isset( $_SERVER['HTTP_HOST'] ) && 'localhost' === $_SERVER['HTTP_HOST'] ? time() : '1.1.0' ) );
    define( 'TSB_DIR_URL', plugin_dir_url( __FILE__ ) );
    define( 'TSB_DIR_PATH', plugin_dir_path( __FILE__ ) );
    define( 'TEAM_SECTION_BLOCK_PRO', file_exists( dirname( __FILE__ ) . '/freemius/start.php' ) );
    if ( !function_exists( 'ts_fs' ) ) {
        function ts_fs() {
            global $ts_fs;
            // Include Freemius SDK.
            if ( !isset( $ts_fs ) ) {
                if ( TEAM_SECTION_BLOCK_PRO ) {
                    require_once dirname( __FILE__ ) . '/freemius/start.php';
                } else {
                    require_once dirname( __FILE__ ) . '/freemius-lite/start.php';
                }
                $apbConfig = array(
                    'id'                  => '21587',
                    'slug'                => 'team-section',
                    'premium_slug'        => 'team-section-pro',
                    'type'                => 'plugin',
                    'public_key'          => 'pk_3ba5bf1bfe18f86fccd5a5995ae77',
                    'is_premium'          => true,
                    'premium_suffix'      => 'Pro',
                    'has_premium_version' => true,
                    'has_addons'          => false,
                    'has_paid_plans'      => true,
                    'menu'                => array(
                        'slug'       => 'edit.php?post_type=tsb',
                        'first-path' => 'edit.php?post_type=tsb&page=team-section-dashboard',
                        'support'    => false,
                    ),
                );
                $ts_fs = ( TEAM_SECTION_BLOCK_PRO ? fs_dynamic_init( $apbConfig ) : fs_lite_dynamic_init( $apbConfig ) );
            }
            return $ts_fs;
        }

        ts_fs();
        // Signal that SDK was initiated.
        do_action( 'ts_fs_loaded' );
    }
    function tsbIsPremium() {
        return ( TEAM_SECTION_BLOCK_PRO ? ts_fs()->can_use_premium_code() : false );
    }

    require_once TSB_DIR_PATH . '/includes/ShortCode.php';
    require_once TSB_DIR_PATH . '/includes/AdminMenu.php';
    if ( !class_exists( 'TSBPlugin' ) ) {
        class TSBPlugin {
            function __construct() {
                add_action( 'enqueue_block_assets', [$this, 'enqueueBockAssets'] );
                add_action( 'init', [$this, 'onInit'] );
                add_action( 'enqueue_block_editor_assets', [$this, 'tsmEnqueueBlockEditorAssets'] );
                add_action( 'wp_enqueue_scripts', [$this, 'tsmEnqueueBlockEditorAssets'] );
            }

            function enqueueBockAssets() {
                wp_register_style(
                    'fontAwesome',
                    TSB_DIR_URL . 'assets/css/font-awesome.min.css',
                    [],
                    '6.4.2'
                );
            }

            function tsmEnqueueBlockEditorAssets() {
                wp_add_inline_script( 'tsb-team-editor-script', 'const tsmbpipecheck = ' . wp_json_encode( tsbIsPremium() ) . ';', 'before' );
                wp_add_inline_script( 'tsb-team-view-script', 'const tsmbpipecheck = ' . wp_json_encode( tsbIsPremium() ) . ';', 'before' );
            }

            function onInit() {
                register_block_type( __DIR__ . '/build' );
            }

        }

        new TSBPlugin();
    }
}
// require_once TSB_DIR_PATH . 'inc/block.php';