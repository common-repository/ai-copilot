<?php
namespace QuadLayers\AICP\Entities\Virtual;

use QuadLayers\WP_Orm\Entity\SingleEntity;

class Assistant_Thread extends SingleEntity {
	public $assistant_id              = '';
	public $openai_id                 = '';
	public static $sanitizeProperties = array(
		'assistant_id' => 'sanitize_text_field',
		'openai_id'    => 'sanitize_text_field',
	);
	public static $validateProperties = array(
		'assistant_id' => 'strlen',
		'openai_id'    => 'strlen',
	);
}
