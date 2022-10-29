<?php 

function afi_referrer_shortcode_maker( $atts="") {
    	$user_id = get_current_user_id();
	  	$ref = get_user_meta($user_id, 'refby', true );
	
		if($atts[0] == "user") {
			$current_user = get_current_user_id();
			$refby = get_user_meta($current_user,"refby", true);
			return $refby;
		}
    
 
	if(!empty($ref)){
	  		//echo $ref;
	  	global $wpdb; 
	    $table_name = $wpdb->base_prefix.'fields_list';
	    $query = "SELECT * FROM $table_name";
	    $query_results = $wpdb->get_results($query);

		$args  = array(
    		'meta_key' => 'wizz_user', //any custom field name
    		'meta_value' => $ref,
    		'meta_compare' => '='
		);
		$user = new WP_User_Query($args);
			if ( ! empty( $user->get_results() ) ) {
				foreach ($user->get_results() as $user ) {

					$a = $atts[0];
					if($a == "user"){
						$url = get_user_meta($user->ID, $a, true);
						if(empty($url)){
							$uurl = get_user_meta(1, $a, true);
						}else{
							$uurl = get_user_meta($user->ID,$a,true);
						}
						return $uurl;
					}
					elseif($a == "firstname"){

							$url = get_userdata($user->ID);
							if(!empty($url)){
								$uurl = $url->first_name;
							}
							return $uurl;
					}elseif($a == "lastname"){

							$url = get_userdata($user->ID);
							if(!empty($url)){
								$uurl = $url->last_name;
							}
							return $uurl;
					}
					elseif($a == "email"){

							$url = get_userdata($user->ID);
							if(!empty($url)){
								$uurl = $url->user_email;
							}
							return $uurl;
					}
					elseif($a == "date_register"){

							$url = get_userdata($user->ID);
							if(!empty($url)){
								//$uurl = $url->user_registered;
								$uurl = date('j F Y', strtotime($url->user_registered));
							}
							return $uurl;
					}
					/*elseif($a == "3Commas"){
                            $author_obj = get_user_by('login',$ref);
                   
	                        $message = get_user_meta($author_obj->ID,$a, true);
							return $message;
					}*/
					elseif($a == "fullname"){

							$url = get_userdata($user->ID);
							if(!empty($url)){
								$uurl = $url->display_name;
							}
							return $uurl;
					}
					elseif($a == "userlist"){

							
				$cuser = get_user_meta(get_current_user_id(), 'wizz_user', true);
				
				$args = array(
			        'orderby' => 'registered',
					'order' => 'DESC',
			        'meta_query' => array(
			            array(
			                'key'     => 'refby',
			                'value'   => $cuser,
			            )
			        ),
			    );


				$users = new WP_User_Query($args);



				if ( ! empty( $users->get_results() ) ) {
					$listu .= "<table class='myreffers display' style='width:100%;text-align:center;'> <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Username</th>
                <th> Registration Date</th>
            </tr>
            </thead><tbody>";
					foreach ($users->get_results() as $u ) {
					
						
						$dt = date('YMD', strtotime($u->user_registered));
							$listu .= "<tr data-id='".$dt."'>";
							$listu .="<td>".$u->display_name.'   </td>';
							$listu .=" <td>".$u->user_email.' </td>';
							$ccuser = get_user_meta($u->ID, 'wizz_user', true);
							$listu .="<td>".$ccuser.'</td>';
							
							$listu .=" <td><span style='display:none;'>".strtotime($u->user_registered)."</span>".date('j F Y', strtotime($u->user_registered)).'</td>';
							$listu .= "</tr>";
							 
					}
					$listu .= "</tbody></table>";
				}


						return $listu;
					}
				}
			}
	 
	  	}else{
	  		return "<p> Refferer Not Found! </p>";
	  	}


       // $author_obj = get_user_by('login',$ref);
      
        
		$args = array( 
		  'meta_query' => array(
			array(
				'key' => 'wizz_user',
				'value' => $ref,
				'compare' => 'EXISTS',
			),
		  ) 
		);
		$u = get_users($args);
		
		$field = get_user_meta($u[0]->data->ID,$atts[0], true); 
	
		echo "<h1>".$u->ID."</h1>";
	
        if(!empty($field)){
            return $field;
        }else{
            $field = get_user_meta(1,$atts[0], true);
            return $field;
        }

}

add_shortcode('fetch_custom_field_referrer', 'afi_referrer_shortcode_maker');