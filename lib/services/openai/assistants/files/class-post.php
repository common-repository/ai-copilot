<?php

namespace QuadLayers\AICP\Services\OpenAI\Assistants\Files;

use QuadLayers\AICP\Services\OpenAI\Base;
use QuadLayers\AICP\Helpers;
use QuadLayers\AICP\Models\Admin_Menu_Services;

/**
 * API_Fetch_Assistant_OpenAi Class extends Base
 */
class Post extends Base {

	/**
	 * Function to build query url.
	 *
	 * @return string
	 */
	public function get_url( $args = null ) {
		return $this->fetch_url . '/files';
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
			'Authorization: Bearer ' . $api_key,
		);

		$file_path  = isset( $args['file']['tmp_name'] ) ? $args['file']['tmp_name'] : '';
		$cfile      = new \CURLFile( $file_path, $args['file']['type'], $args['file']['name'] );
		$postfields = array(
			'file'    => $cfile,
			'purpose' => 'assistants',
		);

		$url     = $this->get_url( $args );
		$timeout = Helpers::get_timeout();

		$ch = curl_init( $url );
		curl_setopt( $ch, CURLOPT_HTTPHEADER, $headers );
		curl_setopt( $ch, CURLOPT_POST, true );
		curl_setopt( $ch, CURLOPT_POSTFIELDS, $postfields );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $ch, CURLOPT_TIMEOUT, $timeout );

		$response = curl_exec( $ch );

		$response = $this->handle_response( $response );

		return $response;
	}

	/**
	 * Function to handle query response.
	 *
	 * @param array $response
	 * @return array
	 */
	public function handle_response( $response = null ) {

		$data = json_decode( $response, true );

		if ( null === $data && json_last_error() !== JSON_ERROR_NONE ) {
			$data = array(
				'error' => array(
					'code'    => 404,
					'message' => sprintf( esc_html__( 'Response is not valid json: %s', 'ai-copilot' ), $data ),
				),
			);
		}

		return $this->handle_error( $data ) ? $this->handle_error( $data ) : $data;
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
			return array(
				'openai_id'       => isset( $response['id'] ) ? $response['id'] : '',
				'file_label'      => isset( $response['filename'] ) ? $response['filename'] : '',
				'file_size_bytes' => isset( $response['bytes'] ) ? $response['bytes'] : 0,
			);
		}

		$parsed_response = array();
		foreach ( $response['data'] as $data ) {
			$parsed_response[] = array(
				'openai_id'       => isset( $data['id'] ) ? $data['id'] : '',
				'file_label'      => isset( $data['filename'] ) ? $data['filename'] : '',
				'file_size_bytes' => isset( $data['bytes'] ) ? $data['bytes'] : 0,
			);
		}

		return $parsed_response;
	}
}
