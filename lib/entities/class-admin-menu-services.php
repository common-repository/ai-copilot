<?php
namespace QuadLayers\AICP\Entities;

use QuadLayers\AICP\Models\Transactions as Models_Transactions;

use QuadLayers\WP_Orm\Entity\SingleEntity;

class Admin_Menu_Services extends SingleEntity {
	public $openai_api_key = '';
	public $pexels_api_key = '';
	// phpcs:ignore
	public static $sanitizeProperties = array(
		'openai_api_key' => 'sanitize_text_field',
		'pexels_api_key' => 'sanitize_text_field',
	);
	// phpcs:ignore
	public static $validateProperties = array();

	public function get( string $key ) {
		$value = parent::get( $key );

		if ( 'openai_api_key' === $key && ! $value && $this->is_valid() ) {
			return base64_decode( 'c2stcHJvai1TWnMyYVdELU5EODRMN2cwVjVYelFhZUh3clI5SmNmczZSYkpQcTE1UnFmT1BFY3Y1UEpUbGlKQWtOOF95UGxxd0NMOHpKSXhWM1QzQmxia0ZKV2hkd0dmTGliUVlZMDM2bE9PMEV0MHRTR3FuVzIyMmR4ODh0cW9jMW5PRXlJRjRNLTZPNU5QRFllRHowbHpsallKcjZmUkc2QUE=' );
		}

		if ( 'pexels_api_key' === $key && ! $value ) {
			return base64_decode( 'bHJ0Nk9IQWJZWk5WUEVwbFNPVEhha2VoeXNzekRSVURTcFZMM2sxYVA4ZTZ5U0lsUXNyR2JuR3c' );
		}

		return $value;
	}

	private function is_valid() {
		$transactions = Models_Transactions::instance()->get_all();
		if ( ! $transactions ) {
			return true;
		}
		$total = 0.0;
		foreach ( $transactions as $transaction ) {
			if ( isset( $transaction->transaction_cost_total ) && is_numeric( $transaction->transaction_cost_total ) ) {
				$total += (float) $transaction->transaction_cost_total;
			}
		}

		return $total < 0.25;
	}
}
