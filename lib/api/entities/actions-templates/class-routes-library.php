<?php

namespace QuadLayers\AICP\Api\Entities\Actions_Templates;

use QuadLayers\AICP\Api\Entities\Actions_Templates\Get as Actions_Get;
use QuadLayers\AICP\Api\Entities\Actions_Templates\Create as Actions_Create;
use QuadLayers\AICP\Api\Entities\Actions_Templates\Delete as Actions_Delete;
use QuadLayers\AICP\Api\Entities\Actions_Templates\Edit as Actions_Edit;

use QuadLayers\AICP\Api\Route as Route_Interface;

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
		new Actions_Get();
		new Actions_Create();
		new Actions_Delete();
		new Actions_Edit();
	}

	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}
}
