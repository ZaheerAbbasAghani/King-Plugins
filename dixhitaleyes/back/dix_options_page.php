<?php
// create custom plugin settings menu
add_action('admin_menu', 'dix_plugin_create_menu');

function dix_plugin_create_menu() {

	//create new top-level menu
	add_menu_page('Dixhitaleyes Settings', 'Dixhitaleyes Settings', 'manage_options', 'dix_dixhitaleyes_settings', 'dix_plugin_settings_page', 'dashicons-database-import', 25);

	//call register settings function
	add_action( 'admin_init', 'register_dix_plugin_settings' );
}


function register_dix_plugin_settings() {
	//register our settings
	register_setting( 'dix-plugin-settings-group', 'dix_import_url' );
	register_setting( 'dix-plugin-settings-group', 'dix_username' );
	register_setting( 'dix-plugin-settings-group', 'dix_password' );
    register_setting( 'dix-plugin-settings-group', 'dix_search_result_page' );
}

function dix_plugin_settings_page() {
?>
<div class="wrap" style="background: #fff;padding: 10px 20px;height: auto;overflow: hidden;">
<h1>Dixhitaleyes Settings </h1><hr>

<?php settings_errors(); ?>

<div class="LeftSide">

    <form method="post" action="options.php">
        <?php settings_fields( 'dix-plugin-settings-group' ); ?>
        <?php do_settings_sections( 'dix-plugin-settings-group' ); ?>
        <table class="form-table">
            <tr valign="top">
            <th scope="row">URL </th>
            <td><input type="text" name="dix_import_url" value="<?php echo esc_attr( get_option('dix_import_url') ); ?>" style="width:100%;"/></td>
            </tr>
             
            <tr valign="top">
            <th scope="row">Username</th>
            <td><input type="text" name="dix_username" value="<?php echo esc_attr( get_option('dix_username') ); ?>" style="width:100%;"/></td>
            </tr>
            
            <tr valign="top">
            <th scope="row">Password</th>
            <td><input type="text" name="dix_password" value="<?php echo esc_attr( get_option('dix_password') ); ?>" style="width:100%;"/></td>
            </tr>


            <tr valign="top">
            <th scope="row">Shortcode</th>
            <td> Use this shortcode <b>[dixSlider]</b> to display item carousel </td>
            </tr>

            <tr valign="top">
            <th scope="row">Shortcode</th>
            <td> Use this shortcode <b>[dixSearchForm]</b> to display item search bar </td>
            </tr>

            <tr valign="top">
            <th scope="row">Shortcode</th>
            <td> Use this shortcode <b>[dixSearchReults]</b> to display item search results </td>
            </tr>

            <tr valign="top">
            <th scope="row">Shortcode</th>
            <td> Use this shortcode <b>[dixNews]</b> to display list of news </td>
            </tr>

            <tr valign="top">
            <th scope="row"> Search Result Page</th>
            <?php $options = get_option( 'dix_search_result_page' ); ?>
                <td> <select name="dix_search_result_page[page_id]">
                            <?php
                            if( $pages = get_pages() ){
                                foreach( $pages as $page ){
                                    echo '<option value="' . $page->ID . '" ' . selected( $page->ID, $options['page_id'] ) . '>' . $page->post_title . '</option>';
                                }
                            }
                            ?>
                    </select>  
                </td>
            </tr>

        

            <tr valign="top">
            <th scope="row">Import Manually</th>
            <td><a href="#" class="button button-default importManually"> Click once to Import </a>

            <div class="response"></div>

            </td>
            </tr>


            
        </table>
        
        <?php submit_button(); ?>

    </form>

</div><!-- left -->

<div class="RightSide">

<?php 

global $wpdb; 
$table_name = $wpdb->base_prefix.'dix_imported_data';

?>

<table id="example" class="display" style="width:100%">
<thead>
    <tr>
        <th>Item ID</th>
        <th>Name</th>
        <th>Badge</th>
        <th>Category</th>
    </tr>
</thead>
<tbody>

<?php 
$query = "SELECT * FROM $table_name";
$query_results = $wpdb->get_results($query, ARRAY_A);



if(count($query_results) != 0) { 

$category = array("New & Best Seller","GOLD","SILVER","PLATINUM","COLLECTIBLES","RARE EARTHS");

$flags = array("Sale","New","Best Price","Top Seller","Low Stock");


    foreach ($query_results as $key => $value) {
        
       

    ?>

        <tr>
            <td><?php echo $value['item_id']; ?></td>
            <td data-id='<?php echo $value['id']; ?>' data-label='item_name'><?php echo $value['item_name']; ?></td>

             <td data-id='<?php echo $value['id']; ?>' data-label='item_badge'>
                <select class="dixFlags" data-id="<?php echo $value['id']; ?>">
                    <option value=""> Select a Flag </option>
                    
                    <?php
                    foreach ($flags as $flag) {
                        if(!empty($flag)){
                            $item_badge = $value['item_badge'] == $flag ? "selected" : "";
                            echo "<option value='".$flag."' ".$item_badge."> $flag </option>";
                        }else{
                            echo "<option value=''> - </option>";
                        }
                    }

                     ?>
                </select>
            </td>
            
            <td data-id='<?php echo $value['id']; ?>' data-label='item_name'>
                <select class="dixCategory" data-id="<?php echo $value['id']; ?>">

                <option value=""> Select a category </option>
                    
                <?php

                foreach ($category as $cat) {
                    if(!empty($cat)){
                        $item_type = $value['item_type'] == $cat ? "selected" : "";
                        echo "<option value='".$cat."' ".$item_type."> $cat </option>";
                    }else{
                        echo "<option value=''> - </option>";
                    }
                }

                 ?>
            </select></td>
          
        </tr>


    <?php 

    }


    //print_r($category);


}else{
    echo "<tr><td colspan='4' style='text-align:center;'>Noting Found!</td></tr>";
}
?>

</tbody>
<tfoot>
    <tr>
        <th>Item ID</th>
        <th>Name</th>
        <th>Badge</th>
        <th>Category</th>
    </tr>
</tfoot>
</table>


</div><!-- Right -->

</div>

<?php 

/*$url = esc_attr(get_option('dix_import_url'));
$username = esc_attr(get_option('dix_username'));
$password = esc_attr(get_option('dix_password'));


$args = array(
'headers' => array(
'Authorization' => 'Basic ' . base64_encode( $username . ':' . $password )
)
);
$request=wp_remote_request( $url, $args );

if( is_wp_error( $request ) ) {
    return false; // Bail early
}

$body = wp_remote_retrieve_body( $request );
$data = json_decode( $body );

echo "<pre>";
    print_r($data);
echo "</pre>";*/

?>


<?php } ?>