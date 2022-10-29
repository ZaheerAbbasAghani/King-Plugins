<?php
/*
Plugin Name: Style Rank Maker
Plugin URI: https://www.fiverr.com/zaheerabbasagha
Description: The service should be an online query for the user to answer some questions (through ranking pictures of styles from most liked to least liked, probably 4-5 screens/steps) about their preferences, to determine their personal style. Their style should then be saved in their personal login (Hubspot) to be used for personal content on the site later on. I will provide the pictures on styles, the attributes per picture and a simple logic, in Excel if that is ok for you. So I guess it’s a plugin but also a ranking model in the background.
Version: 1.0
Author: Zaheer Abbas Aghani
Author URI: https://profiles.wordpress.org/zaheer01/
License: GPLv3 or later
Text Domain: style-rank-maker
Domain Path: /languages
*/

defined("ABSPATH") or die("No direct access!");
class StyleRankMaker {

function __construct() {
	add_action('init', array($this, 'srm_start_from_here'));
	add_action('wp_enqueue_scripts', array($this, 'srm_enqueue_script_front'));
	add_action('admin_enqueue_scripts',array($this,'srm_enqueue_admin'));
	require_once plugin_dir_path(__FILE__) . 'back/srm_custom_post_type.php';

    add_action( 'add_meta_boxes',  array($this, 'srm_custom_postimage_meta_box') );
    add_action( 'save_post',  array($this, 'srm_custom_postimage_meta_box_save') );

    add_action('init', array($this,'srm_create_table') );
	add_action('wp_login',array($this,'srm_user_login'), 10, 2);
}



function srm_start_from_here() {
	
	require_once plugin_dir_path(__FILE__).'front/srm_rank_slider.php';
	require_once plugin_dir_path(__FILE__).'front/srm_save_rankings.php';

}

// Enqueue Style and Scripts

function srm_enqueue_script_front() {
//Style & Script


wp_enqueue_style('srm-slick', 'https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css','1.8.1','all');
wp_enqueue_style('srm-slick-theme', 'https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick-theme.css','1.8.1','all');
wp_enqueue_style('srm-sweet', 'https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/6.6.9/sweetalert2.min.css','6.6.9','all');


wp_enqueue_style('srm-style', plugins_url('assets/css/srm.css', __FILE__),'1.0.0','all');



wp_enqueue_script('srm-slick', plugins_url('assets/js/slickSlider.js', __FILE__),array('jquery'),'1.5.2', true);

if(function_exists( 'wp_enqueue_media' )){
    wp_enqueue_media();
}else{
    wp_enqueue_style('thickbox');
    wp_enqueue_script('media-upload');
    wp_enqueue_script('thickbox');
}


wp_enqueue_script('srm-sweet-alert', "https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/6.6.9/sweetalert2.min.js",array('jquery'),'6.6.9', true);

wp_enqueue_script('srm-script', plugins_url('assets/js/srm.js', __FILE__),array('jquery'),'1.0.0', true);

wp_localize_script( 'srm-script', 'srm_object', array( 'ajax_url' => admin_url( 'admin-ajax.php' )) );

}


function srm_enqueue_admin($hook) {
    if($hook != "styles_page_styles-settings"){
        return;
    }else{
        wp_enqueue_script('srm-admin-script', plugins_url('assets/js/srm_admin.js', __FILE__),array('jquery'),'1.0.0', true);
    }
}


function srm_custom_postimage_meta_box(){

    //on which post types should the box appear?
    $post_types = array('styles');
    foreach($post_types as $pt){
        add_meta_box('srm_custom_postimage_meta_box',__( 'Upload Images', 'style-rank-maker'),array($this,'srm_custom_postimage_meta_box_func'),$pt,'normal','low');
    }
}

function srm_custom_postimage_meta_box_func($post){

    //an array with all the images (ba meta key). The same array has to be in srm_custom_postimage_meta_box_save($post_id) as well.
    $meta_keys = array('second_featured_image','third_featured_image');

    foreach($meta_keys as $meta_key){
        $image_meta_val=get_post_meta( $post->ID, $meta_key, true);
        ?>
        <div class="custom_postimage_wrapper" id="<?php echo $meta_key; ?>_wrapper" style="margin-bottom:20px;">
            <img src="<?php echo ($image_meta_val!=''?wp_get_attachment_image_src( $image_meta_val)[0]:''); ?>" style="display: <?php echo ($image_meta_val!=''?'block':'none'); ?>; height: 270px;" alt=""><br>
            <a class="addimage button" onclick="srm_custom_postimage_add_image('<?php echo $meta_key; ?>');"><?php _e('Add image','yourdomain'); ?></a><br>
            <a class="removeimage" style="color:#a00;cursor:pointer;margin-top:10px;display: <?php echo ($image_meta_val!=''?'block':'none'); ?>" onclick="srm_custom_postimage_remove_image('<?php echo $meta_key; ?>');"><?php _e('Remove image','yourdomain'); ?></a>
            <input type="hidden" name="<?php echo $meta_key; ?>" id="<?php echo $meta_key; ?>" value="<?php echo $image_meta_val; ?>" />
        </div>
    <?php } ?>
    <script>
    function srm_custom_postimage_add_image(key){

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

    function srm_custom_postimage_remove_image(key){
        var $wrapper = jQuery('#'+key+'_wrapper');
        $wrapper.find('input#'+key).val('');
        $wrapper.find('img').hide();
        $wrapper.find('a.removeimage').hide();
        return false;
    }
    </script>
    <?php
    wp_nonce_field( 'srm_custom_postimage_meta_box', 'srm_custom_postimage_meta_box_nonce' );
}

function srm_custom_postimage_meta_box_save($post_id){

    if ( ! current_user_can( 'edit_posts', $post_id ) ){ return 'not permitted'; }

    if (isset( $_POST['srm_custom_postimage_meta_box_nonce'] ) && wp_verify_nonce($_POST['srm_custom_postimage_meta_box_nonce'],'srm_custom_postimage_meta_box' )){

        //same array as in srm_custom_postimage_meta_box_func($post)
        $meta_keys = array('second_featured_image','third_featured_image');
        foreach($meta_keys as $meta_key){
            if(isset($_POST[$meta_key]) && intval($_POST[$meta_key])!=''){
                update_post_meta( $post_id, $meta_key, intval($_POST[$meta_key]));
            }else{
                update_post_meta( $post_id, $meta_key, '');
            }
        }
    }
}



function srm_create_table(){

    global $wpdb;
    $table_name = $wpdb->base_prefix.'srm_rankings';
    $query = $wpdb->prepare( 'SHOW TABLES LIKE %s', $wpdb->esc_like( $table_name ) );
    if ( ! $wpdb->get_var( $query ) == $table_name ) {

        $charset_collate = $wpdb->get_charset_collate();
        $sql = "CREATE TABLE $table_name (
          id mediumint(255) NOT NULL AUTO_INCREMENT,
          user_id int(255) NOT NULL,
          ranking text NOT NULL,
          ranking_result varchar(50) NOT NULL,
          PRIMARY KEY  (id)
        ) $charset_collate;";
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );
    }

    //$wpdb->query("TRUNCATE TABLE $table_name");
}


 
function srm_user_login($user_login, $user) {
 

	global $wpdb; 
	$table_name = $wpdb->base_prefix.'srm_rankings';
	$user_id = $user->ID;

	$query = "SELECT * FROM $table_name WHERE user_id='$user_id' ";
	$query_results = $wpdb->get_results($query);
	if(count($query_results) == 0) {
		$rowResult=$wpdb->insert($table_name, 
				array("user_id" => $user_id,"ranking" => $_COOKIE['ranking_details'], "ranking_result" => $_COOKIE['ranking_style']),
				array("%d","%s","%s")
		);
	}

 
}



} // class ends

// CHECK WETHER CLASS EXISTS OR NOT.

if (class_exists('StyleRankMaker')) {
	$obj = new StyleRankMaker();
}