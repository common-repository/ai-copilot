<?php

namespace QuadLayers\AICP\Api\Services\OpenAI\Images;

use QuadLayers\AICP\Api\Services\OpenAI\Base;
use QuadLayers\AICP\Services\OpenAI\Images\Get as API_Fetch_Image_OpenAi;
use QuadLayers\AICP\Models\Virtual\Images_Openai as Models_Virtual_Images_Openai;

class Post extends Base {
	protected static $route_path = 'images';

	public function callback( \WP_REST_Request $request ) {
		try {
			$data = json_decode( $request->get_body(), true );

			$entity = Models_Virtual_Images_Openai::instance()->create( $data );

			if ( empty( $entity->get( 'model' ) ) ) {
				throw new \Exception( esc_html__( 'The model field is empty.', 'ai-copilot' ), 400 );
			}

			$response = ( new API_Fetch_Image_OpenAi() )->get_data( $entity->getProperties() );

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
