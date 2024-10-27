<?php

namespace QuadLayers\AICP\Api\Services\OpenAI\Assistants\Vector_Stores;

use QuadLayers\AICP\Api\Services\OpenAI\Base;
use QuadLayers\AICP\Services\OpenAI\Assistants\Vector_Store_Files\Post as API_Fetch_Post_Assistant_Vector_Store_File_OpenAi;
use QuadLayers\AICP\Services\OpenAI\Assistants\Vector_Store_Files\Delete as API_Fetch_Delete_Assistant_Vector_Store_File_OpenAi;
use QuadLayers\AICP\Services\OpenAI\Assistants\Vector_Stores\Edit as API_Fetch_Edit_Assistant_Vector_Store_OpenAi;
use QuadLayers\AICP\Models\Assistants_Vector_Stores as Models_Assistant_Vector_Stores;

class Edit extends Base {
	protected static $route_path = 'assistants/vector_stores';

	public function callback( \WP_REST_Request $request ) {
		try {
			$data = json_decode( $request->get_body(), true );

			$response = ( new API_Fetch_Edit_Assistant_Vector_Store_OpenAi() )->get_data( $data );

			if ( isset( $response['code'] ) ) {
				throw new \Exception( $response['message'], $response['code'] );
			}

			$old_vector_store_files_ids = Models_Assistant_Vector_Stores::instance()->get( $data['vector_store_id'] )->getProperties()['vector_store_files_ids'] ?? array();
			$deleted_vector_store_files = array_values( array_diff( $old_vector_store_files_ids, $data['vector_store_files_ids'] ) );
			$added_vector_store_files   = array_values( array_diff( $data['vector_store_files_ids'], $old_vector_store_files_ids ) );

			if ( ! empty( $deleted_vector_store_files ) ) {
				foreach ( $deleted_vector_store_files as $file_id ) {
					$response_deleted = ( new API_Fetch_Delete_Assistant_Vector_Store_File_OpenAi() )->get_data(
						array(
							'openai_id' => $data['openai_id'],
							'file_id'   => $file_id,
						)
					);
					if ( isset( $response_deleted['code'] ) ) {
						throw new \Exception( $response_deleted['message'], $response_deleted['code'] );
					}
				}
			}

			if ( ! empty( $added_vector_store_files ) ) {
				$response_added                  = ( new API_Fetch_Post_Assistant_Vector_Store_File_OpenAi() )->get_data(
					array(
						'openai_id'              => $data['openai_id'],
						'vector_store_files_ids' => $added_vector_store_files,
					)
				);
				$response['vector_store_status'] = $response_added['vector_store_status'];

				if ( isset( $response_added['code'] ) ) {
					throw new \Exception( $response_added['message'], $response_added['code'] );
				}
			}

			$response['vector_store_description'] = $data['vector_store_description'];
			$response['vector_store_files_ids']   = $data['vector_store_files_ids'];

			$vector_store = Models_Assistant_Vector_Stores::instance()->update( $data['vector_store_id'], $response )->getProperties();

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
		return 'PUT, PATCH';
	}

	public static function get_rest_args() {
		return array(
			'vector_store_id' => array(
				'required'          => true,
				'validate_callback' => function ( $param, $request, $key ) {
					if ( ! is_numeric( $param ) ) {
						return new \WP_Error( 400, __( 'Vector Store ID is not set.', 'ai-copilot' ) );
					}
					return true;
				},
			),
		);
	}
}
