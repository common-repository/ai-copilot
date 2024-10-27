<?php

namespace QuadLayers\AICP\Api\Services\OpenAI\Assistants\Threads;

use QuadLayers\AICP\Api\Services\OpenAI\Base;
use QuadLayers\AICP\Services\OpenAI\Assistants\Threads\Post as API_Fetch_Post_Assistant_Thread_OpenAi;
use QuadLayers\AICP\Models\Assistants_Threads as Models_Assistant_Thread;
use QuadLayers\AICP\Models\Virtual\Assistant_Threads as Models_Virtual_Assistant_Threads;

class Post extends Base {
	protected static $route_path = 'assistants/threads';

	public function callback( \WP_REST_Request $request ) {
		try {
			$data = json_decode( $request->get_body(), true );

			$entity = Models_Virtual_Assistant_Threads::instance()->create( $data );

			if ( empty( $entity->get( 'openai_id' ) ) ) {
				throw new \Exception( esc_html__( 'Assistant OpenAI ID not found.', 'ai-copilot' ), 404 );
			}

			$response = ( new API_Fetch_Post_Assistant_Thread_OpenAi() )->get_data(
				array_merge(
					$entity->getProperties(),
					array(
						'assistant_openai_id' => $entity->get( 'openai_id' ),
					)
				)
			);

			if ( isset( $response['code'] ) ) {
				throw new \Exception( $response['message'], $response['code'] );
			}

			$assistant_thread = Models_Assistant_Thread::instance()->create( array_merge( $entity->getProperties(), $response ) );

			if ( ! $assistant_thread ) {
				throw new \Exception( esc_html__( 'Unknown error.', 'ai-copilot' ), 500 );
			}
			return $this->handle_response( (array) $assistant_thread );
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
		return array(
			'assistant_id' => array(
				'required'          => true,
				'validate_callback' => function ( $param, $request, $key ) {
					if ( ! is_numeric( $param ) ) {
						return new \WP_Error( 400, __( 'Assistant ID not found.', 'ai-copilot' ) );
					}
					return true;
				},
			),
			'openai_id'    => array(
				'required'          => true,
				'validate_callback' => function ( $param, $request, $key ) {
					if ( ! is_string( $param ) ) {
						return new \WP_Error( 400, __( 'Assistant OpenAI ID not found.', 'ai-copilot' ) );
					}
					return true;
				},
			),
		);
	}

	public function get_rest_permission() {
		return true;
	}
}
