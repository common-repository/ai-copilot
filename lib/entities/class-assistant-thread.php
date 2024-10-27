<?php
// phpcs:disable Generic.Commenting.DocComment.MissingShort
// phpcs:disable Generic.VariableAnalysis.UnusedVariable
// phpcs:disable Squiz.Commenting.VariableComment.MissingVar
namespace QuadLayers\AICP\Entities;

use WC_Order;

use Symlink\ORM\Models\BaseModel as Model;
/**
 * @ORM_Type Entity
 * @ORM_Table "aicp_assistant_threads"
 * @ORM_AllowSchemaUpdate True
 */
class Assistant_Thread extends Model {

	/**
	 * @ORM_Column_Type int
	 * @ORM_Column_Length 255
	 * @ORM_Column_Null NOT NULL
	 */
	public $assistant_id = '';

	/**
	 * @ORM_Column_Type varchar
	 * @ORM_Column_Length 255
	 * @ORM_Column_Null NOT NULL
	 */
	public $openai_id = '';

	/**
	 * @ORM_Column_Type float
	 * @ORM_Column_Null NOT NULL
	 */
	public $created_at = '';

	/**
	 * @ORM_Column_Type float
	 * @ORM_Column_Null NOT NULL
	 */
	public $expired_at = '';
}
