<?php

/*
* Creating a function to create our CPT
*/
  
function gffaq_custom_post_type() {
  
// Set UI labels for Custom Post Type
    $labels = array(
        'name'                => _x( 'GF Faqs', 'Post Type General Name', 'twentytwentyone' ),
        'singular_name'       => _x( 'GF Faq', 'Post Type Singular Name', 'twentytwentyone' ),
        'menu_name'           => __( 'GF Faqs', 'twentytwentyone' ),
        'parent_item_colon'   => __( 'Parent GF Faq', 'twentytwentyone' ),
        'all_items'           => __( 'All GF Faqs', 'twentytwentyone' ),
        'view_item'           => __( 'View GF Faq', 'twentytwentyone' ),
        'add_new_item'        => __( 'Add New GF Faq', 'twentytwentyone' ),
        'add_new'             => __( 'Add New', 'twentytwentyone' ),
        'edit_item'           => __( 'Edit GF Faq', 'twentytwentyone' ),
        'update_item'         => __( 'Update GF Faq', 'twentytwentyone' ),
        'search_items'        => __( 'Search GF Faq', 'twentytwentyone' ),
        'not_found'           => __( 'Not Found', 'twentytwentyone' ),
        'not_found_in_trash'  => __( 'Not found in Trash', 'twentytwentyone' ),
    );
      
// Set other options for Custom Post Type
      
    $args = array(
        'label'               => __( 'gffaq', 'twentytwentyone' ),
        'description'         => __( 'GF Faq news and reviews', 'twentytwentyone' ),
        'labels'              => $labels,
        // Features this CPT supports in Post Editor
        'supports'            => array( 'title', 'revisions' ),
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
        'menu_icon'   => 'dashicons-search',
        'menu_position' => 20,
  
    );
      
    // Registering your Custom Post Type
    register_post_type( 'gffaq', $args );
  
}
  
/* Hook into the 'init' action so that the function
* Containing our post type registration is not 
* unnecessarily executed. 
*/
  
add_action( 'init', 'gffaq_custom_post_type', 0 );


function prefix_gffaq_metaboxes( ) {
   global $wp_meta_boxes;
   add_meta_box('postfunctiondiv', __('FAQ Handler'), 'prefix_gffaq_metaboxes_html', 'gffaq', 'normal', 'high');
}
add_action( 'add_meta_boxes_gffaq', 'prefix_gffaq_metaboxes' );


function prefix_gffaq_metaboxes_html(){
	echo '<div id="build-wrap"><img src="https://www.geekpassionsgifts.com/wp-content/uploads/2022/05/Ajux_loader.gif" class="loadingImg" style="width:300px;margin:auto;"></div>';
}