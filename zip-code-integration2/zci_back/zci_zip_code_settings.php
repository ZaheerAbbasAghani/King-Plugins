<?php
// create custom plugin settings menu
add_action('admin_menu', 'my_cool_plugin_create_menu');

function my_cool_plugin_create_menu() {

	//create new top-level menu
	add_menu_page('Zip Codes Integration', 'Zip Codes', 'manage_options', 'zip_codes', 'zci_zip_codes' , "dashicons-search",60);

	//call register settings function
	add_action( 'admin_init', 'register_my_cool_plugin_settings' );
}


function register_my_cool_plugin_settings() {
	//register our settings
	register_setting( 'my-cool-plugin-settings-group', 'success_message' );
	register_setting( 'my-cool-plugin-settings-group', 'fail_message' );
	
}

function zci_zip_codes() {
?>
<div class="wrap" style="background: #fff; padding: 10px 20px; box-shadow: 2px 2px 2px #ddd;">
<h1>Zip Code Integration </h1><hr>

<div class="zciWrapper">
<div class="zciLeft">
        <form method="post" action="" id="zci_zip_codes_form">
            <table class="table table-bordered" id="dynamic_field" style="width: 100%;">
                <tr>
                    <td>
                        <!--div class="top-row"-->
                        
                        <div class="field-wrap">
                            
                            <input type="text" name="zci_zip_codes[]" required placeholder="Enter Zip Code" style="width: 100%;" maxlength='5' />
                        </div>
                        
                    </td>
                    <td><button type="button" name="add" id="add" class="button button-default">Add More</button></td>
                </tr>
            </table>
            <input type="button" name="submit" id="zci_submit"  class="button button-primary" value="Submit" />
        </form>


        <p id="zip_code_message">Insert zip code in above box.</p>

<br><br>
<form method="post" action="options.php">
    <?php settings_fields( 'my-cool-plugin-settings-group' ); ?>
    <?php do_settings_sections( 'my-cool-plugin-settings-group' ); ?>
    <table class="form-table">
        <tr valign="top">
        <th scope="row">Success Message</th>
        <td><input type="text" name="success_message" value="<?php echo esc_attr( get_option('success_message') ); ?>" /></td>
        </tr>
         
        <tr valign="top">
        <th scope="row">Fail Message</th>
        <td><input type="text" name="fail_message" value="<?php echo esc_attr( get_option('fail_message') ); ?>" /></td>
        </tr>
    </table>
    
    <?php submit_button(); ?>

</form>


</div>


<div class="zciRight">
    
<table id="zci_zip_code_table" class="display" style="width:100%">
        <thead>
            <tr>
                <th>ID</th>
                <th>Zip Code</th>
                <th>Action</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php 

global $wpdb;
$table_name = $wpdb->base_prefix.'zci_zip_codes';
$zipcodes = $wpdb->get_results( "SELECT * FROM $table_name", OBJECT ); ?>
         <?php foreach ($zipcodes as $zipcode) { ?>
            <tr>
                <td><?php echo $zipcode->id; ?></td>
                <td><?php echo $zipcode->zip_code; ?></td>
                <td><a href="#" class="zci_edit" id="<?php echo $zipcode->id; ?>" zip_code="<?php echo $zipcode->zip_code; ?>"> Edit </a></td>
                <td><a href="#" class="zci_remove" id="<?php echo $zipcode->id; ?>"> Remove </a></td>
            </tr>
        <?php } ?>
        </tbody>
        <tfoot>
           <tr>
                <th>ID</th>
                <th>Zip Code</th>
                <th>Action</th>
                <th>Action</th>
            </tr>
        </tfoot>
    </table>

</div>


<div id="edit_box" style="display: none;">
    <form method="post" action=""><span id="cross">x</span>
            <label>Edit Zip Code</label>
            <input type="text" name="edit_zipcode" value="" id="edit_zipcode" zip_code_id="">
            <input type="button" name="" class="button button-primary edit_now" value="Submit">
    </form>
</div>

</div><!-- wrapper -->


<?php } ?>