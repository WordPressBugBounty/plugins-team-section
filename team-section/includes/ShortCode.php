<?php

    if ( !defined( 'ABSPATH' ) ) { exit; }
class TSB_Shortcode {
    private $post_type = 'tsb';
    	public function __construct() {
		add_shortcode( 'tsb', [$this, 'onAddShortcode'] );
		add_action( 'admin_enqueue_scripts', [$this, 'adminEnqueueScripts'] );
		add_action( 'init', [$this, 'onInit'] );
	     add_filter( 'manage_tsb_posts_columns', [$this, 'manageTSBPostsColumns'], 10 );
        add_action( 'manage_tsb_posts_custom_column', [$this, 'manageTSBPostsCustomColumns'], 10, 2 );

		add_action( 'use_block_editor_for_post', [$this, 'useBlockEditorForPost'], 999, 2 );
	}

	function onInit(){


		register_post_type( 'tsb', [
			'labels'				=> [
				'name'			=> __( 'Team Section', 'team-section' ),
				'singular_name'	=> __( 'ShortCode', 'team-section' ),
				'add_new_item'	=> __( 'Add New ShortCode', 'team-section' ),
				'edit_item'		=> __( 'Edit ShortCode', 'team-section' ),
				'new_item'		=> __( 'New ShortCode', 'team-section' ),
				'view_item'		=> __( 'View ShortCode', 'team-section' ),
				'search_items'	=> __( 'Search ShortCodes', 'team-section' ),
				'not_found'		=> __( 'Sorry, we couldn\'t find the ShortCode you are looking for.', 'team-section' )
			],
			'public'				=> false,
			'show_ui'				=> true,
			'show_in_rest'			=> true,
			'publicly_queryable'	=> false,
			'show_in_menu'			=> true,
			'exclude_from_search'	=> true,
			'menu_position'			=> 14,
			'menu_icon' => 'dashicons-groups',
			'has_archive'			=> false,
			'hierarchical'			=> false,
			'capability_type'		=> 'page',
			'rewrite'				=> [ 'slug' => 'apb' ],
			'supports'				=> [ 'title', 'editor' ],
			'template' => [ [ 'tsb/team' ] ],
			'template_lock'			=> "all",
		] );
	}
	
	function onAddShortcode( $atts ) {
		$post_id = $atts['id'];
		$post = get_post( $post_id );

		if ( !$post ) {
			return '';
		}

		if ( post_password_required( $post ) ) {
			return get_the_password_form( $post );
		}

		switch ( $post->post_status ) {
			case 'publish':
				return $this->displayContent( $post );

			case 'private':
				if ( current_user_can( 'read_private_posts' ) ) {
					return $this->displayContent( $post );
				}
				return '';

			case 'draft':
			case 'pending':
			case 'future':
				if ( current_user_can( 'edit_post', $post_id ) ) {
					return $this->displayContent( $post );
				}
				return '';

			default:
				return '';
		}
	}

	function displayContent( $post ) {
    $content = apply_filters( 'the_content', $post->post_content );
    return $content;
}

	function manageTSBPostsColumns( $defaults ) {
		unset( $defaults['date'] );
		$defaults['shortcode'] = 'ShortCode';
		$defaults['date'] = 'Date';
		return $defaults;
	}

	function manageTSBPostsCustomColumns( $column_name, $post_ID ) {
		if ( $column_name == 'shortcode' ) {
			echo '<div class="bPlAdminShortcode" id="bPlAdminShortcode-' . esc_attr( $post_ID ) . '">
				<input value="[tsb id=' . esc_attr( $post_ID ) . ']" onclick="copyBPlAdminShortcode(\'' . esc_attr( $post_ID ) . '\')">
				<span class="tooltip">' . esc_html__( 'Copy To Clipboard', 'team-section' ) . '</span>
			</div>';
		}
	}

	function useBlockEditorForPost( $use, $post ){
		if ( is_object( $post ) && isset( $post->post_type ) && $this->post_type === $post->post_type ) {
			return true;
		}
		return $use;
	}
    	function adminEnqueueScripts( $hook ){
		if( 'edit.php' === $hook || 'post.php' === $hook ){
			wp_enqueue_style( 'tsb-admin-post', TSB_DIR_URL . 'build/admin-post.css', [], TSB_VERSION );
			wp_enqueue_script( 'tsb-admin-post', TSB_DIR_URL . 'build/admin-post.js', [], TSB_VERSION, true );
			wp_set_script_translations( 'tsb-admin-post', 'team-section', TSB_DIR_PATH . 'languages' );
		}
	}

}
new TSB_Shortcode();