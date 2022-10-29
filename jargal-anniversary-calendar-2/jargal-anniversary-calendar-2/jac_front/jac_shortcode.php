<?php

function jac_calendar_with_annivarsary_dates($return){
//return "<div id='calendar'></div>";

$anniversary_data = array();
$args = array(
'post_type' => 'post',
'posts_per_page' =>-1,
);
$i = 0;
$event_query = new WP_Query($args);
if ($event_query->have_posts()): while ($event_query->have_posts()):
	$event_query->the_post();
	$enable_disable_field = get_post_meta(get_the_ID(), 'rudr_noindex',false);
	//delete_post_meta( get_the_ID(),'rudr_noindex',"on" );
	if($enable_disable_field[0] == "on"){
		echo "";
	}else{
		$year = date("Y");
		$month = get_the_date("m");
		$day = get_the_date("d");
		$current_year = date("Y-m-d",strtotime($year.'-'.$month.'-'.$day));
		$date1 = get_the_date("Y-m-d");
		$date2 = date("Y-m-d");
		$diff = abs(strtotime($date2) - strtotime($date1));

		$years = floor($diff / (365*60*60*24));
		$months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
		$days=floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));
		if($years>=1){
			$old = "il y a $years ans";
		}
		if($years==0 && $months>=0){
			$old = "il y a $months mois";
		}

		if($years==0 && $months==0 ){
			$old = "il y a $days jours";
		}
		//$old = "$years ans, $months mois, $days jours";
		$start_d = date("Y-m-d", strtotime(get_the_date()));
		$anniversary_data[] = array(
			'startDate' => $current_year,
			'endDate' => $current_year,
			'summary' => "<span>".get_the_date()."</span><br>".$old.'<br><a href="'.get_permalink().'" class="summary" >'.get_the_title()."</a>",
		);
		$i++;
	}
		
endwhile;
wp_reset_postdata();
endif;

$return .= "<div id='jac-calendar'></div>";
$return .="<script>
jQuery(document).ready(function () {
	jQuery('#jac-calendar').simpleCalendar({
		fixedStartDay: false,
		disableEmptyDetails: true,
		events:".json_encode($anniversary_data).",
		displayYear:true
	});
});
</script>";
return $return;

}
add_shortcode("jac_calendar","jac_calendar_with_annivarsary_dates");