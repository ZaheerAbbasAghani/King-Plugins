<?php 

/*
* Creating a function to create our CPT
*/
  
function vw_custom_post_type() {
  
// Set UI labels for Custom Post Type
    $labels = array(
        'name'                => _x( 'Vehicles','Post Type General Name', 'vehiclewashing' ),
        'singular_name'       => _x( 'Vehicle','Post Type Singular Name', 'vehiclewashing' ),
        'menu_name'           => __( 'Vehicle Washing', 'vehiclewashing' ),
        'parent_item_colon'   => __( 'Parent Vehicle', 'vehiclewashing' ),
        'all_items'           => __( 'All Vehicles', 'vehiclewashing' ),
        'view_item'           => __( 'View Vehicle', 'vehiclewashing' ),
        'add_new_item'        => __( 'Add New Vehicle', 'vehiclewashing' ),
        'add_new'             => __( 'Add New', 'vehiclewashing' ),
        'edit_item'           => __( 'Edit Vehicle', 'vehiclewashing' ),
        'update_item'         => __( 'Update Vehicle', 'vehiclewashing' ),
        'search_items'        => __( 'Search Vehicle', 'vehiclewashing' ),
        'not_found'           => __( 'Not Found', 'vehiclewashing' ),
        'not_found_in_trash'  => __( 'Not found in Trash', 'vehiclewashing' ),
    );
      
// Set other options for Custom Post Type
      
    $args = array(
        'label'               => __( 'vehicle', 'vehiclewashing' ),
        'description'         => __( 'Vehicle news and reviews', 'vehiclewashing' ),
        'labels'              => $labels,
        // Features this CPT supports in Post Editor
        'supports'            => array( 'title', 'revisions'),
        // You can associate this CPT with a taxonomy or custom taxonomy. 
       // 'taxonomies'          => array( 'types' ),
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
        'menu_position'       => 25,
        'can_export'          => true,
        'has_archive'         => true,
        'exclude_from_search' => false,
        'publicly_queryable'  => true,
        'capability_type'     => 'post',
        'show_in_rest' => true,
        'menu_icon'   => 'dashicons-car',
  
    );
      
    // Registering your Custom Post Type
    register_post_type( 'vehicle', $args );
  
}
  
/* Hook into the 'init' action so that the function
* Containing our post type registration is not 
* unnecessarily executed. 
*/
  
add_action( 'init', 'vw_custom_post_type', 0 );



function global_notice_meta_box() {

	$screens = array( 'vehicle' );
	foreach ( $screens as $screen ) {
        add_meta_box(
            'vehicle-box',
        	__( 'Vehicle Information', 'ehicle-washing' ),
        	'vw_global_notice_meta_box_callback',
            $screen
        );
    }

}

add_action( 'add_meta_boxes', 'global_notice_meta_box' );


function vw_global_notice_meta_box_callback( $post ) {

    // Add a nonce field so we can check for it later.
    wp_nonce_field( 'vw_license_plate_nonce', 'vw_license_plate_nonce' );
    wp_nonce_field( 'vw_year_nonce', 'vw_year_nonce' );
    wp_nonce_field( 'vw_make_nonce', 'vw_make_nonce' );
    wp_nonce_field( 'vw_model_nonce', 'vw_model_nonce' );

    wp_nonce_field( 'vw_price_nonce', 'vw_price_nonce' );

    wp_nonce_field( 'vw_payment_datetime_nonce', 'vw_payment_datetime_nonce' );
    wp_nonce_field( 'vw_parked_datetime_nonce', 'vw_parked_datetime_nonce' );
    wp_nonce_field( 'vw_washed_datetime_nonce', 'vw_washed_datetime_nonce' );

    wp_nonce_field( 'vw_email_nonce', 'vw_email_nonce' );

    $license_plate = get_post_meta( $post->ID, '_vw_license_plate', true );
    $year 	= get_post_meta( $post->ID, '_vw_year', true );
    $make 	= get_post_meta( $post->ID, '_vw_make', true );
    $model 	= get_post_meta( $post->ID, '_vw_model', true );
    $price 	= get_post_meta( $post->ID, '_vw_price', true );

    $payment = get_post_meta( $post->ID, '_payment_date_time', true );
    $parked  = get_post_meta( $post->ID, '_parked_date_time', true );
    $washed  = get_post_meta( $post->ID, '_washed_date_time', true );
    $email 	= get_post_meta( $post->ID, '_vm_email_address', true );

    echo '<label style="display: block;margin-bottom: 10px;"> License Plate: <input type="text" name="license_plate" value="'.$license_plate.'" placeholder="Enter License Plate Here" style="width: 100%;padding: 2px 10px;"></label>';

    echo '<label style="display: block;margin-bottom: 10px;"> Year: <input type="text" name="year" value="'.$year.'" placeholder="Enter Year Here" style="width: 100%;padding: 2px 10px;"></label>';

    echo '<label style="display: block;margin-bottom: 10px;"> Make: <input type="text" name="make" value="'.$make.'" placeholder="Enter Make Here" style="width: 100%;padding: 2px 10px;"></label>';

    echo '<label style="display: block;margin-bottom: 10px;"> Model: <input type="text" name="model" value="'.$model.'" placeholder="Enter Model Here" style="width: 100%;padding: 2px 10px;"></label>';

    echo '<label style="display: block;margin-bottom: 10px;"> Price: <input type="text" name="price" value="'.$price.'" placeholder="Enter Price Here" style="width: 100%;padding: 2px 10px;"></label>';

    echo '<label style="display: block;margin-bottom: 10px;">Payment Date Time: <input type="datetime-local" name="payment_date_time" value="'.$payment.'"  style="width: 100%;padding: 2px 10px;"></label>';

    echo '<label style="display: block;margin-bottom: 10px;">Vehicle Parked Date Time: <input type="datetime-local" name="parked_date_time" value="'.$parked.'" style="width: 100%;padding: 2px 10px;"></label>';

    echo '<label style="display: block;margin-bottom: 10px;">Vehicle Washed Date Time: <input type="datetime-local" name="washed_date_time" value="'.$washed.'" placeholder="Enter Model Here" style="width: 100%;padding: 2px 10px;"></label>';

    echo '<label style="display: block;margin-bottom: 10px;"> Email Address: <input type="email" name="email" value="'.$email.'" placeholder="Enter Email Here" style="width: 100%;padding: 2px 10px;"></label>';

    echo '<label> Before: <br><div class="dropzone clsbox" id="mydropzone"></div> </label>';


      echo '<label> After: <br><div class="dropzone clsbox" id="mydropzone2"></div> </label>';


}


/**
 * When the post is saved, saves our custom data.
 *
 * @param int $post_id
 */
function save_global_notice_meta_box_data( $post_id ) {


    // Check if our nonce is set.
    if ( ! isset( $_POST['vw_license_plate_nonce'] ) && ! isset( $_POST['vw_year_nonce'] ) && ! isset( $_POST['vw_make_nonce'] ) && ! isset( $_POST['vw_model_nonce'] ) && ! isset( $_POST['vw_payment_datetime_nonce'] ) && ! isset( $_POST['vw_parked_datetime_nonce'] ) && ! isset( $_POST['vw_washed_datetime_nonce'] ) && ! isset( $_POST['vw_price_nonce'] ) && ! isset( $_POST['vw_email_nonce'] )  ) {
        return;
    }

    // Verify that the nonce is valid.
    if ( ! wp_verify_nonce( $_POST['vw_license_plate_nonce'], 'vw_license_plate_nonce' )  && ! wp_verify_nonce( $_POST['vw_license_year_nonce'], 'vw_license_year_nonce' ) && ! wp_verify_nonce( $_POST['vw_make_nonce'], 'vw_make_nonce' ) && ! wp_verify_nonce( $_POST['vw_model_nonce'], 'vw_model_nonce' ) && ! wp_verify_nonce( $_POST['vw_payment_datetime_nonce'], 'vw_payment_datetime_nonce' ) && ! wp_verify_nonce( $_POST['vw_parked_datetime_nonce'], 'vw_parked_datetime_nonce' ) && ! wp_verify_nonce( $_POST['vw_washed_datetime_nonce'], 'vw_washed_datetime_nonce' ) && ! wp_verify_nonce( $_POST['vw_washed_datetime_nonce'], 'vw_washed_datetime_nonce' ) && ! wp_verify_nonce( $_POST['vw_price_nonce'], 'vw_price_nonce' ) && ! wp_verify_nonce( $_POST['vw_email_nonce'], 'vw_email_nonce' ) ) {
        return;
    }

    // If this is an autosave, our form has not been submitted, so we don't want to do anything.
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }

    // Check the user's permissions.
    if ( isset( $_POST['post_type'] ) && 'page' == $_POST['post_type'] ) {

        if ( ! current_user_can( 'edit_page', $post_id ) ) {
            return;
        }

    }
    else {

        if ( ! current_user_can( 'edit_post', $post_id ) ) {
            return;
        }
    }

    /* OK, it's safe for us to save the data now. */

    // Make sure that it is set.
    if ( ! isset( $_POST['license_plate'] ) && ! isset($_POST['year']) && ! isset($_POST['make']) && ! isset($_POST['model'] ) && ! isset($_POST['model'] ) && ! isset($_POST['payment_date_time'] )  && ! isset($_POST['parked_date_time'] ) && ! isset($_POST['washed_date_time'] ) && ! isset($_POST['price'] ) && ! isset($_POST['email'] )  ) {
        return;
    }

    // Sanitize user input.
    $license_plate = sanitize_text_field( $_POST['license_plate'] );
    $year = sanitize_text_field( $_POST['year'] );
    $make = sanitize_text_field( $_POST['make'] );
    $model = sanitize_text_field( $_POST['model'] );

    $payment = sanitize_text_field( $_POST['payment_date_time'] );
    $parked = sanitize_text_field( $_POST['parked_date_time'] );
    $washed = sanitize_text_field( $_POST['washed_date_time'] );

    $price = sanitize_text_field( $_POST['price'] );
    $email = sanitize_text_field( $_POST['email'] );


    // Update the meta field in the database.
    update_post_meta( $post_id, '_vw_license_plate', $license_plate );
    update_post_meta( $post_id, '_vw_year', $year );
    update_post_meta( $post_id, '_vw_make', $make );
    update_post_meta( $post_id, '_vw_model', $model );

    update_post_meta( $post_id, '_vw_price', $price );
    update_post_meta( $post_id, '_vw_email_address', $email );

    update_post_meta( $post_id, '_payment_date_time', $payment );
    update_post_meta( $post_id, '_parked_date_time', $parked );
    update_post_meta( $post_id, '_washed_date_time', $washed );
    update_post_meta( $post_id, '_vm_email_address', $email );

}

add_action( 'save_post', 'save_global_notice_meta_box_data' );


// Add the custom columns to the vehicle post type:
add_filter( 'manage_vehicle_posts_columns', 'set_custom_edit_vehicle_columns' );
function set_custom_edit_vehicle_columns($columns) {
    //unset( $columns['title'] );
    unset( $columns['date'] );
    $columns['license_plate'] = __( 'License Plate', 'vehicle-washing' );
    $columns['year'] = __( 'Year', 'vehicle-washing' );
    $columns['make'] = __( 'Make', 'vehicle-washing' );
    $columns['model'] = __( 'Model', 'vehicle-washing' );
    $columns['invoice'] = __( 'Invoice', 'vehicle-washing' );

    return $columns;
}

// Add the data to the custom columns for the vehicle post type:
add_action( 'manage_vehicle_posts_custom_column' , 'custom_vehicle_column', 10, 2 );
function custom_vehicle_column( $column, $post_id ) {
    switch ( $column ) {

        case 'license_plate' :
            echo get_post_meta( $post_id , '_vw_license_plate' , true ); 
        break;

        case 'year' :
            echo get_post_meta( $post_id , '_vw_year' , true ); 
        break;

        case 'make' :
            echo get_post_meta( $post_id , '_vw_make' , true ); 
        break;

        case 'model' :
            echo get_post_meta( $post_id , '_vw_model' , true ); 
        break;

        case 'invoice' :
            echo "<a href='#' class='button button-primary send_invoice' data-id='".$post_id."'> Send Invoice </a>";
        break;

    }
}


add_filter( 'manage_edit-vehicle_columns', 'so_25737839' );

function so_25737839( $columns ) 
{
    $columns['title'] = 'Vin Number';
    return $columns;
}



function cspd_after_post_content($content){
    if (is_singular('vehicle')) {  


        $id = get_the_ID();
        $content .= "<h3> Before <hr></h3>";
        $upload_dir = wp_upload_dir();
        $directory = $upload_dir['basedir'].'/vehicle-before-after/'.$id.'/before';
        $images = glob($directory . "/*.{jpg,png,jpeg,gif,PNG}",GLOB_BRACE);
        $fileList = [];

        $content .= '<ul class="ListImages">';
        $i=1;
        foreach($images as $image)
        {
            $imgName = basename($image);
            $img = get_site_url().'/wp-content/uploads/vehicle-before-after/'.$id.'/before/'.$imgName;
            $content .= "<li> <a href='".$img."' class='big' rel='rel".$i."'> <img src='".$img."' rel='downslider'></a> </li>";
            $i++;
        }

        $content .= "</ul><h3> After <hr></h3>";

        $upload_dir = wp_upload_dir();
        $directory = $upload_dir['basedir'].'/vehicle-before-after/'.$id.'/after';
        $images = glob($directory . "/*.{jpg,png,jpeg,gif,PNG}",GLOB_BRACE);
        $fileList = [];

        $content .= '<ul class="ListImages">';
        foreach($images as $image)
        {
            $imgName = basename($image);
            $img = get_site_url().'/wp-content/uploads/vehicle-before-after/'.$id.'/after/'.$imgName;
            $content .= "<li> <a href='".$img."' class='big' rel='rel".$i."'> <img src='".$img."' rel='downslider'></a> </li>";
            $i++;
        }

        $content .= "</ul>";


    }
    return $content;
}
add_filter( "the_content", "cspd_after_post_content" );