<?php

namespace QuadLayers\AICP\Api\Services\OpenAI\Chat;

use QuadLayers\AICP\Api\Services\OpenAI\Base;
use QuadLayers\AICP\Services\OpenAI\Chat\Post as API_Fetch_Chat_OpenAi;
use QuadLayers\AICP\Models\Virtual\Chats as Models_Virtual_Chat;

class Post extends Base {
	protected static $route_path = 'chat';

	public function callback( \WP_REST_Request $request ) {
		try {
			$data = json_decode( $request->get_body(), true );

			$entity = Models_Virtual_Chat::instance()->create( $data );

			$response = ( new API_Fetch_Chat_OpenAi() )->get_data( $entity->getProperties() );

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
		return \WP_REST_Server::CREATABLE;
	}

	public static function get_rest_args() {
		return array();
	}
}
