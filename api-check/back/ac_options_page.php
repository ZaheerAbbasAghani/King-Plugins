<?php
// create custom plugin settings menu
add_action('admin_menu', 'ac_plugin_create_menu');

function ac_plugin_create_menu() {

	//create new top-level menu
	add_menu_page('API Check Settings', 'API Check', 'manage_options', __FILE__, 'ac_plugin_settings_page' ,'dashicons-yes', 25);

	//call register settings function
	add_action( 'admin_init', 'register_ac_plugin_settings' );
}


function register_ac_plugin_settings() {
	//register our settings
    register_setting( 'ac-plugin-settings-group', 'ac_api_url' );
	register_setting( 'ac-plugin-settings-group', 'ac_api_key' );
	register_setting( 'ac-plugin-settings-group', 'ac_enable_disabled' );
    register_setting( 'ac-plugin-settings-group', 'ac_error_message' );
}

function ac_plugin_settings_page() {
?>
<div class="wrap" style="background: #fff; padding: 10px 20px;">
<h1>API Check Settings</h1><hr>

<form method="post" action="options.php">
    <?php settings_fields( 'ac-plugin-settings-group' ); ?>
    <?php do_settings_sections( 'ac-plugin-settings-group' ); ?>
    <table class="form-table">
      
         
        <tr valign="top">
        <th scope="row">Enable/Disable Check</th>
        <td><input type="checkbox" name="ac_enable_disabled" value="1" <?php checked(1, get_option('ac_enable_disabled'), true); ?> /></td>
        </tr>

        <tr valign="top">
        <th scope="row">API URL</th>
        <td><input type="text" name="ac_api_url" value="<?php echo esc_attr( get_option('ac_api_url') ); ?>" style="width:100%;" placeholder="Add API URL Here"/></td>
        </tr>

        <tr valign="top">
        <th scope="row">API Key</th>
        <td><input type="text" name="ac_api_key" value="<?php echo esc_attr( get_option('ac_api_key') ); ?>" style="width:100%;" placeholder="Add API Key Here"/></td>
        </tr>

        <tr valign="top">
        <th scope="row">Error Message</th>
        <td><input type="text" name="ac_error_message" value="<?php echo esc_attr( get_option('ac_error_message') ); ?>" style="width:100%;" placeholder="Add API Key Here"/></td>
        </tr>
        
      
    </table>
    
    <?php submit_button(); ?>

</form>
</div>
<?php } ?>