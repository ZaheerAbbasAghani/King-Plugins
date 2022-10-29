<?php
// create custom plugin settings menu
add_action('admin_menu', 'fde_plugin_create_menu');

function fde_plugin_create_menu() {

	//create new top-level menu
	add_menu_page('Fiuge Delivery', 'Fiuge Delivery Settings', 'manage_options', 'fde_fiuge_delivery_settings', 'fde_plugin_settings_page', 'dashicons-location
', 25 );

	//call register settings function
	add_action( 'admin_init', 'register_fde_plugin_settings' );
}


function register_fde_plugin_settings() {
	//register our settings
	register_setting( 'fde-plugin-settings-group', 'fde_api_url' );
	register_setting( 'fde-plugin-settings-group', 'fde_api_key' );
    register_setting( 'fde-plugin-settings-group', 'fde_api_post_url' );
	register_setting( 'fde-plugin-settings-group', 'fde_sender_id' );
    register_setting( 'fde-plugin-settings-group', 'fde_store_name' );
    register_setting( 'fde-plugin-settings-group', 'fde_country_code' );
    register_setting( 'fde-plugin-settings-group', 'fde_phone' );
    register_setting( 'fde-plugin-settings-group', 'fde_address' );
    register_setting( 'fde-plugin-settings-group', 'fde_city' );
    register_setting( 'fde-plugin-settings-group', 'fde_zip_code' );
}

function fde_plugin_settings_page() {
    ?>
<div class="wrap" style="background: #fff; padding: 10px 20px;">
<h1>Fiuge Delivery - Settings</h1><hr>

<?php settings_errors(); ?>

<form method="post" action="options.php">
    <?php settings_fields( 'fde-plugin-settings-group' ); ?>
    <?php do_settings_sections( 'fde-plugin-settings-group' ); ?>
    <table class="form-table">
        <tr valign="top">
        <th scope="row">API GET REQUEST URL</th>
        <td><input type="text" name="fde_api_url" value="<?php echo esc_attr( get_option('fde_api_url') ); ?>" style="width: 100%;"/></td>
        </tr>

        <tr valign="top">
        <th scope="row">API POST REQUEST URL</th>
        <td><input type="text" name="fde_api_post_url" value="<?php echo esc_attr( get_option('fde_api_post_url') ); ?>" style="width: 100%;"/></td>
        </tr>
         
        <tr valign="top">
        <th scope="row">API KEY</th>
        <td><input type="text" name="fde_api_key" value="<?php echo esc_attr( get_option('fde_api_key') ); ?>" style="width: 100%;"/></td>
        </tr>

        <tr valign="top">
        <th scope="row">Sender ID</th>
        <td><input type="text" name="fde_sender_id" value="<?php echo esc_attr( get_option('fde_sender_id') ); ?>" style="width: 100%;"/></td>
        </tr>

        <tr valign="top">
        <th scope="row">Store Name</th>
        <td><input type="text" name="fde_store_name" value="<?php echo esc_attr( get_option('fde_store_name') ); ?>" style="width: 100%;"/></td>
        </tr>

        <tr valign="top">
        <th scope="row">Country Code</th>
        <td><input type="text" name="fde_country_code" value="<?php echo esc_attr( get_option('fde_country_code') ); ?>" style="width: 100%;"/></td>
        </tr>

        <tr valign="top">
        <th scope="row">Phone</th>
        <td><input type="text" name="fde_phone" value="<?php echo esc_attr( get_option('fde_phone') ); ?>" style="width: 100%;"/></td>
        </tr>

        <tr valign="top">
        <th scope="row">Address</th>
        <td><input type="text" name="fde_address" value="<?php echo esc_attr( get_option('fde_address') ); ?>" style="width: 100%;"/></td>
        </tr>

        <tr valign="top">
        <th scope="row">City</th>
        <td><input type="text" name="fde_city" value="<?php echo esc_attr( get_option('fde_city') ); ?>" style="width: 100%;"/></td>
        </tr>

        <tr valign="top">
        <th scope="row">Zip Code</th>
        <td><input type="text" name="fde_zip_code" value="<?php echo esc_attr( get_option('fde_zip_code') ); ?>" style="width: 100%;"/></td>
        </tr>
        
    </table>
    
    <?php submit_button(); ?>

</form>
</div>


<?php } ?>