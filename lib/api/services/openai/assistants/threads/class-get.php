<?php

namespace QuadLayers\AICP\Api\Services\OpenAI\Assistants\Threads;

use QuadLayers\AICP\Api\Services\OpenAI\Base;
use QuadLayers\AICP\Models\Assistants_Threads as Models_Assistant_Thread;

class Get extends Base {
	protected static $route_path = 'assistants/threads';

	public function callback( \WP_REST_Request $request ) {
		try {

			$assistant_thread_id = $request->get_param( 'openai_id' );

			$response = array();

			if ( ! is_numeric( $assistant_thread_id ) ) {
				$response = Models_Assistant_Thread::instance()->get_all();
			} else {
				$response = Models_Assistant_Thread::instance()->get_by( 'ID', 'DESC', 0, 0, array( array( 'openai_id', $assistant_thread_id, '=' ) ) );
				if ( ! $response ) {
					throw new \Exception( esc_html__( 'Assistant thread not found.', 'ai-copilot' ), 404 );
				}
			}

			if ( false !== $response && 0 !== count( $response ) ) {
				return $this->handle_response( $response );
			}

			return $this->handle_response( array() );
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
