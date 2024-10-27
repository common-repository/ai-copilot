<?php
namespace QuadLayers\AICP\Entities;

use QuadLayers\WP_Orm\Entity\CollectionEntity;

class Content_Template extends CollectionEntity {
	public static $primaryKey         = 'template_id'; // phpcs:ignore.WordPress.NamingConventions.ValidVariableName.PropertyNotSnakeCase
	public $template_id               = 0;
	public $template_label            = '';
	public $template_description      = '';
	public $template_type             = '';
	public $template_post_type        = array( 'all' );
	public $prompt_system             = 'You are an api that generates posts for a WordPress blog. Follow the user instructions and return text or html without additional explanations or content.';
	public $prompt_title              = '';
	public $prompt_sections           = '';
	public $prompt_content            = '';
	public $prompt_excerpt            = '';
	public $prompt_tags               = '';
	public $variables_section_count   = 0;
	public $variables_paragraph_count = 0;
	public $variables_word_count      = 1000;
	public $variables_language        = 'current_site_language';
	public $variables_style           = '';
	public $variables_tone            = '';
	public $image_n                   = 2;
	public $image_size                = '1024x1024';
	public $image_position            = 'center';
	public $image_source              = 'dall-e';
	public $image_orientation         = 'landscape';
	public $image_color               = '';
	public $image_locale              = '';
	public $image_quality             = 'medium';
	public $image_style               = 'vivid';
	public $model                     = 'gpt-4o';
	public $model_temperature         = 0.8;
	public $model_max_tokens          = 0;
	public static $sanitizeProperties = array(
		'template_label'       => 'sanitize_text_field',
		'template_description' => 'wp_kses_post',
		'template_type'        => 'sanitize_key',
		'prompt_system'        => 'wp_kses_post',
		'prompt_title'         => 'wp_kses_post',
		'prompt_sections'      => 'wp_kses_post',
		'prompt_content'       => 'wp_kses_post',
		'prompt_excerpt'       => 'wp_kses_post',
		'prompt_tags'          => 'wp_kses_post',
		'variables_language'   => 'sanitize_key',
		'variables_style'      => 'sanitize_key',
		'variables_tone'       => 'sanitize_key',
		'image_size'           => 'sanitize_key',
		'image_position'       => 'sanitize_key',
		'image_source'         => 'sanitize_key',
		'image_orientation'    => 'sanitize_key',
		'image_color'          => 'sanitize_key',
		'image_locale'         => 'wp_kses_post',
		'image_quality'        => 'sanitize_key',
		'image_style'          => 'sanitize_key',
		'model'                => 'wp_kses_post',
	);
	public static $validateProperties = array(
		'template_label'  => 'strlen',
		// 'template_description' => 'strlen',
		'prompt_system'   => 'strlen',
		'prompt_title'    => 'strlen',
		'prompt_sections' => 'strlen',
		'prompt_content'  => 'strlen',
		'prompt_excerpt'  => 'strlen',
		'prompt_tags'     => 'strlen',
	);
}
