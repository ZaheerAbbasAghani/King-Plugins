<?php
/*
Plugin Name: Podo
Plugin URI: https://www.fiverr.com/zaheerabbasagha
Description: Doctor patient system crm.
Version: 1.0
Author: Zaheer Abbas Aghani
Author URI: https://profiles.wordpress.org/zaheer01/
License: GPLv3 or later
Text Domain: podo
Domain Path: /languages
*/

defined("ABSPATH") or die("No direct access!");
class AnamneseSystem {

function __construct() {
  //add_filter('login_redirect',array($this,'anam_restrict_wpadmin'));
  register_activation_hook(__FILE__ , array($this,'anam_plugin_activation') );
	add_action('init', array($this, 'anam_start_from_here'));
	add_action('wp_enqueue_scripts', array($this, 'anam_enqueue_script_front'));
  add_action('admin_enqueue_scripts', array($this, 'anam_enqueue_script_admin'));
	add_action('after_setup_theme',array($this,'anam_admin_bar'));
	add_action('init', array($this, 'anam_create_table'));
  add_filter('the_content',array($this,'my_replace_content'),100);
  require_once plugin_dir_path(__FILE__) . 'back/podo_settings_page.php';

  //add_action("init", array($this, 'get_customer_last_name'));
}



function anam_start_from_here() {

    require_once plugin_dir_path(__FILE__) . 'back/anam_role_maker.php';
    require_once plugin_dir_path(__FILE__) . 'back/anam_useful_functions.php';
    require_once plugin_dir_path(__FILE__) . 'front/anam_my_account.php';
    require_once plugin_dir_path(__FILE__) . 'front/anam_create_new_customer.php';
    require_once plugin_dir_path(__FILE__) . 'front/anam_create_new_customer2.php';
    require_once plugin_dir_path(__FILE__) . 'front/anam_remove_customer.php';
  
    require_once plugin_dir_path(__FILE__) . 'front/anam_preview_customer.php';
    require_once plugin_dir_path(__FILE__) . 'front/anam_edit_customer.php';
    require_once plugin_dir_path(__FILE__) . 'front/anam_update_customer.php';
    require_once plugin_dir_path(__FILE__) . 'front/anam_search_customer.php';
    require_once plugin_dir_path(__FILE__) . 'front/anam_create_new_document.php';
    require_once plugin_dir_path(__FILE__) . 'front/anam_insert_new_document.php';
    require_once plugin_dir_path(__FILE__) . 'front/anam_customer_side_info.php';
    require_once plugin_dir_path(__FILE__) . 'front/anam_edit_document.php';
    require_once plugin_dir_path(__FILE__) . 'front/anam_update_document.php';
    require_once plugin_dir_path(__FILE__) . 'front/anam_preview_document.php';
   require_once plugin_dir_path(__FILE__) . 'front/dokument/anam_create_new_dokument.php';
   require_once plugin_dir_path(__FILE__) . 'front/dokument/anam_insert_new_dokument.php';
    require_once plugin_dir_path(__FILE__) . 'front/dokument/anam_preview_dokument.php';
    require_once plugin_dir_path(__FILE__) . 'front/dokument/anam_edit_dokument.php';
    require_once plugin_dir_path(__FILE__) . 'front/dokument/anam_update_dokument.php';
    require_once plugin_dir_path(__FILE__) . 'front/dokument/anam_remove_dokument.php';
    require_once plugin_dir_path(__FILE__) . 'front/dokument/anam_search_for_treatment.php';

    /* Anamnese */
    require_once plugin_dir_path(__FILE__) . 'back/anamnese/podo_create_anamnese_new_field.php';
    require_once plugin_dir_path(__FILE__) . 'back/anamnese/podo_insert_field.php';
    require_once plugin_dir_path(__FILE__) . 'back/anamnese/podo_delete_fields.php';
    require_once plugin_dir_path(__FILE__) . 'back/anamnese/podo_edit_field.php';
    require_once plugin_dir_path(__FILE__) . 'back/anamnese/podo_update_field.php';
	require_once plugin_dir_path(__FILE__) . 'back/anamnese/podo_drag_drop_fields.php';   
	require_once plugin_dir_path(__FILE__) . 'back/anamnese/podo_search_anamnese_filter.php';   
	

	/* Treatments */
   	//require_once plugin_dir_path(__FILE__) . 'back/treatments/podo_create_treatment_popup.php'; 
   	require_once plugin_dir_path(__FILE__) . 'back/treatments/podo_insert_treatments.php'; 
    require_once plugin_dir_path(__FILE__) . 'back/treatments/podo_edit_treatment.php'; 
    require_once plugin_dir_path(__FILE__) . 'back/treatments/podo_update_treatment.php'; 
  	require_once plugin_dir_path(__FILE__) . 'back/treatments/podo_delete_treatment.php';
  	require_once plugin_dir_path(__FILE__) . 'back/treatments/podo_order_by_treatment_details.php';
  	

  /* Customer */
    require_once plugin_dir_path(__FILE__) . 'back/customers/podo_edit_customer.php'; 
    require_once plugin_dir_path(__FILE__) . 'back/customers/podo_update_customer.php'; 
    require_once plugin_dir_path(__FILE__) . 'back/customers/podo_order_by_filter.php';
    require_once plugin_dir_path(__FILE__) . 'back/customers/podo_search_customer_filter.php';

  /* Documentation */
  require_once plugin_dir_path(__FILE__) . 'back/documentation/podo_add_payment_method.php'; 
  require_once plugin_dir_path(__FILE__) . 'back/documentation/podo_add_payment_process.php'; 
  require_once plugin_dir_path(__FILE__) . 'back/documentation/podo_insert_payments.php'; 
  require_once plugin_dir_path(__FILE__) . 'back/documentation/podo_upload_qr_image.php'; 
  require_once plugin_dir_path(__FILE__) . 'back/documentation/podo_delete_payment_method.php'; 
  require_once plugin_dir_path(__FILE__) . 'back/documentation/podo_edit_payment_method.php'; 

  require_once plugin_dir_path(__FILE__) . 'back/documentation/podo_edit_payment_method.php'; 
  require_once plugin_dir_path(__FILE__) . 'front/dokument/anam_filter_treatment_prices.php'; 
  require_once plugin_dir_path(__FILE__) . 'front/dokument/anam_filter_qr_code.php'; 

  /*
    Accounting
  */
  require_once plugin_dir_path(__FILE__) . 'back/accounting/podo_change_dokument_status.php';
  require_once plugin_dir_path(__FILE__) . 'back/accounting/podo_filter_by_month.php';
  require_once plugin_dir_path(__FILE__) . 'back/accounting/podo_filter_by_year.php';




}



function auto_redirect_after_logout(){
  wp_safe_redirect( home_url() );
  exit;
}



// Enqueue Style and Scripts

function anam_enqueue_script_front() {
//Style & Script
	wp_enqueue_style('anam-style', plugins_url('assets/css/anam.css', __FILE__),rand(0,1000),'all');
	wp_enqueue_style('anam-fontawesome','https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@5.15.3/css/fontawesome.min.css','5.15.3','all');
	wp_enqueue_style('anam-sweet','https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.1.2/sweetalert2.min.css',rand(0,1000),'all');
	wp_enqueue_style('anam-magnifier-popup', plugins_url('vendor/magnific-popup.css', __FILE__),'2.8.0','all');


	wp_enqueue_script('anam-magnific-lightboxjs', plugins_url('vendor/jquery.magnific-popup.js', __FILE__),array('jquery'),'2.8.0',false);

	wp_enqueue_script('anam-sweet-js','https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.1.2/sweetalert2.all.min.js',rand(0,1000),false);

  	wp_enqueue_script('media-upload');
  	wp_enqueue_script('thickbox');
  	 wp_enqueue_style('dashicons');



	wp_enqueue_script('anam-script', plugins_url('assets/js/anam.js', __FILE__),array('jquery'),rand(0,1000), false);
	wp_localize_script( 'anam-script', 'anam_object',array( 'ajax_url' => admin_url( 'admin-ajax.php' )) );
	
	
}

function anam_enqueue_script_admin(){
    
	$current_page = get_current_screen()->base;
	//echo $current_page;
	if($current_page == 'toplevel_page_podo_settings_page' || $current_page == 'all-in-podo_page_podo_anamnese_settings_page' || $current_page == 'all-in-podo_page_podo_treatment_settings_page' || $current_page == 'all-in-podo_page_podo_firm_settings_page' || $current_page == 'all-in-podo_page_podo_customers_settings_page' || $current_page == 'all-in-podo_page_podo_documentations_settings_page' || $current_page == 'all-in-podo_page_podo_accounting_settings_page') {

	wp_enqueue_style('podo-admin',plugins_url('assets/css/podo_admin.css', __FILE__),rand(0,1000),'all');

	wp_enqueue_style('podo-jquery-ui','https://code.jquery.com/ui/1.13.0/themes/base/jquery-ui.css',rand(0,1000),'all');


	wp_enqueue_script('podo-sweet-js','https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.1.2/sweetalert2.all.min.js',rand(0,1000),false);

	wp_enqueue_script("jquery-ui-core");
	wp_enqueue_script("jquery-ui-draggable");
	wp_enqueue_media();

	wp_enqueue_style('podo-datatable','https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css','1.11.3','all');

	wp_enqueue_script('podo-datatable-js','https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js', array('jquery'),'1.11.3',false);

	wp_enqueue_script("jquery-ui-tabs");

	wp_enqueue_script('anam-chartjs','https://cdn.jsdelivr.net/npm/chart.js@3.6.0/dist/chart.min.js',array('jquery'),'3.6.0', false);


  /*
    MB EXTRUDER
  */

	wp_enqueue_script('anam-extruder',plugins_url('vendor/mbExtruder.js', __FILE__),array('jquery'),'', false);

	wp_enqueue_script('anam-magnific',plugins_url('vendor/jquery.magnific-popup.js', __FILE__),array('jquery'),'', false);

	wp_enqueue_script('anam-flipText',plugins_url('vendor/jquery.mb.flipText.js', __FILE__),array('jquery'),'', false);

    wp_enqueue_style('podo-mbExtruder',plugins_url('vendor/mbExtruder.css', __FILE__),rand(0,1000),'all');

	wp_enqueue_script('podo-script-admin', plugins_url('assets/js/podo_admin.js', __FILE__),array('jquery'),rand(0,1000), false);
	wp_localize_script( 'podo-script-admin', 'podo_object',array( 'ajax_url' => admin_url( 'admin-ajax.php' ), 'podo_currency' => get_option('podo_currency') ) );


} 

}


function anam_admin_bar() {
    if (!current_user_can('administrator') && !is_admin()) {
      show_admin_bar(false);
    }
}

function anam_restrict_wpadmin(){

	  if(current_user_can('mitarbeiter') || current_user_can('subscriber') ){
	    wp_redirect(home_url().'/my-account');
	    echo "HEKKI";
	    exit;
	  }
}

function anam_create_table(){

    global $wpdb;
    $table_name = $wpdb->base_prefix.'anam_customer_info';
    $query = $wpdb->prepare( 'SHOW TABLES LIKE %s', $wpdb->esc_like( $table_name ) );
    if ( ! $wpdb->get_var( $query ) == $table_name ) {

        $charset_collate = $wpdb->get_charset_collate();
        $sql = "CREATE TABLE $table_name (
          id mediumint(255) NOT NULL AUTO_INCREMENT,
          first_name varchar(20) NOT NULL,
          last_name varchar(20) NOT NULL,
          email_address varchar(30) NOT NULL,
          gender varchar(10) NOT NULL,
          birth_date varchar(20) NOT NULL,
          mobile_no varchar(15) NOT NULL,
          job varchar(50) NOT NULL,
          city varchar(20) NOT NULL,
          zipcode varchar(10) NOT NULL,
          fad varchar(15) NOT NULL,
          address text NOT NULL,
          doctor_name varchar(30) NOT NULL,
          diagnosis varchar(20) NOT NULL,
          phone_of_doctor varchar(15) NOT NULL,
          drugs varchar(30) NOT NULL,
          insurance_company varchar(30) NOT NULL,
          vorerkrankungen varchar(30) NOT NULL,
          agree_on_terms varchar(30) NOT NULL,
          important_notes text NOT NULL,
          doctor_id varchar(20) NOT NULL,
          created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
          updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
          PRIMARY KEY  (id)
        ) $charset_collate;";
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );

    }



/*  	$table_name1 = $wpdb->base_prefix.'anam_document_info';
    $query = $wpdb->prepare( 'SHOW TABLES LIKE %s', $wpdb->esc_like( $table_name1 ) );
    if ( ! $wpdb->get_var( $query ) == $table_name1 ) {

        $charset_collate = $wpdb->get_charset_collate();
        $sql = "CREATE TABLE $table_name1 (
          id mediumint(255) NOT NULL AUTO_INCREMENT,
          antikoagulation varchar(30) NOT NULL,
          weitere_medikamente varchar(30) NOT NULL,
          allergien varchar(30) NOT NULL,
          apoplexie varchar(30) NOT NULL,
          paresen varchar(30) NOT NULL,
          dermatologische_erkrankungen  varchar(30) NOT NULL,
          ulcus  varchar(30) NOT NULL,
          diabetes varchar(30) NOT NULL,
          neuropathie varchar(30) NOT NULL,
          gicht varchar(30) NOT NULL,
          varizen varchar(30) NOT NULL,
          pavk varchar(30) NOT NULL,
          thrombose varchar(30) NOT NULL,
          cvi varchar(30) NOT NULL,
          phlebitis varchar(30) NOT NULL,
          oedem varchar(30) NOT NULL,
          rheumatioide_arthritis_ra varchar(30) NOT NULL,
          arthrose varchar(30) NOT NULL,
          virale_erkrankungen varchar(30) NOT NULL,
          gangran varchar(30) NOT NULL,
          stoffwechselerkrankungen varchar(30) NOT NULL,
          gefasserkrankungen varchar(30) NOT NULL,
          gelenkerkrankungen varchar(30) NOT NULL,
          operationen varchar(30) NOT NULL,
          orthonyxie varchar(30) NOT NULL,
          orthesen varchar(30) NOT NULL,
          nagelprotetik varchar(30) NOT NULL,
          senkfuss_des_lw varchar(30) NOT NULL,
          knickung_des_valgus varchar(30) NOT NULL,
          Senkung_des_qw varchar(30) NOT NULL,
          spreizung_hv_hr_qv varchar(30) NOT NULL,
          hohlfuss varchar(30) NOT NULL,
          hammerzehen varchar(30) NOT NULL,
          krallenzehen varchar(30) NOT NULL,
          beschwerden_schmerzen varchar(30) NOT NULL,
          hyperkreatose varchar(30) NOT NULL,
          rhagaden varchar(30) NOT NULL,
          malum_perforans varchar(30) NOT NULL,
          Panaritium varchar(30) NOT NULL,
          psoriasis varchar(30) NOT NULL,
          navus varchar(30) NOT NULL,
          clavus_clavi varchar(30) NOT NULL,
          clavus_im_nagelfalz varchar(30) NOT NULL,
          clavus_interdig_subung varchar(30) NOT NULL,
          verruca varchar(30) NOT NULL,
          bursitis varchar(30) NOT NULL,
          dermatomykose varchar(30) NOT NULL,
          onychokryptose varchar(30) NOT NULL,
          onychoschisis varchar(30) NOT NULL,
          onychomykose varchar(30) NOT NULL,
          onychauxis varchar(30) NOT NULL,
          onycholyse varchar(30) NOT NULL,
          onychogryposis varchar(30) NOT NULL,
          onychopathie varchar(30) NOT NULL,
          user_id int(10) NOT NULL,
          doctor_id varchar(10) NOT NULL,
          created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
          updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
          PRIMARY KEY  (id)
        ) $charset_collate;";
        require_once( ABSPATH .'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );

    }*/


    $table_name1 = $wpdb->base_prefix.'anam_document_info';
    $query = $wpdb->prepare( 'SHOW TABLES LIKE %s', $wpdb->esc_like( $table_name1 ) );
    if ( ! $wpdb->get_var( $query ) == $table_name1 ) {

        $charset_collate = $wpdb->get_charset_collate();
        $sql = "CREATE TABLE $table_name1 (
          id mediumint(255) NOT NULL AUTO_INCREMENT,
          user_id int(10) NOT NULL,
          doctor_id varchar(10) NOT NULL,
          created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
          updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
          PRIMARY KEY  (id)
        ) $charset_collate;";
        require_once( ABSPATH .'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );

    }

    $existing_columns = $wpdb->get_col("DESC {$table_name1}", 0);
  /*  echo "<pre>";
    print_r($existing_columns);
    echo "</pre>";*/

    $table_name2 = $wpdb->base_prefix.'anam_dokument_info';
    $query = $wpdb->prepare( 'SHOW TABLES LIKE %s', $wpdb->esc_like( $table_name2 ) );
    if ( ! $wpdb->get_var( $query ) == $table_name2 ) {

        $charset_collate = $wpdb->get_charset_collate();
        $sql = "CREATE TABLE $table_name2 (
          id mediumint(255) NOT NULL AUTO_INCREMENT,
          treatment_name varchar(100) NOT NULL,
          price text NOT NULL,
          addition_information text NOT NULL,
          payment_methods varchar(50) NOT NULL,
          email_pdf text NOT NULL,
          status  varchar(100) NOT NULL,
          customer_id varchar(20) NOT NULL,
          doctor_id varchar(20) NOT NULL,
          created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
          updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
          PRIMARY KEY  (id)
        ) $charset_collate;";
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );

    }


    $table_name3 = $wpdb->base_prefix.'anam_fields_maker';
    $query = $wpdb->prepare( 'SHOW TABLES LIKE %s', $wpdb->esc_like( $table_name3 ) );
    if ( ! $wpdb->get_var( $query ) == $table_name3 ) {

        $charset_collate = $wpdb->get_charset_collate();
        $sql = "CREATE TABLE $table_name3 (
          id mediumint(255) NOT NULL AUTO_INCREMENT,
          label varchar(100) NOT NULL,
          fieldtype text NOT NULL,
          fname varchar(100) NOT NULL,
          forder int(100) NOT NULL,
          created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
          PRIMARY KEY  (id)
        ) $charset_collate;";
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );

    }

    $table_name4 = $wpdb->base_prefix.'anam_treatments_list';
    $query = $wpdb->prepare( 'SHOW TABLES LIKE %s', $wpdb->esc_like( $table_name4 ) );
    if ( ! $wpdb->get_var( $query ) == $table_name4 ) {

        $charset_collate = $wpdb->get_charset_collate();
        $sql = "CREATE TABLE $table_name4 (
          id mediumint(255) NOT NULL AUTO_INCREMENT,
          name varchar(100) NOT NULL,
          price varchar(10) NOT NULL,
          duration varchar(20) NOT NULL,
          description text NOT NULL,
          created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
          PRIMARY KEY  (id)
        ) $charset_collate;";
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );

    }

    $table_name5 = $wpdb->base_prefix.'anam_payment_methods';
    $query = $wpdb->prepare( 'SHOW TABLES LIKE %s', $wpdb->esc_like( $table_name5 ) );
    if ( ! $wpdb->get_var( $query ) == $table_name5 ) {

        $charset_collate = $wpdb->get_charset_collate();
        $sql = "CREATE TABLE $table_name5 (
          id mediumint(255) NOT NULL AUTO_INCREMENT,
          payment_method_name varchar(50) NOT NULL,
          payment_method_description text NOT NULL,
          enableQR int(2) NOT NULL, 
          QRImage varchar(255) NOT NULL,
          created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
          PRIMARY KEY  (id)
        ) $charset_collate;";
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );

    }

/*    $sql = "DROP TABLE IF EXISTS $table_name5";
    $wpdb->query($sql);*/


}



function anam_plugin_activation() {
  
    if ( ! current_user_can( 'activate_plugins' ) ) return;
    global $wpdb;
    if ( null === $wpdb->get_row( "SELECT post_name FROM {$wpdb->prefix}posts WHERE post_name = 'customer-page'", 'ARRAY_A' ) ) {
       
      $current_user = wp_get_current_user();
      // create post object
      $page = array(
        'post_title'  => __('Customer Page'),
        'post_status' => 'publish',
        'post_author' => $current_user->ID,
        'post_type'   => 'page',
      );
      
      // insert the post into the database
      wp_insert_post( $page );
    }


    $wp_upload_dir =  wp_upload_dir();
	  $custom_upload_folder = $wp_upload_dir['basedir'].'/'."anam_users";
  	if(!is_dir($path)){
  		mkdir($custom_upload_folder);
  	}

}

function my_replace_content ($content) {
    $page = get_page_by_path('customer-page', OBJECT );
    if ( is_page($page) ){
        $content = do_shortcode("[customer_info]");
    }

    return $content;
}




} // class ends

// CHECK WETHER CLASS EXISTS OR NOT.

if (class_exists('AnamneseSystem')) {
	$obj = new AnamneseSystem();
}