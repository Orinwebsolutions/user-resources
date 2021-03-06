<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://github.com/Orinwebsolutions
 * @since      1.0.0
 *
 * @package    User_Resources
 * @subpackage User_Resources/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    User_Resources
 * @subpackage User_Resources/public
 * @author     Amila <amilapriyankara16@gmail.com>
 */
class User_Resources_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in User_Resources_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The User_Resources_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/user-resources-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in User_Resources_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The User_Resources_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/user-resources-public.js', array( 'jquery' ), $this->version, false );

	}

	public function wp_login_redirects( $url, $request, $user )
	{
		if ( $user && is_object( $user ) && is_a( $user, 'WP_User' ) ) 
		{
			if ( $user->has_cap( 'subscriber' ) )
			{
				$userResourcePage = get_option('user_resource_page_id');
				$url = get_permalink($userResourcePage);
			} 
			else
			{
				$url = admin_url();
			}
		}
		return $url;
	}

	public function wp_logout_redirects()
	{
		wp_safe_redirect(wp_login_url(get_permalink()));
		exit;
	}

}
