<?php
namespace QuadLayers\AICP\Entities\Virtual;

use QuadLayers\WP_Orm\Entity\SingleEntity;

class Assistant_Message extends SingleEntity {
	public $message_content           = '';
	public $thread_openai_id          = '';
	public $assistant_id              = 0;
	public $openai_id                 = '';
	public static $sanitizeProperties = array(
		'message_content'  => 'sanitize_text_field',
		'thread_openai_id' => 'sanitize_text_field',
		'openai_id'        => 'sanitize_text_field',
	);
	public static $validateProperties = array(
		'message_content'  => 'strlen',
		'thread_openai_id' => 'strlen',
		'openai_id'        => 'strlen',
	);
}
