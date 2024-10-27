<?php
namespace QuadLayers\AICP\Entities;

use QuadLayers\WP_Orm\Entity\CollectionEntity;

class Assistant_Vector_Store extends CollectionEntity {
	public static $primaryKey        = 'vector_store_id'; // phpcs:ignore.WordPress.NamingConventions.ValidVariableName.PropertyNotSnakeCase
	public $vector_store_id          = 0;
	public $vector_store_label       = '';
	public $vector_store_description = '';
	public $openai_id                = '';
	public $vector_store_files_ids   = array();
	public $vector_store_assistants  = array();
	public $vector_store_status      = '';
	public $is_restorable            = false;
	// phpcs:ignore
	public static $sanitizeProperties = array(
		'vector_store_label'       => 'sanitize_text_field',
		'vector_store_description' => 'wp_kses_post',
		'openai_id'                => 'sanitize_text_field',
	);
	// phpcs:ignore
	public static $validateProperties = array(
		'vector_store_label' => 'strlen',
	);
}
