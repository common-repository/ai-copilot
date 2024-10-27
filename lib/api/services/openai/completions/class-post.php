<?php

namespace QuadLayers\AICP\Api\Services\OpenAI\Completions;

use QuadLayers\AICP\Api\Services\OpenAI\Base;
use QuadLayers\AICP\Services\OpenAI\Completions\Post as API_Fetch_Completions_OpenAi;
use QuadLayers\AICP\Models\Virtual\Completions as Models_Virtual_Completion;

class Post extends Base {
	protected static $route_path = 'completion';

	public function callback( \WP_REST_Request $request ) {
		try {
			$data = json_decode( $request->get_body(), true );

			$entity = Models_Virtual_Completion::instance()->create( $data );

			$entity->set( 'stop', $entity->get( 'stop' ) ?? null );
			$entity->set( 'top_p', $entity->get( 'top_p' ) ?? 1 );

			$response = ( new API_Fetch_Completions_OpenAi() )->get_data( $entity->getProperties() );

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
