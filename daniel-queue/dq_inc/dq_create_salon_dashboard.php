<?php

/*
* Creating a function to create our CPT
*/
 
 
// Set UI labels for Custom Post Type
    $labels = array(
        'name'                => _x( 'Salons', 'Post Type General Name', 'daniel-queue' ),
        'singular_name'       => _x( 'Salon', 'Post Type Singular Name', 'daniel-queue' ),
        'menu_name'           => __( 'Salons', 'daniel-queue' ),
        'parent_item_colon'   => __( 'Parent Salon', 'daniel-queue' ),
        'all_items'           => __( 'All Salons', 'daniel-queue' ),
        'view_item'           => __( 'View Salon', 'daniel-queue' ),
        'add_new_item'        => __( 'Add New Salon', 'daniel-queue' ),
        'add_new'             => __( 'Add New', 'daniel-queue' ),
        'edit_item'           => __( 'Edit Salon', 'daniel-queue' ),
        'update_item'         => __( 'Update Salon', 'daniel-queue' ),
        'search_items'        => __( 'Search Salon', 'daniel-queue' ),
        'not_found'           => __( 'Not Found', 'daniel-queue' ),
        'not_found_in_trash'  => __( 'Not found in Trash', 'daniel-queue' ),
    );
     
// Set other options for Custom Post Type
     
    $args = array(
        'label'               => __( 'Salons', 'daniel-queue' ),
        'description'         => __( 'Salon news and reviews', 'daniel-queue' ),
        'labels'              => $labels,
        // Features this CPT supports in Post Editor
        'supports'            => array( 'title', 'editor', 'excerpt', 'author', 'thumbnail', 'comments', 'revisions', 'custom-fields', ),
        // You can associate this CPT with a taxonomy or custom taxonomy. 
        'taxonomies'          => array( 'genres' ),
        /* A hierarchical CPT is like Pages and can have
        * Parent and child items. A non-hierarchical CPT
        * is like Posts.
        */ 
        'menu_icon'   => 'dashicons-store',
        'hierarchical'        => false,
        'public'              => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'show_in_nav_menus'   => true,
        'show_in_admin_bar'   => true,
        'menu_position'       => 25,
        'can_export'          => true,
        'has_archive'         => true,
        'exclude_from_search' => false,
        'publicly_queryable'  => true,
        'capability_type'     => 'post',
        'show_in_rest' => true,
 
    );
     
    // Registering your Custom Post Type
    register_post_type( 'Salons', $args );
 

function bpem_add_meta_box() {

    $screens = array( 'salons' );
    foreach ( $screens as $screen ) {
    add_meta_box(
        'SalonInformation',
        'Salon Information',
        'sq_show_custom_meta_box',
        $screen,
        'normal',
        'high'

        );
    }

}

add_action( 'add_meta_boxes', 'bpem_add_meta_box' );


function sq_show_custom_meta_box( $post ) {
    echo "<div class='evn_meta'>";
    wp_nonce_field( 'dq_address', 'dq_address_nonce' );
    $dq_address = get_post_meta( $post->ID, 'salon_address', true );
    echo '<label for="dq_address"><span> '; _e( 'Salon Address', 'bp-event-manager' );
    echo '</span><input type="text" id="dq_address" name="dq_address" value="'.esc_attr( $dq_address ) . '" size="100" /> </label>';

    echo "</div>";
}


function dq_save_meta_box_data( $post_id ) {

    if ( ! isset( $_POST['dq_address_nonce'] )) {
        return;
    }
    if (! wp_verify_nonce( $_POST['dq_address_nonce'], 'dq_address' )) {
        return;
    }
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }

    if ( isset( $_POST['post_type'] ) && 'page' == $_POST['post_type'] ) {
        if ( ! current_user_can( 'edit_page', $post_id ) ) {
            return;
        }
    } else {

        if ( ! current_user_can( 'edit_post', $post_id ) ) {
            return;
        }

    }

    if ( ! isset( $_POST['dq_address'] )){
        return;
    }

    $dq_address = sanitize_text_field( $_POST['dq_address'] );
    update_post_meta( $post_id, 'dq_address', $dq_address );


}

add_action( 'save_post', 'dq_save_meta_box_data' );



add_filter( 'manage_salons_posts_columns', 'set_custom_edit_book_columns' );




function set_custom_edit_book_columns($columns) {

    $columns = array(
    'cb' => '&lt;input type="checkbox" />',
    'title' => __( 'Salon Name' ),
    'search' => __( 'Salon Address' ),
    );
    return $columns;

}

add_action( 'manage_salons_posts_custom_column' , 'custom_book_column', 10, 2 );




function custom_book_column( $column, $post_id ) {

    switch ( $column ) {

        case 'search' :
        $dq_salon = get_post_meta( $post_id, 'salon_address', true );
        echo '<p>'.__( $dq_salon).'</p>';
        break;
    }

}