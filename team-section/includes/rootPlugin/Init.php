<?php

namespace TSB;

if ( ! defined( 'ABSPATH' ) ) { exit; }

class Init {
    function __construct() {
        add_action( 'init', [ $this, 'onInit' ] );
        add_filter( 'default_title', [$this, 'defaultTitle'], 10, 2 );
        add_filter( 'default_content', [$this, 'defaultContent'], 10, 2 );
    }

    function onInit() {
        $this->tsb_register_blocks();

      
    
            $template = [ [ 'tsb/team-section-selector' ] ];
        

        register_post_type( 'tsb', [
            'labels'                => [
                'name'          => __( 'Team Section', 'team-section' ),
                'singular_name' => __( 'ShortCode', 'team-section' ),
                'add_new_item'  => __( 'Add New ShortCode', 'team-section' ),
                'edit_item'     => __( 'Edit ShortCode', 'team-section' ),
                'new_item'      => __( 'New ShortCode', 'team-section' ),
                'view_item'     => __( 'View ShortCode', 'team-section' ),
                'search_items'  => __( 'Search ShortCodes', 'team-section' ),
                'not_found'     => __( 'Sorry, we couldn\'t find the ShortCode you are looking for.', 'team-section' )
            ],
            'public'                => false,
            'show_ui'               => true,
            'show_in_rest'          => true,
            'publicly_queryable'    => false,
            'show_in_menu'          => true,
            'exclude_from_search'   => true,
            'menu_position'         => 14,
            'menu_icon'             => 'dashicons-groups',
            'has_archive'           => false,
            'hierarchical'          => false,
            'capability_type'       => 'page',
            'rewrite'               => [ 'slug' => 'team-section' ],
            'supports'              => [ 'title', 'editor' ],
            'template'              => $template,
            'template_lock'         => 'all',
        ] );
    }


    function tsb_register_blocks() {
        $blocks_path = TSB_DIR_PATH . 'build/blocks/';
        
        // Use scandir instead of glob to prevent issues on restricted servers
        if ( ! is_dir( $blocks_path ) ) {
            return;
        }
        
        $files = scandir( $blocks_path );
        $all_blocks = [];
        
        foreach ( $files as $file ) {
            if ( $file !== '.' && $file !== '..' && is_dir( $blocks_path . $file ) ) {
                $all_blocks[] = $blocks_path . $file;
            }
        }

        if ( empty( $all_blocks ) ) {
            return;
        }

        // Blocks toggled OFF by the admin dashboard.
        $disabled_blocks = get_option( 'tsbBlocks', [] );
        if ( ! is_array( $disabled_blocks ) ) {
            $disabled_blocks = [];
        }


        foreach ( $all_blocks as $block_path ) {
            $block_name = basename( $block_path );

            
            if ( in_array( $block_name, $disabled_blocks, true ) ) {
                continue;
            }

           
            if ( in_array( $block_name, [ 'team-section', 'parent' ], true ) ) {
                register_block_type( $block_path );
                continue;
            }

            // Team Builder (tsb/team-members): only when its PHP glue is loaded
            // (it stays off while the standalone Team Builder plugin is active).
            if ( 'team-builder' === $block_name ) {
                if ( class_exists( '\TSB\TeamBuilder\Block' ) ) {
                    register_block_type( $block_path );
                }
                continue;
            }

        
        }
    }

  
    /**
     * The dashboard "Start Now" link must come from a user who can edit pages and carry a valid nonce.
     */
    private function isValidStartRequest() {
        if ( ! current_user_can( 'edit_pages' ) ) {
            return false;
        }
        $nonce = isset( $_GET['nonce'] ) ? sanitize_text_field( wp_unslash( $_GET['nonce'] ) ) : '';
        return (bool) wp_verify_nonce( $nonce, 'tsb_admin_nonce' );
    }

    function defaultTitle( $title, $post ) {
        if ( 'page' === $post->post_type && isset( $_GET['title'] ) && $this->isValidStartRequest() ) {
            return sanitize_text_field( wp_unslash( $_GET['title'] ) );
        }
        return $title;
    }

    /**
     * Pre-fill a new page with the Team Section block. Only the exact block markup is accepted,
     * and our own constant is returned so request input is never echoed into the editor.
     */
    function defaultContent( $content, $post ) {
        if ( 'page' !== $post->post_type || ! isset( $_GET['content'] ) || ! $this->isValidStartRequest() ) {
            return $content;
        }
        $allowed   = '<!-- wp:tsb/team /-->';
        $requested = trim( wp_unslash( $_GET['content'] ) );

        return $requested === $allowed ? $allowed : $content;
    }
}
