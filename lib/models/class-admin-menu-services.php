<?php
namespace QuadLayers\AICP\Models;

use QuadLayers\AICP\Entities\Admin_Menu_Services as Admin_Menu_Services_Entity;

use QuadLayers\WP_Orm\Builder\SingleRepositoryBuilder;

class Admin_Menu_Services {

	protected static $instance;
	protected $repository;

	private function __construct() {
		$builder = ( new SingleRepositoryBuilder() )
		->setTable( 'aicp_ai' )
		->setEntity( Admin_Menu_Services_Entity::class );

		$this->repository = $builder->getRepository();
	}

	/**
	 * Get the database table associated with the admin menu services.
	 *
	 * @return string
	 */
	public function get_table(): string {
		return $this->repository->getTable();
	}

	/**
	 * Retrieve the chatbot entity from the repository.
	 * If no entity exists, a new Chatbot object is returned.
	 *
	 * @return Chatbot
	 */
	public function get() {
		$entity = $this->repository->find();

		if ( $entity ) {
			return $entity;
		} else {
			$admin = new Admin_Menu_Services_Entity();
			return $admin;
		}
	}

	/**
	 * Delete all menu services entities from the repository.
	 *
	 * @return bool
	 */
	public function delete_all() {
		return $this->repository->delete();
	}

	/**
	 * Save the admin menu services entity to the repository.
	 *
	 * @param array $data The data for the new chatbot entity.
	 * @return bool
	 */
	public function save( $data ) {
		return $this->repository->create( $data );
	}

	public static function instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}
}
