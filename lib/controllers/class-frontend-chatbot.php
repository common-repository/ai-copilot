<?php

namespace QuadLayers\AICP\Controllers;

use QuadLayers\AICP\Models\Admin_Menu_Modules as Models_Admin_Menu_Modules;
use QuadLayers\AICP\Models\Admin_Menu_Chatbot as Models_Chatbot;
use QuadLayers\AICP\Models\Assistants as Models_Assistants;

class Frontend_Chatbot {

	protected static $instance;
	protected static $menu_slug = 'ai-copilot';

	private function __construct() {
		add_action( 'wp', array( $this, 'display' ) );
	}

	public function display() {

		$is_elementor_library = isset( $_GET['post_type'] ) && 'elementor_library' === $_GET['post_type'] && isset( $_GET['render_mode'] ) && 'template-preview' === $_GET['render_mode'];

		if ( $is_elementor_library ) {
			return;
		}

		if ( is_admin() ) {
			return;
		}

		$enabled = Models_Admin_Menu_Modules::instance()->get()->get( 'chatbots_enable' );

		if ( ! $enabled ) {
			return;
		}

		add_action( 'wp_enqueue_scripts', array( $this, 'add_assets' ), 5 );
		add_action( 'wp_footer', array( $this, 'add_app' ) );
	}

	public function add_assets() {

		$frontend_chatbot = include QUADLAYERS_AICP_PLUGIN_DIR . 'build/frontend-chatbot/js/index.asset.php';

		$assistants = array_reduce(
			array_slice( Models_Assistants::instance()->get_all(), 0, 1 ),
			function ( $result, $assistant ) {
				$assistant_id = $assistant->get( 'assistant_id' );
				$properties   = $assistant->getProperties();

				$result[ $assistant_id ] = $properties;

				return $result;
			},
			array()
		);

		$chatbot = Models_Chatbot::instance()->get();

		wp_enqueue_script(
			'aicp-frontend-chatbot',
			plugins_url( '/build/frontend-chatbot/js/index.js', QUADLAYERS_AICP_PLUGIN_FILE ),
			$frontend_chatbot['dependencies'],
			$frontend_chatbot['version']
		);

		wp_localize_script(
			'aicp-frontend-chatbot',
			'aicpFrontendChatbot',
			array(
				'assistants' => $assistants,
				'chatbot'    => $chatbot,
			)
		);

		wp_enqueue_style(
			'aicp-frontend-chatbot',
			plugins_url( '/build/frontend-chatbot/css/style.css', QUADLAYERS_AICP_PLUGIN_FILE ),
			array(),
			QUADLAYERS_AICP_PLUGIN_VERSION
		);
	}

	public function add_app() {
		echo '<div id="aicp__app"></div>';
	}

	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}
}
