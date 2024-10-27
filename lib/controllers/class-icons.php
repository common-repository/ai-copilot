<?php

namespace QuadLayers\AICP\Controllers;

class Icons {

	protected static $instance;

	private function __construct() {
		add_action( 'wp_enqueue_scripts', array( $this, 'register_scripts' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'register_scripts' ) );
	}

	public function register_scripts() {
		$icons = include QUADLAYERS_AICP_PLUGIN_DIR . 'build/icons/js/index.asset.php';

		/**
		 * Register icons assets
		 */
		wp_register_script(
			'aicp-icons',
			plugins_url( '/build/icons/js/index.js', QUADLAYERS_AICP_PLUGIN_FILE ),
			$icons['dependencies'],
			$icons['version'],
			true
		);
	}

	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}
}
