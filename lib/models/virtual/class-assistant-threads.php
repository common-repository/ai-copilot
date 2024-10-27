<?php

namespace QuadLayers\AICP\Models\Virtual;

use QuadLayers\AICP\Entities\Virtual\Assistant_Thread;
use QuadLayers\WP_Orm\Builder\SingleVirtualRepositoryBuilder;

/**
 * Models_Assistant_Threads Class
 */
class Assistant_Threads {

	protected static $instance;
	protected $repository;

	private function __construct() {

		$builder = ( new SingleVirtualRepositoryBuilder() )
		->setEntity( Assistant_Thread::class );

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
