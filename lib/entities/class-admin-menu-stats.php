<?php

namespace QuadLayers\AICP\Entities;

use QuadLayers\WP_Orm\Entity\SingleEntity;

class Admin_Menu_Stats extends SingleEntity {

	public $consumer_module           = '';
	public $api_type                  = '';
	public $api_service               = '';
	public $api_service_model         = '';
	public $consumer_user             = 0;
	public $consumer_user_role        = '';
	public $start_date                = '';
	public $end_date                  = '';
	public $order_by                  = 'date';
	public $order                     = 'ASC';
	public $limit                     = 0;
	public $offset                    = 0;
	public $group_by                  = '';
	public $selected_charts           = array(
		'tokens_usage_input'  => true,
		'tokens_usage_output' => true,
		'tokens_usage_total'  => false,
		'tokens_cost_input'   => true,
		'tokens_cost_output'  => true,
		'tokens_cost_total'   => false,
	);
	public static $sanitizeProperties = array();
	public static $validateProperties = array();
}
