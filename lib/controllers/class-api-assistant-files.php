<?php
namespace QuadLayers\AICP\Controllers;

use QuadLayers\AICP\Api\Services\OpenAI\Routes_Library as Services_OpenAI_Routes_Library;

class Api_Assistant_Files {

	protected static $instance;

	private function __construct() {
		add_action( 'admin_enqueue_scripts', array( $this, 'register_scripts' ) );
	}

	public function register_scripts() {

		$api_assistant_file = include QUADLAYERS_AICP_PLUGIN_DIR . 'build/api/assistant-files/js/index.asset.php';

		wp_register_script(
			'aicp-api-assistant-files',
			plugins_url( '/build/api/assistant-files/js/index.js', QUADLAYERS_AICP_PLUGIN_FILE ),
			$api_assistant_file['dependencies'],
			$api_assistant_file['version'],
			true
		);

		wp_localize_script(
			'aicp-api-assistant-files',
			'aicpApiAssistantFiles',
			array(
				'QUADLAYERS_AICP_API_ASSISTANT_FILES_REST_ROUTES' => $this->get_endpoints(),
			)
		);
	}

	private function get_endpoints() {

		$route_library = Services_OpenAI_Routes_Library::instance();
		$endpoints     = $route_library->get_routes();

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
