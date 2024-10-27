<?php
namespace QuadLayers\AICP\Entities\Virtual;

use QuadLayers\WP_Orm\Entity\SingleEntity;

class Image_Pexels extends SingleEntity {
	public $query                     = '';
	public $orientation               = '';
	public $page                      = 0;
	public $per_page                  = 0;
	public $color                     = '';
	public $locale                    = '';
	public $size                      = '';
	public static $sanitizeProperties = array(
		'query'       => '$this->sanitize_promtp',
		'orientation' => 'sanitize_key',
		'page'        => 'absint',
		'per_page'    => 'absint',
		'color'       => 'sanitize_key',
		'locale'      => 'wp_kses_post',
		'size'        => 'sanitize_key',
	);
	public static $validateProperties = array(
		'prompt' => 'strlen',
	);

	public function sanitize_promtp( $value ) {
		return addslashes( urldecode( $value ) );
	}
}
