<?php

namespace TSB;

if ( !defined( 'ABSPATH' ) ) { exit; } 

class AdminMenu {
	public function __construct() {
		add_action( 'admin_menu', [ $this, 'adminMenu' ] );
		add_action( 'admin_enqueue_scripts', [$this, 'adminEnqueueScripts'] );
	}

	
	public function adminMenu(){
		add_submenu_page(
			'edit.php?post_type=tsb',
			__('Help And Demo - bPlugins', 'team-section'),
			__('Help And Demo', 'team-section'),
			'manage_options',
			'team-section-dashboard',
			[$this, 'renderDashboardPage'],
			1
		);
	
	} 


	public function renderDashboardPage(){ ?>
	
	
		<div
           id='tsm_team_section_block'
            data-info='<?php echo esc_attr( wp_json_encode( [
                'version' => TSB_VERSION,
                
                'action' => 'tsbGetBlocks',
                'nonce' => wp_create_nonce( 'tsb_admin_nonce' ),
                'adminUrl' => admin_url(),
                'deleteDataOnUninstall' => (bool) get_option( 'tsbDeleteDataOnUninstall', false ),
                'uninstallNonce' => wp_create_nonce( 'tsb_save_uninstall_option' ),
            ] ) ); ?>'
        ></div>
	<?php }

	function adminEnqueueScripts( $hook ) {
		if( strpos( $hook, 'team-section-dashboard' ) ){
			$asset_file = TSB_DIR_PATH . 'build/admin-dashboard.asset.php';
			$asset      = file_exists( $asset_file ) ? require $asset_file : [ 'dependencies' => [], 'version' => TSB_VERSION ];

		
			$dependencies = array_unique( array_merge( $asset['dependencies'], [ 'wp-util' ] ) );

			wp_enqueue_style( 'tsb-admin-dashboard', TSB_DIR_URL . 'build/admin-dashboard.css', [], TSB_VERSION );
			wp_enqueue_script( 'tsb-admin-dashboard', TSB_DIR_URL . 'build/admin-dashboard.js', $dependencies, $asset['version'], true );
			wp_set_script_translations( 'tsb-admin-dashboard', 'team-section', TSB_DIR_PATH . 'languages' );

		}
	}

}
