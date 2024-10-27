<?php

namespace QuadLayers\AICP\Services\OpenAI\Assistants\Assistants;

use QuadLayers\AICP\Services\OpenAI\Base;
use QuadLayers\AICP\Helpers;
use QuadLayers\AICP\Models\Admin_Menu_Services;

/**
 * API_Fetch_Assistant_OpenAi Class extends Base
 */
class Get extends Base {

	/**
	 * Function to build query url.
	 *
	 * @return string
	 */
	public function get_url( $args = null ) {
		if ( ! empty( $args['openai_id'] ) ) {
			return $this->fetch_url . '/assistants/' . $args['openai_id'];
		}
		return $this->fetch_url . '/assistants';
	}

	/**
	 * Function to query Open AI data.
	 *
	 * @param string $args Args to set query.
	 * @return array
	 *
	 * @throws \Exception If API Key is not found.
	 */
	public function get_response( $args = null ) {

		$admin_menu_services = Admin_Menu_Services::instance();
		$settings            = $admin_menu_services->get();

		if ( empty( $settings->get( 'openai_api_key' ) ) ) {
			throw new \Exception( esc_html__( 'You have reached the limit of your free credits.', 'ai-copilot' ), 404 );
		}

		$api_key = $settings->get( 'openai_api_key' );

		$headers = array(
			'Content-Type'  => 'application/json',
			'Authorization' => 'Bearer ' . $api_key,
			'OpenAI-Beta'   => 'assistants=v2',
		);

		$url     = $this->get_url( $args );
		$timeout = Helpers::get_timeout();

		$response = wp_remote_post(
			$url,
			array(
				'method'  => 'GET',
				'timeout' => $timeout,
				'headers' => $headers,
			)
		);

		$response = $this->handle_response( $response );

		return $response;
	}

	/**
	 * Function to parse response to usable data.
	 *
	 * @param array $response Raw response from openai.
	 * @return array
	 */
	public function response_to_data( $response = null ) {

		if ( isset( $response['code'] ) && isset( $response['message'] ) ) {
			return $response;
		}

		if ( ! isset( $response['object'] ) || 'list' !== $response['object'] ) {
			$assistant_label = isset( $response['name'] ) ? $response['name'] : '';

			if ( strpos( $assistant_label, $this->prefix ) === 0 ) {
				$assistant_label = substr( $assistant_label, strlen( $this->prefix ) );
			}

			return array(
				'assistant_description' => isset( $response['description'] ) ? $response['description'] : '',
				'assistant_label'       => $assistant_label,
				'model'                 => isset( $response['model'] ) ? $response['model'] : '',
				'prompt_system'         => isset( $response['instructions'] ) ? $response['instructions'] : '',
				'tools'                 => isset( $response['tools'] ) ? $response['tools'] : array(),
				'vector_store_id'       => isset( $response['tool_resources']['file_search']['vector_store_id'] ) ? $response['tool_resources']['file_search']['vector_store_id'] : array(),
				'openai_id'             => isset( $response['id'] ) ? $response['id'] : '',
				'assistant_origin'      => 'user',
			);
		}

		$parsed_response = array();
		foreach ( $response['data'] as $data ) {
			$assistant_label = isset( $data['name'] ) ? $data['name'] : '';

			if ( strpos( $assistant_label, $this->prefix ) === 0 ) {
				$assistant_label = substr( $assistant_label, strlen( $this->prefix ) );
			}

			$parsed_response[] = array(
				'assistant_description' => isset( $data['description'] ) ? $data['description'] : '',
				'assistant_label'       => $assistant_label,
				'model'                 => isset( $data['model'] ) ? $data['model'] : '',
				'prompt_system'         => isset( $data['instructions'] ) ? $data['instructions'] : '',
				'tools'                 => isset( $data['tools'] ) ? $data['tools'] : array(),
				'vector_store_id'       => isset( $response['tool_resources']['file_search']['vector_store_id'] ) ? $response['tool_resources']['file_search']['vector_store_id'] : array(),
				'openai_id'             => isset( $data['id'] ) ? $data['id'] : '',
				'assistant_origin'      => 'user',
			);
		}

		return $parsed_response;
	}
}
