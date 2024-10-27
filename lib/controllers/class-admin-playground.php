<?php

namespace QuadLayers\AICP\Controllers;

use QuadLayers\AICP\Models\Admin_Menu_Modules;
class Admin_Playground {

	protected static $instance;

	private function __construct() {
		add_action( 'admin_enqueue_scripts', array( $this, 'register_scripts' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_action( 'admin_bar_menu', array( $this, 'add_link_to_admin_bar' ), 100 );
	}

	public function register_scripts() {

		$content = include QUADLAYERS_AICP_PLUGIN_DIR . 'build/admin-playground/js/index.asset.php';
		/**
		 * Register admin-playground assets
		 */
		wp_register_script(
			'aicp-admin-playground',
			plugins_url( '/build/admin-playground/js/index.js', QUADLAYERS_AICP_PLUGIN_FILE ),
			$content['dependencies'],
			$content['version'],
			true
		);

		wp_register_style(
			'aicp-admin-playground',
			plugins_url( '/build/admin-playground/css/style.css', QUADLAYERS_AICP_PLUGIN_FILE ),
			array(
				'aicp-components',
				'wp-editor',
				'wp-components',
			),
			QUADLAYERS_AICP_PLUGIN_VERSION
		);
	}

	public function add_link_to_admin_bar( $wp_admin_bar ) {
		$admin_menu_modules_model = Admin_Menu_Modules::instance();
		$admin_menu_modules       = $admin_menu_modules_model->get();
		$class_admin_bar          = null !== $admin_menu_modules->get( 'playground_enable' ) && 1 === $admin_menu_modules->get( 'playground_enable' ) ? '' : 'hidden';

		$args = array(
			'id'    => 'wcp_playground_admin_bar_link',
			'title' => esc_html__( 'Playground', 'ai-copilot' ),
			'meta'  => array(
				'class' => $class_admin_bar,
				'title' => esc_html__( 'Playground', 'ai-copilot' ),
			),
		);

		$wp_admin_bar->add_node( $args );
	}


	public function enqueue_scripts() {

		wp_enqueue_script( 'aicp-admin-playground' );
		wp_enqueue_style( 'aicp-admin-playground' );
	}


	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}
}
