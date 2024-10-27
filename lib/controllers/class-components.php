<?php

namespace QuadLayers\AICP\Controllers;

class Components {

	protected static $instance;

	private function __construct() {
		add_action( 'admin_enqueue_scripts', array( $this, 'register_scripts' ) );
	}

	public function register_scripts() {
		$components = include QUADLAYERS_AICP_PLUGIN_DIR . 'build/components/js/index.asset.php';

		/**
		 * Register components assets
		 */
		wp_register_script(
			'aicp-components',
			plugins_url( '/build/components/js/index.js', QUADLAYERS_AICP_PLUGIN_FILE ),
			$components['dependencies'],
			$components['version'],
			true
		);

		wp_register_style(
			'aicp-components',
			plugins_url( '/build/components/css/style.css', QUADLAYERS_AICP_PLUGIN_FILE ),
			array(
				'wp-components',
				'media-views',
			),
			QUADLAYERS_AICP_PLUGIN_VERSION
		);
	}

	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}
}
