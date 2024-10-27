<?php

namespace QuadLayers\AICP\Api\Services\OpenAI\Assistants\Assistants;

use QuadLayers\AICP\Api\Services\OpenAI\Base;
use QuadLayers\AICP\Models\Assistants as Models_Assistant;
use QuadLayers\AICP\Models\Assistants_Vector_Stores as Models_Vector_Stores;
use QuadLayers\AICP\Services\OpenAI\Assistants\Assistants\Get as API_Fetch_Get_Assistant_OpenAi;

class Get extends Base {
	protected static $route_path = 'assistants';

	public function callback( \WP_REST_Request $request ) {
		try {

			// Query Params.
			$assistant_id = $request->get_param( 'assistant_id' );
			$sync         = $request->get_param( 'sync' );

			$vector_stores = Models_Vector_Stores::instance()->get_all();

			// If assistant_id is not set, get all assistants.
			if ( ! is_numeric( $assistant_id ) ) {
				// If sync is set, update all assistants.
				if ( $sync ) {
					$old_assistants = Models_Assistant::instance()->get_all();
					$assistants     = ( new API_Fetch_Get_Assistant_OpenAi() )->get_data();
					// Check if assistants is an error.
					if ( isset( $assistants['code'] ) ) {
						throw new \Exception( $assistants['message'], $assistants['code'] );
					}

					if ( isset( $assistants['code'] ) ) {
						throw new \Exception( $assistants['message'], $assistants['code'] );
					}

					// Update all current assistants.
					if ( ! empty( $old_assistants ) ) {
						foreach ( $old_assistants as $old_assistant_data ) {
							$new_assistant_data = array_filter(
								$assistants,
								function ( $assistant ) use ( $old_assistant_data ) {
									return $assistant['openai_id'] === $old_assistant_data->get( 'openai_id' );
								}
							);

							// If assistant is in wpdb but not in openai.
							if ( empty( $new_assistant_data ) ) {
								$old_assistant_data->set( 'openai_id', '' );
								$new_assistant_data = $old_assistant_data->getProperties();
							} else {
								$new_assistant_data                     = reset( $new_assistant_data );
								$new_assistant_data['assistant_origin'] = $old_assistant_data->get( 'assistant_origin' );
							}
							Models_Assistant::instance()->update( $old_assistant_data->get( 'assistant_id' ), $new_assistant_data );
						}
					}
				}

				$response = Models_Assistant::instance()->get_all();

				if ( null === $response ) {
					return $this->handle_response( array() );
				}

				if ( null !== $vector_stores ) {
					foreach ( $response as &$assistant ) {
						$vector_stores_labels = array();

						foreach ( $vector_stores as $vector_store ) {
							$openai_id = $vector_store->get( 'openai_id' );

							if ( in_array( $openai_id, $assistant->get( 'assistant_vector_store_ids' ), true ) ) {
								$vector_stores_labels[] = $vector_store->get( 'vector_store_label' );
							}
						}
						$assistant->set( 'assistant_vector_store_labels', $vector_stores_labels );
					}

					unset( $assistant );
				} else {
					foreach ( $response as &$assistant ) {
						$assistant->set( 'assistant_vector_store_labels', array() );
					}
				}

				$response = array_map(
					function ( $assistant ) {
						return $assistant->getProperties();
					},
					Models_Assistant::instance()->get_all()
				);

				return $this->handle_response( $response );
			}

			// If assistant_id is set, get assistant by id.
			// If sync is set, update assistant by id.
			if ( $sync ) {
				$old_assistant_data = Models_Assistant::instance()->get( $assistant_id )->getProperties();
				if ( empty( $old_assistant_data ) ) {
					throw new \Exception( esc_html__( 'The assistant was not found . ', 'ai - copilot' ), 404 );
				}

				$args = array(
					'openai_id' => $old_assistant_data['openai_id'],
				);

				$new_assistant_data = ( new API_Fetch_Get_Assistant_OpenAi() )->get_data( $args );
				// Check if new_assistant_data is an error.
				if ( isset( $new_assistant_data['code'] ) ) {
					throw new \Exception( $new_assistant_data['message'], $new_assistant_data['code'] );
				}
				$new_assistant_data['assistant_origin'] = $old_assistant_data['assistant_origin'];
				$new_assistant_data['visibility']       = $old_assistant_data['visibility'];

				Models_Assistant::instance()->update( $assistant_id, $new_assistant_data );

			}

			$response = Models_Assistant::instance()->get( $assistant_id )->getProperties();

			if ( null === $response ) {
				return $this->handle_response( array() );
			}

			if ( null !== $vector_stores ) {
					$vector_stores_labels = array();

				foreach ( $vector_stores as $vector_store ) {
					$openai_id = $vector_store->get( 'openai_id' );

					if ( in_array( $openai_id, $response['assistant_vector_store_ids'], true ) ) {
						$vector_stores_labels[] = $vector_store->get( 'vector_store_label' );
					}
				}

					$response['vector_stores_labels'] = $vector_stores_labels;
			} else {
				$response['vector_stores_labels'] = array();
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
			'assistant_id' => array(
				'required'          => false,
				'validate_callback' => function ( $param, $request, $key ) {
					if ( ! is_numeric( $param ) ) {
						return new \WP_Error( 400, __( 'Assistant ID not found . ', 'ai - copilot' ) );
					}
					return true;
				},
			),
			'sync'         => array(
				'required' => false,
			),
		);
	}

	public function get_rest_permission() {
		return true;
	}
}
