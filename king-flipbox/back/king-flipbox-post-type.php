<?php 

/*
* Creating a function to create our CPT
*/
 
function custom_post_type() {
 
// Set UI labels for Custom Post Type
    $labels = array(
        'name'                => _x( 'FlipBoxes', 'Post Type General Name', 'KingFlipBox' ),
        'singular_name'       => _x( 'FlipBox', 'Post Type Singular Name', 'KingFlipBox' ),
        'menu_name'           => __( 'FlipBoxes', 'KingFlipBox' ),
        'parent_item_colon'   => __( 'Parent FlipBox', 'KingFlipBox' ),
        'all_items'           => __( 'All FlipBoxes', 'KingFlipBox' ),
        'view_item'           => __( 'View FlipBox', 'KingFlipBox' ),
        'add_new_item'        => __( 'Add New FlipBox', 'KingFlipBox' ),
        'add_new'             => __( 'Add New', 'KingFlipBox' ),
        'edit_item'           => __( 'Edit FlipBox', 'KingFlipBox' ),
        'update_item'         => __( 'Update FlipBox', 'KingFlipBox' ),
        'search_items'        => __( 'Search FlipBox', 'KingFlipBox' ),
        'not_found'           => __( 'Not Found', 'KingFlipBox' ),
        'not_found_in_trash'  => __( 'Not found in Trash', 'KingFlipBox' ),
    );
     
// Set other options for Custom Post Type
     
    $args = array(
        'label'               => __( 'flipboxes', 'KingFlipBox' ),
        'description'         => __( 'FlipBox news and reviews', 'KingFlipBox' ),
        'labels'              => $labels,
        // Features this CPT supports in Post Editor
        'supports'            => array( 'title',  'thumbnail', 'revisions'),
        // You can associate this CPT with a taxonomy or custom taxonomy. 
        'taxonomies'          => array( 'genres' ),
        /* A hierarchical CPT is like Pages and can have
        * Parent and child items. A non-hierarchical CPT
        * is like Posts.
        */ 
        'hierarchical'        => false,
        'public'              => false,
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
        'menu_icon'   => 'dashicons-format-gallery',
 
    );
     
    // Registering your Custom Post Type
    register_post_type( 'flipboxes', $args );
 
}
 
/* Hook into the 'init' action so that the function
* Containing our post type registration is not 
* unnecessarily executed. 
*/
 
add_action( 'init', 'custom_post_type', 0 );


function king_flipboxes_metaboxes( ) {
   global $wp_meta_boxes;
   add_meta_box('redirect_url', __('Redirect URL'), 'king_flipboxes_metaboxes_html', 'flipboxes', 'normal', 'high');

}
add_action( 'add_meta_boxes_flipboxes', 'king_flipboxes_metaboxes' );

function king_flipboxes_metaboxes_html()
{
    global $post;
    $custom = get_post_custom($post->ID);
    $redirect_url = isset($custom["redirect_url"][0])?$custom["redirect_url"][0]:'';
?>
<input type="url" name="redirect_url" value="<?php echo $redirect_url; ?>">
<?php
}

function king_flipboxes_save_post()
{
    if(empty($_POST)) return; //why is king_flipboxes_save_post triggered by add new? 
    global $post;
    update_post_meta($post->ID, "redirect_url", $_POST["redirect_url"]);
}   

add_action( 'save_post_flipboxes', 'king_flipboxes_save_post' );   