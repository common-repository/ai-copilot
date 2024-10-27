<?php

namespace QuadLayers\AICP\Services\OpenAI\Images;

use QuadLayers\AICP\Services\OpenAI\Base;
use QuadLayers\AICP\Helpers;
use QuadLayers\AICP\Models\Admin_Menu_Services;

/**
 * API_Fetch_Text_OpenAi Class extends Base
 */
class Get extends Base {

	/**
	 * Function to build query url.
	 *
	 * @return string
	 */
	public function get_url( $args = null ) {
		$url = $this->fetch_url . '/images/generations';

		return $url;
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
		);

		$body = array(
			'model'           => $args['model'],
			'prompt'          => isset( $args['prompt'] ) ? $args['prompt'] : '',
			'n'               => isset( $args['n'] ) ? $args['n'] : 1,
			'response_format' => 'b64_json',
			'size'            => isset( $args['size'] ) ? $args['size'] : '512x512',
			'style'           => isset( $args['style'] ) ? $args['style'] : 'vivid',
			'quality'         => isset( $args['quality'] ) ? $args['quality'] : '',
		);

		$url     = $this->get_url( $args );
		$timeout = Helpers::get_timeout();

		$response = wp_remote_post(
			$url,
			array(
				'method'  => 'POST',
				'timeout' => $timeout,
				'headers' => $headers,
				'body'    => wp_json_encode( $body ),
			)
		);

		$response = $this->handle_response( $response );

		return $response;
	}
}
