<?php

namespace QuadLayers\AICP\Models;

use QuadLayers\AICP\Entities\Action_Template;
use QuadLayers\WP_Orm\Builder\CollectionRepositoryBuilder;

/**
 * Models_Action Class
 */
class Action_Templates {

	protected static $instance;
	protected $repository;

	private function __construct() {

		$builder = ( new CollectionRepositoryBuilder() )
		->setTable( 'aicp_action_templates' )
		->setEntity( Action_Template::class )
		->setAutoIncrement( true );

		$this->repository = $builder->getRepository();
	}

	public function get_table() {
		return $this->repository->getTable();
	}

	/**
	 * Get args template
	 *
	 * @return array
	 */
	public function get_args(): array {
		$entity   = new Action_Template();
		$defaults = $entity->getDefaults();
		return $defaults;
	}

	/**
	 * Get action template
	 *
	 * @param int $id
	 * @return Action_Template
	 */
	public function get( int $id ): ?Action_Template {
		return $this->repository->find( $id );
	}

	/**
	 * Delete action template
	 *
	 * @param int $id
	 * @return bool
	 */
	public function delete( int $id ): bool {
		return $this->repository->delete( $id );
	}

	/**
	 * Update action template
	 *
	 * @param int   $id
	 * @param array $data
	 * @return Action_Template
	 */
	public function update( int $id, array $data ): Action_Template {
		return $this->repository->update( $id, $data );
	}

	/**
	 * Create action template
	 *
	 * @param array $data
	 * @return Action_Template
	 */
	public function create( array $data ): Action_Template {
		if ( isset( $data['action_id'] ) ) {
			unset( $data['action_id'] );
		}

		return $this->repository->create( $data );
	}

	/**
	 * Get all action template
	 *
	 * @return array
	 */
	public function get_all(): array {
		$entities = $this->repository->findAll();
		if ( ! $entities ) {
			return array();
		}
		return $entities;
	}

	/**
	 * Get all action template
	 *
	 * @return bool
	 */
	public function delete_all(): bool {
		return $this->repository->deleteAll();
	}

	public static function instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}
}
