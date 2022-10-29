<?php 


function file_get_contents_curl($url) {
  $ch = curl_init($url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
  curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
  $data = curl_exec($ch);
  curl_close($ch);
  return $data;
}

add_action( 'wp_ajax_dix_manually_import', 'dix_manually_import' );
add_action( 'wp_ajax_nopriv_dix_manually_import', 'dix_manually_import' );
function dix_manually_import() {

	$url = esc_attr(get_option('dix_import_url'));
	$username = esc_attr(get_option('dix_username'));
	$password = esc_attr(get_option('dix_password'));
	$wp_upload_dir =  wp_upload_dir();

	global $wpdb; 
	$table_name = $wpdb->base_prefix.'dix_imported_data';

	if(!empty($url) && !empty($username) && !empty($password) && $_POST['import'] == 1){

		$args = array(
		  'headers' => array(
		    'Authorization' => 'Basic ' . base64_encode( $username . ':' . $password )
		  )
		);
		$request=wp_remote_request( $url, $args );

		if( is_wp_error( $request ) ) {
		    return false; // Bail early
		}

		$body = wp_remote_retrieve_body( $request );
		$data = json_decode( $body );

		foreach ($data  as $key => $value) {
		    $id =  $value->entity_id;
		    $name =  $value->name;
		    $image = $value->image;
		    $url = $value->url;
		    $item_type = $value->product_margin_type;
		    $item_badge = $value->badge;
	
		 	$query = "SELECT * FROM $table_name WHERE item_id='$id'";
			$query_results = $wpdb->get_results($query);
			if(count($query_results) == 0) {

				if(!empty($id) && !empty($name) && !empty($image)){


					$rawImage = dirname(__FILE__) . '/DixImages/' . basename($image);
					$relativePath =  plugin_dir_url( __FILE__ ).'/DixImages/'.basename($image);

					if($rawImage)
					{	
						$contentOrFalseOnFailure = file_get_contents_curl($image);
						file_put_contents($rawImage, $contentOrFalseOnFailure);
					}
					
					$rowResult=$wpdb->insert($table_name, array("item_id" => $id, "item_name" => $name, "item_image_url" => $relativePath, "item_url" => $url, "item_type" => $item_type,"item_badge"=>$item_badge),array("%s","%s","%s","%s","%s","%s"));
					echo "<p> $name  <span style='color:green;'> Added </span></p> \n";	
				}
			}else{

					echo "<p> $name <span style='color:red;'>already in database </span></p> \n";	
			}

		}
	}
	else{
		echo "Something went wrong";
	}

	wp_die();
}