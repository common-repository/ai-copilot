<?php
namespace QuadLayers\AICP\Entities\Virtual;

use QuadLayers\WP_Orm\Entity\SingleEntity;

class Chat extends SingleEntity {
	public $model                     = '';
	public $max_tokens                = 0;
	public $temperature               = 0;
	public $messages                  = '';
	public $stop                      = '';
	public $top_p                     = 0;
	public static $sanitizeProperties = array(
		'model'       => 'wp_kses_post',
		'messages'    => '$this->sanitize_messages',
		'stop'        => 'json_decode',
		'max_tokens'  => 'intval',
		'temperature' => 'floatval',
		'top_p'       => 'floatval',
	);
	public static $validateProperties = array();

	public function sanitize_messages( $messages ) {
		$decoded_message = json_decode( $messages );

		foreach ( $decoded_message as $key => $message ) {
			$decoded_message[ $key ]->content = addslashes( urldecode( $message->content ) );
		}

		return $decoded_message;
	}

	public function sanitize_promtp( $value ) {
		return addslashes( urldecode( $value ) );
	}
}
