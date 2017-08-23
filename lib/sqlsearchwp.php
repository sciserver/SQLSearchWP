<?php
/*
Plugin Name: SQLSearchWP Core Plugin
Plugin URI: http://www.voyages.sdss.org
Description: Query SDSS MSSQL DB
Version: 1.0.0
Author: Bonnie Souter
Author URI: https://github.com/bonbons0220
License: MIT
*/

/**
 * Singleton class for setting up the plugin.
 *
 */
final class SQLSearchWP {

	public $dir_path = '';
	public $dir_uri = '';
	//public $admin_dir = '';
	public $lib_dir = '';
	//public $templates_dir = '';
	public $css_uri = '';
	public $js_uri = '';
	public $bootstrap_uri = '';

	/**
	 * Returns the instance.
	 */
	public static function get_instance() {

		// THERE CAN ONLY BE ONE
		static $instance = null;
		if ( is_null( $instance ) ) {
			
			$instance = new SQLSearchWP;
			$instance->setup();
			$instance->includes();
			$instance->setup_actions();
		}
		return $instance;
	}
	
	/**
	 * Constructor method.
	 */
	private function __construct() {
		
		//Add Scripts
		add_action( 'wp_enqueue_scripts', array( $this , 'register_sqlswp_script' ) );
		
		//Add Shortcodes
		add_shortcode( 'sqlsearchwp' , array( $this , 'sqlsearchwp_shortcode' ) );
		
		//Add page(s) to the Admin Menu
		add_action( 'admin_menu' , array( $this , 'sqls_menu' ) );

	}
	
	 /**
	 * Add shortcodes menu
	**/
	function sqls_menu() {

		// Add a submenu item and page to Tools 
		add_management_page( 'SQLSearchWP Settings', 'SQLSearchWP Settings', 'export', 'sqlswp-tools-page' , array( 	$this , 'sqlswp_tools_page' ) );
		
	}

	/**
	 * Add shortcodes page
	**/
	function sqlswp_tools_page() {
		
		if ( !current_user_can( 'export' ) )  {
				wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
		}
		echo '<div class="sqls-tools-wrap">';
		echo '<h2>SQLSearchWP Settings</h2>';
		echo '</div>';	
	}

	//
	function register_sqlswp_script() {
		
		//Scripts to be Registered, but not enqueued. This example requires jquery 
		wp_register_script( 'sqlsearchwp-script', $this->js_uri . "sqlsearchwp.js" , array() , '1.0.0', true );
		//wp_register_script( 'bootstrap-min', $this->bootstrap_uri . "bootstrap.min.js" , array( 'jquery' ), false , true );
		//wp_register_script( 'bootstrap', $this->bootstrap_uri . "bootstrap.js" , array( 'jquery' ), false , true );
		
		//Styles to be Registered, but not enqueued
		wp_register_style( 'sqlsearchwp-style', $this->css_uri . "sqlsearchwp.css" );
		
	}

	public function sqlsearchwp_shortcode( $atts = array() ) {

		$result='';

		$whichs=array( 'test' , 'prod' );
		$wheres=array( 'skyserversw' , 'casjobs' , 'odbc' );
		
		$webroot = $this->dir_uri;
		$which = ( !empty( $atts) && array_key_exists( 'which' , $atts ) && 
			in_array( $atts['which'] , $whichs ) ) ? $atts['which'] : 'test' ; 
		$where = ( !empty( $atts) && array_key_exists( 'where' , $atts ) && 
			in_array( $atts['where'] , $wheres ) ) ? $atts['where'] : 'skyserverws' ; 
		$dataiframe = ( !empty( $atts) && array_key_exists( 'iframe' , $atts ) ) ? 'data-sqls-iframe="true"' : 'data-sqls-iframe="false"' ; 
		
		//Shortcode loads scripts and styles
		wp_enqueue_script( 'sqlsearchwp-script' );
		wp_enqueue_style( 'sqlsearchwp-style' );
		if ( defined( 'SQLS_DEVELOP' ) && SQLS_DEVELOP ) 
			wp_enqueue_script( 'bootstrap' );
		else
			wp_enqueue_script( 'bootstrap-min' );
		
		//{$foo->bar[1]}
		
		//Content 
		$result .= <<< EOT
<div id="sqls-container" class="sqls-wrap" data-sqls-webroot="$webroot" data-sqls-which="$which" data-sqls-where="$where" $dataiframe >
<div class="row">
<div class="col-lg-12">
<div class="sqls-messages-wrap">
<div class="sqls-messages"></div>
</div>
</div>
<div class="col-xs-12">
<div class="sqls-instructions-wrap well"> 
<h2><a role="button" data-toggle="collapse" href="#sqls-instructions" aria-expanded="true" aria-controls="sqls-instructions">Instructions</a></h2>
<div class="sqls-instructions collapse"></div> 
</div>
<div id="sqls-form-wrap" class="sqls-form-wrap well"> 
<h2><a role="button" data-toggle="collapse" href="#sqls-form" aria-expanded="true" aria-controls="sqls-form">SQL Search</a></h2>
<div class="form sqls-form collapse show">
<form id="sqls-form">
<input type="hidden" name="searchtool" value="SQL">
<input type="hidden" name="TaskName" value="Skyserver.Search.SQL">
<input type="hidden" name="syntax" value="Syntax">
<input type="hidden" name="ReturnHtml" value="true">
<input type="hidden" name="format" value="html">
<input type="hidden" name="TableName" value="">
<textarea id="sqls-query" name="cmd" class="sqls-query" rows=10 cols=60></textarea><br>
<button id="sqls-submit" name="sqls-submit" class="sqls-submit btn btn-success">Submit</button>
<button id="sqls-syntax" name="sqls-syntax" class="sqls-syntax btn btn-warning">Check Syntax</button>
<button id="sqls-reset" name="sqls-reset" class="sqls-reset btn btn-danger">Reset</button>
</form>
</div>
</div>
</div>
<div class="col-xs-12">
<div class="sqls-results-wrap well"> 
<h2><a role="button" data-toggle="collapse" href="#sqls-results" aria-expanded="false" aria-controls="sqls-results">Results</a></h2>
<div id="sqls-results" class="sqls-results collapse">
</div>
</div>
</div>
</div>
</div>
EOT;

		return $result;
	}

	/**
	 * Magic method to output a string if trying to use the object as a string.
	 */
	public function __toString() {
		return 'sqlsearchwp';
	}

	/**
	 * Magic method to keep the object from being cloned.
	 */
	public function __clone() {
		_doing_it_wrong( __FUNCTION__, esc_html__( 'Sorry, no can do.', 'sqlsearchwp' ), '1.0' );
	}

	/**
	 * Magic method to keep the object from being unserialized.
	 */
	public function __wakeup() {
		_doing_it_wrong( __FUNCTION__, esc_html__( 'Sorry, no can do.', 'sqlsearchwp' ), '1.0' );
	}

	/**
	 * Magic method to prevent a fatal error when calling a method that doesn't exist.
	 */
	public function __call( $method = '', $args = array() ) {
		_doing_it_wrong( "SQLSearchWP::{$method}", esc_html__( 'Method does not exist.', 'sqlsearchwp' ), '1.0' );
		unset( $method, $args );
		return null;
	}

	/**
	 * Sets up globals.
	 */
	private function setup() {

		// Main plugin directory path and URI.
		$this->dir_path = trailingslashit( SQLS_DIR_PATH );
		$this->dir_uri  = trailingslashit( SQLS_DIR_URL );

		// Plugin directory paths.
		$this->lib_dir       = trailingslashit( $this->dir_path . 'lib'       );
		//$this->admin_dir     = trailingslashit( $this->dir_path . 'admin'     );
		//$this->templates_dir = trailingslashit( $this->dir_path . 'templates' );

		// Plugin directory URIs.
		$this->css_uri = trailingslashit( $this->dir_uri . 'css' );
		$this->js_uri  = trailingslashit( $this->dir_uri . 'js'  );
		$this->bootstrap_uri  = trailingslashit( $this->dir_uri . 'vendor/bootstrap/dist/js'  );
	}

	/**
	 * Loads files needed by the plugin.
	 */
	private function includes() {

		// Load include files.
		//require_once( $this->lib_dir . 'functions.php'                     );
		//require_once( $this->lib_dir . 'functions-widgets.php'             );

		// Load template files.
		//require_once( $this->lib_dir . 'template.php' );

		// Load admin/backend files.
		if ( is_admin() ) {

			// General admin functions.
			//require_once( $this->admin_dir . 'functions-admin.php' );
		
		}
	}

	/**
	 * Sets up main plugin actions and filters.
	 */
	private function setup_actions() {

		// Register activation hook.
		register_activation_hook( __FILE__, array( $this, 'activation' ) );
	}

	/**
	 * Method that runs only when the plugin is activated.
	 */
	public function activation() {

	}
	
}

/**
 * Gets the instance of the `SQLSearchWP` class.  This function is useful for quickly grabbing data
 * used throughout the plugin.
 */
function sqlswp_plugin() {
	return SQLSearchWP::get_instance();
}
