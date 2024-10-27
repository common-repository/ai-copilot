<?php

namespace QuadLayers\AICP\Controllers;

use QuadLayers\AICP\Api\Entities\Admin_Menu\Routes_Library as Admin_Menu_Routes_Library;

class Api_Admin_Menu {

	protected static $instance;

	private function __construct() {
		add_action( 'admin_enqueue_scripts', array( $this, 'register_scripts' ) );
	}

	public function register_scripts() {

		$menu = include QUADLAYERS_AICP_PLUGIN_DIR . 'build/api/admin-menu/js/index.asset.php';

		wp_register_script(
			'aicp-api-admin-menu',
			plugins_url( '/build/api/admin-menu/js/index.js', QUADLAYERS_AICP_PLUGIN_FILE ),
			$menu['dependencies'],
			$menu['version'],
			true
		);

		$user_roles  = wp_roles();
		$roles_names = $user_roles->get_names();

		wp_localize_script(
			'aicp-api-admin-menu',
			'aicpApiAdminMenu',
			array(
				'QUADLAYERS_AICP_API_ADMIN_MENU_REST_ROUTES' => $this->get_endpoints(),
				'QUADLAYERS_AICP_USER_ROLES' => $roles_names,
			)
		);
	}

	private function get_endpoints() {
		$route_library   = Admin_Menu_Routes_Library::instance();
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
