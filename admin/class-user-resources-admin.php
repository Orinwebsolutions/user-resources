<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://github.com/Orinwebsolutions
 * @since      1.0.0
 *
 * @package    User_Resources
 * @subpackage User_Resources/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    User_Resources
 * @subpackage User_Resources/admin
 * @author     Amila <amilapriyankara16@gmail.com>
 */
class User_Resources_Admin {

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
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/user-resources-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
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

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/user-resources-admin.js', array( 'jquery' ), $this->version, false );
		wp_localize_script( $this->plugin_name, 'localizeAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' )));

	}

	public function resources_cpt() {
		$labels = array(
		  'name'               => _x( 'Resources', 'post type general name' ),
		  'singular_name'      => _x( 'Resource', 'post type singular name' ),
		  'add_new'            => _x( 'Add New', 'add new' ),
		  'add_new_item'       => __( 'Add New Resource' ),
		  'edit_item'          => __( 'Edit Resource' ),
		  'new_item'           => __( 'New Resource' ),
		  'all_items'          => __( 'All Resources' ),
		  'view_item'          => __( 'View Resource' ),
		  'search_items'       => __( 'Search Resources' ),
		  'not_found'          => __( 'No Resources found' ),
		  'not_found_in_trash' => __( 'No Resources found in the Trash' ),
		  'menu_name'          => 'Resources'
		);
		$args = array(
		  'labels'        => $labels,
		  'description'   => 'Resources details',
		  'public'        => true,
		  'menu_position' => 77,
		  'menu_icon'     => 'dashicons-open-folder',
		  'supports'      => array( 
			  'title', 
			  'editor', 
			//   'author', 
			//   'thumbnail', 
			//   'excerpt', 
			//   'custom-fields', 
			//   'page-attributes'
			 ),
		  'has_archive'   => true,
		);
		register_post_type( 'resources', $args ); 
	}

	public function resource_attachment(){

		add_meta_box(
			"resource_attachment", 
			"Resource Documents", 
			array($this, "rendor_upload_area"), 
			"resources", 
			"normal", 
			"low");
	}

	public function update_form_settings()
	{
		global $post;
		if(get_post_type( $post->ID ) == 'resources') {
			echo ' enctype="multipart/form-data"';
		}
	}

	public function rendor_upload_area($post)
	{
		wp_nonce_field('resource_cpt_attachment_nonce', 'resource_cpt_nonce');
		$attachment_file = get_post_meta( $post->ID, 'resource_cpt_file_attachment', true );
		$fileTypes = array(
			'application/pdf'=> 'pdf.png', 
			'application/vnd.ms-excel' => 'xls.jpg', 
			'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' => 'xls.jpg',
			'application/vnd.ms-powerpoint' => 'ppt',
			'application/vnd.oasis.opendocument.text' => 'doc.png',
			'application/vnd.openxmlformats-officedocument.presentationml.presentation' => 'ppt.png',
			'application/msword' => 'doc.png', 
			'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'doc.png',
			'image/png' => 'image-2.jpg', 
			'image/gif' => 'image-2.jpg', 
			'image/jpeg' => 'image-2.jpg');
		$html = '<p class="description">Upload you attachment</p><br/>';
		if(!empty($attachment_file)){
			$html .= '<a class="resource uploaded-attachment" href="#" data-url="'.$attachment_file[0].'" data-post-id="'.$post->ID.'"><img src="'.PLUGIN_FILE_URL.'admin/img/'.$fileTypes[$attachment_file[1]].'" alt="" width="50" height="50" /></a>
			'.$attachment_file[0].'<br/>';
		}
		$html .= '<input type="file" id="resource_cpt_attachment" name="resource_cpt_file_attachment" value="" /><br/>';
		
		echo $html;
	}


	public function resource_attachment_save($postid)
	{
		$dir = PLUGIN_DIR;
		/* --- security verification --- */
		if(isset($_POST['resource_cpt_nonce']) && !wp_verify_nonce($_POST['resource_cpt_nonce'], 'resource_cpt_attachment_nonce')) {
			return $postid;
		} // end if
			
		if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
			return $postid;
		} // end if
			
		if(isset($_POST['post_type']) && 'resources' == $_POST['post_type']) {
			if(!current_user_can('edit_page', $postid)) {
				return $postid;
			} // end if
		}
		/* - end security verification - */

		// Make sure the file array isn't empty
		if(!empty($_FILES['resource_cpt_file_attachment']['name'])) {

			// Setup the array of supported file types. In this case, it's just PDF.
			$supported_types = array(
				'application/pdf', 
				'application/vnd.ms-excel', 
				'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
				'application/vnd.ms-powerpoint',
				'application/vnd.oasis.opendocument.text',
				'application/vnd.openxmlformats-officedocument.presentationml.presentation',
				'application/msword', 
				'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
				'image/png', 
				'image/gif', 
				'image/jpeg');
			
			// Get the file type of the upload
			$arr_file_type = wp_check_filetype( basename($_FILES['resource_cpt_file_attachment']['name']));
			$uploaded_type = $arr_file_type['type'];

			// Check if the type is supported. If not, throw an error.
			if(in_array($uploaded_type, $supported_types)) {
				$filename;
				$upload;
				$upload_dir = wp_upload_dir();
				if ( ! empty( $upload_dir['basedir'] ) ) {
					$user_dirname = $upload_dir['basedir'].'/user-resources';
					if ( ! file_exists( $user_dirname ) ) {
						wp_mkdir_p( $user_dirname );
					}
		
					$filename = wp_unique_filename( $user_dirname, $_FILES['resource_cpt_file_attachment']['name'] );
					$upload = move_uploaded_file($_FILES['resource_cpt_file_attachment']['tmp_name'], $user_dirname."/".$filename);
					// save into database $upload_dir['baseurl'].'/user-resources/'.$filename;
				}

				if($upload == false){
					//ToDo display proper error messge on wordpress
					wp_die('There was an error uploading your file');
				}else{
					update_post_meta($postid, 'resource_cpt_file_attachment', array($filename, $uploaded_type));     				
				}
	
			} else {
				//ToDo display proper error messge on wordpress
				wp_die("The file type that you've uploaded is not a supported type.");
			} // end if/else

		} // end if
	}

	function resources_cpt_taxonomies() {
		$labels = array(
			'name'              => _x( 'Resources country', 'taxonomy general name', 'textdomain' ),
			'singular_name'     => _x( 'Resource country', 'taxonomy singular name', 'textdomain' ),
			'search_items'      => __( 'Search Resources country', 'textdomain' ),
			'all_items'         => __( 'All Resources country', 'textdomain' ),
			'parent_item'       => __( 'Parent Resource country', 'textdomain' ),
			'parent_item_colon' => __( 'Parent Resource country:', 'textdomain' ),
			'edit_item'         => __( 'Edit Resource country', 'textdomain' ),
			'update_item'       => __( 'Update Resource country', 'textdomain' ),
			'add_new_item'      => __( 'Add New Resource country', 'textdomain' ),
			'new_item_name'     => __( 'New Resource country Name', 'textdomain' ),
			'menu_name'         => __( 'Resource country', 'textdomain' ),
		);
	 
		$args = array(
			'hierarchical'      => true,
			'labels'            => $labels,
			'show_ui'           => true,
			'show_admin_column' => true,
			'query_var'         => true,
			'rewrite'           => array( 'slug' => 'resource_country' ),
		);
	 
		register_taxonomy( 'resource_country', array( 'resources' ), $args );
	}

	public function resources_attachment_remove()
	{
		if( !wp_verify_nonce($_POST['securityNonce'], 'resource_cpt_attachment_nonce')) {
			return;
		} // end if

		if(!empty($_POST['attachName']) && !empty($_POST['pageID'])){
			$upload_dir = wp_upload_dir();
			$user_dirname = $upload_dir['basedir'].'/user-resources';
			wp_delete_file($user_dirname."/".$_POST['attachName']);
			$result = delete_post_meta($_POST['pageID'], 'resource_cpt_file_attachment');
			wp_send_json($result);
		}

	}
}
