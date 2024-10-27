<?php

namespace QuadLayers\AICP\Api\Entities\Transactions;

use DateTime;
use QuadLayers\AICP\Api\Entities\Transactions\Base;
use QuadLayers\AICP\Models\Transactions as Models_Transactions;

class Get extends Base {
	protected static $route_path = 'transactions';

	public function callback( \WP_REST_Request $request ) {
		try {
			$params = $request->get_params();

			if ( empty( $params ) ) {
				$response = Models_Transactions::instance()->get_all();
				return $this->handle_response( $response );
			}

			// Params for query visulization.
			$order_by = isset( $params['order_by'] ) ? $params['order_by'] : 'date';
			$order    = isset( $params['order'] ) ? $params['order'] : 'ASC';
			$limit    = isset( $params['limit'] ) ? $params['limit'] : 0;
			$offset   = isset( $params['offset'] ) ? $params['offset'] : 0;
			$group_by = isset( $params['group_by'] ) ? $params['group_by'] : '';

			// Where for query.
			$where = array();
			if ( isset( $params['consumer_module'] ) ) {
				$where[] = array( 'consumer_module', $params['consumer_module'], '=' );
			}
			if ( isset( $params['api_type'] ) ) {
				$where[] = array( 'api_type', $params['api_type'], '=' );
			}
			if ( isset( $params['api_service'] ) ) {
				$where[] = array( 'api_service', $params['api_service'], '=' );
			}
			if ( isset( $params['api_service_model'] ) ) {
				$where[] = array( 'api_service_model', $params['api_service_model'], '=' );
			}
			if ( isset( $params['consumer_user'] ) ) {
				$where[] = array( 'consumer_user', $params['consumer_user'], '=' );
			}
			if ( isset( $params['consumer_user_role'] ) ) {
				$where[] = array( 'consumer_user_role', $params['consumer_user_role'], '=' );
			}
			if ( isset( $params['start_date'] ) ) {
				$date = new DateTime( $params['start_date'] );
				$date->setTime( 0, 0, 0 );
				$formatted_start_date = $date->format( 'Y-m-d\TH:i:s' );
				$where[]              = array( 'date', $formatted_start_date, '>=' );
			}

			if ( isset( $params['end_date'] ) ) {
				$date = new DateTime( $params['end_date'] );
				$date->setTime( 23, 59, 59 );
				$formatted_end_date = $date->format( 'Y-m-d\TH:i:s' );
				$where[]            = array( 'date', $formatted_end_date, '<=' );
			}

			$response = array();

			if ( ! empty( $group_by ) ) {
				$response = Models_Transactions::instance()->get_by_grouped( $order, $order_by, $limit, $offset, $where, $group_by );
			} else {
				$response = Models_Transactions::instance()->get_by( $order_by, $order, $limit, $offset, $where );
			}
			if ( ! $response ) {
				$response = array();
			}

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
		return \WP_REST_Server::READABLE;
	}

	public static function get_rest_args() {
		return array(
			'params' => array(
				'required' => false,
			),
		);
	}
}
