<?php

namespace QuadLayers\AICP\Api\Entities\Content_Templates;

use QuadLayers\AICP\Api\Route as Route_Interface;

use QuadLayers\AICP\Api\Entities\Content_Templates\Get as Content_Template_Get;
use QuadLayers\AICP\Api\Entities\Content_Templates\Create as Content_Template_Create;
use QuadLayers\AICP\Api\Entities\Content_Templates\Edit as Content_Template_Edit;
use QuadLayers\AICP\Api\Entities\Content_Templates\Delete as Content_Template_Delete;

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
		new Content_Template_Get();
		new Content_Template_Create();
		new Content_Template_Edit();
		new Content_Template_Delete();
	}

	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}
}
