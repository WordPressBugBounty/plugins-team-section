<?php
namespace TSB;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class BlockCategory {

    public function __construct() {
        add_filter( 'block_categories_all', [ $this, 'register_category' ], 10, 2 );
    }

    public function register_category( $categories, $context ) {
        if ( ! is_array( $categories ) ) {
            $categories = [];
        }


        array_unshift( $categories, [
            'slug'  => 'team-section',
            'title' => __( 'Team Blocks', 'team-section' ),
            'icon'  => null,
        ] );

        return $categories;
    }
}
