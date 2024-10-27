<?php

namespace QuadLayers\AICP\Controllers;

use QuadLayers\AICP\Api\Entities\Transactions\Routes_Library as Transactions_Routes_Library;

class Api_Transactions {

	protected static $instance;

	private function __construct() {
		add_action( 'admin_enqueue_scripts', array( $this, 'register_scripts' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'register_scripts' ) );
	}

	public function register_scripts() {

		$menu = include QUADLAYERS_AICP_PLUGIN_DIR . 'build/api/transactions/js/index.asset.php';

		wp_register_script(
			'aicp-api-transactions',
			plugins_url( '/build/api/transactions/js/index.js', QUADLAYERS_AICP_PLUGIN_FILE ),
			$menu['dependencies'],
			$menu['version'],
			true
		);

		wp_localize_script(
			'aicp-api-transactions',
			'aicpApiTransactions',
			array(
				'QUADLAYERS_AICP_API_TRANSACTIONS_REST_ROUTES' => $this->get_endpoints(),
			)
		);
	}

	private function get_endpoints() {
		$route_library   = Transactions_Routes_Library::instance();
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
