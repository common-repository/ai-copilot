<?php

namespace QuadLayers\AICP\Services\OpenAI\Completions;

use QuadLayers\AICP\Services\OpenAI\Base;

/**
 * API_Fetch_Text_OpenAi Class extends Base
 */
class Post extends Base {

	/**
	 * Function to build query url.
	 *
	 * @return string
	 */
	public function get_url( $args ) {
		return $this->fetch_url . '/completions';
	}

	/**
	 * Function to parse response to usable data.
	 *
	 * @param array $response Raw response from openai.
	 * @return array
	 */
	public function response_to_data( $response = null ) {

		if ( ! empty( $response['choices'] ) ) {
			$choice = $response['choices'][0];
			$text   = '';

			if ( isset( $choice['message'] ) ) {
				$text = $choice['message']['content'];
			} else {
				$text = $choice['text'];
			}

			$usage = $response['usage'];

			return array(
				'text'              => $text,
				'prompt_tokens'     => $usage['prompt_tokens'],
				'completion_tokens' => isset( $usage['completion_tokens'] ) ? $usage['completion_tokens'] : 0,
				'total_tokens'      => $usage['total_tokens'],
			);
		}

		return $response;
	}
}
