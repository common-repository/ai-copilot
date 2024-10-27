<?php
namespace QuadLayers\AICP\Api\Entities\Actions_Templates;

use QuadLayers\AICP\Models\Action_Templates as Models_Action_Templates;
use QuadLayers\AICP\Api\Entities\Actions_Templates\Base;

/**
 * API_Rest_Actions_Delete Class
 */
class Delete extends Base {

	protected static $route_path = 'actions';

	public function callback( \WP_REST_Request $request ) {

		try {
			$action_id = $request->get_param( 'action_id' );

			$action_templates = Models_Action_Templates::instance();
			$success          = $action_templates->delete( $action_id );

			if ( ! $success ) {
				throw new \Exception( esc_html__( 'Cannot delete the action: action ID not found.', 'ai-copilot' ), 404 );
			}

			return $this->handle_response( $success );
		} catch ( \Throwable  $error ) {
			return $this->handle_response(
				array(
					'code'    => $error->getCode(),
					'message' => $error->getMessage(),
				)
			);
		}
	}

	public static function get_rest_args() {
		return array(
			'action_id' => array(
				'required'          => true,
				'validate_callback' => function ( $param, $request, $key ) {
					if ( ! is_numeric( $param ) ) {
						return new \WP_Error( 400, __( 'Action ID not found.', 'ai-copilot' ) );
					}
					return true;
				},
			),
		);
	}

	public static function get_rest_method() {
		return \WP_REST_Server::DELETABLE;
	}

	public function get_rest_permission() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return false;
		}
		return true;
	}
}
