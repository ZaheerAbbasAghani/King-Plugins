<?php
// create custom plugin settings menu
add_action('admin_menu', 'my_cool_plugin_create_menu');

function my_cool_plugin_create_menu() {

	//create new top-level menu
	add_menu_page('SMS Settings', 'SMS Settings', 'manage_options', 'wsm_api_settings_page_process', 'wsm_plugin_settings_page' , "dashicons-thumbs-up", 50 );

	//call register settings function
	add_action( 'admin_init', 'register_my_cool_plugin_settings' );
}


function register_my_cool_plugin_settings() {
    //register our settings
    register_setting( 'wsm-plugin-settings-group', 'wsm_api_key');	
    register_setting( 'wsm-plugin-settings-group', 'wsm_slot_sim'); 
    register_setting( 'wsm-plugin-settings-group', 'wsm_device_id'); 
    // Make sure enable/disable
 register_setting( 'wsm-plugin-settings-group', 'enable_disable_pending_payment' );
 register_setting( 'wsm-plugin-settings-group', 'enable_disable_processing' );
 register_setting( 'wsm-plugin-settings-group', 'enable_disable_on_hold' );
 register_setting( 'wsm-plugin-settings-group', 'enable_disable_completed' );
 register_setting( 'wsm-plugin-settings-group', 'enable_disable_cancel' );
 register_setting( 'wsm-plugin-settings-group', 'enable_disable_refunded' );
 register_setting( 'wsm-plugin-settings-group', 'enable_disable_failed' );

//Status Messages

 register_setting( 'wsm-plugin-settings-group', 'wsm_status_pending_payment' );
 register_setting( 'wsm-plugin-settings-group', 'wsm_status_processing' );
 register_setting( 'wsm-plugin-settings-group', 'wsm_status_on_hold' );
 register_setting( 'wsm-plugin-settings-group', 'wsm_status_completed' );
 register_setting( 'wsm-plugin-settings-group', 'wsm_status_cancel' );
 register_setting( 'wsm-plugin-settings-group', 'wsm_status_refunded' );
 register_setting( 'wsm-plugin-settings-group', 'wsm_status_failed' );


}

function wsm_plugin_settings_page() {
?>
<div class="wrap" style="background: #fff;padding: 20px;box-shadow: 2px 2px 2px #ddd, -2px -2px 2px #ddd;">
<div class="title_head" style="text-align: center;">
	<img src="<?php echo plugins_url('', dirname(__FILE__) ).'/assets/images/telegram.png'; ?>" style="width: 80px;">
	<h1 style="margin-bottom: 20px;">Woocommerce - Order Status SMS Settings</h1><hr>

<?php 
    $wsm_status_processing=get_option('wsm_status_processing');
    $order_processing = explode(" ", $wsm_status_processing);
    
    echo " by SMS-MARKETING.RO "



?>

</div>

<form method="post" action="options.php">
    <?php settings_fields( 'wsm-plugin-settings-group' ); ?>
    <?php do_settings_sections( 'wsm-plugin-settings-group' ); ?>
    <table class="form-table">
	<p> You can use TAGs when you creat the sms text: {name} {address} {amount} {ordernumber}</p>
        <tr valign="top">
        <th scope="row">Pending Payment</th>
        <td><textarea name="wsm_status_pending_payment" style="width: 100%;" rows="5"><?php echo esc_attr( get_option('wsm_status_pending_payment') ); ?></textarea>
    	<?php 
    		$enable_disable_pending_payment=get_option('enable_disable_pending_payment');  
    	?>
        <label>Enable/Disable</label> <input type="checkbox" name="enable_disable_pending_payment[wsm_status_pp]" value="1"<?php checked( 1 == $enable_disable_pending_payment['wsm_status_pp'] ); ?> />
        </td>


        <th scope="row">Api Key</th>
        <td><input type="text" name="wsm_api_key" value="<?php echo esc_attr( get_option('wsm_api_key') ); ?>" /></td>
        </tr>
         
        <tr valign="top">
        <th scope="row">Processing</th>
        <td><textarea name="wsm_status_processing" style="width: 100%;" rows="5"><?php echo esc_attr( get_option('wsm_status_processing') ); ?></textarea>
        <?php 
    		$enable_disable_processing=get_option('enable_disable_processing');  
    	?>
        <label>Enable/Disable</label> <input type="checkbox" name="enable_disable_processing[wsm_processing]" value="1"<?php checked( 1 == $enable_disable_processing['wsm_processing'] ); ?> />
        
        </td>

        <th scope="row">Slot Sim</th>
        <td><input type="text" name="wsm_slot_sim" value="<?php echo esc_attr( get_option('wsm_slot_sim') ); ?>" /></td>
        </tr>
        
        <tr valign="top">
        <th scope="row">On Hold</th>
        <td><textarea name="wsm_status_on_hold" style="width: 100%;" rows="5"><?php echo esc_attr( get_option('wsm_status_on_hold') ); ?></textarea>
		<?php 
    		$enable_disable_on_hold=get_option('enable_disable_on_hold');  
    	?>
        <label>Enable/Disable</label> <input type="checkbox" name="enable_disable_on_hold[wsm_on_hold]" value="1"<?php checked(1 == $enable_disable_on_hold['wsm_on_hold'] ); ?> />

        </td>

        <th scope="row">Device ID </th>
        <td><input type="text" name="wsm_device_id" value="<?php echo esc_attr( get_option('wsm_device_id') ); ?>" /></td>
        </tr>

        <tr valign="top">
        <th scope="row">Completed</th>
        <td><textarea name="wsm_status_completed" style="width: 100%;" rows="5"><?php echo esc_attr( get_option('wsm_status_completed') ); ?></textarea>
        <?php 
    		$enable_disable_completed=get_option('enable_disable_completed');  
    	?>
        <label>Enable/Disable</label> <input type="checkbox" name="enable_disable_completed[wsm_completed]" value="1"<?php checked( 1 == $enable_disable_completed['wsm_completed'] ); ?> />

        </td>
        </tr>

        <tr valign="top">
        <th scope="row">Cancel</th>
        <td><textarea name="wsm_status_cancel" style="width: 100%;" rows="5"><?php echo esc_attr( get_option('wsm_status_cancel') ); ?></textarea>
        <?php 
    		$enable_disable_cancel=get_option('enable_disable_cancel');  
    	?>
        <label>Enable/Disable</label> <input type="checkbox" name="enable_disable_cancel[wsm_cancel]" value="1"<?php checked( 1 == $enable_disable_cancel['wsm_cancel'] ); ?> />

        </td>
        </tr>

        <tr valign="top">
        <th scope="row">Refunded</th>
        <td><textarea name="wsm_status_refunded" style="width: 100%;" rows="5"><?php echo esc_attr( get_option('wsm_status_refunded') ); ?></textarea>
        <?php 
    		$enable_disable_refunded=get_option('enable_disable_refunded');  
    	?>
        <label>Enable/Disable</label> <input type="checkbox" name="enable_disable_refunded[wsm_refunded]" value="1"<?php checked( 1 == $enable_disable_refunded['wsm_refunded'] ); ?> />
        
        </td>
        </tr>

        <tr valign="top">
        <th scope="row">Failed</th>
        <td><textarea name="wsm_status_failed" style="width: 100%;" rows="5"><?php echo esc_attr( get_option('wsm_status_failed') ); ?></textarea>
        <?php 
    		$enable_disable_failed=get_option('enable_disable_failed');  
    	?>
        <label>Enable/Disable</label> <input type="checkbox" name="enable_disable_failed[wsm_failed]" value="1"<?php checked( 1 == $enable_disable_failed['wsm_failed'] ); ?> />
        
        </td>
        </tr>

    </table>
    
    <?php submit_button(); ?>

</form>
</div>
<?php } ?>