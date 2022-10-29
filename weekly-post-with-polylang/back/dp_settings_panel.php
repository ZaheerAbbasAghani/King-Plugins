<?php
// create custom plugin settings menu
add_action('admin_menu', 'dp_plugin_create_menu');

function dp_plugin_create_menu() {

	//create new top-level menu
	add_menu_page('Weekly Post Settings', 'Weekly Post', 'manage_options', 'weekly_post_settings', 'dp_plugin_settings_page','dashicons-plus-alt',25);

	//call register settings function
	add_action( 'admin_init', 'register_dp_plugin_settings' );
}


function register_dp_plugin_settings() {
	//register our settings
	register_setting( 'dp-plugin-settings-group', 'dp_select_category' );
	register_setting( 'dp-plugin-settings-group', 'dp_select_tag' );
	//register_setting( 'dp-plugin-settings-group', 'option_etc' );
}

function dp_plugin_settings_page() {
?>
<div class="wrap" style="background: #fff; padding: 10px 20px;border-radius: 5px;">
<h1>Weekly Post Settings</h1><hr>
<?php settings_errors(); ?>
<form method="post" action="options.php">
    <?php settings_fields( 'dp-plugin-settings-group' ); ?>
    <?php do_settings_sections( 'dp-plugin-settings-group' ); ?>
    <table class="form-table">
        <tr valign="top">
        <th scope="row"> Categories </th>
	        <td> 
	        	<select name="dp_select_category">
	        		<option value="">No Default Category</option>
	        		<?php 
	        			$categories = get_categories(array('hide_empty' => false));
						foreach($categories as $category) {
							if($category->term_id != 1){ ?>

			          			<option value="<?php echo $category->term_id; ?>" <?php selected(get_option('dp_select_category'), $category->term_id); ?>>
			          			<?php echo $category->name; ?>
			          			</option>

			          		<?php } ?>
	          		<?php } ?>
	          	</select>
	    	</td>
        </tr>
         
       <tr valign="top">
        <th scope="row"> Tags </th>
	        <td> 
	        	<select name="dp_select_tag">
	        		<option value="">No Default Tag</option>
	        		<?php 
	        			$tags = get_tags(array('hide_empty' => false));

						foreach($tags as $tag) {
							if($tag->term_id != 1){ ?>

			          			<option value="<?php echo $tag->name; ?>" <?php selected(get_option('dp_select_tag'), $tag->name); ?>>
			          			<?php echo $tag->name; ?>
			          			</option>

			          		<?php } ?>
	          		<?php } ?>
	          	</select>
	    	</td>
        </tr>
    </table>
    
    <?php submit_button(); ?>

</form>
</div>
<?php } ?>
