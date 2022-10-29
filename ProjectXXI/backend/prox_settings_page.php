<?php
// create custom plugin settings menu
add_action('admin_menu', 'prox_plugin_create_menu');

function prox_plugin_create_menu() {

	//create new top-level menu
	/*add_menu_page('Settings', 'Settings', 'manage_options', "prox_settings", 'prox_plugin_settings_page');
*/

    add_submenu_page(
        'edit.php?post_type=puzzles','Settings', 'Settings', 'manage_options', "prox_settings", 'prox_plugin_settings_page'
    );

	//call register settings function
	add_action( 'admin_init', 'register_prox_plugin_settings' );
}


function register_prox_plugin_settings() {
	//register our settings
	
	register_setting( 'prox-plugin-settings-group', 'fail_redirect_link' );
    register_setting( 'prox-plugin-settings-group', 'late_redirect_link' );
	register_setting( 'prox-plugin-settings-group', 'success_message' );
    register_setting( 'prox-plugin-settings-group', 'fail_message' );
}

function prox_plugin_settings_page() {
?>
<div class="wrap" style="background: #fff;padding: 10px 20px;">
<h1>ProjectXXI Settings</h1><hr>

<form method="post" action="options.php">
    <?php settings_fields( 'prox-plugin-settings-group' ); ?>
    <?php do_settings_sections( 'prox-plugin-settings-group' ); ?>
    <table class="form-table">

         
        <tr valign="top">
        <th scope="row">Fail Redirect Link</th>
        <td><input type="text" name="fail_redirect_link" value="<?php echo esc_attr( get_option('fail_redirect_link') ); ?>" /></td>
        </tr>

         <tr valign="top">
        <th scope="row">Late Redirect Link</th>
        <td><input type="text" name="late_redirect_link" value="<?php echo esc_attr( get_option('late_redirect_link') ); ?>" /></td>
        </tr>
        
        <tr valign="top">
        <th scope="row">Success Message</th>
        <td><input type="text" name="success_message" value="<?php echo esc_attr( get_option('success_message') ); ?>" /></td>
        </tr>

        <tr valign="top">
        <th scope="row">Fail Message</th>
        <td><input type="text" name="fail_message" value="<?php echo esc_attr( get_option('fail_message') ); ?>" /></td>
        </tr>


    </table>
    
    <?php submit_button(); ?>

</form>
</div>
<?php } ?>