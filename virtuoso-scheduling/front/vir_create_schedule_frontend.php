<?php 

function vir_create_schedule_frontend($tabs){

if(is_user_logged_in()):

  $tabs .= '<div id="tabs">
	      	<ul>
	      		<li><a href="#request-schedule">Request a Schedule</a></li>
	            <li><a href="#calendar-view">Calendar View</a></li>
	            <li><a href="#history">History</a></li>
	      	</ul>


	<div id="calendar-view" style="width:100%;">
		<div id="vir-calendar"></div>
	</div><!-- Tab 3 -->
    

    <div id="request-schedule">';
            $user_id = get_current_user_id();
            $user = get_user_by( "id", $user_id);

            $user_name = current_user_can('administrator') ? "" : $user->display_name;
            $user_email = current_user_can('administrator') ? "" : $user->user_email;

            $can = current_user_can('administrator') ? "" : "readonly";

            $user_label=current_user_can('administrator') ? "User Name":"Your Name";
            $email_label=current_user_can('administrator') ? "User Email":"Your Email";

            $autocomplete = current_user_can('administrator') ? "vir_auto":"";
                
    	$tabs .= '<form method="post" action="" id="requestSchedule" name="requestSchedule">
          <div class="form-group">
            <label for="InputYourName">'.$user_label.'</label>
            <input type="text" class="form-control '.$autocomplete.'" id="InputYourName" name="InputYourName" value="'.$user_name.'"  placeholder="Enter User Name" '.$can.' required style="width: 100%;padding: 6px 11px;border: 1px solid #ddd;">
          </div>

          <div class="form-group">
            <label for="InputYourEmail">'.$email_label.'</label>
            <input type="email" class="form-control" id="InputYourEmail" name="InputYourEmail" value="'.$user_email.'"  placeholder="Enter User Email" '.$can.' required style="width: 100%;padding: 6px 11px;border: 1px solid #ddd;">
          </div>

          <div class="form-group">
                <label for="InputYourEmail">Phone Number</label>
                <input type="phone" class="form-control" id="InputYourPhone" name="InputYourPhone" value=""  placeholder="+15595439153" required style="width: 100%;padding: 6px 11px;border: 1px solid #ddd;">
          </div>

          <div class="form-group">
            <label for="InputYourMessage"> Message </label>
            
            <textarea class="form-control" id="InputYourMessage" name="InputYourMessage"  placeholder="Enter User Message" rows="6" cols="10" required style="width: 100%;padding: 6px 11px;border: 1px solid #ddd;"></textarea>
          </div>

          <div class="form-group">
            <label for="InputYourDate"> Select Schedule Date Time </label>
            <input type="text" size="60" id="date-range1" name="ScheduleDateTime" class="form-control" id="InputYourDateTime"  placeholder="Enter User Schedule Date & Time" style="width: 100%;padding: 6px 11px;border: 1px solid #ddd;">
          </div>
          <button type="submit" class="btn btn-primary">Submit</button>
        </form>


  	</div><!-- Tab 4 -->

  	<div id="history">';


	global $wpdb; // this is how you get access to the database
	$table_name = $wpdb->base_prefix.'vir_scheules';
	$query = "SELECT * FROM $table_name";
	$query_results = $wpdb->get_results($query);


    $tabs .= '<table class="table table-striped">
      <thead>
        <tr>
          <th scope="col">#</th>
          <th scope="col">User Name</th>
          <th scope="col">User Email</th>
          <th scope="col">Message</th>
          <th scope="col">Schedule Date</th>
          <th scope="col">Status</th>
          <th scope="col">Action</th>
        </tr>
      </thead>
      <tbody>';
        
        $i = 1;
        if(!empty($query_results)){
            foreach ($query_results as $key => $value) {

	            $user = get_user_by( "id", $value->user_id);
	            $dt = explode("to", $value->vir_date);
	            $tabs .= '<tr data-id="'.$value->user_id.'">
	              <td>'.$i.'</td>
	              <td>'.$user->display_name.'</td>
	              <td>'.$user->user_email.'</td>
	              <td>'.$value->vir_message.'</td>
	              <td>'. $dt[0].'<hr>'.$dt[1].'</td>';
	              
	                if($value->vir_status == 0){ 
	                	$tabs .= '<td>'. "<span class='alert alert-success'> Pending... </span>".'</td>';
	                }else if($value->vir_status == 1) { 
	                $tabs .= '<td>'. "<span class='alert alert-danger'> Denied... </span>".'</td>';
	                } else if($value->vir_status == 2) { 
	                $tabs .= '<td>'. "<span class='alert alert-success'> Accepted... </span>".'</td>';
	                } 


	            if($value->vir_status == 0){ 
	                $tabs .= '<td><a href="#" data-id="'.$value->id.'" class="btn btn-danger delete_row"> Delete </a></td>';
	            } 

	            if($value->vir_status == 1){ 
	                $tabs .= '<td><a href="#" data-id="'.$value->id.'" class="btn btn-info"> Denied </a></td>';
	            }

	            if($value->vir_status == 2){
	                $tabs .= '<td><a href="#" data-id="'.$value->id.'" class="btn btn-success"> Accepted </a></td>';
	            }

	            $tabs .= '</tr>';

            	$i++; 
        	} 
       
        } else{ 
           
            $tabs .= '<tr><td colspan="7">Nothing created yet.</td></tr>';
        } 
      
      $tabs .= '</tbody>
    </table>';

  	$tabs .= '</div><!-- Tab 5 -->

</div>';


else:

$tabs .= "<h5> Please <a href='".wp_login_url()."'> login </a> to access this page content </h5>";

endif;

return $tabs;

}
add_shortcode("schedule", "vir_create_schedule_frontend");