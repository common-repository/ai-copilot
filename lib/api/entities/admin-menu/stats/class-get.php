<?php

namespace QuadLayers\AICP\Api\Entities\Admin_Menu\Stats;

use QuadLayers\AICP\Api\Entities\Admin_Menu\Base;
use QuadLayers\AICP\Models\Admin_Menu_Stats;

class Get extends Base {
	protected static $route_path = 'stats';

	public function callback( \WP_REST_Request $request ) {
		try {

			$admin_menu_stats_model = Admin_Menu_Stats::instance();

			$response = $admin_menu_stats_model->get()->getProperties();

			return $this->handle_response( $response );
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
