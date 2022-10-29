<?php 

function afi_referrer_shortcode_maker( $atts="") {
  
	$user_id = get_current_user_id();
  	$ref = get_user_meta($user_id, 'afi_reffered_by', true );
  	if(!empty($ref)){
  	

  	global $wpdb; 
    $table_name = $wpdb->base_prefix.'fields_list';
    $query = "SELECT * FROM $table_name";
    $query_results = $wpdb->get_results($query);

	$args= array(
	  'search' => $ref,
	  'search_fields' => array('user_login','user_nicename','display_name')
	);
	$user = new WP_User_Query($args);

		if ( ! empty( $user->get_results() ) ) {
			foreach ($user->get_results() as $user ) {

				$a = $atts[0];
				$url = get_user_meta($user->ID, $a, true);
				if(empty($url)){
					$uurl = get_user_meta(1, $a, true);
				}else{
					$uurl = get_user_meta($user->ID, $a, true);
				}
				return $uurl;
			}
		}
 
  	}else{
  		return "<p> Refferer Not Found! </p>";
  	}

 
}

add_shortcode('fetch_custom_field_referrer', 'afi_referrer_shortcode_maker');