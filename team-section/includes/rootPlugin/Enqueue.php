<?php

namespace TSB;

class Enqueue {
    function __construct() {
        add_action( 'enqueue_block_assets', [$this, 'enqueueBlockAssets'] );
        add_action( 'admin_enqueue_scripts', [$this, 'adminEnqueueScripts'] );
    }

    function enqueueBlockAssets(){
        wp_register_style( 'fontAwesome', TSB_DIR_URL . 'assets/css/font-awesome.min.css', [], '6.4.2' );

        $disabled_blocks = get_option( 'tsbBlocks', [] );
		if ( ! is_array( $disabled_blocks ) ) {
			$disabled_blocks = [];
		}

		wp_localize_script(
			'wp-blocks',
			'TSB_BLOCK_DATA',
			[
				'disabledBlocks' => $disabled_blocks,
			]
		);
    }

    function adminEnqueueScripts( $hook ){
        global $typenow;

        if ( 'tsb' === $typenow ) {
            if( 'edit.php' === $hook || 'post.php' === $hook ){
                wp_enqueue_style( 'tsb-admin-post', TSB_DIR_URL . 'build/admin-post.css', [], TSB_VERSION );
                wp_enqueue_script( 'tsb-admin-post', TSB_DIR_URL . 'build/admin-post.js', [], TSB_VERSION, true );
                wp_set_script_translations( 'tsb-admin-post', 'team-section', TSB_DIR_PATH . 'languages' );
            }
        }
    }
  
}
