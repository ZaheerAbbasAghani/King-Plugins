<?php
// create custom plugin settings menu
add_action('admin_menu', 'lms_plugin_create_menu');

function lms_plugin_create_menu() {

	//create new top-level menu
	add_menu_page('Time Off Settings', 'Time Off Settings', 'manage_options', 'lms_time_off_settings', 'lms_plugin_settings_page', 'dashicons-bell', 25 );

	//call register settings function
	add_action( 'admin_init', 'register_lms_plugin_settings' );
}


function register_lms_plugin_settings() {
	//register our settings
	register_setting( 'lms-plugin-settings-group', 'email_address' );
    register_setting( 'lms-plugin-settings-group', 'email_subject' );
	register_setting( 'lms-plugin-settings-group', 'email_heading' );
	register_setting( 'lms-plugin-settings-group', 'email_body' );
}

function lms_plugin_settings_page() {
?>

<style type="text/css">
    #leave-request{
        overflow: scroll;
    }
</style>

<div class="wrap" style="background: #fff; padding: 10px 20px;">
<h1>Time Off Settings</h1> <hr>


<div id="tabs">
  <ul>
    <li><a href="#leave-request">Leave Request</a></li>
    <li><a href="#email-template">Email Template</a></li>
    <li><a href="#calendar-view">Calendar View</a></li>
  </ul>
  <div id="leave-request">
    
    <?php 

    global $wpdb; // this is how you get access to the database
    $table_name = $wpdb->base_prefix.'lms_records';
    $query = "SELECT * FROM $table_name";
    $query_results = $wpdb->get_results($query);

    ?>
    <table id="lms_list" class="display" style="width:100%;text-align: center;">
    <thead>
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Message</th>
            <th>Date/Time</th>
            <th>Created at</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        
        <?php 

        $created_at = current_time( 'mysql' );
        foreach ($query_results  as $result) {

            $user = get_user_by( "id", $result->user_id);

            echo "<tr><td data-name='".$user->display_name."'>".$user->display_name."</td>";
            echo "<td class='user_email'>".$user->user_email."</td>";
            echo "<td>".$result->lms_message."</td>";
            echo "<td>".$result->lms_date."</td>";
            echo "<td>".$result->created_at."</td>";

            $approve = $result->lms_status == 1 ? "selected" : "";
            $deny = $result->lms_status == 2 ? "selected" : "";

            echo "<td><select class='leaveStatus' data-id='".$result->id."'>
                <option value=''> Select Leave Status </option>
                <option value='1' ".$approve."> Approve </option>
                <option value='2' ".$deny."> Deny </option>
            </select></td>";
            echo "<td><a href='#' class='button button-default dltbtn' data-id='".$result->id."'>Delete</a></td></tr>";

        } ?>
        
    </tbody>
    <tfoot>
         <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Message</th>
            <th>Date/Time</th>
            <th>Created at</th>
            <th>Status</th>
            <th>Action</th>
        </tr>   
    </tfoot>
    </table>




  </div>
  <div id="email-template">
        
    <?php settings_errors(); ?>
    <form method="post" action="options.php">
    <?php settings_fields( 'lms-plugin-settings-group' ); ?>
    <?php do_settings_sections( 'lms-plugin-settings-group' ); ?>
    <table class="form-table">
        
        <tr valign="top">
        <th scope="row">Admin Email</th>
        <td><input type="email" name="email_address" value="<?php echo esc_attr( get_option('email_address') ); ?>" placeholder="Enter Admin Email Address" style="width:100%"/></td>
        </tr>

        <tr valign="top">
        <th scope="row">Subject</th>
        <td><input type="text" name="email_subject" value="<?php echo esc_attr( get_option('email_subject') ); ?>" placeholder="Enter Email Subject" style="width:100%"/></td>
        </tr>
         
        <tr valign="top">
        <th scope="row">Heading</th>
        <td><input type="text" name="email_heading" value="<?php echo esc_attr( get_option('email_heading') ); ?>" placeholder="Enter Email Heading" style="width:100%"/></td>
        </tr>
        
        <tr valign="top">
        <th scope="row">Body</th>
        <td>

            <?php 

            $email_body = get_option( 'email_body' );
            $settings = array( 
                'editor_height' => 425,
                'textarea_rows' => 20,
                'wpautop' => false,
             );

            echo wp_editor( wpautop( $email_body ) , 'email_body',  $settings); 

            ?>
           
        <h4> Template Tags: </h4><i>{name}, {email}, {message}, {leave_request_date_time}, {leave_request_created_at}, {requests_url}</i>

        </td>
        </tr>
    </table>
    
    <?php submit_button(); ?>

    </form> 
    </div>




    <div id="calendar-view">

        <?php 

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

        ?>

       <div id='lms-calendar' style='max-width: 1100px;margin: 0 auto;'></div>
    </div>



    <script type="text/javascript">
        

    document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('lms-calendar');
    var today = moment().day();
    var calendar = new FullCalendar.Calendar(calendarEl, {
      firstDay: today,
      dayMaxEvents: true, // allow "more" link when too many events
      events: <?php echo json_encode($leave_data); ?>,
    });

    calendar.render();
  });

    </script>


</div>


</div>
<?php } ?>