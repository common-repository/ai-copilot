<?php
namespace QuadLayers\AICP\Entities;

use QuadLayers\AICP\Services\Visibility\Options as Service_Visibility_Options;
use QuadLayers\WP_Orm\Entity\CollectionEntity;

class Assistant extends CollectionEntity {
	public static $primaryKey             = 'assistant_id'; // phpcs:ignore.WordPress.NamingConventions.ValidVariableName.PropertyNotSnakeCase
	public $assistant_id                  = 0;
	public $assistant_description         = '';
	public $assistant_first_message       = '';
	public $assistant_label               = '';
	public $assistant_origin              = 'system';
	public $assistant_post_type           = array( 'all' );
	public $model                         = 'gpt-3.5-turbo';
	public $prompt_system                 = '';
	public $tools                         = array();
	public $assistant_vector_store_ids    = array();
	public $assistant_vector_store_labels = array();
	public $openai_id                     = '';
	public $visibility                    = array();
	// phpcs:ignore
	public static $sanitizeProperties     = array(
		'assistant_label'         => 'sanitize_text_field',
		'assistant_first_message' => 'sanitize_text_field',
		'assistant_description'   => 'wp_kses_post',
		'model'                   => 'wp_kses_post',
		'prompt_system'           => 'wp_kses_post',
		'openai_id'               => 'sanitize_text_field',
	);
	// phpcs:ignore
	public static $validateProperties     = array(
		'assistant_label' => 'strlen',
		'prompt_system'   => 'strlen',
	);

	public function __construct() {
		$this->visibility = Service_Visibility_Options::instance()->get_args();
	}
}
