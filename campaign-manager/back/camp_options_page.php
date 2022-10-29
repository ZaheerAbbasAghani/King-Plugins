<?php
// create custom plugin settings menu
add_action('admin_menu', 'my_cool_plugin_create_menu');

function my_cool_plugin_create_menu() {

	//create new top-level menu
	add_submenu_page('edit.php?post_type=campaigns','Settings', 'Settings', 'manage_options', 'campaigns_settings', 'campaign_plugin_settings_page');

	//call register settings function
	add_action( 'admin_init', 'campaign_plugin_settings' );
}


function campaign_plugin_settings() {
	//register our settings
	register_setting( 'camp-plugin-settings-group', 'camp_account_sid');
	register_setting( 'camp-plugin-settings-group', 'camp_auth_token' );
	register_setting( 'camp-plugin-settings-group', 'camp_phone_number' );
}

function campaign_plugin_settings_page() {
?>
<div class="wrap" style="background: #fff;padding: 10px 20px;">
<h1>Campaing Manager</h1><hr>

<?php settings_errors(); ?>

<form method="post" action="options.php">
    <?php settings_fields( 'camp-plugin-settings-group' ); ?>
    <?php do_settings_sections( 'camp-plugin-settings-group' ); ?>
    <table class="form-table">
        <tr valign="top">
        <th scope="row">ACCOUNT SID</th>
        <td><input type="text" name="camp_account_sid" value="<?php echo esc_attr( get_option('camp_account_sid') ); ?>" placeholder="ACCOUNT SID" style="width:100%;"/></td>
        </tr>
         
        <tr valign="top">
        <th scope="row">AUTH TOKEN</th>
        <td><input type="text" name="camp_auth_token" value="<?php echo esc_attr( get_option('camp_auth_token') ); ?>" placeholder="AUTH TOKEN" style="width:100%;"/></td>
        </tr>

        <tr valign="top">
        <th scope="row">PHONE NUMBER</th>
        <td><input type="text" name="camp_phone_number" value="<?php echo esc_attr( get_option('camp_phone_number') ); ?>" placeholder="PHONE NUMBER" style="width:100%;"/></td>
        </tr>
        
    </table>
    
    <?php submit_button(); ?>


<?php 

$votable = get_user_meta(52, "votable", false );
print_r($votable);

?>


</form>
</div>
<?php } ?>