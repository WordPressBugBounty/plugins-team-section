<?php


if ( !defined( 'ABSPATH' ) ) { exit; } 

class TSBproAdminMenu {
	public function __construct() {
		add_action( 'admin_menu', [ $this, 'adminMenu' ] );
		add_action( 'admin_enqueue_scripts', [$this, 'adminEnqueueScripts'] );
	}

	
	public function adminMenu(){
					$icon = "
					<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 388.266 388.266' fill='currentColor' color='rgba(240, 246, 252, .6)'>
					<path d='M388.172,55.218L116.189,1.089l-0.654-0.128L26.397,1.125L0,1.089v386.216h388.266V64.598L388.172,55.218z 
					M319.291,144.434C293.78,121.517,213.527,66.745,159.834,30.772l206.154,42.069C361.865,90.479,350.016,126.11,319.291,144.434z 
					M321.917,166.114c21.45-10.299,36.072-26.548,45.986-43.079v243.904H20.362V21.497l4.5-0.011l84.381,0.112 
					c67.822,44.747,184.602,123.856,200.608,142.056l5.089,5.787L321.917,166.114z'/>
					</svg>
					";

	
		// add_menu_page(
		// 	__('Team Section- bPlugins', 'team-section'),
		// 	__('Team Section', 'team-section'),
		// 	'manage_options',
		// 	'team-section-dashboard',
		// 	'',
		// 	'data:image/svg+xml;base64,' . base64_encode( $icon ),
		// 	20
		// );
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
				'isPremium' => tsbIsPremium(),
				'hasPro' => TEAM_SECTION_BLOCK_PRO
			] ) ); ?>'
		></div>
	<?php }

	function adminEnqueueScripts( $hook ) {
		if( strpos( $hook, 'team-section-dashboard' ) ){
			wp_enqueue_style( 'tsb-admin-dashboard', TSB_DIR_URL . 'build/admin-dashboard.css', [], TSB_VERSION );
			wp_enqueue_script( 'tsb-admin-dashboard', TSB_DIR_URL . 'build/admin-dashboard.js', [ 'react', 'react-dom' ], TSB_VERSION, true );
			wp_set_script_translations( 'tsb-admin-dashboard', 'click-to-copy', TSB_DIR_PATH . 'languages' );
				
		}
	}
}
new TSBproAdminMenu();
