<?php

namespace QuadLayers\AICP\Controllers;

use QuadLayers\AICP\Api\Entities\Actions_Templates\Routes_Library as Actions_Templates_Routes_Library;

class Api_Actions_Templates {

	protected static $instance;

	private function __construct() {
		add_action( 'admin_enqueue_scripts', array( $this, 'register_scripts' ) );
	}

	public function register_scripts() {

		$actions = include QUADLAYERS_AICP_PLUGIN_DIR . 'build/api/action-templates/js/index.asset.php';

		wp_register_script(
			'aicp-api-action-templates',
			plugins_url( '/build/api/action-templates/js/index.js', QUADLAYERS_AICP_PLUGIN_FILE ),
			$actions['dependencies'],
			$actions['version'],
			true
		);

		wp_localize_script(
			'aicp-api-action-templates',
			'aicpApiActionTemplates',
			array(
				'QUADLAYERS_AICP_API_ACTION_TEMPLATES_REST_ROUTES' => $this->get_endpoints(),
			)
		);
	}

	private function get_endpoints() {
		$route_library   = Actions_Templates_Routes_Library::instance();
		$endpoints       = $route_library->get_routes();
		$endpoints_array = array();

		foreach ( $endpoints as $endpoint ) {

			$endpoint_key = str_replace( '/', '_', $endpoint::get_rest_route() );

			if ( ! isset( $endpoints_array[ $endpoint_key ] ) ) {

				$endpoints_array[ $endpoint_key ] = $endpoint::get_rest_path();

			}
		}

		return $endpoints_array;
	}


	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}
}
