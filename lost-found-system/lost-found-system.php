<?php
/*
Plugin Name: Lost Found System
Plugin URI: http://wordpresswithzaheer.blogspot.com/p/plugin.html
Description: Display list of p.
Version: 1.0
Author: Zaheer Abbas Aghani
Author URI: https://profiles.wordpress.org/zaheer01/
License: GPLv3 or later
Text Domain: lost-found-system
Domain Path: /languages
*/

defined("ABSPATH") or die("No direct access!");
class LostFoundSystem {

function __construct() {
	add_action('init', array($this, 'lfs_start_from_here'));
	add_action('wp_enqueue_scripts', array($this, 'lfs_enqueue_script_front'));
	add_action( 'init',  array($this,'lfs_create_cpt'));
	add_action( 'add_meta_boxes',  array($this,'advertisements_extra_information' ));
	add_action( 'save_post', array($this,'lfs_extra_information_box_save' ));
  	add_filter( 'manage_advertisement_posts_columns', array($this,'set_custom_edit_advertisement_columns' ));	
  	add_action( 'manage_advertisement_posts_custom_column' , array($this,'custom_advertisement_column'), 10, 2 );
	add_action( 'after_setup_theme', array($this,'custom_postimage_setup' )); 
	add_action( 'add_meta_boxes', array($this,'custom_postimage_meta_box' ));
    add_action( 'save_post', array($this,'custom_postimage_meta_box_save' ));
    add_filter( "the_content", array($this,"lfs_show_advertisement_extra_information"));
    add_filter( 'pre_get_posts', array($this,'lfs_custom_search_query'));
}



function lfs_start_from_here() {
	require_once plugin_dir_path(__FILE__).'lfs_front/lfs_create_advertisement.php';
	require_once plugin_dir_path(__FILE__).'lfs_front/lfs_create_advertisement_process.php';
	require_once plugin_dir_path(__FILE__).'lfs_front/lfs_list_advertisement.php';
	require_once plugin_dir_path(__FILE__).'lfs_front/lfs_contact_entity_owner.php';


}

// Enqueue Style and Scripts

function lfs_enqueue_script_front() {
//Style & Script
wp_enqueue_style('bootstrap-style', "https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css",'4.5.2','all');
wp_enqueue_script('bootstrap-script', "https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js",array('jquery'),'4.5.2', true);

wp_enqueue_script('fd-validate', 'https://cdn.jsdelivr.net/npm/jquery-validation@1.19.0/dist/jquery.validate.min.js', array('jquery'), '',true);

//wp_enqueue_script('flexible-pagination', plugins_url('assets/js/flexible.pagination.js', __FILE__), array('jquery'), '',true);


//wp_enqueue_script('lfs-script', plugins_url('library/js/multi-post-thumbnails-admin.js', __FILE__),array('jquery'),'1.0.0', true);


wp_enqueue_style('lfs-style', plugins_url('assets/css/lfs.css', __FILE__),'1.0.0','all');
wp_enqueue_script('lfs-script', plugins_url('assets/js/lfs.js', __FILE__),array('jquery'),'1.0.0', true);
wp_localize_script('lfs-script', 'ajax_object', array('ajax_url' => admin_url('admin-ajax.php')));

}


function lfs_create_cpt() {
  $labels = array(
    'name'               => _x( 'Advertisements', 'advertisements' ),
    'singular_name'      => _x( 'advertisement', 'advertisements' ),
    'add_new'            => _x( 'Add New', 'advertisements' ),
    'add_new_item'       => __( 'Add New advertisement' ),
    'edit_item'          => __( 'Edit advertisement' ),
    'new_item'           => __( 'New advertisement' ),
    'all_items'          => __( 'All Advertisements' ),
    'view_item'          => __( 'View advertisement' ),
    'search_items'       => __( 'Search Advertisements' ),
    'not_found'          => __( 'No advertisements found' ),
    'not_found_in_trash' => __( 'No advertisements found in the Trash' ), 
    'menu_name'          => __('Advertisements')
  );
  $args = array(
    'labels'        => $labels,
    'description'   => 'Holds our advertisements and advertisements specific data',
    'public'        => true,
    'menu_position' => 5,
    'supports'      => array( 'title', 'editor', 'thumbnail', 'excerpt', 'comments' ),
    'has_archive'   => true,
    'menu_icon'   => 'dashicons-admin-site-alt',
  );
  register_post_type( 'advertisement', $args ); 
}


function advertisements_extra_information() {
    add_meta_box( 
        'advertisements_extra_information',
        __( 'Advertisement Extra Information', 'advertisements' ),
        array($this,'advertisements_extra_information_content'),
        'advertisement',
        'normal',
        'high'
    );
}


function advertisements_extra_information_content( $post ) {
wp_nonce_field( plugin_basename( __FILE__ ), 'lfs_user_email_content_nonce');
  wp_nonce_field( plugin_basename( __FILE__ ), 'lfs_phone_number_content_nonce');
  wp_nonce_field( plugin_basename( __FILE__ ), 'lfs_lost_or_found_date_nonce');
  wp_nonce_field( plugin_basename( __FILE__ ), 'lfs_lost_or_found_place');
  wp_nonce_field( plugin_basename( __FILE__ ), 'lfs_state');
  wp_nonce_field( plugin_basename( __FILE__ ), 'lfs_animal_type');
  wp_nonce_field( plugin_basename( __FILE__ ), 'lfs_animal_breed');
  wp_nonce_field( plugin_basename( __FILE__ ), 'lfs_special_mark');
  wp_nonce_field( plugin_basename( __FILE__ ), 'lfs_birth_Day');
  wp_nonce_field( plugin_basename( __FILE__ ), 'lfs_micro_chip');

  $lfs_user_email = get_post_meta( $post->ID, 'lfs_user_email', true );
  $lfs_phone_number = get_post_meta( $post->ID, 'lfs_phone_number', true );
  $lfs_lost_or_found_date = get_post_meta( $post->ID, 'lfs_lost_or_found_date', true );
  $lfs_lost_or_found_place = get_post_meta( $post->ID, 'lfs_lost_or_found_place', true );
  $lfs_state = get_post_meta( $post->ID, 'lfs_state', true );
  $lfs_animal_type = get_post_meta( $post->ID, 'lfs_animal_type', true );
  $lfs_animal_breed = get_post_meta( $post->ID, 'lfs_animal_breed', true );
  $lfs_special_mark = get_post_meta( $post->ID, 'lfs_special_mark', true );
  $lfs_birth_Day = get_post_meta( $post->ID, 'lfs_birth_Day', true );
  $lfs_micro_chip = get_post_meta( $post->ID, 'lfs_micro_chip', true );

  echo '<label for="lfs_user_email" style="display:block;padding: 4px;margin-bottom: 10px;"> Enter user email';
  echo '<input type="text" id="lfs_user_email" name="lfs_user_email" placeholder="Enter worth to win" value="'.$lfs_user_email.'" style="width:100%;"/></label>';

  echo '<label for="lfs_phone_number" style="display:block;padding: 4px;margin-bottom: 10px;"> Enter phone number';
  echo '<input type="text" id="lfs_phone_number" name="lfs_phone_number" placeholder="Enter advertisement lasting" value="'.$lfs_phone_number.'" style="width:100%;"/></label>';

  echo '<label for="lfs_lost_or_found_date" style="display:block;padding: 4px;margin-bottom: 10px;"> Enter Date';
  echo '<input type="text" id="lfs_lost_or_found_date" name="lfs_lost_or_found_date" placeholder="Enter Date" value="'.$lfs_lost_or_found_date.'" style="width:100%;"/></label>';

  echo '<label for="lfs_lost_or_found_place" style="display:block;padding: 4px;margin-bottom: 10px;"> Enter Place';
  echo '<input type="text" id="lfs_lost_or_found_place" name="lfs_lost_or_found_place" placeholder="Enter Place" value="'.$lfs_lost_or_found_place.'" style="width:100%;"/></label>';

  echo '<label for="lfs_state" style="display:block;padding: 4px;margin-bottom: 10px;"> State';
  echo '<input type="text" id="lfs_state" name="lfs_state" placeholder="Enter Place" value="'.$lfs_state.'" style="width:100%;"/></label>';

  echo '<label for="lfs_animal_type" style="display:block;padding: 4px;margin-bottom: 10px;"> Animal Type';
  echo '<input type="text" id="lfs_animal_type" name="lfs_animal_type" placeholder="Animal Type" value="'.$lfs_animal_type.'" style="width:100%;"/></label>';

  echo '<label for="lfs_animal_breed" style="display:block;padding: 4px;margin-bottom: 10px;"> Animal Breed';
  echo '<input type="text" id="lfs_animal_breed" name="lfs_animal_breed" placeholder="Animal Breed" value="'.$lfs_animal_breed.'" style="width:100%;"/></label>';

  echo '<label for="lfs_special_mark" style="display:block;padding: 4px;margin-bottom: 10px;"> Special Mark';
  echo '<input type="text" id="lfs_special_mark" name="lfs_special_mark" placeholder="Special Mark" value="'.$lfs_special_mark.'" style="width:100%;"/></label>';

  echo '<label for="lfs_birth_Day" style="display:block;padding: 4px;margin-bottom: 10px;"> Birthday';
  echo '<input type="text" id="lfs_birth_Day" name="lfs_birth_Day" placeholder="Birthday" value="'.$lfs_birth_Day.'" style="width:100%;"/></label>';

  echo '<label for="lfs_micro_chip" style="display:block;padding: 4px;margin-bottom: 10px;"> Microchip';
  echo '<input type="text" id="lfs_micro_chip" name="lfs_micro_chip" placeholder="Microchip" value="'.$lfs_micro_chip.'" style="width:100%;"/></label>';


}


function lfs_extra_information_box_save( $post_id ) {

  if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
  return;


  /*if ( !wp_verify_nonce( $_POST['lfs_user_email_content_nonce'], plugin_basename( __FILE__ ) && !wp_verify_nonce( $_POST['lfs_phone_number_content_nonce'], plugin_basename( __FILE__ ) ) && !wp_verify_nonce( $_POST['lfs_lost_or_found_date_nonce'], plugin_basename( __FILE__ ) ) && !wp_verify_nonce( $_POST['lfs_lost_or_found_place'], plugin_basename( __FILE__ ) ) && !wp_verify_nonce( $_POST['lfs_state'], plugin_basename( __FILE__ ) ) && !wp_verify_nonce( $_POST['lfs_animal_type'], plugin_basename( __FILE__ ) ) && !wp_verify_nonce( $_POST['lfs_animal_breed'], plugin_basename( __FILE__ ) ) && !wp_verify_nonce( $_POST['lfs_special_mark'], plugin_basename( __FILE__ ) ) && !wp_verify_nonce( $_POST['lfs_birth_Day'], plugin_basename( __FILE__ ) ) && !wp_verify_nonce( $_POST['lfs_micro_chip'], plugin_basename( __FILE__ ) ) ) )    
  return;*/

  if ( 'page' == $_POST['post_type'] ) {
    if ( !current_user_can( 'edit_page', $post_id ) )
    return;
  } else {
    if ( !current_user_can( 'edit_post', $post_id ) )
    return;
  }


  $lfs_user_email = $_POST['lfs_user_email'];
  update_post_meta( $post_id, 'lfs_user_email', $lfs_user_email );

  $lfs_phone_number = $_POST['lfs_phone_number'];
  update_post_meta( $post_id, 'lfs_phone_number', $lfs_phone_number );

  $lfs_lost_or_found_date = $_POST['lfs_lost_or_found_date'];
  update_post_meta( $post_id, 'lfs_lost_or_found_date', $lfs_lost_or_found_date );

  $lfs_lost_or_found_place = $_POST['lfs_lost_or_found_place'];
  update_post_meta( $post_id, 'lfs_lost_or_found_place', $lfs_lost_or_found_place );

  $lfs_state = $_POST['lfs_state'];
  update_post_meta( $post_id, 'lfs_state', $lfs_state);

  $lfs_animal_type = $_POST['lfs_animal_type'];
  update_post_meta( $post_id, 'lfs_animal_type', $lfs_animal_type);

  $lfs_animal_breed = $_POST['lfs_animal_breed'];
  update_post_meta( $post_id, 'lfs_animal_breed', $lfs_animal_breed);

  $lfs_special_mark = $_POST['lfs_special_mark'];
  update_post_meta( $post_id, 'lfs_special_mark', $lfs_special_mark);

  $lfs_birth_Day = $_POST['lfs_birth_Day'];
  update_post_meta( $post_id, 'lfs_birth_Day', $lfs_birth_Day);

  $lfs_micro_chip = $_POST['lfs_micro_chip'];
  update_post_meta( $post_id, 'lfs_micro_chip', $lfs_micro_chip);

}


// Add the custom columns to the book post type:

function set_custom_edit_advertisement_columns($columns) {
    //unset( $columns['author'] );
    $columns['email'] = __( 'Email', 'lost-found-system' );
    $columns['phone'] = __( 'Phone', 'lost-found-system' );
    $columns['animal_type'] = __( 'Animal Type', 'lost-found-system' );
    $columns['animal_breed'] = __( 'Animal Breed', 'lost-found-system' );

    return $columns;
}

// Add the data to the custom columns for the book post type:

function custom_advertisement_column( $column, $post_id ) {
    switch ( $column ) {

        case 'email' :
            echo get_post_meta( $post_id , 'lfs_user_email' , true ); 
            break;
        case 'phone' :
            echo get_post_meta( $post_id , 'lfs_phone_number' , true ); 
            break;
        case 'animal_type' :
            echo get_post_meta( $post_id , 'lfs_animal_type' , true ); 
            break;

        case 'animal_breed' :
            echo get_post_meta( $post_id , 'lfs_animal_breed' , true ); 
            break;

    }


}


function lfs_show_advertisement_extra_information( $content ) {
    if ( is_single()) {
        
	$lfs_user_email = get_post_meta( get_the_ID(), 'lfs_user_email', true );
	$lfs_phone_number = get_post_meta( get_the_ID(), 'lfs_phone_number', true );
	$lfs_lost_or_found_date = get_post_meta( get_the_ID(), 'lfs_lost_or_found_date', true );
	$lfs_lost_or_found_place = get_post_meta( get_the_ID(), 'lfs_lost_or_found_place', true );
	$lfs_state = get_post_meta( get_the_ID(), 'lfs_state', true );
	$lfs_animal_type = get_post_meta( get_the_ID(), 'lfs_animal_type', true );
	$lfs_animal_breed = get_post_meta( get_the_ID(), 'lfs_animal_breed', true );
	$lfs_special_mark = get_post_meta( get_the_ID(), 'lfs_special_mark', true );
	$lfs_birth_Day = get_post_meta( get_the_ID(), 'lfs_birth_Day', true );
	$lfs_micro_chip = get_post_meta( get_the_ID(), 'lfs_micro_chip', true );
    	$content .=  "<div class='missing_details'> ";    
	    //$content .= "<p><b> User Email: </b><a href=".'mailto:'.$lfs_user_email.">$lfs_user_email </a></p>";
	    $content .= "<p><b> Phone Number: </b> <a href=".'tel:'.$lfs_phone_number.">$lfs_phone_number </a></p>";
	    $content .= "<p><b> Date: </b> $lfs_lost_or_found_date </p>";
	    $content .= "<p><b> Place: </b> $lfs_lost_or_found_place </p>";
	    $content .= "<p><b> State: </b> $lfs_state </p>";
	    $content .= "<p><b> Animal Type: </b> $lfs_animal_type </p>";
	    $content .= "<p><b> Animal Breed: </b> $lfs_animal_breed </p>";
	    $content .= "<p><b> Special Mark: </b> $lfs_special_mark </p>";
	    $content .= "<p><b> Birthday: </b> $lfs_birth_Day </p>";
	    $content .= "<p><b> Microchip: </b> $lfs_micro_chip </p>";
	    $content .=  "</div>";    

	   	$content .=  "<h6><a href='#' class='missing_contact sc_button_hover_slide_left'> Contact </a></h6>";    

	    $content .=  "<div class='missing_details owner_contact'> ";    
	    	$content .= "<form method='post' action='' id='missing_form'> ";
	    		$content .= "<input type='text' id='myname' placeholder='Enter your name'>";	
	    		$content .= "<textarea id='mymessage' placeholder='Enter your message'></textarea>";
	    		$content .= "<input type='email' id='myemail' value='' placeholder='Enter your email'>";
	    		$content .= "<input type='text' id='myphone' value='' placeholder='Enter your phone'><input type='hidden' value='".$lfs_user_email."' id='owner_email'> ";
	    		$content .= "<input type='submit' id='my_response' value='Submit'>";		
	    	$content .= "</form>";
	    $content .=  "</div>";    


        return $content;
    } 
    return $content;
}



//init the meta box



function custom_postimage_meta_box(){

    //on which post types should the box appear?
    $post_types = array('advertisement');
    foreach($post_types as $pt){
        add_meta_box('custom_postimage_meta_box',__( 'More Featured Images', 'yourdomain'),array($this,'custom_postimage_meta_box_func'),$pt,'side','low');
    }
}

function custom_postimage_meta_box_func($post){

    //an array with all the images (ba meta key). The same array has to be in custom_postimage_meta_box_save($post_id) as well.
    $meta_keys = array('second_featured_image','third_featured_image','fourth_featured_image','fifth_featured_image');

    foreach($meta_keys as $meta_key){
        $image_meta_val=get_post_meta( $post->ID, $meta_key, true);
        ?>
        <div class="custom_postimage_wrapper" id="<?php echo $meta_key; ?>_wrapper" style="margin-bottom:20px;">
            <img src="<?php echo ($image_meta_val!=''?wp_get_attachment_image_src( $image_meta_val)[0]:''); ?>" style="width:100%;display: <?php echo ($image_meta_val!=''?'block':'none'); ?>" alt="">
            <a class="addimage button" onclick="custom_postimage_add_image('<?php echo $meta_key; ?>');"><?php _e('add image','yourdomain'); ?></a><br>
            <a class="removeimage" style="color:#a00;cursor:pointer;display: <?php echo ($image_meta_val!=''?'block':'none'); ?>" onclick="custom_postimage_remove_image('<?php echo $meta_key; ?>');"><?php _e('remove image','yourdomain'); ?></a>
            <input type="hidden" name="<?php echo $meta_key; ?>" id="<?php echo $meta_key; ?>" value="<?php echo $image_meta_val; ?>" />
        </div>
    <?php } ?>
    <script>
    function custom_postimage_add_image(key){

        var $wrapper = jQuery('#'+key+'_wrapper');

        custom_postimage_uploader = wp.media.frames.file_frame = wp.media({
            title: '<?php _e('select image','yourdomain'); ?>',
            button: {
                text: '<?php _e('select image','yourdomain'); ?>'
            },
            multiple: false
        });
        custom_postimage_uploader.on('select', function() {

            var attachment = custom_postimage_uploader.state().get('selection').first().toJSON();
            var img_url = attachment['url'];
            var img_id = attachment['id'];
            $wrapper.find('input#'+key).val(img_id);
            $wrapper.find('img').attr('src',img_url);
            $wrapper.find('img').show();
            $wrapper.find('a.removeimage').show();
        });
        custom_postimage_uploader.on('open', function(){
            var selection = custom_postimage_uploader.state().get('selection');
            var selected = $wrapper.find('input#'+key).val();
            if(selected){
                selection.add(wp.media.attachment(selected));
            }
        });
        custom_postimage_uploader.open();
        return false;
    }

    function custom_postimage_remove_image(key){
        var $wrapper = jQuery('#'+key+'_wrapper');
        $wrapper.find('input#'+key).val('');
        $wrapper.find('img').hide();
        $wrapper.find('a.removeimage').hide();
        return false;
    }
    </script>
    <?php
    wp_nonce_field( 'custom_postimage_meta_box', 'custom_postimage_meta_box_nonce' );
}

function custom_postimage_meta_box_save($post_id){

    if ( ! current_user_can( 'edit_posts', $post_id ) ){ return 'not permitted'; }

    if (isset( $_POST['custom_postimage_meta_box_nonce'] ) && wp_verify_nonce($_POST['custom_postimage_meta_box_nonce'],'custom_postimage_meta_box' )){

        //same array as in custom_postimage_meta_box_func($post)
        $meta_keys = array('second_featured_image','third_featured_image','fourth_featured_image','fifth_featured_image');
        foreach($meta_keys as $meta_key){
            if(isset($_POST[$meta_key]) && intval($_POST[$meta_key])!=''){
                update_post_meta( $post_id, $meta_key, intval($_POST[$meta_key]));
            }else{
                update_post_meta( $post_id, $meta_key, '');
            }
        }
    }
}


/*
 * Extend wp search to include custom post meta 
 */
 
function lfs_custom_search_query( $query ) {
    //if ( !is_admin() && $query->is_search ) {
    	$_model = $_GET['s'];
        $query->set('meta_query', array(
            array(
                'key' => 'lfs_lost_or_found_date',
                //'value' => $query->query_vars['s'],
                'value' => $_model,
                'compare' => 'LIKE'
            )
        ));
         $query->set('post_type', 'advertisement'); // optional
    //};
}





} // class ends

// CHECK WETHER CLASS EXISTS OR NOT.

if (class_exists('LostFoundSystem')) {
	$obj = new LostFoundSystem();
}