<?php

namespace QuadLayers\AICP\Controllers;

use QuadLayers\AICP\Helpers;
use QuadLayers\AICP\Models\Admin_Menu_Modules;

class Admin_Actions {

	protected static $instance;

	private function __construct() {
		add_action( 'admin_enqueue_scripts', array( $this, 'register_scripts' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_filter( 'mce_buttons', array( $this, 'add_custom_tinymce_button' ) );
		add_filter( 'mce_external_plugins', array( $this, 'add_custom_tinymce_plugin' ) );
	}

	public function register_scripts() {
		$actions = include QUADLAYERS_AICP_PLUGIN_DIR . 'build/admin-actions/js/index.asset.php';
		/**
		 * Register admin-actions assets
		 */
		wp_register_script(
			'aicp-admin-actions',
			plugins_url( '/build/admin-actions/js/index.js', QUADLAYERS_AICP_PLUGIN_FILE ),
			$actions['dependencies'],
			$actions['version'],
			true
		);

		wp_register_style(
			'aicp-admin-actions',
			plugins_url( '/build/admin-actions/css/style.css', QUADLAYERS_AICP_PLUGIN_FILE ),
			array(
				'aicp-components',
				'wp-components',
			),
			QUADLAYERS_AICP_PLUGIN_VERSION
		);
	}

	public function enqueue_scripts() {
		if ( ! $this->is_valid_post_type() ) {
			return;
		}
		wp_enqueue_script( 'aicp-admin-actions' );
		wp_enqueue_style( 'aicp-admin-actions' );
	}

	public function add_custom_tinymce_button( $buttons ) {
		if ( ! $this->is_valid_post_type() ) {
			return $buttons;
		}
		// Add buttons for AI Copilot and Image Generator.
		array_push( $buttons, 'aicp-ai-copilot', 'aicp-image-generator' );
		return $buttons;
	}

	public function add_custom_tinymce_plugin( $plugins ) {
		if ( ! $this->is_valid_post_type() ) {
			return $plugins;
		}
		// Register plugins for AI Copilot and Image Generator.
		$plugins['aicp-ai-copilot']      = plugins_url( '/build/admin-actions/js/index.js', QUADLAYERS_AICP_PLUGIN_FILE );
		$plugins['aicp-image-generator'] = plugins_url( '/build/admin-actions/js/index.js', QUADLAYERS_AICP_PLUGIN_FILE );
		return $plugins;
	}


	private function is_valid_post_type() {
		$admin_menu_modules = Admin_Menu_Modules::instance()->get();
		$post_types         = array();

		if ( ! $admin_menu_modules->get( 'actions_enable' ) ) {
			return;
		}

		$post_types = $admin_menu_modules->get( 'actions_post_types' );

		if ( empty( $post_types ) ) {
			return;
		}

		$is_valid_post_type = Helpers::is_admin_post_type( $post_types );

		if ( ! $is_valid_post_type ) {
			return;
		}

		return true;
	}


	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}
}
