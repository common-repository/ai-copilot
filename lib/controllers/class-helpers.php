<?php

namespace QuadLayers\AICP\Controllers;

use QuadLayers\AICP\Services\Visibility\Options as Service_Visibility_Options;

class Helpers {

	protected static $instance;

	private function __construct() {
		add_action( 'admin_enqueue_scripts', array( $this, 'register_scripts' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'register_scripts' ) );
	}

	public function register_scripts() {
		$helpers = include QUADLAYERS_AICP_PLUGIN_DIR . 'build/helpers/js/index.asset.php';

		/**
		 * Register helpers assets
		 */
		wp_register_script(
			'aicp-helpers',
			plugins_url( '/build/helpers/js/index.js', QUADLAYERS_AICP_PLUGIN_FILE ),
			$helpers['dependencies'],
			$helpers['version'],
			true
		);

		require_once ABSPATH . 'wp-admin/includes/translation-install.php';
		$available_languages = wp_get_available_translations();
		$languages           = array();

		foreach ( $available_languages as $key => $value ) {
			$languages[ $key ] = array(
				'language'     => $value['language'],
				'english_name' => $value['english_name'],
			);
		}

		$languages['en_US'] = array(
			'language'     => 'en_US',
			'english_name' => 'English (USA)',
		);

		$post_types = \QuadLayers\AICP\Helpers::get_valid_post_types();

		$entity_options = Service_Visibility_Options::instance();

		global $wp_version;

		global $wp_version;

		wp_localize_script(
			'aicp-helpers',
			'aicpHelpers',
			array(
				'WP_LANGUAGES'                       => $languages,
				'WP_LANGUAGE'                        => get_locale(),
				'WP_STATUSES'                        => get_post_statuses(),
				'WP_VERSION'                         => $wp_version,
				'WP_ADMIN_URL'                       => admin_url(),
				'QUADLAYERS_AICP_PLUGIN_URL'         => plugins_url( '/', QUADLAYERS_AICP_PLUGIN_FILE ),
				'QUADLAYERS_AICP_PLUGIN_NAME'        => QUADLAYERS_AICP_PLUGIN_NAME,
				'QUADLAYERS_AICP_PLUGIN_VERSION'     => QUADLAYERS_AICP_PLUGIN_VERSION,
				'QUADLAYERS_AICP_WORDPRESS_URL'      => QUADLAYERS_AICP_WORDPRESS_URL,
				'QUADLAYERS_AICP_REVIEW_URL'         => QUADLAYERS_AICP_REVIEW_URL,
				'QUADLAYERS_AICP_DEMO_URL'           => QUADLAYERS_AICP_DEMO_URL,
				'QUADLAYERS_AICP_PREMIUM_SELL_URL'   => QUADLAYERS_AICP_PREMIUM_SELL_URL,
				'QUADLAYERS_AICP_SUPPORT_URL'        => QUADLAYERS_AICP_SUPPORT_URL,
				'QUADLAYERS_AICP_DOCUMENTATION_URL'  => QUADLAYERS_AICP_DOCUMENTATION_URL,
				'QUADLAYERS_AICP_GROUP_URL'          => QUADLAYERS_AICP_GROUP_URL,
				'QUADLAYERS_AICP_DEVELOPER'          => QUADLAYERS_AICP_DEVELOPER,
				'QUADLAYERS_AICP_VALID_POST_TYPES'   => $post_types,
				'QUADLAYERS_AICP_KNOWLEDGE_BASE_URL' => wp_nonce_url( admin_url( 'admin-ajax.php?action=ai_copilot_generate_knowledge_base' ), 'process' ),
				'QUADLAYERS_AICP_DISPLAY_POST_TYPES' => $entity_options->get_entries(),
				'QUADLAYERS_AICP_DISPLAY_TAXONOMIES' => $entity_options->get_taxonomies(),
			)
		);
	}

	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}
}
