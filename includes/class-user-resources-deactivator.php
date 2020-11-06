<?php

/**
 * Fired during plugin deactivation
 *
 * @link       https://github.com/Orinwebsolutions
 * @since      1.0.0
 *
 * @package    User_Resources
 * @subpackage User_Resources/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    User_Resources
 * @subpackage User_Resources/includes
 * @author     Amila <amilapriyankara16@gmail.com>
 */
class User_Resources_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {
		if(get_option('users_can_register'))
		{
			update_option('users_can_register', false);
			if( !empty(get_option('user_resource_page_id')) ){
				$postId = get_option('user_resource_page_id');
				wp_delete_post($postId);
			}
			delete_option('user_resource_page_id');
		}
	}

}
