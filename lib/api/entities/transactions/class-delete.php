<?php

namespace QuadLayers\AICP\Api\Entities\Transactions;

use QuadLayers\AICP\Api\Entities\Transactions\Base;
use QuadLayers\AICP\Models\Transactions;

class Delete extends Base {
	protected static $route_path = 'transactions';

	public function callback( \WP_REST_Request $request ) {
		try {
			$id = $request->get_param( 'ID' );

			$transactions = Transactions::instance();

			$response = $transactions->delete( $id );

			return $this->handle_response( $response );
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
		return \WP_REST_Server::DELETABLE;
	}

	public static function get_rest_args() {
		return array(
			'ID' => array(
				'required' => true,
			),
		);
	}
}
