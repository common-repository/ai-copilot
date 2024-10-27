<?php
namespace QuadLayers\AICP\Entities;

use QuadLayers\WP_Orm\Entity\CollectionEntity;

class Assistant_File extends CollectionEntity {
	public static $primaryKey = 'file_id'; // phpcs:ignore.WordPress.NamingConventions.ValidVariableName.PropertyNotSnakeCase
	public $file_id           = 0;
	public $file_label        = '';
	public $file_description  = '';
	public $file_origin       = 'user';
	public $file_size_bytes   = 0;
	public $openai_id         = '';
	public $attachment_id     = 0;
	public $attachment_url    = '';
	public $file_status       = '';
	public $post_types        = array();
	public $vector_stores     = array();
	public $file_date;
	public $is_restorable = false;
	// phpcs:ignore
	public static $sanitizeProperties = array(
		'file_label'       => 'sanitize_text_field',
		'file_description' => 'wp_kses_post',
		'openai_id'        => 'sanitize_text_field',
	);
	// phpcs:ignore
	public static $validateProperties = array(
		'file_label' => 'strlen',
	);

	public function __construct() {
		$this->file_date = gmdate( 'Y-m-d' );
	}
}
