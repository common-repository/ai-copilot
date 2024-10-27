<?php
namespace QuadLayers\AICP\Entities\Virtual;

use QuadLayers\WP_Orm\Entity\SingleEntity;

class Image_Openai extends SingleEntity {
	public $model                     = '';
	public $prompt                    = '';
	public $n                         = 0;
	public $response_format           = '';
	public $size                      = '';
	public $style                     = '';
	public $quality                   = '';
	public static $sanitizeProperties = array(
		'model'           => 'wp_kses_post',
		'prompt'          => '$this->sanitize_promtp',
		'n'               => 'absint',
		'response_format' => 'json_decode',
		'size'            => 'wp_kses_post',
		'style'           => 'sanitize_key',
		'quality'         => 'sanitize_key',
	);
	public static $validateProperties = array(
		'prompt' => 'strlen',
	);

	public function sanitize_promtp( $value ) {
		return addslashes( urldecode( $value ) );
	}
}
