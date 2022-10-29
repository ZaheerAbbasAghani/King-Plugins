<?php  

/*

* 	Creating events tab and display events with form to create events.

*/

function bpem_events_tab() {

global $bp;

if (isset($bp->groups->current_group->slug)) {



bp_core_new_subnav_item(array(

'name' => 'Events',

'slug' => 'events',

'parent_slug' => $bp->groups->current_group->slug,

'parent_url' => bp_get_group_permalink($bp->groups->current_group),

'screen_function' => 'my_new_group_show_screen',

'position' => 80));

}



}



add_action('wp', 'bpem_events_tab');

function my_new_group_show_screen() {

add_action('bp_template_content', 'bpem_group_show_screen_content');

$templates = array('groups/single/plugins.php', 'plugin-template.php');

if (strstr(locate_template($templates), 'groups/single/plugins.php')) {

bp_core_load_template(apply_filters('bp_core_template_plugin', 'groups/single/plugins'));



} else {

bp_core_load_template(apply_filters('bp_core_template_plugin', 'plugin-template'));

}

}



function bpem_group_show_screen_content() {



if(  current_user_can('administrator') || groups_is_user_admin(get_current_user_id() ,  bp_get_group_id()) ){ ?>



<div class="event_wrapper">	

<h1>Create Event </h1> <hr>

<form class="form-horizontal" method="post" action="" enctype="multipart/form-data" id="eventform">

<!-- 
 <div class="control-group">

      <label class="control-label text-left"  for="eventTitle">Event Title *</label>
      <div class="controls">
        <input type="text" id="event_title" name="event_title" placeholder="Enter Event Title" class="input-xlarge">
      </div>
  </div>
 -->
<div class="form-group">
  <label for="eventTitle" class="control-label">Event Title *</label>
    <div class="controls"><input type="text" id="event_title" name="event_title" class="form-control" placeholder="Enter Event Title" ></div>
</div>



<div class="form-group">

<label for="eventDescription" class="control-label">Event Description *</label>

<div class="controls">



<?php $settings = array( 'media_buttons' => false,  'editor_height' => 150, 'textarea_rows' => 20);	 $content = '';

$editor_id = 'event_desc';

wp_editor( $content, $editor_id, $settings ); 

?>

</div>

</div> 



<div class="form-group">

<label for="uploadImage" class="control-label">Upload Image</label>

<!-- <div class="controls"><button type="button" id="uploadImage" class="btn btn-default"> Upload Image </button>
<input type="hidden" id="EventUploadImage" name="EventUploadImage" value=""> -->
<div class="controls">
<input type="file" name="uploadImage" id="uploadImage">

<div class="imgshow"></div>

</div>

</div>



<div class="form-group">
  <label for="Location" class="control-label">Location *</label>
  <div class="controls"><input type="text" name="event_location" id="event_location" class="form-control" placeholder="Enter Event Location" ></div>
</div>



<div class="form-group">

<label for="EventOrganizer" class="control-label">Event Organiser</label>

<div class="controls">

<input type="text" id="event_organizer" name="event_organizer" class="form-control" placeholder="Enter Event organiser" >

</div>

</div>





<div class="form-group">

<label for="EventOrganizerUrl" class="control-label">Event Organiser URL</label>

<div class="controls">

<input type="url" id="event_organizer_url" name="event_organizer_url" class="form-control" placeholder="Enter Event organiser" >

</div>

</div>



<div class="form-group">

<label for="datetime" class="control-label">Start Date / Time *</label>



<div class="controls start_date">

<input type="text"  id="start_date" name="start_date" class="form-control" style="width: 50%; float: left;" >

<label> @ </label>

<input type="text" id="start_time" name="start_time" class="form-control"  style="width: 40%; float: right;" > 

</div>

</div>



<div class="form-group">

<label for="datetime" class="control-label">End Date / Time *</label>

<div class="controls end_date">

<input type="text" id="end_date" name="end_date" class="form-control" style="width: 50%; float: left;" >

<label> @ </label> 

<input type="text" id="end_time" name="end_time" class="form-control" style="width: 40%; float: right;" > 

</div>

</div>



<input type="hidden" id="evn_group" name="evn_group" value="<?php echo __(bp_get_group_id(),'bp-event-manager'); ?>">

<div class="form-group">


<div class="controls">

<input type="submit" value="Submit" class="btn btn-success create_event" >
 <!-- <button class="btn btn-success create_event" type="submit">Submit</button> -->
<img src="<?php echo plugin_dir_url(__FILE__).'images/loaders.gif'; ?>" class="loaders">
<h3 class="bpem_success"></h3>
</div>

</div>



</form>





</div><!--eventWrapper -->



<?php $current_user = wp_get_current_user(); ?>



<div class="event_list">



<div class="w3-sidebar w3-bar-block w3-light-grey w3-card">

<button class="w3-bar-item w3-button tablink active" onclick="openCity(event, 'Upcoming')">Upcoming</button>

<button class="w3-bar-item w3-button tablink" onclick="openCity(event, 'Past')">Past</button>

</div>



<div class="event_wrap">

<div id="Upcoming" class="w3-container eventcontainer">



<?php 	$group_name =  bp_get_group_id();

$args = array(

'post_type' => array('bpem_event'),

'post_status'	=> 'publish',

'meta_key' => 'evn_group',

'meta_value' => $group_name,

);

$events = new WP_Query($args);

$num=0;



if($events->have_posts()): while($events->have_posts()): $events->the_post(); 



$start_date = get_post_meta( get_the_id(), 'evn_startDate'); 

$start_time = get_post_meta( get_the_id(), 'evn_startTime');

$location = get_post_meta( get_the_id(), 'evn_location');



// End Date / Time

$end_date = get_post_meta(get_the_id(), 'evn_endDate' );

$end_time = get_post_meta(get_the_id(), 'evn_endTime' );

$expire =  strtotime($end_date[0].''.$end_time[0]);

$today = strtotime(date("D, M d, Y g:ia"));



if($expire>$today) {

?> 



<div class="bp-events">



<div class="date-time"><p><i class="fa fa-clock-o" aria-hidden="true"></i> <?php echo __($start_date[0],'bp-event-manager'); ?>, <?php echo __($start_time[0],'bp-event-manager'); ?>  </p>

</div><!-- date-time-->



<div class="title">

<a href="<?php the_permalink(); ?>"><?php echo __(get_the_title(),'bp-event-manager'); ?> </a>

</div><!-- title -->



<div class="location">

<p><i class="fa fa-map-marker" aria-hidden="true"></i> <?php echo __($location[0],'bp-event-manager'); ?> </p>

</div><!-- location -->



<div class="description"><?php echo __(get_the_excerpt(),'bp-event-manager'); ?></div><!-- title -->



<div class="footer clearfix">



<?php  $user_ids =  get_post_meta( get_the_id(), 'event_attend_id'); ?>



<div class="total pull-left">



<?php 

$i=0;

foreach (array_slice($user_ids, 0, get_option( 'bpem_number_avatar' )) as $user_id) { 

$usr = get_user_by('id', $user_ids[$i]); 

//print_r($usr);

$avatar =  bp_core_fetch_avatar( array( 'item_id' => $user_id, 'width' => 100,'height' => 100, 'class' => 'avatar','html' => false));



?>

<?php if(!empty($usr->user_url)){ ?>

<a href="<?php echo $usr->user_url; ?>" target="_blank">

<?php } else { ?>

<a href="<?php bp_core_get_user_domain( $user_ids[$i]); ?>" target="_blank">

<?php  } ?>

<img src="<?php echo $avatar; ?>">



</a>

<?php



$i++;

} ?><a href="<?php the_permalink(); ?>"><p>See all </p></a>



</div><!-- total -->







<?php $avatar =  bp_core_fetch_avatar( array( 'item_id' => bp_loggedin_user_id(), 'width' => 100,'height' => 100, 'class' => 'avatar','type' => 'full','html' => false));  ?>





<?php if(in_array($current_user->ID, $user_ids) ){ ?>





<div class="action pull-right interested">

<div class="cnt">



<a href="<?php the_permalink(); ?>" ><i class="fa fa-comments"></i>  <?php comments_number( '0', '1', '% ' ); ?> </a>



</div>





<button class="btn btn-primary pull-right interestedbtn" data-id="<?php echo get_the_id(); ?>" ><?php if(!empty(get_option('bpem_not_join_event_label'))){ echo get_option('bpem_not_join_event_label'); } else{ echo __('Leave Event','bp-event-manager') ;} ?></button>



</div><!-- action -->



<?php } else { 



$link = home_url( '/members/' . bp_core_get_username( get_current_user_id() )  );

?>



<?php //&& get_option('bpem_allow_user_join_event_without_join_group') == 1  ?>

<div class="action pull-right">

<?php if (!groups_is_user_member(get_current_user_id(),bp_get_group_id()) && get_option('bpem_allow_user_join_event_after_join_group') == 1){ ?>

<p>Join Group To Attend Event</p>

<?php } elseif(groups_is_user_member(get_current_user_id(),bp_get_group_id()) ) { ?>

<div class="commentsCount">

<a href="<?php the_permalink(); ?>"><i class="fa fa-comments"></i>  <?php comments_number( '0', '1', '% ' ); ?> </a>

</div>

<button class="btn btn-primary" data-id="<?php echo get_the_id(); ?>"  id="attend_<?php echo $num; ?>" ><?php if(!empty(get_option('bpem_join_event_label'))){ echo get_option('bpem_join_event_label'); } else{echo __('Join Event','bp-event-manager'); } ?></button>

<img src="<?php echo plugin_dir_url(__FILE__).'images/loaders.gif'; ?>" class="loaderss ghoom_<?php echo $num; ?>">





<?php } elseif(!groups_is_user_member(get_current_user_id(),bp_get_group_id()) && get_option('bpem_allow_user_join_event_after_join_group') == 0) { ?>

<p>Join Group To Attend Event</p>

<?php } ?>





</div><!-- action -->

<?php } //end else  ?>



</div><!-- footer -->



</div>



<?php }



$num++;

endwhile;



else:

echo __("No Events Created Yet.","bp-event-manager");

endif;



?>

<div class="event-nav"></div>



</div><!-- Upcoming ends -->





<div id="Past" class="w3-container eventcontainer" style="display:none;">



<?php 



$group_name =  bp_get_group_id();



$args = array(



'post_type' => array('bpem_event'),

'post_status'	=> 'publish',

'meta_key' => 'evn_group',

'meta_value' => $group_name,



);



$events = new WP_Query($args);



$i=0;



if($events->have_posts()): while($events->have_posts()): $events->the_post(); 



$start_date = get_post_meta( get_the_id(), 'evn_startDate'); 

$start_time = get_post_meta( get_the_id(), 'evn_startTime');

$location = get_post_meta( get_the_id(), 'evn_location');



// End Date / Time

$end_date = get_post_meta(get_the_id(), 'evn_endDate' );

$end_time = get_post_meta(get_the_id(), 'evn_endTime' );



$expire =  strtotime($end_date[0].''.$end_time[0]);

$today = strtotime(date("D, M d, Y g:ia"));



if($expire<$today) {



?> 







<div class="bp-events">



<div class="date-time"><p><i class="fa fa-clock-o" aria-hidden="true"></i> <?php echo __($start_date[0],'bp-event-manager'); ?>, <?php echo __($start_time[0],'bp-event-manager'); ?>  </p>

</div><!-- date-time-->



<div class="title">

	<a href="<?php the_permalink(); ?>"><?php echo __(get_the_title(),'bp-event-manager'); ?> </a>

</div><!-- title -->



<div class="location">

	<p><i class="fa fa-map-marker" aria-hidden="true"></i> <?php echo __($location[0],'bp-event-manager'); ?> </p>

</div><!-- location -->



<div class="description"><?php echo __(get_the_excerpt(),'bp-event-manager'); ?></div><!-- description -->



<div class="footer clearfix">

<?php  $user_ids =  get_post_meta( get_the_id(), 'event_attend_id'); ?>



<div class="total pull-left">



<?php 

$i=0;

foreach (array_slice($user_ids, 0, get_option( 'bpem_number_avatar' )) as $user_id) { 

$usr = get_user_by('id', $user_ids[$i]); 





$avatar =  bp_core_fetch_avatar( array( 'item_id' => $user_id, 'width' => 100,'height' => 100, 'class' => 'avatar','html' => false));



?>

<?php if(!empty($usr->user_url)){ ?>

<a href="<?php echo $usr->user_url; ?>" target="_blank">

<?php } else { ?>

<a href="<?php echo bp_core_get_user_domain( $user_ids[$i]); ?>" target="_blank">

<?php  } ?>

<img src="<?php echo $avatar; ?>">



</a>

<?php



$i++;

} ?><a href="<?php the_permalink(); ?>"><p>See all </p></a>



</div><!-- total -->



<div class="action pull-right">

<p> <?php comments_number( '0', '1', '% ' ); ?> Comments</p>

</div><!-- action -->

</div><!--footer -->

</div><!-- events -->





<?php  }







$i++;



endwhile;



else:

echo __( "No past event is avaialble.", "bp-event-manager");



endif;







?>

<div class="event-nav"></div>



</div><!-- Past -->





</div>

</div><!-- event_list -->



<?php } // main if

// User Can

elseif(is_user_logged_in() && groups_is_user_member(get_current_user_id(),bp_get_group_id())) { 

	require_once plugin_dir_path( __FILE__ ).'bpem-user-can-events.php';     

} //end elseif

else{

	echo "<div class='bpem_alerts'> Only Group Members Can Access Events </div>";

}





}

