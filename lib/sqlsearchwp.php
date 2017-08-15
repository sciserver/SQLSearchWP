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
		wp_register_script( 'sqlsearchwp-script', $this->js_uri . "sqlsearchwp.js", array( 'jquery' ), '1.0.0', true );
		//wp_register_script( 'popper', 'https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.1/esm/popper.js', array( 'jquery' ), false , true );
		//wp_register_script( 'bootstrap', "https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.js", array( 'popper' , 'jquery' ), false , true );
		
		//Styles to be Registered, but not enqueued
		wp_register_style( 'sqlsearchwp-style', $this->css_uri . "sqlsearchwp.css" );
		wp_register_style( 'bootstrap-style', $this->css_uri . "bootstrap.min.css" );
		
	}

	public function sqlsearchwp_shortcode( $atts, $content = null, $tagname = null ) {

		$result='';

		//Shortcode loads scripts and styles
		wp_enqueue_script( 'sqlsearchwp-script' );
		//wp_enqueue_script( 'popper' );
		//wp_enqueue_script( 'bootstrap-script' );
		wp_enqueue_style( 'sqlsearchwp-style' );
		wp_enqueue_style( 'bootstrap-style' );
		
		//Content 
		$result .= <<< EOT
<div class="sqls-messages-wrap">
<h2>Messages</h2>
<div class="sqls-messages"></div>
</div>
<div class="sqls-instructions-wrap"> 
<h2>Instructions</h2>
<div class="sqls-instructions"></div> 
</div>
<div class="sqls-form-wrap"> 
<h2>SQL Search Form</h2>
<form class="sqls-form">
</form>
<div class="sqls-form"></div>
</div>
<div class="sqls-results-wrap"> 
<h2>SQL Search Results</h2>
<div class="sqls-results"></div>
</div>
EOT;
		$result = '<div id="sqls-container" class="sqls-wrap" ' . 
		          ' data-sqls-webroot="' . $this->dir_uri . '"' . 
		          ' data-sqls-which="test"' . 
				  '>' . $result . '</div>';
		
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
