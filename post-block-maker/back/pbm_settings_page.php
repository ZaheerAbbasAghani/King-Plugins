<?php
// create custom plugin settings menu
add_action('admin_menu', 'pbm_plugin_create_menu');

function pbm_plugin_create_menu() {

	//create new top-level menu
	add_menu_page('Block Maker', 'Block Maker Settings', 'manage_options', 'pbm_block_maker_settings', 'pbm_plugin_settings_page', 'dashicons-networking', 25);

	add_action( 'admin_init', 'register_pbm_plugin_settings' );
}


function register_pbm_plugin_settings() {
	//register our settings
	/*register_setting( 'my-cool-plugin-settings-group', 'new_option_name' );
	register_setting( 'my-cool-plugin-settings-group', 'some_other_option' );*/
	register_setting( 'pbm-plugin-settings-group', 'pbm_listPages' );
    register_setting( 'pbm-plugin-settings-group', 'pbm_listCategory' );
}

function pbm_plugin_settings_page() {
    settings_errors();
?>
<div class="wrap" style="background: #fff; padding: 10px 20px;">
<h1>Post Block Maker</h1> <hr>

<form method="post" action="options.php" name="blockGenerator" id="blockGeneratorForm">
    <?php settings_fields( 'pbm-plugin-settings-group' ); ?>
    <?php do_settings_sections( 'pbm-plugin-settings-group' ); ?>

    <label> Select a page  </label>
    <select name="pbm_listPages" id="pbm_listPages">
        <option value="" selected disabled=""> Select a page </option>
        <?php foreach (get_pages()  as $page) { ?>
                <option value="<?php echo $page->ID; ?>" <?php selected(get_option('pbm_listPages'), $page->ID); ?>><?php echo $page->post_title; ?></option>
        <?php } ?>
    </select>

    <label> Select a category </label>
    <select name="pbm_listCategory" id="pbm_listCategory">
        <option value="" selected disabled=""> Select a category </option>
        <?php foreach (get_categories()  as $category) { ?>
                <option value="<?php echo $category->term_id; ?>" <?php selected(get_option('pbm_listCategory'), $category->term_id); ?>><?php echo $category->cat_name; ?></option>
        <?php } ?>
    </select>


    <?php //submit_button(); ?>
    <input type="button" name="" id="blockGenerator" value="Create Post Blocks" class="button button-primary">
</form>

</div>
<?php } ?>