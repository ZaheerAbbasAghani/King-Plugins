<?php

/*

 * 	Creating events tab and display events with form to create events.

 */

function bpem_events_list_tab() {
	global $bp;
	if (isset($bp->groups->current_group->slug)) {

		bp_core_new_subnav_item(array(

			'name' => 'Events List',
			'slug' => 'events-list',
			'parent_slug' => $bp->groups->current_group->slug,
			'parent_url' => bp_get_group_permalink($bp->groups->current_group),
			'screen_function' => 'my_new_group_show_screen_list',
			'position' => 80));

	}

}

add_action('wp', 'bpem_events_list_tab');

function my_new_group_show_screen_list() {
	add_action('bp_template_title', 'bpem_create_event_title');
	add_action('bp_template_content', 'bpem_group_show_screen_content_list');

	$templates = array('groups/single/plugins.php', 'plugin-template.php');

	if (strstr(locate_template($templates), 'groups/single/plugins.php')) {

		bp_core_load_template(apply_filters('bp_core_template_plugin', 'groups/single/plugins'));

	} else {

		bp_core_load_template(apply_filters('bp_core_template_plugin', 'plugin-template'));

	}

}

function bpem_create_event_title() {
	echo 'Event List';
}

function bpem_group_show_screen_content_list() {
	?>


<?php
// Particular Event Published by user
	if (is_user_logged_in()):

		$group_name = bp_get_group_id();

		$args = array(

			'post_type' => array('bpem_event'),
			'post_status' => 'publish',
			'meta_key' => 'evn_group',
			'meta_value' => $group_name,
			'author' => get_current_user_id(),

		);
		$first_post = new WP_Query($args);
		echo '<div class="event_wrapper bpem_event_list">';
		echo "<table>";
		if ($first_post->have_posts()) {

			while ($first_post->have_posts()) {

				$first_post->the_post();
				$event_thumb = get_the_post_thumbnail_url(get_the_id());
				$location = get_post_meta(get_the_id(), "evn_location");
				$start_date = get_post_meta(get_the_id(), "evn_startDate");
				$start_time = get_post_meta(get_the_id(), "evn_startTime");
				$evn_endDate = get_post_meta(get_the_id(), "evn_endDate");
				$evn_endTime = get_post_meta(get_the_id(), "evn_endTime");
				$evn_organizer = get_post_meta(get_the_id(), "evn_organizer");
				$evn_organizer_url = get_post_meta(get_the_id(), "evn_organizer_url");
				$thumb = get_post_meta(get_the_id(), '_thumbnail_id');
//echo $thumb[0];

				?>
		<tr>
		<td><?php echo wp_get_attachment_image($thumb[0]); ?></td>
		<td><a href="<?php the_permalink();?>"> <?php echo get_the_title(); ?> </a></td>
		<td><a href="#" class="edit_event" data-id="<?php echo get_the_id(); ?>" event-name="<?php echo get_the_title(); ?>" event-desc="<?php echo sanitize_textarea_field(strip_tags(get_the_content())); ?>" event-thumb="<?php echo $event_thumb; ?>" event-location="<?php echo $location[0]; ?>" event-organization="<?php echo $evn_organizer[0]; ?>"  event-organization-url="<?php echo $evn_organizer_url[0]; ?>" event-start-date="<?php echo $start_date[0]; ?>" event-start-time="<?php echo $start_time[0]; ?>" event-end-date="<?php echo $evn_endDate[0]; ?>" event-end-time="<?php echo $evn_endTime[0]; ?>" event-attach-id="<?php echo $thumb[0]; ?>" >  Edit </a></td>


		<td><a href="#" class="delete_event" data-id="<?php echo get_the_id(); ?>" event-location="<?php echo $location[0]; ?>" event-organization="<?php echo $evn_organizer[0]; ?>"  event-organization-url="<?php echo $evn_organizer_url[0]; ?>" event-start-date="<?php echo $start_date[0]; ?>" event-start-time="<?php echo $start_time[0]; ?>" event-end-date="<?php echo $evn_endDate[0]; ?>" event-end-time="<?php echo $evn_endTime[0]; ?>">  Delete </a></td>
		</tr>
		<?php

			}
			wp_reset_postdata();
//endif;
		} else {
			echo "<tr> <td>No Event Found! </td> </tr>";
		}
		echo "</table>";
		echo '</div>';

	else:
		echo "<tr> <td> No Login! No Events! </td> </tr>";
	endif;

}

function bpem_update_form() {

/*if (current_user_can('administrator') || groups_is_user_admin(get_current_user_id(), bp_get_group_id())) {*/
	?>


<div class="update_event">

<div class="whiteBox">
<p class="closeplz"> &times; </p>
<h1>Update Event </h1> <hr>

<form class="form-horizontal" method="post" action="" enctype="multipart/form-data">



<div class="form-group">
<label for="eventTitle" class="col-md-2 control-label">Event Title *</label>
<div class="col-sm-10">
<input type="text" name="event_title" id="event_title" class="form-control" placeholder="Enter Event Title" required="required">
</div>
</div>


<div class="form-group">

<label for="eventDescription" class="col-sm-2 control-label">Event Description *</label>

<div class="col-sm-10">



<?php $settings = array('media_buttons' => false, 'editor_height' => 150, 'textarea_rows' => 20);
	$content = '';

	$editor_id = 'event_desc';

	wp_editor($content, $editor_id, $settings);

	?>

</div>

</div>



<div class="form-group">

<label for="Location" class="col-sm-2 control-label">Location *</label>

<div class="col-sm-10">

<input type="text" id="event_location" class="form-control" placeholder="Enter Event Location" required="required">

</div>

</div>



<div class="form-group">

<label for="EventOrganizer" class="col-sm-2 control-label">Event Organiser *</label>

<div class="col-sm-10">

<input type="text" id="event_organizer" class="form-control" placeholder="Enter Event organiser" required="required">

</div>

</div>







<div class="form-group">

<label for="EventOrganizerUrl" class="col-sm-2 control-label">Event Organiser URL</label>

<div class="col-sm-10">

<input type="url" id="event_organizer_url" class="form-control" placeholder="Enter Event organiser" required="required">

</div>

</div>







<div class="form-group ">

<label for="datetime" class="col-sm-2 control-label">
<div style="width: 60%; float: left;"> Start Date*  </div>
<div style="width: 40%; float: left;"> Time* </div>
</label>



<div class="col-sm-10 start_date">

<input type="text"  id="start_date" class="form-control" style="width: 50%; float: left;" required="required">

<label> @ </label>

<input type="text" id="start_time" class="form-control"  style="width: 40%; float: right;" required="required">

</div>

</div>



<div class="form-group ">

<label for="datetime" class="col-sm-2 control-label">
<div style="width: 60%; float: left; margin-top: 10px;"> End Date*  </div>
<div style="width: 40%; float: left;margin-top: 10px;"> Time* </div>
</label>


<div class="col-sm-10 end_date">

<input type="text" id="end_date" class="form-control" style="width: 50%; float: left;" required="required">

<label> @ </label>

<input type="text" id="end_time" class="form-control" style="width: 40%; float: right;" required="required">

</div>

</div>



<input type="hidden" id="eventid" value="">





<div class="form-group">

<div class="col-sm-2"></div>

<div class="col-sm-10"><br>

<input type="submit" value="Update" class="btn btn-success update_event_sub">

<img src="<?php echo plugin_dir_url(__FILE__) . 'images/loaders.gif'; ?>" class="loaders">

</div>

</div>



</form>


</div><!-- whiteBox -->


</div><!--eventWrapper -->




<?php

//} //endif
} //bpem_update_form

add_action('wp_footer', 'bpem_update_form');