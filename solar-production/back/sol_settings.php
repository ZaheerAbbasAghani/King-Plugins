<?php

// create custom plugin settings menu

add_action('admin_menu', 'sol_cool_plugin_create_menu');



function sol_cool_plugin_create_menu() {



	//create new top-level menu

	add_menu_page('Solar Production', 'Solar Production ', 'manage_options','sol_production', 'sol_plugin_settings_page' , 'dashicons-location-alt', 30 );



	//call register settings function

	add_action( 'admin_init', 'register_sol_plugin_settings' );

}





function register_sol_plugin_settings() {
	register_setting('sol-plugin-settings-group', 'api_url' );
	register_setting('sol-plugin-settings-group', 'sol_api_url' );
    register_setting('sol-plugin-settings-group', 'pricing' );
    register_setting('sol-plugin-settings-group', 'graphColor' );
    register_setting('sol-plugin-settings-group','outputBoxColors');
    register_setting('sol-plugin-settings-group','outputBoxTextColors');

    register_setting('sol-plugin-settings-group','graphMonthColorFont');
    register_setting('sol-plugin-settings-group','graphMonthFontSize');
    register_setting('sol-plugin-settings-group','BoxFontSize');
    register_setting('sol-plugin-settings-group','enable_disable_export');
    
    register_setting('sol-plugin-settings-group','sol_license_key');
    

}



function sol_plugin_settings_page() {

?>

<div class="wrap" style="background: #fff; padding: 10px 20px; box-shadow: 2px 2px 2px #ddd;font-size: 15px;">

<h1>Solar Production Settings </h1><hr>

<?php 
 $license_key = get_option('sol_license_key');
   // API query parameters
        $api_params = array(
            'slm_action' => 'slm_activate',
            'secret_key' => SOL_SECRET_KEY,
            'license_key' => $license_key,
            'registered_domain' => $_SERVER['SERVER_NAME'],
            'item_reference' => urlencode(SOL_ITEM_REFERENCE),
        );

        // Send query to the license manager server
        $query = esc_url_raw(add_query_arg($api_params, SOL_SERVER_URL));
        $response = wp_remote_get($query, array('timeout' => 20, 'sslverify' => false));

        // Check for error in the response
        if (is_wp_error($response)){
            echo "Unexpected Error! The query returned with an error.";
        }

        //var_dump($response);//uncomment it if you want to look at the full response
        
        // License data.
        $license_data = json_decode(wp_remote_retrieve_body($response));
        
        //print_r($license_data);

        // TODO - Do something with it.
        //var_dump($license_data);//uncomment it to look at the data
        
        if($license_data->result == 'success'){

?>

<div class="sol_settings_area"> 
    <span class="notactive"  style="font-size: 17px;background: green;padding: 6px 20px;border-radius: 4px;color: #fff;position: absolute;right: 40px;">License Active</span>
<form method="post" action="options.php" class="solorapi">

    <?php settings_fields( 'sol-plugin-settings-group' ); ?>

    <?php do_settings_sections( 'sol-plugin-settings-group' ); ?>

    <table class="form-table">

        <tr valign="top" style="display: none;">

        <th scope="row">Google API URL </th>

        <td><input type="text" name="api_url" value="<?php echo esc_attr( get_option('api_url') ); ?>" style="width: 60%;border:1px solid #ddd;"/></td>

        </tr>

        <tr valign="top" style="display: none;">
        <th scope="row">Solar Production API </th>
        <td><input type="text" name="sol_api_url" value="<?php echo esc_attr( get_option('sol_api_url') ); ?>" style="width: 60%;border:1px solid #ddd;"/></td>
        </tr>

        <tr valign="top">
        <th scope="row"> Graph Color </th>
        <td> <input type="hidden" name="graphColor" value="<?php echo esc_attr( get_option('graphColor') ); ?>" id="graphColor">
        	<?php if(get_option('graphColor')  == ""){ ?>
        		<input type="text" value="#81bef7" data-default-color="#effeff" class="my-color-field" />
        	<?php } else { ?>
        	<input type="text" value="<?php echo get_option('graphColor'); ?>" data-default-color="<?php echo get_option('graphColor'); ?>" class="my-color-field" />
        	<?php } ?>
        	</td>
        </tr>


     <tr valign="top">
    <th scope="row"> Graph Month Color/Font </th>
    <td> <input type="hidden" name="graphMonthColorFont" value="<?php echo esc_attr( get_option('graphMonthColorFont') ); ?>" id="graphMonthColorFont">
        <?php if(get_option('graphMonthColorFont')  == ""){ ?>
            <input type="text" value="#81bef7" data-default-color="#effeff" class="my-color-field4" />
        <?php } else { ?>
        <input type="text" value="<?php echo get_option('graphMonthColorFont'); ?>" data-default-color="<?php echo get_option('graphMonthColorFont'); ?>" class="my-color-field4" />
        <?php } ?>

         <input type="text" name="graphMonthFontSize" value="<?php echo esc_attr( get_option('graphMonthFontSize') ); ?>" placeholder="20" />

        </td>
    </tr>


<?php //delete_option( "sol_license_key" ); ?>

        <tr valign="top">
        <th scope="row"> Output Box Colors </th>
        <td> <input type="hidden" name="outputBoxColors" value="<?php echo esc_attr( get_option('outputBoxColors') ); ?>" id="outputBoxColors">
        	<?php if(get_option('outputBoxColors')  == ""){ ?>
        		<input type="text" value="#81bef7" data-default-color="#effeff" class="my-color-field2" />
        	<?php } else { ?>
        	<input type="text" value="<?php echo get_option('outputBoxColors'); ?>" data-default-color="<?php echo get_option('outputBoxColors'); ?>" class="my-color-field2" />
        	<?php } ?>
        	</td>
        </tr>


<tr valign="top">
<th scope="row"> Output Box Text Colors </th>
<td> <input type="hidden" name="outputBoxTextColors" value="<?php echo esc_attr( get_option('outputBoxTextColors') ); ?>" id="outputBoxTextColors">
	<?php if(get_option('outputBoxTextColors')  == ""){ ?>
		<input type="text" value="#81bef7" data-default-color="#effeff" class="my-color-field3" />
	<?php } else { ?>
	<input type="text" value="<?php echo get_option('outputBoxTextColors'); ?>" data-default-color="<?php echo get_option('outputBoxTextColors'); ?>" class="my-color-field3" />
	<?php } ?>

  <input type="text" name="BoxFontSize" value="<?php echo esc_attr( get_option('BoxFontSize') ); ?>" placeholder="20" />
	</td>
</tr>

<tr valign="top" >

    <th scope="row">Hide Export Result Button</th>

    <td><input name="enable_disable_export" type="checkbox" value="1" <?php checked( '1', get_option( 'enable_disable_export' ) ); ?> />
    </td>

</tr>



 <tr valign="top" class="pricingtr"><th scope="row">Pricing </th><td>
    <div class="field-wrap" id="dynamic_field">         
        <input type="text" class="kwp_from" required placeholder="KWP" style="width: 27%;border:1px solid #ddd;float: left;" />
        <input type="text" class="kwp_m2" required placeholder="m2" style="width: 27%;border:1px solid #ddd;float: left;" />

        <input type="text" class="pricing" required placeholder="Price" style="width: 27%;border:1px solid #ddd;float: left;" />
        <button type="button" name="add" id="add" class="button button-default">Add More</button>
    </div></td>
</tr>
    </table>
    <?php $other_attributes = array( 'class' => 'sol_submit' );
submit_button( __( 'Save Settings', 'textdomain' ), 'primary', 'sol_submit', true, $other_attributes );
  ?>
</form>
</div>

<!-- Showing All -->


<div class="zciRight">
<h3>Solar Production Entries</h3><hr>
<table id="sol_code_table" class="table table-striped table-bordered" style="width:100%; text-align: center;">
        <thead>
            <tr>
                <th>ID</th>
                <th>KWP </th>
                <th>m2 </th>
                <th>Pricing</th>
                <th>Action</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php 

global $wpdb;
$table_name = $wpdb->base_prefix.'sol_pricing';
$pricing = $wpdb->get_results( "SELECT * FROM $table_name", OBJECT );
$i = 1;
 ?>
         <?php foreach ($pricing as $row) { ?>
            <tr>
                <td><?php echo $i; ?></td>
                <td><?php echo $row->kwp_from; ?></td>
                <td><?php echo $row->kwp_m2; ?></td>
                <td><?php echo $row->pricing; ?></td>
                <td><a href="#" class="sol_edit" id="<?php echo $row->id; ?>" kwp_m2="<?php echo $row->kwp_m2; ?>" kwp_from="<?php echo $row->kwp_from; ?>" pricing="<?php echo $row->pricing; ?>"> Edit </a></td>
                <td><a href="#" class="sol_remove" id="<?php echo $row->id; ?>"> Remove </a></td>
            </tr>
        <?php $i++; } ?>
        </tbody>
    </table>

</div>




<div id="edit_box" style="display: none;">
    <form method="post" action=""><span id="cross">x</span>
            <label>Edit Pricing </label>
            <input type="text" name="kwp_from" value="" id="kwp_from">
            <input type="text" name="kwp_m2" value="" id="kwp_m2">
            <input type="text" name="pricing" value="" id="pricing">
            <input type="hidden" name="rowid" value="" id="rowid">
            <input type="button" name="" class="button button-primary edit_now" value="Submit">
    </form>
</div>

<?php //delete_option('sol_license_key');
 } 
    else{ ?>


 <p>Please enter the license key for this product to activate it. You were given a license key when you purchased this item. <span class="notactive"  style="float: right;font-size: 17px;background: red;padding: 6px 20px;border-radius: 4px;color: #fff;">License Not Active</span></p>
    <form method="post" action="options.php" class="solorapi">
    <?php settings_fields( 'sol-plugin-settings-group' ); ?>
    <?php do_settings_sections( 'sol-plugin-settings-group' ); ?>
        <table class="form-table">
            <tr>
                <th style="width:100px;"><label for="sol_license_key">License Key</label></th>
                <td ><input class="regular-text" type="text" id="sol_license_key" name="sol_license_key"  value="<?php echo get_option('sol_license_key'); ?>" ></td>
            </tr>
        </table>
        <p class="submit">
            <input type="submit" name="activate_license" value="Activate" class="button-primary" />
          <!--   <input type="submit" name="deactivate_license" value="Deactivate" class="button" /> -->
        </p>
    </form>


<?php 

            //Show error to the user. Probably entered incorrect license key.
            
            //Uncomment the followng line to see the message that returned from the license server
          //  echo '<br />The following message was returned from the server: '.$license_data->message;

} ?>

</div>


<style type="text/css">

#edit_box {
    background: #fff;
    position: fixed;
    top: 0px;
    bottom: 0px;
    left: 0px;
    right: 0px;
    z-index: 9999;
    text-align: center;
}

#edit_box  form{ margin: 24% 34%; position: relative;}
#edit_box #cross {
    position: absolute;
    right: 10px;
    cursor: pointer;
    font-size: 24px;
    top: 0px;
}
#edit_zipcode{
        width: 100%;
}
#edit_box label{
        font-size: 15px;
    margin-bottom: 10px;
    display: block;
}
#edit_box input[type="text"] {
    width: 80%;
    display: block;
    margin: 10px auto;
    border: 1px solid #ddd;
    padding: 4px 12px;
    font-size: 19px;
}
</style>

<?php  } ?>