<?php
/*
Plugin Name: King Flipbox
Plugin URI: https://www.fiverr.com/zaheerabbasagha
Description: A Flip Box Plugin which Flips or Fades from one picture element to another. The Pictures should be Customizable also Customer wants to set the pictures by himself.
Version: 1.0
Author: Zaheer Abbas Aghani
Author URI: https://profiles.wordpress.org/zaheer01/
License: GPLv3 or later
Text Domain: KingFlipBox
Domain Path: /languages
*/

defined("ABSPATH") or die("No direct access!");
class KingFlipBox {

function __construct() {
	add_action('init', array($this, 'flip_start_from_here'));
	add_action('wp_enqueue_scripts', array($this, 'flip_enqueue_script_front'));
	//add_action('admin_enqueue_scripts', array($this, 'flip_enqueue_admin'));

	add_action( 'add_meta_boxes', array($this, 'flip_postimage_meta_box') );
    add_action( 'save_post', array($this,'flip_postimage_meta_box_save') );
	
}

function flip_start_from_here() {
	
	require_once plugin_dir_path(__FILE__) . 'front/king-flipbox-boxes-display.php';




}

// Enqueue Style and Scripts

function flip_enqueue_script_front() {
	//Style & Script
	wp_enqueue_style('flip-style', plugins_url('assets/css/flip.css', __FILE__),'1.0.0','all');
	//wp_enqueue_script('flip-multi-post-thumbnails-admin', plugins_url('assets/js/multi-post-thumbnails-admin.js', __FILE__),array('jquery'),'1.0.0', true);

	wp_enqueue_script('flip-script', plugins_url('assets/js/flip.js', __FILE__),array('jquery'),'1.0.0', true);

}


//init the meta box
/*add_action( 'after_setup_theme', 'flip_postimage_setup' );
function flip_postimage_setup(){
    add_action( 'add_meta_boxes', 'flip_postimage_meta_box' );
    add_action( 'save_post', 'flip_postimage_meta_box_save' );
}
*/
function flip_postimage_meta_box(){

    //on which post types should the box appear?
    $post_types = array('flipboxes');
    foreach($post_types as $pt){
        add_meta_box('flip_postimage_meta_box',__( 'Hover Image', 'KingFlipBox'),array($this,'flip_postimage_meta_box_func'),$pt,'normal','low');
    }
}

function flip_postimage_meta_box_func($post){

    //an array with all the images (ba meta key). The same array has to be in flip_postimage_meta_box_save($post_id) as well.
    $meta_keys = array('second_featured_image');

    foreach($meta_keys as $meta_key){
        $image_meta_val=get_post_meta( $post->ID, $meta_key, true);
        ?>
        <div class="flip_postimage_wrapper" id="<?php echo $meta_key; ?>_wrapper" style="margin-bottom:20px;">
            <img src="<?php echo ($image_meta_val!=''?wp_get_attachment_image_src( $image_meta_val, 'full-size')[0]:''); ?>" style="width:266px;height: 200px;display: <?php echo ($image_meta_val!=''?'block':'none'); ?>" alt=""><br>
            <a class="addimage button" onclick="flip_postimage_add_image('<?php echo $meta_key; ?>');" style="color: #444;border: 1px solid #ddd;margin: 10px 0px;"><?php _e('Upload image','KingFlipBox'); ?></a>
            <a class="removeimage" style="color:#a00;cursor:pointer;display: <?php echo ($image_meta_val!=''?'block':'none'); ?>" onclick="flip_postimage_remove_image('<?php echo $meta_key; ?>');"><?php _e('Remove Hover image','KingFlipBox'); ?></a>
            <input type="hidden" name="<?php echo $meta_key; ?>" id="<?php echo $meta_key; ?>" value="<?php echo $image_meta_val; ?>" />
        </div>
    <?php } ?>
    <script>
    function flip_postimage_add_image(key){

        var $wrapper = jQuery('#'+key+'_wrapper');

        flip_postimage_uploader = wp.media.frames.file_frame = wp.media({
            title: '<?php _e('select image','KingFlipBox'); ?>',
            button: {
                text: '<?php _e('select image','KingFlipBox'); ?>'
            },
            multiple: false
        });
        flip_postimage_uploader.on('select', function() {

            var attachment = flip_postimage_uploader.state().get('selection').first().toJSON();
            var img_url = attachment['url'];
            var img_id = attachment['id'];
            $wrapper.find('input#'+key).val(img_id);
            $wrapper.find('img').attr('src',img_url);
            $wrapper.find('img').show();
            $wrapper.find('a.removeimage').show();
        });
        flip_postimage_uploader.on('open', function(){
            var selection = flip_postimage_uploader.state().get('selection');
            var selected = $wrapper.find('input#'+key).val();
            if(selected){
                selection.add(wp.media.attachment(selected));
            }
        });
        flip_postimage_uploader.open();
        return false;
    }

    function flip_postimage_remove_image(key){
        var $wrapper = jQuery('#'+key+'_wrapper');
        $wrapper.find('input#'+key).val('');
        $wrapper.find('img').hide();
        $wrapper.find('a.removeimage').hide();
        return false;
    }
    </script>
    <?php
    wp_nonce_field( 'flip_postimage_meta_box', 'flip_postimage_meta_box_nonce' );
}

function flip_postimage_meta_box_save($post_id){

    if ( ! current_user_can( 'edit_posts', $post_id ) ){ return 'not permitted'; }

    if (isset( $_POST['flip_postimage_meta_box_nonce'] ) && wp_verify_nonce($_POST['flip_postimage_meta_box_nonce'],'flip_postimage_meta_box' )){

        //same array as in flip_postimage_meta_box_func($post)
        $meta_keys = array('second_featured_image');
        foreach($meta_keys as $meta_key){
            if(isset($_POST[$meta_key]) && intval($_POST[$meta_key])!=''){
                update_post_meta( $post_id, $meta_key, intval($_POST[$meta_key]));
            }else{
                update_post_meta( $post_id, $meta_key, '');
            }
        }
    }
}



} // class ends

// CHECK WETHER CLASS EXISTS OR NOT.

if (class_exists('KingFlipBox')) {
	$obj = new KingFlipBox();
	require_once plugin_dir_path(__FILE__) . 'back/king-flipbox-post-type.php';

}