<?php
/**
 * Team Builder block (tsb/team-members): PHP glue loader.
 *
 * The block is rendered in the browser (build/blocks/team-builder/view.js) from its attributes;
 * PHP only prints the mount wrapper, the window.bptmData bridge, Font Awesome and the shortcodes.
 *
 * The standalone "Team Builder – Grid, List, Carousel & Popup" plugin ships the same block,
 * PHP API (bptm_* functions) and asset handles, so this copy stays off while that plugin is active.
 *
 * @package TSB\TeamBuilder
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( defined( 'BPTM_FILE' ) || class_exists( '\\BPTM\\Block' ) ) {
	return;
}

require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/Block.php';
require_once __DIR__ . '/Quick.php';
