<?php
/**
 * Public helper API.
 *
 * Team members live in the block (`members` attribute, see docs/ARCHITECTURE.md "v2 data model").
 * bptm_member_from_item() turns one stored item into the member array every theme / popup / panel /
 * drawer template and the render Context work with.
 *
 * Team Builder block (tsb/team-members) — part of Team Section.
 *
 * @package TSB\TeamBuilder
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Interpret a stored option value as boolean.
 *
 * Settings may store toggles as bool, 1/0, '1'/'', 'on'/'off', 'yes'/'no' or 'true'/'false'.
 *
 * @param mixed $value Raw value.
 * @return bool
 */
function bptm_is_on( $value ) {
	if ( is_bool( $value ) ) {
		return $value;
	}
	if ( is_numeric( $value ) ) {
		return (int) $value > 0;
	}
	if ( is_string( $value ) ) {
		return in_array( strtolower( trim( $value ) ), array( 'on', 'yes', 'true', '1' ), true );
	}
	return ! empty( $value );
}

/**
 * Built-in default front-end strings (keys => English).
 *
 * @return array
 */
function bptm_translation_defaults() {
	return array(
		'company'            => __( 'Company', 'team-section' ),
		'address'            => __( 'Address', 'team-section' ),
		'land_phone'         => __( 'Land Phone', 'team-section' ),
		'cell_phone'         => __( 'Cell Phone', 'team-section' ),
		'email'              => __( 'Email', 'team-section' ),
		'zip_code'           => __( 'Zip Code', 'team-section' ),
		'group'              => __( 'Group', 'team-section' ),
		'tag'                => __( 'Tag', 'team-section' ),
		'location'           => __( 'Location', 'team-section' ),
		'language'           => __( 'Language', 'team-section' ),
		'specialty'          => __( 'Specialty', 'team-section' ),
		'gender'             => __( 'Gender', 'team-section' ),
		'extra_one'          => __( 'Extra One', 'team-section' ),
		'extra_two'          => __( 'Extra Two', 'team-section' ),
		'extra_three'        => __( 'Extra Three', 'team-section' ),
		'extra_four'         => __( 'Extra Four', 'team-section' ),
		'extra_five'         => __( 'Extra Five', 'team-section' ),
		'follow_me_on'       => __( 'Follow Me On', 'team-section' ),
		'skills'             => __( 'Skills', 'team-section' ),
		'read_on'            => __( 'Read On', 'team-section' ),
		'more'               => __( 'More', 'team-section' ),
		'vcard'              => __( 'Download vCard', 'team-section' ),
		'prev'               => __( 'Prev', 'team-section' ),
		'next'               => __( 'Next', 'team-section' ),
		'no_members'         => __( 'No team member found.', 'team-section' ),
	);
}

/**
 * Preference defaults (option 'bptm_settings' without translations).
 *
 * @return array
 */
function bptm_preference_defaults() {
	return array(
		'multilingual'         => false,
		'disable_google_fonts' => false,
		'disable_lazy_load'    => false,
		'lazy_load_class'      => 'skip-lazy',
		'land_phone_link'      => false,
		'cell_phone_link'      => false,
		'email_link'           => false,
		'custom_css'           => '',
	);
}

/**
 * All defaults of option 'bptm_settings' (preferences + translations).
 *
 * @return array
 */
function bptm_option_defaults() {
	return array_merge( bptm_preference_defaults(), bptm_translation_defaults() );
}

/**
 * Get a preference (option 'bptm_settings') merged with defaults.
 *
 * Keys of removed preferences (member slug, single page, ACF …) still saved in the option are
 * returned as stored but no code reads them any more.
 *
 * @param string $key     Option key.
 * @param mixed  $default Fallback when neither saved nor default exists.
 * @return mixed
 */
function bptm_get_option( $key, $default = null ) {
	$saved = get_option( 'bptm_settings', array() );
	if ( is_array( $saved ) && array_key_exists( $key, $saved ) ) {
		return $saved[ $key ];
	}
	$defaults = bptm_option_defaults();
	if ( array_key_exists( $key, $defaults ) ) {
		return $defaults[ $key ];
	}
	return $default;
}

/**
 * Replaceable front-end string.
 *
 * Saved values are read from option 'bptm_settings' (either top-level key or a
 * 'translations' sub-array); multilingual mode always uses the translatable default.
 *
 * @param string $key Translation key.
 * @return string
 */
function bptm_translation( $key ) {
	$defaults = bptm_translation_defaults();
	$default  = isset( $defaults[ $key ] ) ? $defaults[ $key ] : '';

	if ( bptm_is_on( bptm_get_option( 'multilingual', false ) ) ) {
		return (string) apply_filters( 'bptm_translation', $default, $key );
	}

	$saved = get_option( 'bptm_settings', array() );
	$value = '';
	if ( is_array( $saved ) ) {
		if ( isset( $saved['translations'][ $key ] ) && is_string( $saved['translations'][ $key ] ) ) {
			$value = $saved['translations'][ $key ];
		} elseif ( isset( $saved[ $key ] ) && is_string( $saved[ $key ] ) ) {
			$value = $saved[ $key ];
		}
	}
	if ( '' === trim( $value ) ) {
		$value = $default;
	}

	return (string) apply_filters( 'bptm_translation', $value, $key );
}

