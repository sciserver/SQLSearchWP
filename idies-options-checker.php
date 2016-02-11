<?php
/*
Plugin Name: IDIES Options Checker Plugin
Plugin URI: http://zendgame.ocm
Description: Check the WordPress database options for incorrectly serialized options and fix them.
Version: 1.0
Author: Bonnie Souter
Author URI: http://zendgame.com
License: GPLv2
*/
/*  Copyright 2015 Bonnie Souter  (email : bonnie@zendgame.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

/**
 * Singleton class for setting up the plugin.
 *
 */
final class IDIES_Options_Checker_Plugin {

	public $dir_path = '';
	public $dir_uri = '';
	public $admin_dir = '';
	public $lib_dir = '';
	public $templates_dir = '';
	public $css_uri = '';
	public $js_uri = '';

	/**
	 * Returns the instance.
	 */
	public static function get_instance() {

		static $instance = null;

		if ( is_null( $instance ) ) {
			$instance = new IDIES_Options_Checker_Plugin;
			$instance->setup();
			$instance->includes();
			$instance->setup_actions();
		}

		return $instance;
	}
	
	/**
	 * Constructor method.
	 */
	private function __construct() {}

	/**
	 * Magic method to output a string if trying to use the object as a string.
	 */
	public function __toString() {
		return 'idies_options_checker';
	}

	/**
	 * Magic method to keep the object from being cloned.
	 */
	public function __clone() {
		_doing_it_wrong( __FUNCTION__, esc_html__( 'Sorry, no can do.', 'idies_options_checker' ), '1.0' );
	}

	/**
	 * Magic method to keep the object from being unserialized.
	 */
	public function __wakeup() {
		_doing_it_wrong( __FUNCTION__, esc_html__( 'Sorry, no can do.', 'idies_options_checker' ), '1.0' );
	}

	/**
	 * Magic method to prevent a fatal error when calling a method that doesn't exist.
	 */
	public function __call( $method = '', $args = array() ) {
		_doing_it_wrong( "IDIES_Options_Checker_Plugin::{$method}", esc_html__( 'Method does not exist.', 'idies_options_checker' ), '1.0' );
		unset( $method, $args );
		return null;
	}

	/**
	 * Sets up globals.
	 */
	private function setup() {

		// Main plugin directory path and URI.
		$this->dir_path = trailingslashit( plugin_dir_path( __FILE__ ) );
		$this->dir_uri  = trailingslashit( plugin_dir_url(  __FILE__ ) );

		// Plugin directory paths.
		$this->lib_dir       = trailingslashit( $this->dir_path . 'lib'       );
		$this->admin_dir     = trailingslashit( $this->dir_path . 'admin'     );
		$this->templates_dir = trailingslashit( $this->dir_path . 'templates' );

		// Plugin directory URIs.
		$this->css_uri = trailingslashit( $this->dir_uri . 'css' );
		$this->js_uri  = trailingslashit( $this->dir_uri . 'js'  );
		
		//Create an options page in the Tools section.
		add_action( 'admin_menu', 'ioc_create_menu' );

		function ioc_create_menu() {

			//create a submenu under Settings
			add_management_page( 'Check Serialized Options Page', 'Check Serialized Options',
				'manage_options', __FILE__, 'ioc_tools_page' );

		}
		
		function ioc_tools_page() {
			
			global $wpdb;
			//$all_options = $wpdb->get_results( 'SELECT option_id, option_name, option_value FROM wp_options WHERE option_id=83', ARRAY_A );
			$all_options = $wpdb->get_results( 'SELECT option_id, option_name, option_value FROM wp_options', ARRAY_A );
			
			echo "<div class='wrap'>";
			echo "<h2>Checking Integrity of Serialized Arrays</h2>";
			echo "<pre>";
			foreach ($all_options as $this_option) {
				if ( is_serialized( $this_option['option_value'] ) ) {
					if ( !wd_check_serialization( $this_option['option_value'] , $this_err ) ) {
						echo "Possible problem with " . $this_option['option_id'] , ": $this_err\n";
					} else {
						echo $this_option['option_id'] , ": OK\n";
					}
				}
			}
			echo "</pre>";
			echo "</div>";
		}
		
		function wd_check_serialization( $string, &$errmsg ) 
		{

			$str = 's';
			$array = 'a';
			//$binary = 'b';
			$integer = 'i';
			$any = '[^}]*?';
			$count = '\d+';
			$content = '"(?:\\\";|.)*?";';
			$open_tag = '\{';
			$close_tag = '\}';
			//$parameter = "($str|$array|$integer|$binary|$any):($count)" . "(?:[:]($open_tag|$content)|[;])";            
			$parameter = "($str|$array|$integer|$any):($count)" . "(?:[:]($open_tag|$content)|[;])";            
			$preg = "/$parameter|($close_tag)/";
			if( !preg_match_all( $preg, $string, $matches ) ) 
			{            
				$errmsg = 'not a serialized string';
				return false;
			}    
			$open_arrays = 0;
			foreach( $matches[1] AS $key => $value )
			{
				//if( !empty( $value ) && ( $value != $array xor $value != $str xor $value != $integer xor $value != $binary ) ) 
				if( !empty( $value ) && ( $value != $array xor $value != $str xor $value != $integer  ) ) 
				{
					$errmsg = 'undefined datatype: *' . $value . '*';
					return false;
				}
				if( $value == $array )
				{
					$open_arrays++;                                
					if( $matches[3][$key] != '{' ) 
					{
						$errmsg = 'open tag expected';
						return false;
					}
				}
				if( $value == '' )
				{
					if( $matches[4][$key] != '}' ) 
					{
						$errmsg = 'close tag expected';
						return false;
					}
					$open_arrays--;
				}
				if( $value == $str )
				{
					$aVar = ltrim( $matches[3][$key], '"' );
					$aVar = rtrim( $aVar, '";' );
					if( strlen( $aVar ) != $matches[2][$key] ) 
					{
						$errmsg = 'stringlen for string not match';
						return false;
					}
				}
				//if( ( $value == $integer ) || ( $value == $binary ) )
				if( $value == $integer )
				{
					if( !empty( $matches[3][$key] ) ) 
					{
						$errmsg = 'unexpected data';
						return false;
					}
					if( !is_integer( (int)$matches[2][$key] ) ) 
					{
						$errmsg = 'integer expected';
						return false;
					}
				}
			}        
			if( $open_arrays != 0 ) 
			{
				$errmsg = 'wrong setted arrays';
				return false;
			}
			return true;
		}

	}

	/**
	 * Loads files needed by the plugin.
	 */
	private function includes() {


		// Load admin files.
		if ( is_admin() ) {

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
 * Gets the instance of the `IDIES_Options_Checker_Plugin` class.  This function is useful for quickly grabbing data
 * used throughout the plugin.
 */
function idies_options_checker_plugin() {
	return IDIES_Options_Checker_Plugin::get_instance();
}

// Let's roll!
idies_options_checker_plugin();