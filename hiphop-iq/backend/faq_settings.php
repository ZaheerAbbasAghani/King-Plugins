<?php
// create custom plugin settings menu
add_action('admin_menu', 'my_cool_plugin_create_menu');

function my_cool_plugin_create_menu() {

	//create new top-level menu
	add_submenu_page('edit.php?post_type=faq','FAQ - Settings', 'Settings', 'manage_options', "faq_settings", 'faq_plugin_settings_page' );

	//call register settings function
	add_action( 'admin_init', 'register_my_cool_plugin_settings' );
}


function register_my_cool_plugin_settings() {
	//register our settings
	register_setting( 'faq-plugin-settings-group', 'faq_banner_title' );
	register_setting( 'faq-plugin-settings-group', 'faq_banner_sub_title' );
	register_setting( 'faq-plugin-settings-group', 'faq_button_text' );
    register_setting( 'faq-plugin-settings-group', 'faq_banner_image' );
    
}

function faq_plugin_settings_page() {
?>
<div class="wrap" style="background: #fff; box-shadow: 2px 2px 2px #ddd; padding: 10px 20px;clear: both;height: auto;overflow: hidden;">
<h1>FAQ Settings</h1><hr>
<div class="leftSide" style="float: left;background: #eee;margin-right: 10px;padding: 4px 15px 4px 15px;width: 97%;">
<form method="post" action="options.php">
    <?php settings_fields( 'faq-plugin-settings-group' ); ?>
    <?php do_settings_sections( 'faq-plugin-settings-group' ); ?>
    <table class="form-table">
        <tr valign="top">
        <th scope="row">Banner Headings</th>
        <td><input type="text" name="faq_banner_title" value="<?php echo esc_attr( get_option('faq_banner_title') ); ?>" style="width:100%;"/></td>
        </tr>
         
        <tr valign="top">
        <th scope="row">Banner Sub-Headings</th>
        <td><input type="text" name="faq_banner_sub_title" value="<?php echo esc_attr( get_option('faq_banner_sub_title') ); ?>" style="width:100%;"/></td>
        </tr>
        
        <tr valign="top">
        <th scope="row">Button Text</th>
        <td><input type="text" name="faq_button_text" value="<?php echo esc_attr( get_option('faq_button_text') ); ?>" style="width:100%;"/></td>
        </tr>

        <tr valign="top">
        <th scope="row">Upload Banner</th>
        <td><input type="text" name="faq_banner_image" value="<?php echo esc_attr( get_option('faq_banner_image') ); ?>" id="faqBanner" style="width:100%;"/>
            <button class="button wpse-228085-upload">Choose Image</button></td>
        </tr>

        


    </table>
    
    <?php submit_button(); ?>

</form>
</div><!--LeftSide-->
<?php } ?>