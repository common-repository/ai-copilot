<?php

namespace QuadLayers\AICP\Api\Entities\Admin_Menu\Modules;

use QuadLayers\AICP\Api\Entities\Admin_Menu\Base;
use QuadLayers\AICP\Models\Admin_Menu_Modules;

class Get extends Base {
	protected static $route_path = 'modules';

	public function callback( \WP_REST_Request $request ) {
		try {
			$admin_menu_modules_model = Admin_Menu_Modules::instance();

			$admin_menu_modules = $admin_menu_modules_model->get()->getProperties();

			return $this->handle_response( $admin_menu_modules );
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
