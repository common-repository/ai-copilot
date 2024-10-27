<?php

namespace QuadLayers\AICP\Controllers;

use QuadLayers\AICP\Helpers;
use QuadLayers\AICP\Models\Admin_Menu_Modules;

class Admin_Bulk {

	protected static $instance;

	private function __construct() {
		add_action( 'admin_enqueue_scripts', array( $this, 'register_scripts' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
	}

	public function register_scripts() {
		$bulk = include QUADLAYERS_AICP_PLUGIN_DIR . 'build/admin-bulk/js/index.asset.php';
		/**
		 * Register admin-bulk assets
		 */
		wp_register_script(
			'aicp-admin-bulk',
			plugins_url( '/build/admin-bulk/js/index.js', QUADLAYERS_AICP_PLUGIN_FILE ),
			$bulk['dependencies'],
			$bulk['version'],
			true
		);

		$post_type_object = Helpers::get_admin_screen_post_type();

		if ( ! isset( $post_type_object->labels->name ) ) {
			return;
		}

		$post_type                  = $post_type_object->name;
		$post_type_name             = $post_type_object->labels->name;
		$post_type_rest             = $post_type_object->rest_base;
		$post_type_use_block_editor = use_block_editor_for_post_type( $post_type );

		wp_localize_script(
			'aicp-admin-bulk',
			'aicpAdminBulk',
			array(
				'AICP_POST_TYPE_USE_BLOCK_EDITOR' => $post_type_use_block_editor,
				'AICP_POST_TYPE_REST'             => $post_type_rest,
				'AICP_POST_TYPE_NAME'             => $post_type_name,
				'AICP_POST_TYPE'                  => $post_type,
			)
		);
	}

	public function enqueue_scripts() {

		$admin_menu_modules = Admin_Menu_Modules::instance()->get();

		$post_types = array();

		if ( ! $admin_menu_modules->get( 'content_enable' ) ) {
			return;
		}

		$post_types = $admin_menu_modules->get( 'content_post_types' );

		if ( empty( $post_types ) ) {
			return;
		}

		foreach ( $post_types as &$post_type ) {
			$post_type = 'edit-' . $post_type;
		}

		$is_valid_post_type = in_array( get_current_screen()->id, $post_types );

		if ( ! $is_valid_post_type ) {
			return;
		}

		wp_enqueue_script( 'aicp-admin-bulk' );
	}


	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}
}
