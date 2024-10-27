<?php

namespace QuadLayers\AICP\Api\Services\OpenAI\Assistants\Vector_Stores;

use QuadLayers\AICP\Api\Services\OpenAI\Base;
use QuadLayers\AICP\Services\OpenAI\Assistants\Vector_Stores\Post as API_Fetch_Post_Assistant_Vector_Store_OpenAi;
use QuadLayers\AICP\Models\Assistants_Vector_Stores as Models_Assistant_Vector_Stores;

class Post extends Base {
	protected static $route_path = 'assistants/vector_stores';

	public function callback( \WP_REST_Request $request ) {
		try {
			$data = json_decode( $request->get_body(), true );

			$response = ( new API_Fetch_Post_Assistant_Vector_Store_OpenAi() )->get_data( $data );

			if ( isset( $response['code'] ) ) {
				throw new \Exception( $response['message'], $response['code'] );
			}

			$vector_store = Models_Assistant_Vector_Stores::instance()->create(
				array(
					'vector_store_label'       => $response['file_label'] ?? $data['vector_store_label'],
					'vector_store_description' => $data['vector_store_description'] ?? '',
					'openai_id'                => $response['openai_id'] ?? '',
					'vector_store_files_ids'   => $data['vector_store_files_ids'] ?? array(),
					'vector_store_status'      => $response['vector_store_status'] ?? 'in_progress',
				)
			)->getProperties();

			if ( ! $vector_store ) {
				throw new \Exception( esc_html__( 'Unknown error.', 'ai-copilot' ), 500 );
			}

			return $this->handle_response( $vector_store );

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
