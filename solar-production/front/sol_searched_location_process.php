<?php 

add_action( 'wp_ajax_sol_searched_location_process', 'sol_searched_location_process' );
add_action( 'wp_ajax_nopriv_sol_searched_location_process', 'sol_searched_location_process' );

function sol_searched_location_process() {

	global $wpdb; // this is how you get access to the database	
$address = $_POST['address'];
$area1 = $_POST['area1'];
$area_total = $area1 * 6.25;
$area2 = $_POST['area2'];
$area3 = $_POST['area3'];
$dirVal = $_POST['dirValue'];
$ratVal = $_POST['ratioValue'];
$sol_api_url = get_option("sol_api_url");
$apiUrl = 'https://solkollen.se/green/api/calculate2?address='.urlencode($address).'&area1='.$area_total.'&area2='.$area2.'&area3='.$area3.'&dirValue='.$dirVal.'&ratioValue='.$ratVal.'';
$response = wp_remote_get($apiUrl);
$responseBody = wp_remote_retrieve_body( $response );
$result = json_decode( $responseBody );

$table_name = $wpdb->base_prefix.'sol_pricing';
$query = "SELECT * FROM $table_name WHERE kwp_from='$area1'";
$query_results = $wpdb->get_results($query);
foreach ($query_results as $res) {
  $m2 = $res->kwp_m2;
}


if (! is_wp_error( $result ) ) { 

	 $diagram = array();

  	$month = array("January","February","March","April","May","June","July","August","September","October","November","December");
  	$i=0;
  	foreach ($result->areas->month_es_1 as $value) {
  		array_push($diagram,trim($value));  		
  		$i++;
  	}

    $response = array("month" => $month, "dia_values" => $diagram,"full" => $result->areas, "m2" => $m2);

    wp_send_json( $response );

} else {
    echo "Nothing Found!";
}


//print_r($_POST);


	wp_die(); // this is required to terminate immediately and return a proper response

}