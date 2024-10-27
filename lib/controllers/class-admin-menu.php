<?php

namespace QuadLayers\AICP\Controllers;

class Admin_Menu {

	protected static $instance;
	protected static $menu_slug = 'ai-copilot';

	private function __construct() {
		/**
		 * Admin Menu
		 */
		add_action( 'admin_enqueue_scripts', array( $this, 'register_scripts' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_action( 'admin_menu', array( $this, 'add_menu' ) );
		add_action( 'admin_footer', array( __CLASS__, 'add_menu_css' ) );
	}

	public function register_scripts() {

		$menu = include QUADLAYERS_AICP_PLUGIN_DIR . 'build/admin-menu/js/index.asset.php';

		wp_register_script(
			'aicp-admin-menu',
			plugins_url( '/build/admin-menu/js/index.js', QUADLAYERS_AICP_PLUGIN_FILE ),
			$menu['dependencies'],
			$menu['version'],
			true
		);

		wp_register_style(
			'aicp-admin-menu',
			plugins_url( '/build/admin-menu/css/style.css', QUADLAYERS_AICP_PLUGIN_FILE ),
			array(
				'aicp-components',
				'media-views',
			),
			QUADLAYERS_AICP_PLUGIN_VERSION
		);
	}

	public function enqueue_scripts() {

		if ( ! isset( $_GET['page'] ) || self::get_menu_slug() !== $_GET['page'] ) {
			return;
		}

		wp_enqueue_media();
		wp_enqueue_editor();
		wp_enqueue_script( 'aicp-admin-menu' );
		wp_enqueue_style( 'aicp-admin-menu' );
	}

	public function add_menu() {

		$menu_slug = self::get_menu_slug();

		add_menu_page(
			QUADLAYERS_AICP_PLUGIN_NAME,
			QUADLAYERS_AICP_PLUGIN_NAME,
			'edit_posts',
			$menu_slug,
			'__return_null',
			plugins_url( '/assets/backend/img/logo.svg', QUADLAYERS_AICP_PLUGIN_FILE )
		);
		add_submenu_page(
			$menu_slug,
			esc_html__( 'Welcome', 'ai-copilot' ),
			esc_html__( 'Welcome', 'ai-copilot' ),
			'edit_posts',
			$menu_slug,
			'__return_null'
		);
		add_submenu_page(
			$menu_slug,
			esc_html__( 'Services', 'ai-copilot' ),
			esc_html__( 'Services', 'ai-copilot' ),
			'manage_options',
			"{$menu_slug}&tab=services",
			'__return_null'
		);
		add_submenu_page(
			$menu_slug,
			esc_html__( 'Modules', 'ai-copilot' ),
			esc_html__( 'Modules', 'ai-copilot' ),
			'manage_options',
			"{$menu_slug}&tab=modules",
			'__return_null'
		);
		add_submenu_page(
			$menu_slug,
			esc_html__( 'Content', 'ai-copilot' ),
			esc_html__( 'Content', 'ai-copilot' ),
			'manage_options',
			"{$menu_slug}&tab=content",
			'__return_null'
		);
		add_submenu_page(
			$menu_slug,
			esc_html__( 'Chatbot', 'ai-copilot' ),
			esc_html__( 'Chatbot', 'ai-copilot' ) . ' <span class="aicp__badge">Beta</span>',
			'manage_options',
			"{$menu_slug}&tab=chatbots",
			'__return_null'
		);
		add_submenu_page(
			$menu_slug,
			esc_html__( 'Actions', 'ai-copilot' ),
			esc_html__( 'Actions', 'ai-copilot' ),
			'manage_options',
			"{$menu_slug}&tab=actions",
			'__return_null'
		);
		add_submenu_page(
			$menu_slug,
			esc_html__( 'Stats', 'ai-copilot' ),
			esc_html__( 'Stats', 'ai-copilot' ),
			'manage_options',
			"{$menu_slug}&tab=stats",
			'__return_null'
		);
	}

	public static function get_menu_slug() {
		return self::$menu_slug;
	}

	public static function add_menu_css() {
		$menu_slug = self::get_menu_slug();
		?>
			<style>
				#toplevel_page_<?php echo esc_attr( $menu_slug ); ?> .wp-menu-image img {
					width: 18px;
					fill: white;
					margin: -1px 0 0 0;
				}
			</style>
		<?php
	}

	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}
}
