<?php

namespace QuadLayers\AICP\Api\Entities\Content_Templates;

use QuadLayers\AICP\Models\Content_Templates as Models_Templates;
use QuadLayers\AICP\Api\Entities\Content_Templates\Base;
use WP_REST_Server;

class Create extends Base {

	protected static $route_path = 'templates';

	public function callback( \WP_REST_Request $request ) {
		try {
			$data = json_decode( $request->get_body(), true );

			$template = Models_Templates::instance()->create( $data )->getProperties();

			if ( ! $template ) {
				throw new \Exception( esc_html__( 'Unknown error.', 'ai-copilot' ), 500 );
			}

			return $this->handle_response( $template );

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
			'template_label'  => array(
				'required'          => true,
				'validate_callback' => function ( $param, $request, $key ) {
					if ( empty( strlen( trim( $param ) ) ) ) {
						return new \WP_Error( 400, __( 'The label field is empty.', 'ai-copilot' ) );
					}
					return true;
				},
			),
			'prompt_title'    => array(
				'required'          => true,
				'validate_callback' => function ( $param, $request, $key ) {
					if ( empty( strlen( trim( $param ) ) ) ) {
						return new \WP_Error( 400, __( 'The prompt title field is empty.', 'ai-copilot' ) );
					}
					return true;
				},
			),
			'prompt_content'  => array(
				'required'          => true,
				'validate_callback' => function ( $param, $request, $key ) {
					if ( empty( strlen( trim( $param ) ) ) ) {
						return new \WP_Error( 400, __( 'The prompt content field is empty.', 'ai-copilot' ) );
					}
					return true;
				},
			),
			'prompt_sections' => array(
				'required'          => true,
				'validate_callback' => function ( $param, $request, $key ) {
					if ( empty( strlen( trim( $param ) ) ) ) {
						return new \WP_Error( 400, __( 'The prompt sections field is empty.', 'ai-copilot' ) );
					}
					return true;
				},
			),
			'prompt_excerpt'  => array(
				'required'          => true,
				'validate_callback' => function ( $param, $request, $key ) {
					if ( empty( strlen( trim( $param ) ) ) ) {
						return new \WP_Error( 400, __( 'The prompt excerpt field is empty.', 'ai-copilot' ) );
					}
					return true;
				},
			),
			'prompt_tags'     => array(
				'required'          => true,
				'validate_callback' => function ( $param, $request, $key ) {
					if ( empty( strlen( trim( $param ) ) ) ) {
						return new \WP_Error( 400, __( 'The prompt tags field is empty.', 'ai-copilot' ) );
					}
					return true;
				},
			),
		);
	}

	public static function get_rest_method() {
		return WP_REST_Server::CREATABLE;
	}

	public function get_rest_permission() {
		return current_user_can( 'manage_options' );
	}
}
