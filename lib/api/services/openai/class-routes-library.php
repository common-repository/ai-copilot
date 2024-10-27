<?php

namespace QuadLayers\AICP\Api\Services\OpenAI;

// Assistants services.
use QuadLayers\AICP\Api\Services\OpenAI\Assistants\Assistants\Get as Assistants_Get;
use QuadLayers\AICP\Api\Services\OpenAI\Assistants\Assistants\Edit as Assistants_Edit;
use QuadLayers\AICP\Api\Services\OpenAI\Assistants\Assistants\Post as Assistants_Post;
use QuadLayers\AICP\Api\Services\OpenAI\Assistants\Assistants\Delete as Assistants_Delete;

// Assistants Vector Stores services.
use QuadLayers\AICP\Api\Services\OpenAI\Assistants\Vector_Stores\Get as Assistants_Vector_Stores_Get;
use QuadLayers\AICP\Api\Services\OpenAI\Assistants\Vector_Stores\Post as Assistants_Vector_Stores_Post;
use QuadLayers\AICP\Api\Services\OpenAI\Assistants\Vector_Stores\Delete as Assistants_Vector_Stores_Delete;
use QuadLayers\AICP\Api\Services\OpenAI\Assistants\Vector_Stores\Edit as Assistants_Vector_Stores_Edit;

// Assistants Files services.
use QuadLayers\AICP\Api\Services\OpenAI\Assistants\Files\Get as Assistants_Files_Get;
use QuadLayers\AICP\Api\Services\OpenAI\Assistants\Files\Post as Assistants_Files_Post;
use QuadLayers\AICP\Api\Services\OpenAI\Assistants\Files\Delete as Assistants_Files_Delete;
use QuadLayers\AICP\Api\Services\OpenAI\Assistants\Files\Edit as Assistants_Files_Edit;
use QuadLayers\AICP\Api\Services\OpenAI\Assistants\Files\Example_File as Assistants_Files_Example_File;

// Assistants Messages services.
use QuadLayers\AICP\Api\Services\OpenAI\Assistants\Messages\Get as Assistants_Messages_Get;
use QuadLayers\AICP\Api\Services\OpenAI\Assistants\Messages\Post as Assistants_Messages_Post;

// Assistants Threads services.
use QuadLayers\AICP\Api\Services\OpenAI\Assistants\Threads\Get as Assistants_Threads_Get;
use QuadLayers\AICP\Api\Services\OpenAI\Assistants\Threads\Post as Assistants_Threads_Post;

// Chat services.
use QuadLayers\AICP\Api\Services\OpenAI\Chat\Post as Chat;

// Completion services.
use QuadLayers\AICP\Api\Services\OpenAI\Completions\Post as Completions;

// Images services.
use QuadLayers\AICP\Api\Services\OpenAI\Images\Post as Images;

use QuadLayers\AICP\Api\Route as Route_Interface;

class Routes_Library {
	protected $routes                = array();
	protected static $rest_namespace = 'quadlayers/ai-copilot/openai';
	protected static $instance;

	private function __construct() {
		add_action( 'init', array( $this, '_rest_init' ) );
	}

	public static function get_namespace() {
		return self::$rest_namespace;
	}

	public function get_routes( $route_path = null ) {
		if ( ! $route_path ) {
			return $this->routes;
		}

		if ( isset( $this->routes[ $route_path ] ) ) {
			return $this->routes[ $route_path ];
		}
	}

	public function register( Route_Interface $instance ) {
		$this->routes[ $instance::get_name() ] = $instance;
	}

	public function _rest_init() {
		// Assistants services.
		new Assistants_Get();
		new Assistants_Edit();
		new Assistants_Post();
		new Assistants_Delete();

		// Assistants Vector Stores services.
		new Assistants_Vector_Stores_Post();
		new Assistants_Vector_Stores_Get();
		new Assistants_Vector_Stores_Edit();
		new Assistants_Vector_Stores_Delete();

		// Assistants Files services.
		new Assistants_Files_Post();
		new Assistants_Files_Get();
		new Assistants_Files_Delete();
		new Assistants_Files_Edit();
		new Assistants_Files_Example_File();

		// Assistants Messages services.
		new Assistants_Messages_Post();
		new Assistants_Messages_Get();

		// Assistants Threads services.
		new Assistants_Threads_Post();
		new Assistants_Threads_Get();

		// Chat services.
		new Chat();

		// Completions services.
		new Completions();

		// Images services.
		new Images();
	}

	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}
}
