<?php

namespace QuadLayers\AICP\Api\Entities\Admin_Menu\Stats;

use QuadLayers\AICP\Api\Entities\Admin_Menu\Base;
use QuadLayers\AICP\Models\Admin_Menu_Stats;

class Create extends Base {
	protected static $route_path = 'stats';

	public function callback( \WP_REST_Request $request ) {
		try {

			$body = json_decode( $request->get_body(), true );

			$admin_menu_stats_model = Admin_Menu_Stats::instance();

			$model_defaults_fields = array(
				'api_type',
				'api_service',
				'api_service_model',
				'consumer_module',
				'consumer_user',
				'consumer_user_role',
				'start_date',
				'end_date',
				'order',
				'order_by',
			);

			foreach ( $model_defaults_fields as $field ) {
				if ( ! isset( $body[ $field ] ) ) {
					throw new \Exception( sprintf( esc_html__( 'The following field is missing: %s.', 'ai-copilot' ), $field ), 400 );
				}
			}

			$status = $admin_menu_stats_model->save( $body );

			return $this->handle_response( $status );
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
		return \WP_REST_Server::CREATABLE;
	}

	public static function get_rest_args() {
		return array();
	}
}
