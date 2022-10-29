<?php

// create custom plugin settings menu

add_action('admin_menu', 'adm_plugin_create_menu');



function adm_plugin_create_menu() {

	//create new top-level menu
	add_menu_page('Ad Maker', 'Ad Maker', 'manage_options', 'adm_ad_maker', 'adm_plugin_settings_page', 'dashicons-bell', 20 );
	//call register settings function
	add_action( 'admin_init', 'register_adm_plugin_settings' );

}

function register_adm_plugin_settings() {

	//register our settings
	register_setting( 'ad-plugin-settings-group', 'form_headings' );
	register_setting( 'ad-plugin-settings-group', 'form_description' );
	register_setting( 'ad-plugin-settings-group', 'ad_listing_headings' );
	register_setting( 'ad-plugin-settings-group', 'ad_listing_description' );

	register_setting( 'ad-plugin-settings-group', 'email_subject' );
	register_setting( 'ad-plugin-settings-group', 'email_body' );
	register_setting( 'ad-plugin-settings-group', 'adm_site_key' );
	register_setting( 'ad-plugin-settings-group', 'adm_secret_key' );

}



function adm_plugin_settings_page() {

?>

<style type="text/css">

    

    #build-wrap ul.stage-wrap li{

        border: 1px solid #ddd;

        padding: 14px;

        box-shadow: 1px 1px 4px #ddd;

    }





</style>

<div class="wrap" style="background: #fff; padding: 10px 20px;height: auto;overflow: hidden;">

<h1> Ad Maker Form -  Drag Drop Features</h1> <hr>


<div id="tabs">
  <ul>
    <li><a href="#ad-form">AD Form</a></li>
    <li><a href="#ad-listing">AD Listing</a></li>
    <li><a href="#email-template">Email Template / Google reCAPTCHA </a></li>
    
  </ul>
  <div id="ad-form">
	    <div id="build-wrap">
	    	<img src="https://www.geekpassionsgifts.com/wp-content/uploads/2022/05/Ajux_loader.gif" class="loadingImg" style="width:300px;margin:auto;">
	    </div>
	    <h5>Ad Form Shortcode: [ad_maker]</h5>
	    <h5>Ad View Shortcode: [ad_display]</h5>
  </div><!-- ad-form -->
  <div id="email-template">
    	<?php settings_errors(); ?>


	<form method="post" action="options.php">
	    <?php settings_fields( 'ad-plugin-settings-group' ); ?>
	    <?php do_settings_sections( 'ad-plugin-settings-group' ); ?>
	    <?php 
			$email_body = get_option( 'email_body' );
			$settings = array( 
			    'editor_height' => 300,
			    'textarea_rows' => 20,
			    'wpautop' => false,
			 );
	    ?>

	    <table class="form-table">

	    		<tr valign="top">
	        	<th scope="row">Form Headings</th>
	        	<td><input type="text" name="form_headings" value="<?php echo esc_attr( get_option('form_headings') ); ?>" /></td>
	        </tr>

	        <tr valign="top">
	        	<th scope="row">Form Description</th>
	        	<td><input type="text" name="form_description" value="<?php echo esc_attr( get_option('form_description') ); ?>" /></td>
	        </tr>

	        <tr valign="top">
	        	<th scope="row">Listing Headings</th>
	        	<td><input type="text" name="ad_listing_headings" value="<?php echo esc_attr( get_option('ad_listing_headings') ); ?>" /></td>
	        </tr>

	        <tr valign="top">
	        	<th scope="row">Listing Description</th>
	        	<td><input type="text" name="ad_listing_description" value="<?php echo esc_attr( get_option('ad_listing_description') ); ?>" /><hr style="margin-top: 20px;"></td>
			   </tr>
	       
	        <tr valign="top">
	        <th scope="row">Email Subject</th>
	        <td><input type="text" name="email_subject" value="<?php echo esc_attr( get_option('email_subject') ); ?>" /></td>
	        </tr>
	         
	        <tr valign="top">
	        <th scope="row">Email Message</th>
		        <td><?php  wp_editor( wpautop( $email_body ) , 'email_body',  $settings);  ?>
		        <br>
		        <?php 
								global $wpdb; // this is how you get access to the database
								$table_name = $wpdb->base_prefix.'adm_fields_table';
								$query = "SELECT label FROM  $table_name ORDER BY position";
								$query_results = $wpdb->get_results($query);

								//print_r($query_results);


								$count = count($query_results);
								$i = 0;
								foreach ($query_results as $results) {

									//print_r($results->label);

									if(!empty($results->label)){

										echo "{".str_replace(' ', '_',  strtolower($results->label))."}";
										if($count != $i){
											echo ", ";
											$i++;
										}
										
									}
								}
						?>
						{link}
		        </td>
	        </tr>

	        <tr valign="top">
	        <th scope="row"> Site Key </th>
	        <td><input type="text" name="adm_site_key" value="<?php echo esc_attr( get_option('adm_site_key') ); ?>" /></td>
	        </tr>

	        <tr valign="top">
	        <th scope="row"> Secreat Key </th>
	        <td><input type="text" name="adm_secret_key" value="<?php echo esc_attr( get_option('adm_secret_key') ); ?>" /></td>
	        </tr>
	        
	    </table>
	    
	    <?php submit_button(); ?>

	</form>
  </div><!-- email-template -->


 <div id="ad-listing">

<?php

$table = "";

$Columntable = $wpdb->base_prefix.'adm_fields_table';
$dataTable = $wpdb->base_prefix.'adm_submitted_data_table';

$cquery = "SELECT * FROM $Columntable ORDER BY position ASC";
$column_results = $wpdb->get_results($cquery);



$allColumns = array();
foreach ($column_results as $columns) {
	if(!empty($columns->label)){
		array_push($allColumns,str_replace(' ', '_',  strtolower($columns->label)));
	}
}


$query = "SELECT * FROM $dataTable ORDER BY id DESC";
$data_results = $wpdb->get_results($query);

$table .= '<div style="text-align:center;"><h2> '.get_option('form_headings').' </h2>';
$table .= '<p style="font-size:17px;"> '.get_option('form_description').' </p></div>';

$table .= '<table id="example" class="display" style="width:100%">
	<thead>
	<tr>';
	foreach ($column_results as $column) {
			if(!empty($column->label)){
				$table .=  "<th>".$column->label."</th>";
			}
	}
	$table .=  "<th>Action</th>";
$table .='</tr>
	</thead>
	<tbody>';
		$i=0;
		foreach ($data_results as $results) {

			$table .=  "<tr>";

			foreach (unserialize($results->formData) as $key => $value) {

					if(in_array($key, $allColumns)){

							if(is_array(maybe_unserialize($value))){

								$table .=  "<td>";
								$j=0;
								foreach (maybe_unserialize($value) as $v) {
									if($j == 0){
										$table .= $v;
									}else{
										$table .= ", ".$v;
									}
									$j++;
								}
								$table .=  "</td>";
								
							}else{

								if(filter_var($value, FILTER_VALIDATE_EMAIL)) {
	    							$table .=  "<td><a href='mailto:".$value."' > Click to email </a></td>";
	    						}else{


	    							$justNums = preg_replace("/[^0-9]/", '', $value);
	    							if(strlen($justNums) >= 11 && !empty($value)) {
									   $table .=  "<td><a href='tel:".$value."' > Click to phone </a></td>";
									} else {
										if(!empty($value)){
									    	$table .=  "<td>".$value."</td>";
									    }else{
									    	$table .=  "<td> - </td>";
									    }
									}
	    						}
								
							}
					}
			}
			$table .=  "<td><a href='#' data-id='".$result->id."' class='deleteItem'> Delete </a></td>";
			$table .=  "</tr>";

			$i++;
		}
	$table .='</tbody>
	<tfoot>
	<tr>';
	foreach ($column_results as $column) {
			if(!empty($column->label)){
				$table .=  "<th>".$column->label."</th>";
			}
	}
	$table .=  "<th>Action</th>";
	$table .='</tr>
	</tfoot>
</table>';

echo $table;

?>

 </div><!-- ad-listing -->



</div>



</div>

<?php } ?>