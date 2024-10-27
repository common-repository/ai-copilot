<?php

namespace QuadLayers\AICP;

use QuadLayers\AICP\Models\Admin_Menu_Services;

final class Helpers {

	public static function get_admin_screen_post_type() {
		$screen = get_current_screen();
		if ( ! isset( $screen->post_type ) ) {
			return;
		}
		$post_type        = $screen->post_type;
		$post_type_object = get_post_type_object( $post_type );
		if ( ! $post_type_object ) {
			return;
		}
		return $post_type_object;
	}

	public static function is_admin_post_type( $post_types = array() ) {
		global $pagenow;
		// Check that we're in the admin area and on a post type edit page.
		if ( ! is_admin() || ! ( 'post.php' === $pagenow || 'post-new.php' === $pagenow || 'edit.php' === $pagenow ) ) {
			return false;
		}
		$screen = get_current_screen();
		// Check that we're editing one of the specified post types or all post types.
		if ( ! empty( $post_types ) && ! in_array( $screen->post_type, $post_types, true ) ) {
			return false;
		}
		return true;
	}

	public static function get_valid_post_types() {
		$post_types = get_post_types();
		$filtered   = array();
		foreach ( $post_types as $post_type ) {
			$post_type_object   = get_post_type_object( $post_type );
			$has_editor_support = post_type_supports( $post_type, 'editor' );
			if ( $has_editor_support && $post_type_object->show_ui && 'wp_block' !== $post_type && 'wp_navigation' !== $post_type ) {
				$filtered[] = $post_type_object;
			}
		}
		return $filtered;
	}

	public static function get_max_execution_time() {
		return ini_get( 'max_execution_time' );
	}

	public static function get_timeout() {
		$max_execution_time = self::get_max_execution_time();
		$timeout            = min( $max_execution_time - 5, 240 );
		return $timeout;
	}

	public static function wait( int $millisecond = 0 ) {
		if ( 0 !== $millisecond ) {
			$seconds      = (int) ( $millisecond / 1000 );
			$nano_seconds = ( $millisecond % 1000 ) * 1000000;
			time_nanosleep( $seconds, $nano_seconds );
		}
	}

	public static function is_update_required() {
		return defined( 'QUADLAYERS_AICP_PRO_PLUGIN_VERSION' ) && ( version_compare( QUADLAYERS_AICP_PRO_PLUGIN_VERSION, QUADLAYERS_AICP_PRO_MIN_PLUGIN_VERSION, '<' ) || version_compare( QUADLAYERS_AICP_PRO_PLUGIN_VERSION, '1.2.0', '<' ) );
	}

	public static function is_api_key_required() {
		return ! Admin_Menu_Services::instance()->get()->get( 'openai_api_key' );
	}
}
