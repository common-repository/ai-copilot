<?php

namespace QuadLayers\AICP\Api\Entities\Admin_Menu\Services;

use QuadLayers\AICP\Api\Entities\Admin_Menu\Base;
use QuadLayers\AICP\Models\Admin_Menu_Services;

class Get extends Base {
	protected static $route_path = 'services';

	public function callback( \WP_REST_Request $request ) {
		try {
			$admin_menu_services = Admin_Menu_Services::instance();

			$settings = $admin_menu_services->get()->getProperties();

			return $this->handle_response( $settings );
		} catch ( \Throwable $error ) {
			return $this->handle_response(
				array(
					'code'    => $error->getCode(),
					'message' => $error->getMessage(),
				)
			);
		}
	}

	public static function get_rest_method() {
		return \WP_REST_Server::READABLE;
	}

	public static function get_rest_args() {
		return array();
	}
}
