<?php

namespace QuadLayers\AICP\Services\Pexels\Images;

use QuadLayers\AICP\Services\Pexels\Base;
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
		$url = $this->fetch_url . '/search';

		$params = array(
			'query'       => isset( $args['query'] ) ? $args['query'] : '',
			'orientation' => isset( $args['orientation'] ) ? $args['orientation'] : 'landscape',
			'page'        => isset( $args['page'] ) ? $args['page'] : 0,
			'per_page'    => isset( $args['per_page'] ) ? $args['per_page'] : 4,
			'color'       => isset( $args['color'] ) ? $args['color'] : '',
			'locale'      => isset( $args['locale'] ) ? $args['locale'] : '',
			'size'        => isset( $args['size'] ) ? $args['size'] : 'medium',
		);

		$url = $url . '?' . http_build_query( $params );

		return $url;
	}

	/**
	 * Function to handle error on query response.
	 *
	 * @param array $response response.
	 * @return array
	 */
	public function handle_error( $response = null ) {
		/*
		Pexels error structure:
		response: {
			"status": 401,
			"code": "Unauthorized"
		}
		*/
		$is_error = isset( $response['code'] ) && 0 !== $response['code'];

		if ( isset( $response['status'] ) && $response['status'] === 401 ) {
			$response['code'] = esc_html__( 'Unauthorized Pexels API key.', 'ai-copilot' );
		}

		if ( $is_error ) {
			$message = isset( $response['code'] ) ? $response['code'] : esc_html__( 'Unknown error.', 'ai-copilot' );
			$code    = isset( $reponse['status'] ) ? $response['status'] : 413;
			return array(
				'code'    => $code,
				'message' => $message,
			);
		}
		return false;
	}
}
