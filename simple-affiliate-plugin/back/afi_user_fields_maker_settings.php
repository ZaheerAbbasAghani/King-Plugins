<?php
// create custom plugin settings menu
add_action('admin_menu', 'afi_plugin_create_menu');

function afi_plugin_create_menu() {

	//create new top-level menu
	add_menu_page('User Maker', 'Affiliate Fields', 'manage_options', 'afi_user_maker_settings', 'my_cool_plugin_settings_page', 'dashicons-admin-tools', 30);

	//call register settings function
	add_action( 'admin_init', 'register_afi_plugin_settings' );
}


function register_afi_plugin_settings() {
	//register our settings
    register_setting( 'afi-plugin-settings-group', 'cookie_name' );
	   /*register_setting( 'afi-plugin-settings-group', 'some_other_option' );
	register_setting( 'afi-plugin-settings-group', 'option_etc' );*/
}

function my_cool_plugin_settings_page() {
?>
<div class="wrap" style="background: #fff; padding: 10px 20px;">
<h1>User Fields Maker</h1><hr>

<form method="post" action="options.php">
    <?php settings_fields( 'afi-plugin-settings-group' ); ?>
    <?php do_settings_sections('afi-plugin-settings-group'); ?>
    <table class="form-table">
        <tr valign="top">
        <th scope="row">Cookie Name</th>
        <td><input type="text" name="cookie_name" value="<?php echo esc_attr( get_option('cookie_name') ); ?>" /></td>
        </tr>
         
    </table>
    
    <?php submit_button(); ?>

</form>


<hr>

<form method="post" action="" id="afi_field_name_form">
    <table class="table table-bordered" id="dynamic_field" style="width: 100%;">
        <tr>
            <td>
                <!--div class="top-row"-->
                
                <div class="field-wrap">
                    
                    <input type="text" name="afi_field_name[]" required placeholder="Enter Field Name" style="width: 100%;margin:0px;" class='afifield'/>
                </div>
                
            </td>
            <td><button type="button" name="add" id="add" class="button button-default">Add More</button></td>
        </tr>
    </table>
    <input type="button" name="submit" id="afi_submit"  class="button button-primary" value="Submit" style="margin-left: 3px;" />
</form>
<div id="field_code_message"></div>


<table id="afi_fields_table" class="display" style="width:100%; text-align: center;">
        <thead>
            <tr>
                <th>ID</th>
                <th>Field Name</th>
                <th>Send Shortcode </th>
                <th>Fetch Shortcode </th>
                <th>Fetch Referrer Shortcode</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php 

global $wpdb;
$i=2;
$table_name = $wpdb->base_prefix.'fields_list';
$fields = $wpdb->get_results( "SELECT * FROM $table_name", OBJECT ); 

$current_user = wp_get_current_user();

$wizz_user = get_user_meta($current_user->ID,'wizz_user',true);

?>  
  <tr>
                <td><?php echo "1"; ?></td>
               <td>Custom User</td>
                <td>[send_custom_field user] </td>
                <td>[fetch_custom_field user] </td>
                <td> [fetch_custom_field_referrer user] </td>
                <td></td>
            </tr>
   
         <?php foreach ($fields as $field) { ?>
            <tr>
                <td><?php echo $i; ?></td>
                <td><?php echo $field->field; ?></td>
                <td>[send_custom_field <?php echo $field->field; ?>] </td>
                <td>[fetch_custom_field <?php echo $field->field; ?>] </td>
                 <td>[fetch_custom_field_referrer <?php echo $field->field; ?>] </td>
                <td><a href="#" class="afi_remove" id="<?php echo $field->id; ?>" field_name='<?php echo $field->field; ?>'> Remove </a></td>
            </tr>
        <?php 
        $i++;
    } ?>
    
        </tbody>
        <tfoot>
           <tr>
                <th>ID</th>
                <th>Field Name</th>
                <th>Send Shortcode </th>
                <th>Fetch Shortcode </th>
                <th>Fetch Referrer Shortcode</th>
                <th>Action</th>
            </tr>
        </tfoot>
    </table>
<br><br>
<!-- Wordpress Account Details -->
<h1>Wordpress Account Details </h1><hr>
<table id="afi_fields_table_2" class="display" style="width:100%; text-align: center;">
        <thead>
            <tr>
                <th>ID</th>
                <th>Field Name</th>
                <th>Fetch Referrer Shortcode </th>
            </tr>
        </thead>
        <tbody>

    	<tr>
    		<td>1</td>
    		<td>First Name</td>
    		<td>[fetch_custom_field_referrer firstname]</td>
    	</tr>
    	<tr>
    		<td>2</td>
    		<td>Last Name</td>
    		<td>[fetch_custom_field_referrer lastname]</td>
    	</tr>
        <tr>
            <td>3</td>
            <td>Full Name</td>
            <td>[fetch_custom_field_referrer fullname]</td>
        </tr>
    	<tr>
    		<td>4</td>
    		<td>Email</td>
    		<td>[fetch_custom_field_referrer email]</td>
    	</tr>
    	<tr>
    		<td>5</td>
    		<td>Date Register</td>
    		<td>[fetch_custom_field_referrer date_register]</td>
    	</tr>

        <tr>
            <td>6</td>
            <td>Referred User List</td>
            <td>[fetch_custom_field_referrer userlist]</td>
        </tr>

        </tbody>
        <tfoot>
           <tr>
                <th>ID</th>
                <th>Field Name</th>
                <th>Fetch Referrer Shortcode </th>
            </tr>
        </tfoot>
    </table>





</div>
<?php } ?>