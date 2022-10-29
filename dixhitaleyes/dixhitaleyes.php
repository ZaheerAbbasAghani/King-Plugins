<?php
/*
Plugin Name: Dixhitaleyes
Plugin URI: www.dixhitaleyes.com
Description: A wordpress plugin to take some data and image from a JSON Api and save them into a custom database inside wordpress.The datas will be some strings and image will be provided from the Api in a URL form. So practically download it into wordpress image gallery
Version: 1.0
Author: Dixhital eyes
Author URI: ipmbullion.com
License: GPLv3 or later
Text Domain: dixit
Domain Path: /languages
*/

defined("ABSPATH") or die("No direct access!");
class Dixhitaleyes {

function __construct() {
	add_action('init', array($this, 'dix_start_from_here'));
	add_action('admin_enqueue_scripts', array($this, 'dix_enqueue_script_backend'));
	add_action('init', array($this, 'dix_create_table'));
	add_action('init', array($this, 'dix_feeds_table'));

	// Scheduling daily
	register_activation_hook( __FILE__, array($this,'my_activation'));
	add_action( 'dix_daily_event', array($this,'dix_do_this_daily'));
	register_deactivation_hook( __FILE__, array($this,'dix_deactivation' ));

	// Schedule 10 min

	register_activation_hook( __FILE__, array($this,'dix_schedule_three_min_cron'));
	add_filter('cron_schedules',array($this,'dix_three_min_cron_schedules'));
	register_deactivation_hook( __FILE__, array($this,'dix_three_min_deactivation' ));
	add_action('dix_three_min_cron', array($this,'dix_three_min_cron'));
	
	
	add_action("wp_enqueue_scripts", array($this, "dix_enqueue_script_front"));
}



function dix_start_from_here() {
	require_once plugin_dir_path(__FILE__) . 'back/dix_options_page.php';
	require_once plugin_dir_path(__FILE__) . 'back/dix_manually_import.php';
	require_once plugin_dir_path(__FILE__) . 'back/dix_edit_product_name.php';
	require_once plugin_dir_path(__FILE__) . 'back/dix_update_product_name.php';
	require_once plugin_dir_path(__FILE__) . 'back/dix_update_category.php';
    require_once plugin_dir_path(__FILE__) . 'back/dix_update_flags.php';
    require_once plugin_dir_path(__FILE__) . 'front/dix_items_slider.php';
	require_once plugin_dir_path(__FILE__) . 'front/dix_fetch_product_prices.php';
    require_once plugin_dir_path(__FILE__) . 'front/dix_search_bar.php';
    require_once plugin_dir_path(__FILE__) . 'front/dix_search_results.php';
    require_once plugin_dir_path(__FILE__) . 'front/dix_news_list.php';
    
    
}


function dix_enqueue_script_front() {


	wp_enqueue_style('dix-owl-carousel', 'https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css','2.3.4','all');

	wp_enqueue_style('dix-front', plugins_url('assets/css/dix_front.css', __FILE__),rand(0,1000),'all');

    wp_enqueue_script('dix-cookie-front', plugins_url('assets/js/jquery.cookie.js', __FILE__),array('jquery'),'1.4.1', true);

	wp_enqueue_script('dix-owl-carousel', 'https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js',array('jquery'),'2.3.4', true);

    wp_enqueue_script('dix-owl-carousel-filters', plugins_url('assets/js/owlcarousel2-filter.min.js', __FILE__),array('jquery'),'1.0', true);

	wp_enqueue_script('dix-script-front', plugins_url('assets/js/dix_front.js', __FILE__),array('jquery'),rand(0,1000), true);


	global $wpdb; 
	$table_name = $wpdb->base_prefix.'dix_feeds_data';

	$query = "SELECT * FROM $table_name";
	$query_results = $wpdb->get_results($query, ARRAY_A);


	$usa = array();
	$myr = array();
	$sgd = array();
	$gbp = array();
	$euro = array();
	foreach ($query_results as $results) {

		array_push($usa, unserialize($results['usa_feeds']) );
		array_push($myr, unserialize($results['myr_feeds']) );
		array_push($sgd, unserialize($results['sgd_feeds']) );
		array_push($gbp, unserialize($results['gbp_feeds']) );
		array_push($euro, unserialize($results['euro_feeds']) );

	}

    $cpage = get_option('dix_search_result_page');
	wp_localize_script( 'dix-script-front', 'dix_front_ajax_object',
            array( 'ajax_url' => admin_url( 'admin-ajax.php' ), 'usa'=> $usa, 'myr'=> $myr, 'sgd'=> $sgd, 'gbp'=> $gbp, 'euro'=> $euro,"current_page"=>$cpage['page_id'] ) );

}

// Enqueue Style and Scripts

function dix_enqueue_script_backend($hook) {
	//Style & Script

	if($hook != "toplevel_page_dix_dixhitaleyes_settings")
		return false;

	wp_enqueue_style('dix-datatable', 'https://cdn.datatables.net/1.11.4/css/jquery.dataTables.min.css','1.11.4','all');

	wp_enqueue_style('dix-sweetalert2', 'https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.1.9/sweetalert2.min.css','11.1.9','all');

	
	wp_enqueue_style('dix-style', plugins_url('assets/css/dix.css', __FILE__),'1.0.0','all');
	
	wp_enqueue_script('dix-script-dataTables', 'https://cdn.datatables.net/1.11.4/js/jquery.dataTables.min.js',array('jquery'),'1.11.4', true);

	wp_enqueue_script('dix-dataTables.buttons', 'https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js',array('jquery'),'1.11.4', true);
	wp_enqueue_script('dix-script-jszip', 'https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js',array('jquery'),'1.11.4', true);
	wp_enqueue_script('dix-script-pdfmake', 'https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js',array('jquery'),'1.11.4', true);
	wp_enqueue_script('dix-script-vfs_fonts', 'https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js',array('jquery'),'1.11.4', true);

	wp_enqueue_script('dix-script-buttons.html5', 'https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js',array('jquery'),'2.2.2', true);

	wp_enqueue_script('dix-sweetalert', 'https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.1.9/sweetalert2.all.min.js',array('jquery'),'11.1.9', true);
	
	wp_enqueue_script('dix-script', plugins_url('assets/js/dix.js', __FILE__),array('jquery'),rand(0,1000), true);
	
	wp_localize_script( 'dix-script', 'dix_ajax_object',
            array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
}


function my_activation() {
    wp_schedule_event( time(), 'daily', 'dix_daily_event' );

    $wp_upload_dir =  wp_upload_dir();
    $custom_upload_folder = plugin_dir_path(__FILE__)."back/DixImages";
    if(!is_dir($custom_upload_folder)){
        mkdir($custom_upload_folder);
    }


}


function dix_do_this_daily(){

	$url = esc_attr(get_option('dix_import_url'));
	$username = esc_attr(get_option('dix_username'));
	$password = esc_attr(get_option('dix_password'));

	global $wpdb; 
	$table_name = $wpdb->base_prefix.'dix_imported_data';

	if(!empty($url) && !empty($username) && !empty($password)){

		$args = array(
		  'headers' => array(
		    'Authorization' => 'Basic ' . base64_encode( $username . ':' . $password )
		  )
		);
		$request=wp_remote_request( $url, $args );

		if( is_wp_error( $request ) ) {
		    return false; // Bail early
		}

		$body = wp_remote_retrieve_body( $request );
		$data = json_decode( $body );

		foreach ($data  as $key => $value) {
		    $id =  $value->entity_id;
		    $name =  $value->name;
		    $image = $value->image;
	
		 	$query = "SELECT * FROM $table_name WHERE item_id='$id'";
			$query_results = $wpdb->get_results($query);
			if(count($query_results) == 0) {

				if(!empty($id) && !empty($name) && !empty($image)){

                    $rawImage = dirname(__FILE__) . '/DixImages/' . basename($image);
                    $relativePath =  plugin_dir_url( __FILE__ ).'/DixImages/'.basename($image);

                    if($rawImage)
                    {   
                        $contentOrFalseOnFailure = file_get_contents_curl($image);
                        file_put_contents($rawImage, $contentOrFalseOnFailure);
                    }
                    
					$rowResult=$wpdb->insert($table_name, array("item_id" => $id, "item_name" => $name, "item_image_url" =>$relativePath, "item_url" => $url, "item_type" => $item_type,"item_badge" => $item_badge),array("%s","%s","%s","%s","%s","%s"));
				}
			}

		}
	}
}


function dix_deactivation() {
    wp_clear_scheduled_hook( 'dix_daily_event' );
}


function dix_three_min_cron_schedules($schedules){
    if(!isset($schedules["3min"])){
        $schedules["3min"] = array(
            'interval' => 3*60,
            'display' => __('Once every 3 minutes'));
    }
    
    return $schedules;
}

function dix_schedule_three_min_cron(){
    wp_schedule_event(time(), '3min', 'dix_three_min_cron');
}

function dix_three_min_cron(){

 	global $wpdb; 
    $table_name = $wpdb->base_prefix.'dix_feeds_data';

    // USA Feeds
    $url = 'https://www.indigopreciousmetals.com/feeds';
    $username = esc_attr(get_option('dix_username'));
    $password = esc_attr(get_option('dix_password'));

    $args = array(
      'headers' => array(
        'Authorization' => 'Basic ' . base64_encode( $username . ':' . $password )
      )
    );
    $request=wp_remote_request( $url, $args );

    if( is_wp_error( $request ) ) {
        return false; // Bail early
    }

    $body = wp_remote_retrieve_body( $request );
    $data = json_decode( $body );
    $USAinfo = serialize($data->productIds);


    // MYR Feeds
    $urlMyr = 'https://myr.indigopreciousmetals.com/feeds';

    $args = array(
      'headers' => array(
        'Authorization' => 'Basic ' . base64_encode( $username . ':' . $password )
      )
    );
    $request=wp_remote_request($urlMyr, $args );

    if( is_wp_error( $request ) ) {
        return false; // Bail early
    }

    $body = wp_remote_retrieve_body( $request );
    $data = json_decode( $body );
    $MYRinfo = serialize($data->productIds);
  


    // SGD Feeds
    $urlSgd = 'https://sgd.indigopreciousmetals.com/feeds';

    $args = array(
      'headers' => array(
        'Authorization' => 'Basic ' . base64_encode( $username . ':' . $password )
      )
    );
    $request=wp_remote_request($urlSgd, $args );

    if( is_wp_error( $request ) ) {
        return false; // Bail early
    }

    $body = wp_remote_retrieve_body( $request );
    $data = json_decode( $body );
    
    $SGDinfo = serialize($data->productIds);
  


    // GBP Feeds
    $urlGbp = 'https://gbp.indigopreciousmetals.com/feeds';

    $args = array(
      'headers' => array(
        'Authorization' => 'Basic ' . base64_encode( $username . ':' . $password )
      )
    );
    $request=wp_remote_request($urlGbp, $args );

    if( is_wp_error( $request ) ) {
        return false; // Bail early
    }

    $body = wp_remote_retrieve_body( $request );
    $data = json_decode( $body );

    $GBPinfo = serialize($data->productIds);
   
    // EURO Feeds
    $urlEuro = 'https://eur.indigopreciousmetals.com/feeds';

    $args = array(
      'headers' => array(
        'Authorization' => 'Basic ' . base64_encode( $username . ':' . $password )
      )
    );
    $request=wp_remote_request($urlEuro, $args );

    if( is_wp_error( $request ) ) {
        return false; // Bail early
    }

    $body = wp_remote_retrieve_body( $request );
    $data = json_decode( $body );

    $EUROinfo = serialize($data->productIds);
   
    $d = $wpdb->query($wpdb->prepare("UPDATE $table_name SET usa_feeds= '$USAinfo', myr_feeds= '$MYRinfo', sgd_feeds='$SGDinfo', gbp_feeds='$GBPinfo', euro_feeds='$EUROinfo' WHERE id=1 "));

}


function dix_three_min_deactivation() {
    wp_clear_scheduled_hook( 'dix_three_min_cron' );
}



function dix_create_table(){

    global $wpdb;
    $table_name = $wpdb->base_prefix.'dix_imported_data';
    $query = $wpdb->prepare( 'SHOW TABLES LIKE %s', $wpdb->esc_like( $table_name ) );
    if ( ! $wpdb->get_var( $query ) == $table_name ) {

        $charset_collate = $wpdb->get_charset_collate();
        $sql = "CREATE TABLE $table_name (
          id mediumint(255) NOT NULL AUTO_INCREMENT,
          item_id varchar(100) NOT NULL,
          item_name varchar(255) NOT NULL,
          item_image_url varchar(255) NOT NULL,
          item_url varchar(255) NOT NULL,
          item_type varchar(50) NOT NULL,
          item_badge varchar(50) NOT NULL,
          item_price varchar(50) NOT NULL,
          PRIMARY KEY  (id)
        ) $charset_collate;";
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );

    }

  ///  $wpdb->query('DROP TABLE '.$table_name);
  // $wpdb->query('TRUNCATE TABLE '.$table_name);

}

function dix_feeds_table(){

    global $wpdb;
    $table_name = $wpdb->base_prefix.'dix_feeds_data';
    $query = $wpdb->prepare( 'SHOW TABLES LIKE %s', $wpdb->esc_like( $table_name ) );
    if ( ! $wpdb->get_var( $query ) == $table_name ) {

        $charset_collate = $wpdb->get_charset_collate();
        $sql = "CREATE TABLE $table_name (
          id mediumint(255) NOT NULL AUTO_INCREMENT,
          usa_feeds text NOT NULL,
          myr_feeds text NOT NULL,
          sgd_feeds text NOT NULL,
          gbp_feeds text NOT NULL,
          euro_feeds text NOT NULL,
          PRIMARY KEY  (id)
        ) $charset_collate;";
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );

    	$wpdb->insert($table_name, array("usa_feeds" => 'USA',"myr_feeds" => 'MYR',"sgd_feeds" => 'SGD',"gbp_feeds" => 'GBP',"euro_feeds" => 'EURO'),array("%s","%s","%s","%s","%s"));

    }

}



} // class ends

// CHECK WETHER CLASS EXISTS OR NOT.

if (class_exists('Dixhitaleyes')) {
	$obj = new Dixhitaleyes();
}