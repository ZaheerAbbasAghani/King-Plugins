<?php



// Creating  and showing events in calendar



function bpem_event_calendar_tab() {



	global $bp;



	if (isset($bp->groups->current_group->slug)) {



		bp_core_new_subnav_item(array(



			'name' => 'Event Calendar',



			'slug' => 'events-calendar',



			'parent_slug' => $bp->groups->current_group->slug,



			'parent_url' => bp_get_group_permalink($bp->groups->current_group),



			'screen_function' => 'bpem_event_calendar_roup_show_screen',



			'position' => 90));



	}



}



add_action('wp', 'bpem_event_calendar_tab');



function bpem_event_calendar_roup_show_screen() {




//add_action('bp_template_title', 'ev_new_group_show_screen_title');



	add_action('bp_template_content', 'bpem_event_calendar_group_show_screen_content');



	$templates = array('groups/single/plugins.php', 'plugin-template.php');



	if (strstr(locate_template($templates), 'groups/single/plugins.php')) {



		bp_core_load_template(apply_filters('bp_core_template_plugin', 'groups/single/plugins'));



	} else {



		bp_core_load_template(apply_filters('bp_core_template_plugin', 'plugin-template'));



	}



}



function bpem_event_calendar_group_show_screen_content() {

//	if (current_user_can('administrator') || groups_is_user_admin(get_current_user_id(), bp_get_group_id())) {

if (is_user_logged_in() && groups_is_user_member(get_current_user_id(), bp_get_group_id())) {



	$group_name = bp_get_group_id();



	$event_data = array();



	$args = array(



		'post_type' => 'bpem_event',



		'posts_per_page' => -1,



		'meta_key' => 'evn_group',



		'meta_value' => $group_name,



	);



	$i = 0;



	$event_query = new WP_Query($args);



	if ($event_query->have_posts()): while ($event_query->have_posts()):



			$event_query->the_post();



			$start_date = get_post_meta(get_the_id(), 'evn_startDate');



			$start_d = date("Y-m-d", strtotime($start_date[0]));



			$start_time = get_post_meta(get_the_id(), 'evn_startTime');



			$start_t = date("H:i:s", strtotime($start_time[0]));



			$end_date = get_post_meta(get_the_id(), 'evn_endDate');



			$end_d = date("Y-m-d", strtotime($end_date[0]));



			$end_time = get_post_meta(get_the_id(), 'evn_endTime');



			$end_t = date("H:i:s", strtotime($end_time[0]));



			$event_data[] = array(



				'title' => get_the_title(),



				'start' => $start_d . 'T' . $start_t,



				'end' => $end_d . 'T' . $end_t,



				'url' => get_the_permalink(),



				'imageurl' => get_the_post_thumbnail_url(get_the_id(), array(200, 200)),



			);



			$i++;



		endwhile;



		wp_reset_postdata();



	endif;



	echo "<div id='bpem-calendar'></div>";



	?>





<script type="text/javascript">





jQuery(document).ready(function() {





var todayDate = jQuery.datepicker.formatDate('yy-mm-dd', new Date());





jQuery('#bpem-calendar').fullCalendar({





header: {





left: 'prev,next today',





center: 'title',





right: 'month,agendaWeek,agendaDay'





},





defaultDate: todayDate,





//businessHours: true, // display business hours





editable: true,





eventLimit: true,





navLinks: true,





events:<?php echo json_encode($event_data); ?>,





//timeFormat: 'H(:mm)'





eventRender: function(event, eventElement) {





    if (event.imageurl) {





        eventElement.find("div.fc-content").prepend("<img src='" + event.imageurl +"' width='60' height='60'>");





    }





},





});











jQuery('.fc-agendaWeek-button').click(function(){





var str = jQuery('.fc-toolbar .fc-center').text().replace(/â€”/g, '-');





jQuery('.fc-toolbar .fc-center h2').text(str);





});





});





</script>





<?php


} else{
	echo "Only Group Members Can Access Events ";
}


}