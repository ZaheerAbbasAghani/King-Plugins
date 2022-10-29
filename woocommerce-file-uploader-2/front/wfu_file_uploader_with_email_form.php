<?php 

// Display additional product fields (+ jQuery code)
add_action( 'woocommerce_before_add_to_cart_button', 'display_additional_product_fields', 9 );
function display_additional_product_fields(){
	/* $ed = get_post_meta( get_the_ID(), '_enable_disable_de', true);
	if($ed == "yes"){ */ ?>
	
 	<p class="form-row validate-required" id="image" >
        <label for="file_field"><?php echo __("Upload Image") . ': '; ?>
            <input type='file' name='image' accept='image/*' required>
        </label>
    </p>

<?php /*
    <p class="form-row validate-required" id="image" >
        <label for="file_field"><?php echo __("Email") . ': '; ?>
            <input type='email' name='email_address' required>
        </label>
    </p>

 ?>

	<?php 
	}else{
    ?>
   
    <?php
	} //endelse

    */
}


// Add custom fields data as the cart item custom data
add_filter( 'woocommerce_add_cart_item_data', 'add_custom_fields_data_as_custom_cart_item_data', 10, 2 );
function add_custom_fields_data_as_custom_cart_item_data( $cart_item, $product_id ){
	//echo $_FILES['image']['size'];
	$up = esc_attr( get_option('new_option_name') );
	$upsize = $up."000000";
if ($_FILES['image']['size'] <= $upsize) {     
    if( isset($_FILES['image']) && ! empty($_FILES['image']) ) {
        $upload       = wp_upload_bits( $_FILES['image']['name'], null, file_get_contents( $_FILES['image']['tmp_name'] ) );
        $filetype     = wp_check_filetype( basename( $upload['file'] ), null );
        $upload_dir   = wp_upload_dir();
        $upl_base_url = is_ssl() ? str_replace('http://', 'https://', $upload_dir['baseurl']) : $upload_dir['baseurl'];
        $base_name    = basename( $upload['file'] );

        $cart_item['file_upload'] = array(
            'guid'      => $upl_base_url .'/'. _wp_relative_upload_path( $upload['file'] ), // Url
            'file_type' => $filetype['type'], // File type
            'file_name' => $base_name, // File name
            'title'     => ucfirst( preg_replace('/\.[^.]+$/', '', $base_name ) ), // Title
        );
        $cart_item['unique_key'] = md5( microtime().rand() ); // 
        //$cart_item['save_email_address'] = $_POST['email_address'];
    }
}else{
	echo "Image size limit exceed";
	die();
}


    return $cart_item;
}



function iconic_add_engraving_text_to_cart_item( $cart_item_data, $product_id, $variation_id ) {
    $engraving_text = "HELLO WORLD";

    if ( empty( $engraving_text ) ) {
        return $cart_item_data;
    }

    $cart_item_data['file_upload'] = $engraving_text;

    return $cart_item_data;
}

add_filter( 'woocommerce_add_cart_item_data', 'iconic_add_engraving_text_to_cart_item', 10, 3 );


// Display custom cart item data in cart (optional)
add_filter( 'woocommerce_get_item_data', 'display_custom_item_data', 10, 2 );
function display_custom_item_data( $cart_item_data, $cart_item ) {
    if ( isset( $cart_item['file_upload']['title'] ) ){
        $cart_item_data[] = array(
            'name' => __( 'Image uploaded', 'woocommerce' ),
            'value' =>  $cart_item['file_upload']['title'],
        );
    }

    /*if( isset($cart_item['save_email_address']) ) {
       $cart_item_data[] = array(
            'name' => __( 'Email Address', 'woocommerce' ),
            'value' =>  $cart_item['save_email_address'],
        );
    } */

/*    echo "<pre>";
    print_r($cart_item);
    echo "</pre>";*/


    return $cart_item_data;
}

// Save Image data as order item meta data
add_action( 'woocommerce_checkout_create_order_line_item', 'custom_field_update_order_item_meta', 20, 4 );
function custom_field_update_order_item_meta( $item, $cart_item_key, $values, $order ) {
    if ( isset( $values['file_upload'] ) ){
        $item->update_meta_data( '_img_file',  $values['file_upload'] );
    }

   /* if( isset($values['save_email_address']) ) {
        $key = __('Email', 'woocommerce');
        $value = $values['save_email_address'];
        $item->update_meta_data( $key, $value ,$item->get_id());
    }*/


}


/*add_action( 'woocommerce_thankyou', 'your_wc_autocomplete_order' );

function your_wc_autocomplete_order( $order_id ) {

 if ( ! $order_id ) {
   return;
 }
 //$order = wc_get_order( $order_id );


  $order = new WC_Order ( $order_id );
  $items = $order->get_items();

  foreach ( $items as $item ) {
	$product_id   = $item->get_product_id(); 
	$filename = $item ['_img_file'];
	$email_address = $item['E-Mail'];
	if(!empty($filename) && !empty($email_address)){
		$admin_email=esc_attr( get_option('new_option_name2')); 
		$to = $admin_email;
	  	$subject = $email_address;
	  	
		$body = 'Product: '.get_the_title($product_id)."\n".'Email Address: '.$email_address;
	
		$prefix = substr($subject, 0, strrpos($subject, '@'));

		//$headers[] = 'From: '.ucfirst($prefix).' <'.$subject.'>';
		$headers[] = 'Reply-To:'.ucfirst($prefix).' <'.$subject.'>';
		$parts=parse_url($filename['guid']);
		$path_parts=explode('/', $parts['path']);
		
		$img_url = WP_CONTENT_DIR.'/'.$path_parts[2].'/'.$path_parts[3].'/'.$path_parts[4].'/'.$path_parts[5];
		$attachments = array($img_url);
		add_filter('wp_mail_content_type', create_function('', 'return "text/html"; '));
		wp_mail($to, $subject, $body, $headers,$attachments);
		remove_filter('wp_mail_content_type', 'set_html_content_type');
	
		


	}else{
		echo "Something went wrong.";
	}
  }

}
*/

/*-------------------------------------------*/
/* 5. Adding Custom Field */
/*-------------------------------------------*/

// Add custom fields in "product data" settings metabox ("Advanced" tab)
add_action('woocommerce_product_options_advanced','woocious_add_custom_field_product_dashboard');
function woocious_add_custom_field_product_dashboard(){
    global $post;

     echo '<div class="product_custom_field">';

     // Checkbox Field
     woocommerce_wp_checkbox( array(
             'id'          => '_enable_disable_de',
             'description' =>  __('Select if you want add on services', 'woocious'),
             'label'       => __('Show Upload Fields'),
             'desc_tip'    => 'true',
     ) );


     echo '</div>';
}

// Save Inputted Entries, in the Product Dashboard Text Fields.
add_action('woocommerce_process_product_meta', 'woocommerce_product_custom_fields_save');
 function woocommerce_product_custom_fields_save($post_id){
    // Checkbox Field
    $checkbox=isset( $_POST['_enable_disable_de'] ) ? 'yes' : 'no';
    update_post_meta( $post_id, '_enable_disable_de', $checkbox );

}