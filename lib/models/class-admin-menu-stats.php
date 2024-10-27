<?php

namespace QuadLayers\AICP\Models;

use QuadLayers\WP_Orm\Builder\SingleRepositoryBuilder;
use QuadLayers\AICP\Entities\Admin_Menu_Stats as Admin_Menu_Stats_Entity;

class Admin_Menu_Stats {

	protected static $instance;
	protected $repository;

	private function __construct() {
		$builder = ( new SingleRepositoryBuilder() )
		->setTable( 'aicp_admin_menu_stats' )
		->setEntity( Admin_Menu_Stats_Entity::class );

		$this->repository = $builder->getRepository();
	}

	/**
	 * Get the database table associated with the admin menu stats.
	 *
	 * @return string
	 */
	public function get_table(): string {
		return $this->repository->getTable();
	}

	/**
	 * Retrieve the Admin Menu Stats entity from the repository.
	 * If no entity exists, a new Admin Menu Stats object is returned.
	 *
	 * @return Admin_Menu_Stats_Entity
	 */
	public function get(): Admin_Menu_Stats_Entity {
		$entity = $this->repository->find();

		if ( $entity ) {
			return $entity;
		} else {
			$admin = new Admin_Menu_Stats_Entity();
			return $admin;
		}
	}

	/**
	 * Delete all Admin Menu Stats entities from the repository.
	 *
	 * @return bool
	 */
	public function delete_all(): bool {
		return $this->repository->delete();
	}

	/**
	 * Save Admin Menu Stats entity to the repository.
	 *
	 * @param array $data The data for the new chatbot entity.
	 * @return bool
	 */
	public function save( $data ): bool {
		return $this->repository->create( $data );
	}

	public static function instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}
}
