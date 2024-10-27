<?php

namespace QuadLayers\AICP\Api\Services\OpenAI\Assistants\Files;

use QuadLayers\AICP\Api\Services\OpenAI\Base;
use QuadLayers\AICP\Services\OpenAI\Assistants\Files\Delete as API_Fetch_Delete_Assistant_File_OpenAi;
use QuadLayers\AICP\Models\Assistants_Files as Models_Assistants_Files;
use QuadLayers\AICP\Models\Assistants_Vector_Stores as Models_Vector_Stores;

class Delete extends Base {
	protected static $route_path = 'assistants/files';

	public function callback( \WP_REST_Request $request ) {
		try {
			$file_id = $request->get_param( 'file_id' );

			if ( ! is_numeric( $file_id ) ) {
				throw new \Exception( esc_html__( 'File ID not set.', 'ai-copilot' ), 400 );
			}

			$file = Models_Assistants_Files::instance()->get( $file_id );

			if ( ! is_numeric( $file->get( 'file_id' ) ) ) {
				throw new \Exception( esc_html__( 'File not found.', 'ai-copilot' ), 404 );
			}

			if ( $file->get( 'openai_id' ) ) {
				$vector_stores = Models_Vector_Stores::instance()->get_all();

				foreach ( $vector_stores as $vector_store ) {
					if ( in_array( $file->get( 'openai_id' ), $vector_store->get( 'vector_store_files_ids' ), true ) ) {

						$key = array_search( $file->get( 'openai_id' ), $vector_store->get( 'vector_store_files_ids' ), true );

						if ( false !== $key ) {
							$filtered_vector_store = array_filter(
								$vector_store->get( 'vector_store_files_ids' ),
								function ( $vector_store_files_id ) use ( $key ) {
									return $vector_store_files_id !== $key;
								}
							);

							$vector_store->set(
								'vector_store_files_ids',
								$filtered_vector_store
							);

							Models_Vector_Stores::instance()->update( $vector_store->get( 'vector_store_id' ), $vector_store->getProperties() );
						}
					}
				}

				if ( ! empty( $file->get( 'openai_id' ) ) && $file->get( 'openai_id' ) !== 'file-P2mCnfk5ZvZSF4CGfYEjmr0Q' ) {
					( new API_Fetch_Delete_Assistant_File_OpenAi() )->get_data(
						array(
							'openai_id' => $file->get( 'openai_id' ),
						)
					);
				}
			}

			$success = Models_Assistants_Files::instance()->delete( $file->get( 'file_id' ) );

			if ( ! $success ) {
				throw new \Exception( esc_html__( 'Cannot delete the file.', 'ai-copilot' ), 404 );
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
			'file_id' => array(
				'required'          => true,
				'validate_callback' => function ( $param, $request, $key ) {
					if ( ! is_numeric( $param ) ) {
						return new \WP_Error( 400, __( 'File ID not found.', 'ai-copilot' ) );
					}
					return true;
				},
			),
		);
	}
}
