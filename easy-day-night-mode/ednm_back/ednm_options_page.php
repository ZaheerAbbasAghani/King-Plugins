<?php
// create custom plugin settings menu
add_action('admin_menu', 'ednm_settings_create_menu');

function ednm_settings_create_menu() {

	//create new top-level menu
	add_menu_page('Mode Settings', 'Mode Settings', 'manage_options', "ednm_settings", 'ednm_settings_settings_page' ,  "dashicons-flag", 55);

	//call register settings function
	add_action( 'admin_init', 'register_my_cool_plugin_settings' );
}


function register_my_cool_plugin_settings() {
	//register our settings
	register_setting( 'my-cool-plugin-settings-group', 'ednm_all_classes' );
    register_setting( 'my-cool-plugin-settings-group', 'ednm_color_picker_val' );

}

function ednm_settings_settings_page() {
?>
<style type="text/css">
    .CodeMirror {width: 100%; float:right;}
</style>
<div class="wrap_easy_day_night" style="background: #fff; padding:5px 15px 10px 15px; margin-top: 10px;">
<h1>Easy Day Night Mode</h1> <hr>

<form method="post" action="options.php">
    <?php settings_fields( 'my-cool-plugin-settings-group' ); ?>
    <?php do_settings_sections( 'my-cool-plugin-settings-group' ); ?>
    <table class="form-table">
        <tr valign="top">
            <td style="width: 20%;vertical-align: baseline;"><h4> All Website Classes </h4><span>You can insert Classes IDs manually from here.</span></td>
            <td>
                <textarea name="ednm_all_classes" id="all_classes"  style="width: 100%;padding:10px; border: 1px solid #ddd;" rows="10" cols="10"> <?php echo esc_attr(get_option('ednm_all_classes')); ?></textarea>
                <div class="btnGenerate" style="float: left;margin-top: 10px;">
                    <button class="generate_classes button button-secondary"> Generate Classes</button>
                </div>
            </td>
        </tr>


        <tr valign="top">
            <td style="vertical-align: baseline;"><h4> Text Color </h4> <span> You can set text color manually from here.</span></td>
            <td>
               <input type="hidden" id="ednm_color_picker_val" name="ednm_color_picker_val" value="<?php echo esc_attr(get_option('ednm_color_picker_val')); ?>">
            </td>
        </tr>

    </table>

<div class="wrap_actions" style="clear: both; height: auto; overflow: hidden;">
    <div class="btnSubmit" style="float: left;">
        <?php submit_button(); ?>
    </div>

</div>

</form>
</div>
<?php } ?>
