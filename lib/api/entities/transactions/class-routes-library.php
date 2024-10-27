<?php

namespace QuadLayers\AICP\Api\Entities\Transactions;

use QuadLayers\AICP\Api\Route as Route_Interface;

use QuadLayers\AICP\Api\Entities\Transactions\Get;
use QuadLayers\AICP\Api\Entities\Transactions\Create;
use QuadLayers\AICP\Api\Entities\Transactions\Delete;

class Routes_Library {
	protected $routes                = array();
	protected static $rest_namespace = 'quadlayers/ai-copilot';
	protected static $instance;

	private function __construct() {
		add_action( 'init', array( $this, '_rest_init' ) );
	}

	public static function get_namespace() {
		return self::$rest_namespace;
	}

	public function get_routes( $route_path = null ) {
		if ( ! $route_path ) {
			return $this->routes;
		}

		if ( isset( $this->routes[ $route_path ] ) ) {
			return $this->routes[ $route_path ];
		}
	}

	public function register( Route_Interface $instance ) {
		$this->routes[ $instance::get_name() ] = $instance;
	}

	public function _rest_init() {
		new Get();
		new Create();
		new Delete();
	}

	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}
}
