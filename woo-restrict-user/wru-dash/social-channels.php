<?php
// create custom plugin settings menu
add_action('admin_menu', 'sl_plugin_create_menu');

function sl_plugin_create_menu() {

	//create new top-level menu
	add_menu_page('Social Latest Posts', 'Social Latest', 'manage_options','social-latest', 'sl_plugin_settings_page' ,  'dashicons-share' );

	//call register settings function
	add_action( 'admin_init', 'register_my_cool_plugin_settings' );
}


function register_my_cool_plugin_settings() {
	//register our settings
	register_setting( 'sl-plugin-settings-group', 'sl_instagram' );
	register_setting( 'sl-plugin-settings-group', 'sl_twitter' );
	register_setting( 'sl-plugin-settings-group', 'sl_facebook' );
}

function sl_plugin_settings_page() {
?>
<div class="wrap" style="background: #fff; padding: 10px 25px;">
<h1>Social Latest Posts</h1>
<hr>
<form method="post" action="options.php">
    <?php settings_fields( 'sl-plugin-settings-group' ); ?>
    <?php do_settings_sections( 'sl-plugin-settings-group' ); ?>
    <table class="form-table">
        <tr valign="top">
        <th scope="row">Instagram</th>
        <td><input type="text" name="sl_instagram" value="<?php echo esc_attr( get_option('sl_instagram') ); ?>" /> <span>Enter Instagram Username without URL</span></td>

        </tr>
         
        <tr valign="top">
        <th scope="row">Twitter</th>
        <td><input type="text" name="sl_twitter" value="<?php echo esc_attr( get_option('sl_twitter') ); ?>" /> <span>Enter Twitter Username without URL</span></td>
        </tr>
        
        <tr valign="top">
        <th scope="row">Facebook</th>
        <td><input type="text" name="sl_facebook" value="<?php echo esc_attr( get_option('sl_facebook') ); ?>" /> <span>Enter Facebook Username without URL</span></td>
        </tr>
    </table>
    
    <?php submit_button(); ?>

</form>
</div>
<?php } ?>