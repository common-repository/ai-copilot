<?php

namespace QuadLayers\AICP\Api\Services\OpenAI\Assistants\Vector_Stores;

use QuadLayers\AICP\Api\Services\OpenAI\Base;
use QuadLayers\AICP\Services\OpenAI\Assistants\Vector_Stores\Get as API_Fetch_Get_Assistant_Vector_Store_OpenAi;
use QuadLayers\AICP\Models\Assistants_Vector_Stores as Models_Assistant_Vector_Stores;
use QuadLayers\AICP\Models\Assistants as Models_Assistants;
use QuadLayers\AICP\Models\Assistants_Files as Models_Assistants_Files;

class Get extends Base {
	protected static $route_path = 'assistants/vector_stores';

	public function callback( \WP_REST_Request $request ) {
		try {
			$sync = $request->get_param( 'sync' );

			$response = array();

			$assistants = Models_Assistants::instance()->get_all();

			if ( ! isset( $vector_store_id ) ) {
				// If sync is set, update all vector_stores.
				if ( $sync ) {
					$old_vector_stores = Models_Assistant_Vector_Stores::instance()->get_all();
					$vector_stores     = ( new API_Fetch_Get_Assistant_Vector_Store_OpenAi() )->get_data();
					// Check if vector_stores is an error.
					if ( isset( $vector_stores['code'] ) ) {
						throw new \Exception( $vector_stores['message'], $vector_stores['code'] );
					}

					// Update all current vector_stores.
					if ( ! empty( $old_vector_stores ) ) {
						foreach ( $old_vector_stores as $old_vector_store_data ) {
							$new_vector_store_data = array_filter(
								$vector_stores,
								function ( $vector_store ) use ( $old_vector_store_data ) {
									return $vector_store['openai_id'] === $old_vector_store_data->get( 'openai_id' );
								}
							);

							// If vector store is in wpdb but not in openai.
							if ( empty( $new_vector_store_data ) ) {
								if ( null !== $assistants ) {
									foreach ( $assistants as $assistant ) {
										if ( in_array( $old_vector_store_data->get( 'openai_id' ), $assistant->get( 'assistant_vector_store_ids' ), true ) ) {
											$key = array_search( $old_vector_store_data->get( 'openai_id' ), $assistant->get( 'assistant_vector_store_ids' ), true );
											if ( false !== $key ) {
												$filtered_assistant_vector_store_ids = array_filter(
													$assistant->get( 'assistant_vector_store_ids' ),
													function ( $vector_store_id ) use ( $key ) {
													return $vector_store_id !== $key;
													}
												);
												$assistant->set( 'assistant_vector_store_ids', $filtered_assistant_vector_store_ids );
												Models_Assistants::instance()->update( $assistant->get( 'assistant_id' ), $assistant );
											}
										}
									}
								}
								$old_vector_store_data->set( 'openai_id', '' );
								$old_vector_store_data->set( 'vector_store_status', 'deleted' );
								$new_vector_store_data = $old_vector_store_data->getProperties();
							} else {
								$new_vector_store_data                           = reset( $new_vector_store_data );
								$new_vector_store_data['vector_store_label']     = $old_vector_store_data->get( 'vector_store_label' );
								$new_vector_store_data['vector_store_files_ids'] = $old_vector_store_data->get( 'vector_store_files_ids' );
								$new_vector_store_data['vector_store_status']    = $new_vector_store_data['vector_store_status'];
							}
							$new_vector_store_data['vector_store_status'] = $new_vector_store_data['vector_store_status'] ?? 'ready';
							$is_deleted                                   = isset( $new_vector_store_data['vector_store_status'] ) && 'deleted' === $new_vector_store_data['vector_store_status'];

							$files       = ( Models_Assistants_Files::instance() )->get_all();
							$files_ready = true;
							if ( ! empty( $files ) ) {
								foreach ( $new_vector_store_data['vector_store_files_ids'] as $file_id ) {
										$file = array_values(
											array_filter(
												$files,
												function ( $file ) use ( $file_id ) {
													return $file->get( 'openai_id' ) === $file_id;
												}
											)
										)[0];

									// Check foreach file if it is ready.
									if ( $file ) {
										if ( 'ready' !== $file->get( 'file_status' ) ) {
											$files_ready = false;
											break;
										}
									}
								}
							}

							$new_vector_store_data['is_restorable'] = $is_deleted && $files_ready;
							Models_Assistant_Vector_Stores::instance()->update( $old_vector_store_data->get( 'vector_store_id' ), $new_vector_store_data );
						}
					}
				}
				$response = Models_Assistant_Vector_Stores::instance()->get_all();

				if ( empty( $response ) ) {
					return $this->handle_response( array() );
				}

				if ( null !== $assistants ) {
					foreach ( $response as &$vector_store ) {
						$openai_id        = $vector_store->get( 'openai_id' );
						$assistants_found = array();

						foreach ( $assistants as $assistant ) {
							if ( in_array( $openai_id, $assistant->get( 'assistant_vector_store_ids' ), true ) ) {
								$assistants_found[] = $assistant->get( 'assistant_label' );
							}
						}

						$vector_store->set( 'vector_store_assistants', $assistants_found );
					}

					unset( $vector_store );
				} else {
					foreach ( $response as &$vector_store ) {
						$vector_store->set( 'vector_store_assistants', array() );
					}
				}
			}

			$response = array_map(
				function ( $vector_store ) {
					return $vector_store->getProperties();
				},
				Models_Assistant_Vector_Stores::instance()->get_all()
			);

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
			'sync' => array(
				'required' => false,
			),
		);
	}
}
