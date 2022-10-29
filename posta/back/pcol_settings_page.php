<?php
// create custom plugin settings menu
add_action('admin_menu', 'pcol_plugin_create_menu');

function pcol_plugin_create_menu() {

	//create new top-level menu
	add_menu_page('Posta Settings', 'Posta Settings', 'manage_options', 'pcol_posta_settings', 'pcol_posta_settings_process', 'dashicons-cart', 25 );

	//call register settings function
	add_action( 'admin_init', 'register_pcol_plugin_settings' );
}


function register_pcol_plugin_settings() {
	//register our settings
	//register_setting( 'pcol-plugin-settings-group', 'delivery_url' );
	register_setting( 'pcol-plugin-settings-group', 'pcol_packages' );
	/*register_setting( 'pcol-plugin-settings-group', 'option_etc' );*/
}

function pcol_posta_settings_process() {
?>
<div class="wrap" style="background: #fff; padding: 10px 20px;"> 
<h1>Posta Settings <span style="float: right;color:#666;font-size: 18px;"> Version 1.1</span></h1><hr>

<?php 

settings_errors();


?>

<form method="post" action="options.php">
    <?php settings_fields( 'pcol-plugin-settings-group' ); ?>
    <?php do_settings_sections( 'pcol-plugin-settings-group' ); ?>
    <table class="form-table">
      
        <tr valign="top">
        <th scope="row"> Pakkar </th>
        <td><input type="text" name="pcol_packages" value="<?php echo esc_attr( get_option('pcol_packages') ); ?>" id="tags" /></td>


        </tr>
   
    </table>
    
    <?php submit_button(); ?>

</form>


<img src="<?php echo plugin_dir_url( __FILE__ ) . '/Attachment_1646158456.png'; ?>" style="width: 200px;float: right;margin-top: -70px;">

</div>
<?php } ?>