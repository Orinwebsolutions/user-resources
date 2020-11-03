<?php

/**
 * Fired during plugin activation
 *
 * @link       https://github.com/Orinwebsolutions
 * @since      1.0.0
 *
 * @package    User_Resources
 * @subpackage User_Resources/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    User_Resources
 * @subpackage User_Resources/includes
 * @author     Amila <amilapriyankara16@gmail.com>
 */
class User_Resources_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */

	public static function activate() {
		$plugin_admin = new User_Resources_Admin( 'user-resources', USER_RESOURCES_VERSION);
		$plugin_admin->resources_cpt();
		flush_rewrite_rules();
	}

}
