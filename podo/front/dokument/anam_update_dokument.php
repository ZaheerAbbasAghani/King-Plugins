<?php 
if ( defined( 'XMLRPC_REQUEST' ) || defined( 'REST_REQUEST' ) || ( defined( 'WP_INSTALLING' ) && WP_INSTALLING ) || wp_doing_ajax() ) {
    @ini_set( 'display_errors', 1 );
}

add_action( 'wp_ajax_nopriv_anam_update_dokument', 'anam_update_dokument' );
add_action( 'wp_ajax_anam_update_dokument', 'anam_update_dokument' );
function anam_update_dokument() {
	global $wpdb;
    $table_name = $wpdb->base_prefix.'anam_dokument_info';

	$dokinfo = array();
	parse_str($_POST['dok_info'], $dokinfo);
	extract($dokinfo);

    //print_r($dokinfo);
    $currency = get_option('podo_currency');
    $price = str_replace($currency, ' ', $tprice);
//    echo $price;
    $time = current_time( 'mysql' );
    $info = $wpdb->query($wpdb->prepare("UPDATE $table_name SET treatment_name='$search_treatments', price='$price',addition_information='$addition_information',payment_methods='$payment_methods',status='$payment_status',email_pdf='$email_pdf',updated_at='$time' WHERE customer_id='$user_id' AND doctor_id='$doctor_id' AND id='$doc_id'" ));


        if($info == 1){
        	$response = array("message" => "Dokument erfolgreich aktualisiert.", "status" => 1);
    		echo wp_send_json($response);
        }else{
        	$response = array("message" => "Entschuldigung, da ist etwas schief gelaufen! ".$gangrän, "status" => 0);
    		echo wp_send_json($response);
        }


	wp_die();
}