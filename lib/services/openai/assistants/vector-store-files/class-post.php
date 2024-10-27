<?php

namespace QuadLayers\AICP\Services\OpenAI\Assistants\Vector_Store_Files;

use QuadLayers\AICP\Services\OpenAI\Base;
use QuadLayers\AICP\Helpers;
use QuadLayers\AICP\Models\Admin_Menu_Services;

/**
 * API_Fetch_Post_Assistant_Vector_Store_File_OpenAi Class extends Base
 */
class Post extends Base {

	/**
	 * Function to build query url.
	 *
	 * @return string
	 */
	public function get_url( $args = null ) {
		return $this->fetch_url . '/vector_stores/' . $args['openai_id'] . '/file_batches';
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

		$body = array(
			'file_ids' => $args['vector_store_files_ids'],
		);

		$url = $this->get_url( $args );

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

		if ( is_wp_error( $response ) ) {
			throw new \Exception( esc_html__( 'Error fetching data.', 'ai-copilot' ), 404 );
		}

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

		$parsed_response = array();

		$vector_store_label = isset( $response['name'] ) ? $response['name'] : '';

		if ( strpos( $vector_store_label, $this->prefix ) === 0 ) {
			$vector_store_label = substr( $vector_store_label, strlen( $this->prefix ) );
		}

		$parsed_response = array(
			'vector_store_status' => isset( $response['status'] ) ? $response['status'] : '',
		);

		return $parsed_response;
	}
}
