<?php
namespace QuadLayers\AICP\Entities;

use QuadLayers\WP_Orm\Entity\CollectionEntity;

class Action_Template extends CollectionEntity {
	public static $primaryKey         = 'action_id'; // phpcs:ignore.WordPress.NamingConventions.ValidVariableName.PropertyNotSnakeCase
	public $action_id                 = 0;
	public $action_label              = '';
	public $action_origin             = 'user';
	public $action_description        = '';
	public $action_post_type          = array( 'all' );
	public $action_type               = 'replace';
	public $variables_language        = 'preserve_text_language';
	public $variables_style           = '';
	public $variables_tone            = '';
	public $prompt_system             = '';
	public $prompt_user               = '';
	public $model                     = 'gpt-3.5-turbo';
	public $model_temperature         = 0.8;
	public $model_max_tokens          = 2048;
	public static $sanitizeProperties = array(
		'action_label'       => 'sanitize_text_field',
		'action_description' => 'wp_kses_post',
		'action_type'        => 'sanitize_key',
		'variables_language' => 'sanitize_key',
		'variables_style'    => 'sanitize_key',
		'variables_tone'     => 'sanitize_key',
		'prompt_system'      => 'wp_kses_post',
		'prompt_user'        => 'wp_kses_post',
		'model'              => 'wp_kses_post',
	);
	public static $validateProperties = array(
		'action_label'  => 'strlen',
		// 'action_description' => 'strlen',
		'prompt_system' => 'strlen',
		'prompt_user'   => 'strlen',
	);
}
