<?php


// create custom plugin settings menu
add_action('admin_menu', 'cm_plugin_create_menu');

function cm_plugin_create_menu() {

	//create new top-level menu
	add_menu_page('Call Manager Settings', 'Call Manager Settings', 'manage_options', 'cm_call_manager', 'cm_plugin_settings_page','dashicons-bell', 25);

	//call register settings function
	add_action( 'admin_init', 'register_cm_plugin_settings' );
}


function register_cm_plugin_settings() {
	//register our settings
	register_setting( 'cm-plugin-settings-group', 'auth_id' );
	register_setting( 'cm-plugin-settings-group', 'auth_token' );
	//register_setting( 'cm-plugin-settings-group', 'option_etc' );
}

function cm_plugin_settings_page() {





?>
<div class="wrap" style="background: #fff; padding: 10px 20px;">
<h1>Call Manager Plugin Setting</h1><hr>





<form method="post" action="options.php">
    <?php settings_fields( 'cm-plugin-settings-group' ); ?>
    <?php do_settings_sections( 'cm-plugin-settings-group' ); ?>
    <table class="form-table">
        <tr valign="top">
        <th scope="row">Account SID:</th>
        <td><input type="text" name="auth_id" value="<?php echo esc_attr( get_option('auth_id') ); ?>" style="width: 100%;margin-bottom: 5px;"/><br><i style="color:#ccc;">You can get this information from site <a href="https://www.twilio.com/"> Twilio </a></i></td>
        </tr>
         
        <tr valign="top">
        <th scope="row">Auth Token:</th>
        <td><input type="text" name="auth_token" value="<?php echo esc_attr( get_option('auth_token') ); ?>" style="width: 100%;margin-bottom: 5px;"/><br><i style="color:#ccc;">You can get this information from site <a href="https://www.twilio.com/"> Twilio </a></i></td>
        </tr>
    
    </table>
    
    <?php submit_button(); ?>

</form>
</div>
<?php } ?>