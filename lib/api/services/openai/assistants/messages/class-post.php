<?php

namespace QuadLayers\AICP\Api\Services\OpenAI\Assistants\Messages;

use QuadLayers\AICP\Api\Services\OpenAI\Base;
use QuadLayers\AICP\Services\OpenAI\Assistants\Messages\Post as API_Fetch_Post_Assistant_Message_OpenAi;
use QuadLayers\AICP\Services\OpenAI\Assistants\Runs\Get as API_Fetch_Get_Assistant_Run_OpenAi;
use QuadLayers\AICP\Services\OpenAI\Assistants\Runs\Post as API_Fetch_Post_Assistant_Run_OpenAi;
use QuadLayers\AICP\Helpers;
use QuadLayers\AICP\Models\Virtual\Assistant_Messages as Models_Virtual_Assistant_Messages;

class Post extends Base {
	protected static $route_path = 'assistants/messages';

	public function callback( \WP_REST_Request $request ) {
		try {
			$data = json_decode( $request->get_body(), true );

			$run_openai_id = $data['run_openai_id'] ?? null;

			// Max time/attempts to get response from OpenAI.
			$timeout            = Helpers::get_timeout();
			$sleep_milliseconds = 300; // Milliseconds.
			$sleep_seconds      = $sleep_milliseconds / 1000; // Seconds.
			$iteration          = $sleep_seconds + 3;
			$max_attempts       = ( $timeout - $iteration ) / $iteration;

			$entity = Models_Virtual_Assistant_Messages::instance()->create( $data );

			if ( ! $run_openai_id ) {
				// Add message to given thread in OpenAI.
				$response = ( new API_Fetch_Post_Assistant_Message_OpenAi() )->get_data( $entity->getProperties() );

				if ( isset( $response['code'] ) ) {
					throw new \Exception( $response['message'], $response['code'] );
				}

				// Loop to make polling awaiting for [status] = completed.
				$post_run_current_attemps = 0;
				do {
					++$post_run_current_attemps;

					if ( $post_run_current_attemps > $max_attempts ) {
						$error = array(
							'code'    => 500,
							'message' => esc_html__( 'Maximum post run attempts reached.', 'ai-copilot' ),
						);

						throw new \Exception( $error['message'], $error['code'] );
					}

					// Create Run.
					$post_run = ( new API_Fetch_Post_Assistant_Run_OpenAi() )->get_data(
						array_merge(
							$entity->getProperties(),
							array(
								'assistant_openai_id' => $entity->get( 'openai_id' ),
							)
						)
					);

					Helpers::wait( $sleep_milliseconds );

					$completed = isset( $post_run['status'] );

					// Exit condition: status has to be set and completed else keep polling, if response is and error exit and throw exception.
				} while ( ! $completed );

				if ( isset( $post_run['code'] ) ) {
					throw new \Exception( $post_run['message'], $post_run['code'] );
				}
			}

			// Loop to make polling awaiting for $[status] = completed.
			$get_run_current_attemps = 0;
			do {
				++$get_run_current_attemps;

				$post_run['openai_id'] = $post_run['openai_id'] ?? $run_openai_id;

				if ( $get_run_current_attemps > $max_attempts ) {
					// Response with run_id to be able to send again in loop until get response in frontend.
					return array(
						'run_openai_id' => $post_run['openai_id'],
					);
				}

				// Get Run.
				$get_run = ( new API_Fetch_Get_Assistant_Run_OpenAi() )->get_data(
					array_merge(
						$entity->getProperties(),
						array( 'run_openai_id' => $post_run['openai_id'] )
					)
				);

				Helpers::wait( $sleep_milliseconds );

				$completed = isset( $get_run['status'] ) && 'completed' === $get_run['status'];

				if ( $completed ) {
					$response['usage'] = $get_run['usage'];
				}

				// Exit condition: status has to be set and completed else keep polling, if response is and error exit and throw exception.
			} while ( ! $completed );

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
		return array(
			'assistant_id' => array(
				'required'          => true,
				'validate_callback' => function ( $param, $request, $key ) {
					if ( ! is_numeric( $param ) ) {
						return new \WP_Error( 400, __( 'Assistant ID not found.', 'ai-copilot' ) );
					}
					return true;
				},
			),
			'openai_id'    => array(
				'required'          => true,
				'validate_callback' => function ( $param, $request, $key ) {
					if ( ! is_string( $param ) ) {
						return new \WP_Error( 400, __( 'Assistant OpenAI ID not found.', 'ai-copilot' ) );
					}
					return true;
				},
			),
		);
	}

	public function get_rest_permission() {
		return true;
	}
}
