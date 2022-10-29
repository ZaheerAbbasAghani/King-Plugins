<?php
function my_the_content_filter( $content ) {
if ( is_single() && 'salons' == get_post_type() )
{
    global $post;
    $address = get_post_meta($post->ID,"salon_address",true);
    $list = get_post_meta($post->ID,"user_in_queue",false);

    echo "<p><b>Address: </b>".$address."</p>";

    $content .= "<table id='dq_queue'> 
    <thead><th>Display Picture</th><th>Client Name</th><th>Queue Status</th><th>Position</th></thead>
    <tbody>";
    $i=1;
    if(!empty($list)){
	    foreach ($list as $user_id) {
	    	$user_info = get_userdata($user_id );
			$content .= "<tr>
			<td><img src='".esc_url( get_avatar_url( $user_id ) )."' class='queue_man'/></td>
			<td>".$user_info->display_name."</td>";
			if($i == 1){
				$content .="<td>In Progress</td>";
			}else{
				$content .="<td>Queuing</td>";
			}
			$content .= "<td>".$i."</td>
			</tr>";
			$i++;
	    }
    }else{
    	$content .= "<tr><td colspan='4'>No customer in queue.</td></tr>";
    }
    
    $content .= "</tbody></table>";

//	$content .= "<div class=''> <button class='delete_queue_btn'>Leave Queue</button></div>";    
    $user_queue = get_post_meta($post->ID,"user_in_queue",false);
    //print_r($user_queue);
	if(is_user_logged_in()){
		if(in_array(get_current_user_id(), $user_queue)){
			$content .= "<div class='delete_queue1'><button class='delete_queue_btn' user_id='".get_current_user_id()."' salon_id='".get_the_id()."'> Leave Queue </button></div>";
		}else{
			$content .= "<div class='join_queue1'><button class='join_queue_btn' user_id='".get_current_user_id()."' salon_id='".get_the_id()."'> Join Queue </button></div>";
		}
	}else{
		$content .= "<div class='join_queue1'><a href='".get_site_url()."/signin' id='login' class='search-submit'> Login </a></div>";
	}


}
return $content;
}

add_filter( 'the_content', 'my_the_content_filter' ,80);