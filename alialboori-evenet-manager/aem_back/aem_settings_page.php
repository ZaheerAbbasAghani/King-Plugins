<?php

// create custom plugin settings menu

add_action('admin_menu', 'aem_plugin_create_menu');

function aem_plugin_create_menu() {

	//create submenu page in cpt

	add_submenu_page('edit.php?post_type=events', 'Settings', 'Email Settings', 'manage_options', 'aem_settings', 'aem_plugin_settings_page');

	//call register settings function

	add_action('admin_init', 'aem_register_plugin_settings_page');

}

function aem_register_plugin_settings_page() {

	//register our settings

 	register_setting( 'aem-plugin-group', 'aem_subject' );
  	register_setting( 'aem-plugin-group', 'aem_message' );

}

function aem_plugin_settings_page() {

echo date("Y-m-d");

	?>

<div class="aem_wrap wrap" style="background: #fff;padding: 13px 20px;">

<h1>Message Settings</h1><hr>

<form method="post" action="options.php">
    <?php settings_fields( 'aem-plugin-group' ); ?>
    <?php do_settings_sections( 'aem-plugin-group' ); ?>
    <table class="form-table">
        <tr valign="top">
        <th scope="row">Email Subject</th>
        <td><input type="text" name="aem_subject" value="<?php echo esc_attr( get_option('aem_subject') ); ?>" placeholder="Enter Email Subject text here" style="width:45%;"/></td>
        </tr>
         
        <tr valign="top">
        <th scope="row">Email Message</th>
        <td><textarea name="aem_message" placeholder="Enter Email Message text here" rows="5" cols="55"><?php echo esc_attr( get_option('aem_message') ); ?></textarea></td>
        </tr>

    </table>
    
    <?php submit_button(); ?>

</form>


</div>


<?php 

}

?>
