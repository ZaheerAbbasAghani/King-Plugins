<?php

// create custom plugin settings menu

add_action('admin_menu', 'wcpu_plugin_create_menu');



function wcpu_plugin_create_menu() {



    //create new top-level menu

    add_menu_page('Product Uploader', 'Product Uploader', 'manage_options', 'wcpu_product_uploader', 'wcpu_plugin_settings_page', 'dashicons-upload',25);



    //call register settings function

    add_action( 'admin_init', 'register_wcpu_plugin_settings' );

}





function register_wcpu_plugin_settings() {

    //register our settings

    /*register_setting( 'my-cool-plugin-settings-group', 'new_option_name' );

    register_setting( 'my-cool-plugin-settings-group', 'some_other_option' );

    register_setting( 'my-cool-plugin-settings-group', 'option_etc' );*/

}



function wcpu_plugin_settings_page() {





?>

<div class="wrap" style="background-color:#fff; padding:10px 20px;">

<h1>Product Uploader Settings </h1><hr>



<?php 


/*
if (isset($_POST['submit'])) {

$csv_file = $_FILES['csv_file'];
$csv_to_array = array_map('str_getcsv', file($csv_file['tmp_name']));



global $wpdb;

// All Products
$all_ids = get_posts( array(
    'post_type' => 'product',
    'numberposts' => -1,
    'post_status' => 'publish',
    'fields' => 'ids',
));

$all_products = array();
foreach ( $all_ids as $id ) {
    array_push($all_products, $id);
}


$available_products = array();


foreach ($csv_to_array as $key => $value) {

  if ($key == 0)
    continue;

    $post_title = mb_convert_encoding($value[2], "HTML-ENTITIES", 'ISO-8859-1');

    $BothCategories = explode(">", $value[0]);

    $check_post = get_page_by_title($post_title, OBJECT, 'product');


    if($check_post){
        //echo "Exists <br>";
        array_push($available_products, $check_post->ID);

        $post = [
            'ID'           => $check_post->ID,
            'post_author' => get_current_user_id(),
            'post_title' => $post_title,
            'post_name' => $post_title,
            'post_excerpt' => $value[3],
            'post_content' => $value[4],
            'post_status' => "publish",
            'post_type' => "product",
        ];

        //Create Post

        $product_id = wp_update_post($post);

        update_post_meta($product_id, '_stock_status', 'instock');
        update_post_meta($product_id, '_stock', $value[7]);
        update_post_meta($product_id, '_price', $value[5]);
        update_post_meta( $product_id, '_regular_price', $value[5] );
        update_post_meta( $product_id, '_sku', $value[1] );



    }else{

        $post = [

            'post_author' => get_current_user_id(),
            'post_title' => htmlentities($post_title),
            'post_name' => htmlentities($post_title),
            'post_excerpt' => utf8_encode($value[3]),
            'post_content' => utf8_encode($value[4]),
            'post_status' => "publish",
            'post_type' => "product",
        ];

        //Create Post

        $product_id = wp_insert_post($post);

        $cat_name = utf8_encode($BothCategories[0]); // category name we want to assign the post to 

        $taxonomy = 'product_cat'; // category by default for posts for other custom post types like woo-commerce it is product_cat

        $append = true ;// true means it will add the cateogry beside already set categories. false will overwrite

        //get the category to check if exists

        $cat  = get_term_by('name', $cat_name , $taxonomy);
        //check existence

        if($cat == false){

            //cateogry not exist create it 
            $cat = wp_insert_term($cat_name, $taxonomy);
            $child_cat = wp_insert_term(utf8_encode($BothCategories[1]), $taxonomy, array('description'=> '', 'slug' => sanitize_title(utf8_encode($BothCategories[1])),'parent' => $cat['term_id']));

            $cid = $child_cat['term_id'];


          //  echo "Parent: ".$cid."<br>";

        }else{
           $cid = $BothCategories[1];
           //echo "Child: ".$cid."<br>";
        }



        //setting post category 

        wp_set_object_terms($product_id, array($cid), 'product_cat');
        wp_set_object_terms($product_id, array($value[6]), 'product_tag');

        update_post_meta($product_id, '_stock_status', 'instock');
        update_post_meta($product_id, '_stock', $value[7]);
        update_post_meta($product_id, '_price', $value[5]);
        update_post_meta( $product_id, '_regular_price', $value[5] );
        update_post_meta( $product_id, '_sku', $value[1] );


        // only need these if performing outside of admin environment

        /*require_once(ABSPATH . 'wp-admin/includes/media.php');
        require_once(ABSPATH . 'wp-admin/includes/file.php');
        require_once(ABSPATH . 'wp-admin/includes/image.php');

        // example image
        $image = $value[8];


        $url     = $image;
       // $post_id = 0;
        $desc    = "The WordPress Logo";

        $image = media_sideload_image( $url, $product_id, $desc, 'id' );

        if(is_wp_error($image)) {
            echo $image->get_error_message();
        }else {
            echo $image;
        }


        // magic sideload image returns an HTML image, not an ID
        $media = media_sideload_image($image, $product_id);

       // echo $media."<br>";

        // therefore we must find it so we can set it as featured ID

        if(!empty($media) && !is_wp_error($media)){

            $args = array(
                'post_type' => 'attachment',
                'posts_per_page' => -1,
                'post_status' => 'any',
                'post_parent' => $product_id
            );

            // reference new image to set as featured

            $attachments = get_posts($args);

            if(isset($attachments) && is_array($attachments)){

                foreach($attachments as $attachment){

                    // grab source of full size images (so no 300x150 nonsense in path)

                    $image = wp_get_attachment_image_src($attachment->ID, 'full');

                    // determine if in the $media image we created, the string of the URL exists

                    if(strpos($media, $image[0]) !== false){

                        // if so, we found our image. set it as thumbnail

                        set_post_thumbnail($product_id, $attachment->ID);

                        // only want one image

                        break;

                    }
                }
       
            }
    
        }else{
            echo "Something went wrong";
        }


    } // if not exists



}

$need_to_delete = array_diff($all_products, $available_products);



foreach ($need_to_delete as $delete) {
    wp_delete_post($delete, true);

    delete_post_meta( $delete, '_stock_status', get_post_meta( $delete, '_stock_status', true ));

    delete_post_meta( $delete, '_stock', get_post_meta($delete,'_stock', true ));
    delete_post_meta( $delete, '_price', get_post_meta($delete,'_price', true ));
    delete_post_meta( $delete, '_regular_price', get_post_meta($delete,'_regular_price', true ));
    delete_post_meta( $delete, '_sku', get_post_meta($delete,'_sku', true ));

}


echo "<div style='background:#ddd; padding:10px;'> All Product Uploaded Successfully. </div>";



} else {

echo '<form action="" method="post" enctype="multipart/form-data">';
echo '<input type="file" name="csv_file" required>';
echo '<input type="submit" name="submit" value="submit" class="button button-primary">';
echo '</form>';

}

*/

echo '<form id="upload_csv" method="post" enctype="multipart/form-data">
      <div class="col-md-3">
        <label>Upload More Files</label>
    </div>
    <div class="col-md-4">
        <input type="file" name="marks_file" />
    </div>
    <div class="col-md-5" >
        <input type="button" name="upload" id="upload" value="UPLOAD"  class="button button-primary">
    </div>
    <div class="response"></div>
    <div style= "clear:both"></div>
</form>';

?>





</div>

<?php } ?>