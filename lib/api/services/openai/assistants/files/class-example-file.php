<?php

namespace QuadLayers\AICP\Api\Services\OpenAI\Assistants\Files;

use QuadLayers\AICP\Api\Services\OpenAI\Base;

class Example_File extends Base {
	protected static $route_path = 'assistants/file_example';

	public function callback( \WP_REST_Request $request ) {
		try {
			// Path to the file in the plugin directory
			$example_file_path = QUADLAYERS_AICP_PLUGIN_DIR . 'assets/backend/dummy-data/knowledge.json';

			// Verify that the file exists
			if ( ! file_exists( $example_file_path ) ) {
				return new \WP_Error( 400, esc_html__( 'The file is not in the plugin directory', 'ai-copilot' ) );
			}

			// Get the uploads directory
			$upload_dir = wp_upload_dir();

			// Generate a unique route for the file in the uploads directory
			$unique_file_name = wp_unique_filename( $upload_dir['path'], basename( $example_file_path ) );
			$new_file_path    = $upload_dir['path'] . '/' . $unique_file_name;

			// Copy the file to the uploads directory
			if ( ! copy( $example_file_path, $new_file_path ) ) {
				return new \WP_Error( 412, esc_html__( 'There was an error copying the file to the uploads directory', 'ai-copilot' ) );
			}

			// Prepare the file data for insertion
			$filetype   = wp_check_filetype( $unique_file_name, null );
			$attachment = array(
				'guid'           => $upload_dir['url'] . '/' . basename( $new_file_path ),
				'post_mime_type' => $filetype['type'],
				'post_title'     => sanitize_file_name( basename( $new_file_path ) ),
				'post_content'   => '',
				'post_status'    => 'inherit',
			);

			// Insert the file into the Media Gallery
			$attachment_id = wp_insert_attachment( $attachment, $new_file_path );

			// Verify if the insertion was successful
			if ( is_wp_error( $attachment_id ) ) {
				return $this->handle_response( $attachment_id );
			}

			// Generate the attachment metadata
			require_once ABSPATH . 'wp-admin/includes/image.php';
			$attachment_data = wp_generate_attachment_metadata( $attachment_id, $new_file_path );
			wp_update_attachment_metadata( $attachment_id, $attachment_data );

			return $this->handle_response( array( 'attachment_id' => $attachment_id ) );
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
		return array();
	}
}
