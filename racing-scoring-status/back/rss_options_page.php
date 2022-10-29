<?php
// create custom plugin settings menu
add_action('admin_menu', 'rss_plugin_create_menu');

function rss_plugin_create_menu() {

	//create new top-level menu
	add_menu_page('Racing Scoring Status Settings', 'Racing Scoring Status', 'manage_options', 'rss_racing_scoring_status', 'rss_plugin_settings_page', 'dashicons-flag', 25 );

	//call register settings function
	add_action( 'admin_init', 'register_rss_plugin_settings' );
}


function register_rss_plugin_settings() {
	//register our settings
	register_setting( 'rss-plugin-settings-group', 'rss_api_url' );
  register_setting( 'rss-plugin-settings-group', 'rss_api_key' );
  register_setting( 'rss-plugin-settings-group', 'rss_number_of_features');
  register_setting( 'rss-plugin-settings-group', 'rss_number_of_top1');
  register_setting( 'rss-plugin-settings-group', 'rss_number_of_top3');
  register_setting( 'rss-plugin-settings-group', 'rss_number_of_top5');
  register_setting( 'rss-plugin-settings-group', 'rss_point_difference');
	register_setting( 'rss-plugin-settings-group', 'rss_update_how_often');
}

function rss_plugin_settings_page() {
    settings_errors();
?>
<div class="wrap" style="background: #fff; padding: 10px 20px;">
<h1> Racing Scoring Status </h1><hr>

<form method="post" action="options.php">
    <?php settings_fields( 'rss-plugin-settings-group' ); ?>
    <?php do_settings_sections( 'rss-plugin-settings-group' ); ?>
    <table class="form-table">

        <tr valign="top">
        <th scope="row">API URL</th>
        <td><input type="text" name="rss_api_url" value="<?php echo esc_attr( get_option('rss_api_url') ); ?>" style="width: 100%;"/></td>
        </tr>

        <tr valign="top">
        <th scope="row">API KEY</th>
        <td><input type="text" name="rss_api_key" value="<?php echo esc_attr( get_option('rss_api_key') ); ?>" style="width: 100%;"/></td>
        </tr>

        <tr valign="top">
        <th scope="row">Display Number of Features</th>
        <td><input type="checkbox" name="rss_number_of_features" value="1" <?php checked(1, get_option('rss_number_of_features'), true); ?> /></td>
        </tr>

        <tr valign="top">
        <th scope="row">Display Number of Top 1</th>
        <td><input type="checkbox" name="rss_number_of_top1" value="1" <?php checked(1, get_option('rss_number_of_top1'), true); ?> /></td>
        </tr>

        <tr valign="top">
        <th scope="row">Display Number of Top 3</th>
        <td><input type="checkbox" name="rss_number_of_top3" value="1" <?php checked(1, get_option('rss_number_of_top3'), true); ?> /></td>
        </tr>

        <tr valign="top">
        <th scope="row">Display Number of Top 5</th>
        <td><input type="checkbox" name="rss_number_of_top5" value="1" <?php checked(1, get_option('rss_number_of_top5'), true); ?> /></td>
        </tr>

        <tr valign="top">
        <th scope="row">Display Point Difference</th>
        <td><input type="checkbox" name="rss_point_difference" value="1" <?php checked(1, get_option('rss_point_difference'), true); ?> /></td>
        </tr>

        <?php $options = get_option( 'rss_update_how_often' ); ?>
        <tr valign="top">
        <th scope="row">How often update data?</th>
        <td> <select name="rss_update_how_often[rss_time_period]" value="<?php esc_attr_e( $options['rss_time_period'] ); ?>">
            <option value="72" <?php selected('72' == $options['rss_time_period'] ); ?>>Three Hours</option>
            <option value="24" <?php selected('24' == $options['rss_time_period'] ); ?>>Daily</option>
            <option value="7" <?php selected('7' == $options['rss_time_period'] ); ?>>Weekly</option>
           
          </select>
        </td>
        </tr>



        <tr valign="top">
        <th scope="row">Action</th>
        <td><a href="#" class="button button-default rss_update_now"> Create New Data </a></td>
        </tr>


          
         
    </table>
  
    <?php submit_button(); ?>

</form>
</div>
<?php 

/*$wp_request_url =  esc_attr( get_option('rss_api_url') );
$username =  esc_attr( get_option('rss_device') );

$wp_request_headers = array(
  'ApiKey' => get_option('rss_api_key')
);
$wp_response = wp_remote_request(
  $wp_request_url,
  array(
      'method'    => 'GET',
      'headers'   => $wp_request_headers
  )
);

echo "<pre>";
print_r(json_decode($wp_response['body']));
echo "</pre>";*/

/*
$data = get_transient( 'rss_divisions' );

echo "<pre>";
print_r($data);
echo "</pre>";
*/


//delete_option( 'rss_update_how_often' );
 //$d = delete_transient( 'rss_divisions' );

 //echo $d;


} ?>