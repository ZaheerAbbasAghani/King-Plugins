<?php 
if ( defined( 'XMLRPC_REQUEST' ) || defined( 'REST_REQUEST' ) || ( defined( 'WP_INSTALLING' ) && WP_INSTALLING ) || wp_doing_ajax() ) {
    @ini_set( 'display_errors', 1 );
}

add_action( 'wp_ajax_nopriv_anam_update_document', 'anam_update_document' );
add_action( 'wp_ajax_anam_update_document', 'anam_update_document' );
function anam_update_document() {
	global $wpdb;
    $table_name = $wpdb->base_prefix.'anam_document_info';

	$dinfo = array();
	parse_str($_POST['customer_info'], $dinfo);

	$senkfuss 				= $dinfo['senkfuss'];
	$spreizfuss 			= $dinfo['spreizfuss'];
	$knickfuss_nach_innen 	= $dinfo['knickfuss_nach_innen'];
	$knickfuss_nach_aussen 	= $dinfo['knickfuss_nach_aussen'];
	$hohlfuss 				= $dinfo['hohlfuss'];
	$plattfuss 				= $dinfo['plattfuss'];
	$fusschwellung 			= $dinfo['fusschwellung'];
	$other_foot_deformation = $dinfo['other_foot_deformation'];
	$oberschenkel 		= $dinfo['oberschenkel'];
	$unterschenkel 			= $dinfo['unterschenkel'];
	$konfektion 		= $dinfo['konfektion'];
	$nach_mass 	= $dinfo['nach_mass'];
	$risks 		= serialize($dinfo['risks']);
	$infektionskrankheiten= $dinfo['infektionskrankheiten'];
	$findings 			= serialize($dinfo['findings']);
	$wunden = $dinfo['wunden'];
	$huhneraugen_auf_zehen= $dinfo['huhneraugen_auf_zehen'];
	$hammerzehen = $dinfo['hammerzehen'];
	$nagelpilz = $dinfo['nagelpilz'];
	$eingewachsene_nagel = $dinfo['eingewachsene_nagel'];
	$zustand_de_nagel = $dinfo['zustand_de_nagel'];
	$user_id = $dinfo['user_id'];
	$doctor_id = $dinfo['doctor_id'];
	$doc_id = $dinfo['doc_id'];

	

    $info = $wpdb->query($wpdb->prepare("UPDATE $table_name SET senkfuss='$senkfuss',spreizfuss='$spreizfuss',knickfuss_nach_innen='$knickfuss_nach_innen',knickfuss_nach_aussen='$knickfuss_nach_aussen',hohlfuss='$hohlfuss',plattfuss='$plattfuss',fusschwellung='$fusschwellung',other_foot_deformation='$other_foot_deformation',oberschenkel='$oberschenkel',unterschenkel='$unterschenkel',konfektion='$konfektion',nach_mass='$nach_mass',risks='$risks',infektionskrankheiten='$infektionskrankheiten', findings='$findings',wunden='$wunden',huhneraugen_auf_zehen='$huhneraugen_auf_zehen',hammerzehen='$hammerzehen',nagelpilz='$nagelpilz',eingewachsene_nagel='$eingewachsene_nagel',zustand_de_nagel='$zustand_de_nagel',user_id='$user_id',doctor_id='$doctor_id' WHERE id=$doc_id"));
   /* $wpdb->show_errors();
	$wpdb->print_error();*/
    if($info == true){
    	$response = array("message" => "Customer Record Updated Successfully.", "status" => 1);
		echo wp_send_json($response);
    }else{
    	$response = array("message" => "Sorry, something went wrong!", "status" => 0);
		echo wp_send_json($response);
    }


	wp_die();
}