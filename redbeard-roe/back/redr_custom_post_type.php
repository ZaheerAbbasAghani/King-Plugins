<?php 

/*
* Creating a function to create our CPT
*/
 
function custom_post_type() {
 
// Set UI labels for Custom Post Type
    $labels = array(
        'name'                => _x( 'My Siri Shorcuts', 'Post Type General Name', 'redbeardroe' ),
        'singular_name'       => _x( 'Siri Shortcuts', 'Post Type Singular Name', 'redbeardroe' ),
        'menu_name'           => __( 'My Siri Shorcuts', 'redbeardroe' ),
        'parent_item_colon'   => __( 'Parent Siri Shortcuts', 'redbeardroe' ),
        'all_items'           => __( 'All My Siri Shorcuts', 'redbeardroe' ),
        'view_item'           => __( 'View Siri Shortcuts', 'redbeardroe' ),
        'add_new_item'        => __( 'Add New Siri Shortcuts', 'redbeardroe' ),
        'add_new'             => __( 'Add New', 'redbeardroe' ),
        'edit_item'           => __( 'Edit Siri Shortcuts', 'redbeardroe' ),
        'update_item'         => __( 'Update Siri Shortcuts', 'redbeardroe' ),
        'search_items'        => __( 'Search Siri Shortcuts', 'redbeardroe' ),
        'not_found'           => __( 'Not Found', 'redbeardroe' ),
        'not_found_in_trash'  => __( 'Not found in Trash', 'redbeardroe' ),
    );
     
// Set other options for Custom Post Type
     
    $args = array(
        'label'               => __( 'siri_shortcuts', 'redbeardroe' ),
        'description'         => __( 'Siri Shortcuts news and reviews', 'redbeardroe' ),
        'labels'              => $labels,
        // Features this CPT supports in Post Editor
        'supports'=>array('title','editor','thumbnail','taxonomies'),
        'taxonomies'  => array( 'integration' ),
        'hierarchical'        => false,
        'public'              => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'show_in_nav_menus'   => true,
        'show_in_admin_bar'   => true,
        'menu_position'       => 5,
        'can_export'          => true,
        'has_archive'         => true,
        'exclude_from_search' => false,
        'publicly_queryable'  => true,
        'capability_type'     => 'post',
        'show_in_rest' => true,
        'menu_icon'   => 'dashicons-backup',
 
    );
     
    // Registering your Custom Post Type
    register_post_type( 'siri_shortcuts', $args );
 
}
 
/* Hook into the 'init' action so that the function
* Containing our post type registration is not 
* unnecessarily executed. 
*/
 
add_action( 'init', 'custom_post_type', 0 );



add_action( 'init', 'create_integration_taxonomies', 0 );

//create two taxonomies, genres and integrations for the post type "integration"
function create_integration_taxonomies() 
{
  // Add new taxonomy, NOT hierarchical (like integrations)
  $labels = array(
    'name' => _x( 'Integrations', 'taxonomy general name' ),
    'singular_name' => _x( 'Integration', 'taxonomy singular name' ),
    'search_items' =>  __( 'Search Integrations' ),
    'popular_items' => __( 'Popular Integrations' ),
    'all_items' => __( 'All Integrations' ),
    'parent_item' => null,
    'parent_item_colon' => null,
    'edit_item' => __( 'Edit Integration' ), 
    'update_item' => __( 'Update Integration' ),
    'add_new_item' => __( 'Add New Integration' ),
    'new_item_name' => __( 'New Integration Name' ),
    'separate_items_with_commas' => __( 'Separate integrations with commas' ),
    'add_or_remove_items' => __( 'Add or remove integrations' ),
    'choose_from_most_used' => __( 'Choose from the most used integrations' ),
    'menu_name' => __( 'Integrations' ),
  ); 

  register_taxonomy('integration','siri_shortcuts',array(
    'hierarchical' =>  array( 
            'hierarchical'  => false, 
            'label'         => __( 'Integration', CURRENT_THEME ), 
            'singular_name' => __( 'Integration', CURRENT_THEME ), 
            'rewrite'       => true, 
            'query_var'     => true 
        )  ,
    'labels' => $labels,
    'show_ui' => true,
    'show_in_rest' => true,
    'update_count_callback'=>'_update_post_term_count',
    'query_var' => true,
    'rewrite' => array( 'slug' => 'integration' ),
  ));
}
add_action( 'admin_menu', 'redr_add_meta_box');

/*
    Meta Box
*/
function redr_add_meta_box() {

    add_meta_box(
        'redr_metabox', // metabox ID
        'Sir Shortcut Fields', // title
         'redr_metabox_siri_shortcuts_callback', // callback function
        'siri_shortcuts',
        'normal', // position (normal, side, advanced)
        'default' // priority (default, low, high, core)
    );

}


function redr_metabox_siri_shortcuts_callback( $post ) {
    wp_nonce_field( basename( __FILE__ ), 'redr_shortcut_1_nonce' ); 
    wp_nonce_field( basename( __FILE__ ), 'redr_shortcut_2_nonce' ); 
    wp_nonce_field( basename( __FILE__ ), 'redr_shortcut_3_nonce' ); 
    wp_nonce_field( basename( __FILE__ ), 'redr_shortcut_4_nonce' ); 
    wp_nonce_field( basename( __FILE__ ), 'redr_shortcut_5_nonce' ); 
    wp_nonce_field( basename( __FILE__ ), 'redr_shortcut_6_nonce' ); 
    wp_nonce_field( basename( __FILE__ ), 'redr_shortcut_7_nonce' ); 
    wp_nonce_field( basename( __FILE__ ), 'redr_shortcut_8_nonce' ); 


   $title = get_post_meta($post->ID, 'redr_shortcut_title', true);
   $current_version = get_post_meta($post->ID, 'redr_curret_version', true);
   $pre_version = get_post_meta($post->ID, 'redr_pre_version', true);
   $current_version_no = get_post_meta($post->ID, 'redr_current_version_no', true);
   $pre_version_no = get_post_meta($post->ID, 'redr_pre_version_no', true);
   $shortcut_demo_video = get_post_meta($post->ID, 'redr_shortcut_demo_video', true);
   $shortcut_qr_code = get_post_meta($post->ID, 'redr_shortcut_qr_code', true);
   $shortcut_about = get_post_meta($post->ID, 'redr_shortcut_about', true);


    echo '<p><label for="redr_shortcut_title" style="display:block;font-weight:bold;">'.__('Shortcut Title <span style="color:red;"> * </span>', 'textdomain').'</label><p> This is the name of your shortcut. </p><input type="text" name="redr_shortcut_title" value="'.$title.'" required style="width:100%;"/></p><hr>';

    echo '<p><label for="redr_curret_version" style="display:block;font-weight:bold;">'.__('Version Shortcut URL <span style="color:red;"> * </span>', 'textdomain').'</label><p> Current  This is where your shortcut can be found. </p><input type="url" name="redr_curret_version" value="'.$current_version.'" required style="width:100%;"/></p><hr>';

    echo '<p><label for="redr_pre_version" style="display:block;font-weight:bold;">'.__('Previous version shortcut URL ', 'textdomain').'</label><input type="url" name="redr_pre_version" value="'.$pre_version.'" required style="width:100%;"/></p><hr>';

    echo '<p><label for="redr_current_version_no" style="display:block;font-weight:bold;">'.__('Current Version Number  <span style="color:red;"> * </span>', 'textdomain').'</label><p> This is your current version number of this shortcut. </p><input type="text" name="redr_current_version_no" value="'.$current_version_no.'" required style="width:100%;"/></p><hr>';

    echo '<p><label for="redr_pre_version_no" style="display:block;font-weight:bold;">'.__('Previous Version Number ', 'textdomain').'</label><p> This is the name of your shortcut. </p><input type="text" name="redr_pre_version_no" value="'.$pre_version_no.'"  style="width:100%;"/></p><hr>';

     echo "<p><b> Changelog <span style='color:red;'> * </span></b></p><hr>";

     echo '<p><label for="redr_shortcut_demo_video" style="display:block;font-weight:bold;">'.__('Shortcut Demo Video', 'textdomain').'</label></label><input type="text" name="redr_shortcut_demo_video" value="'.$shortcut_demo_video.'"  style="width:100%;"/></p><hr>';


    echo '<p><label for="redr_shortcut_demo_video" style="display:block;font-weight:bold;margin:10px 0px;">'.__('Shortcut Demo Video', 'textdomain').'</label><p> This is a QR code image your users can scan to downlad with. </p><input id="upload_image" type="hidden" size="36" name="redr_shortcut_qr_code" value="'.$shortcut_qr_code.'" /><img src="'.$shortcut_qr_code.'" style="width:300px; height:300px;">
        <input id="upload_image_button" type="button" value="Upload Image" class="button button-default"/><hr>';

    
     echo '<label for="redr_shortcut_about" style="display:block;font-weight:bold;margin:10px 0px;">'.__('About This Shortcut', 'textdomain').'<textarea name="redr_shortcut_about" style="display:none;"> '.$shortcut_about.'</textarea></label>';

     echo wp_editor($shortcut_about, 'custom_editor_1', array( 'textarea_name' => 'redr_shortcut_about','textarea_rows' => 10 ));


}

add_action( 'save_post_siri_shortcuts', 'siri_shortcut_save_meta_boxes_data', 10, 2 );
function siri_shortcut_save_meta_boxes_data( $post_id ){
    // check for nonce to top xss

    for($i = 1; $i<=8; $i ++){

        if ( !isset( $_POST['redr_shortcut_'.$i.'_nonce'] ) || !wp_verify_nonce( $_POST['redr_shortcut_'.$i.'_nonce'], basename( __FILE__ ) ) ){
            return;
        }
    }

    // check for correct user capabilities - stop internal xss from customers
    if ( ! current_user_can( 'edit_post', $post_id ) ){
        return;
    }

    // update fields
    if ( isset( $_REQUEST['redr_shortcut_title'] ) ) {
        update_post_meta($post_id,'redr_shortcut_title', sanitize_text_field($_POST['redr_shortcut_title']));
    }

    if ( isset( $_REQUEST['redr_curret_version'] ) ) {
        update_post_meta($post_id,'redr_curret_version', sanitize_text_field($_POST['redr_curret_version']));
    }

    if ( isset( $_REQUEST['redr_pre_version'] ) ) {
        update_post_meta($post_id,'redr_pre_version', sanitize_text_field($_POST['redr_pre_version']));
    }

    if ( isset( $_REQUEST['redr_current_version_no'] ) ) {
        update_post_meta($post_id,'redr_current_version_no', sanitize_text_field($_POST['redr_current_version_no']));
    }

    if ( isset( $_REQUEST['redr_pre_version_no'] ) ) {
        update_post_meta($post_id,'redr_pre_version_no', sanitize_text_field($_POST['redr_pre_version_no']));
    }

    if (isset($_REQUEST['redr_shortcut_demo_video'])) {
        update_post_meta($post_id,'redr_shortcut_demo_video', sanitize_text_field($_POST['redr_shortcut_demo_video']));
    }

    if (isset($_REQUEST['redr_shortcut_about'])) {
        update_post_meta($post_id,'redr_shortcut_about', sanitize_text_field($_POST['redr_shortcut_about']));
    }

    if (isset($_REQUEST['redr_shortcut_qr_code'])) {
        update_post_meta($post_id,'redr_shortcut_qr_code', sanitize_text_field($_POST['redr_shortcut_qr_code']));
    }


    
}