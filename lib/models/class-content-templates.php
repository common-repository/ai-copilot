<?php

namespace QuadLayers\AICP\Models;

use QuadLayers\AICP\Entities\Content_Template;
use QuadLayers\WP_Orm\Builder\CollectionRepositoryBuilder;

/**
 * Models_Template Class
 */
class Content_Templates {

	protected static $instance;
	protected $repository;

	private function __construct() {

		$builder = ( new CollectionRepositoryBuilder() )
		->setTable( 'aicp_content_templates' )
		->setEntity( Content_Template::class )
		->setAutoIncrement( true );

		$this->repository = $builder->getRepository();
	}

	/**
	 * Get the database table associated with the content template.
	 *
	 * @return string
	 */
	public function get_table(): string {
		return $this->repository->getTable();
	}

	/**
	 * Get the args associated with the content template.
	 *
	 * @return array
	 */
	public function get_args(): array {
		$entity   = new Content_Template();
		$defaults = $entity->getDefaults();
		return $defaults;
	}

	/**
	 * Get the args associated with the content template.
	 *
	 * @param int $id
	 * @return Content_Template
	 */
	public function get( int $id ): ?Content_Template {
		return $this->repository->find( $id );
	}

	/**
	 * Delete content template entitie from the repository.
	 *
	 * @param int $id
	 * @return bool
	 */
	public function delete( int $id ): bool {
		return $this->repository->delete( $id );
	}

	/**
	 * Update content template
	 *
	 * @param int   $id
	 * @param array $data
	 * @return Content_Template
	 */
	public function update( int $id, array $data ): Content_Template {
		return $this->repository->update( $id, $data );
	}

	/**
	 * Create content template
	 *
	 * @param array $data
	 * @return Content_Template
	 */
	public function create( array $data ): Content_Template {
		if ( isset( $data['template_id'] ) ) {
			unset( $data['template_id'] );
		}
		return $this->repository->create( $data );
	}

	/**
	 * Get all content template
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
	 * Delete all content templates
	 *
	 * @return bool
	 */
	public function delete_all(): bool {
		return $this->repository->deleteAll();
	}

	public static function instance(): Content_Templates {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}
}
