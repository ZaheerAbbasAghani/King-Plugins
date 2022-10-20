<?php 
/*
    * Creating a function to create our CPT
*/
  
function sqr_custom_post_type() {
  
// Set UI labels for Custom Post Type
    $labels = array(
        'name'                => _x( 'Floors', 'Post Type General Name', 'squizz-reservation' ),
        'singular_name'       => _x( 'Floor', 'Post Type Singular Name', 'squizz-reservation' ),
        'menu_name'           => __( 'Floors', 'squizz-reservation' ),
        'parent_item_colon'   => __( 'Parent Floor', 'squizz-reservation' ),
        'all_items'           => __( 'All Floors', 'squizz-reservation' ),
        'view_item'           => __( 'View Floor', 'squizz-reservation' ),
        'add_new_item'        => __( 'Add New Floor', 'squizz-reservation' ),
        'add_new'             => __( 'Add New', 'squizz-reservation' ),
        'edit_item'           => __( 'Edit Floor', 'squizz-reservation' ),
        'update_item'         => __( 'Update Floor', 'squizz-reservation' ),
        'search_items'        => __( 'Search Floor', 'squizz-reservation' ),
        'not_found'           => __( 'Not Found', 'squizz-reservation' ),
        'not_found_in_trash'  => __( 'Not found in Trash', 'squizz-reservation' ),
    );
      
// Set other options for Custom Post Type
      
    $args = array(
        'label'               => __( 'Floors', 'squizz-reservation' ),
        'description'         => __( 'Floor news and reviews', 'squizz-reservation' ),
        'labels'              => $labels,
        // Features this CPT supports in Post Editor
        'supports'            => array( 'title'),
        // You can associate this CPT with a taxonomy or custom taxonomy. 
        //'taxonomies'          => array( 'genres' ),
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
        'menu_icon'   => 'dashicons-editor-kitchensink',
  
    );
      
    // Registering your Custom Post Type
    register_post_type( 'floors', $args );
  
}
  
/* Hook into the 'init' action so that the function
* Containing our post type registration is not 
* unnecessarily executed. 
*/
  
add_action( 'init', 'sqr_custom_post_type', 0 );


function op_register_menu_meta_box() {
    add_meta_box(
        'sqr-shortcode',
        esc_html__( 'Floor Details', 'squizz-reservation'),
        'sqr_shortcode_panel',
        'Floors',
        'normal',
        'core'
    );
}
add_action( 'add_meta_boxes', 'op_register_menu_meta_box' );
 
function sqr_shortcode_panel($post) {

    echo "<style> 
    
    .gameBlock{
        border: 1px solid #ddd;
        padding: 15px;
        box-shadow: 1px 1px 1px #ddd;
        margin-bottom: 20px;
    }

    </style>";

    // Metabox content
    wp_nonce_field( basename( __FILE__ ), 'sqr_meta_box_nonce' ); 
    $sqr_game_name = get_post_meta( $post->ID, 'sqr_game_name', true);
    $seats_to_fill = get_post_meta( $post->ID, 'seats_to_fill', true);
    $sqr_game_color = get_post_meta( $post->ID, 'sqr_game_color', true);

    $i=0;
    foreach (unserialize($sqr_game_name) as  $value) {

        echo "<div class='gameBlock'>";
            echo "<label> Game Name: <input type='text' name='sqr_game_name[]' value='".$value."' placeholder='Enter total seats available' style='width: 100%;margin-top: 6px;padding: 3px 10px;'/></label><br><br>";
            echo "<label> Seats to fill: <input type='text' name='seats_to_fill[]' value='".unserialize($seats_to_fill)[$i]."' placeholder='Enter seats to full when user make reservation' style='width: 100%;margin-top: 6px;padding: 3px 10px;'/></label><br><br>";
            echo "<label> Game Color: </label><br><br> <input type='text' name='sqr_game_color[]' class='sqr_game_color' value='".unserialize($sqr_game_color)[$i]."'/><br><br><button id='removeRow' type='button' class='button'>Remove</button>";
        echo "</div>";
        $i++;
    }

     echo '<div id="newRow"></div> 
        <button id="addRow" type="button" class="button button-primary">Add Game</button> <br><br>';

 
    echo 'Table Shortcode: [table table_id='.$post->ID.']';


}

function sqr_Floors_save_post($post_id)
{
   // check for nonce to top xss
    if ( !isset( $_POST['sqr_meta_box_nonce'] ) || !wp_verify_nonce( $_POST['sqr_meta_box_nonce'], basename( __FILE__ ) ) ){
        return;
    }

    // check for correct user capabilities - stop internal xss from customers
    if ( ! current_user_can( 'edit_post', $post_id ) ){
        return;
    }


    // update fields
    if ( isset( $_REQUEST['sqr_game_name'] ) ) {
        $name = array();
        foreach ($_POST['sqr_game_name'] as $game_name) {
            array_push($name, $game_name);
        }
        update_post_meta( $post_id, 'sqr_game_name', sanitize_text_field( serialize($name) ) );
    }

    if ( isset( $_REQUEST['seats_to_fill'] ) ) {
        $seats = array();
        foreach ($_POST['seats_to_fill'] as $seatsfill) {
            array_push($seats, $seatsfill);
        }
        update_post_meta( $post_id, 'seats_to_fill', sanitize_text_field( serialize($seats) ) );
    }

    if ( isset( $_REQUEST['sqr_game_color'] ) ) {
        $color = array();
        foreach ($_POST['sqr_game_color'] as $game_color) {
            array_push($color, $game_color);
        }
        update_post_meta( $post_id, 'sqr_game_color', sanitize_text_field( serialize($color) ) );
    }

}   

add_action( 'save_post_floors', 'sqr_Floors_save_post' );  

/*
    Page Reservation Wordpress
*/

// create custom plugin settings menu
add_action('admin_menu', 'squizz_table_plugin_create_menu_settings_');

function squizz_table_plugin_create_menu_settings_() {

    //create new top-level menu
     add_submenu_page(
        'edit.php?post_type=floors',
        __( 'Reservations', 'squizz-reservation' ),
        __( 'Reservations', 'squizz-reservation' ),
        'manage_options',
        'table-reservation',
        'squizz_table_reservation'
    );

    //call register settings function
    add_action( 'admin_init', 'squizz_table_plugin_settings' );
}


function squizz_table_plugin_settings() {
    //register our settings
/*    register_setting( 'squizz-plugin-settings-group', 'auto_approve_permission' );
    register_setting( 'squizz-plugin-settings-group', 'reservation_publish_message' );
    register_setting( 'squizz-plugin-settings-group', 'reservation_pending_message' );*/
}

function squizz_table_reservation() {
?>
<style type="text/css">
    .dataTables_length select{
        width: 80px;
    }
</style>
<div class="wrap" style="background:#fff; padding:10px 20px;">
<h1> Squiggz Table Reservation  <a href="#" class="button button-primary makeReserve" style="float:right;"> Reserve a Seat </a> <a href="#" class="button button-default standard_floor_plan" style="float:right;margin-right: 5px;"> Create Standard Floor Plan  </a> </h1><hr>

        <?php  echo do_shortcode( '[table table_id=99129929244]' ); ?>
      

<?php 

//echo get_current_user_id();

?>


<table id="reservationTable" class="display">
    <thead>
        <tr>
            <th>Start Date/Time</th>
            <th>Start Time</th>
            <th>End Time</th>
            <th>User</th>
            <th>Game</th>
            <th>Spot Selected</th>
            <?php if(get_option( 'auto_approve_permission' ) == 1){ ?>
                <th>Status</th>
            <?php } ?>
            <th>Created at</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>

    <?php 

    global $wpdb;
    $table_name = $wpdb->prefix . 'sqr_squizz_reservation';
    $results = $wpdb->get_results ("SELECT * FROM $table_name");

    foreach ( $results as $result ){ ?>

        <?php 

        $user = get_userdata($result->user_id);

        ?>

        <tr data-sort="<?php echo strtotime($result->created_at); ?>">
            <td class="startDate"><?php echo $result->start_date; ?></td>
            <td class="endDate"><?php echo $result->start_time; ?></td>
            <td class="endDate"><?php echo $result->end_time; ?></td>
            <td><?php echo $user->display_name; ?></td>
            <td><?php echo $result->choose_game; ?></td>
            <td><?php echo $result->spot_selected; ?></td>
            <td><?php echo $result->created_at; ?></td>
            <?php if(get_option( 'auto_approve_permission' ) == 1){ ?>
            <td><select id="permission_needed">
                <option value="1" <?php echo $result->status == 1 ?  "selected" : ""; ?> data-id="<?php echo $result->id; ?>"> Approve </option>
                <option value="0" <?php echo $result->status == 0 ?  "selected" : ""; ?>  data-id="<?php echo $result->id; ?>"> Pending </option>
            </select></td>
            <?php } ?>
            <td><?php echo "<a href='#' data-id='".$result->id."' class='button button-default deleteit'> Delete </a>"; ?></td>

        </tr>

    <?php } ?>
      
    </tbody>
    <tfoot>
        <tr>
            <th>Start Date/Time</th>
            <th>Start Time</th>
            <th>End Time</th>
            <th>User</th>
            <th>Game</th>
            <th>Spot Selected</th>
            <?php if(get_option( 'auto_approve_permission' ) == 1){ ?>
                <th>Status</th>
            <?php } ?>
            <th>Created at</th>
            <th>Action</th>
        </tr>
    </tfoot>
</table>



</div>
<?php } 


/*
    Settings Page Reservation Wordpress
*/

// create custom plugin settings menu
add_action('admin_menu', 'squizz_table_plugin_create_menu_settings');

function squizz_table_plugin_create_menu_settings() {

    //create new top-level menu
     add_submenu_page(
        'edit.php?post_type=floors',
        __( 'Settings', 'squizz-reservation' ),
        __( 'Settings', 'squizz-reservation' ),
        'manage_options',
        'squizz-settings',
        'squizz_tables_settings'
    );

    //call register settings function
    add_action( 'admin_init', 'squizz_table_plugin_settings_page' );
}


function squizz_table_plugin_settings_page() {
    //register our settings
    register_setting( 'squizz-plugin-settings-group', 'auto_approve_permission' );
    register_setting( 'squizz-plugin-settings-group', 'reservation_publish_message' );
    register_setting( 'squizz-plugin-settings-group', 'reservation_pending_message' );
    register_setting( 'squizz-plugin-settings-group', 'after_login_redirect' );
    register_setting( 'squizz-plugin-settings-group', 'reservation_before_time' );
    register_setting( 'squizz-plugin-settings-group', 'reservation_after_days' );
    
    register_setting( 'squizz-plugin-settings-group', 'restrict_min_duration' );
    register_setting( 'squizz-plugin-settings-group', 'restrict_max_duration' );

    register_setting( 'squizz-plugin-settings-group', 'make_reservation' );   
    register_setting( 'squizz-plugin-settings-group', 'reservation_form_title' );    
    register_setting( 'squizz-plugin-settings-group', 'reservation_start_time_label' );
    register_setting( 'squizz-plugin-settings-group', 'reservation_end_time_label' );
    register_setting( 'squizz-plugin-settings-group', 'reservation_choose_game_label' );
    register_setting( 'squizz-plugin-settings-group', 'reservation_submit_button_text' );  

    register_setting( 'squizz-plugin-settings-group', 'reservation_before_after_time_message' );  
    register_setting( 'squizz-plugin-settings-group', 'reservation_login_message' );  

    register_setting( 'squizz-plugin-settings-group', 'reservation_blocked_already');  

}

function squizz_tables_settings() {
?>
<style type="text/css">
    .dataTables_length select{
        width: 80px;
    }
</style>
<div class="wrap" style="background:#fff; padding:10px 20px;">
<h1> Squiggz Table Reservation - Settings </h1><hr>
<?php settings_errors(); ?>
<form method="post" action="options.php">
    <?php settings_fields( 'squizz-plugin-settings-group' ); ?>
    <?php do_settings_sections( 'squizz-plugin-settings-group' ); ?>
    <table class="form-table">
        <tr valign="top">
        <th scope="row"> Reservation Approval <br><span style="color:#777;"> Enable checkbox to  approve listing before publish </span> </th>
        <td> <input type="checkbox" name="auto_approve_permission" value="1" <?php checked(1, get_option('auto_approve_permission'), true); ?> /></td>
        </tr>
        
        <tr valign="top">
        <th scope="row">Reservation Publish Message</th>
        <td><input type="text" name="reservation_publish_message" value="<?php echo esc_attr( get_option('reservation_publish_message') ); ?>" style="width:100%;"/></td>
        </tr>
        
        <tr valign="top">
        <th scope="row">Reservation Pending Message</th>
        <td><input type="text" name="reservation_pending_message" value="<?php echo esc_attr( get_option('reservation_pending_message') ); ?>" style="width:100%;"/></td>
        </tr>
        <?php  $options = get_option( 'after_login_redirect' ); ?>
        <tr valign="top"><th scope="row">Choose a page <br><span style="color:#777;"> Choose a login page to redirect before reservation </span></th>
            <td>
                <select name="after_login_redirect[page_id]" style="width:100%;">
                    <?php
                    if( $pages = get_pages() ){
                        foreach( $pages as $page ){
                            echo '<option value="' . $page->ID . '" ' . selected( $page->ID, $options['page_id'] ) . '>' . $page->post_title . '</option>';
                        }
                    }
                    ?>
                </select>
            </td>
        </tr>

        <?php  
      
            $options = get_option( 'reservation_before_time' ); 
            $options1 = get_option( 'reservation_after_days' );

        ?>
        <tr valign="top">
            <th scope="row">Reservation can be done before </th>
            <td>
                <select name="reservation_before_time[period]" style="width:100%;">
                    <option value="">Any Time</option>
                    <?php

                    $periods = array( "1" => "1 day","2" => "2 days", "3" => "3 days", "4" => "4 days", "5" => "5 days", "6" => "6 days", "7" => "7 days", "8" => "8 days", "9" => "9 days", "10" => "10 days", "11" => "11 days", "12" => "12 days", "13" => "13 days", "14" => "14 days", "15" => "15 days", "16" => "16 days", "17" => "17 days", "18" => "18 days", "19" => "19 days", "20" => "20 days", "21" => "21 days", "22" => "22 days", "23" => "23 days", "24" => "24 days", "25" => "25 days", "26" => "26 days", "27" => "27 days", "28" => "28 days", "29" => "29 days", "30" => "30 days");

                    if( $periods ){
                        foreach( $periods as $key => $period ){
                            echo '<option value="' . $key . '" ' . selected( $key, $options['period'] ) . '>' . $period . '</option>';
                        }
                    }
                    ?>
                </select>
         
                <label style="width:100%;margin-left: 10px;"> & after 
                <select name="reservation_after_days[day]" style="width:100%;margin-left: 10px;">
                    <option value="">Any Day</option>
                    <?php

                    $days = array( "1" => "1 day","2" => "2 days", "3" => "3 days", "4" => "4 days", "5" => "5 days", "6" => "6 days", "7" => "7 days", "8" => "8 days", "9" => "9 days", "10" => "10 days", "11" => "11 days", "12" => "12 days", "13" => "13 days", "14" => "14 days", "15" => "15 days", "16" => "16 days", "17" => "17 days", "18" => "18 days", "19" => "19 days", "20" => "20 days", "21" => "21 days", "22" => "22 days", "23" => "23 days", "24" => "24 days", "25" => "25 days", "26" => "26 days", "27" => "27 days", "28" => "28 days", "29" => "29 days", "30" => "30 days");

                    if( $days ){
                        foreach( $days as $key => $day){
                            echo '<option value="' . $key . '" ' . selected( $key, $options1['day'] ) . '>' . $day . '</option>';
                        }
                    }
                    ?>
                </select></label>
            </td>
        </tr>

        <tr valign="top">
        <th scope="row"> Make Reservation Button Text </th>
        <td><input type="text" name="make_reservation" value="<?php echo esc_attr( get_option('make_reservation') ); ?>" style="width:100%;"/></td>
        </tr>

        <tr valign="top">
        <th scope="row"> Reservation Form Title </th>
        <td><input type="text" name="reservation_form_title" value="<?php echo esc_attr( get_option('reservation_form_title') ); ?>" style="width:100%;"/></td>
        </tr>

        <tr valign="top">
        <th scope="row"> Reservation Start Time Label </th>
        <td><input type="text" name="reservation_start_time_label" value="<?php echo esc_attr( get_option('reservation_start_time_label') ); ?>" style="width:100%;"/></td>
        </tr>

        <tr valign="top">
        <th scope="row"> Reservation End Time Label </th>
        <td><input type="text" name="reservation_end_time_label" value="<?php echo esc_attr( get_option('reservation_end_time_label') ); ?>" style="width:100%;"/></td>
        </tr>

         <tr valign="top">
        <th scope="row"> Reservation End Time Label </th>
        <td><input type="text" name="reservation_end_time_label" value="<?php echo esc_attr( get_option('reservation_end_time_label') ); ?>" style="width:100%;"/></td>
        </tr>

        <tr valign="top">
        <th scope="row"> Reservation Submit Button Text </th>
        <td><input type="text" name="reservation_submit_button_text" value="<?php echo esc_attr( get_option('reservation_submit_button_text') ); ?>" style="width:100%;"/></td>
        </tr>

        <tr valign="top">
        <th scope="row"> Reservation Before After Time </th>
        <td><input type="text" name="reservation_before_after_time_message" value="<?php echo esc_attr( get_option('reservation_before_after_time_message') ); ?>" style="width:100%;"/>
            <span>use #before to show before time in message, and use #after to show after time in message </span>
        </td>
        </tr>

        <tr valign="top">
        <th scope="row"> Login Message </th>
        <td><input type="text" name="reservation_login_message" value="<?php echo esc_attr( get_option('reservation_login_message') ); ?>" style="width:100%;"/>
        </td>
        </tr>

        <tr valign="top">
        <th scope="row"> Seat Block Message </th>
        <td><input type="text" name="reservation_blocked_already" value="<?php echo esc_attr( get_option('reservation_blocked_already') ); ?>" style="width:100%;"/>
        </td>
        </tr>




        <?php  
  /*

        register_setting( 'squizz-plugin-settings-group', 'reservation_form_title' );    
        register_setting( 'squizz-plugin-settings-group', 'reservation_start_time_label' );
        register_setting( 'squizz-plugin-settings-group', 'reservation_end_time_label' );
        register_setting( 'squizz-plugin-settings-group', 'reservation_choose_game_label' );
        register_setting( 'squizz-plugin-settings-group', 'reservation_submit_button_text' );  

            $option  = get_option( 'restrict_min_duration' ); 
            $option1 = get_option( 'restrict_max_duration' );

        ?>
        <tr valign="top">
            <th scope="row"> Minimum Duration </th>
            <td>
                <select name="restrict_min_duration[time]" style="width:100%;">
                    <option value="">Select Min Duration</option>
                    <?php

                    $minimum = array( "15" => "15 minutes", "30" => "30 minutes", "45" => "45 minutes");

                    if( $minimum ){
                        foreach( $minimum as $key => $min ){
                            echo '<option value="' . $key . '" ' . selected( $key, $option['time'] ) . '>' . $min . '</option>';
                        }
                    }
                    ?>
                </select>
         
                <label style="width:100%;margin-left: 10px;"> Maximum Duration
                <select name="restrict_max_duration[time]" style="width:100%;margin-left: 10px;">
                    <option value=""> Select Max Duration</option>
                    <?php

                     $maximum = array( "1" => "1 hour", "2" => "2 hours", "3" => "3 hours", "4" => "4 hours", "5" => "5 hours", "6" => "6 hours", "7" => "7 hours", "8" => "8 hours");

                    if( $days ){
                        foreach( $maximum as $key => $max){
                            echo '<option value="' . $key . '" ' . selected( $key, $option1['time'] ) . '>' . $max . '</option>';
                        }
                    }
                    ?>
                </select></label>
            </td>
        </tr>

    */ ?>
        

    </table>
    
    <?php submit_button(); ?>

</form>


</div>
<?php } ?>