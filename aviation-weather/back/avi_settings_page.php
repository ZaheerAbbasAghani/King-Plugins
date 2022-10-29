<?php
// create custom plugin settings menu
add_action('admin_menu', 'avi_plugin_create_menu');

function avi_plugin_create_menu() {

	//create new top-level menu
	add_menu_page('Aviation Weather Settings', 'Aviation Weather', 'manage_options', 'avi_aviation_weather_settings', 'avi_plugin_settings_page','dashicons-airplane
', 25);

	//call register settings function
	add_action( 'admin_init', 'register_avi_plugin_settings' );
}


function register_avi_plugin_settings() {
	//register our settings
	register_setting( 'avi-plugin-settings-group', 'avi_api_key' );
	/*register_setting( 'avi-plugin-settings-group', 'some_other_option' );
	register_setting( 'avi-plugin-settings-group', 'option_etc' );*/
}

function avi_plugin_settings_page() {
?>
<div class="wrap" style="background: #fff; padding: 10px 20px;">
<h1>Aviation Weather Settings </h1><hr>
<?php settings_errors(); ?>
<form method="post" action="options.php">
    <?php settings_fields( 'avi-plugin-settings-group' ); ?>
    <?php do_settings_sections( 'avi-plugin-settings-group' ); ?>
    <table class="form-table">
        <tr valign="top">
        <th scope="row">API KEY</th>
        <td><input type="text" name="avi_api_key" value="<?php echo esc_attr( get_option('avi_api_key') ); ?>" style="width: 100%;"/><br>
            <i>To make aviation weather plugin work you must add api key.</i>
        </td>
        </tr>

        <tr valign="top">
        <th scope="row">Shortcode</th>
        <td> User this shortcode [aviation-weather] anywhere on site to show search form</td>
        </tr>

    </table>
    
    <?php submit_button(); ?>

</form>
</div>

<?php } ?>