<?php
// create custom plugin settings menu
add_action('admin_menu', 'wfu_plugin_create_menu');

function wfu_plugin_create_menu() {

	//create new top-level menu
	add_menu_page('Uploader', 'Uploader', 'manage_options', "wfu_uploader", 'wfu_plugin_settings_page' , 'dashicons-cover-image', 40 );

	//call register settings function
	add_action( 'admin_init', 'register_wfu_plugin_settings' );
}


function register_wfu_plugin_settings() {
	//register our settings
	register_setting( 'wfu-plugin-settings-group', 'new_option_name' );
	register_setting( 'wfu-plugin-settings-group', 'new_option_name2' );
}

function wfu_plugin_settings_page() {
?>
<div class="wrap" style="background: #fff;box-shadow: 2px 2px 2px #ddd; padding: 10px 20px;">
<h1>Woocommerce File Uploader</h1>

<form method="post" action="options.php">
    <?php settings_fields( 'wfu-plugin-settings-group' ); ?>
    <?php do_settings_sections( 'wfu-plugin-settings-group' ); ?>
    <table class="form-table">
        <tr valign="top">
        <th scope="row">Image Uplaod Size (MB)</th>
        <td><input type="text" name="new_option_name" value="<?php echo esc_attr( get_option('new_option_name') ); ?>" />
            <i>Image Upload Size must be in mb</i>
        </td>
        </tr>

         <tr valign="top">
        <th scope="row">Admin Email</th>
        <td><input type="text" name="new_option_name2" value="<?php echo esc_attr( get_option('new_option_name2') ); ?>" />
            <i>You can add email where you want to receive user message</i>
        </td>
        </tr>
         
    </table>
    
    <?php submit_button(); ?>

</form>
</div>
<?php } ?>