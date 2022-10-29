<?php 
if ( defined( 'XMLRPC_REQUEST' ) || defined( 'REST_REQUEST' ) || ( defined( 'WP_INSTALLING' ) && WP_INSTALLING ) || wp_doing_ajax() ) {
    @ini_set( 'display_errors', 1 );
}
add_action('wp_ajax_nopriv_podo_filter_by_month','podo_filter_by_month' );
add_action( 'wp_ajax_podo_filter_by_month', 'podo_filter_by_month' );
function podo_filter_by_month() {

global $wpdb;
$table_name = $wpdb->base_prefix.'anam_dokument_info';
$val = $_POST['val'];
$currency = get_option('podo_currency');
$m = date("m", strtotime($val));

$query3 ="SELECT created_at,sum(price) as revenue FROM $table_name WHERE MONTH(created_at)=$m AND status='pending' GROUP BY date(created_at) ORDER BY created_at DESC LIMIT 10";


$revenue_by_created_at = $wpdb->get_results($query3);

//print_r($revenue_by_created_at);

$customer_table = $wpdb->base_prefix.'anam_customer_info';

$result = "";

	foreach ($revenue_by_created_at as $rev) { 


		$result .='<li style="font-weight:bold; margin:20px 0px" data-month="'.date('F',strtotime($rev->created_at)).'">'.date("Y.m.d",strtotime($rev->created_at)).'</li>';
		$result .='<li style="font-weight:bold; margin:20px 0px" data-month="'.date('F',strtotime($rev->created_at)).'">'.$rev->revenue." ".$currency.'</li>';
		$result .='<li style="font-weight:bold; margin:20px 0px" data-month="'.date('F',strtotime($rev->created_at)).'">  Status </li>';
		$result .='<li style="font-weight:bold; margin:20px 0px" data-month="'.date('F',strtotime($rev->created_at)).'">  Zahlungsstatus ändern </li>';

		$dd = date("Y-m-d",strtotime($rev->created_at));
		
			// Fetching records day by day
		$query4 ="SELECT * FROM $table_name WHERE date(created_at)= '".$dd."' AND  status='pending' ";
		$revenue_by_day = $wpdb->get_results($query4);

		foreach ($revenue_by_day as $rbd) { 

			// Get Username Only
			$query5 ="SELECT * FROM $customer_table WHERE id='".$rbd->customer_id."' ";
			$user = $wpdb->get_results($query5);

		
			 $result .= '<div class="rowbyDate" data-month="'.date('F',strtotime($rbd->created_at)).'" data-year="'.date('Y',strtotime($rbd->created_at)).'">';
		  		if(!empty($user[0]->first_name)){ 
  					$result .= '<li data-month="'.date('F',strtotime($rbd->created_at)).'" data-year="'.date('Y',strtotime($rbd->created_at)).'">'.$user[0]->first_name." ".$user[0]->last_name.'</li>';
  				} 	

  				$result .= '<li data-month="'.date('F',strtotime($rbd->created_at)).'" data-year="'.date('Y',strtotime($rbd->created_at)).'">'.$rbd->price." ".$currency.'</li>';
  			
  			if($rbd->status == "paid"){ 
  				$result .= '<li data-month="'.date('F',strtotime($rbd->created_at)).'" data-year="'.date('Y',strtotime($rbd->created_at)).'"><span class="dashicons dashicons-yes" style="color:#4ab516;font-size: 30px;line-height: 26px;margin-left: -6px;"></span></li>';
  			} else { 
  				$result .= '<li data-month="'.date('F',strtotime($rbd->created_at)).'" data-year="'.date('Y',strtotime($rbd->created_at)).'"><span class="dashicons dashicons-clock" style="color:#f7c631;font-size: 22px;line-height: 26px;"></span></li>';
  			} 
  			$result .= '<li data-month="'.date('F',strtotime($rbd->created_at)).'" data-year="'.date('Y',strtotime($rbd->created_at)).'"> <a href="#" data-id="'.$rbd->id.'" class="change_status">Status zu “bezahlt” ändern</a></li>';
  			 $result .= '</div>';

		} // end inner foreach 
		$result .= '<hr style="margin-top: 30px;border: 1px solid #ddd;">';
	} // end outer foreach 

echo $result;
		
wp_die();

}