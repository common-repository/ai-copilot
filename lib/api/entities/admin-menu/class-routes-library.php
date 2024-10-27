<?php

namespace QuadLayers\AICP\Api\Entities\Admin_Menu;

use QuadLayers\AICP\Api\Entities\Admin_Menu\Modules\Get as Modules_Get;
use QuadLayers\AICP\Api\Entities\Admin_Menu\Modules\Post as Modules_Post;

use QuadLayers\AICP\Api\Entities\Admin_Menu\Services\Get as Services_Get;
use QuadLayers\AICP\Api\Entities\Admin_Menu\Services\Post as Services_Post;

use QuadLayers\AICP\Api\Entities\Admin_Menu\Stats\Get as Stats_Get;
use QuadLayers\AICP\Api\Entities\Admin_Menu\Stats\Create as Stats_Create;
use QuadLayers\AICP\Api\Entities\Admin_Menu\Stats\Delete as Stats_Delete;

use QuadLayers\AICP\Api\Entities\Admin_Menu\Chatbots\Get as Chatbots_Get;
use QuadLayers\AICP\Api\Entities\Admin_Menu\Chatbots\Post as Chatbots_Post;

use QuadLayers\AICP\Api\Route as Route_Interface;

class Routes_Library {
	protected $routes                = array();
	protected static $rest_namespace = 'quadlayers/ai-copilot/admin-menu';
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
		new Modules_Get();
		new Modules_Post();
		new Services_Get();
		new Services_Post();
		new Stats_Get();
		new Stats_Create();
		new Stats_Delete();
		new Chatbots_Get();
		new Chatbots_Post();
	}

	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}
}
