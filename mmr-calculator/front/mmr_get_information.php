<?php 

add_action( 'wp_ajax_mmr_get_information', 'mmr_get_information' );
add_action( 'wp_ajax_nopriv_mmr_get_information', 'mmr_get_information' );

function mmr_get_information() {
	global $wpdb; // this is how you get access to the database

$region = $_POST['region'];
$summoner_name = $_POST['summoner_name'];

$response = wp_remote_request( 'https://'.$region.'.whatismymmr.com/api/v1/summoner?name='.$summoner_name,
    array(
        'method'     => 'GET'
    )
);
 
$body = wp_remote_retrieve_body($response);
$result = json_decode($body);


//print_r($result);


if($result->error->code != 100){
$normal = $result->normal->avg;
$aram = $result->ARAM->avg;
$ranked = $result->ranked->avg;
$closestRank = $result->ranked->closestRank;

$res = "";
$res .= '<table class="search-result">
		<tr>
		<td>Normal MMR:</td>';

		if(!empty($normal)){
			$res .= '<td>'.$normal.'</td>';
		}else{
			$res .= '<td>-</td>';
		}

$res.='</tr>
		<tr>
		<td>ARAM MMR:</td>';

		if(!empty($aram)){
			$res .= '<td>'.$aram.'</td>';
		}else{
			$res .= '<td>-</td>';
		}


$res .='</tr>
		<tr>
			<td>Ranked MMR:</td>';
	
			if(!empty($ranked)){
				$res .= '<td>'.$ranked.'</td>';
			}else{
				$res .= '<td>-</td>';
			}

$res .='</tr>

	<tr>
		<td>Division MMR:</td>';

		if(!empty($closestRank)){
			$res .= '<td>'.$closestRank.'</td>';
		}else{
			$res .= '<td>-</td>';
		}

$res .= '</tr>
</table>';

}else{
	$res .= '<p class="error" style="color: red;font-size: 20px;">No recent MMR data for summoner.</p>';
}

echo $res;

	wp_die(); // this is required to terminate immediately and return a proper response
}