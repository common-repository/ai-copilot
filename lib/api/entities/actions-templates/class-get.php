<?php
namespace QuadLayers\AICP\Api\Entities\Actions_Templates;

use QuadLayers\AICP\Models\Action_Templates as Models_Action_Templates;
use QuadLayers\AICP\Api\Entities\Actions_Templates\Base;
/**
 * API_Rest_Actions_Get Class
 */
class Get extends Base {

	protected static $route_path = 'actions';

	public function callback( \WP_REST_Request $request ) {
		try {
			$action_templates = Models_Action_Templates::instance();

			if ( ! $action_templates ) {
				throw new \Exception( esc_html__( 'No actions template found.', 'ai-copilot' ), 404 );
			}

			$actions = array_map(
				function ( $action ) {
				return $action->getProperties(); },
				$action_templates->get_all()
			);

			if ( null !== $actions && 0 !== count( $actions ) ) {
				return $this->handle_response( $actions );
			}

			return $this->handle_response( array() );
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
				'validate_callback' => function ( $param, $request, $key ) {
					return is_numeric( $param );
				},
			),
		);
	}

	public static function get_rest_method() {
		return \WP_REST_Server::READABLE;
	}

	public function get_rest_permission() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return false;
		}
		return true;
	}
}
