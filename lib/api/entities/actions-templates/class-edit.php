<?php
namespace QuadLayers\AICP\Api\Entities\Actions_Templates;

use QuadLayers\AICP\Models\Action_Templates as Models_Action_Templates;
use QuadLayers\AICP\Api\Entities\Actions_Templates\Base;

/**
 * API_Rest_Actions_Edit Class
 */
class Edit extends Base {

	protected static $route_path = 'actions';

	public function callback( \WP_REST_Request $request ) {

		try {
			$data = json_decode( $request->get_body(), true );

			$action = Models_Action_Templates::instance()->update( $data['action_id'], $data )->getProperties();

			if ( ! $action ) {
				throw new \Exception( esc_html__( 'Unknown error.', 'ai-copilot' ), 500 );
			}

			return $this->handle_response( $action );
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
			'prompt_system' => array(
				'required'          => true,
				'validate_callback' => function ( $param, $request, $key ) {
					if ( empty( strlen( trim( $param ) ) ) ) {
						return new \WP_Error( 400, __( 'Prompt system is empty.', 'ai-copilot' ) );
					}
					return true;
				},
			),
			'prompt_user'   => array(
				'required'          => true,
				'validate_callback' => function ( $param, $request, $key ) {
					if ( empty( strlen( trim( $param ) ) ) ) {
						return new \WP_Error( 400, __( 'The prompt for the user is empty.', 'ai-copilot' ) );
					}
					return true;
				},
			),
			'action_label'  => array(
				'required'          => true,
				'validate_callback' => function ( $param, $request, $key ) {
					if ( empty( strlen( trim( $param ) ) ) ) {
						return new \WP_Error( 400, __( 'The label field is empty.', 'ai-copilot' ) );
					}
					return true;
				},
			),
			'action_id'     => array(
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
		return \WP_REST_Server::EDITABLE;
	}

	public function get_rest_permission() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return false;
		}
		return true;
	}
}
