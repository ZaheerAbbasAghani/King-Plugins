<?php
// create custom plugin settings menu
add_action('admin_menu', 'tks_plugin_create_menu');

function tks_plugin_create_menu() {

    //create new top-level menu
    add_menu_page('Ticket Seller Settings', 'Ticket Seller Settings', 'manage_options', 'tks_ticket_seller', 'tks_plugin_settings_page', 'dashicons-tickets-alt', 25 );

    //call register settings function
    add_action( 'admin_init', 'register_tks_plugin_settings' );
}


function register_tks_plugin_settings() {
    //register our settings
    register_setting( 'tks-plugin-settings-group', 'tks_get_product_url' );
    register_setting( 'tks-plugin-settings-group', 'tks_ticket_sold_url' );
    register_setting( 'tks-plugin-settings-group', 'tks_username' );
    register_setting( 'tks-plugin-settings-group', 'tks_password' );

    register_setting( 'tks-plugin-settings-group', 'tks_societyId' );
    register_setting( 'tks-plugin-settings-group', 'tks_pointOfSaleId' );
    register_setting( 'tks-plugin-settings-group', 'tks_numberOfTheYear' );
    register_setting( 'tks-plugin-settings-group', 'tks_userId' );
    register_setting( 'tks-plugin-settings-group', 'tks_busAgencyName' );
    register_setting( 'tks-plugin-settings-group', 'tks_subject_name' );
    register_setting(  "tks-plugin-settings-group", "tks_enable_disable_date");
    register_setting(  "tks-plugin-settings-group", "tks_description");
    
    
    
}

function tks_plugin_settings_page() {
    settings_errors();
?>
<div class="wrap" style="background: #fff; padding: 10px 20px;">
<h1>Ticket Seller </h1><hr>

<form method="post" action="options.php">
    <?php settings_fields( 'tks-plugin-settings-group' ); ?>
    <?php do_settings_sections( 'tks-plugin-settings-group' ); ?>
    <table class="form-table">
        <tr valign="top">
        <th scope="row">Fetch Product URL</th>
        <td><input type="text" name="tks_get_product_url" value="<?php echo esc_attr( get_option('tks_get_product_url') ); ?>" style="width: 100%;"/></td>
        </tr>

        <tr valign="top">
        <th scope="row">Ticket Sold URL</th>
        <td><input type="text" name="tks_ticket_sold_url" value="<?php echo esc_attr( get_option('tks_ticket_sold_url') ); ?>" style="width: 100%;"/></td>
        </tr>
         
        <tr valign="top">
        <th scope="row">Username</th>
        <td><input type="text" name="tks_username" value="<?php echo esc_attr( get_option('tks_username') ); ?>" style="width: 100%;"/></td>
        </tr>
        
        <tr valign="top">
        <th scope="row">Password</th>
        <td><input type="text" name="tks_password" value="<?php echo esc_attr( get_option('tks_password') ); ?>" style="width: 100%;"/></td>
        </tr>

        <tr valign="top">
        <th scope="row">Society ID</th>
        <td><input type="text" name="tks_societyId" value="<?php echo esc_attr( get_option('tks_societyId') ); ?>" style="width: 100%;"/></td>
        </tr>

        <tr valign="top">
        <th scope="row">Point of Sale ID</th>
        <td><input type="text" name="tks_pointOfSaleId" value="<?php echo esc_attr( get_option('tks_pointOfSaleId') ); ?>" style="width: 100%;"/></td>
        </tr>

        <tr valign="top">
        <th valign="top">Number of the Year</th>
        <td><input type="text" name="tks_numberOfTheYear" value="<?php echo esc_attr( get_option('tks_numberOfTheYear') ); ?>" style="width: 100%;"/></td>
        </tr>

        <tr valign="top">
        <th valign="top">User ID</th>
        <td><input type="text" name="tks_userId" value="<?php echo esc_attr( get_option('tks_userId') ); ?>" style="width: 100%;"/></td>
        </tr>

        <tr valign="top">
        <th valign="top">Bus Agency Name</th>
        <td><input type="text" name="tks_busAgencyName" value="<?php echo esc_attr( get_option('tks_busAgencyName') ); ?>" style="width: 100%;"/></td>
        </tr>

        <tr valign="top">
        <th valign="top">Email Subject Title</th>
        <td><input type="text" name="tks_subject_name" value="<?php echo esc_attr( get_option('tks_subject_name') ); ?>" style="width: 100%;"/></td>
        </tr>

        <tr valign="top">
        <th valign="top">Description</th>
        <td>
            <textarea name="tks_description" placeholder="Enter fixed text to show on email body" style="width: 100%;" cols="10" rows="5"><?php echo esc_attr( get_option('tks_description') ); ?></textarea>
        </td>
        </tr>

        <tr valign="top">
        <th valign="top">Enable Disable Trip Date</th>
        <td><input type="checkbox" name="tks_enable_disable_date" value="1" <?php checked(1, get_option('tks_enable_disable_date'), true); ?> /></td>
        </tr>


        <tr valign="top">
        <th scope="row">Shortcode</th>
        <td>[product_list]</td>
        </tr>

        <tr valign="top">
        <th scope="row">Create New Products</th>
        <td><a href='#' class="button button-default update_products">Update Products </a> <br><i style="color:#777;">You can update products 2 times per year.</i></td>
        </tr>



        

    </table>
    
    <?php submit_button(); ?>

</form>
</div>
<?php 

/*
$results = get_transient( 'tks_records' );
echo "<pre>";
print_r($results);
echo "</pre>";

foreach ($results as $result) {
    if ( post_exists( $result->Description ) == 0 ) {
        $post_id = wp_insert_post( array(
            'post_title' => $result->Description,
            'post_content' => $result->MainDescription,
            'post_excerpt' => $result->ShortDescription,
            'post_status' => 'publish',
            'post_type' => "product",
        ) );
        wp_set_object_terms( $post_id, 'simple', 'product_type' );
        $product = wc_get_product( $post_id );
        $product->set_price( $result->Price->Price->Amount );
        $product->set_regular_price( $result->Price->Price->Amount );
        $product->set_stock_quantity("100");
        $product->save();

        add_post_meta( $post_id, "BusAgencyId", $result->BusAgencyId);
        add_post_meta( $post_id, "ProductId", $result->ProductId);
        add_post_meta( $post_id, "Rate", $result->Rate);
        add_post_meta( $post_id, "IsAvailable", $result->IsAvailable);
        add_post_meta( $post_id, "RunCounter", $result->RunCounter);
        add_post_meta( $post_id, "validityMinutes", $result->validityMinutes);

    }

}
*/


} ?>