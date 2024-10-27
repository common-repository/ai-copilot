<?php

namespace QuadLayers\AICP\Controllers;

use QuadLayers\AICP\Helpers;
use QuadLayers\AICP\Models\Admin_Menu_Modules;

class Admin_Content {

	protected static $instance;

	private function __construct() {
		add_action( 'admin_enqueue_scripts', array( $this, 'register_scripts' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
	}

	public function register_scripts() {
		$content = include QUADLAYERS_AICP_PLUGIN_DIR . 'build/admin-content/js/index.asset.php';
		/**
		 * Register admin-content assets
		 */
		wp_register_script(
			'aicp-admin-content',
			plugins_url( '/build/admin-content/js/index.js', QUADLAYERS_AICP_PLUGIN_FILE ),
			$content['dependencies'],
			$content['version'],
			true
		);

		wp_register_style(
			'aicp-admin-content',
			plugins_url( '/build/admin-content/css/style.css', QUADLAYERS_AICP_PLUGIN_FILE ),
			array(
				'aicp-components',
				'wp-components',
			),
			QUADLAYERS_AICP_PLUGIN_VERSION
		);
	}

	public function enqueue_scripts() {
		$admin_menu_modules = Admin_Menu_Modules::instance()->get();
		$post_types         = array();

		if ( ! $admin_menu_modules->get( 'content_enable' ) ) {
			return;
		}

		$post_types = $admin_menu_modules->get( 'content_post_types' );

		if ( empty( $post_types ) ) {
			return;
		}

		$is_valid_post_type = Helpers::is_admin_post_type( $post_types );

		if ( ! $is_valid_post_type ) {
			return;
		}

		wp_enqueue_script( 'aicp-admin-content' );
		wp_enqueue_style( 'aicp-admin-content' );
	}


	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}
}
