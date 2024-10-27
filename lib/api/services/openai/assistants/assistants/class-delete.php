<?php

namespace QuadLayers\AICP\Api\Services\OpenAI\Assistants\Assistants;

use QuadLayers\AICP\Api\Services\OpenAI\Base;
use QuadLayers\AICP\Models\Assistants as Models_Assistant;
use QuadLayers\AICP\Services\OpenAI\Assistants\Assistants\Delete as API_Fetch_Delete_Assistant_OpenAi;

class Delete extends Base {
	protected static $route_path = 'assistants';

	public function callback( \WP_REST_Request $request ) {
		try {
			$assistant_id = $request->get_param( 'assistant_id' );

			$assistant = Models_Assistant::instance()->get( $assistant_id );

			if ( ! $assistant ) {
				throw new \Exception( esc_html__( 'The assistant was not found.', 'ai-copilot' ), 404 );
			}

			if ( ! empty( $assistant->get( 'openai_id' ) ) && $assistant->get( 'openai_id' ) !== 'asst_2JccEFYWzYh7y2QKR4a6YMf3' ) {
				( new API_Fetch_Delete_Assistant_OpenAi() )->get_data(
					array(
						'openai_id' => $assistant->get( 'openai_id' ),
					)
				);
			}

			$response = Models_Assistant::instance()->delete( $assistant->get( 'assistant_id' ) );

			if ( ! $response ) {
				throw new \Exception( esc_html__( 'Cannot delete the assistant; assistant ID not found.', 'ai-copilot' ), 404 );
			}

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
		return array(
			'assistant_id' => array(
				'required'          => true,
				'validate_callback' => function ( $param, $request, $key ) {
					if ( ! is_numeric( $param ) ) {
						return new \WP_Error( 400, __( 'Assistant id not found.', 'ai-copilot' ) );
					}
					return true;
				},
			),
		);
	}
}
