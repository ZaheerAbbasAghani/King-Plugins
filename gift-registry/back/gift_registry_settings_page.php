<?php
// create custom plugin settings menu
add_action('admin_menu', 'gft_plugin_create_menu');

function gft_plugin_create_menu() {

	//create new top-level menu
	add_menu_page('Gift Registry Settings', 'Gift Registry', 'manage_options', 'gft_plugin_settings_page', 'gft_plugin_settings_page', 'dashicons-megaphone', 20 );

	//call register settings function
	add_action( 'admin_init', 'register_gft_plugin_settings' );
}


function register_gft_plugin_settings() {
	//register our settings
	register_setting( 'gft-plugin-settings-group', 'gft_account_sid' );
	register_setting( 'gft-plugin-settings-group', 'gft_auth_token' );
	register_setting( 'gft-plugin-settings-group', 'gft_twilio_phone_number' );
}

function gft_plugin_settings_page() {
?>
<div class="wrap" style="background: #fff; padding: 10px 20px;">
<h1>Gift Registry Plugin Settings</h1> <hr>

<?php settings_errors(); ?>

<form method="post" action="options.php">
    <?php settings_fields( 'gft-plugin-settings-group' ); ?>
    <?php do_settings_sections( 'gft-plugin-settings-group' ); ?>
    <table class="form-table">
        <tr valign="top">
        <th scope="row">Account SID</th>
        <td><input type="text" name="gft_account_sid" value="<?php echo esc_attr( get_option('gft_account_sid') ); ?>" placeholder="Enter Twilio Account SID" style="width:100%;"/></td>
        </tr>
         
        <tr valign="top">
        <th scope="row">Auth Token</th>
        <td><input type="text" name="gft_auth_token" value="<?php echo esc_attr( get_option('gft_auth_token') ); ?>" placeholder="Enter Twilio Account Auth Token" style="width:100%;"/></td>
        </tr>
        
        <tr valign="top">
        <th scope="row">My Twilio phone number</th>
        <td><input type="text" name="gft_twilio_phone_number" value="<?php echo esc_attr( get_option('gft_twilio_phone_number') ); ?>" placeholder="Enter Twilio Account Phone Number" style="width:100%;"/></td>
        </tr>
    </table>
    
    <?php submit_button(); ?>

</form>
</div>
<?php } ?>