<?php
/**
 * Plugin Name: Team Section - Block
 * Description: Makes background element scrolls slower than foreground content.
 * Version: 3.0.1
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
	define( 'TSB_VERSION', isset( $_SERVER['HTTP_HOST'] ) && 'localhost' === $_SERVER['HTTP_HOST'] ? time() : '3.0.1' );
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

	require_once TSB_DIR_PATH . 'includes/class-tsb-promo-banner.php';

	function tsbPluginActionLinks( $links, $file ) {
		if ( plugin_basename( __FILE__ ) === $file ) {
			$goProLink = 'https://bplugins.com/products/team-section/pricing/?utm_source=admin&utm_medium=plugins&utm_campaign=bfcm2026';

			$linkText = ( current_time( 'Y-m-d' ) > '2026-12-05' )
				? __( 'Upgrade to Pro', 'team-section' )
				: __( 'Upgrade to Pro & Save Up to 45% This Black Friday!', 'team-section' );

			$links['help-and-demos'] = sprintf(
				'<a href="%s" target="_blank" rel="noopener noreferrer" style="%s">%s</a>',
				esc_url( $goProLink ),
				'color:#d63638;font-weight:bold;',
				$linkText
			);
		}

		return $links;
	}

	add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'tsbPluginActionLinks', 10, 2 );

}