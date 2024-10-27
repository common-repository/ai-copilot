<?php

namespace QuadLayers\AICP\Controllers;

class Hooks {

	protected static $instance;

	private function __construct() {
		add_action( 'admin_enqueue_scripts', array( $this, 'register_scripts' ) );
	}

	public function register_scripts() {
		$hooks = include QUADLAYERS_AICP_PLUGIN_DIR . 'build/hooks/js/index.asset.php';

		/**
		 * Register hooks assets
		 */
		wp_register_script(
			'aicp-hooks',
			plugins_url( '/build/hooks/js/index.js', QUADLAYERS_AICP_PLUGIN_FILE ),
			$hooks['dependencies'],
			$hooks['version'],
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
