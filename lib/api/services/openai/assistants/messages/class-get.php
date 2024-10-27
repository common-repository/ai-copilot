<?php
namespace QuadLayers\AICP\Api\Services\OpenAI\Assistants\Messages;

use QuadLayers\AICP\Api\Services\OpenAI\Base;
use QuadLayers\AICP\Services\OpenAI\Assistants\Messages\Get as API_Fetch_Assistant_Message_OpenAi;
/**
 * API_Rest_Assistant_Message_Get Class
 */
class Get extends Base {

	protected static $route_path = 'assistants/messages';

	public function callback( \WP_REST_Request $request ) {
		try {

			$params = $request->get_params();

			$message = ( new API_Fetch_Assistant_Message_OpenAi() )->get_data( $params );

			if ( isset( $message['code'] ) ) {
				throw new \Exception( $message['message'], $message['code'] );
			}

			return $this->handle_response( $message );
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
			'message_openai_before' => array(
				'required'          => false,
				'default'           => '',
				'sanitize_callback' => 'sanitize_text_field',
			),
			'limit'                 => array(
				'required'          => false,
				'default'           => 100,
				'sanitize_callback' => 'absint',
			),
			'thread_openai_id'      => array(
				'required'          => true,
				'default'           => '',
				'sanitize_callback' => 'sanitize_text_field',
			),
		);
	}

	public static function get_rest_method() {
		return \WP_REST_Server::READABLE;
	}

	public function get_rest_permission() {
		return true;
	}
}
