<?php
/*
Plugin Name: ProjectXXI
Plugin URI: http://wordpresswithzaheer.blogspot.com/p/plugin.html
Description: This plugin is used to create pages which will be accessible after solving words and number puzzle.
Version: 1.0
Author: Zaheer Abbas Aghani
Author URI: https://profiles.wordpress.org/zaheer01/
License: GPLv3 or later
Text Domain: project-xxi
Domain Path: /languages
*/

defined("ABSPATH") or die("No direct access!");
class Projectxxi {

function __construct() {
	add_action( 'init',  array($this,'prox_custom_post_type'), 0 );
	add_action( 'add_meta_boxes_puzzles', array($this,'prox_meta_box_for_puzzles') );
	add_action( 'save_post_puzzles', array($this,'prox_save_meta_boxes_data'), 10, 2 );

	add_action('init', array($this, 'prox_start_from_here'));
	add_action('admin_enqueue_scripts', array($this, 'prox_enqueue_script_admin'));

  add_action('wp_enqueue_scripts', array($this, 'prox_enqueue_script_front'));

	//register_activation_hook( __FILE__, array($this, 'create_directory_inside_uploads' ));	
}



function prox_start_from_here() {
	require_once plugin_dir_path(__FILE__) . 'backend/prox_settings_page.php';
  require_once plugin_dir_path(__FILE__) . 'frontend/prox_list_of_puzzles.php';
	require_once plugin_dir_path(__FILE__) . 'frontend/prox_search_values_in_puzzles.php';
}

// Enqueue Style and Scripts

function prox_enqueue_script_admin($hook) {
//Style & Script

//if($hook != "post.php")
//	return false;


wp_enqueue_script('prox-custom-admin', plugins_url('backend/js/prox_custom.js', __FILE__), array('jquery'), '',true);


}

function prox_enqueue_script_front(){
    wp_enqueue_style("prox-style", plugins_url('frontend/css/prox_style.css', __FILE__),'1.0.0','all');

    wp_enqueue_script('prox-script', plugins_url('frontend/js/prox_script.js', __FILE__), array('jquery'), '1.0',true);

  
    $fail_url = get_option("fail_redirect_link");
    $late_url = get_option("late_redirect_link");
    $slug = basename(parse_url($fail_url, PHP_URL_PATH));
    
    $page = get_page_by_path($slug);
    wp_localize_script('prox-script', 'ajax_object',
            array('ajax_url'=>admin_url('admin-ajax.php'), "fail_url"=>$fail_url,"late_url"=>$late_url,"page_id"=>$page->ID ));
}


/*
* Creating a function to create our CPT
*/
 
function prox_custom_post_type() {
 
// Set UI labels for Custom Post Type
    $labels = array(
        'name'                => _x( 'Puzzles', 'Post Type General Name', 'projectxxi' ),
        'singular_name'       => _x( 'Puzzle', 'Post Type Singular Name', 'projectxxi' ),
        'menu_name'           => __( 'Puzzles', 'projectxxi' ),
        'parent_item_colon'   => __( 'Parent Puzzle', 'projectxxi' ),
        'all_items'           => __( 'All Puzzles', 'projectxxi' ),
        'view_item'           => __( 'View Puzzle', 'projectxxi' ),
        'add_new_item'        => __( 'Add New Puzzle', 'projectxxi' ),
        'add_new'             => __( 'Add New', 'projectxxi' ),
        'edit_item'           => __( 'Edit Puzzle', 'projectxxi' ),
        'update_item'         => __( 'Update Puzzle', 'projectxxi' ),
        'search_items'        => __( 'Search Puzzle', 'projectxxi' ),
        'not_found'           => __( 'Not Found', 'projectxxi' ),
        'not_found_in_trash'  => __( 'Not found in Trash', 'projectxxi' ),
    );
     
// Set other options for Custom Post Type
     
    $args = array(
        'label'               => __( 'puzzles', 'projectxxi' ),
        'description'         => __( 'Puzzle news and reviews', 'projectxxi' ),
        'labels'              => $labels,
        // Features this CPT supports in Post Editor
        'supports'            => array( 'title', 'editor', 'excerpt', 'author', 'thumbnail', 'comments', 'revisions', 'custom-fields' ),
        // You can associate this CPT with a taxonomy or custom taxonomy. 
        'taxonomies'          => array( 'genres' ),
        /* A hierarchical CPT is like Pages and can have
        * Parent and child items. A non-hierarchical CPT
        * is like Posts.
        */ 
        'hierarchical'        => false,
        'public'              => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'show_in_nav_menus'   => true,
        'show_in_admin_bar'   => true,
        'menu_icon'   => 'dashicons-schedule',
        'menu_position'       => 5,
        'can_export'          => true,
        'has_archive'         => true,
        'exclude_from_search' => false,
        'publicly_queryable'  => true,
        'capability_type'     => 'post',
        'show_in_rest' => true,
 
    );
     
    // Registering your Custom Post Type
    register_post_type( 'puzzles', $args );
 
}
 
/* Hook into the 'init' action so that the function
* Containing our post type registration is not 
* unnecessarily executed. 
*/
 


function prox_meta_box_for_puzzles( $post ){
    add_meta_box( 'my_meta_box_custom_id', __( 'Additional info', 'textdomain' ), array($this, 'prox_meta_box_html_output'), 'puzzles', 'normal', 'low' );
}

function prox_meta_box_html_output( $post ) {
   /* wp_nonce_field( plugin_basename( __FILE__ ),'prox_answer1_nonce');
	wp_nonce_field( plugin_basename( __FILE__ ),'prox_answer2_nonce');
    wp_nonce_field( plugin_basename( __FILE__ ),'prox_answer3_nonce');*/

    $prox_answer1 = get_post_meta($post->ID,'prox_answer1',true );
    $prox_answer2 = get_post_meta($post->ID,'prox_answer2',true );
    $prox_answer3 = get_post_meta($post->ID,'prox_answer3',true);
    $prox_numers = get_post_meta($post->ID,'prox_numers',true);
    $prox_user_attmept = get_post_meta($post->ID,'prox_user_attmept',true);

    $prox_hidden_pages = get_post_meta( $post->ID, 'prox_hidden_pages', true );
    $pages = get_posts( array(
        'post_type'      => 'page',
        'post_status'    => 'publish',
        'posts_per_page' => -1
    ) );

    $prox_numer_puzzle = get_post_meta( $post->ID, 'prox_numer_puzzle' , true );

    echo "<h4>Words Puzzles Answers</h4>";
  	echo '<input type="text" id="prox_answer1" name="prox_answer1" placeholder="Enter answer 1" value="'.$prox_answer1.'" style="width: 100%;margin-bottom: 10px;padding: 2px 10px;border: 1px solid #ddd;"/>';

  	echo '<input type="text" id="prox_answer2" name="prox_answer2" placeholder="Enter answer 2" value="'.$prox_answer2.'" style="width: 100%;margin-bottom: 10px;padding: 2px 10px;border: 1px solid #ddd;"/></label>';

  	echo '<input type="text" id="prox_answer3" name="prox_answer3" placeholder="Enter answer 3" value="'.$prox_answer3.'" style="width: 100%;margin-bottom: 10px;padding: 2px 10px;border: 1px solid #ddd;"/></label>';

  	echo "<h4>Number Puzzles Description / Answers</h4>";

  	echo '<textarea cols="5" rows="5" name="prox_numer_puzzle" placeholder="Enter number puzzle description" style="width: 100%;margin-bottom: 10px;padding:10px;border: 1px solid #ddd;">'.$prox_numer_puzzle.'</textarea>';


  	echo '<br><input type="text" class="prox_numers" name="prox_numers" placeholder="Enter unique number" value="'.$prox_numers.'" style="width: 100%;margin-bottom: 10px;padding: 2px 10px;border: 1px solid #ddd;" />';

  	echo "<h4>User Attempts</h4>";

  	echo '<input type="number" id="prox_user_attmept" name="prox_user_attmept" placeholder="Enter user attmept number" value="'.$prox_user_attmept.'" style="width: 100%;margin-bottom: 10px;padding: 2px 10px;border: 1px solid #ddd;"/>';

  	?>

  	 <p>
        <select id="prox_hidden_pages" name="prox_hidden_pages" style="width: 100%;margin-bottom: 10px;padding: 2px 10px;border: 1px solid #ddd;">
            <option value="">Selecht a page...</option>
            <?php foreach ( $pages as $page ) : ?>
                <option value="<?php echo esc_attr( $page->post_title ); ?>" <?php selected( $prox_hidden_pages, esc_attr( $page->post_title ) ); ?>><?php echo esc_html( $page->post_title ); ?></option>
            <?php endforeach; ?>
        </select>
    </p>

  	<?php 

}


function prox_save_meta_boxes_data( $post_id ){
    // check for nonce to top xss

	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
  	return;

	  if ( !isset($_POST['prox_answer1']) && !isset($_POST['prox_answer2']) && !isset($_POST['prox_answer3']) && !isset($_POST['prox_numers']) && !isset($_POST['prox_user_attmept']))    
	  return;

	  if ( 'page' == $_POST['post_type'] ) {
	    if ( !current_user_can( 'edit_page', $post_id ) )
	    return;
	  } else {
	    if ( !current_user_can( 'edit_post', $post_id ) )
	    return;
	  }

   	$prox_answer1 = $_POST['prox_answer1'];
  	update_post_meta( $post_id, 'prox_answer1', $prox_answer1 );
  	$prox_answer2 = $_POST['prox_answer2'];
  	update_post_meta( $post_id, 'prox_answer2', $prox_answer2 );
  	$prox_answer3 = $_POST['prox_answer3'];
  	update_post_meta( $post_id, 'prox_answer3', $prox_answer3 );
  	$prox_numers = $_POST['prox_numers'];
  	update_post_meta( $post_id, 'prox_numers', $prox_numers );
  	$prox_user_attmept = $_POST['prox_user_attmept'];
  	update_post_meta( $post_id, 'prox_user_attmept', $prox_user_attmept );

  	$prox_hidden_pages = $_POST['prox_hidden_pages'];
  	update_post_meta( $post_id, 'prox_hidden_pages', $prox_hidden_pages );


    $prox_numer_puzzle=$_POST['prox_numer_puzzle'];
    update_post_meta($post_id, 'prox_numer_puzzle', $prox_numer_puzzle );


}



} // class ends

// CHECK WETHER CLASS EXISTS OR NOT.

if (class_exists('Projectxxi')) {
	$obj = new Projectxxi();
}