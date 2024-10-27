<?php

namespace QuadLayers\AICP\Models;

use QuadLayers\AICP\Entities\Chatbot;
use QuadLayers\WP_Orm\Builder\SingleRepositoryBuilder;

/**
 * Models_Chatbot Class
 */
class Admin_Menu_Chatbot {

	protected static $instance;
	protected $repository;

	private function __construct() {

		$builder = ( new SingleRepositoryBuilder() )
		->setTable( 'aicp_chatbot' )
		->setEntity( Chatbot::class );

		$this->repository = $builder->getRepository();
	}

	/**
	 * Get the database table associated with the chatbot.
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
	public function get(): Chatbot {
		$entity = $this->repository->find();

		if ( $entity ) {
			return $entity;
		} else {
			$admin = new Chatbot();
			return $admin;
		}
	}

	/**
	 * Delete all chatbot entities from the repository.
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
	public function save( $data ): bool {
		return $this->repository->create( $data );
	}


	public static function instance(): Admin_Menu_Chatbot {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}
}
