<?php 
if ( defined( 'XMLRPC_REQUEST' ) || defined( 'REST_REQUEST' ) || ( defined( 'WP_INSTALLING' ) && WP_INSTALLING ) || wp_doing_ajax() ) {
    @ini_set( 'display_errors', 1 );
}
add_action('wp_ajax_nopriv_podo_search_customer_filter','podo_search_customer_filter' );
add_action( 'wp_ajax_podo_search_customer_filter', 'podo_search_customer_filter' );
function podo_search_customer_filter() {

global $wpdb;
$table_name = $wpdb->base_prefix.'anam_customer_info';

$keyword = trim($_POST['search']) ;
$keyword = $wpdb->_real_escape($keyword);

$query = "SELECT * FROM $table_name where first_name like '%$keyword%' ORDER BY first_name";
$query_results = $wpdb->get_results($query);


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


wp_die();

}