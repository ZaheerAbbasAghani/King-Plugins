<?php 

function lms_off_form($form){
$form = "";
if(is_user_logged_in()){


    $user_id = get_current_user_id();

    $user = get_user_by( "id", $user_id);


  $form .= '<div class="offWrapper">


<ul class="nav nav-tabs" id="myTab" role="tablist">
  <li class="nav-item">
    <a class="nav-link active" id="request-leave" data-toggle="tab" href="#requestLeave" role="tab" aria-controls="requestLeave" aria-selected="true">Request a Leave</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" id="history-tab" data-toggle="tab" href="#history" role="tab" aria-controls="history" aria-selected="false">History</a>
  </li>
 
  <li class="nav-item">
    <a class="nav-link" id="calendar-view" data-toggle="tab" href="#calendar_view" role="tab" aria-controls="calendar_view" aria-selected="false">Calendar View</a>
  </li>

</ul>
<div class="tab-content" id="myTabContent">
  <div class="tab-pane fade show active" id="requestLeave" role="tabpanel" aria-labelledby="request-leave">

     <div class="LeftSide">
    <form method="post" action="" id="off_form" name="off_form">
      <div class="form-group">
        <label for="InputYourName">Your Name</label>
        <input type="text" class="form-control" id="InputYourName" name="InputYourName" value="'.$user->display_name.'"  placeholder="Enter Your Name" readonly required>
      </div>

      <div class="form-group">
        <label for="InputYourEmail">Your Email</label>
        <input type="email" class="form-control" id="InputYourEmail" name="InputYourEmail" value="'.$user->user_email.'"  placeholder="Enter Your Email" readonly required>
      </div>

      <div class="form-group">
        <label for="InputYourMessage"> Message </label>
        
        <textarea class="form-control" id="InputYourMessage" name="InputYourMessage"  placeholder="Enter Your Message" rows="6" cols="10" required></textarea>
      </div>

      <div class="form-group">
        <label for="InputYourDate"> Select Date Time Off </label>
        <input type="text" size="60" id="date-range1" name="DateTimeOff" class="form-control" id="InputYourDateTime"  placeholder="Enter Your Date & Time Off">
      </div>
      <button type="submit" class="btn btn-primary">Submit</button>
    </form>
  </div>

  </div>
  <div class="tab-pane fade" id="history" role="tabpanel" aria-labelledby="history-tab">';

   $form .= '<div class="RightSide">';


    global $wpdb; // this is how you get access to the database
    $table_name = $wpdb->base_prefix.'lms_records';
    $query = "SELECT * FROM $table_name WHERE user_id='$user_id'";
    $query_results = $wpdb->get_results($query);


  $form .= '<table id="lms_list" class="display" style="width:100%;text-align:center;">
    <thead>
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Leave Date/Time</th>
            <th>Status</th>
            <th>Created at</th>
        </tr>
    </thead>
    <tbody>
    ';
      
        $created_at = current_time( 'mysql' );
        foreach ($query_results  as $result) {
            $form .= "<tr><td>".$user->display_name."</td>";
            $form .= "<td>".$user->user_email."</td>";
            $form .= "<td>".$result->lms_date."</td>";
            if($result->lms_status == 0){
                $form .= "<td><span style='background:red;padding: 10px;color: #fff;text-decoration: none;font-size: 13px;'>Pending</span></td>";
            }else{
                $form .= "<td><span style='background:green;padding: 10px;color: #fff;text-decoration: none;font-size: 13px;'>On Leave</span></td>";
            }
            $form .= "<td>".$result->created_at."</td></tr>";
        } 
        
    $form .= '
    </tbody>
    <tfoot>
       <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Leave Date/Time</th>
            <th>Status</th>
            <th>Created at</th>
        </tr>
    </tfoot>
    </table>';

    $form .= '</div>';
    $form .= '</div>';


    $form .= ' <div class="tab-pane fade" id="calendar_view" role="tabpanel" aria-labelledby="calendar-view">';


    foreach ($query_results as $result) {

        $user = get_user_by( "id", $result->user_id);
        $pieces = explode("~", $result->lms_date);

        $startDateTime = $pieces[0];
        $endDateTime = $pieces[1];

        $pieces2 = explode(" ", $startDateTime);
        $pieces3 = explode(" ", $endDateTime);

        $leave_data[] = array(
            'title' => "Leave Request of ".$user->display_name,
            'start' => $pieces2[0],
            'end' => $pieces3[1],
        );
    }

    $form .= "<div id='lms-calendar' style='padding:20px 10px;width:100%;height:100%;'></div>";

    $form .= '</div>';

    $form .=' </div>';


  $form .= '</div>';



/*    $form .= '<script type="text/javascript">

        window.addEventListener("DOMContentLoaded", function() {
            var calendarEl = document.getElementById("lms-calendar");
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialDate: "2022-04-01",
                editable: true,
                selectable: true,
                //contentHeight: auto,
                //businessHours: true,
                //dayMaxEvents: true,
                events: '.json_encode($leave_data).',
            });

            calendar.render();
        }, false);

    </script>';*/



}else{

    $loginUrl = esc_url( wp_login_url( get_permalink() ) );
    $form .= '<div class="offWrapper"><p> Please <a href="'.$loginUrl.'"> login </a> to continue. </p></div>';
}


return $form;

}
add_shortcode( "time_off", "lms_off_form");