<?php
namespace QuadLayers\AICP\Entities;

use QuadLayers\AICP\Services\Visibility\Options as Service_Visibility_Options;
use QuadLayers\WP_Orm\Entity\SingleEntity;

class Chatbot extends SingleEntity {
	public $chatbot_position   = 'bottom-right';
	public $chatbot_layout     = 'button';
	public $chatbot_style      = 'rounded';
	public $chatbot_auto_load  = true;
	public $chatbot_auto_delay = '1000';

	public $chatbot_button_text;
	public $chatbot_header_text;
	public $chatbot_footer_text;
	public $chatbot_message_placeholder;

	public $visibility;

	public $chatbot_typography = array(
		'font_family' => 'inherit',
		'font_size'   => 'inherit',
	);

	public $chatbot_font_size = '';

	public $chatbot_theme = array(
		'general_text'         => '#141414',
		'general_background'   => '#FFFFFF',
		'primary_text'         => '#FFFFFF',
		'primary_background'   => '#009CFF',
		'secondary_text'       => '#141414',
		'secondary_background' => '#E3E3E3',
		'icon'                 => '#FFFFFF',
	);

	public $chatbot_custom_css = '';

	public static $sanitizeProperties = array(
		'chatbot_button_text'         => 'sanitize_text_field',
		'chatbot_header_text'         => 'wp_kses_post',
		'chatbot_footer_text'         => 'wp_kses_post',
		'chatbot_custom_css'          => 'sanitize_text_field',
		'chatbot_message_placeholder' => 'sanitize_text_field',
	);

	public static $validateProperties = array(
		'chatbot_button_text' => 'strlen',
		// 'chatbot_header_text' => 'strlen',
		// 'chatbot_footer_text' => 'strlen',
	);

	public function __construct() {
		$this->chatbot_button_text         = esc_html__( 'Hi! How can I help you?', 'ai-copilot' );
		$this->chatbot_header_text         = '<p><span style="display:block;font-size: 10px;line-height: 10px;vertical-align: bottom;letter-spacing: -0.2px;">Powered by</span><a style="font-size: 24px;line-height: 34px;font-weight: bold;text-decoration: none;color: white" href="' . QUADLAYERS_AICP_DEMO_URL . '" target="_blank">' . QUADLAYERS_AICP_PLUGIN_NAME . '</a></p>';
		$this->chatbot_footer_text         = '<p style="text-align: start;">' . QUADLAYERS_AICP_PLUGIN_NAME . ' is free, download and try it now <a target="_blank" href="' . QUADLAYERS_AICP_DEMO_URL . '">here!</a></p>';
		$this->chatbot_message_placeholder = esc_html__( 'Enter your message...', 'ai-copilot' );
		$this->visibility                  = Service_Visibility_Options::instance()->get_args();
	}
}
