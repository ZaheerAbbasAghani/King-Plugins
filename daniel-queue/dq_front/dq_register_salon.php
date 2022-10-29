<?php 

function dq_register_salon_process($data){
//$dq_address = get_post_meta( 625, 'salon_master', true );

//print_r($dq_address);

$args = array(
    'post_type' => 'salons',
    'orderby'=> 'title',
    'order' => 'ASC',
    'meta_query' => array(
        array(
            'key' => 'salon_master',
            'value' => get_current_user_id(),
            'compare' => 'LIKE'
        ), 
    ), 
);


$query = new WP_Query($args);

//if(!empty($query)){
	
	if($query->have_posts()): while($query->have_posts()): $query->the_post();
		$list = get_post_meta(get_the_ID(),"user_in_queue",false);
//print_r($list);
		$featured_img_url = get_the_post_thumbnail_url(get_the_ID(),'full'); 
		$address = get_post_meta(get_the_ID(),'salon_address',true);
		$data .= "<div class='dq_my_salon'><div class='mini_salon'>
					<img src='".$featured_img_url."'> 
					<p><b>Salon Name: </b><a href='".get_the_permalink()."'> ".get_the_title()."</a></p>
					<p><b>Description: </b>".get_the_content()."</p>
					<p><b>Address: </b>".$address."</p></div>
					
				</div>";

	 $data .= "<table id='dq_queue'> 
    <thead><th>Display Picture</th><th>Client Name</th><th>Queue Status</th><th>Position</th><th>Action</td></thead>
    <tbody>";
    $i=1;
    foreach ($list as $user_id) {
    	$user_info = get_userdata($user_id );
		$data .= "<tr>
		<td><img src='".esc_url( get_avatar_url( $user_id ) )."' class='queue_man'/></td>
		<td>".$user_info->display_name."</td>";
		if($i == 1){
			$data .="<td>In Progress</td>";
		}else{
			$data .="<td>Queuing</td>";
		}

		$data .= "<td>".$i."</td>";
		if($i == 1){
			$data .="<td><button id='remove_customer' salon_id='".get_the_ID()."' user_id='".$user_id."'>x</button></td>";
		}else{
			$data .="<td><button id='remove_customer_disabled' disabled>x</button></td>";
		}

		$data .= "</tr>";

		$i++;
    }
    
    
    $data .= "</tbody></table>";


	endwhile;
	else:

	$data .='<div id="create_salon_wrapper"><h4>Insert your salon information</h4><hr><form method="post" action="" id="frm_salon">
		<input type="text" name="salon_name" id="salon_name" placeholder="Enter salon name">
		<input type="text" name="salon_address" id="salon_address" placeholder="Enter salon address"> 
		<textarea name="salon_description" id="salon_description" placeholder="Enter Salon Description"></textarea>
		<input type="file" name="salon_image" id="salon_image">
		<input type="hidden" value="'.get_current_user_id().'" id="user_id"/>
		<input type="submit" name="" id="createSalon">
		</form></div>';


	endif;


	//$data.="<div class='join_queue'><button id='join_queue_btn'> Join Queue </button></div>";



	/*if(!is_user_logged_in()){
	$data .='<div id="create_salon_wrapper"><form method="post" action="" id="frm_salon">
		<input type="text" name="salon_name" id="salon_name" placeholder="Enter salon name">
		<input type="text" name="salon_address" id="salon_address" placeholder="Enter salon address"> 
		<textarea name="salon_description" id="salon_description" placeholder="Enter Salon Description"></textarea>
		<input type="file" name="salon_image" id="salon_image">
		<input type="hidden" value="'.get_current_user_id().'" id="user_id"/>
		<input type="submit" name="" id="createSalon">
		</form></div>';
	
	}*/
	return $data;
}

add_shortcode( "register_salon", "dq_register_salon_process");