<?php
/*
Plugin Name: Jargal Anniversary Calendar
Plugin URI: http://wordpresswithzaheer.blogspot.com/p/plugin.html
Description: Display Anniversary Dates of Articles In Calendar.
Version: 1.0
Author: Zaheer Abbas Aghani
Author URI: https://profiles.wordpress.org/zaheer01/
License: GPLv3 or later
Text Domain: jargal-anniversary-calendar
Domain Path: /languages
*/

defined("ABSPATH") or die("No direct access!");
class JargalAnniversayCalendar {

function __construct() {
	add_action('init', array($this, 'jac_start_from_here'));
	add_action('wp_enqueue_scripts', array($this, 'jac_enqueue_script_front'));
    add_filter('manage_post_posts_columns',array($this,'misha_price_and_featured_columns'));
    add_action('manage_posts_custom_column',array($this,'misha_populate_both_columns'),10,2);
    add_action('quick_edit_custom_box',array($this,'misha_quick_edit_fields'), 10, 2);
    add_action( 'save_post', array($this,'misha_quick_edit_save' ));
    add_action( 'admin_enqueue_scripts', array($this,'misha_enqueue_quick_edit_population' ));
    add_action( "init",array($this,"stick_anniversay_post_on_top"));
    add_shortcode('subscribe_now', array($this, 'aem_embed_event_content_before'));
    add_action('init', array($this,'jac_create_table'));
   	add_action('admin_enqueue_scripts', array($this, 'jac_enqueue_admin_script'));
   	register_activation_hook( __FILE__, array($this,'my_activation'));
	add_action( 'my_hourly_event', array($this,'do_this_hourly'));
	register_deactivation_hook( __FILE__, array($this,'my_deactivation' ));
    add_action( "init", array($this,'jac_unsubscribe'));
    add_filter( 'the_content', array($this,'theme_slug_filter_the_content' ));
}



function jac_start_from_here() {
	require_once plugin_dir_path(__FILE__) . 'jac_front/jac_shortcode.php';
	require_once plugin_dir_path(__FILE__) . 'jac_front/jac_enable_disable_post.php';
	require_once plugin_dir_path(__FILE__) . 'jac_front/jac_subscribe_now.php';

	require_once plugin_dir_path(__FILE__) . 'jac_back/jac_subscription_dashboard.php';
	require_once plugin_dir_path(__FILE__) . 'jac_back/jac_subscriber_remove.php';
	require_once plugin_dir_path(__FILE__) . 'jac_back/jac_send_test_email.php';
 
}

// Enqueue Style and Scripts

function jac_enqueue_script_front() {


wp_enqueue_style('jsc-main-css',plugins_url('assets/animated-event-calendar/dist/simple-calendar',__FILE__),'','all');

wp_enqueue_script('jsc-main-js',plugins_url('assets/animated-event-calendar/dist/jquery.simple-calendar.min',__FILE__),array('jquery'),'', false);


//Style & Script
wp_enqueue_style('jac-style', plugins_url('assets/css/jac.css', __FILE__),'1.0.0','all');
wp_enqueue_script('jac-script', plugins_url('assets/js/jac.js', __FILE__),array('jquery', 'jquery-ui-core', 'jquery-ui-datepicker'),'1.0.0', true);

wp_localize_script( 'jac-script', 'ajax_object',
            array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );


}



function jac_enqueue_admin_script(){

	// DataTable
	wp_enqueue_style('jac-dataTable', "https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css",'1.10.21','all');

	wp_enqueue_script('jac-script', "https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js",array('jquery'),'1.10.21', true);

	wp_enqueue_script('jac-btn', "https://cdn.datatables.net/buttons/1.6.2/js/dataTables.buttons.min.js",array('jquery'),'1.6.2', true);

	wp_enqueue_script('jac-jszip', "https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js",array('jquery'),'3.1.3', true);

	wp_enqueue_script('jac-jszip', "https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js",array('jquery'),'0.1.53', true);

	wp_enqueue_script('jac-vfs_fonts', "https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js",array('jquery'),'0.1.53', true);

	wp_enqueue_script('jac-btn-html5', "https://cdn.datatables.net/buttons/1.6.2/js/buttons.html5.min.js",array('jquery'),'1.6.2', true);

	wp_enqueue_script('jquery-ui-accordion');

	wp_enqueue_script('jac-admin-script', plugins_url('assets/js/jac_admin.js', __FILE__),array('jquery'),'1.0.0', true);
	wp_localize_script( 'jac-admin-script', 'ajax_object',
            array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
}



/*
 * New columns
 */

// the above hook will add columns only for default 'post' post type, for CPT:
// manage_{POST TYPE NAME}_posts_columns
function misha_price_and_featured_columns( $column_array ) {
    $column_array['hidePost'] = 'Anniversary Disabled';
    
    return $column_array;
}
 
/*
 * Populate our new columns with data
 */

function misha_populate_both_columns( $column_name, $id ) {
 
    // if you have to populate more that one columns, use switch()
    switch( $column_name ) :
        case 'hidePost': {
            if( get_post_meta($id,'rudr_noindex',true) == 'on') {
                echo 'Yes';
            }else{
                echo 'No';
            }
            break;
        }
    endswitch;
 
}


function misha_quick_edit_fields( $column_name, $post_type ) {
 
    // you can check post type as well but is seems not required because your columns are added for specific CPT anyway
 
    switch( $column_name ) :
        case 'hidePost': {
            wp_nonce_field( 'misha_q_edit_nonce', 'misha_nonce' );
            echo '<label class="alignleft">
                    <input type="checkbox" name="rudr_noindex">
                    <span class="checkbox-title">Check this to hide post from anniversairy calendar.</span>
                </label>';
         //   echo "<a href='#'>Test Email</a>";
           // echo '</div></div></fieldset>';
 
            break;
 
        }
 
    endswitch;
 
}


/*
 * Quick Edit Save
 */

 
function misha_quick_edit_save( $post_id ){
 
    // check user capabilities
    if ( !current_user_can( 'edit_post', $post_id ) ) {
        return;
    }
 
    // check nonce
    if ( !wp_verify_nonce( $_POST['misha_nonce'], 'misha_q_edit_nonce' ) ) {
        return;
    }
 
    // update checkbox
    if ( isset( $_POST['rudr_noindex'] ) ) {
        update_post_meta( $post_id, 'rudr_noindex', $_POST['rudr_noindex'] );
    } else {
        update_post_meta( $post_id, 'rudr_noindex', '' );
    }
 
 
}


function misha_enqueue_quick_edit_population( $pagehook ) {
 
    // do nothing if we are not on the target pages
    if ( 'edit.php' != $pagehook ) {
        return;
    }
 
    
    wp_enqueue_script( 'my_custom_script', plugins_url('/assets/js/admin_edit.js', __FILE__),false, null, true );
 
}

function aem_embed_event_content_before($form) {
    if ( is_singular( 'post' ) ) {
    	
        $status = get_post_meta(get_the_ID(),'rudr_noindex',true);

        if($status != 'on'){
        	$form .= "<form method='post' action='#' onsubmit='return false' id='jac_subscribe'> 
        		<input type='email' id='subscribe_it' placeholder='Votre email' required style='width:100%;'>
                <label style='width:100%;display:block;margin-top:10px;'><input type='radio' name='subscribe' class='subscribe' value='All' required> Tous les événements</label>
                <label style='width:100%;display:block;margin-top:10px;margin-bottom:15px;'><input type='radio' name='subscribe' class='subscribe' value='".get_the_ID()."' required> Seulement cet événement</label>
        		<input type='submit' value='Envoyer' id='frmbtn'/>
        	</form>";
        	return $form;
        }
    }
    //return $content;
}


/**
 * Create Table In Database
*/
function jac_create_table(){
    global $wpdb;
    $table_name = $wpdb->base_prefix.'jac_subscribe';
    $query = $wpdb->prepare( 'SHOW TABLES LIKE %s', $wpdb->esc_like( $table_name ) );
    if ( ! $wpdb->get_var( $query ) == $table_name ) {

        $charset_collate = $wpdb->get_charset_collate();
        $sql = "CREATE TABLE $table_name (
          id mediumint(100) NOT NULL AUTO_INCREMENT,
          user_email tinytext NOT NULL,
          post_id tinytext NOT NULL,
          PRIMARY KEY  (id)
        ) $charset_collate;";
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );

    }
   // $wpdb->query( "DROP TABLE IF EXISTS $table_name" );
}


 
function my_activation() {
    wp_schedule_event( time(), 'hourly', 'my_hourly_event' );
}
 
function do_this_hourly() {
    // do something every hour

$args = array("post_type" => "post", "posts_per_page" => -1);
$query = new WP_Query($args);

if($query->have_posts()): while($query->have_posts()): $query->the_post();
     $featured_img_url = get_the_post_thumbnail_url(get_the_ID(),'full'); 

    $post_date  =  get_the_date("d-m");
    $today      =  date("d-m");
    $status     = get_post_meta(get_the_ID(),'rudr_noindex',true);

    global $wpdb;
    $table_name = $wpdb->base_prefix.'jac_subscribe';
    $results = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $table_name"));

    $message = esc_attr(get_option('jac_message'));
    $m = explode(" ", $message);

    $time_ago = array_search('[time_ago]', $m);
    $m[$time_ago] = esc_html(human_time_diff_floor( get_the_time('U'), current_time('timestamp') ) ); 

$all_email = get_post_meta( get_the_ID(), 'message_sent', false );
  //  print_r($all_email)."<br>";
foreach ($results as $result) {
 if($post_date == $today && $status != "on"){

 if($result->post_id == "All"){
                        
    $headers=array('Content-Type: text/html; charset=UTF-8');
                        
    $message = '<html>
    <head>

        <title>'.get_the_title().'</title>

    </head>
    <body>
        <div id="email_container" style="background: #fff;
    width: 556px;
    margin: auto;
    padding: 0px 17px;border:3px #ee3333 solid;">
         
                 <div style="width:100%;display:block;text-align:center;"><img src="https://tech-time.fr/wp-content/uploads/2020/12/Tech-Time-logo-2-e1607210236183.png" style="width:90%;padding-top: 20px;">
                </div>
            
                <p style="text-align: center;padding-top: 10px;margin-bottom: 30px;font-size:17px;">'.implode(" ", $m).'</p>
          
                <a href="'.get_the_permalink().'" style="background: #ee3333;padding: 13px 20px;color: #fff;text-decoration: none;font-weight: bold;">'.get_the_date("j F Y").'</a>
                <a href="'.get_the_permalink().'" style="text-decoration: none;" target="_blank"><h1 style="padding: 15px 0 0 0;font-family: georgia;font-weight: 500;font-size: 24px;color: #000;border-bottom: 1px solid #bbb;padding-bottom: 0px;margin-top: 0px;">'.get_the_title().'</span> 
                </h1></a>

                <img src="'.$featured_img_url.'" style="width:100%;">

            

                <div style="font-size:17px;line-height:30px; color:#000;">'. wp_trim_words( get_the_content(),100, "" ) .' <a href="'.get_the_permalink().'" style="color: #f10909;" target="_blank">...Lire la suite >> </a></div>
               
                <p style="">
                    <a href="'.get_site_url().'">'.get_bloginfo("name").'</a>
                </p>
      
            </div>
        </div>
    </body>
    </html>';

	if(!in_array($result->user_email, $all_email)){
	$subject = 'Anniversaire ! '. get_the_title();
        wp_mail( $result->user_email,$subject,$message, $headers);

        add_post_meta(get_the_ID(),"message_sent",$result->user_email);
    }
                   
} 
else{
if($result->post_id == get_the_ID()){
         
   $headers=array('Content-Type: text/html; charset=UTF-8');
   $message = '<html>
    <head>

        <title>'.get_the_title().'</title>

    </head>
    <body>
        <div id="email_container" style="background: #fff;
    width: 556px;
    margin: auto;
    padding: 0px 17px;border:3px #ee3333 solid;">
         
                <div style="width:100%;display:block;text-align:center;"><img src="https://tech-time.fr/wp-content/uploads/2020/07/cropped-Tech-Time-2-4.png" style="height:60px;padding-top: 20px;">
                </div>
            
                <p style="text-align: center;padding-top: 10px;margin-bottom: 30px;font-size:17px;">'.implode(" ", $m).'</p>
          
                <a href="'.get_the_permalink().'" style="background: #ee3333;padding: 13px 20px;color: #fff;text-decoration: none;font-weight: bold;">'.get_the_date("j l Y").'</a>
                <a href="'.get_the_permalink().'" style="text-decoration: none;" target="_blank"><h1 style="padding: 15px 0 0 0;font-family: georgia;font-weight: 500;font-size: 24px;color: #000;border-bottom: 1px solid #bbb;padding-bottom: 0px;margin-top: 0px;">'.get_the_title().'</span> 
                </h1></a>

                <img src="'.$featured_img_url.'" style="width:100%; height:300px;">

                <div style="font-size:17px;line-height:30px; color:#000;">'. wp_trim_words( get_the_content(),100, "" ) .' <a href="'.get_the_permalink().'" style="color: #f10909;" target="_blank">Read more >> </a></div>
               
                <p style="">
                    <a href="'.get_site_url().'">'.get_bloginfo("name").'</a>
                </p>
      
            </div>
        </div>
    </body>
    </html>';

            $results1 = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $table_name WHERE post_id='".get_the_ID()."' "));

//            print_r($results1);

            foreach ($results1 as $result1) {
                if($post_date == $today && $status != "on"){
                    $all_email = get_post_meta( get_the_ID(), 'message_sent', false );
	                if(!in_array($result1->user_email, $all_email)){
	                	$subject = get_the_title().' - '.get_the_date("d-m-Y");
	                    wp_mail( $result1->user_email, $subject ,$message, $headers);
	                    add_post_meta(get_the_ID(),"message_sent",$result1->user_email);
	                }
                }
            }


} //end second if

} //endelse


    }
          
}
   // }

endwhile;
endif;




}


 
function my_deactivation() {
    wp_clear_scheduled_hook( 'my_hourly_event' );
}


function jac_unsubscribe(){
    
    if(!empty($_GET['user_email'])){
        global $wpdb;
        $table_name = $wpdb->base_prefix.'jac_subscribe';
        $email = $_GET['user_email'];
        $wpdb->delete( $table_name, array( 'user_email' => $email ) );

        echo "Désinscription enregistrée";
        echo "<a href='".get_site_url()."'> Cliquez ici pour retourner sur le site</a>";
        die();
    }
}

function theme_slug_filter_the_content( $content ) {
    
    if ( is_singular( 'post' ) ) {
        
        $status = get_post_meta(get_the_ID(),'rudr_noindex',true);
        if($status != 'on'){
            $content = $content. '<div class="subscribe_here"> <p> <b>Abonnez-vous !</b><br><font size=2>Soyez alerté des anniversaires par e-mail</font></p> [subscribe_now] </div>';
        
        }
    }

    return $content;
}



} // class ends

// CHECK WETHER CLASS EXISTS OR NOT.

if (class_exists('JargalAnniversayCalendar')) {
	$obj = new JargalAnniversayCalendar();
    require_once plugin_dir_path(__FILE__) . 'jac_front/jac_anniversary_of_the_day_widget.php';
}