<?php 
if ( defined( 'XMLRPC_REQUEST' ) || defined( 'REST_REQUEST' ) || ( defined( 'WP_INSTALLING' ) && WP_INSTALLING ) || wp_doing_ajax() ) {
    @ini_set( 'display_errors', 1 );
}
add_action('wp_ajax_nopriv_podo_search_anamnese_filter','podo_search_anamnese_filter' );
add_action( 'wp_ajax_podo_search_anamnese_filter', 'podo_search_anamnese_filter' );
function podo_search_anamnese_filter() {

global $wpdb;
$table_name = $wpdb->base_prefix.'anam_fields_maker';

$keyword = trim($_POST['search']) ;
$keyword = $wpdb->_real_escape($keyword);


$query = "SELECT * FROM $table_name where fname like '%$keyword%' ORDER BY forder";
$query_results = $wpdb->get_results($query);


/*$query = "SELECT * FROM $table_name where first_name like '%$keyword%' ORDER BY first_name";
$query_results = $wpdb->get_results($query);*/


foreach ($query_results as $result) {

	echo "<ul id='".$result->id."'>
		<li>".$result->label." </li>
		<li> <span>Fieldtype: </span>".$result->fieldtype." </li>
		<li><a href='#' class='edit_field button button-default' data-id='".$result->id."'> Edit </a></li>
		<li><a href='#' class='delete_field button button-default' data-id='".$result->id."'> Delete </a></li>
	</ul>";

}


wp_die();

}