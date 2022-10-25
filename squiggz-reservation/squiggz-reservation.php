<?php
/*
Plugin Name: Squiggz Reservation
Plugin URI: https://www.fiverr.com/zaheerabbasagha
Description:  A plugin to implement a table reservation system for customers.
Version: 1.0
Author: Zaheer Abbas Aghani
Author URI: https://profiles.wordpress.org/zaheer01/
License: GPLv3 or later
Text Domain: squizz-reservation
Domain Path: /languages
*/

defined("ABSPATH") or die("No direct access!");
class SquiggzReservation {


function __construct() {
	
	//add_action( 'admin_init', array($this,'wtc_if_woocommerce_not_active' ));	
	add_action('init', array($this, 'sqr_start_from_here'));
	add_action( "wp_enqueue_scripts", array($this, "sqr_files_to_enqueue"));
	add_action( 'admin_enqueue_scripts', array($this,'sqr_add_color_picker') );	
	register_activation_hook( __FILE__, array($this, 'sqr_create_squiggz_table') );

	add_action( 'init', array($this, 'sqr_add_reservation_support_endpoint') );
	add_filter( 'query_vars', array($this,  'sqr_reservation_support_query_vars'), 0 );
	add_filter( 'woocommerce_account_menu_items', array($this, 'sqr_add_reservation_support_link_my_account'));
	add_action( 'woocommerce_account_sqr-reservation_endpoint', array($this, 'sqr_reservation_support_content'));
	add_filter( 'woocommerce_login_redirect',  array($this, 'sqr_custom_user_redirect'), 10, 2 ); 

}

function sqr_start_from_here() {

	require_once plugin_dir_path(__FILE__) . 'front/sqr_table_display.php';
	require_once plugin_dir_path(__FILE__) . 'front/sqr_fetch_games.php';
	require_once plugin_dir_path(__FILE__) . 'front/sqr_make_reservation.php';
	require_once plugin_dir_path(__FILE__) . 'front/sqr_change_dates.php';
	require_once plugin_dir_path(__FILE__) . 'front/sqr_make_sure_correct_slot.php';
	require_once plugin_dir_path(__FILE__) . 'front/sqr_fetch_reserved_spots.php';
	require_once plugin_dir_path(__FILE__) . 'front/sqr_make_it_reservable.php';
	require_once plugin_dir_path(__FILE__) . 'front/sqr_check_empty_seats.php';
	require_once plugin_dir_path(__FILE__) . 'front/sqr_double_check_date_time.php';
	
	require_once plugin_dir_path(__FILE__) . 'back/sqr_permission_needed.php';
	require_once plugin_dir_path(__FILE__) . 'back/sqr_edit_reserve_information.php';
	require_once plugin_dir_path(__FILE__) . 'back/sqr_update_reserve_information.php';
	require_once plugin_dir_path(__FILE__) . 'back/sqr_delete_reserve_information.php';
	require_once plugin_dir_path(__FILE__) . 'back/sqr_get_reserved_seats_table.php';
	require_once plugin_dir_path(__FILE__) . 'back/sqr_floor_plan_maker.php';
	require_once plugin_dir_path(__FILE__) . 'back/sqr_floor_plan_delete.php';
	

}

function sqr_files_to_enqueue(){
	wp_enqueue_script('jquery-ui-core');
	wp_enqueue_script('jquery-ui-tooltip');
	
	wp_enqueue_style('sqr-style-a', 'https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css','1.13.2','all');
	
	wp_enqueue_style('sqr-datetimepicker', 'https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.min.css','2.5.20','all');

	wp_enqueue_style('sqr-style', plugins_url('assets/css/sqr.css', __FILE__),'1.0.0','all');

	wp_enqueue_script('sqr-sweetalert2', 'https://cdn.jsdelivr.net/npm/sweetalert2@11.4.32/dist/sweetalert2.all.min.js',array('jquery'),'11.4.32', true);

	wp_enqueue_script('sqr-datetimepicker', 'https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.min.js"',array('jquery'),'2.5.20', true);

	wp_enqueue_script('sqr-datetimepickerFull', 'https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.full.min.js',array('jquery'),'2.5.20', true);

	wp_enqueue_script('sqr-moment', 'https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.17.1/moment-with-locales.min.js',array('jquery'),'2.17.1', true);


	wp_enqueue_style('sqr-dataTables', 'https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css','1.12.1','all');
    wp_enqueue_script('sqr-dataTables','https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js',array('jquery'),'1.12.1', true);


	wp_enqueue_script('sqr-script', plugins_url('assets/js/sqr.js', __FILE__),array('jquery'),'1.0.0', false);

	$options 	= 	__(get_option( 'reservation_before_time' ));
	$options1 	= 	__(get_option( 'reservation_after_days' ));

	$option  = __(get_option( 'restrict_min_duration' )); 
    $option1 = __(get_option( 'restrict_max_duration' ));

    $before_after_message = str_replace(array('#before', '#after'), array($options['period'], $options1['day']), get_option( 'reservation_before_after_time_message' ));


	wp_localize_script('sqr-script', 'sqr_object',
		array( 
				'ajax_url' => admin_url( 'admin-ajax.php' ), 
				'sqrTime' => $options['period'], 
				'sqrDays' => $options1['day'], 
				'sqrMinDuration' => $option['time'], 
				'sqrMaxDuration' => $option1['time'], 
				"login_redirect" => get_the_permalink( get_option( 'after_login_redirect')['page_id'] ),
				'todayDate' => date( 'Y-m-d', current_time( 'timestamp', 0 ) ),
				'todayTime' => date( 'H:i', current_time( 'timestamp', 0 ) ),	
				'make_reservation' => __(get_option( 'make_reservation' )),
				'reservation_form_title' => __(get_option( 'reservation_form_title' )),
				'reservation_start_time_label' => __(get_option( 'reservation_start_time_label' )),
				'reservation_end_time_label' 		=> __(get_option( 'reservation_end_time_label' )),
				'reservation_choose_game_label' 	=> __(get_option( 'reservation_choose_game_label' )),
				'reservation_submit_button_text' 	=> __(get_option( 'reservation_submit_button_text' )),
				'reservation_before_after_time_message' 	=> __($before_after_message),
				'loginMessage' => __(esc_attr( get_option('reservation_login_message') )),
				'AlreadyBlocked' => __(esc_attr( get_option('reservation_blocked_already') )),
				
			)
		);

}


function sqr_add_color_picker( $hook ) {
 
    if( is_admin() ) { 
        // Add the color picker css file       
		wp_enqueue_script('jquery-ui-core');
		wp_enqueue_script('jquery-ui-tooltip');

		wp_enqueue_style('sqr-style-a', 'https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css','1.13.2','all');

		wp_enqueue_style('sqr-datetimepicker', 'https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.min.css','2.5.20','all');

        wp_enqueue_style('sqr-dataTables', 'https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css','1.12.1','all');
        wp_enqueue_script('sqr-dataTables','https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js',array('jquery'),'1.12.1', true);

        wp_enqueue_script('sqr-sweetalert2', 'https://cdn.jsdelivr.net/npm/sweetalert2@11.4.32/dist/sweetalert2.all.min.js',array('jquery'),'11.4.32', true);

        wp_enqueue_style('sqr-style', plugins_url('assets/css/sqr_admin.css', __FILE__),'1.0.0','all');

        wp_enqueue_script('sqr-datetimepicker', 'https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.min.js"',array('jquery'),'2.5.20', true);

		wp_enqueue_script('sqr-datetimepickerFull', 'https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.full.min.js',array('jquery'),'2.5.20', true);

		wp_enqueue_script('sqr-moment', 'https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.17.1/moment-with-locales.min.js',array('jquery'),'2.17.1', true);


        // Include our custom jQuery file with WordPress Color Picker dependency
        wp_enqueue_script( 'custom-script-handle', plugins_url( 'assets/js/sqr-admin.js', __FILE__ ), array( 'wp-color-picker' ), false, true ); 

        // in JavaScript, object properties are accessed as ajax_object.ajax_url, ajax_object.we_value
		wp_localize_script( 'custom-script-handle', 'sqr_ajax_object',
			array( 
				'ajax_url' => admin_url( 'admin-ajax.php' ), 
				'sqrTime' => $options['period'], 
				'sqrDays' => $options1['day'], 
				'sqrMinDuration' => $option['time'], 
				'sqrMaxDuration' => $option1['time'], 
				"login_redirect" => get_the_permalink( get_option( 'after_login_redirect')['page_id'] ),
				'todayDate' => date( 'Y-m-d', current_time( 'timestamp', 0 ) ),
				'todayTime' => date( 'H:i', current_time( 'timestamp', 0 ) ),	
				'todayTime2' => strtotime(date( 'H:i', current_time( 'timestamp', 0 ) )),	
				'make_reservation' => __(get_option( 'make_reservation' )),
				'reservation_form_title' => __(get_option( 'reservation_form_title' )),
				'reservation_start_time_label' => __(get_option( 'reservation_start_time_label' )),
				'reservation_end_time_label' 		=> __(get_option( 'reservation_end_time_label' )),
				'reservation_choose_game_label' 	=> __(get_option( 'reservation_choose_game_label' )),
				'reservation_submit_button_text' 	=> __(get_option( 'reservation_submit_button_text' )),
				'reservation_before_after_time_message' 	=> __($before_after_message),
				'loginMessage' => __(esc_attr( get_option('reservation_login_message') )),
				'AlreadyBlocked' => __(esc_attr( get_option('reservation_blocked_already') )),
				
			)
		);

    }
}

function sqr_create_squiggz_table() {

	global $wpdb;
	$charset_collate = $wpdb->get_charset_collate();
	$table_name = $wpdb->prefix . 'sqr_squizz_reservation';
	$table_name1 = $wpdb->prefix . 'sqr_squizz_tables';

	$sql = "CREATE TABLE $table_name (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		start_date varchar(255) NOT NULL,
		start_time varchar(255) NOT NULL,
		end_time varchar(255) NOT NULL,
		choose_game varchar(255) NOT NULL,
		spot_reserve varchar(255) NOT NULL,
		spot_selected varchar(255) NOT NULL,
		color varchar(255) NOT NULL,
		status int(1) NOT NULL,
		floor_id varchar(255) NOT NULL,
		user_id int(50) NOT NULL,
		created_at datetime NOT NULL,
		PRIMARY KEY (id)
	) $charset_collate;";

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );


	$sql1 = "CREATE TABLE $table_name1 (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		choosen_date varchar(255) NOT NULL,
		start_time varchar(255) NOT NULL,
		end_time varchar(255) NOT NULL,
		spots text NOT NULL,
		created_at datetime NOT NULL,
		PRIMARY KEY (id)
	) $charset_collate;";

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql1 );

}


function sqr_add_reservation_support_endpoint() {
    add_rewrite_endpoint( 'sqr-reservation', EP_ROOT | EP_PAGES );
}
  
// ------------------
// 2. Add new query var
  
function sqr_reservation_support_query_vars( $vars ) {
    $vars[] = 'sqr-reservation';
    return $vars;
}
  
// ------------------
// 3. Insert the new endpoint into the My Account menu
  
function sqr_add_reservation_support_link_my_account( $items ) {
    $items['sqr-reservation'] = __('Reservation');
    return $items;
}
  
// ------------------
// 4. Add content to the new tab
  
function sqr_reservation_support_content() { ?>

<h3>Reservation</h3>
<table id="table_id" class="display">
    <thead>
        <tr>
        	<th>Game</th>
            <th>Start Date</th>
            <th>Start Time</th>
            <th>End Time</th>
            <th>Reserved Spots</th>
        </tr>
    </thead>
    <tbody>

    <?php 

    global $wpdb;
    $table_name = $wpdb->prefix . 'sqr_squizz_reservation';
    $id = get_current_user_id();
    $results = $wpdb->get_results ("SELECT * FROM $table_name WHERE user_id='$id'");
    $i=0;
    foreach ( $results as $result ){ ?>
        <tr data-id="<?php echo $result->id; ?>" class='row_<?php echo $i; ?>'>

        	<td><?php echo $result->choose_game; ?></td>
            <td><?php echo $result->start_date; ?></td>
            <td><?php echo $result->start_time; ?></td>
            <td><?php echo $result->end_time; ?></td>
            <td><?php echo $result->spot_selected; ?></td>

        </tr>

    <?php 
    $i++;	
	} ?>
      
    </tbody>
    <tfoot>
        <tr>
        	<th>Game</th>
            <th>Start Date</th>
            <th>Start Time</th>
            <th>End Time</th>
            <th>Reserved Spots</th>
        </tr>
    </tfoot>
</table>


<?php 
  
}
  
// After login redirect
function sqr_custom_user_redirect( $redirect, $user ) {
  // Get the first of all the roles assigned to the user
  $role = $user->roles[0];

  $dashboard = admin_url();
  $myaccount = get_permalink( wc_get_page_id( 'shop' ) );
  if( $role == 'administrator' ) {
    //Redirect administrators to the dashboard
    $redirect = $dashboard;
  } elseif ( $role == 'shop-manager' ) {
    //Redirect shop managers to the dashboard
    $redirect = $dashboard;
  } elseif ( $role == 'editor' ) {
    //Redirect editors to the dashboard
    $redirect = $dashboard;
  } elseif ( $role == 'author' ) {
    //Redirect authors to the dashboard
    $redirect = $dashboard;
  } elseif ( $role == 'customer' || $role == 'subscriber' ) {
    //Redirect customers and subscribers to the "My Account" page
    $redirect = get_the_permalink(get_option( 'after_login_redirect')['page_id']);
  } else {
    //Redirect any other role to the previous visited page or, if not available, to the home
    $redirect = wp_get_referer() ? wp_get_referer() : home_url();
  }
  return $redirect;
}




// Note: add_action must follow 'woocommerce_account_{your-endpoint-slug}_endpoint' format


} // class ends

// CHECK WETHER CLASS EXISTS OR NOT.

if (class_exists('SquiggzReservation')) {
	$obj = new SquiggzReservation();
	require_once plugin_dir_path(__FILE__) . 'back/sqr_floors_post_type.php';
}