<?php

namespace QuadLayers\AICP\Api\Services\OpenAI\Assistants\Files;

use QuadLayers\AICP\Api\Services\OpenAI\Base;
use QuadLayers\AICP\Models\Assistants_Files as Models_Assistants_Files;
use QuadLayers\AICP\Models\Assistants_Vector_Stores as Models_Vector_Stores;
use QuadLayers\AICP\Services\OpenAI\Assistants\Files\Get as API_Fetch_Get_Assistant_File_OpenAi;

class Get extends Base {
	protected static $route_path = 'assistants/files';

	public function callback( \WP_REST_Request $request ) {
		try {

			$file_id = $request->get_param( 'file_id' );
			$sync    = $request->get_param( 'sync' );

			$response = array();

			$vector_stores = Models_Vector_Stores::instance()->get_all();

			// If file_id is not set, get all files.
			if ( ! isset( $file_id ) ) {
				// If sync is set, update all files.
				if ( $sync ) {
					$old_files = Models_Assistants_Files::instance()->get_all();
					$files     = ( new API_Fetch_Get_Assistant_File_OpenAi() )->get_data();
					// Check if files is an error.
					if ( isset( $files['code'] ) ) {
						throw new \Exception( $files['message'], $files['code'] );
					}

					// Update all current files.
					if ( ! empty( $old_files ) ) {
						foreach ( $old_files as $old_file_data ) {
							$new_file_data = array_filter(
								$files,
								function ( $file ) use ( $old_file_data ) {
									return $file['openai_id'] === $old_file_data->get( 'openai_id' );
								}
							);

							// If file is in wpdb but not in openai.
							if ( empty( $new_file_data ) ) {
								if ( null !== $vector_stores ) {
									foreach ( $vector_stores as $vector_store ) {
										if ( in_array( $old_file_data->get( 'openai_id' ), $vector_store->get( 'vector_store_files_ids' ), true ) ) {
											$key = array_search( $old_file_data->get( 'openai_id' ), $vector_store->get( 'vector_store_files_ids' ), true );

											if ( false !== $key ) {
												$filtered_vector_store_files_ids = array_filter(
													$vector_store->get( 'vector_store_files_ids' ),
													function ( $vector_store_files_id ) use ( $key ) {
														return $vector_store_files_id !== $key;
													}
												);
												$vector_store->set( 'vector_store_files_ids', $filtered_vector_store_files_ids );

												Models_Vector_Stores::instance()->update( $vector_store->get( 'vector_store_id' ), $vector_store->getProperties() );
											}
										}
									}
								}

								$old_file_data->set( 'file_status', 'deleted' );
								$new_file_data = $old_file_data->getProperties();
							} else {
								$new_file_data                = reset( $new_file_data );
								$new_file_data['file_origin'] = $old_file_data->get( 'file_origin' );
								$new_file_data['file_label']  = $old_file_data->get( 'file_label' );
								$new_file_data['file_status'] = $old_file_data->get( 'file_status' );

							}

							$new_file_data['file_status'] = isset( $new_file_data['file_status'] ) ? $new_file_data['file_status'] : 'ready';
							$is_deleted                   = isset( $new_file_data['file_status'] ) && 'deleted' === $new_file_data['file_status'];
							$is_user_origin               = isset( $new_file_data['file_origin'] ) && 'user' === $new_file_data['file_origin'];
							$is_attachment                = isset( $new_file_data['attachment_id'] ) && get_post_meta( $new_file_data['attachment_id'], '_wp_attached_file', true );

							$new_file_data['is_restorable'] = $is_deleted && $is_user_origin && $is_attachment;
							Models_Assistants_Files::instance()->update( $old_file_data->get( 'file_id' ), $new_file_data );

						}
					}
				}
				$response = Models_Assistants_Files::instance()->get_all();

				if ( empty( $response ) ) {
					return $this->handle_response( array() );
				}

				if ( null !== $vector_stores ) {
					foreach ( $response as &$file ) {
						$openai_id           = $file->get( 'openai_id' );
						$vector_stores_found = array();

						foreach ( $vector_stores as $vector_store ) {
							if ( in_array( $openai_id, $vector_store->get( 'vector_store_files_ids' ), true ) ) {
								$vector_stores_found[] = $vector_store->get( 'vector_store_label' );
							}
						}

						$file->set( 'vector_stores', $vector_stores_found ?? array() );
						$file->set( 'attachment_url', wp_get_attachment_url( $file->get( 'attachment_id' ) ) );
					}

					unset( $file );
				} else {
					foreach ( $response as &$file ) {
						$file->set( 'vector_stores', array() );
					}
				}
			} else {
				// If file_id is set, get file by id.
				// If sync is set, update file by id.
				if ( $sync ) {
					$old_file_data = Models_Assistants_Files::instance()->get( $file_id )->getProperties();
					if ( empty( $old_file_data ) ) {
						throw new \Exception( esc_html__( 'File not found.', 'ai-copilot' ), 404 );
					}

					$args = array(
						'openai_id' => $old_file_data['openai_id'],
					);

					$new_file_data = ( new API_Fetch_Get_Assistant_File_OpenAi() )->get_data( $args );

					// Check if new_file_data is an error.
					if ( isset( $new_file_data['code'] ) ) {
						throw new \Exception( $new_file_data['message'], $new_file_data['code'] );
					}

					if ( empty( $new_file_data ) ) {
						$old_file_data['openai_id'] = null;
						$new_file_data              = $old_file_data;
					}

					$new_file_data['file_label']  = $old_file_data['file_label'];
					$new_file_data['file_status'] = $old_file_data['file_status'];

					Models_Assistants_Files::instance()->update( $file_id, $new_file_data );

				}
				$response = Models_Assistants_Files::instance()->get( $file_id )->getProperties();

				if ( null === $response ) {
					return $this->handle_response( array() );
				}

				if ( null !== $vector_stores ) {
					$openai_id          = $response['openai_id'];
					$vector_store_found = array();

					foreach ( $vector_stores as $vector_store ) {
						if ( in_array( $openai_id, $vector_store->get( 'vector_store_files_ids' ), true ) ) {
							$vector_store_found[] = $vector_store->get( 'vector_store_label' );
						}
					}

					$response['vector_stores'] = $vector_store_found;
				} else {
					$response['vector_stores'] = array();
				}

				$response['attachment_url'] = wp_get_attachment_url( $response['attachment_id'] );
			}

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
		return \WP_REST_Server::READABLE;
	}

	public static function get_rest_args() {
		return array(
			'file_id' => array(
				'required'          => false,
				'validate_callback' => function ( $param, $request, $key ) {
					if ( ! is_numeric( $param ) ) {
						return new \WP_Error( 400, __( 'File ID not found.', 'ai-copilot' ) );
					}
					return true;
				},
			),
			'sync'    => array(
				'required' => false,
			),
		);
	}
}
