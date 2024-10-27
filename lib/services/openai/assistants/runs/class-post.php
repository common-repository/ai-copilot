<?php

namespace QuadLayers\AICP\Services\OpenAI\Assistants\Runs;

use QuadLayers\AICP\Services\OpenAI\Base;
use QuadLayers\AICP\Models\Admin_Menu_Services;

/**
 * API_Fetch_Assistant_Run_OpenAi Class extends Base
 */
class Post extends Base {

	public $timeout = 6;

	/**
	 * Function to build query url.
	 *
	 * @return string
	 */
	public function get_url( $args = null ) {
		return $this->fetch_url . '/threads/' . $args['thread_openai_id'] . '/runs';
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

		if ( empty( $args['thread_openai_id'] ) ) {
			throw new \Exception( esc_html__( 'Thread OpenAI Id not found.', 'ai-copilot' ), 404 );
		}

		$api_key = $settings->get( 'openai_api_key' );

		$headers = array(
			'Content-Type'  => 'application/json',
			'Authorization' => 'Bearer ' . $api_key,
			'OpenAI-Beta'   => 'assistants=v2',
		);

		$body = array(
			'assistant_id' => isset( $args['assistant_openai_id'] ) ? $args['assistant_openai_id'] : '',
		);

		$url = $this->get_url( $args );

		$response = wp_remote_post(
			$url,
			array(
				'method'  => 'POST',
				'timeout' => $this->timeout,
				'headers' => $headers,
				'body'    => wp_json_encode( $body ),
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
		if ( ! isset( $response['message'], $response['code'] ) ) {
			$response = array(
				'status'    => $response['status'],
				'openai_id' => $response['id'],
			);
		}
		return $response;
	}
}
