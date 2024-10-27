<?php

namespace QuadLayers\AICP\Models\Virtual;

use QuadLayers\AICP\Entities\Virtual\Completion;
use QuadLayers\WP_Orm\Builder\SingleVirtualRepositoryBuilder;

/**
 * Models_Completions Class
 */
class Completions {

	protected static $instance;
	protected $repository;

	private function __construct() {

		$builder = ( new SingleVirtualRepositoryBuilder() )
		->setEntity( Completion::class );

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
