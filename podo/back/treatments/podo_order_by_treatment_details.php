<?php 
if ( defined( 'XMLRPC_REQUEST' ) || defined( 'REST_REQUEST' ) || ( defined( 'WP_INSTALLING' ) && WP_INSTALLING ) || wp_doing_ajax() ) {
    @ini_set( 'display_errors', 1 );
}
add_action('wp_ajax_nopriv_podo_order_by_treatment_details','podo_order_by_treatment_details' );
add_action( 'wp_ajax_podo_order_by_treatment_details', 'podo_order_by_treatment_details' );
function podo_order_by_treatment_details() {

global $wpdb;
$table_name = $wpdb->base_prefix.'anam_treatments_list';

$filter = $_POST['filter'];


if($filter == 1){
	$query = "SELECT * FROM $table_name ORDER BY name DESC";
	$query_results = $wpdb->get_results($query);
	//print_r($query_results);
	foreach ($query_results as $result) { ?>
		<ul class='box edit_treatment' data-id='<?php echo $result->id; ?>' onclick="jQuery('#extruderLeft2').openMbExtruder(true);jQuery('#extruderLeft2').openPanels()">
			<span class="dashicons dashicons-admin-post"></span>
			<li><?php echo $result->name; ?></li>
			<li> Dauer: <?php echo $result->duration; ?></li>
			<li> Kosten: <?php echo $result->price.' '.$currency; ?> </li>
		</ul>
	<?php 
	}

}

// DESC Customer Name
if($filter == 2){
	$query = "SELECT * FROM $table_name ORDER BY price DESC";
	$query_results = $wpdb->get_results($query);
	//print_r($query_results);
	foreach ($query_results as $result) { ?>
		<ul class='box edit_treatment' data-id='<?php echo $result->id; ?>' onclick="jQuery('#extruderLeft2').openMbExtruder(true);jQuery('#extruderLeft2').openPanels()">
			<span class="dashicons dashicons-admin-post"></span>
			<li><?php echo $result->name; ?></li>
			<li> Dauer: <?php echo $result->duration; ?></li>
			<li> Kosten: <?php echo $result->price.' '.$currency; ?> </li>
		</ul>
	<?php 
	}
}


// DESC Date ASC
if($filter == 3){
		$query = "SELECT * FROM $table_name ORDER BY duration DESC";
	$query_results = $wpdb->get_results($query);
	//print_r($query_results);
	foreach ($query_results as $result) { ?>
		<ul class='box edit_treatment' data-id='<?php echo $result->id; ?>' onclick="jQuery('#extruderLeft2').openMbExtruder(true);jQuery('#extruderLeft2').openPanels()">
			<span class="dashicons dashicons-admin-post"></span>
			<li><?php echo $result->name; ?></li>
			<li> Dauer: <?php echo $result->duration; ?></li>
			<li> Kosten: <?php echo $result->price.' '.$currency; ?> </li>
		</ul>
	<?php 
	}
}


wp_die();

}