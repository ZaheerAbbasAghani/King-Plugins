<?php 
if ( defined( 'XMLRPC_REQUEST' ) || defined( 'REST_REQUEST' ) || ( defined( 'WP_INSTALLING' ) && WP_INSTALLING ) || wp_doing_ajax() ) {
    @ini_set( 'display_errors', 1 );
}
add_action('wp_ajax_nopriv_podo_order_by_filter','podo_order_by_filter' );
add_action( 'wp_ajax_podo_order_by_filter', 'podo_order_by_filter' );
function podo_order_by_filter() {


global $wpdb;
$table_name = $wpdb->base_prefix.'anam_customer_info';
$filter = $_POST['filter'];

// ASC Customer Name
if($filter == 1){
	$query = "SELECT * FROM $table_name ORDER BY first_name ASC";
	$query_results = $wpdb->get_results($query);
	//print_r($query_results);

	foreach ($query_results as $result) {
		$table_name2 = $wpdb->base_prefix.'anam_dokument_info';
		$query2 = "SELECT * FROM $table_name2 WHERE customer_id='$result->id' ORDER BY created_at DESC LIMIT 1";
		$query_results2 = $wpdb->get_results($query2);

		$query3 = "SELECT count(*) as total_docs FROM $table_name2 WHERE customer_id='$result->id' ORDER BY created_at ";
		$query_results3 = $wpdb->get_results($query3);

		echo '<ul>
			<li>'.$result->first_name.' '.$result->last_name.'</li>
			<li>'.$result->mobile_no.'</li>
			<li>'.wp_trim_words($result->important_notes, 3, '...').'</li>
			<li>'.$query_results2[0]->created_at.'</li>
			<li><a href="#" class="edit_customer_dashboard button button-primary" data-id="'.$result->id.'"> Edit </a></li>	
			<div class="extended_bar" style="display:none;">
				<div class="appointments">
					<p> All Appointments: '.$query_results3[0]->total_docs.' </p>
					<p> All Appointments: '.$query_results2[0]->created_at.'</p>
				</div>
				<div class="appointments">
					<p> Note: '.$result->important_notes.'</p>
				</div>
			</div>
		</ul>';
	}
}

// DESC Customer Name
if($filter == 2){
	$query = "SELECT * FROM $table_name ORDER BY first_name DESC";
	$query_results = $wpdb->get_results($query);
	//print_r($query_results);

	foreach ($query_results as $result) {
		$table_name2 = $wpdb->base_prefix.'anam_dokument_info';
		$query2 = "SELECT * FROM $table_name2 WHERE customer_id='$result->id' ORDER BY created_at DESC LIMIT 1";
		$query_results2 = $wpdb->get_results($query2);

		$query3 = "SELECT count(*) as total_docs FROM $table_name2 WHERE customer_id='$result->id' ORDER BY created_at ";
		$query_results3 = $wpdb->get_results($query3);

		echo '<ul>
			<li>'.$result->first_name.' '.$result->last_name.'</li>
			<li>'.$result->mobile_no.'</li>
			<li>'.wp_trim_words($result->important_notes, 3, '...').'</li>
			<li>'.$query_results2[0]->created_at.'</li>
			<li><a href="#" class="edit_customer_dashboard button button-primary" data-id="'.$result->id.'"> Edit </a></li>	
			<div class="extended_bar" style="display:none;">
				<div class="appointments">
					<p> All Appointments: '.$query_results3[0]->total_docs.' </p>
					<p> All Appointments: '.$query_results2[0]->created_at.'</p>
				</div>
				<div class="appointments">
					<p> Note: '.$result->important_notes.'</p>
				</div>
			</div>
		</ul>';
	}
}


// DESC Date ASC
if($filter == 3){
	$query = "SELECT * FROM $table_name ORDER BY first_name ASC";
	$query_results = $wpdb->get_results($query);
	//print_r($query_results);

	foreach ($query_results as $result) {
		$table_name2 = $wpdb->base_prefix.'anam_dokument_info';
		$query2 = "SELECT * FROM $table_name2 WHERE customer_id='$result->id' ORDER BY created_at DESC LIMIT 1";
		$query_results2 = $wpdb->get_results($query2);

		$query3 = "SELECT count(*) as total_docs FROM $table_name2 WHERE customer_id='$result->id' ORDER BY created_at ";
		$query_results3 = $wpdb->get_results($query3);

		echo '<ul>
			<li>'.$result->first_name.' '.$result->last_name.'</li>
			<li>'.$result->mobile_no.'</li>
			<li>'.wp_trim_words($result->important_notes, 3, '...').'</li>
			<li>'.$query_results2[0]->created_at.'</li>
			<li><a href="#" class="edit_customer_dashboard button button-primary" data-id="'.$result->id.'"> Edit </a></li>	
			<div class="extended_bar" style="display:none;">
				<div class="appointments">
					<p> All Appointments: '.$query_results3[0]->total_docs.' </p>
					<p> All Appointments: '.$query_results2[0]->created_at.'</p>
				</div>
				<div class="appointments">
					<p> Note: '.$result->important_notes.'</p>
				</div>
			</div>
		</ul>';
	}
}


// DESC Date DESC
if($filter == 4){
	$query = "SELECT * FROM $table_name ORDER BY first_name DESC";
	$query_results = $wpdb->get_results($query);
	//print_r($query_results);

	foreach ($query_results as $result) {
		$table_name2 = $wpdb->base_prefix.'anam_dokument_info';
		$query2 = "SELECT * FROM $table_name2 WHERE customer_id='$result->id' ORDER BY created_at DESC LIMIT 1";
		$query_results2 = $wpdb->get_results($query2);

		$query3 = "SELECT count(*) as total_docs FROM $table_name2 WHERE customer_id='$result->id' ORDER BY created_at ";
		$query_results3 = $wpdb->get_results($query3);

		echo '<ul>
			<li>'.$result->first_name.' '.$result->last_name.'</li>
			<li>'.$result->mobile_no.'</li>
			<li>'.wp_trim_words($result->important_notes, 3, '...').'</li>
			<li>'.$query_results2[0]->created_at.'</li>
			<li><a href="#" class="edit_customer_dashboard button button-primary" data-id="'.$result->id.'"> Edit </a></li>	
			<div class="extended_bar" style="display:none;">
				<div class="appointments">
					<p> All Appointments: '.$query_results3[0]->total_docs.' </p>
					<p> All Appointments: '.$query_results2[0]->created_at.'</p>
				</div>
				<div class="appointments">
					<p> Note: '.$result->important_notes.'</p>
				</div>
			</div>
		</ul>';
	}
}



wp_die();

}