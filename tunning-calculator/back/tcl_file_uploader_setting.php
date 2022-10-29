<?php
// create custom plugin settings menu
add_action('admin_menu', 'tcl_plugin_create_menu');

function tcl_plugin_create_menu() {

	//create new top-level menu
	add_menu_page('Tunning Calculator Plugin Settings', 'Tunning Calculator', 'manage_options', 'tunning_calculator', 'tcl_plugin_settings_page', 'dashicons-calculator',30);
    //call register settings function
    add_action( 'admin_init', 'register_tcl_plugin_settings' );
}

function register_tcl_plugin_settings() {
    //register our settings
    register_setting('my-tcl-settings-group', 'max_limit' );
    register_setting('my-tcl-settings-group', 'tcl_button_text' );
    register_setting('my-tcl-settings-group', 'tcl_gauge_background' );
    register_setting('my-tcl-settings-group', 'tcl_background' );
    register_setting('my-tcl-settings-group', 'tcl_background_fill' );
    register_setting('my-tcl-settings-group', 'tcl_power_labels' );
    register_setting('my-tcl-settings-group', 'tcl_torque_labels' );
    register_setting('my-tcl-settings-group', 'image_1_upload' );
    register_setting('my-tcl-settings-group', 'image_2_upload' );
    register_setting('my-tcl-settings-group', 'image_3_upload' );


}



function tcl_plugin_settings_page() {
?>
<div class="wrap" style="background: #fff;padding: 10px 20px;height: auto;overflow: hidden;">
<h1>Tunning Calculator</h1><i>Shortcode: tunning_details</i><hr> 

<form method="post" action="" enctype="multipart/form-data" style="float: left;">

    <table class="form-table">
        <tr valign="top">
        <th scope="row">Upload XLSX File </th>
        <td><input type="file" name="xlsx_file_uploader" /></td>
        </tr>
    </table>
    
    <input type="submit" name="sub">

</form>



<form method="post" action="options.php" style="float: left;width: 50%;background: #eee;padding: 0px 10px;box-shadow: 2px 2px 5px #ddd,-2px -2px 5px #ddd;">
    <?php settings_fields( 'my-tcl-settings-group' ); ?>
    <?php do_settings_sections('my-tcl-settings-group'); ?>
    <table class="form-table">
     
     	 <tr valign="top">
        <th scope="row">Max Limit</th>
        <td><input type="text" name="max_limit" value="<?php echo esc_attr( get_option('max_limit') ); ?>" style="width:100%;"/></td>
        </tr>
     
        <tr valign="top">
        <th scope="row">Button Text</th>
        <td><input type="text" name="tcl_button_text" value="<?php echo esc_attr( get_option('tcl_button_text') ); ?>" style="width:100%;"/></td>
        </tr>

        <tr valign="top">
        <th scope="row">Gauge Background Inner</th>
        <td>
            <input type="text" name="tcl_gauge_background" value="<?php echo esc_attr( get_option('tcl_gauge_background') ); ?>" class="my-color-field" data-default-color="#effeff" style="width:100%;"/></td>
        </tr>

        <tr valign="top">
        <th scope="row">Gauge Background</th>
        <td>
            <input type="text" name="tcl_background" value="<?php echo esc_attr( get_option('tcl_background') ); ?>" class="my-color-field2" data-default-color="#effeff" style="width:100%;"/><br><i>For better results make same color as background. </i></td>
        </tr>

        <tr valign="top">
        <th scope="row">Gauge Fill</th>
        <td>
            <input type="text" name="tcl_background_fill" value="<?php echo esc_attr( get_option('tcl_background_fill') ); ?>" class="my-color-field3" data-default-color="#effeff" style="width:100%;"/></td>
        </tr>
        
        <tr valign="top">
        <th scope="row">Power Label </th>
        <td><input type="text" name="tcl_power_labels" value="<?php echo esc_attr( get_option('tcl_power_labels') ); ?>" style="width:100%;"/></td>
        </tr>
        
        <tr valign="top">
        <th scope="row">Torque Label </th>
        <td><input type="text" name="tcl_torque_labels" value="<?php echo esc_attr( get_option('tcl_torque_labels') ); ?>" style="width:100%;"/></td>
        </tr>

    	<tr valign="top">
        <th scope="row">Delete all records</th>
        <td><input type="button"  class="button" value="Delete All" id="tcl_delete_all" style="background: red;border: none;color: #fff;"> </td>
        </tr>
        
    
    </table>
    
    <?php submit_button(); ?>

</form>


<?php 

/*global $wpdb;
$table_name = $wpdb->base_prefix.'tcl_tunning_calculator';
$query = "SELECT * FROM $table_name";

$query_results = $wpdb->get_results($query);*/

/*echo "<pre>";
print_r($query_results);
echo "</pre>";*/


if(isset($_POST['sub'])){

    require_once __DIR__.'/simple-xlsx-2020-05-19/src/SimpleXLSX.php';
    $target_dir = wp_upload_dir();

    $file = $_FILES['xlsx_file_uploader']['name'];
    $path = pathinfo($file);
    $num = rand(0,1000);
    $filename = str_replace(' ','',$path['filename']).'_'.$num;
    $ext = $path['extension'];
    $temp_name = $_FILES['xlsx_file_uploader']['tmp_name'];
    $path_filename_ext = $target_dir['path'].'/'.$filename.".".$ext;
 
    // Check if file already exists
    if (file_exists($path_filename_ext)) {
        echo "Sorry, file already exists.";
    }else{
        move_uploaded_file($temp_name,$path_filename_ext);
       // echo '<pre>';
        if ( $xlsx = SimpleXLSX::parse($path_filename_ext) ) {
            
            global $wpdb;
            $table_name = $wpdb->base_prefix.'tcl_tunning_calculator';
            echo "<h3>Below Records Inserted or already exists. </h3><table border='1'>";
            foreach ($xlsx->rows() as $key => $value) {
                if($key == 0){
                    echo "<tr>
                        <th>".$value[0]."</th>
                        <th>Status</th>
                    </tr>";
                }else{


            $query = "SELECT * FROM $table_name WHERE make='$value[0]' AND model='$value[1]' AND trrim='$value[2]' AND stock_power='$value[4]' AND stock_torque='$value[5]' AND stage_1_power='$value[6]' AND stage_1_torque='$value[7]' AND stage_2_power='$value[8]' AND stage_2_torque='$value[9]' AND stage_1_price='$value[10]' AND stage_2_price='$value[11]' AND dynograph='$value[12]' AND learnmoreUrl='$value[13]' AND learnmoreUrl='$value[14]' ";

            $query_results = $wpdb->get_results($query);

                if(count($query_results) == 0) {
                    $rowResult=$wpdb->insert($table_name, 
                        array("make" => $value[0], 
                            "model"=> $value[1],
                            "trrim"=> $value[2],
                            "stock_power"=> $value[4],
                            "stock_torque"=> $value[5],
                            "stage_1_power"=> $value[6],
                            "stage_1_torque"=> $value[7],
                            "stage_2_power"=> $value[8],
                            "stage_2_torque"=> $value[9],
                            "stage_1_price"=> $value[10],
                            "stage_2_price"=> $value[11],
                            "Stage1img"=> $value[12],
                            "Stage2img"=> $value[13],
                            "learnmoreUrl"=> $value[14],
                        ),
                        array("%s","%s","%s","%s","%d","%d","%d","%d","%d","%d","%d","%s","%s","%s")
                    );


                        echo "<tr>
                            <td>".$value[0]."</td>
                            <td><b> Added. </b></td>
                        
                        </tr>";


                }else{
                        echo "<tr>
                            <td>".$value[0]."</td>
                            <td><b> Already Exists. </b></td>
                        </tr>";
                }
				
            }

      

            }
      echo "</table>";

        } else {
            echo SimpleXLSX::parseError();
        }
       // echo '</pre>';
    }

    unlink($path_filename_ext);

}
?>

</div>
<?php } ?>