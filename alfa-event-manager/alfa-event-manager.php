<?php
/*
Plugin Name: Alfa Event Manager
Plugin URI: https://www.fiverr.com/zaheerabbasagha
Description: Creator Role can Add/Edit events. Information needed for Creation will be Title, Date, Mandatory Attendace for Group X, Amount of Points. Attendace Role should - as far as his user role is like attendance Group - assign to an event. If event is over the creator role kann confirm his attendance and the user will be awared with the amount of points. Amount of points need to be visible for creator role (date from/to) and csv. exported. Attendee role is only able to view his own events and points.
Roles don't need to change. They are called 'Fux', 'Bursch', 'Alter Herr' and as Creator 'CHC'
Version: 1.0
Author: Zaheer Abbas Aghani
Author URI: https://profiles.wordpress.org/zaheer01/
License: GPLv3 or later
Text Domain: alfa-event-manager
Domain Path: /languages
*/

defined("ABSPATH") or die("Kein direkter Zugriff!");
class AlfaEventManager {

function __construct() {
	add_action('init', array($this, 'aem_start_from_here'));
	add_action('wp_enqueue_scripts', array($this, 'aem_enqueue_script_front'));
	add_action( 'init',  array($this,'aem_create_cpt'));
	add_action( 'add_meta_boxes',  array($this,'events_extra_information' ));
	add_action( 'save_post', array($this,'aem_extra_information_box_save' ));
  add_filter( 'manage_events_posts_columns', array($this,'set_custom_edit_events_columns' ));	
  add_action( 'manage_events_posts_custom_column' , array($this,'custom_events_column'), 10, 2 );
	add_filter( "the_content", array($this,'aem_show_event_extra_information'));	
	add_action( 'init',  array($this,'aem_register_ref_page'));
	add_action( "admin_enqueue_scripts", array($this,"aem_admin_enqueue"));
  add_action( "init", array($this,"aem_event_status_change"));
	add_action( "init", array($this,"alfa_create_table"));
  add_action( 'init', array($this,'create_genres_hierarchical_taxonomy'), 0 );  	
}

function aem_start_from_here() {
	// Backend Scripts
	require_once plugin_dir_path(__FILE__) . 'back/create_point_for_user.php';

	// Frontend Scripts
	require_once plugin_dir_path(__FILE__) . 'front/aem_list_of_events.php';
	require_once plugin_dir_path(__FILE__) . 'front/aem_join_event_process.php';
	require_once plugin_dir_path(__FILE__) . 'front/aem_leave_event_process.php';
  require_once plugin_dir_path(__FILE__) . 'front/aem_profile_page.php';
  require_once plugin_dir_path(__FILE__).'front/aem_filter_post_by_category.php';

}

// Enqueue Style and Scripts
function aem_enqueue_script_front() {
//Style & Script
wp_enqueue_style('aem-style',plugins_url('assets/css/aem.css', __FILE__),'1.0.0','all');
wp_enqueue_style('aem-bootstrap',"https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css",'4.5.2','all');

wp_enqueue_style('aem-roboto',"https://fonts.googleapis.com/css2?family=Roboto:wght@300&display=swap");

wp_enqueue_script('aem-bootstrap', "https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js",array('jquery'),'4.5.2', true);

wp_enqueue_style('aem-dataTable', "https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css",'1.10.21','all');

wp_enqueue_script('aem-script-datatable', "https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js",array('jquery'),'1.10.21', true);

wp_enqueue_script('aem-btn', "https://cdn.datatables.net/buttons/1.6.2/js/dataTables.buttons.min.js",array('jquery'),'1.6.2', true);

wp_enqueue_script('aem-jszip', "https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js",array('jquery'),'3.1.3', true);

wp_enqueue_script('aem-jszip', "https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js",array('jquery'),'0.1.53', true);

wp_enqueue_script('aem-vfs_fonts', "https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js",array('jquery'),'0.1.53', true);

wp_enqueue_script('aem-btn-html5', "https://cdn.datatables.net/buttons/1.6.2/js/buttons.html5.min.js",array('jquery'),'1.6.2', true);

wp_enqueue_script( 'jquery-ui-datepicker' );

// You need styling for the datepicker. For simplicity I've linked to the jQuery UI CSS on a CDN.
wp_enqueue_style('jquery-ui','https://code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css' ,'1.12.1','all');

wp_enqueue_script('aem-moment', "https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.0/moment.min.js",array('jquery'),'2.29.0', true);

wp_enqueue_script('aem-script', plugins_url('assets/js/aem.js', __FILE__),array('jquery'),'1.0.0', true);

wp_localize_script( 'aem-script', 'ajax_object',array( 'ajax_url' => admin_url( 'admin-ajax.php' )) );


}


function aem_admin_enqueue(){
wp_enqueue_script('jquery-ui-accordion');

wp_enqueue_style('aem-dataTable', "https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css",'1.10.21','all');

wp_enqueue_script('aem-script', "https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js",array('jquery'),'1.10.21', true);

wp_enqueue_script('aem-btn', "https://cdn.datatables.net/buttons/1.6.2/js/dataTables.buttons.min.js",array('jquery'),'1.6.2', true);

wp_enqueue_script('aem-jszip', "https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js",array('jquery'),'3.1.3', true);

wp_enqueue_script('aem-jszip', "https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js",array('jquery'),'0.1.53', true);

wp_enqueue_script('aem-vfs_fonts', "https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js",array('jquery'),'0.1.53', true);

wp_enqueue_script('aem-btn-html5', "https://cdn.datatables.net/buttons/1.6.2/js/buttons.html5.min.js",array('jquery'),'1.6.2', true);

wp_enqueue_script('aem-moment', "https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.0/moment.min.js",array('jquery'),'2.29.0', true);

wp_enqueue_script('aem-canvas', "https://canvasjs.com/assets/script/jquery.canvasjs.min.js",array('jquery'),'', true);

wp_enqueue_script('aem-adminjs',plugins_url('assets/js/aem-admin.js', __FILE__),array('jquery'),'1.0.0', true);



}


function aem_create_cpt() {
  $labels = array(
    'name'               => _x( 'Events', 'events' ),
    'singular_name'      => _x( 'Termin', 'events' ),
    'add_new'            => _x( 'Neuer Termin', 'events' ),
    'add_new_item'       => __( 'Neuen Termin erstellen' ),
    'edit_item'          => __( 'Termin bearbeiten' ),
    'new_item'           => __( 'Neuer Termin' ),
    'all_items'          => __( 'Alle Termine' ),
    'view_item'          => __( 'Termine ansehen' ),
    'search_items'       => __( 'Termine durchsuchen' ),
    'not_found'          => __( 'Keine Eintragungen gefunden' ),
    'not_found_in_trash' => __( 'Keine Termine im Papierkorb' ), 
    'menu_name'          => __('Termine')
  );
  $args = array(
    'labels'        => $labels,
    'description'   => 'Alle Einträge und deren Details',
    'public'        => true,
    'menu_position' => 5,
    'supports'      => array( 'title', 'editor' ),
    'has_archive'   => false,
    'hierarchical' => true,
    'menu_icon'   => 'dashicons-buddicons-groups',
    'taxonomies'  => array( 'groups' ),

  );
  register_post_type( 'events', $args ); 
}


//hook into the init action and call create_book_taxonomies when it fires
 

 
//create a custom taxonomy name it genres for your posts
 
function create_genres_hierarchical_taxonomy() {
 
// Add new taxonomy, make it hierarchical like categories
//first do the translations part for GUI
 
  $labels = array(
    'name' => _x( 'Kategorien', 'taxonomy general name' ),
    'singular_name' => _x( 'Kategorie', 'taxonomy singular name' ),
    'search_items' =>  __( 'Kategorien durchsuchen' ),
    'all_items' => __( 'Alle Kategorien' ),
    'parent_item' => __( 'Übergeordnete Kategorie' ),
    'parent_item_colon' => __( 'Übergeordnete Kategorie:' ),
    'edit_item' => __( 'Bearbeiten Kategorie' ), 
    'update_item' => __( 'Aktualisieren Kategorie' ),
    'add_new_item' => __( 'Neue Kategorie erstellen' ),
    'new_item_name' => __( 'Name neuer Kategorie' ),
    'menu_name' => __( 'Kategorien' ),
  );    
 
// Now register the taxonomy
  register_taxonomy('genres',array('events'), array(
    'hierarchical' => true,
    'labels' => $labels,
    'show_ui' => true,
    'show_in_rest' => true,
    'show_admin_column' => true,
    'query_var' => true,
    'rewrite' => array( 'slug' => 'genre' ),
  ));
 
}


function events_extra_information() {
    add_meta_box( 
        'events_extra_information',
        __( 'Zusatzinformationen', 'events' ),
        array($this,'events_extra_information_content'),
        'events',
        'normal',
        'high'
    );
}


function events_extra_information_content( $post ) {
	wp_nonce_field( plugin_basename( __FILE__ ), 'aem_event_start_date_content_nonce');
	wp_nonce_field( plugin_basename( __FILE__ ), 'aem_event_end_date_content_nonce');
	wp_nonce_field( plugin_basename( __FILE__ ), 'aem_select_roles_nonce');
	wp_nonce_field( plugin_basename( __FILE__ ), 'aem_points_fux');
  wp_nonce_field( plugin_basename( __FILE__ ), 'aem_points_bursch');
  wp_nonce_field( plugin_basename( __FILE__ ), 'aem_points_alter_herr');


  
	
  $aem_event_start_date = get_post_meta( $post->ID,'aem_event_start_date',true);
  $aem_event_end_date = get_post_meta( $post->ID, 'aem_event_end_date', true );
  $aem_select_roles 	= get_post_meta( $post->ID, 'aem_select_roles', true );
  $aem_points_fux     = get_post_meta( $post->ID, 'aem_points_fux', true );
  $aem_points_bursch  = get_post_meta( $post->ID, 'aem_points_bursch', true );
  $aem_points_alter_herr = get_post_meta( $post->ID,'aem_points_alter_herr',true);

  $aem_max_users 		= get_post_meta( $post->ID, 'aem_max_users', true );
  
  echo '<label for="aem_event_start_date" style="display:block;padding: 4px;margin-bottom: 10px;"> Event Start Date ';
  echo '<input type="date" id="aem_event_start_date" name="aem_event_start_date" placeholder="Startzeitpunkt" value="'.$aem_event_start_date.'" style="width:100%;" min="'.date("Y-m-d").'" required/></label>';

  echo '<label for="aem_event_end_date" style="display:block;padding: 4px;margin-bottom: 10px;"> Event End Date ';
  echo '<input type="date" id="aem_event_end_date" name="aem_event_end_date" placeholder="Endzeitpunkt" value="'.$aem_event_end_date.'" style="width:100%;" min="" required/></label>';

  echo '<label for="aem_select_roles" style="display:block;padding: 4px;margin-bottom: 10px;"> Select Roles';
	$aem_select_roles = get_post_meta( $post->ID, 'aem_select_roles', true ); 
	$roles = array("Fux","Bursch","Alter Herr"); 
	$role_name = array("um_fux","um_bursch","um_alter-herr");
	?>
	<select name="speaker_id[]" id="speaker_id" multiple="multiple" style="width:100%;" required>
	<?php 
	$i=0;
	foreach ($roles as $role) {  
	$selected = (in_array($role_name[$i], $aem_select_roles)) ? 'selected="selected"' :  ''; ?>
	    <option value="<?php echo $role_name[$i]; ?>" <?php echo $selected; ?>><?php echo $role; ?></option>
	<?php $i++; } ?>
	</select>


<script type="text/javascript">
  
  jQuery("#aem_event_start_date").change(function(){
    var start_date  = jQuery("#aem_event_start_date").val();
    var min_date    =  jQuery("#aem_event_end_date").attr("min",start_date);
  });

</script>

<?php 

  echo '<div class="um_fux" style="display:none;"> <label for="aem_points_fux" style="display:block;padding: 4px;margin-bottom: 10px;"> Role FUX Points ';
  echo '<input type="number" id="aem_points_fux" name="aem_points_fux" placeholder="Zu vergebende Punkte" value="'.$aem_points_fux.'" style="width:100%;"/></label></div>';

    echo '<div class="um_bursch" style="display:none;"><label for="aem_points_bursch" style="display:block;padding: 4px;margin-bottom: 10px;"> Role BURSCH Points ';
  echo '<input type="number" id="aem_points_bursch" name="aem_points_bursch" placeholder="Zu vergebende Punkte" value="'.$aem_points_bursch.'" style="width:100%;"/></label></div>';


    echo '<div class="um_alter-herr" style="display:none;"><label for="aem_points_alter_herr" style="display:block;padding: 4px;margin-bottom: 10px;"> Role ALTER HERR Points ';
  echo '<input type="number" id="aem_points_alter_herr" name="aem_points_alter_herr" placeholder="Zu vergebende Punkte" value="'.$aem_points_alter_herr.'" style="width:100%;"/></label></div>';


    echo '<label for="aem_max_users" style="display:block;padding: 4px;margin-bottom: 10px;"> Max Users ';
  echo '<input type="number" id="aem_max_users" name="aem_max_users" placeholder="Maximale Teilnehmerzahl" value="'.$aem_max_users.'" style="width:100%;" required/></label>';

  

}


function aem_extra_information_box_save( $post_id ) {

  if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
  return;


/*  if ( !wp_verify_nonce( plugin_basename( __FILE__ ), 'aem_event_start_date_content_nonce', plugin_basename( __FILE__ ) && 
  !wp_verify_nonce( plugin_basename( __FILE__ ), 'aem_event_end_date_content_nonce', plugin_basename( __FILE__ ) ) && 
  !wp_verify_nonce(plugin_basename( __FILE__ ), 'aem_select_roles_nonce', plugin_basename( __FILE__ ) ) && 
  !wp_verify_nonce( plugin_basename( __FILE__ ), 'aem_amount_points', plugin_basename( __FILE__ ) ) && 
  !wp_verify_nonce( plugin_basename( __FILE__ ), 'aem_state',  plugin_basename( __FILE__ ) ) && 
  !wp_verify_nonce( plugin_basename( __FILE__ ), 'aem_animal_type', plugin_basename( __FILE__ ) ) && 
  !wp_verify_nonce( plugin_basename( __FILE__ ), 'aem_animal_breed', plugin_basename( __FILE__ ) ) && 
  !wp_verify_nonce( plugin_basename( __FILE__ ), 'aem_special_mark', plugin_basename( __FILE__ ) ) && 
  !wp_verify_nonce(plugin_basename( __FILE__ ), 'aem_birth_Day', plugin_basename( __FILE__ ) ) && 
  !wp_verify_nonce( plugin_basename( __FILE__ ), 'aem_micro_chip', plugin_basename( __FILE__ ) ) ) )    
  return;*/

  if ( 'page' == $_POST['post_type'] ) {
    if ( !current_user_can( 'edit_page', $post_id ) )
    return;
  } else {
    if ( !current_user_can( 'edit_post', $post_id ) )
    return;
  }


  $aem_event_start_date = $_POST['aem_event_start_date'];
  update_post_meta( $post_id, 'aem_event_start_date', $aem_event_start_date );

  $aem_event_end_date = $_POST['aem_event_end_date'];
  update_post_meta( $post_id, 'aem_event_end_date', $aem_event_end_date );
  if ( isset( $_POST['speaker_id'] ) ) {
        $sanitized_data = array();
        $data = (array) $_POST['speaker_id'];
        foreach ($data as $key => $value) {
            $sanitized_data[ $key ] = $value ;
        }
        update_post_meta( $post_id, 'aem_select_roles', $sanitized_data );
    }
  $aem_points_fux = $_POST['aem_points_fux'];
  update_post_meta( $post_id, 'aem_points_fux', $aem_points_fux );

  $aem_points_bursch = $_POST['aem_points_bursch'];
  update_post_meta( $post_id, 'aem_points_bursch', $aem_points_bursch );

  $aem_points_alter_herr = $_POST['aem_points_alter_herr'];
  update_post_meta( $post_id, 'aem_points_alter_herr', $aem_points_alter_herr );

  $aem_max_users = $_POST['aem_max_users'];
  update_post_meta( $post_id, 'aem_max_users', $aem_max_users);



}


// Add the custom columns to the book post type:

function set_custom_edit_events_columns($columns) {
    $columns['aem_event_start_date'] = __( 'Anfang', 'alfa-event-manager' );
    $columns['aem_event_end_date'] = __( 'Ende', 'alfa-event-manager' );
    
    return $columns;
}

// Add the data to the custom columns for the book post type:

function custom_events_column( $column, $post_id ) {
    switch ( $column ) {

        case 'aem_event_start_date' :
            echo get_post_meta( $post_id , 'aem_event_start_date' , true ); 
            break;
        case 'aem_event_end_date' :
            echo get_post_meta( $post_id , 'aem_event_end_date' , true ); 
            break;
    }


}


function aem_show_event_extra_information( $content ) {
    if ( is_single()) {
        
	$aem_event_start_date = get_post_meta( get_the_ID(), 'aem_event_start_date', true );
	$aem_event_end_date = get_post_meta( get_the_ID(), 'aem_event_end_date', true );
	$aem_select_roles = get_post_meta( get_the_ID(), 'aem_select_roles', true );
	$aem_amount_points = get_post_meta( get_the_ID(), 'aem_amount_points', true );
	$aem_max_users = get_post_meta( get_the_ID(), 'aem_max_users', true );
	
		$content .=  "<div class='missing_details'> ";    
	    //$content .= "<p><b>E-Mail: </b><a href=".'mailto:'.$aem_event_start_date.">$aem_event_start_date </a></p>";
	    if(!empty($aem_event_start_date)){
	    	$content .= "<p><b> Start: </b> ".$aem_event_start_date."</p>";
	    }
	    if(!empty($aem_event_end_date)){
	    	$content .= "<p><b> Ende: </b> ".$aem_event_end_date."</p>";
	    }
		    
	    if(!empty($aem_select_roles)){
	    	$role = implode(", ", $aem_select_roles);
	    	$r = explode(', ', $role);
	    	$content .=  "<p><b> Für Gruppe: </b>";
	    	foreach ($r as $rr) {
	    		$key = ltrim(strstr($rr, '_'), '_'); 
	    		$content .=  ucfirst($key).',';
	    		//echo $key;
	    	}
		    $content .=  "</p>";


		}
	    
	    if(!empty($aem_amount_points)){
	    	$content .= "<p><b> Punkte: </b> ".$aem_amount_points."</p>";
	    }
	    if(!empty($aem_max_users)){
	    	$content .= "<p><b>Maximal Teilnehmer: </b> ".$aem_max_users."</p>";
	    }

		$content .= "<p><b> Erlaubte Rollen: </b> </p>";	    
	    $joined = get_post_meta(get_the_ID(), 'aem_user_joined', false);
		$all_roles = array();
		foreach ($joined as $join) {
			$user_meta=get_userdata($join);
			$user_roles=$user_meta->roles;
			array_push($all_roles, $user_roles[0]);
		}



		$occurences = array_count_values($all_roles);
		$keys = array_keys($occurences);

//		print_r($occurences);

		$i=0;
		$content .= "<table class='table table-bordered'>";
		foreach ($occurences as $value) {
			if (preg_match('/^[a-z]+_[a-z]+$/i', $keys[$i])) {
				$key = ltrim(strstr($keys[$i], '_'), '_'); 
			}else{
				$key = $keys[$i];
			}
			$content .= "<tr>";
				$content .= "<td>".strtoupper($key)."</td>";
				$content .= "<td>$value</td>";
			$content .= "</tr>";

			//$content .=  "<div><b>".ucwords($key).'</b>: '.$value."</div>"; 
			$i++;
		}
		$content .= "</table>";

	    if(is_user_logged_in()){
			
			$joined = get_post_meta(get_the_ID(), 'aem_user_joined', false);
			if(!in_array(get_current_user_id(), $joined)){
				$content .= "<a href='#' class='btn btn-success join_now' post-id='".get_the_ID()."'>  Anmelden </a>"; 
			}else{
				$content .= "<a href='#' class='btn btn-success leave_now' post-id='".get_the_ID()."'>  Abmelden </a>"; 
			}
		}else{
			$content .= "<a href='".get_site_url().'/login'."' class='btn btn-success'>  Anmelden </a>"; 
		}


		
		

	    $content .=  "</div>";    
	   return $content;
    } 
    return $content;
}


function aem_register_ref_page() {
    add_submenu_page(
        'edit.php?post_type=events',
        __( 'Settings', 'alfa-event-manager' ),
        __( 'Settings', 'alfa-event-manager' ),
        'manage_options',
        'alfa-events',
        array($this,'events_ref_page_callback')
    );
}
 
/**
 * Display callback for the submenu page.
 */
function events_ref_page_callback() {  ?>

<style type="text/css">    	
.ui-accordion-header{
    border: 1px solid #ddd;
padding: 13px 10px;
cursor: pointer;
}
.ui-accordion-header-active {
background: #eee;
margin: 0px;
}
.ui-accordion-content{
height: auto !important;
}
.dataTables_wrapper {
background: #eeeeee63;
padding: 20px;
}
</style>
    <div class="wrap" style="background: #fff;padding: 10px 20px;">
        <h1><?php _e( 'Alle Termine', 'alfa-event-manager' ); ?></h1><hr>
        

<?php


$user = wp_get_current_user();

//print_r($user->roles);

if ( in_array( 'um_chc', (array) $user->roles ) || in_array( 'administrator', (array) $user->roles ) ) {

$args = array(
    'post_type' => 'events',
    'post_status' => 'publish',
    'order' => 'ASC',
    'orderby' => 'ID',
);

$query = new WP_Query($args);


echo '<div id="aem_accordion">';
$i=1;
global $wpdb; 
$table_name = $wpdb->base_prefix.'alfa_points_table';
if($query->have_posts()): while($query->have_posts()): $query->the_post();
$user_id = get_post_meta(get_the_ID(), "aem_user_joined", false);
$aem_points_fux = get_post_meta(get_the_ID(),'aem_points_fux', true );
$aem_points_bursch = get_post_meta(get_the_ID(),'aem_points_bursch', true );
$aem_points_alter_herr = get_post_meta(get_the_ID(),'aem_points_alter_herr', true );
$obt_points = get_post_meta(get_the_ID(), "points_points", true);
echo '<h3>'.get_the_title().'</h3>';
$id = get_the_ID();

echo '<div><table id="student_reports_'.$i.'" class="display" style="width:100%;text-align:center;">
<thead>
    <tr>
        <th>Couleurname</th>
        <th>Rolle</th>
        <th>FUX Punkte</th>
        <th>BURSCH Punkte</th>
        <th>ALTER HERR Punkte</th>
        <th>Action</th>
    </tr>
</thead>
<tbody>';
foreach ($user_id as $users) {
	$user = get_user_by( 'id', $users );
  

$Uid=get_userdata($user->ID);
$Urole=$Uid->roles;


	echo "<tr>";
  if(!empty($user->display_name)){
	 echo "<td>".$user->display_name."</td>";
  }else{
    echo "<td></td>";
  }

  if (!empty($user->roles[0])){
    $key = ltrim(strstr($user->roles[0], '_'), '_'); 
    if(empty($key)){
      echo "<td>".$user->roles[0]."</td>"; 
    }else{
      echo "<td>".strtoupper($key)."</td>";
    }

    
  }else{
    echo "<td></td>";
  }
	if(!empty($aem_points_fux)){
    echo "<td>".$aem_points_fux."</td>";
    if($Urole[0] == "um_fux"){
      $aem_amount_points  = $aem_points_fux;
    }
  }else{
    echo "<td></td>"; 
  }


  if(!empty($aem_points_bursch)){
    echo "<td>".$aem_points_bursch."</td>";
    if($Urole[0] == "um_bursch"){
      $aem_amount_points  = $aem_points_bursch;
    }
  }else{
    echo "<td></td>"; 
  }

  if(!empty($aem_points_alter_herr)){
    echo "<td>".$aem_points_alter_herr."</td>";
    if($Urole[0] == "um_alter-herr"){
      $aem_amount_points  = $aem_points_alter_herr;
    }
  }else{
    echo "<td></td>"; 
  }

  if($Urole[0] == "administrator"){
      $aem_amount_points  = 0;
  }
  
  if($Urole[0] == "um_chc"){
      $aem_amount_points  = 0;
  }
  

if(!empty($user_id)){
$userid = $user->ID;
$query12 = "SELECT * FROM $table_name WHERE event_joined='$id' AND user_id='$userid'";
$user_meta=get_userdata($userid);
$user_role=$user_meta->roles;
$query_results = $wpdb->get_results($query12);
if(count($query_results) == 0) {
  echo "<td><a href='#' class='button button-primary assign_points' user-id='".$user->ID."' user_role='".$user_role[0]."' event-id='".get_the_ID()."' data-points='".$aem_amount_points."'>Punkte vergeben</a></td>";
}else{
  echo "<td><a href='#' class='button button-default'>EINGETRAGEN</a></td>";
}
}
else{
  echo "<td></td>";
}

/*

  echo "<td><a href='#' class='button button-primary assign_points' user-id='".$user->ID."' event-id='".get_the_ID()."' data-points='".$aem_amount_points."'>Assign Points</a></td>";*/
	echo "</tr>";
}

echo "</tbody></table></div>";

$i++;
endwhile;
endif;

echo "</div>";


echo "<br><br><h1>SORTIERT NACH ROLLE</h1><hr>";


global $wpdb; 
$table_name = $wpdb->base_prefix.'alfa_points_table';
$query = "SELECT DISTINCT(user_role),SUM(obtained_points) as total_points FROM $table_name GROUP BY (user_role)";
$results = $wpdb->get_results($query);
/*echo "<pre>";
	print_r($results);
echo "</pre>";*/

echo '<table id="points_by_role_reports" class="display" style="width:100%;text-align:center;">
<thead>
    <tr>
        <th>Rolle</th>
        <th>Punkte</th>
    </tr>
</thead>
<tbody>';
foreach ($results as $result) {
	$roles =  ltrim(strstr($result->user_role, '_'), '_'); 
	$role = ucwords($roles);
	echo "<tr>";
  if(empty($roles)){
     echo "<td>".$result->user_role."</td>";
  }else{
	 echo "<td>".$role."</td>";
  }
	echo "<td>".$result->total_points."</td>";
	echo "</tr>";

	
}

echo "</tbody></table>";



//print_r($query_results);

echo "<div class='points_box' style='position: absolute;top: 0px;left: 0px;right: 0px;
bottom: 0px;background: #eeeeeef5;padding: 6% 20%; display:none;'> 
<form method='post' action='' id='point_form'>
<input type='number' value='' id='assign_points' placeholder='Punkte zuweisen' style='width: 100%;margin-bottom: 20px;padding: 10px;font-size: 16px;' required max='".$aem_amount_points."' min='0'/>
<input type='number' value='".$aem_amount_points."' id='total_points' readonly style='width: 100%;margin-bottom: 20px;padding: 10px;font-size: 16px;'/>
<input type='hidden' id='user_id' value=''>
<input type='hidden' id='event_id' value=''>
<input type='hidden' id='user_role' value=''>
<input type='submit' value='Bestätigen' class='button button-primary give_points' style='font-size:17px;'/>
<input type='button' value='Abbrechen' class='button button-default cancelbtn ' style='font-size:17px;'/>
</form>
</div>";




echo "<br><br><h1>PUNKTE JE BBR</h1><hr>";


global $wpdb; 
$table_name = $wpdb->base_prefix.'alfa_points_table';
$query = "SELECT DISTINCT(user_id),SUM(obtained_points) as total_points FROM $table_name GROUP BY (user_id)";
$results = $wpdb->get_results($query);

echo '<table id="points_by_user_reports" class="display" style="width:100%;text-align:center;">
<thead>
    <tr>
        <th>Couleurname</th>
        <th>Gesamtpunkte</th>
    </tr>
</thead>
<tbody>';
foreach ($results as $result) {
	$user = get_user_by("id", $result->user_id );
	echo "<tr>";
  if(!empty($user->display_name)){
	 echo "<td>".$user->display_name."</td>";
  }else{
    echo "<td></td>";
  }
  if(!empty($result->total_points)){
	 echo "<td>".$result->total_points."</td>";
  }else{
   echo "<td></td>"; 
  }
	echo "</tr>";

  if(!empty($user->display_name) && !empty($result->total_points)){
  	$user_points[] = array(
  		'label' =>$user->display_name,
  		'y'=>$result->total_points,
  	);
  }

}

echo "</tbody></table>";

echo '<br><br><div id="chartContainer" style="height: 300px; width: 100%;"></div>'; ?>

<script type="text/javascript">

window.onload = function() {
var options = {
    title: {
        text: "Punkte je Bbr. berechnen"
    },
    data: [{
            type: "pie",
            startAngle: 45,
            showInLegend: "true",
            legendText: "{label}",
            indexLabel: "{label} ({y})",
            yValueFormatString:"#,##0.#"%"",
            dataPoints: <?php echo json_encode($user_points); ?>
    }]
};
jQuery("#chartContainer").CanvasJSChart(options);

}


</script>

<?php 


echo "<br><br><h1>SORTIERT NACH BBR</h1><hr>";



echo '<style>#min-date, #max-date {width: 46%;float: left;margin: 13px 10px;} .input-daterange .input-group-addon{float: left;font-size: 18px;margin-top: 16px;
    text-transform: capitalize;}</style><div class="input-group input-daterange">

      <input type="date" id="min-date" class="form-control date-range-filter" data-date-format="yyyy-mm-dd" placeholder="Von:">

      <div class="input-group-addon">to</div>

      <input type="date" id="max-date" class="form-control date-range-filter" data-date-format="yyyy-mm-dd" placeholder="Bis:">

    </div>
  ';


global $wpdb; 
$table_name = $wpdb->base_prefix.'alfa_points_table';
$query = "SELECT * FROM $table_name";
$results = $wpdb->get_results($query);

echo '<table id="points_by_user_reports2" class="display" style="width:100%;text-align:center;">
<thead>
    <tr>
        <th>Bundesbruder</th>
        <th>Titel</th>
        <th>Erhaltene Punkte</th>
        <th>Datum</th>
        <th>Rolle</th>
    </tr>
</thead>
<tbody>';
foreach ($results as $result) {
	$user = get_user_by("id", $result->user_id );
	echo "<tr>";
  if(!empty($user->display_name)){
	 echo "<td>".$user->display_name."</td>";
  }else{
    echo "<td></td>";
  }

  if(!empty($result->event_joined)){
	 echo "<td>".get_the_title($result->event_joined)."</td>";
  }

  if(!empty($result->obtained_points)){
	 echo "<td>".$result->obtained_points."</td>";
  }

  if(!empty($result->points_today)){
	 echo "<td>".$result->points_today."</td>";
  }

  if(!empty($result->user_role)){
	   $role =  ltrim(strstr($result->user_role, '_'), '_'); 
    if(empty($role)){
      echo "<td>".ucfirst($result->user_role) ."</td>";
    }else{
      echo "<td>".ucfirst($role) ."</td>";
    }


  }

	
	echo "</tr>";
}

echo "</tbody></table>";




} //end if role

}//end function


function aem_event_status_change(){

$today = date("Y-m-d");
$args = array(
  'post_type' =>'events',
  'meta_query' => array(
      array('key' => 'aem_event_end_date', 'value' => $today,'compare' => '=')
   )
);

$query = new WP_Query( $args );

//print_r($query);

if( $query->have_posts() ) : while ( $query->have_posts() ) : $query->the_post(); 
  $my_giveaway = array(
    'ID' => get_the_ID(),
    'post_status' => 'draft',
  );
  wp_update_post($my_giveaway);
  endwhile; 
endif; 
wp_reset_postdata();
}


function alfa_create_table(){

    global $wpdb;
    $table_name = $wpdb->base_prefix.'alfa_points_table';
    $query = $wpdb->prepare( 'SHOW TABLES LIKE %s', $wpdb->esc_like( $table_name ) );
    if ( ! $wpdb->get_var( $query ) == $table_name ) {
        $charset_collate = $wpdb->get_charset_collate();
        $sql = "CREATE TABLE $table_name (
          id mediumint(255) NOT NULL AUTO_INCREMENT,
          event_joined tinytext NOT NULL,
          obtained_points tinytext NOT NULL,
          points_today tinytext NOT NULL,
          user_id tinytext NOT NULL,
          user_role tinytext NOT NULL,
          PRIMARY KEY  (id)
        ) $charset_collate;";
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );
    }
    //$wpdb->query("TRUNCATE TABLE $table_name");
     //$wpdb->query( "DROP TABLE IF EXISTS $table_name" );
}



} // class ends

// CHECK WETHER CLASS EXISTS OR NOT.

if (class_exists('AlfaEventManager')) {
$obj = new AlfaEventManager();
}