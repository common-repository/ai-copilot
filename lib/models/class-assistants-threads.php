<?php

namespace QuadLayers\AICP\Models;

use Symlink\ORM\Manager;
use Symlink\ORM\Mapping;
use QuadLayers\AICP\Entities\Assistant_Thread as Assistant_Thread_Entity;

/**
 * Models_Assistant_Threads Class
 */
class Assistants_Threads {

	protected static $instance;
	protected $orm;
	protected $repository;

	private function __construct() {
		$orm              = Manager::getManager();
		$this->orm        = $orm;
		$repository       = $this->orm->getRepository( Assistant_Thread_Entity::class );
		$this->repository = $repository;
	}

	/**
	 * Create table
	 */
	public static function create_table() {
		$mapper = Mapping::getMapper();
		$mapper->updateSchema( Assistant_Thread_Entity::class );
	}

	/**
	 * Get all threads
	 *
	 * @param int $page
	 * @param int $limit
	 *
	 * @return mixed
	 */
	public function get_all( int $page = 0, int $limit = 0 ): mixed {
		$offset = ( $page - 1 ) * $limit;
		$query  = $this->repository->createQueryBuilder()
			->orderBy( 'ID', 'DESC' )
			->limit( $limit, $offset )
			->buildQuery();
		return $query->getResults( true );
	}

	/**
	 * Get all threads
	 *
	 * @param string $order_by
	 * @param string $order
	 * @param int    $limit
	 * @param int    $offset
	 * @param array  $where
	 *
	 * @return mixed
	 */
	public function get_by( $order_by = 'ID', $order = 'DESC', $limit = 0, $offset = 0, $where = array() ): mixed {
		$query = $this->repository->createQueryBuilder();
		$query->orderBy( $order_by, $order );
		$query->limit( $limit, $offset );
		if ( ! empty( $where ) ) {
			foreach ( $where as $key => $value ) {
				$query->where( $value[0], $value[1], $value[2] );
			}
		}
		$query->buildQuery();
		return $query->getResults( true );
	}

	/**
	 * Parse where
	 *
	 * @param array $where_array
	 *
	 * @return string
	 */
	public function parse_where( $where_array ): string {
		$where_query = '';
		if ( 0 === count( $where_array ) ) {
			return $where_query;
		}
		$where_query     .= 'WHERE ';
		$total_conditions = count( $where_array );
		for ( $i = 0; $i < $total_conditions; $i++ ) {
			$value        = $where_array[ $i ];
			$where_query .= "$value[0] $value[2] '$value[1]'";

			if ( $i < $total_conditions - 1 ) {
				$where_query .= ' AND ';
			}
		}
		return $where_query;
	}

	/**
	 * Get table name
	 *
	 * @return string
	 */
	public function get_table_name(): string {
		global $wpdb;
		return $wpdb->prefix . $this->repository->getDBTable();
	}

	/**
	 * Create thread
	 *
	 * @param array $data
	 *
	 * @return Assistant_Thread_Entity
	 */
	public function create( array $data ): Assistant_Thread_Entity {
		$sixty_days_in_seconds = 60 * DAY_IN_SECONDS;
		$expired_at            = $data['created_at'] + $sixty_days_in_seconds;
		$thread                = new Assistant_Thread_Entity();
		$thread->set( 'assistant_id', $data['assistant_id'] );
		$thread->set( 'openai_id', $data['openai_id'] );
		$thread->set( 'created_at', $data['created_at'] );
		$thread->set( 'expired_at', $expired_at );
		if ( ! $this->save( $thread ) ) {
			return false;
		}
		return $thread;
	}

	/**
	 * Save thread
	 *
	 * @param Assistant_Thread_Entity $thread
	 *
	 * @return bool
	 */
	public function save( Assistant_Thread_Entity $thread ): bool {
		try {
			$this->orm->persist( $thread );
			$this->orm->flush();
			return true;
		} catch ( \Throwable  $error ) {
			return false;
		}
	}

	/**
	 * Delete thread
	 *
	 * @param Assistant_Thread_Entity $thread
	 *
	 * @return bool
	 */
	public function delete( $thread ): bool {
		try {
			if ( ! $thread instanceof Assistant_Thread_Entity ) {
				$thread = $this->find( $thread );
			}
			if ( ! $thread instanceof Assistant_Thread_Entity ) {
				return false;
			}
			$this->orm->remove( $thread );
			$this->orm->flush();
			return true;
		} catch ( \Throwable  $error ) {
			return false;
		}
	}

	/**
	 * Find thread
	 *
	 * @param string $id
	 *
	 * @return Assistant_Thread_Entity
	 */
	public function find( $id ): Assistant_Thread_Entity {
		try {
			$assistant_thread_entity = $this->repository->find( $id );
			return $assistant_thread_entity;
		} catch ( \Throwable  $error ) {
			return false;
		}
	}

	public static function instance(): Assistants_Threads {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}
}
