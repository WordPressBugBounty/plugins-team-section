<?php

namespace TSB;

class CustomColumn {
    function __construct() {
        add_filter('manage_tsb_posts_columns', [$this, 'manageTSBPostsColumns'], 10);
		add_action('manage_tsb_posts_custom_column', [$this, 'manageTSBPostsCustomColumns'], 10, 2);
	}

    function manageTSBPostsColumns($defaults){
		unset($defaults['date']);
		$defaults['shortcode'] = 'ShortCode';
		$defaults['date'] = 'Date';
		return $defaults;
	}

	function manageTSBPostsCustomColumns($column_name, $post_ID){
		if ($column_name == 'shortcode') {
			echo '<div class="bPlAdminShortcode" id="bPlAdminShortcode-' . esc_attr($post_ID) . '">
				<input value="[tsb id=' . esc_attr($post_ID) . ']" onclick="copyBPlAdminShortcode(\'' . esc_attr($post_ID) . '\')">
				<span class="tooltip">' . esc_html__('Copy To Clipboard', 'team-section') . '</span>
			</div>';
		}
	}

}
