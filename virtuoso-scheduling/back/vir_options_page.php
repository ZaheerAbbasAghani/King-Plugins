<?php
// create custom plugin settings menu
add_action('admin_menu', 'vir_plugin_create_menu');

function vir_plugin_create_menu() {

	//create new top-level menu
	add_menu_page('Settings', 'Settings', 'manage_options', 'vir_settings_page', 'vir_plugin_settings_page', 'dashicons-clock', 25);

	//call register settings function
	add_action( 'admin_init', 'register_vir_plugin_settings' );
}


function register_vir_plugin_settings() {
	//register our settings
	register_setting( 'vir-plugin-settings-group', 'vir_twilio_account_sid' );
	register_setting( 'vir-plugin-settings-group', 'vir_twilio_auth_token' );
    register_setting( 'vir-plugin-settings-group', 'vir_twilio_phone_number' );

    register_setting( 'vir-plugin-settings-group2', 'vir_admin_email' );
    register_setting( 'vir-plugin-settings-group2', 'vir_email_subject' );
    register_setting( 'vir-plugin-settings-group2', 'vir_email_heading' );
    register_setting( 'vir-plugin-settings-group2', 'vir_email_body' );
    

    
}

function vir_plugin_settings_page() {
?>

<style type="text/css">
.ui-widget {
    font-size: 15px !important;
}
td{
    vertical-align: middle !important;
}
</style>
<div class="wrap" style="background: #fff; padding: 10px 20px;">
<h1>Virtuoso Scheduling</h1><hr>


    <div id="tabs">
      <ul>
        <?php if(current_user_can('administrator')): ?>
            <li><a href="#vir_settings">Settings</a></li>
            <li><a href="#email-template">Email / Message Template</a></li>
        <?php endif; ?>

        <?php //if(current_user_can('vir_employee')): ?>
            <li><a href="#request-schedule">Request a Schedule</a></li>
        <?php //endif; ?>

            <li><a href="#calendar-view">Calendar View</a></li>
            <li><a href="#history">History</a></li>
      </ul>

    <?php if(current_user_can('administrator')): ?>
    <div id="vir_settings">

        <?php settings_errors(); ?>

        <form method="post" action="options.php">
            <?php settings_fields( 'vir-plugin-settings-group' ); ?>
            <?php do_settings_sections( 'vir-plugin-settings-group' ); ?>
            <table class="form-table">
               
                 
                <tr valign="top">
                <th scope="row">Twilio Account SID</th>
                <td><input type="text" name="vir_twilio_account_sid" value="<?php echo esc_attr( get_option('vir_twilio_account_sid') ); ?>" style="width: 100%;padding: 6px 11px;border: 1px solid #ddd;" placeholder="ACdd0962bf4c05df7850759c9ec10a8aaa"/></td>
                </tr>
                
                <tr valign="top">
                <th scope="row">Twilio Auth Token</th>
                <td><input type="text" name="vir_twilio_auth_token" value="<?php echo esc_attr( get_option('vir_twilio_auth_token') ); ?>" style="width: 100%;padding: 6px 11px;border: 1px solid #ddd;" placeholder="a3332f88b1b1066ddab75452c5f26aaa"/></td>
                </tr>

                <tr valign="top">
                <th scope="row">Twilio Phone Number</th>
                <td><input type="text" name="vir_twilio_phone_number" value="<?php echo esc_attr( get_option('vir_twilio_phone_number') ); ?>" style="width: 100%;padding: 6px 11px;border: 1px solid #ddd;" placeholder="+15595439153"/></td>
                </tr>

            </table>
            
            <?php submit_button(); ?>

        </form>

    </div><!-- Tab 1 -->

     <div id="email-template">
        
    <form method="post" action="options.php">
    <?php settings_fields( 'vir-plugin-settings-group2' ); ?>
    <?php do_settings_sections( 'vir-plugin-settings-group2' ); ?>
    <table class="form-table">

        <tr valign="top">
        <th scope="row">Admin Email</th>
        <td><input type="text" name="vir_admin_email" value="<?php echo esc_attr( get_option('vir_admin_email') ); ?>" style="width: 100%;padding: 6px 11px;border: 1px solid #ddd;" placeholder="admin@domain.com"/></td>
        </tr>
        
        <tr valign="top">
        <th scope="row">Subject</th>
        <td><input type="text" name="vir_email_subject" value="<?php echo esc_attr( get_option('vir_email_subject') ); ?>" placeholder="Enter Email Subject" style="width: 100%;padding: 6px 11px;border: 1px solid #ddd;"/></td>
        </tr>
         
        <tr valign="top">
        <th scope="row">Heading</th>
        <td><input type="text" name="vir_email_heading" value="<?php echo esc_attr( get_option('vir_email_heading') ); ?>" placeholder="Enter Email Heading" style="width: 100%;padding: 6px 11px;border: 1px solid #ddd;"/></td>
        </tr>
        
        <tr valign="top">
        <th scope="row">Body</th>
        <td>
            <?php 
            $vir_email_body = get_option( 'vir_email_body' );
            $settings = array( 
                'editor_height' => 200,
                'textarea_rows' => 20,
                'wpautop' => false,
             );

            echo wp_editor( wpautop( $vir_email_body ) , 'vir_email_body',  $settings);

            ?>
        <br> <h6> Template Tags: </h6> <i>{name}, {email}, {message}, {schedule_request_date_time}, {schedule_request_created_at}, {requests_url}</i>

        </td>
        </tr>
    </table>
    
    <?php submit_button(); ?>

    </form> 
    </div>

    <!-- Tab 2 -->

    <?php endif; ?>

    
    <div id="calendar-view">
       <div id='vir-calendar'></div>
    </div><!-- Tab 3 -->
    

    <div id="request-schedule">

        <?php 

            $user_id = get_current_user_id();
            $user = get_user_by( "id", $user_id);

            $user_name = current_user_can('administrator') ? "" : $user->display_name;
            $user_email = current_user_can('administrator') ? "" : $user->user_email;

            $can = current_user_can('administrator') ? "" : "readonly";

            $user_label=current_user_can('administrator') ? "User Name":"Your Name";
            $email_label=current_user_can('administrator') ? "User Email":"Your Email";

            $autocomplete = current_user_can('administrator') ? "vir_auto":"";

        ?>
                
        <form method="post" action="" id="requestSchedule" name="requestSchedule">
          <div class="form-group">
            <label for="InputYourName"><?php echo $user_label; ?></label>
            <input type="text" class="form-control <?php echo $autocomplete; ?>" id="InputYourName" name="InputYourName" value="<?php echo $user_name;?>"  placeholder="Enter User Name" <?php echo $can; ?> required style="width: 100%;padding: 6px 11px;border: 1px solid #ddd;">
          </div>

          <div class="form-group">
            <label for="InputYourEmail"><?php echo $email_label; ?></label>
            <input type="email" class="form-control" id="InputYourEmail" name="InputYourEmail" value="<?php echo $user_email;?>"  placeholder="Enter User Email" <?php echo $can; ?> required style="width: 100%;padding: 6px 11px;border: 1px solid #ddd;">
          </div>

          <div class="form-group">
                <label for="InputYourEmail">Phone Number</label>
                <input type="phone" class="form-control" id="InputYourPhone" name="InputYourPhone" value=""  placeholder="+15595439153"  required style="width: 100%;padding: 6px 11px;border: 1px solid #ddd;">
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


    <div id="history">
        
    <?php 

    if(!current_user_can('administrator')){

        global $wpdb; // this is how you get access to the database
        $table_name = $wpdb->base_prefix.'vir_scheules';
        $query = "SELECT * FROM $table_name";
        $query_results = $wpdb->get_results($query);

    ?>

    <table class="table table-striped">
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
      <tbody>
        
        <?php 
        $i = 1;

        if(!empty($query_results)){
            foreach ($query_results as $key => $value) {

                $user = get_user_by( "id", $value->user_id);

               ?>
            <tr data-id="<?php echo $value->user_id; ?>">
                <td><?php echo $i; ?></td>
                <td class='displayName'><?php echo $user->display_name; ?></td>
                <td><?php echo $user->user_email; ?></td>
                <td><?php echo $value->vir_message; ?></td>
                <td><?php $dt =  explode("to", $value->vir_date); echo $dt[0].'<hr>'.$dt[1]; ?></td>
                
              
                <?php if($value->vir_status == 0){ ?>
                <td><?php echo "<span class='alert alert-success'> Pending... </span>"; ?></td>
                <?php } else if($value->vir_status == 1) { ?>
                <td><?php echo "<span class='alert alert-danger'> Denied... </span>"; ?></td>
                 <?php } else if($value->vir_status == 2) { ?>
                <td><?php echo "<span class='alert alert-success'> Accepted... </span>"; ?></td>
                <?php } ?>

                <?php if($value->user_id == get_current_user_id()): ?>
                    <td><a href="#" data-id="<?php echo $value->id; ?>" class="btn btn-danger delete_row"> Delete </a></td>
                <?php else:  ?>

                    <?php if($value->user_id == get_current_user_id()){ ?>
                        
                        <?php  if($value->vir_status == 0){ ?>
                            <td><a href="#" data-id="<?php echo $value->id; ?>" class="btn btn-success"> Pending... </a></td>
                        <?php } ?>
                        
                    <?php } else{ ?>

                        <?php if($value->vir_status == 0){ ?>
                            <td><a href="#" data-id="<?php echo $value->id; ?>" class="btn btn-success cover_request"> Cover </a></td>
                        <?php } ?>

                    <?php } ?>

                    <?php if($value->vir_status == 1){ ?>
                        <td><a href="#" data-id="<?php echo $value->id; ?>" class="btn btn-info"> Denied </a></td>
                    <?php } ?>

                    <?php if($value->vir_status == 2){ ?>
                        <td><a href="#" data-id="<?php echo $value->id; ?>" class="btn btn-success"> Accepted </a></td>
                    <?php } ?>

                <?php endif; ?>
            </tr>

            <?php $i++; } ?>
        <?php } else{ ?>

             <tr><td colspan="7">Nothing created yet.</td></tr>

        <?php } ?>
      
      </tbody>
    </table>

    <?php } else{ 


        global $wpdb; // this is how you get access to the database
        $table_name = $wpdb->base_prefix.'vir_scheules';
        $query = "SELECT * FROM $table_name";
        $query_results = $wpdb->get_results($query);

    ?>

    <table class="table table-striped">
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
      <tbody>
        
        <?php 
        $i = 1;

        if(!empty($query_results)){
            foreach ($query_results as $key => $value) {

                $user = get_user_by( "id", $value->user_id);

               ?>
            <tr data-id="<?php echo $value->user_id; ?>">
              <td><?php echo $i; ?></td>
              <td><?php echo $user->display_name; ?></td>
              <td><?php echo $user->user_email; ?></td>
              <td><?php echo $value->vir_message; ?></td>
              <td><?php $dt =  explode("to", $value->vir_date); echo $dt[0].'<hr>'.$dt[1]; ?></td>
              
                <?php if($value->vir_status == 0){ ?>
                <td><?php echo "<span class='alert alert-success'> Pending... </span>"; ?></td>
                <?php } else if($value->vir_status == 1) { ?>
                <td><?php echo "<span class='alert alert-danger'> Denied... </span>"; ?></td>
                 <?php } else if($value->vir_status == 2) { ?>
                <td><?php echo "<span class='alert alert-success'> Accepted... </span>"; ?></td>
                <?php } ?>


            <?php  if($value->vir_status == 0){ ?>
                <td><a href="#" data-id="<?php echo $value->id; ?>" class="btn btn-danger "> Deny </a></td>
            <?php } ?>

            <?php if($value->vir_status == 1){ ?>
                <td><a href="#" data-id="<?php echo $value->id; ?>" class="btn btn-info"> Denied </a></td>
            <?php } ?>

            <?php if($value->vir_status == 2){ ?>
                <td><a href="#" data-id="<?php echo $value->id; ?>" class="btn btn-success"> Accepted </a></td>
            <?php } ?>


            </tr>

            <?php $i++; } ?>
        <?php } else{ ?>

             <tr><td colspan="7">Nothing created yet.</td></tr>

        <?php } ?>
      
      </tbody>
    </table>



    <?php } ?>


    </div><!-- Tab 5 -->

   


</div>
<?php } ?>