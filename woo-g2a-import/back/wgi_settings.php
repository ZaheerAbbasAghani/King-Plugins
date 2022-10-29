<?php
// create custom plugin settings menu
add_action('admin_menu', 'wgi_plugin_create_menu');

function wgi_plugin_create_menu() {

	//create new top-level menu
	add_menu_page('Woocommerce G2A Import', 'G2A Settings', 'manage_options', 'wgi_g2_settings', 'wgi_plugin_settings_page' ,  'dashicons-database-import', 35);

	//call register settings function
	add_action( 'admin_init', 'register_wgi_plugin_settings' );
}


function register_wgi_plugin_settings() {
    register_setting('wgi-plugin-settings-group','mode_of_operation');
	register_setting( 'wgi-plugin-settings-group', 'g2a_hash_key' );
	register_setting( 'wgi-plugin-settings-group', 'g2a_secret_key' );
    register_setting( 'wgi-plugin-settings-group', 'seller_email' );

    register_setting( 'wgi-plugin-settings-group', 'product_state' );
    register_setting( 'wgi-plugin-settings-group', 'price_markup_type');
    register_setting( 'wgi-plugin-settings-group', 'price_markup_value');
    register_setting( 'wgi-plugin-settings-group', 'auto_import_frequency');
}

function wgi_plugin_settings_page() {
?>
<div class="wrap" style="background: #fff; box-shadow: 2px 2px 2px #ddd; padding: 10px 20px;">
<h1>Woocommerce G2A Import Settings </h1><hr>

<form method="post" action="options.php">
    <?php settings_fields( 'wgi-plugin-settings-group' ); ?>
    <?php do_settings_sections( 'wgi-plugin-settings-group' ); ?>

    <table class="form-table">
        <tr valign="top">
        <?php settings_errors(); ?>
        <th scope="row">Select Mode of Operation</th>
        <?php   $options = get_option( 'mode_of_operation' );  ?>
        <td><select name="mode_of_operation[page_id]" style="width: 100%;">
        <?php
            echo '<option value="Test Mode" ' . selected('Test Mode',$options['page_id'] ) . '> Test Mode </option>';
            echo '<option value="Production Mode" ' . selected('Production Mode',$options['page_id'] ) . '> Production Mode </option>';
        ?>
        </select></td>
        </tr>
         
        <tr valign="top">
        <th scope="row">G2A Hash Key</th>
        <td><input type="text" name="g2a_hash_key" value="<?php echo esc_attr( get_option('g2a_hash_key') ); ?>" style="width: 100%;"/></td>
        </tr>
        
        <tr valign="top">
        <th scope="row">G2A Secret Key</th>
        <td><input type="text" name="g2a_secret_key" value="<?php echo esc_attr( get_option('g2a_secret_key') ); ?>" style="width: 100%;"/></td>
        </tr>

        <tr valign="top">
        <th scope="row">Seller Email</th>
        <td><input type="text" name="seller_email" value="<?php echo esc_attr( get_option('seller_email') ); ?>" style="width: 100%;"/></td>
        </tr>

        <tr valign="top">
        <th scope="row">Product State</th>
        <?php  $options2 = get_option( 'product_state' );  ?>
        <td><select name="product_state[page_id2]" style="width: 100%;">
        <?php
            $post_statuses = get_post_statuses();

            foreach ($post_statuses as $status) {
            	if($status == "published"){
            		$status ="publish";
                	echo '<option value="'.$status.'" ' . selected($status,$options2['page_id2'] ) . '> '.$status.' </option>';
                }else{
                	echo '<option value="'.$status.'" ' . selected($status,$options2['page_id2'] ) . '> '.$status.' </option>';
                }
            }
            
        ?>
        </select></td>
        </tr>

        <tr valign="top">
        <th scope="row">Price Markup Type</th>
         <td><select name="price_markup_type[page_id3]" style="width: 100%;">
        <?php
            echo '<option value="Percentage" ' . selected('Percentage',$options1['page_id3'] ) . '> Percentage </option>';
           // echo '<option value="Production Mode" ' . selected('Production Mode',$options1['page_id3'] ) . '> Production Mode </option>';
        ?>
        </select></td>
        </tr>

        <tr valign="top">
        <th scope="row">Price Markup Value</th>
        <td><input type="text" name="price_markup_value" value="<?php echo esc_attr( get_option('price_markup_value') ); ?>" style="width: 100%;"/></td>
        </tr>

        <tr valign="top">
        <th scope="row">Auto Import Frequency</th>
        <?php  $options1 = get_option( 'auto_import_frequency' );  ?>
        <td><select name="auto_import_frequency[page_id1]" style="width: 100%;">
        <?php
            echo '<option value="15" ' . selected('15',$options1['page_id1'] ) . '> Every 15 minutes </option>';
            echo '<option value="30" ' . selected('30',$options1['page_id1'] ) . '> Every 30 minutes </option>';
            echo '<option value="45" '.selected('45',$options1['page_id1'] ) . '>Every 45 minutes </option>';
               echo '<option value="60" '.selected('60',$options1['page_id1'] ) . '>Every 60 minutes </option>';
        ?>
        </select></td>
        </tr>


    </table>
    
    <?php submit_button(); ?>

</form>
</div>

<?php 




//$envDomain = 'https://api.g2a.com/v1/products'; // for production, domain will be different

$options = get_option( 'mode_of_operation' );
if($options['page_id'] == "Test Mode"){
	$envDomain = 'https://sandboxapi.g2a.com/v1/products';
    $g2aEmail = "sandboxapitest@g2a.com";
    $clientId = 'qdaiciDiyMaTjxMt'; // API Client ID
    $clientSecret = 'b0d293f6-e1d2-4629-8264-fd63b5af3207b0d293f6-e1d2-4629-8264-fd63b5af3207'; // customer Client secret
}else{
	$envDomain = 'https://api.g2a.com/v1/products';
    $g2aEmail = esc_attr( get_option('seller_email') ); // customer email
    $clientId = esc_attr( get_option('g2a_hash_key') ); // API Client ID
    $clientSecret = esc_attr( get_option('g2a_secret_key') ); // customer Client secret
}



$apiKey = hash('sha256', $clientId . $g2aEmail . $clientSecret);

$curl = curl_init();
curl_setopt_array($curl, array(
    CURLOPT_URL => $envDomain,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_TIMEOUT => 30,
    CURLOPT_CUSTOMREQUEST => "GET",
    CURLOPT_HTTPHEADER => array(
        "Authorization: $clientId, $apiKey",
        "Content-Type: application/json",
    ),
));

$response = curl_exec($curl);
$err = curl_error($curl);
$info = curl_getinfo($curl, CURLINFO_CONTENT_LENGTH_DOWNLOAD);
curl_close($curl);

if ($err) {
  echo "cURL Error #:" . $err;
} else {

$json = json_decode($response, true);

$options1 = get_option( 'auto_import_frequency' ); 
$hours = floor($options1['page_id1'] / 60);
$minutes = $options1['page_id1'] % 60;





//echo $minutes;

//print_r($json);

$i=0;
echo "<pre>";
print_r($json['docs']);
echo "</pre>";

/*foreach ($json['docs'] as $result) {

$content = "Processor: ".$result['requirements']['minimal']['reqprocessor']."\n"."Memory: ".$result['requirements']['minimal']['reqmemory']." \n "."Disk Space:".$result['requirements']['minimal']['reqdiskspace']." \n ".$result['requirements']['minimal']['reqsystem']." \n "." Other ".$result['requirements']['minimal']['reqother'];
$percentage = esc_attr( get_option('price_markup_value') );
$price = $result['retail_min_price'] + ($percentage / 100 ) * $result['retail_min_price'];


$post = array(
    'post_author' => get_current_user_id(),
    'post_content' => $content,
    'post_status' => "publish",
    'post_title' => $result['name'],
    'post_parent' => '',
    'post_type' => "product",
    //'post_category' => array($comma_sep)
);

$product = post_exists($result['name']);
if($product == true){
    echo "";
}else{
//Create post
$post_id = wp_insert_post( $post);
if($post_id){

    // Create and set Categories
    foreach ($result['categories'] as $category) {

        $taxonomy = 'product_cat';
        if (term_exists($category['name'], $taxonomy)) {
            $terms = array($category['name']);
            wp_set_object_terms($post_id, $terms, $taxonomy, true);
        } else {
            $term = $category['name'];
            $inserted_term = wp_insert_term( $term, $taxonomy);
            if(!is_wp_error($inserted_term)) {
                wp_set_object_terms( $post_id, $term, $taxonomy, true);
            }
        }

    }


    // Product Thumbnail
    require_once(ABSPATH . 'wp-admin/includes/media.php');
    require_once(ABSPATH . 'wp-admin/includes/file.php');
    require_once(ABSPATH . 'wp-admin/includes/image.php');

    $image_url = $result['coverImage'];
    $image_tmp = download_url($image_url);
     
    $image_size = filesize($image_tmp);
    $image_name = basename($image_url) . ".jpg"; // .jpg optional
    //Download complete now upload in your project
    $file = array(
       'name' => $image_name, // ex: wp-header-logo.png
       'type' => 'image/jpg',
       'tmp_name' => $image_tmp,
       'error' => 0,
       'size' => $image_size
    );

    //This image/file will show on media page...
    $thumb_id = media_handle_sideload( $file, $post_id);
    set_post_thumbnail($post_id, $thumb_id); //optional
    //update_post_meta($post_id, '_product_image_gallery', $thumb_id);

    update_post_meta( $post_id, '_stock', $result['qty'] );
    update_post_meta( $post_id, '_price', $price );
    update_post_meta( $post_id, '_regular_price', $price );

}

} //else end

} //endforeach*/



} //endElse





 } ?>