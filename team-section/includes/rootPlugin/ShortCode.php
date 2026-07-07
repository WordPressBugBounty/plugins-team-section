<?php
namespace TSB;

class ShortCode {
    private $post_type = 'tsb';

    function __construct() {
        add_shortcode( 'tsb', [$this, 'onAddShortcode'] );
        add_action( 'use_block_editor_for_post', [$this, 'useBlockEditorForPost'], 999, 2 );
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

    function useBlockEditorForPost( $use, $post ){
		if ( is_object( $post ) && isset( $post->post_type ) && $this->post_type === $post->post_type ) {
			return true;
		}
		return $use;
	}
    
}
