<?php

namespace QuadLayers\AICP\Models;

use QuadLayers\AICP\Entities\Assistant_Vector_Store;
use QuadLayers\WP_Orm\Builder\CollectionRepositoryBuilder;

/**
 * Models_Vector_Stores Class
 */
class Assistants_Vector_Stores {

	protected static $instance;
	protected $repository;

	private function __construct() {

		$builder = ( new CollectionRepositoryBuilder() )
		->setTable( 'aicp_vector_stores' )
		->setEntity( Assistant_Vector_Store::class )
		->setAutoIncrement( true )
		->setDefaultEntities(
			array(
				array(
					'vector_store_id'          => 0,
					'vector_store_label'       => 'Knowledge / QuadLayers',
					'vector_store_description' => 'Knowledge base of QuadLayers products.',
					'openai_id'                => 'vs_ovyxLUTWUOX0MpAqhMH6zDQU',
					'vector_store_files_ids'   => array(
						'file-P2mCnfk5ZvZSF4CGfYEjmr0Q',
					),
					'vector_store_status'      => 'completed',
					'is_restorable'            => false,
				),
			)
		);

		$this->repository = $builder->getRepository();
	}

	/**
	 * Get the database table associated with the vector store.
	 *
	 * @return string
	 */
	public function get_table(): string {
		return $this->repository->getTable();
	}

	/**
	 * Get the args associated with the vector store.
	 *
	 * @return array
	 */
	public function get_args(): array {
		$entity   = new Assistant_Vector_Store();
		$defaults = $entity->getDefaults();
		return $defaults;
	}

	/**
	 * Get the args associated with the vector store.
	 *
	 * @param int $id
	 * @return Assistant_Vector_Store
	 */
	public function get( int $id ): ?Assistant_Vector_Store {
		return $this->repository->find( $id );
	}

	/**
	 * Delete vector store entitie from the repository.
	 *
	 * @param int $id
	 * @return bool
	 */
	public function delete( int $id ): bool {
		return $this->repository->delete( $id );
	}

	/**
	 * Update vector store
	 *
	 * @param int   $id
	 * @param array $data
	 * @return ?Assistant_Vector_Store
	 */
	public function update( int $id, array $data ): ?Assistant_Vector_Store {
		return $this->repository->update( $id, $data );
	}

	/**
	 * Create vector store
	 *
	 * @param array $data
	 * @return Assistant_Vector_Store
	 */
	public function create( array $data ): Assistant_Vector_Store {
		if ( isset( $data['vector_store_id'] ) ) {
			unset( $data['vector_store_id'] );
		}
		return $this->repository->create( $data );
	}

	/**
	 * Get all vector stores
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
	 * Delete all vector stores
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
