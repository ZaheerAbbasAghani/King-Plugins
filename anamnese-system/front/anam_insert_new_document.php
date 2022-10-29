<?php 
if ( defined( 'XMLRPC_REQUEST' ) || defined( 'REST_REQUEST' ) || ( defined( 'WP_INSTALLING' ) && WP_INSTALLING ) || wp_doing_ajax() ) {
    @ini_set( 'display_errors', 1 );
}

add_action( 'wp_ajax_nopriv_anam_insert_new_document', 'anam_insert_new_document' );
add_action( 'wp_ajax_anam_insert_new_document', 'anam_insert_new_document' );
function anam_insert_new_document() {
	

global $wpdb;
$table_name = $wpdb->base_prefix.'anam_document_info';

$senkfuss 				= $_POST['senkfuss'];
$spreizfuss 			= $_POST['spreizfuss'];
$knickfuss_nach_innen 	= $_POST['knickfuss_nach_innen'];
$knickfuss_nach_aussen 	= $_POST['knickfuss_nach_aussen'];
$hohlfuss 				= $_POST['hohlfuss'];
$plattfuss 				= $_POST['plattfuss'];
$fusschwellung 			= $_POST['fusschwellung'];
$other_foot_deformation = $_POST['other_foot_deformation'];
$oberschenkel 			= $_POST['oberschenkel'];
$unterschenkel 			= $_POST['unterschenkel'];
$konfektion 			= $_POST['konfektion'];
$nach_mass 				= $_POST['nach_mass'];
$risks 					= serialize($_POST['risks']);
$infektionskrankheiten	= $_POST['infektionskrankheiten'];
$findings 				= serialize($_POST['findings']);
$wunden					= $_POST['wunden'];
$huhneraugen_auf_zehen 	= $_POST['huhneraugen_auf_zehen'];
$hammerzehen			= $_POST['hammerzehen'];
$nagelpilz				= $_POST['nagelpilz'];
$eingewachsene_nagel	= $_POST['eingewachsene_nagel'];
$zustand_de_nagel		= $_POST['zustand_de_nagel'];
$user_id				= $_POST['user_id'];
$doctor_id				= $_POST['doctor_id'];
$now = current_time('mysql');

$insert = $wpdb->query("INSERT INTO $table_name (senkfuss, spreizfuss, knickfuss_nach_innen,knickfuss_nach_aussen,hohlfuss,plattfuss,fusschwellung,other_foot_deformation,oberschenkel,unterschenkel,konfektion,nach_mass,risks,infektionskrankheiten,findings,wunden,huhneraugen_auf_zehen,hammerzehen, nagelpilz, eingewachsene_nagel, zustand_de_nagel, user_id,doctor_id, created_at) VALUES ('$senkfuss', '$spreizfuss', '$knickfuss_nach_innen','$knickfuss_nach_aussen', '$hohlfuss', '$plattfuss', '$fusschwellung', '$other_foot_deformation', '$oberschenkel', '$unterschenkel', '$konfektion', '$nach_mass', '$risks', '$infektionskrankheiten', '$findings','$wunden', '$huhneraugen_auf_zehen', '$hammerzehen', '$nagelpilz', '$eingewachsene_nagel', '$zustand_de_nagel', '$user_id', '$doctor_id', '$now')");

if($insert == 1){


		$error=array();
		$extension=array("jpeg","jpg","png","gif");
		$wp_upload_dir =  wp_upload_dir();
		$lastid = $wpdb->insert_id;
		$custom_upload_folder= $wp_upload_dir['basedir'].'/anam_users/'.$user_id.'/'.$lastid;

		if(!is_dir($custom_upload_folder)){
			mkdir($custom_upload_folder);
		}

		foreach($_FILES["upimg"]["tmp_name"] as $key=>$tmp_name) {
		    $file_name=$_FILES["upimg"]["name"][$key];
		    $file_tmp=$_FILES["upimg"]["tmp_name"][$key];
		    $ext=pathinfo($file_name,PATHINFO_EXTENSION);

		    if(in_array($ext,$extension)) {
		        if(!file_exists($custom_upload_folder."/".$file_name)) {
		            move_uploaded_file($file_tmp=$_FILES["upimg"]["tmp_name"][$key],$custom_upload_folder."/".$file_name);
		         //   echo "SUCCESS";
		        }
		        else {
		            $filename=basename($file_name,$ext);
		            $newFileName=$filename.time().".".$ext;
		             move_uploaded_file($file_tmp=$_FILES["upimg"]["tmp_name"][$key],$custom_upload_folder."/".$newFileName);
		        }
		    }
		    else {
		        array_push($error,"$file_name, ");
		    }
		}


	$response = array("message" => "New Document Created Successfully.", "status" => 1);
	echo wp_send_json($response);
}else{

	$response = array("message" => "Something went wrong!", "status" => 0);
	echo wp_send_json($response);
}


	wp_die();
}