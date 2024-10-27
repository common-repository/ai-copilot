<?php
namespace QuadLayers\AICP\Api\Entities\Content_Templates;

use QuadLayers\AICP\Models\Content_Templates as Models_Templates;
use QuadLayers\AICP\Api\Entities\Content_Templates\Base;

/**
 * API_Rest_Templates_Delete Class
 */
class Delete extends Base {

	protected static $route_path = 'templates';

	public function callback( \WP_REST_Request $request ) {

		try {
			$template_id = $request->get_param( 'template_id' );

			$success = Models_Templates::instance()->delete( $template_id );

			if ( ! $success ) {
				throw new \Exception( esc_html__( 'Cannot delete the template; template ID not found.', 'ai-copilot' ), 404 );
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
			'template_id' => array(
				'required'          => true,
				'validate_callback' => function ( $param, $request, $key ) {
					if ( ! is_numeric( $param ) ) {
						return new \WP_Error( 400, __( 'Template id not found.', 'ai-copilot' ) );
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
