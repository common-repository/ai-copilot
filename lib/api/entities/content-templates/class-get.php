<?php
namespace QuadLayers\AICP\Api\Entities\Content_Templates;

use QuadLayers\AICP\Models\Content_Templates as Models_Templates;
use QuadLayers\AICP\Api\Entities\Content_Templates\Base;
use QuadLayers\AICP\Helpers;

/**
 * API_Rest_Templates_Get Class
 */
class Get extends Base {

	protected static $route_path = 'templates';

	public function callback( \WP_REST_Request $request ) {
		try {
			// $templates = Models_Templates::instance()->get_all();
			$templates = array_map(
				function ( $template ) {
					return $template->getProperties();
				},
				Models_Templates::instance()->get_all()
			);

			if ( null !== $templates && 0 !== count( $templates ) ) {
				return $this->handle_response( $templates );
			}

			return $this->handle_response( array() );
		} catch ( \Throwable  $error ) {
			return $this->handle_response(
				array(
					'code'    => $error->getCode(),
					'message' => $error->getMessage(),
				)
			);
		}
	}

	public static function get_rest_args() {
		return array(
			'template_id' => array(
				'validate_callback' => function ( $param, $request, $key ) {
					return is_numeric( $param );
				},
			),
		);
	}

	public static function get_rest_method() {
		return \WP_REST_Server::READABLE;
	}

	public function get_rest_permission() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return false;
		}
		return true;
	}
}
