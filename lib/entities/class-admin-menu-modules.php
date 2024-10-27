<?php
namespace QuadLayers\AICP\Entities;

use QuadLayers\WP_Orm\Entity\SingleEntity;

class Admin_Menu_Modules extends SingleEntity {
	public $playground_enable         = 1;
	public $actions_enable            = 1;
	public $actions_post_types        = array( 'post', 'page', 'product' );
	public $content_enable            = 1;
	public $content_post_types        = array( 'post', 'page', 'product' );
	public $chatbots_enable           = 1;
	public $stats_enable              = 1;
	public static $sanitizeProperties = array();
	public static $validateProperties = array();
}
