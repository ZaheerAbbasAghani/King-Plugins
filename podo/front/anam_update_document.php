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
	extract($dinfo);
	$time = current_time( 'mysql' );

	array_pop($dinfo);

//	print_r($dinfo);

	$where = array("id" => $doc_id);
	$updated = $wpdb->update( $table_name, $dinfo, $where);

	if ( false === $updated ) {
	    $response = array("message" => "Entschuldigung, da ist etwas schief gelaufen! ".$gangrän, "status" => 0);
		echo wp_send_json($response);
	} else {
	   $response = array("message" => "Kundendatensatz erfolgreich aktualisiert.", "status" => 1);
		echo wp_send_json($response);
	}

/*
    $info = $wpdb->query($wpdb->prepare("UPDATE $table_name SET antikoagulation='$antikoagulation',weitere_medikamente='$weitere_medikamente',allergien='$allergien',apoplexie='$apoplexie',paresen='$paresen',dermatologische_erkrankungen='$dermatologische_erkrankungen',ulcus='$ulcus',diabetes='$diabetes',neuropathie='$neuropathie',gicht='$gicht',varizen='$varizen',pavk='$pavk',thrombose='$thrombose',cvi='$cvi', phlebitis='$phlebitis',oedem='$oedem',rheumatioide_arthritis_ra='$rheumatioide_arthritis_ra',arthrose='$arthrose',virale_erkrankungen='$virale_erkrankungen',gangran='$gangrän',stoffwechselerkrankungen='$stoffwechselerkrankungen',gefasserkrankungen='$gefässerkrankungen',gelenkerkrankungen='$gelenkerkrankungen',operationen='$operationen',orthesen='$orthesen',orthonyxie='$orthonyxie',nagelprotetik='$nagelprotetik',senkfuss_des_lw='$senkfuss_des_lw',knickung_des_valgus='$knickung_des_valgus',Senkung_des_qw='$Senkung_des_qw',spreizung_hv_hr_qv='$spreizung_hv_hr_qv',hohlfuss='$hohlfuss',hammerzehen='$hammerzehen',krallenzehen='$krallenzehen',beschwerden_schmerzen='$beschwerden_schmerzen',hyperkreatose='$hyperkreatose',rhagaden='$rhagaden',malum_perforans='$malum_perforans',Panaritium='$Panaritium',psoriasis='$psoriasis',navus='$nävus',clavus_clavi='$clavus_clavi',clavus_im_nagelfalz='$clavus_im_nagelfalz',clavus_interdig_subung='$clavus_interdig_subung',verruca='$verruca',bursitis='$bursitis',dermatomykose='$dermatomykose',onychokryptose='$onychokryptose',onychoschisis='$onychoschisis',onychomykose='$onychomykose',onychauxis='$onychauxis',onycholyse='$onycholyse',onychogryposis='$onychogryposis',onychopathie='$onychopathie',user_id='$user_id',doctor_id='$doctor_id',updated_at='$time' WHERE id=$doc_id"));
    if($info == true){
    	$response = array("message" => "Kundendatensatz erfolgreich aktualisiert.", "status" => 1);
		echo wp_send_json($response);
    }else{
    	$response = array("message" => "Entschuldigung, da ist etwas schief gelaufen! ".$gangrän, "status" => 0);
		echo wp_send_json($response);
    }*/


	wp_die();
}