<?php
// create custom plugin settings menu
add_action('admin_menu', 'lcr_plugin_create_menu');

function lcr_plugin_create_menu() {

	//create new top-level menu
	add_menu_page('LMS Student Reports', 'LMS Student Reports', 'manage_options', 'lcr_student_reports', 'lcr_plugin_settings_page', 'dashicons-chart-pie
', 25);

	//call register settings function
	add_action( 'admin_init', 'register_lcr_plugin_settings' );
}


function register_lcr_plugin_settings() {
	//register our settings
	/*register_setting( 'my-cool-plugin-settings-group', 'new_option_name' );
	register_setting( 'my-cool-plugin-settings-group', 'some_other_option' );
	register_setting( 'my-cool-plugin-settings-group', 'option_etc' );*/
}

function lcr_plugin_settings_page() {
?>
<div class="wrap" style="background-color:#fff; padding:10px 20px;">
<h1>LMS Student Reports</h1><hr>


<table border="0" cellspacing="5" cellpadding="5" style="width: 100%;background: #eee;padding: 10px 0px;margin-bottom: 20px;">
<tbody><tr>
    
    <td>Minimum date:</td>
    <td><input type="text" id="min" name="min" placeholder="Choose Minimum Date" style="width:100%;"></td>

    <td>Maximum date:</td>
    <td><input type="text" id="max" name="max" placeholder="Choose Maximum Date" style="width:100%;"></td>

</tr>

</tbody></table>

<table id="example" class="display" style="width:100%;text-align: center;">
        <thead>
            <tr>
                <th>License Number</th>
                <th>Course ID</th>
                <th>Student's License Number</th>
                <th>Quiz Complete Date</th>
                <th>Student's State</th>
            </tr>
        </thead>
        <tbody>

        <?php 

        global $wpdb; 
        $table_name = 'klp_learndash_pro_quiz_statistic_ref';

        $query = "SELECT * FROM $table_name";
        $query_results = $wpdb->get_results($query);

        $state_list = array('AL'=>"Alabama", 'AK'=>"Alaska", 'AZ'=>"Arizona", 'AR'=>"Arkansas",  'CA'=>"California",  'CO'=>"Colorado",  'CT'=>"Connecticut",  'DE'=>"Delaware",  'DC'=>"District Of Columbia",  'FL'=>"Florida",  'GA'=>"Georgia",  'HI'=>"Hawaii",  'ID'=>"Idaho",  'IL'=>"Illinois",  'IN'=>"Indiana",  'IA'=>"Iowa",  'KS'=>"Kansas",  'KY'=>"Kentucky",  'LA'=>"Louisiana",  'ME'=>"Maine",  'MD'=>"Maryland",  'MA'=>"Massachusetts",  'MI'=>"Michigan",  'MN'=>"Minnesota",  'MS'=>"Mississippi",  'MO'=>"Missouri",  'MT'=>"Montana",'NE'=>"Nebraska",'NV'=>"Nevada",'NH'=>"New Hampshire",'NJ'=>"New Jersey",'NM'=>"New Mexico",'NY'=>"New York",'NC'=>"North Carolina",'ND'=>"North Dakota",'OH'=>"Ohio",  'OK'=>"Oklahoma",  'OR'=>"Oregon",  'PA'=>"Pennsylvania",  'RI'=>"Rhode Island",  'SC'=>"South Carolina",  'SD'=>"South Dakota",'TN'=>"Tennessee",  'TX'=>"Texas",  'UT'=>"Utah",  'VT'=>"Vermont",  'VA'=>"Virginia",  'WA'=>"Washington",  'WV'=>"West Virginia",  'WI'=>"Wisconsin",  'WY'=>"Wyoming");


        //print_r($state_list);

    
        foreach ($query_results as $results) {
                
            $courses = learndash_user_get_enrolled_courses($results->user_id,array(), true);
            $qStatistics = learndash_get_quiz_statistics_ref_for_quiz_attempt( $results->user_id, array() );


            echo "<tr>
                    <td>33206</td>
                    <td>".get_post_meta($results->course_post_id, '_course_id', true)."</td>
                    <td>".get_user_meta($results->user_id, 'billing_license_number', true)."</td>
                    <td>".date("Y/m/d",$results->create_time)."</td>";
            $state = get_user_meta($results->user_id, 'billing_licensure_state', true);
            if(in_array($state, $state_list)){
                echo "<td>State=".array_search( $state,$state_list)."</td>";
            }else{
                echo "<td></td>";
            }

            echo "</tr>";


        }

        ?>
          
        </tbody>
        <tfoot>
            <tr>
                <th>License Number</th>
                <th>Course ID</th>
                <th>Student's License Number</th>
                <th>Quiz Complete Date</th>
                <th>Student's State</th>
            </tr>
        </tfoot>
</table>


<?php /*
<form method="post" action="options.php">
    <?php settings_fields( 'my-cool-plugin-settings-group' ); ?>
    <?php do_settings_sections( 'my-cool-plugin-settings-group' ); ?>
    <table class="form-table">
        <tr valign="top">
        <th scope="row">New Option Name</th>
        <td><input type="text" name="new_option_name" value="<?php echo esc_attr( get_option('new_option_name') ); ?>" /></td>
        </tr>
         
        <tr valign="top">
        <th scope="row">Some Other Option</th>
        <td><input type="text" name="some_other_option" value="<?php echo esc_attr( get_option('some_other_option') ); ?>" /></td>
        </tr>
        
        <tr valign="top">
        <th scope="row">Options, Etc.</th>
        <td><input type="text" name="option_etc" value="<?php echo esc_attr( get_option('option_etc') ); ?>" /></td>
        </tr>
    </table>
    
    <?php submit_button(); ?>
</form>

*/ ?>

</div>
<?php } ?>