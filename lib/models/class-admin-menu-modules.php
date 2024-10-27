<?php
namespace QuadLayers\AICP\Models;

use QuadLayers\AICP\Entities\Admin_Menu_Modules as Admin_Menu_Modules_Entity;

use QuadLayers\WP_Orm\Builder\SingleRepositoryBuilder;

class Admin_Menu_Modules {

	protected static $instance;
	protected $repository;

	private function __construct() {
		$builder = ( new SingleRepositoryBuilder() )
		->setTable( 'aicp_features' )
		->setEntity( Admin_Menu_Modules_Entity::class );

		$this->repository = $builder->getRepository();
	}


	/**
	 * Get the database table associated with the admin menu.
	 *
	 * @return string
	 */
	public function get_table(): string {
		return $this->repository->getTable();
	}

	/**
	 * Retrieve the admin menu modules entity from the repository.
	 * If no entity exists, a new admin menu modules object is returned.
	 *
	 * @return Admin_Menu_Modules_Entity
	 */
	public function get(): Admin_Menu_Modules_Entity {
		$entity = $this->repository->find();

		if ( $entity ) {
			return $entity;
		} else {
			$admin = new Admin_Menu_Modules_Entity();
			return $admin;
		}
	}

	/**
	 * Delete all admin menu modules entities from the repository.
	 *
	 * @return bool
	 */
	public function delete_all(): bool {
		return $this->repository->delete();
	}

	/**
	 * Save a new chatbot entity to the repository.
	 *
	 * @param array $data The data for the new chatbot entity.
	 * @return bool
	 */
	public function save( $data ) {
		return $this->repository->create( $data );
	}

	public static function instance(): Admin_Menu_Modules {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}
}
