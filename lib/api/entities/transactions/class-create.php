<?php

namespace QuadLayers\AICP\Api\Entities\Transactions;

use QuadLayers\AICP\Api\Entities\Transactions\Base;
use QuadLayers\AICP\Models\Transactions;

class Create extends Base {
	protected static $route_path = 'transactions';

	public function callback( \WP_REST_Request $request ) {
		try {
			$body = json_decode( $request->get_body(), true );

			$transaction = $body['transaction'];

			$transaction_defaults_fields = array(
				'consumer_module',
				'api_type',
				'api_service',
				'api_service_model',
				'tokens_qty_input',
				'tokens_qty_output',
				'transaction_cost_input',
				'transaction_cost_output',
			);

			foreach ( $transaction_defaults_fields as $field ) {
				if ( ! isset( $transaction[ $field ] ) ) {
					throw new \Exception( sprintf( esc_html__( 'The following field is missing: %s.', 'ai-copilot' ), $field ), 400 );
				}
			}

			$current_user_id              = get_current_user_id();
			$transaction['consumer_user'] = $current_user_id;

			if ( 0 !== $current_user_id ) {
				$user              = get_userdata( $current_user_id );
				$user_roles        = $user->roles;
				$current_user_role = reset( $user_roles );

				$transaction['consumer_user_role'] = $current_user_role;
			} else {
				$transaction['consumer_user_role'] = 'reader';
			}

			$transactions = Transactions::instance();

			$response = $transactions->create( $transaction );

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
		return \WP_REST_Server::CREATABLE;
	}

	public static function get_rest_args() {
		return array();
	}

	public function get_rest_permission() {
		return true;
	}
}
