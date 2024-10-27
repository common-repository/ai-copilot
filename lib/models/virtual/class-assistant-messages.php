<?php

namespace QuadLayers\AICP\Models\Virtual;

use QuadLayers\AICP\Entities\Virtual\Assistant_Message;
use QuadLayers\WP_Orm\Builder\SingleVirtualRepositoryBuilder;

/**
 * Models_Assistant_Messages Class
 */
class Assistant_Messages {

	protected static $instance;
	protected $repository;

	private function __construct() {

		$builder = ( new SingleVirtualRepositoryBuilder() )
		->setEntity( Assistant_Message::class );

		$this->repository = $builder->getRepository();
	}

	public function create( $data ) {
		return $this->repository->create( $data );
	}

	public static function instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}
}
