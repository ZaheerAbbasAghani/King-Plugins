<?php



/*

* Creating a function to create our CPT

*/

 

 

// Set UI labels for Custom Post Type

    $labels = array(

        'name'                => _x( 'Events', 'Post Type General Name', 'alialboor-event-manager' ),

        'singular_name'       => _x( 'Event', 'Post Type Singular Name', 'alialboor-event-manager' ),

        'menu_name'           => __( 'Events', 'alialboor-event-manager' ),

        'parent_item_colon'   => __( 'Parent Event', 'alialboor-event-manager' ),

        'all_items'           => __( 'All Events', 'alialboor-event-manager' ),

        'view_item'           => __( 'View Event', 'alialboor-event-manager' ),

        'add_new_item'        => __( 'Add New Event', 'alialboor-event-manager' ),

        'add_new'             => __( 'Add New', 'alialboor-event-manager' ),

        'edit_item'           => __( 'Edit Event', 'alialboor-event-manager' ),

        'update_item'         => __( 'Update Event', 'alialboor-event-manager' ),

        'search_items'        => __( 'Search Event', 'alialboor-event-manager' ),

        'not_found'           => __( 'Not Found', 'alialboor-event-manager' ),

        'not_found_in_trash'  => __( 'Not found in Trash', 'alialboor-event-manager' ),

    );

     

// Set other options for Custom Post Type

     

    $args = array(

        'label'               => __( 'Events', 'alialboor-event-manager' ),

        'description'         => __( 'Event news and reviews', 'alialboor-event-manager' ),

        'labels'              => $labels,

        // Features this CPT supports in Post Editor

        'supports'            => array( 'title', 'editor', 'excerpt', 'author', 'thumbnail', 'comments', 'revisions'),

        // You can associate this CPT with a taxonomy or custom taxonomy. 

        'taxonomies'          => array( 'genres' ),

        /* A hierarchical CPT is like Pages and can have

        * Parent and child items. A non-hierarchical CPT

        * is like Posts.

        */ 

        'menu_icon'   => 'dashicons-location-alt',

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

    register_post_type( 'events', $args );

 



function bpem_add_meta_box() {



    $screens = array( 'events' );

    foreach ( $screens as $screen ) {

    add_meta_box(

        'EventInformation',

        'Event Information',

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

    wp_nonce_field( 'aem_address', 'aem_address_nonce' );

    wp_nonce_field( 'aem_event_start_date', 'aem_event_start_date_nonce' );

	wp_nonce_field( 'aem_start_end_date', 'aem_start_end_date_nonce' );

	



    $aem_address = get_post_meta( $post->ID, 'aem_address', true );

    echo '<label for="aem_address"><span> '; _e( 'Event Address', 'alialboor-event-manager' );

    echo '</span><input type="text" id="aem_address" name="aem_address" value="'.esc_attr( $aem_address ) . '" size="99" style="margin-bottom: 10px;margin-left: 6px;border: 1px solid #ddd;"/> </label>';



    echo "<div class='aem_start_end_date'> ";

	    $aem_event_start_date = get_post_meta( $post->ID, 'aem_event_start_date', true );

	    echo '<label for="aem_event_start_date"><span> '; _e( 'Event Start Date', 'alialboor-event-manager' );

	    echo '</span><input type="date" id="aem_event_start_date" name="aem_event_start_date" value="'.esc_attr( $aem_event_start_date ) . '" size="99" style="margin-bottom: 10px;margin-left: 6px;border: 1px solid #ddd;"/> </label>';

	echo "</div>";



	echo "<div class='aem_start_end_date'> ";

	    $aem_event_end_date = get_post_meta( $post->ID, 'aem_event_end_date', true );

	    echo '<label for="aem_event_end_date"><span> '; _e( 'Event End Date', 'alialboor-event-manager' );

	    echo '</span><input type="date" id="aem_event_end_date" name="aem_event_end_date" value="'.esc_attr( $aem_event_end_date ) . '" size="99" style="margin-bottom: 10px;margin-left: 6px;border: 1px solid #ddd;"/> </label>';

	echo "</div>";











	echo "</div>";

}





function aem_save_meta_box_data( $post_id ) {



    if ( ! isset( $_POST['aem_address_nonce'] ) && ! isset( $_POST['aem_event_start_date_nonce'] )	&& ! isset( $_POST['aem_event_end_date_nonce'] ) && ! isset( $_POST['aem_event_start_time_nonce'] ) && ! isset( $_POST['aem_event_start_time_nonce'] ) && !isset( $_POST['aem_event_end_time_nonce'])) {

        return;

    }

    if (! wp_verify_nonce( $_POST['aem_address_nonce'], 'aem_address' ) && 

    	! wp_verify_nonce($_POST['aem_event_start_date_nonce'],'aem_event_start_date') 

    		&& ! wp_verify_nonce( $_POST['aem_event_end_date_nonce'], 'aem_event_end_date') ) 



    {

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



 

    $aem_address = sanitize_text_field( $_POST['aem_address'] );

    update_post_meta( $post_id, 'aem_address', $aem_address );



    $aem_event_start_date = sanitize_text_field( $_POST['aem_event_start_date'] );

    update_post_meta( $post_id, 'aem_event_start_date', $aem_event_start_date );



    $aem_event_end_date = sanitize_text_field( $_POST['aem_event_end_date'] );

    update_post_meta( $post_id, 'aem_event_end_date', $aem_event_end_date );



 





}



add_action( 'save_post', 'aem_save_meta_box_data' );







add_filter( 'manage_events_posts_columns', 'set_custom_edit_book_columns' );









function set_custom_edit_book_columns($columns) {



    $columns = array(

    'cb' => '&lt;input type="checkbox" />',

    'title' => __( 'Event Name' ),

    'search' => __( 'Event Address' ),

    );

    return $columns;



}



add_action( 'manage_events_posts_custom_column' , 'custom_book_column', 10, 2 );









function custom_book_column( $column, $post_id ) {



    switch ( $column ) {



        case 'search' :

        $dq_salon = get_post_meta( $post_id, 'aem_address', true );

        echo '<p>'.__( $dq_salon).'</p>';

        break;

    }



}







function aem_add_meta_box_history() {



    $screens = array( 'events' );

    foreach ( $screens as $screen ) {

    add_meta_box(

        'EventReminders',

        'Event Reminders History',

        'aem_show_custom_meta_box',

        $screen,

        'normal',

        'high'



        );

    }



}



add_action( 'add_meta_boxes', 'aem_add_meta_box_history' );



function aem_show_custom_meta_box($post){







    $email_address = get_post_meta( $post->ID,'aem_email_address', false);

    $aem_calendar = get_post_meta( $post->ID,'aem_date_calendar', false);
    $aem_message_status = get_post_meta( $post->ID,'aem_message_status', false);



    
    //echo date("Y-m-d");
    echo "<table style='width:100%;' border='1'>";

    echo "<tr><th style='text-align:left;padding:10px;'>Email Address</th><th style='text-align:left;padding:10px;'> Reminder Date </th><th style='text-align:left;padding:10px;'> Action </th></tr>";

    $i=0;

    foreach ($email_address as $address) {

        echo "<tr class='".$i."'>";

            echo "<td style='padding:10px;' class='aem_email'>".$address."</td><td style='padding:10px;' class='aem_calendar'>".$aem_calendar[$i]."</td><td style='padding:10px;'><a href='#' class='aem_delete' post-id='".$post->ID."'> Delete </a></td>";

        echo "</tr>";

        $i++;

    }

    

    echo "</table>";

    

}