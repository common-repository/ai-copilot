<?php
namespace QuadLayers\AICP\Entities\Virtual;

use QuadLayers\WP_Orm\Entity\SingleEntity;

class Completion extends SingleEntity {
	public $model                     = '';
	public $prompt                    = '';
	public $max_tokens                = 0;
	public $stop                      = '';
	public $temperature               = 0;
	public $best_of                   = 0;
	public $top_p                     = 0;
	public static $sanitizeProperties = array(
		'model'       => 'wp_kses_post',
		'prompt'      => '$this->sanitize_promtp',
		'max_tokens'  => 'absint',
		'stop'        => 'json_decode',
		'temperature' => 'floatval',
		'best_of'     => 'absint',
		'top_p'       => 'floatval',
	);
	public static $validateProperties = array(
		'prompt' => 'strlen',
	);

	public function sanitize_promtp( $value ) {
		return addslashes( urldecode( $value ) );
	}
}
