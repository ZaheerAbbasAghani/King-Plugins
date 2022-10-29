<?php 

/*
* Creating a function to create our CPT
*/
 
function gstl_register_events_custom_post_type() {
 
// Set UI labels for Custom Post Type
    $labels = array(
        'name'  => _x( 'Events', 'Post Type General Name', 'gstl' ),
        'singular_name' => _x( 'Event', 'Post Type Singular Name', 'gstl' ),
        'menu_name'  => __( 'Events', 'gstl' ),
        'parent_item_colon' => __('Parent Event','gstl'),
        'all_items'  => __( 'All Events', 'gstl' ),
        'view_item'  => __( 'View Event', 'gstl' ),
        'add_new_item' => __( 'Add New Event', 'gstl' ),
        'add_new'     => __( 'Add New', 'gstl' ),
        'edit_item'   => __( 'Edit Event', 'gstl' ),
        'update_item' => __( 'Update Event', 'gstl' ),
        'search_items' => __( 'Search Event', 'gstl' ),
        'not_found'    => __( 'Not Found', 'gstl' ),
        'not_found_in_trash' => __('Not found in Trash', 'gstl' ),
    );
     
// Set other options for Custom Post Type
     
    $args = array(
        'label'               => __( 'Events', 'gstl' ),
        'description'         => __( 'Event news and reviews', 'gstl' ),
        'labels'              => $labels,
        // Features this CPT supports in Post Editor
        'supports'    => array( 'title', 'editor', 'thumbnail','revisions'),
        'taxonomies'          => array( 'type' ),
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
 
    );
     
    // Registering your Custom Post Type
    register_post_type( 'gstl_events', $args );
 
}
 
/* Hook into the 'init' action so that the function
* Containing our post type registration is not 
* unnecessarily executed. 
*/
 
add_action('init','gstl_register_events_custom_post_type', 0 );