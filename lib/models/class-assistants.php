<?php

namespace QuadLayers\AICP\Models;

use QuadLayers\AICP\Entities\Assistant;
use QuadLayers\WP_Orm\Builder\CollectionRepositoryBuilder;

/**
 * Models_Assistant Class
 */
class Assistants {

	protected static $instance;
	protected $repository;

	private function __construct() {

		$builder = ( new CollectionRepositoryBuilder() )
		->setTable( 'aicp_assistants' )
		->setEntity( Assistant::class )
		->setAutoIncrement( true )
		->setDefaultEntities(
			array(
				array(
					'assistant_id'               => 0,
					'assistant_description'      => 'Ask about any QuadLayers product.',
					'assistant_first_message'    => 'Hi! How can I help you?',
					'assistant_label'            => 'QuadLayers Bot',
					'assistant_origin'           => 'user',
					'model'                      => 'gpt-4o-mini',
					'prompt_system'              => 'You are a sales assistant inside a chat designed to help customers find products and services offered by QuadLayers. 

QuadLayers is a company specializing in the development of plugins for WordPress. Your goal is to provide the information inside the documents and guide the customer towards a successful purchase.

About QuadLayers:
- Website: www.quadlayers.com
- Support: www.quadlayers.com/account/support
- Typical Customers: QuadLayers’ customers usually include website owners, developers, and small businesses looking to enhance the functionality and design of their WordPress sites.
- Value Proposition: QuadLayers stands out by offering high-quality WordPress plugins, efficient technical support, and regular updates to ensure compatibility with the latest versions of WordPress.

Your Objectives:
- Product Information: Provide clear and concise details about QuadLayers’ plugins.
- Sales: Guide the customer towards purchasing products or services based on their needs.
- Support Redirection: Any inquiries related to technical support should be redirected to the official support website.

Your Dialogue Guidelines:
- Sales Focus: Prioritize selling products and services that align with the customer\'s needs. Ask relevant questions to identify their preferences.
- Tone and Language: Use a friendly, professional, and customer-oriented tone. Be clear and direct in your communication.
- Response: Provide concise answers, each no longer than 300 characters.

Your Restrictions:
- Don\'t play games.
- Respect personal privacy.
- Do not request or share personal information.
- Provide only known information. If you are unsure about something, direct the customer to the website.
- I\'f the query of the user is not related to the scope of this instructions, direct the customer to the website.

Products:
- "item_title": contains the name of the plugin or product.
- "item_content": contains the description of product or plugin.
- "item_link" contains the link to the product or plugin.',
					'tools'                      => array(
						array(
							'type'        => 'file_search',
							'file_search' => array(
								'ranking_options' => array(
									'ranker'          => 'default_2024_08_21',
									'score_threshold' => 0.0,
								),
							),
						),
					),
					'assistant_vector_store_ids' => array(
						'vs_ovyxLUTWUOX0MpAqhMH6zDQU',
					),
					'openai_id'                  => 'asst_2JccEFYWzYh7y2QKR4a6YMf3',
				),
			)
		);

		$this->repository = $builder->getRepository();
	}

	/**
	 * Get the database table associated with the assistants.
	 *
	 * @return string
	 */
	public function get_table(): string {
		return $this->repository->getTable();
	}

	/**
	 * Get the args associated with the assistants.
	 *
	 * @return array
	 */
	public function get_args(): array {
		$entity   = new Assistant();
		$defaults = $entity->getDefaults();
		return $defaults;
	}

	/**
	 * Get the args associated with the assistants.
	 *
	 * @param int $id
	 * @return Assistant
	 */
	public function get( int $id ): ?Assistant {
		return $this->repository->find( $id );
	}

	/**
	 * Delete assistants entitie from the repository.
	 *
	 * @param int $id
	 * @return bool
	 */
	public function delete( int $id ): bool {
		return $this->repository->delete( $id );
	}

	/**
	 * Update assistant
	 *
	 * @param int   $id
	 * @param array $data
	 * @return ?Assistant
	 */
	public function update( int $id, array $data ): ?Assistant {
		return $this->repository->update( $id, $data );
	}

	/**
	 * Create assistant
	 *
	 * @param array $data
	 * @return Assistant
	 */
	public function create( array $data ): Assistant {
		if ( isset( $data['assistant_id'] ) ) {
			unset( $data['assistant_id'] );
		}
		return $this->repository->create( $data );
	}

	/**
	 * Get all assistant
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
	 * Delete all assistants
	 *
	 * @return bool
	 */
	public function delete_all(): bool {
		return $this->repository->deleteAll();
	}

	public static function instance(): Assistants {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}
}
