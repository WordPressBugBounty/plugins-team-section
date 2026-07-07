<?php
/**
 * Plugin Name: Team Section - Block
 * Description: Makes background element scrolls slower than foreground content.
 * Version: 2.0.3
 * Author: bPlugins
 * Author URI: http://bplugins.com
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl-3.0.txt
 * Text Domain: team-section
 * @fs_premium_only vendor/freemius-lite
 */

// ABS PATH
if ( !defined( 'ABSPATH' ) ) { exit; }

if ( function_exists( 'ts_fs' ) ) {
	ts_fs()->set_basename( true, __FILE__ );
} else {

	// Constant
	define( 'TSB_VERSION', isset( $_SERVER['HTTP_HOST'] ) && 'localhost' === $_SERVER['HTTP_HOST'] ? time() : '2.0.3' );
	define( 'TSB_DIR_URL', plugin_dir_url( __FILE__ ) );
	define( 'TSB_DIR_PATH', plugin_dir_path( __FILE__ ) );


	if ( ! function_exists( 'ts_fs' ) ) {

		function ts_fs() {
			global $ts_fs;

			if ( ! isset( $ts_fs ) ) {

		
					require_once dirname(__FILE__) . '/vendor/freemius-lite/start.php';
				
				$apbConfig = array(
					'id'                  => '21587',
					'slug'                => 'team-section',
					'premium_slug'        => 'team-section-pro',
					'type'                => 'plugin',
					'public_key'          => 'pk_3ba5bf1bfe18f86fccd5a5995ae77',
					'is_premium'          => false,
					'premium_suffix'      => 'Pro',

					'has_premium_version' => true,
					'has_addons'          => false,
					'has_paid_plans'      => true,
					'menu'                => array(
						'slug'           => 'edit.php?post_type=tsb',
						'first-path'     => 'edit.php?post_type=tsb&page=team-section-dashboard#/welcome',
						'support'        => false,
					),
				) ;
				$ts_fs = fs_lite_dynamic_init( $apbConfig );
			}
			return $ts_fs;
		}

		ts_fs();
		do_action( 'ts_fs_loaded' );

	}


	
	require_once TSB_DIR_PATH . 'includes/plugin.php';

}