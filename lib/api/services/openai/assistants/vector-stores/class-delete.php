<?php

namespace QuadLayers\AICP\Api\Services\OpenAI\Assistants\Vector_Stores;

use QuadLayers\AICP\Api\Services\OpenAI\Base;
use QuadLayers\AICP\Services\OpenAI\Assistants\Vector_Stores\Delete as API_Fetch_Delete_Assistant_Vector_Store_OpenAi;
use QuadLayers\AICP\Models\Assistants_Vector_Stores as Models_Assistant_Vector_Stores;
use QuadLayers\AICP\Models\Assistants as Models_Assistants;

class Delete extends Base {
	protected static $route_path = 'assistants/vector_stores';

	public function callback( \WP_REST_Request $request ) {
		try {

			$vector_store_id = $request->get_param( 'vector_store_id' );

			if ( ! is_numeric( $vector_store_id ) ) {
				throw new \Exception( esc_html__( 'Vector Store ID not set.', 'ai-copilot' ), 400 );
			}

			$vector_store = Models_Assistant_Vector_Stores::instance()->get( $vector_store_id );
			if ( ! is_numeric( $vector_store->get( 'vector_store_id' ) ) ) {
				throw new \Exception( esc_html__( 'Vector Store not found.', 'ai-copilot' ), 404 );
			}

			if ( $vector_store->get( 'openai_id' ) ) {
				$assistants = Models_Assistants::instance()->get_all();

				foreach ( $assistants as $assistant ) {
					if ( in_array( $vector_store->get( 'openai_id' ), $assistant->get( 'assistant_vector_store_ids' ), true ) ) {

						$key = array_search( $vector_store->get( 'openai_id' ), $assistant->get( 'assistant_vector_store_ids' ), true );

						if ( false !== $key ) {
							$filtered_assistant_vector_store_ids = array_filter(
								$assistant->get( 'assistant_vector_store_ids' ),
								function ( $vector_store_files_id ) use ( $key ) {
									return $vector_store_files_id !== $key;
								}
							);
							$assistant->set(
								'assistant_vector_store_ids',
								$filtered_assistant_vector_store_ids
							);

							Models_Assistants::instance()->update( $assistant->get( 'assistant_id' ), $assistant->getProperties() );
						}
					}
				}

				if ( ! empty( $vector_store->get( 'openai_id' ) ) && $vector_store->get( 'openai_id' ) !== 'vs_ovyxLUTWUOX0MpAqhMH6zDQU' ) {
					( new API_Fetch_Delete_Assistant_Vector_Store_OpenAi() )->get_data(
						array(
							'openai_id' => $vector_store->get( 'openai_id' ),
						)
					);
				}
			}

			$success = Models_Assistant_Vector_Stores::instance()->delete( $vector_store->get( 'vector_store_id' ) );

			if ( ! $success ) {
				throw new \Exception( esc_html__( 'Cannot delete the vector store.', 'ai-copilot' ), 404 );
			}

			return $this->handle_response( $success );
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
			'vector_store_id' => array(
				'required'          => true,
				'validate_callback' => function ( $param, $request, $key ) {
					if ( ! is_numeric( $param ) ) {
						return new \WP_Error( 400, __( 'Vector Store ID not found.', 'ai-copilot' ) );
					}
					return true;
				},
			),
		);
	}
}
