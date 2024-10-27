<?php

namespace QuadLayers\AICP\Api\Entities\Admin_Menu\Stats;

use QuadLayers\AICP\Api\Entities\Admin_Menu\Base;
use QuadLayers\AICP\Models\Admin_Menu_Stats;

class Delete extends Base {
	protected static $route_path = 'stats';

	public function callback( \WP_REST_Request $request ) {
		try {

			$state = Admin_Menu_Stats::instance()->delete_all();

			if ( ! $state ) {
				throw new \Exception( esc_html__( 'The statistics could not be deleted.', 'ai-copilot' ), 400 );
			}

			$response = Admin_Menu_Stats::instance()->get();

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
		return \WP_REST_Server::DELETABLE;
	}

	public static function get_rest_args() {
		return array();
	}
}
