<?php

/**
 * RestApi.php — AJAX handler for block toggle management.
 * Mirrors: info-cards/inc/RestApi.php (tsbGetBlocks action only)
 */

namespace TSB;

if ( ! defined( 'ABSPATH' ) ) { exit; }

class RestApi {

    function __construct() {
        add_action( 'wp_ajax_tsbGetBlocks', [ $this, 'tsbGetBlocks_callback' ] );
        add_action( 'wp_ajax_tsbSaveUninstallOption', [ $this, 'tsbSaveUninstallOption_callback' ] );
    }

    // -------------------------------------------------------------------------
    // tsbGetBlocks (AJAX)
    // -------------------------------------------------------------------------

    public function tsbGetBlocks_callback() {
        $nonce = sanitize_text_field( wp_unslash( $_POST['_wpnonce'] ?? '' ) );

        if ( ! wp_verify_nonce( $nonce, 'tsb_admin_nonce' ) ) {
            wp_send_json_error( 'Invalid Request' );
        }

        $db_data = get_option( 'tsbBlocks', [] );

        if ( ! isset( $_POST['data'] ) ) {
            wp_send_json_success( $db_data );
        }

        $data = json_decode( stripslashes( $_POST['data'] ), true );

        update_option( 'tsbBlocks', $data );

        wp_send_json_success( $data );
    }

    // -------------------------------------------------------------------------
    // tsbSaveUninstallOption (AJAX) — persist the "delete data on uninstall" toggle.
    // Contract matches bpl-tools/Admin/Settings: reads $_POST['nonce'] and $_POST['enabled'].
    // -------------------------------------------------------------------------

    public function tsbSaveUninstallOption_callback() {
        $nonce = sanitize_text_field( wp_unslash( $_POST['nonce'] ?? '' ) );

        if ( ! wp_verify_nonce( $nonce, 'tsb_save_uninstall_option' ) ) {
            wp_send_json_error( [ 'message' => __( 'Invalid security token.', 'team-section' ) ], 403 );
        }

        if ( ! current_user_can( 'manage_options' ) ) {
            wp_send_json_error( [ 'message' => __( 'You do not have permission to perform this action.', 'team-section' ) ], 403 );
        }

        // Support both string 'true'/'false' and '1'/'0'.
        $raw_enabled = isset( $_POST['enabled'] ) ? sanitize_text_field( wp_unslash( $_POST['enabled'] ) ) : '';
        $enabled     = ( 'true' === $raw_enabled || '1' === $raw_enabled );

        update_option( 'tsbDeleteDataOnUninstall', $enabled );

        wp_send_json_success( [
            'enabled' => $enabled,
            'message' => $enabled
                ? __( 'Data deletion enabled.', 'team-section' )
                : __( 'Data will be preserved on uninstall.', 'team-section' ),
        ] );
    }
}
