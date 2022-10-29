<?php 
function gvw_profile_settings($profile){

$user_id = get_current_user_id();
$user_info = get_user_by( "id", $user_id );

if(is_user_logged_in()){
$profile .= "<div class='profile_settings'><div class='row'>

<div class='col-3 pleftSide'>

<div class='profile-sidebar'>
	<!-- SIDEBAR USERPIC -->
	<div class='profile-userpic text-center'>
		<img src='".esc_url(get_avatar_url($user_id))."' class='img-responsive' alt=''>
	</div>
	<!-- END SIDEBAR USERPIC -->
	<!-- SIDEBAR USER TITLE -->
	<div class='profile-usertitle'>
		<div class='profile-usertitle-name'>
			".$user_info->display_name."
		</div>
	</div>
	<!-- END SIDEBAR USER TITLE -->
	<!-- SIDEBAR BUTTONS -->
	<!-- SIDEBAR MENU -->
	<div class='profile-usermenu list-unstyled'>
		<ul class='nav'>
			<li class='active'>
				<a href='#'>
				<i class='glyphicon glyphicon-giveaway-join'></i>
				Overview </a>
			</li>
			<!--li>
				<a href='#'>
				<i class='glyphicon glyphicon-user'></i>
				Account Settings </a>
			</li>
			<li>
				<a href='#' target='_blank'>
				<i class='glyphicon glyphicon-ok'></i>
				Tasks </a>
			</li-->
			<li>
				<a href='https://www.secwins.com/wp-login.php?action=logout'>
				<i class='glyphicon glyphicon-flag'></i>
				Logout </a>
			</li>
		</ul>
	</div>
	<!-- END MENU -->
</div>
</div><!--3-->

<div class='col-9'>

<ul class='nav nav-tabs' id='myTab' role='tablist'>
  <li class='nav-item'>
    <a class='nav-link active' id='giveaway-join-tab' data-toggle='tab' href='#giveaway-join' role='tab' aria-controls='giveaway-join' aria-selected='true'>Join Events </a>
  </li>
</ul>

<!-- Tab panes -->
<div class='tab-content'>
  <div class='tab-pane active' id='giveaway-join' role='tabpanel' aria-labelledby='giveaway-join-tab'>";

$profile .= '

    <div class="input-group input-daterange">

      <input type="date" id="min-date" class="form-control date-range-filter" data-date-format="yyyy-mm-dd" placeholder="From:">

      <div class="input-group-addon">to</div>

      <input type="date" id="max-date" class="form-control date-range-filter" data-date-format="yyyy-mm-dd" placeholder="To:">

    </div>
  ';

$id = get_current_user_id();

global $wpdb; 
$table_name = $wpdb->base_prefix.'alfa_points_table';
$query = "SELECT * FROM $table_name WHERE user_id='$id'";
$query_results = $wpdb->get_results($query);

$profile .= "<table class='table table-bordered text-center' id='points_table'><thead>
	<th>Post Title</th>
	<th>Points</th>
	<th>Date</th>
<thead><tbody>";

foreach ($query_results as $user) {
	$profile .= "<tr>";
	$profile .= "<td>".get_the_title($user->event_joined)."</td>";
	$profile .= "<td>".$user->obtained_points."</td>";
	$profile .= "<td>".$user->points_today."</td>";
	$profile .= "</tr>";

}


$profile .= "</body></table>";


$profile.="<hr>";


$query = "SELECT sum(obtained_points) as total FROM $table_name WHERE user_id='$id'";
$result = $wpdb->get_results($query);

$profile .= "<div id='total_points' class='text-center'><h1> Total Points: ".$result[0]->total."</h1></div>";

  $profile .= "</div>
  

</div></div>";


} else{
		echo "<h3 style='padding: 30px 20px 50px 0px;'> Please <a href=".get_site_url().'/wp-admin'." style='text-decoration:underline;'> Login </a> to continue. </h3>";
}

return $profile;

}
add_shortcode('profile-settings', 'gvw_profile_settings');