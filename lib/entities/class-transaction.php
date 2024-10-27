<?php
// phpcs:disable Generic.Commenting.DocComment.MissingShort
// phpcs:disable Generic.VariableAnalysis.UnusedVariable
// phpcs:disable Squiz.Commenting.VariableComment.MissingVar
namespace QuadLayers\AICP\Entities;

use WC_Order;

use Symlink\ORM\Models\BaseModel as Model;
/**
 * @ORM_Type Entity
 * @ORM_Table "aicp_transactions"
 * @ORM_AllowSchemaUpdate True
 */
class Transaction extends Model {

	/**
	 * @ORM_Column_Type datetime
	 * @ORM_Column_Null NOT NULL
	 */
	public $date = '';

	/**
	 * @ORM_Column_Type varchar
	 * @ORM_Column_Length 255
	 * @ORM_Column_Null NOT NULL
	 */
	public $consumer_module = '';

	/**
	 * @ORM_Column_Type varchar
	 * @ORM_Column_Length 255
	 * @ORM_Column_Null NOT NULL
	 */
	public $api_type = '';

	/**
	 * @ORM_Column_Type varchar
	 * @ORM_Column_Length 255
	 * @ORM_Column_Null NOT NULL
	 */
	public $api_service = '';

	/**
	 * @ORM_Column_Type varchar
	 * @ORM_Column_Length 255
	 * @ORM_Column_Null NOT NULL
	 */
	public $api_service_model = '';

	/**
	 * @ORM_Column_Type int
	 * @ORM_Column_Null NOT NULL
	 */
	public $consumer_user = 0;

	/**
	 * @ORM_Column_Type varchar
	 * @ORM_Column_Length 255
	 * @ORM_Column_Null NOT NULL
	 */
	public $consumer_user_role = '';

	/**
	 * @ORM_Column_Type float
	 * @ORM_Column_Null NOT NULL
	 */
	public $tokens_qty_input = 0;

	/**
	 * @ORM_Column_Type float
	 * @ORM_Column_Null NOT NULL
	 */
	public $tokens_qty_output = 0;

	/**
	 * @ORM_Column_Type float
	 * @ORM_Column_Null NOT NULL
	 */
	public $tokens_qty_total = 0;

	/**
	 * @ORM_Column_Type float
	 * @ORM_Column_Null NOT NULL
	 */
	public $transaction_cost_input = 0;

	/**
	 * @ORM_Column_Type float
	 * @ORM_Column_Null NOT NULL
	 */
	public $transaction_cost_output = 0;

	/**
	 * @ORM_Column_Type float
	 * @ORM_Column_Null NOT NULL
	 */
	public $transaction_cost_total = 0;
}
