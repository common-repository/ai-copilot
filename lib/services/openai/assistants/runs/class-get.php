<?php

namespace QuadLayers\AICP\Services\OpenAI\Assistants\Runs;

use QuadLayers\AICP\Services\OpenAI\Base;
use QuadLayers\AICP\Helpers;
use QuadLayers\AICP\Models\Admin_Menu_Services;

/**
 * API_Fetch_Assistant_Run_OpenAi Class extends Base
 */
class Get extends Base {

	public $timeout = 3;

	/**
	 * Function to build query url.
	 *
	 * @return string
	 */
	public function get_url( $args = null ) {
		if ( isset( $args['thread_openai_id'], $args['run_openai_id'] ) ) {
			$url = $this->fetch_url . '/threads/' . $args['thread_openai_id'] . '/runs/' . $args['run_openai_id'];
			return $url;
		}
		return $this->fetch_url . '/threads';
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

		if ( empty( $args['thread_openai_id'] ) || empty( $args['run_openai_id'] ) ) {
			throw new \Exception( esc_html__( 'Thread OpenAI Id not found.', 'ai-copilot' ), 404 );
		}

		$api_key = $settings->get( 'openai_api_key' );

		$headers = array(
			'Content-Type'  => 'application/json',
			'Authorization' => 'Bearer ' . $api_key,
			'OpenAI-Beta'   => 'assistants=v2',
		);

		$url = $this->get_url( $args );

		$response = wp_remote_post(
			$url,
			array(
				'method'  => 'GET',
				'timeout' => $this->timeout,
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
		if ( ! isset( $response['message'], $response['code'] ) ) {
			$response = array(
				'status'    => $response['status'],
				'openai_id' => $response['id'],
				'usage'     => $response['usage'],
			);
		}
		return $response;
	}
}
