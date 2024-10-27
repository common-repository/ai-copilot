<?php

namespace QuadLayers\AICP\Controllers;

use QuadLayers\AICP\Api\Services\OpenAI\Routes_Library as Services_OpenAI_Routes_Library;
use QuadLayers\AICP\Api\Services\Pexels\Routes_Library as Services_Pexels_Routes_Library;

class Api_Services {

	protected static $instance;

	private function __construct() {
		add_action( 'admin_enqueue_scripts', array( $this, 'register_scripts' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'register_scripts' ) );
	}
	public function register_scripts() {
		$fetch = include QUADLAYERS_AICP_PLUGIN_DIR . 'build/api/services/js/index.asset.php';

		/**
		 * Register api assets
		 */
		wp_register_script(
			'aicp-api-services',
			plugins_url( '/build/api/services/js/index.js', QUADLAYERS_AICP_PLUGIN_FILE ),
			$fetch['dependencies'],
			$fetch['version'],
			true
		);

		wp_localize_script(
			'aicp-api-services',
			'aicpApiServices',
			array(
				'QUADLAYERS_AICP_API_SERVICES_OPENAI_ROUTES' => $this->get_openai_endpoints(),
				'QUADLAYERS_AICP_API_SERVICES_PEXELS_ROUTES' => $this->get_pexels_endpoints(),
			)
		);
	}

	private function get_openai_endpoints() {
		$endpoints_paths = array();

		$openai_routes_library = Services_OpenAI_Routes_Library::instance();
		$openai_endpoints      = $openai_routes_library->get_routes();

		foreach ( $openai_endpoints as $endpoint ) {
			$path = $endpoint::get_rest_route();
			if ( ! isset( $endpoints_paths[ $path ] ) ) {
				$endpoints_paths[ $path ] = $endpoint::get_rest_path();
			}
		}

		return $endpoints_paths;
	}

	private function get_pexels_endpoints() {
		$endpoints_paths = array();

		$pexels_routes_library = Services_Pexels_Routes_Library::instance();
		$pexels_endpoints      = $pexels_routes_library->get_routes();

		foreach ( $pexels_endpoints as $endpoint ) {
			$path = $endpoint::get_rest_route();
			if ( ! isset( $endpoints_paths[ $path ] ) ) {
				$endpoints_paths[ $path ] = $endpoint::get_rest_path();
			}
		}

		return $endpoints_paths;
	}

	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}
}
