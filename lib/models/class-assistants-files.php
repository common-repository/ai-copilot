<?php

namespace QuadLayers\AICP\Models;

use QuadLayers\AICP\Entities\Assistant_File;
use QuadLayers\WP_Orm\Builder\CollectionRepositoryBuilder;

/**
 * Models_Assistants_Files Class
 */
class Assistants_Files {

	protected static $instance;
	protected $repository;

	private function __construct() {

		$builder = ( new CollectionRepositoryBuilder() )
		->setTable( 'aicp_assistant_files' )
		->setEntity( Assistant_File::class )
		->setAutoIncrement( true )
		->setDefaultEntities(
			array(
				array(
					'file_id'          => 0,
					'file_label'       => '2024/10/knowledge.json',
					'file_description' => 'This document contains detailed information on vehicle products.',
					'file_origin'      => 'user',
					'file_size_bytes'  => 86751,
					'openai_id'        => 'file-P2mCnfk5ZvZSF4CGfYEjmr0Q',
					'attachment_id'    => 54,
					'attachment_url'   => '',
					'file_status'      => 'ready',
					'post_types'       => array(
						'product',
					),
					'vector_stores'    => array(),
					'file_date'        => gmdate( 'Y-m-d' ),
					'is_restorable'    => false,
				),
			)
		);

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
	 * Get args files
	 *
	 * @return array
	 */
	public function get_args(): array {
		$entity   = new Assistant_File();
		$defaults = $entity->getDefaults();
		return $defaults;
	}

	/**
	 * Get assistant file
	 *
	 * @param int $id
	 * @return Assistant_File
	 */
	public function get( int $id ): ?Assistant_File {
		return $this->repository->find( $id );
	}

	/**
	 * Delete assistant file
	 *
	 * @param int $id
	 * @return bool
	 */
	public function delete( int $id ): bool {

		$entity = $this->repository->find( $id );
		if ( ! $entity ) {
			return false;
		}

		if ( $entity->attachment_id ) {
			wp_delete_attachment( $entity->attachment_id, true );
		}

		return $this->repository->delete( $id );
	}

	/**
	 * Update assistant file
	 *
	 * @param int   $id
	 * @param array $data
	 * @return Assistant_File
	 */
	public function update( int $id, array $data ): ?Assistant_File {
		return $this->repository->update( $id, $data );
	}

	/**
	 * Create assistant template
	 *
	 * @param array $data
	 * @return Assistant_File
	 */
	public function create( array $data ): Assistant_File {
		if ( isset( $data['file_id'] ) ) {
			unset( $data['file_id'] );
		}

		return $this->repository->create( $data );
	}

	/**
	 * Get all assistant files
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
	 * Delete all assistant files entities from the repository.
	 *
	 * @return bool
	 */
	public function delete_all(): bool {
		return $this->repository->deleteAll();
	}

	public static function instance(): Assistants_Files {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}
}
