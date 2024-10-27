<?php

if ( class_exists( 'QuadLayers\\WP_Plugin_Table_Links\\Load' ) ) {
	new \QuadLayers\WP_Plugin_Table_Links\Load(
		QUADLAYERS_AICP_PLUGIN_FILE,
		array(
			array(
				'text'   => esc_html__( 'Settings', 'ai-copilot' ),
				'url'    => admin_url( 'admin.php?page=ai-copilot' ),
				'target' => '_self',
			),
			array(
				'place' => 'row_meta',
				'text'  => esc_html__( 'Support', 'ai-copilot' ),
				'url'   => QUADLAYERS_AICP_SUPPORT_URL,
			),
			array(
				'place' => 'row_meta',
				'text'  => esc_html__( 'Documentation', 'ai-copilot' ),
				'url'   => QUADLAYERS_AICP_DOCUMENTATION_URL,
			),
		)
	);
}
